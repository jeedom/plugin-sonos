<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

/* * ***************************Includes********************************* */
require_once __DIR__ . '/../../../../core/php/core.inc.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Icewind\SMB\ServerFactory;
use Icewind\SMB\BasicAuth;

class sonos3 extends eqLogic {
	/*     * *************************Attributs****************************** */

	public static $_widgetPossibility = array(
		'custom' => true,
		'custom::layout' => false,
		'parameters' => array(
			'sub-background-color' => array(
				'name' => 'Couleur de la barre de contrôle',
				'type' => 'color',
				'default' => '#5d9cec',
				'allow_transparent' => true,
				'allow_displayType' => true
			)
		)
	);
	public static $_encryptConfigKey = array('tts_username', 'tts_password');

	/*     * ***********************Methode static*************************** */

	protected static function getSocketPort() {
		return config::byKey('socketport', __CLASS__, 42042);;
	}

	public static function sendToDaemon($params) {
		$deamon_info = self::deamon_info();
		if ($deamon_info['state'] != 'ok') {
			throw new RuntimeException("Le démon n'est pas démarré");
		}
		log::add(__CLASS__, 'debug', 'params to send to daemon:' . json_encode($params));
		$params['apikey'] = jeedom::getApiKey(__CLASS__);
		$payLoad = json_encode($params);
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_connect($socket, '127.0.0.1', self::getSocketPort());
		socket_write($socket, $payLoad, strlen($payLoad));
		socket_close($socket);
	}

	public static function deamon_info() {
		$return = array();
		$return['log'] = '';
		$return['launchable'] = 'ok';
		$return['state'] = 'nok';
		$pid_file = jeedom::getTmpFolder(__CLASS__) . '/daemon.pid';
		if (file_exists($pid_file)) {
			if (@posix_getsid(trim(file_get_contents($pid_file)))) {
				$return['state'] = 'ok';
			} else {
				shell_exec(system::getCmdSudo() . 'rm -rf ' . $pid_file . ' 2>&1 > /dev/null');
			}
		}
		return $return;
	}

	public static function deamon_start() {
		self::deamon_stop();
		$deamon_info = self::deamon_info();
		if ($deamon_info['launchable'] != 'ok') {
			throw new Exception(__('Veuillez vérifier la configuration', __FILE__));
		}

		$path = realpath(__DIR__ . '/../../resources');
		$cmd = system::getCmdPython3(__CLASS__) . " {$path}/sonosd.py";
		$cmd .= ' --loglevel ' . log::convertLogLevel(log::getLogLevel(__CLASS__));
		$cmd .= ' --socketport ' . self::getSocketPort();
		$cmd .= ' --cycle ' . config::byKey('cycle', __CLASS__, 0.5);
		$cmd .= ' --callback ' . network::getNetworkAccess('internal', 'proto:127.0.0.1:port:comp') . '/plugins/sonos3/core/php/jeesonos3.php';
		$cmd .= ' --apikey ' . jeedom::getApiKey(__CLASS__);
		$cmd .= ' --pid ' . jeedom::getTmpFolder(__CLASS__) . '/daemon.pid';
		$cmd .= ' --internalIp ' . network::getNetworkAccess('internal', 'ip');
		log::add(__CLASS__, 'info', 'Lancement démon');
		$result = exec($cmd . ' >> ' . log::getPathToLog(__CLASS__ . '_daemon') . ' 2>&1 &');
		$i = 0;
		while ($i < 10) {
			$deamon_info = self::deamon_info();
			if ($deamon_info['state'] == 'ok') {
				break;
			}
			sleep(1);
			$i++;
		}
		if ($i >= 10) {
			log::add(__CLASS__, 'error', __('Impossible de lancer le démon', __FILE__), 'unableStartDeamon');
			return false;
		}
		message::removeAll(__CLASS__, 'unableStartDeamon');

		return true;
	}

	public static function deamon_stop() {
		$pid_file = jeedom::getTmpFolder(__CLASS__) . '/daemon.pid';
		if (file_exists($pid_file)) {
			log::add(__CLASS__, 'info', 'Arrêt démon');
			$pid = intval(trim(file_get_contents($pid_file)));
			system::kill($pid);
		}
		sleep(1);
		system::kill('sonosd.py');
		// system::fuserk(config::byKey('socketport', __CLASS__));
	}

	public static function interact($_query, $_parameters = array()) {
		if (trim(config::byKey('interact::sentence', __CLASS__)) == '') {
			return null;
		}
		$ok = false;
		$matchs = explode("\n", str_replace('\n', "\n", trim(config::byKey('interact::sentence', __CLASS__))));
		if (count($matchs) == 0) {
			return null;
		}
		$query = strtolower(sanitizeAccent($_query));
		foreach ($matchs as $match) {
			if (preg_match_all('/' . $match . '/', $query)) {
				$ok = true;
			}
		}
		if (!$ok) {
			return null;
		}
		$sonos = null;
		$data = interactQuery::findInQuery('object', $_query);
		/** @var jeeObject */
		$object = $data['object'];
		if (is_object($object)) {
			$founds = $object->getEqLogic(true, false, __CLASS__);
			if (count($founds) != 0) {
				/** @var eqLogic */
				$sonos = $founds[0];
			}
		}
		if ($sonos == null) {
			$data = interactQuery::findInQuery('eqLogic', $_query);
			if (is_object($data['eqLogic'])) {
				/** @var eqLogic */
				$sonos = $data['eqLogic'];
			}
		}
		if ($sonos == null) {
			return null;
		}
		$playlists = json_decode(cache::byKey('sonos3::playlist')->getValue());
		if (is_array($playlists)) {
			foreach ($playlists as $name) {
				if (interactQuery::autoInteractWordFind($data['query'], $name)) {
					$sonos->getCmd(null, 'play_playlist')->execCmd(array('title' => $name));
					return array('reply' => __('Ok j\'ai lancé', __FILE__) . ' : ' . $name);
				}
			}
		}
		$favorites = json_decode(cache::byKey('sonos3::favorites')->getValue());
		if (is_array($favorites)) {
			foreach ($favorites as $name) {
				if (interactQuery::autoInteractWordFind($data['query'], $name)) {
					$sonos->getCmd(null, 'play_favorite')->execCmd(array('title' => $name));
					return array('reply' => __('Ok j\'ai lancé', __FILE__) . ' : ' . $name);
				}
			}
		}
		return array('reply' => 'Playlist ou favoris non trouvé');
	}

	public static function createSonos($controllers) {
		$speakers_array = array();
		foreach ($controllers as $ip => $controller) {
			$speakers_array[$ip] = $controller['zone_name'];
			/** @var sonos3 */
			$eqLogic = self::byLogicalId($ip, __CLASS__);
			if (!is_object($eqLogic)) {
				log::add(__CLASS__, 'info', "Create new controller: {$ip}");
				$eqLogic = new self();
				$eqLogic->setLogicalId($ip);
				$eqLogic->setName($controller['zone_name'] . ' - ' . $controller['model_name']);
				$eqLogic->setEqType_name(__CLASS__);
				$eqLogic->setIsVisible(1);
				$eqLogic->setIsEnable(1);
				$eqLogic->save();

				try {
					$object = jeeObject::byName($controller['zone_name']);
					if (is_object($object)) {
						$eqLogic->setObject_id($object->getId());
						$eqLogic->save(true);
					}
				} catch (\Throwable $th) {
				}
			}
			$eqLogic->setConfiguration('model_name', $controller['model_name']);
			$eqLogic->setConfiguration('model_number', $controller['model_number']);
			$eqLogic->setConfiguration('software_version', $controller['software_version']);
			$eqLogic->setConfiguration('hardware_version', $controller['hardware_version']);
			$eqLogic->setConfiguration('serial_number', $controller['serial_number']);
			$eqLogic->setConfiguration('uid', $controller['uid']);
			$eqLogic->setConfiguration('display_version', $controller['display_version']);
			$eqLogic->setConfiguration('mac_address', $controller['mac_address']);
			$eqLogic->save(true);

			$eqLogic->createCommands();
		}
		$eqLogics = eqLogic::byType(__CLASS__);
		foreach ($eqLogics as $eqLogic) {
			$eqLogic->setConfiguration('speakers', json_encode($speakers_array));
			$eqLogic->save(true);
			$eqLogic->getCmd('action', 'join')->setDisplay('title_possibility_list', json_encode(array_values($speakers_array)))->save(true);
			$eqLogic->getCmd('action', 'unjoin')->setDisplay('title_possibility_list', json_encode(array_values($speakers_array)))->save(true);
		}
	}

	private static function getShuffleState($playModeState) {
		return in_array($playModeState, ['SHUFFLE_NOREPEAT', 'SHUFFLE', 'SHUFFLE_REPEAT_ONE']);
	}

	private static function getRepeatState($playModeState) {
		return in_array($playModeState, ['SHUFFLE', 'REPEAT_ALL', 'SHUFFLE_REPEAT_ONE', 'REPEAT_ONE']);
	}

	public static function async_get_track_image($options) {
		$eqLogic = eqLogic::byId($options['eqLogic_id']);
		$cmd_track_image = $eqLogic->getCmd(null, 'local_track_image');

		if (is_object($cmd_track_image)) {
			$old_image_md5 = $cmd_track_image->execCmd();

			if ($options['image_url'] != '') {
				$image_content = file_get_contents($options['image_url']);
				$image_md5 = md5($image_content);

				if ($eqLogic->checkAndUpdateCmd($cmd_track_image, $image_md5)) {
					@unlink(__DIR__ . "/../../../../plugins/sonos3/data/{$old_image_md5}");
					file_put_contents(__DIR__ . "/../../../../plugins/sonos3/data/{$image_md5}", $image_content);
					log::add(__CLASS__, 'debug', "Save local image:" . $options['image_url']);
					$eqLogic->refreshWidget();
				}
			} elseif (file_exists(__DIR__ . "/../../../../plugins/sonos3/data/{$old_image_md5}")) {
				$eqLogic->checkAndUpdateCmd($cmd_track_image, '');
				@unlink(__DIR__ . "/../../../../plugins/sonos3/data/{$old_image_md5}");
			}
		}
	}

	public static function updateSpeakers($speakers) {
		foreach ($speakers as $ip => $data) {
			$eqLogic = self::byLogicalId($ip, __CLASS__);
			if (!is_object($eqLogic)) {
				log::add(__CLASS__, 'warning', "no speaker with ip: {$ip}");
				continue;
			}
			log::add(__CLASS__, 'debug', "update commands of speaker: {$ip}");
			$changed = false;
			$changed = $eqLogic->checkAndUpdateCmd('volume_state', $data['volume']) || $changed;
			$changed = $eqLogic->checkAndUpdateCmd('mute_state', $data['muted']) || $changed;
			$changed = $eqLogic->checkAndUpdateCmd('play_mode_state', $data['media']['play_mode']) || $changed;
			$changed = $eqLogic->checkAndUpdateCmd('playback_status', $data['media']['playback_status']) || $changed;
			$changed = $eqLogic->checkAndUpdateCmd('state', self::convertState($data['media']['playback_status'])) || $changed;
			$changed = $eqLogic->checkAndUpdateCmd('shuffle_state', self::getShuffleState($data['media']['play_mode'])) || $changed;
			$changed = $eqLogic->checkAndUpdateCmd('repeat_state', self::getRepeatState($data['media']['play_mode']), false) || $changed;
			$changed = $eqLogic->checkAndUpdateCmd('track_album', $data['media']['album_name']) || $changed;
			$changed = $eqLogic->checkAndUpdateCmd('track_artist', $data['media']['artist']) || $changed;
			$changed = $eqLogic->checkAndUpdateCmd('track_title', $data['media']['title']) || $changed;
			$changed = $eqLogic->checkAndUpdateCmd('track_image', $data['media']['image_url']) || $changed;
			$changed = $eqLogic->checkAndUpdateCmd('group_state', $data['grouped']) || $changed;
			$changed = $eqLogic->checkAndUpdateCmd('group_name', $data['group_name']) || $changed;

			//save image locally to improve widget display but getting file content can take few seconds so its done async to not block update of all speakers
			//TODO: try to optimize and do it once for all speakers in group
			utils::executeAsync(__CLASS__, 'async_get_track_image', ['eqLogic_id' => $eqLogic->getId(), 'image_url' => $data['media']['image_url']]);

			if ($changed) {
				$eqLogic->refreshWidget();
			}
		}
	}

	public static function convertState($_state) {
		switch ($_state) {
			case 'PLAYING':
				return __('Lecture', __FILE__);
			case 'PAUSED_PLAYBACK':
				return __('Pause', __FILE__);
			case 'STOPPED':
				return __('Arrêté', __FILE__);
		}
		return $_state;
	}

	/**
	 * Save favorites to cache and update 'play_favorite' commands
	 *
	 * @param string[] $favorites
	 * @return void
	 */
	public static function setFavorites(array $favorites) {
		$encoded_favorites = json_encode($favorites);
		cache::set("sonos3::favorites", $encoded_favorites);
		foreach (self::byType(__CLASS__) as $sonos3) {
			$cmd = $sonos3->getCmd('action', 'play_favorite');
			if (is_object($cmd)) {
				$cmd->setDisplay('title_possibility_list', $encoded_favorites);
				$cmd->save();
			}
		}
	}

	public static function setPlaylists($playlists) {
		$encoded_playlists = json_encode($playlists);
		cache::set("sonos3::playlist", $encoded_playlists);
		foreach (self::byType(__CLASS__) as $sonos3) {
			$cmd = $sonos3->getCmd('action', 'play_playlist');
			if (is_object($cmd)) {
				$cmd->setDisplay('title_possibility_list', $encoded_playlists);
				$cmd->save();
			}
		}
	}

	public static function setRadios($radios) {
		foreach (self::byType(__CLASS__) as $sonos3) {
			$cmd = $sonos3->getCmd('action', 'play_radio');
			if (is_object($cmd)) {
				$cmd->setDisplay('title_possibility_list', json_encode($radios));
				$cmd->save();
			}
		}
	}

	/*     * *********************Méthodes d'instance************************* */

	public function preSave() {
		$this->setCategory('multimedia', 1);
	}

	public function migrateConfig() {
		config::remove('playlist', __CLASS__);
		config::remove('favourites', __CLASS__);

		$add = $this->getCmd(null, 'add_speaker');
		if (is_object($add)) {
			$add->remove();
		}
		$remove = $this->getCmd(null, 'remove_speaker');
		if (is_object($remove)) {
			$remove->remove();
		}
		$dominantColor = $this->getCmd(null, 'dominantColor');
		if (is_object($dominantColor)) {
			$dominantColor->remove();
		}
		$dominantColor2 = $this->getCmd(null, 'dominantColor2');
		if (is_object($dominantColor2)) {
			$dominantColor2->remove();
		}

		$line_in = $this->getCmd('action', 'line_in');
		if (is_object($line_in)) {
			$line_in->setLogicalId('switch_to_line_in');
			$line_in->save(true);
		}

		$setVolume = $this->getCmd('action', 'setVolume');
		if (is_object($setVolume)) {
			$setVolume->setLogicalId('volume');
			$setVolume->save(true);
		}
		$volume = $this->getCmd('info', 'volume');
		if (is_object($volume)) {
			$volume->setLogicalId('volume_state');
			$volume->save(true);
		}

		$favorite = $this->getCmd('action', 'play_favourite');
		if (is_object($favorite)) {
			$favorite->setLogicalId('play_favorite');
			$favorite->save(true);
		}
	}

	public function createCommands() {
		$playback_status = $this->getCmd(null, 'playback_status');
		if (!is_object($playback_status)) {
			$playback_status = new sonos3Cmd();
			$playback_status->setLogicalId('playback_status');
			$playback_status->setName(__('Statut de lecture', __FILE__));
			$playback_status->setType('info');
			$playback_status->setSubType('string');
			$playback_status->setEqLogic_id($this->getId());
			$playback_status->save();
		}
		$play_mode_state = $this->getCmd(null, 'play_mode_state');
		if (!is_object($play_mode_state)) {
			$play_mode_state = new sonos3Cmd();
			$play_mode_state->setLogicalId('play_mode_state');
			$play_mode_state->setName(__('Mode de lecture', __FILE__));
			$play_mode_state->setType('info');
			$play_mode_state->setSubType('string');
			$play_mode_state->setEqLogic_id($this->getId());
			$play_mode_state->save();
		}
		$play_mode = $this->getCmd(null, 'play_mode');
		if (!is_object($play_mode)) {
			$play_mode = new sonos3Cmd();
			$play_mode->setLogicalId('play_mode');
			$play_mode->setName(__('Choisir mode de lecture', __FILE__));
			$play_mode->setType('action');
			$play_mode->setSubType('select');
			$play_mode->setConfiguration('listValue', "NORMAL|Normal;REPEAT_ALL|Répéter tout;SHUFFLE|Aléatoire;SHUFFLE_NOREPEAT|Aléatoire sans répétition;REPEAT_ONE|Répéter le morceau;SHUFFLE_REPEAT_ONE|Aléatoire et répéter le morceau");
			$play_mode->setEqLogic_id($this->getId());
			$play_mode->setValue($play_mode_state->getId());
			$play_mode->save();
		}

		$state = $this->getCmd(null, 'state');
		if (!is_object($state)) {
			$state = new sonos3Cmd();
			$state->setLogicalId('state');
			$state->setName(__('Statut', __FILE__));
			$state->setType('info');
			$state->setSubType('string');
			$state->setEqLogic_id($this->getId());
			$state->save();
		}

		$play = $this->getCmd(null, 'play');
		if (!is_object($play)) {
			$play = new sonos3Cmd();
			$play->setLogicalId('play');
			$play->setName(__('Play', __FILE__));
			$play->setGeneric_type('MEDIA_RESUME');
			$play->setType('action');
			$play->setSubType('other');
			$play->setEqLogic_id($this->getId());
			$play->save();
		}

		$stop = $this->getCmd(null, 'stop');
		if (!is_object($stop)) {
			$stop = new sonos3Cmd();
			$stop->setLogicalId('stop');
			$stop->setName(__('Stop', __FILE__));
			$stop->setGeneric_type('MEDIA_STOP');
			$stop->setType('action');
			$stop->setSubType('other');
			$stop->setEqLogic_id($this->getId());
			$stop->save();
		}

		$pause = $this->getCmd(null, 'pause');
		if (!is_object($pause)) {
			$pause = new sonos3Cmd();
			$pause->setLogicalId('pause');
			$pause->setName(__('Pause', __FILE__));
			$pause->setGeneric_type('MEDIA_PAUSE');
			$pause->setType('action');
			$pause->setSubType('other');
			$pause->setEqLogic_id($this->getId());
			$pause->save();
		}

		$next = $this->getCmd(null, 'next');
		if (!is_object($next)) {
			$next = new sonos3Cmd();
			$next->setLogicalId('next');
			$next->setName(__('Suivant', __FILE__));
			$next->setGeneric_type('MEDIA_NEXT');
			$next->setType('action');
			$next->setSubType('other');
			$next->setEqLogic_id($this->getId());
			$next->save();
		}

		$previous = $this->getCmd(null, 'previous');
		if (!is_object($previous)) {
			$previous = new sonos3Cmd();
			$previous->setLogicalId('previous');
			$previous->setName(__('Précédent', __FILE__));
			$previous->setGeneric_type('MEDIA_PREVIOUS');
			$previous->setType('action');
			$previous->setSubType('other');
			$previous->setEqLogic_id($this->getId());
			$previous->save();
		}

		$group_state = $this->getCmd(null, 'group_state');
		if (!is_object($group_state)) {
			$group_state = new sonos3Cmd();
			$group_state->setLogicalId('group_state');
			$group_state->setName(__('Groupe statut', __FILE__));
			$group_state->setType('info');
			$group_state->setSubType('binary');
			$group_state->setEqLogic_id($this->getId());
			$group_state->save();
		}
		$group_name = $this->getCmd(null, 'group_name');
		if (!is_object($group_name)) {
			$group_name = new sonos3Cmd();
			$group_name->setLogicalId('group_name');
			$group_name->setName(__('Nom du groupe', __FILE__));
			$group_name->setType('info');
			$group_name->setSubType('string');
			$group_name->setEqLogic_id($this->getId());
			$group_name->save();
		}

		$mute_state = $this->getCmd(null, 'mute_state');
		if (!is_object($mute_state)) {
			$mute_state = new sonos3Cmd();
			$mute_state->setLogicalId('mute_state');
			$mute_state->setName(__('Muet statut', __FILE__));
			$mute_state->setType('info');
			$mute_state->setSubType('binary');
			$mute_state->setEqLogic_id($this->getId());
			$mute_state->save();
		}
		$mute = $this->getCmd(null, 'mute');
		if (!is_object($mute)) {
			$mute = new sonos3Cmd();
			$mute->setLogicalId('mute');
			$mute->setName(__('Muet', __FILE__));
			$mute->setType('action');
			$mute->setSubType('other');
			$mute->setEqLogic_id($this->getId());
		}
		$mute->setValue($mute_state->getId());
		$mute->save();
		$unmute = $this->getCmd(null, 'unmute');
		if (!is_object($unmute)) {
			$unmute = new sonos3Cmd();
			$unmute->setLogicalId('unmute');
			$unmute->setName(__('Non muet', __FILE__));
			$unmute->setType('action');
			$unmute->setSubType('other');
			$unmute->setEqLogic_id($this->getId());
		}
		$unmute->setValue($mute_state->getId());
		$unmute->save();

		$repeat = $this->getCmd(null, 'repeat');
		if (!is_object($repeat)) {
			$repeat = new sonos3Cmd();
			$repeat->setLogicalId('repeat');
			$repeat->setName(__('Répéter', __FILE__));
			$repeat->setType('action');
			$repeat->setSubType('other');
			$repeat->setEqLogic_id($this->getId());
			$repeat->save();
		}
		$repeat_state = $this->getCmd(null, 'repeat_state');
		if (!is_object($repeat_state)) {
			$repeat_state = new sonos3Cmd();
			$repeat_state->setLogicalId('repeat_state');
			$repeat_state->setName(__('Répéter statut', __FILE__));
			$repeat_state->setType('info');
			$repeat_state->setSubType('binary');
			$repeat_state->setEqLogic_id($this->getId());
			$repeat_state->save();
		}

		$shuffle = $this->getCmd(null, 'shuffle');
		if (!is_object($shuffle)) {
			$shuffle = new sonos3Cmd();
			$shuffle->setLogicalId('shuffle');
			$shuffle->setName(__('Aléatoire', __FILE__));
			$shuffle->setType('action');
			$shuffle->setSubType('other');
			$shuffle->setEqLogic_id($this->getId());
			$shuffle->save();
		}
		$shuffle_state = $this->getCmd(null, 'shuffle_state');
		if (!is_object($shuffle_state)) {
			$shuffle_state = new sonos3Cmd();
			$shuffle_state->setLogicalId('shuffle_state');
			$shuffle_state->setName(__('Aléatoire statut', __FILE__));
			$shuffle_state->setType('info');
			$shuffle_state->setSubType('binary');
			$shuffle_state->setEqLogic_id($this->getId());
			$shuffle_state->save();
		}

		$volume_state = $this->getCmd(null, 'volume_state');
		if (!is_object($volume_state)) {
			$volume_state = new sonos3Cmd();
			$volume_state->setLogicalId('volume_state');
			$volume_state->setName(__('Volume statut', __FILE__));
			$volume_state->setGeneric_type('VOLUME');
			$volume_state->setUnite('%');
			$volume_state->setType('info');
			$volume_state->setSubType('numeric');
			$volume_state->setEqLogic_id($this->getId());
			$volume_state->save();
		}

		$volume = $this->getCmd(null, 'volume');
		if (!is_object($volume)) {
			$volume = new sonos3Cmd();
			$volume->setLogicalId('volume');
			$volume->setName(__('Volume', __FILE__));
			$volume->setGeneric_type('SET_VOLUME');
			$volume->setType('action');
			$volume->setSubType('slider');
			$volume->setValue($volume_state->getId());
			$volume->setEqLogic_id($this->getId());
			$volume->save();
		}

		$track_title = $this->getCmd(null, 'track_title');
		if (!is_object($track_title)) {
			$track_title = new sonos3Cmd();
			$track_title->setLogicalId('track_title');
			$track_title->setName(__('Piste', __FILE__));
			$track_title->setType('info');
			$track_title->setSubType('string');
			$track_title->setEqLogic_id($this->getId());
			$track_title->save();
		}

		$track_artist = $this->getCmd(null, 'track_artist');
		if (!is_object($track_artist)) {
			$track_artist = new sonos3Cmd();
			$track_artist->setLogicalId('track_artist');
			$track_artist->setName(__('Artiste', __FILE__));
			$track_artist->setType('info');
			$track_artist->setSubType('string');
			$track_artist->setEqLogic_id($this->getId());
			$track_artist->save();
		}

		$track_album = $this->getCmd(null, 'track_album');
		if (!is_object($track_album)) {
			$track_album = new sonos3Cmd();
			$track_album->setLogicalId('track_album');
			$track_album->setName(__('Album', __FILE__));
			$track_album->setType('info');
			$track_album->setSubType('string');
			$track_album->setEqLogic_id($this->getId());
			$track_album->save();
		}

		$track_image = $this->getCmd(null, 'track_image');
		if (!is_object($track_image)) {
			$track_image = new sonos3Cmd();
			$track_image->setLogicalId('track_image');
			$track_image->setName(__('Image', __FILE__));
			$track_image->setType('info');
			$track_image->setSubType('string');
			$track_image->setEqLogic_id($this->getId());
			$track_image->save();
		}
		$local_track_image = $this->getCmd(null, 'local_track_image');
		if (!is_object($local_track_image)) {
			$local_track_image = new sonos3Cmd();
			$local_track_image->setLogicalId('local_track_image');
			$local_track_image->setName(__('Image locale', __FILE__));
			$local_track_image->setType('info');
			$local_track_image->setSubType('string');
			$local_track_image->setEqLogic_id($this->getId());
			$local_track_image->save();
		}

		$play_playlist = $this->getCmd(null, 'play_playlist');
		if (!is_object($play_playlist)) {
			$play_playlist = new sonos3Cmd();
			$play_playlist->setLogicalId('play_playlist');
			$play_playlist->setName(__('Jouer playlist', __FILE__));
			$play_playlist->setType('action');
			$play_playlist->setSubType('message');
			$play_playlist->setDisplay('message_placeholder', __('Options', __FILE__));
			$play_playlist->setDisplay('title_placeholder', __('Titre de la playlist', __FILE__));
			$play_playlist->setEqLogic_id($this->getId());
			$play_playlist->save();
		}

		$play_favorite = $this->getCmd(null, 'play_favorite');
		if (!is_object($play_favorite)) {
			$play_favorite = new sonos3Cmd();
			$play_favorite->setLogicalId('play_favorite');
			$play_favorite->setName(__('Jouer favoris', __FILE__));
			$play_favorite->setType('action');
			$play_favorite->setSubType('message');
			$play_favorite->setDisplay('message_placeholder', __('Options', __FILE__));
			$play_favorite->setDisplay('title_placeholder', __('Titre du favoris', __FILE__));
			$play_favorite->setEqLogic_id($this->getId());
			$play_favorite->save();
		}

		$play_radio = $this->getCmd(null, 'play_radio');
		if (!is_object($play_radio)) {
			$play_radio = new sonos3Cmd();
			$play_radio->setLogicalId('play_radio');
			$play_radio->setName(__('Jouer une radio', __FILE__));
			$play_radio->setType('action');
			$play_radio->setSubType('message');
			$play_radio->setDisplay('message_disable', 1);
			$play_radio->setDisplay('title_placeholder', __('Titre de la radio', __FILE__));
			$play_radio->setEqLogic_id($this->getId());
			$play_radio->save();
		}

		$join = $this->getCmd(null, 'join');
		if (!is_object($join)) {
			$join = new sonos3Cmd();
			$join->setLogicalId('join');
			$join->setName(__('Rejoindre un groupe', __FILE__));
			$join->setType('action');
			$join->setSubType('message');
			$join->setDisplay('message_disable', 1);
			$join->setDisplay('title_placeholder', __('Nom de la pièce', __FILE__));
			$join->setEqLogic_id($this->getId());
			$join->save();
		}
		$unjoin = $this->getCmd(null, 'unjoin');
		if (!is_object($unjoin)) {
			$unjoin = new sonos3Cmd();
			$unjoin->setLogicalId('unjoin');
			$unjoin->setName(__('Quitter le groupe', __FILE__));
			$unjoin->setType('action');
			$unjoin->setSubType('other');
			$unjoin->setEqLogic_id($this->getId());
			$unjoin->save();
		}

		$line_in = $this->getCmd(null, 'switch_to_line_in');
		if (!is_object($line_in)) {
			$line_in = new sonos3Cmd();
			$line_in->setLogicalId('switch_to_line_in');
			$line_in->setName(__('Entrée audio analogique', __FILE__));
			$line_in->setType('action');
			$line_in->setSubType('other');
			$line_in->setEqLogic_id($this->getId());
			$line_in->save();
		}

		$tv = $this->getCmd(null, 'switch_to_tv');
		if (!is_object($tv)) {
			$tv = new sonos3Cmd();
			$tv->setLogicalId('switch_to_tv');
			$tv->setName(__('TV', __FILE__));
			$tv->setType('action');
			$tv->setSubType('other');
			$tv->setEqLogic_id($this->getId());
			$tv->save();
		}

		$tts = $this->getCmd(null, 'tts');
		if (!is_object($tts)) {
			$tts = new sonos3Cmd();
			$tts->setLogicalId('tts');
			$tts->setName(__('Dire', __FILE__));
			$tts->setType('action');
			$tts->setSubType('message');
			$tts->setDisplay('title_disable', 0);
			$tts->setDisplay('title_placeholder', __('Volume', __FILE__));
			$tts->setDisplay('message_placeholder', __('Message', __FILE__));
			$tts->setEqLogic_id($this->getId());
			$tts->save();
		}
	}

	public function toHtml($_version = 'dashboard') {
		$replace = $this->preToHtml($_version);
		if (!is_array($replace)) {
			return $replace;
		}
		$version = jeedom::versionAlias($_version);
		$replace['#text_color#'] = $this->getConfiguration('text_color');
		$replace['#version#'] = $_version;

		$cmd_state = $this->getCmd(null, 'state');
		if (is_object($cmd_state)) {
			$replace['#state#'] = $cmd_state->execCmd();
			if ($replace['#state#'] == __('Lecture', __FILE__)) {
				$replace['#state_nb#'] = 1;
			} else {
				$replace['#state_nb#'] = 0;
			}
		}

		foreach ($this->getCmd('action') as $cmd) {
			$replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
			if ($_version != 'mobile' && $cmd->getLogicalId() == 'play_playlist') {
				$replace['#playlist#'] = str_replace(array("'", '+'), array("\'", '\+'), $cmd->getDisplay('title_possibility_list'));
			}
			if ($_version != 'mobile' && $cmd->getLogicalId() == 'play_radio') {
				$replace['#radio#'] = str_replace(array("'", '+'), array("\'", '\+'), $cmd->getDisplay('title_possibility_list'));
			}
			if ($_version != 'mobile' && $cmd->getLogicalId() == 'play_favorite') {
				$replace['#favourite#'] = str_replace(array("'", '+'), array("\'", '\+'), $cmd->getDisplay('title_possibility_list'));
			}
		}

		foreach ($this->getCmd('info') as $cmd) {
			$replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
			$replace['#' . $cmd->getLogicalId() . '#'] = $cmd->execCmd();
		}
		if ($replace['#mute_state#'] == 1) {
			$replace['#mute_id#'] = $replace['#unmute_id#'];
		}

		$cmd_track_artist = $this->getCmd(null, 'track_artist');
		if (is_object($cmd_track_artist)) {
			$replace['#title#'] = $cmd_track_artist->execCmd();
		}

		$cmd_track_album = $this->getCmd(null, 'track_album');
		if (is_object($cmd_track_album)) {
			$replace['#title#'] .= ' - ' . $cmd_track_album->execCmd();
		}
		$replace['#title#'] = trim(trim(trim($replace['#title#']), ' - ' . __('Aucun', __FILE__)));

		$cmd_track_title = $this->getCmd(null, 'track_title');
		if (is_object($cmd_track_title)) {
			$replace['#title#'] .= ' - ' . $cmd_track_title->execCmd();
		}
		$replace['#title#'] = trim(trim(trim($replace['#title#']), '-'));

		if (strlen($replace['#title#']) > 12) {
			$replace['#title#'] = '<marquee behavior="scroll" direction="left" scrollamount="2">' . $replace['#title#'] . '</marquee>';
		}
		if ($_version != 'mobile') {
			$replace['#speakers#'] = str_replace(array("'", '+'), array("\'", '\+'), $this->getConfiguration('speakers'));
		}

		$replace['#thumbnail#'] = 'plugins/sonos3/plugin_info/sonos3_alt_icon.png';
		$cmd_track_image = $this->getCmd(null, 'local_track_image');
		if (is_object($cmd_track_image)) {
			$image = $cmd_track_image->execCmd();
			if ($image != '' && file_exists(__DIR__ . "/../../../../plugins/sonos3/data/{$image}")) {
				$replace['#thumbnail#'] = "plugins/sonos3/data/{$image}";
			}
		}
		return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'eqLogic', __CLASS__)));
	}

	public function getImage() {
		$filename = strtoupper($this->getConfiguration('model_name'));
		if ($filename == '') {
			return parent::getImage();
		}
		$filename = str_replace(':', '', $filename);
		$filename = str_replace(' ', '_', $filename);
		return "plugins/sonos3/core/img/{$filename}.png";
	}

	public static function syncAll() {
		self::sendToDaemon(['action' => 'sync']);
	}

	/*     * **********************Getteur Setteur*************************** */
}

class sonos3Cmd extends cmd {
	/*     * *************************Attributs****************************** */

	public static $_widgetPossibility = array('custom' => false);

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

	public function dontRemoveCmd() {
		return true;
	}

	public function execute($_options = array()) {
		if ($this->getType() == 'info') {
			return;
		}

		/** @var sonos3 */
		$eqLogic = $this->getEqLogic();

		$params = [
			'action' => $this->getLogicalId(),
			'ip' => $eqLogic->getLogicalId()
		];

		switch ($this->getLogicalId()) {
			case 'tts':
				$url = network::getNetworkAccess('internal') . '/core/api/tts.php?apikey=' . jeedom::getApiKey('apitts');
				$text = trim($_options['message']);
				$file_content = file_get_contents($url . '&text=' . urlencode($text));
				$file_name = md5($text) . '.mp3';

				log::add("sonos3", "debug", "get tts file");

				$host = config::byKey('tts_host', 'sonos3');

				$serverFactory = new ServerFactory();
				$auth = new BasicAuth(config::byKey('tts_username', 'sonos3'), null, config::byKey('tts_password', 'sonos3'));
				$server = $serverFactory->createServer($host, $auth);
				$share_name = sanitizeAccent(trim(config::byKey('tts_share', 'sonos3')), " \n\r\t\v\0/");
				$share = $server->getShare($share_name);

				log::add("sonos3", "debug", "get share");

				$path_name = sanitizeAccent(trim(config::byKey('tts_path', 'sonos3')), " \n\r\t\v\0/");
				$fh = $share->write("{$path_name}/{$file_name}");
				fwrite($fh, $file_content);
				fclose($fh);
				log::add("sonos3", "debug", "write file");

				$params['file'] = "//{$host}/{$share_name}/{$path_name}/{$file_name}";
				break;
			case 'play_favorite':
				$favorites = json_decode(cache::byKey('sonos3::favorites')->getValue());
				if (!is_array($favorites) || !in_array($_options['title'], $favorites)) {
					message::add(__CLASS__, "Impossible de lancer \"{$_options['title']}\" sur \"{$eqLogic->getName()}\", le favori n'existe pas.");
					return;
				}
				break;
			case 'play_playlist':
				$playlists = json_decode(cache::byKey('sonos3::playlist')->getValue());
				if (!is_array($playlists) || !in_array($_options['title'], $playlists)) {
					message::add(__CLASS__, "Impossible de lancer \"{$_options['title']}\" sur \"{$eqLogic->getName()}\", la liste de lecture n'existe pas.");
					return;
				}
				break;
		}

		switch ($this->getSubType()) {
			case 'message':
				$params['title'] = $_options['title'] ?? '';
				$params['message'] = $_options['message'] ?? '';
				break;
			case 'slider':
				if ($_options['slider'] < 0) {
					$_options['slider'] = 0;
				} else if ($_options['slider'] > 100) {
					$_options['slider'] = 100;
				}
				$params['slider'] = $_options['slider'];
				break;
			case 'select':
				$params['select'] = $_options['select'];
				break;
		}
		sonos3::sendToDaemon($params);
	}

	/*     * **********************Getteur Setteur*************************** */
}
