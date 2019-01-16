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
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . '/../../vendor/autoload.php';
use duncan3dc\Sonos\Tracks\TextToSpeech;
use duncan3dc\Sonos\Tracks\Track;
use duncan3dc\Sonos\Utils\Directory;
use duncan3dc\Speaker\Providers\JeedomProvider;
use Icewind\SMB\Server;
use League\Flysystem\Filesystem;
use RobGridley\Flysystem\Smb\SmbAdapter;

class sonos3 extends eqLogic {
	/*     * *************************Attributs****************************** */
	
	private static $_sonos = null;
	private static $_eqLogics = null;
	private static $_sonosAddOK = false;
	public static $_widgetPossibility = array(
		'custom' => true,
		'custom::layout' => false,
		'parameters' => array(
			'sub-background-color' => array(
				'name' => 'Couleur de la barre de contrôle',
				'type' => 'color',
				'default' => '#5d9cec',
				'allow_transparent' => true,
				'allow_displayType' => true,
			),
		),
	);
	
	private $_controller = null;
	
	/*     * ***********************Methode static*************************** */
	
	public static function restore() {
		try {
			sonos3::syncSonos();
		} catch (Exception $e) {
			
		}
	}
	
	public static function dependancy_info() {
		$return = array();
		$return['log'] = 'sonos_update';
		$return['state'] = 'ok';
		$return['progress_file'] = jeedom::getTmpFolder('sonos3') . '/dependance';
		if (exec('which smbclient | wc -l') == 0) {
			$return['state'] = 'nok';
		}
		$extensions = get_loaded_extensions();
		if (!in_array('soap', $extensions)) {
			$return['state'] = 'nok';
		}
		if (!in_array('mbstring', $extensions)) {
			$return['state'] = 'nok';
		}
		return $return;
	}
	
	public static function dependancy_install() {
		log::remove(__CLASS__ . '_update');
		return array('script' => dirname(__FILE__) . '/../../resources/install_#stype#.sh ' . jeedom::getTmpFolder('sonos3') . '/dependance', 'log' => log::getPathToLog(__CLASS__ . '_update'));
	}
	
	public static function deamon_info() {
		$return = array();
		$return['log'] = '';
		$return['state'] = 'nok';
		$cron = cron::byClassAndFunction('sonos3', 'pull');
		if (is_object($cron) && $cron->running()) {
			$return['state'] = 'ok';
		}
		$return['launchable'] = 'ok';
		return $return;
	}
	
	public static function deamon_start() {
		self::deamon_stop();
		$deamon_info = self::deamon_info();
		if ($deamon_info['launchable'] != 'ok') {
			throw new Exception(__('Veuillez vérifier la configuration', __FILE__));
		}
		$cron = cron::byClassAndFunction('sonos3', 'pull');
		if (!is_object($cron)) {
			throw new Exception(__('Tache cron introuvable', __FILE__));
		}
		$cron->run();
	}
	
	public static function deamon_stop() {
		$cron = cron::byClassAndFunction('sonos3', 'pull');
		if (!is_object($cron)) {
			throw new Exception(__('Tache cron introuvable', __FILE__));
		}
		$cron->halt();
	}
	
	public static function deamon_changeAutoMode($_mode) {
		$cron = cron::byClassAndFunction('sonos3', 'pull');
		if (!is_object($cron)) {
			throw new Exception(__('Tache cron introuvable', __FILE__));
		}
		$cron->setEnable($_mode);
		$cron->save();
	}
	
	public static function interact($_query, $_parameters = array()) {
		if (trim(config::byKey('interact::sentence', 'sonos3')) == '') {
			return null;
		}
		$ok = false;
		$files = array();
		$matchs = explode("\n", str_replace('\n', "\n", trim(config::byKey('interact::sentence', 'sonos3'))));
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
		if (is_object($data['object'])) {
			$founds = $data['object']->getEqLogic(true, false, 'sonos3');
			if (count($founds) != 0) {
				$sonos = $founds[0];
			}
		}
		if ($sonos == null) {
			$data = interactQuery::findInQuery('eqLogic', $_query);
			if (is_object($data['eqLogic'])) {
				$sonos = $data['eqLogic'];
			}
		}
		if ($sonos == null) {
			return null;
		}
		$playlists = config::byKey('playlist', 'sonos3');
		if (is_array($playlists)) {
			foreach ($playlists as $uri => $name) {
				if (interactQuery::autoInteractWordFind($data['query'], $name)) {
					$sonos->getCmd(null, 'play_playlist')->execCmd(array('title' => $name));
					return array('reply' => __('Ok j\'ai lancé : ', __FILE__) . $name);
				}
			}
		}
		$favourites = config::byKey('favourites', 'sonos3');
		if (is_array($favourites)) {
			foreach ($favourites as $favourite) {
				if (interactQuery::autoInteractWordFind($data['query'], $favourite['name'])) {
					$sonos->getCmd(null, 'play_favourite')->execCmd(array('title' => $favourite['name']));
					return array('reply' => __('Ok j\'ai lancé : ', __FILE__) . $favourite['name']);
				}
			}
		}
		return array('reply' => 'Playlist ou favoris non trouvé');
	}
	
	public static function getSonos($_emptyCache = false) {
		if (self::$_sonos === null || $_emptyCache) {
			self::$_sonos = new duncan3dc\Sonos\Network();
			self::$_sonos->setLogger(log::getLogger('sonos3'));
		}
		return self::$_sonos;
	}
	
	public static function cronDaily() {
		self::deamon_start();
	}
	
	public static function syncSonos() {
		$sonos = self::getSonos(true);
		try {
			$controllers = $sonos->getControllers();
		} catch (\Exception $e) {
			$eqLogics = eqLogic::byType('sonos3');
			if(count($eqLogics) == 0){
				throw $e;
			}
			$ips = array();
			foreach ($eqLogics as $eqLogic) {
				$ips[] = $eqLogic->getLogicalId();
			}
			$controllers = self::getControllerByIp($ips);
		}
		$speakers = sonos3::getSpeaker();
		$speakers_array = array();
		foreach ($speakers as $speaker) {
			$speakers_array[$speaker->getIp()] = $speaker->getRoom();
		}
		foreach ($controllers as $controller) {
			$eqLogic = sonos3::byLogicalId($controller->getIp(), 'sonos3');
			if (!is_object($eqLogic)) {
				$eqLogic = new self();
				$eqLogic->setLogicalId($controller->getIp());
				$eqLogic->setName($controller->getRoom() . ' - ' . $controller->getName());
				$object = object::byName($controller->getRoom());
				if (is_object($object)) {
					$eqLogic->setObject_id($object->getId());
					$eqLogic->setName($controller->getName());
				}
				if (strpos($controller->getName(), 'PLAY:1') !== false) {
					$eqLogic->setConfiguration('model', 'PLAY1');
				} else if (strpos(strtoupper($controller->getName()), 'PLAY:3') !== false) {
					$eqLogic->setConfiguration('model', 'PLAY3');
				} else if (strpos(strtoupper($controller->getName()), 'PLAY:5') !== false) {
					$eqLogic->setConfiguration('model', 'PLAY5');
				} else if (strpos(strtoupper($controller->getName()), 'PLAYBAR') !== false) {
					$eqLogic->setConfiguration('model', 'PLAYBAR');
				} else if (strpos(strtoupper($controller->getName()), 'PLAYBASE') !== false) {
					$eqLogic->setConfiguration('model', 'PLAYBASE');
				} else if (strpos(strtoupper($controller->getName()), 'CONNECT:AMP') !== false) {
					$eqLogic->setConfiguration('model', 'CONNECTAMP');
				} else if (strpos(strtoupper($controller->getName()), 'CONNECT') !== false) {
					$eqLogic->setConfiguration('model', 'CONNECT');
				} else if (strpos(strtoupper($controller->getName()), 'BEAM') !== false) {
					$eqLogic->setConfiguration('model', 'BEAM');
				} else if (strpos(strtoupper($controller->getName()), 'ONE') !== false) {
					$eqLogic->setConfiguration('model', 'ONE');
				}
				$eqLogic->setEqType_name('sonos3');
				$eqLogic->setIsVisible(1);
				$eqLogic->setIsEnable(1);
				$eqLogic->save();
			}
			
			
		}
		$eqLogics = eqLogic::byType('sonos3');
		if(count($eqLogics) != 0){
			foreach ($eqLogics as $eqLogic) {
				$eqLogic->setConfiguration('speakers', json_encode($speakers_array));
				$eqLogic->save();
			}
		}
		
		self::getRadioStations();
		self::getPlayLists();
		self::getFavourites();
		self::deamon_start();
	}
	
	public static function pull($_eqLogic_id = null) {
		if (self::$_eqLogics == null) {
			self::$_eqLogics = self::byType('sonos3');
		}
		foreach (self::$_eqLogics as $eqLogic) {
			if ($_eqLogic_id != null && $_eqLogic_id != $eqLogic->getId()) {
				continue;
			}
			if ($eqLogic->getIsEnable() == 0) {
				$eqLogic->refresh();
				if ($eqLogic->getIsEnable() == 0) {
					continue;
				}
			}
			if ($eqLogic->getLogicalId() == '') {
				continue;
			}
			try {
				$changed = false;
				$controller = $eqLogic->getController();
				if ($controller == null) {
					continue;
				}
				$state = self::convertState($controller->getStateName());
				if ($state == __('Transition', __FILE__)) {
					continue;
				}
				$shuffle = ($controller->getShuffle() == '') ? 0 : $controller->getShuffle();
				$repeat = ($controller->getRepeat() == '') ? 0 : $controller->getRepeat();
				$mute = ($controller->isMuted() == '') ? 0 : $controller->isMuted();
				$track = $controller->getStateDetails();
				if ($controller->isStreaming()) {
					$title = __('Entrée de ligne', __FILE__);
				} else {
					$title = $track->getTitle();
				}
				if ($title == '') {
					$title = __('Aucun', __FILE__);
				}
				$album = $track->getAlbum();
				if ($album == '') {
					$album = __('Aucun', __FILE__);
				}
				$artist = $track->getArtist();
				if ($artist == '') {
					$artist = __('Aucun', __FILE__);
				}
				$changed = $eqLogic->checkAndUpdateCmd('state', $state) || $changed;
				$changed = $eqLogic->checkAndUpdateCmd('volume', $controller->getVolume()) || $changed;
				$changed = $eqLogic->checkAndUpdateCmd('shuffle_state', $shuffle) || $changed;
				$changed = $eqLogic->checkAndUpdateCmd('mute_state', $mute) || $changed;
				$changed = $eqLogic->checkAndUpdateCmd('repeat_state', $repeat) || $changed;
				$changed = $eqLogic->checkAndUpdateCmd('track_title', $title) || $changed;
				$changed = $eqLogic->checkAndUpdateCmd('track_album', $album) || $changed;
				$changed = $eqLogic->checkAndUpdateCmd('track_artist', $artist) || $changed;
				if ($track->getAlbumArt() != '') {
					if ($eqLogic->checkAndUpdateCmd('track_image', $track->getAlbumArt())) {
						file_put_contents(dirname(__FILE__) . '/../../../../plugins/sonos3/sonos_' . $eqLogic->getId() . '.jpg', file_get_contents($track->getAlbumArt()));
						$eqLogic->checkAndUpdateCmd('dominantColor', getDominantColor(dirname(__FILE__) . '/../../../../plugins/sonos3/sonos_' . $eqLogic->getId() . '.jpg'));
						$changed = true;
					}
				} else if (file_exists(dirname(__FILE__) . '/../../../../plugins/sonos3/sonos_' . $eqLogic->getId() . '.jpg')) {
					unlink(dirname(__FILE__) . '/../../../../plugins/sonos3/sonos_' . $eqLogic->getId() . '.jpg');
				}
				if ($changed) {
					$eqLogic->refreshWidget();
				}
				if ($eqLogic->getConfiguration('sonosNumberFailed', 0) > 0) {
					foreach (message::byPluginLogicalId('sonos3', 'sonosLost' . $eqLogic->getId()) as $message) {
						$message->remove();
					}
					$eqLogic->setConfiguration('sonosNumberFailed', 0);
					$eqLogic->save();
				}
			} catch (Exception $e) {
				if ($_eqLogic_id != null) {
					log::add('sonos3', 'error', $e->getMessage());
				} else {
					$eqLogic->refresh();
					if ($eqLogic->getIsEnable() == 0) {
						continue;
					}
					if ($eqLogic->getConfiguration('sonosNumberFailed', 0) == 150) {
						log::add('sonos3', 'error', __('Erreur sur ', __FILE__) . $eqLogic->getHumanName() . ' : ' . $e->getMessage(), 'sonosLost' . $eqLogic->getId());
					} else {
						$eqLogic->setConfiguration('sonosNumberFailed', $eqLogic->getConfiguration('sonosNumberFailed', 0) + 1);
						$eqLogic->save();
					}
				}
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
			case 'TRANSITIONING':
			return __('Transition', __FILE__);
		}
		return $_state;
	}
	
	public static function getPlayLists() {
		$sonos = sonos3::getSonos();
		$playlists = $sonos->getPlaylists();
		$array = array();
		foreach ($playlists as $playlist) {
			$array[$playlist->getUri()] = $playlist->getName();
		}
		config::save('playlist', $array, 'sonos3');
		foreach (sonos3::byType('sonos3') as $sonos3) {
			$cmd = $sonos3->getCmd('action', 'play_playlist');
			if (is_object($cmd)) {
				$cmd->setDisplay('title_possibility_list', json_encode(array_values($array)));
				$cmd->save();
			}
		}
		return $playlists;
	}
	
	public static function getFavourites() {
		$sonos = sonos3::getSonos();
		$favourites = $sonos->getFavourites()->listFavorite();
		config::save('favourites', $favourites, 'sonos3');
		$array = array();
		foreach ($favourites as $favourite) {
			$array[$favourite['uri']] = $favourite['name'];
		}
		foreach (sonos3::byType('sonos3') as $sonos3) {
			$cmd = $sonos3->getCmd('action', 'play_favourite');
			if (is_object($cmd)) {
				$cmd->setDisplay('title_possibility_list', json_encode(array_values($array)));
				$cmd->save();
			}
		}
		return $favourites;
	}
	
	public static function getRadioStations() {
		$radios = sonos3::getSonos()->getRadio()->getFavouriteStations();
		$array = array();
		foreach ($radios as $radio) {
			$array[] = $radio->getTitle();
		}
		foreach (sonos3::byType('sonos3') as $sonos3) {
			$cmd = $sonos3->getCmd('action', 'play_radio');
			if (is_object($cmd)) {
				$cmd->setDisplay('title_possibility_list', json_encode($array));
				$cmd->save();
			}
		}
		return $radios;
	}
	
	public static function getSpeaker() {
		return sonos3::getSonos()->getSpeakers();
	}
	
	public static function getControllerByIp($_ip) {
		$devices = new \duncan3dc\Sonos\Devices\Collection();
		$devices->setLogger(log::getLogger('sonos3'));
		if(is_array($_ip)){
			foreach ($_ip as $ip) {
				$devices->addIp($ip);
			}
		}else{
			$devices->addIp($_ip);
		}
		self::$_sonos = new \duncan3dc\Sonos\Network($devices);
		if(is_array($_ip)){
			$return = array();
			foreach ($_ip as $ip) {
				$return[$ip] = self::$_sonos->getControllerByIp($ip);
			}
			return $return;
		}
		return self::$_sonos->getControllerByIp($_ip);
	}
	
	/*     * *********************Méthodes d'instance************************* */
	
	public function getPlayListsUri($_playlist) {
		$_playlist = trim(trim($_playlist), '"');
		$playlists = config::byKey('playlist', 'sonos3');
		if (is_array($playlists)) {
			foreach ($playlists as $uri => $name) {
				if (strtolower($name) == strtolower($_playlist)) {
					return $uri;
				}
			}
		}
		$sonos = sonos3::getPlayLists();
		$playlists = config::byKey('playlist', 'sonos3');
		if (is_array($playlists)) {
			foreach ($playlists as $uri => $name) {
				if (strtolower($name) == strtolower($_playlist)) {
					return $uri;
				}
			}
		}
		return null;
	}
	
	public function getFavouritesUri($_favourite) {
		$_favourite = trim(trim($_favourite), '"');
		$favourites = config::byKey('favourites', 'sonos3');
		if (is_array($favourites)) {
			foreach ($favourites as $favourite) {
				if (strtolower($favourite['name']) == strtolower($_favourite)) {
					return $favourite;
				}
			}
		}
		$sonos = sonos3::getFavourites();
		$favourites = config::byKey('favourites', 'sonos3');
		if (is_array($favourites)) {
			foreach ($favourites as $favourite) {
				if (strtolower($favourite['name']) == strtolower($_favourite)) {
					return $favourite;
				}
			}
		}
		return null;
	}
	
	public function getController() {
		if ($this->_controller === null) {
			$this->_controller = self::getControllerByIp($this->getLogicalId());
		}
		return $this->_controller;
	}
	
	public function preSave() {
		$this->setCategory('multimedia', 1);
	}
	
	public function postSave() {
		$state = $this->getCmd(null, 'state');
		if (!is_object($state)) {
			$state = new sonos3Cmd();
			$state->setLogicalId('state');
			$state->setName(__('Status', __FILE__));
		}
		$state->setType('info');
		$state->setSubType('string');
		$state->setConfiguration('repeatEventManagement', 'never');
		$state->setEqLogic_id($this->getId());
		$state->save();
		
		$play = $this->getCmd(null, 'play');
		if (!is_object($play)) {
			$play = new sonos3Cmd();
			$play->setLogicalId('play');
			$play->setName(__('Play', __FILE__));
		}
		$play->setType('action');
		$play->setSubType('other');
		$play->setEqLogic_id($this->getId());
		$play->save();
		
		$stop = $this->getCmd(null, 'stop');
		if (!is_object($stop)) {
			$stop = new sonos3Cmd();
			$stop->setLogicalId('stop');
			$stop->setName(__('Stop', __FILE__));
		}
		$stop->setType('action');
		$stop->setSubType('other');
		$stop->setEqLogic_id($this->getId());
		$stop->save();
		
		$pause = $this->getCmd(null, 'pause');
		if (!is_object($pause)) {
			$pause = new sonos3Cmd();
			$pause->setLogicalId('pause');
			$pause->setName(__('Pause', __FILE__));
		}
		$pause->setType('action');
		$pause->setSubType('other');
		$pause->setEqLogic_id($this->getId());
		$pause->save();
		
		$next = $this->getCmd(null, 'next');
		if (!is_object($next)) {
			$next = new sonos3Cmd();
			$next->setLogicalId('next');
			$next->setName(__('Suivant', __FILE__));
		}
		$next->setType('action');
		$next->setSubType('other');
		$next->setEqLogic_id($this->getId());
		$next->save();
		
		$previous = $this->getCmd(null, 'previous');
		if (!is_object($previous)) {
			$previous = new sonos3Cmd();
			$previous->setLogicalId('previous');
			$previous->setName(__('Précédent', __FILE__));
		}
		$previous->setType('action');
		$previous->setSubType('other');
		$previous->setEqLogic_id($this->getId());
		$previous->save();
		
		$mute = $this->getCmd(null, 'mute');
		if (!is_object($mute)) {
			$mute = new sonos3Cmd();
			$mute->setLogicalId('mute');
			$mute->setName(__('Muet', __FILE__));
		}
		$mute->setType('action');
		$mute->setSubType('other');
		$mute->setEqLogic_id($this->getId());
		$mute->save();
		
		$unmute = $this->getCmd(null, 'unmute');
		if (!is_object($unmute)) {
			$unmute = new sonos3Cmd();
			$unmute->setLogicalId('unmute');
			$unmute->setName(__('Non muet', __FILE__));
		}
		$unmute->setType('action');
		$unmute->setSubType('other');
		$unmute->setEqLogic_id($this->getId());
		$unmute->save();
		
		$mute_state = $this->getCmd(null, 'mute_state');
		if (!is_object($mute_state)) {
			$mute_state = new sonos3Cmd();
			$mute_state->setLogicalId('mute_state');
			$mute_state->setName(__('Muet status', __FILE__));
		}
		$mute_state->setType('info');
		$mute_state->setSubType('binary');
		$mute_state->setConfiguration('repeatEventManagement', 'never');
		$mute_state->setEqLogic_id($this->getId());
		$mute_state->save();
		
		$repeat = $this->getCmd(null, 'repeat');
		if (!is_object($repeat)) {
			$repeat = new sonos3Cmd();
			$repeat->setLogicalId('repeat');
			$repeat->setName(__('Répéter', __FILE__));
		}
		$repeat->setType('action');
		$repeat->setSubType('other');
		$repeat->setEqLogic_id($this->getId());
		$repeat->save();
		
		$repeat_state = $this->getCmd(null, 'repeat_state');
		if (!is_object($repeat_state)) {
			$repeat_state = new sonos3Cmd();
			$repeat_state->setLogicalId('repeat_state');
			$repeat_state->setName(__('Répéter status', __FILE__));
		}
		$repeat_state->setType('info');
		$repeat_state->setSubType('binary');
		$repeat_state->setConfiguration('repeatEventManagement', 'never');
		$repeat_state->setEqLogic_id($this->getId());
		$repeat_state->save();
		
		$shuffle = $this->getCmd(null, 'shuffle');
		if (!is_object($shuffle)) {
			$shuffle = new sonos3Cmd();
			$shuffle->setLogicalId('shuffle');
			$shuffle->setName(__('Aléatoire', __FILE__));
		}
		$shuffle->setType('action');
		$shuffle->setSubType('other');
		$shuffle->setEqLogic_id($this->getId());
		$shuffle->save();
		
		$shuffle_state = $this->getCmd(null, 'shuffle_state');
		if (!is_object($shuffle_state)) {
			$shuffle_state = new sonos3Cmd();
			$shuffle_state->setLogicalId('shuffle_state');
			$shuffle_state->setName(__('Aléatoire status', __FILE__));
		}
		$shuffle_state->setType('info');
		$shuffle_state->setSubType('binary');
		$shuffle_state->setConfiguration('repeatEventManagement', 'never');
		$shuffle_state->setEqLogic_id($this->getId());
		$shuffle_state->save();
		
		$volume = $this->getCmd(null, 'volume');
		if (!is_object($volume)) {
			$volume = new sonos3Cmd();
			$volume->setLogicalId('volume');
			$volume->setName(__('Volume status', __FILE__));
		}
		$volume->setUnite('%');
		$volume->setType('info');
		$volume->setSubType('numeric');
		$volume->setConfiguration('repeatEventManagement', 'never');
		$volume->setEqLogic_id($this->getId());
		$volume->save();
		
		$setVolume = $this->getCmd(null, 'setVolume');
		if (!is_object($setVolume)) {
			$setVolume = new sonos3Cmd();
			$setVolume->setLogicalId('setVolume');
			$setVolume->setName(__('Volume', __FILE__));
		}
		$setVolume->setType('action');
		$setVolume->setSubType('slider');
		$setVolume->setValue($volume->getId());
		$setVolume->setEqLogic_id($this->getId());
		$setVolume->save();
		
		$track_title = $this->getCmd(null, 'track_title');
		if (!is_object($track_title)) {
			$track_title = new sonos3Cmd();
			$track_title->setLogicalId('track_title');
			$track_title->setName(__('Piste', __FILE__));
		}
		$track_title->setType('info');
		$track_title->setSubType('string');
		$track_title->setConfiguration('repeatEventManagement', 'never');
		$track_title->setEqLogic_id($this->getId());
		$track_title->save();
		
		$track_artist = $this->getCmd(null, 'track_artist');
		if (!is_object($track_artist)) {
			$track_artist = new sonos3Cmd();
			$track_artist->setLogicalId('track_artist');
			$track_artist->setName(__('Artiste', __FILE__));
		}
		$track_artist->setType('info');
		$track_artist->setSubType('string');
		$track_artist->setConfiguration('repeatEventManagement', 'never');
		$track_artist->setEqLogic_id($this->getId());
		$track_artist->save();
		
		$track_album = $this->getCmd(null, 'track_album');
		if (!is_object($track_album)) {
			$track_album = new sonos3Cmd();
			$track_album->setLogicalId('track_album');
			$track_album->setName(__('Album', __FILE__));
		}
		$track_album->setType('info');
		$track_album->setSubType('string');
		$track_album->setConfiguration('repeatEventManagement', 'never');
		$track_album->setEqLogic_id($this->getId());
		$track_album->save();
		
		$track_position = $this->getCmd(null, 'track_image');
		if (!is_object($track_position)) {
			$track_position = new sonos3Cmd();
			$track_position->setLogicalId('track_image');
			$track_position->setName(__('Image', __FILE__));
		}
		$track_position->setType('info');
		$track_position->setSubType('string');
		$track_position->setConfiguration('repeatEventManagement', 'never');
		$track_position->setEqLogic_id($this->getId());
		$track_position->save();
		
		$play_playlist = $this->getCmd(null, 'play_playlist');
		if (!is_object($play_playlist)) {
			$play_playlist = new sonos3Cmd();
			$play_playlist->setLogicalId('play_playlist');
			$play_playlist->setName(__('Jouer playlist', __FILE__));
		}
		$play_playlist->setType('action');
		$play_playlist->setSubType('message');
		$play_playlist->setDisplay('message_placeholder', __('Options', __FILE__));
		$play_playlist->setDisplay('title_placeholder', __('Titre de la playlist', __FILE__));
		$play_playlist->setEqLogic_id($this->getId());
		$play_playlist->save();
		
		$play_favourite = $this->getCmd(null, 'play_favourite');
		if (!is_object($play_favourite)) {
			$play_favourite = new sonos3Cmd();
			$play_favourite->setLogicalId('play_favourite');
			$play_favourite->setName(__('Jouer favoris', __FILE__));
		}
		$play_favourite->setType('action');
		$play_favourite->setSubType('message');
		$play_favourite->setDisplay('message_placeholder', __('Options', __FILE__));
		$play_favourite->setDisplay('title_placeholder', __('Titre du favoris', __FILE__));
		$play_favourite->setEqLogic_id($this->getId());
		$play_favourite->save();
		
		$play_radio = $this->getCmd(null, 'play_radio');
		if (!is_object($play_radio)) {
			$play_radio = new sonos3Cmd();
			$play_radio->setLogicalId('play_radio');
			$play_radio->setName(__('Jouer une radio', __FILE__));
		}
		$play_radio->setType('action');
		$play_radio->setSubType('message');
		$play_radio->setDisplay('message_disable', 1);
		$play_radio->setDisplay('title_placeholder', __('Titre de la radio', __FILE__));
		$play_radio->setEqLogic_id($this->getId());
		$play_radio->save();
		
		$add_speaker = $this->getCmd(null, 'add_speaker');
		if (!is_object($add_speaker)) {
			$add_speaker = new sonos3Cmd();
			$add_speaker->setLogicalId('add_speaker');
			$add_speaker->setName(__('Ajout un haut parleur', __FILE__));
		}
		$add_speaker->setType('action');
		$add_speaker->setSubType('message');
		$add_speaker->setDisplay('message_disable', 1);
		$add_speaker->setDisplay('title_placeholder', __('Nom de la piece', __FILE__));
		$add_speaker->setEqLogic_id($this->getId());
		$add_speaker->save();
		
		$remove_speaker = $this->getCmd(null, 'remove_speaker');
		if (!is_object($remove_speaker)) {
			$remove_speaker = new sonos3Cmd();
			$remove_speaker->setLogicalId('remove_speaker');
			$remove_speaker->setName(__('Supprimer un haut parleur', __FILE__));
		}
		$remove_speaker->setType('action');
		$remove_speaker->setSubType('message');
		$remove_speaker->setDisplay('message_disable', 1);
		$remove_speaker->setDisplay('title_placeholder', __('Nom de la piece', __FILE__));
		$remove_speaker->setEqLogic_id($this->getId());
		$remove_speaker->save();
		
		$line_in = $this->getCmd(null, 'line_in');
		if (!is_object($line_in)) {
			$line_in = new sonos3Cmd();
			$line_in->setLogicalId('line_in');
			$line_in->setName(__('Entrée de ligne', __FILE__));
		}
		$line_in->setType('action');
		$line_in->setSubType('other');
		$line_in->setEqLogic_id($this->getId());
		$line_in->save();
		
		$tts = $this->getCmd(null, 'tts');
		if (!is_object($tts)) {
			$tts = new sonos3Cmd();
			$tts->setLogicalId('tts');
			$tts->setName(__('Dire', __FILE__));
		}
		$tts->setType('action');
		$tts->setSubType('message');
		$tts->setDisplay('title_disable', 0);
		$tts->setDisplay('title_placeholder', __('Volume', __FILE__));
		$tts->setDisplay('message_placeholder', __('Message', __FILE__));
		$tts->setEqLogic_id($this->getId());
		$tts->save();
		
		$dominantColor = $this->getCmd(null, 'dominantColor');
		if (!is_object($dominantColor)) {
			$dominantColor = new sonos3Cmd();
			$dominantColor->setLogicalId('dominantColor');
			$dominantColor->setName(__('Couleur dominante', __FILE__));
		}
		$dominantColor->setType('info');
		$dominantColor->setSubType('string');
		$dominantColor->setEqLogic_id($this->getId());
		$dominantColor->save();
	}
	
	public function toHtml($_version = 'dashboard') {
		$replace = $this->preToHtml($_version, array('#background-color#' => '#4a89dc'));
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
			if ($_version != 'mobile' && $_version != 'mview' && $cmd->getLogicalId() == 'play_playlist') {
				$replace['#playlist#'] = str_replace(array("'", '+'), array("\'", '\+'), $cmd->getDisplay('title_possibility_list'));
			}
			if ($_version != 'mobile' && $_version != 'mview' && $cmd->getLogicalId() == 'play_radio') {
				$replace['#radio#'] = str_replace(array("'", '+'), array("\'", '\+'), $cmd->getDisplay('title_possibility_list'));
			}
			if ($_version != 'mobile' && $_version != 'mview' && $cmd->getLogicalId() == 'play_favourite') {
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
		if ($_version != 'mobile' && $_version != 'mview') {
			$replace['#speakers#'] = str_replace(array("'", '+'), array("\'", '\+'), $this->getConfiguration('speakers'));
		}
		
		$cmd_track_image = $this->getCmd(null, 'track_image');
		if (is_object($cmd_track_image)) {
			$img = dirname(__FILE__) . '/../../../../plugins/sonos3/sonos_' . $this->getId() . '.jpg';
			if (file_exists($img) && filesize($img) > 500) {
				$replace['#thumbnail#'] = 'plugins/sonos3/sonos_' . $this->getId() . '.jpg?' . md5($cmd_track_image->execCmd());
			} else {
				$replace['#thumbnail#'] = 'plugins/sonos3/plugin_info/sonos3_alt_icon.png';
			}
		}
		return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'eqLogic', 'sonos3')));
	}
	
	public function getQueue() {
		return $this->getController()->getQueue()->getTracks();
	}
	
	public function playTrack($_position) {
		$controller = $this->getController();
		if (!$controller->isUsingQueue()) {
			$controller->useQueue();
		}
		$controller->selectTrack($_position);
		$controller->play();
	}
	
	public function removeTrack($_position) {
		$this->getController()->getQueue()->removeTrack($_position);
	}
	
	public function emptyQueue() {
		$this->getController()->getQueue()->clear();
	}
	
	public function playGoogleMusic($_id) {
		$this->getController()->getQueue()->addTrack(new Track($_id));
	}
	
	public function getImage() {
		return 'plugins/sonos3/core/img/' . str_replace(':', '', $this->getConfiguration('model')) . '.jpg';
	}
	
	/*     * **********************Getteur Setteur*************************** */
}

class sonos3Cmd extends cmd {
	/*     * *************************Attributs****************************** */
	
	public static $_widgetPossibility = array('custom' => false);
	
	/*     * ***********************Methode static*************************** */
	
	/*     * *********************Methode d'instance************************* */
	
	public function imperihomeGenerate($ISSStructure) {
		$eqLogic = $this->getEqLogic();
		$object = $eqLogic->getObject();
		$type = 'DevPlayer';
		$info_device = array(
			'id' => $this->getId(),
			'name' => $eqLogic->getName(),
			'room' => (is_object($object)) ? $object->getId() : 99999,
			'type' => $type,
			'params' => array(),
		);
		$info_device['params'] = $ISSStructure[$info_device['type']]['params'];
		$info_device['params'][0]['value'] = '#' . $eqLogic->getCmd('info', 'state')->getId() . '#';
		$info_device['params'][1]['value'] = '#' . $eqLogic->getCmd('info', 'volume')->getId() . '#';
		$info_device['params'][2]['value'] = '#' . $eqLogic->getCmd('info', 'mute_state')->getId() . '#';
		$info_device['params'][3]['value'] = '';
		$info_device['params'][4]['value'] = '';
		$info_device['params'][5]['value'] = '#' . $eqLogic->getCmd('info', 'track_title')->getId() . '#';
		$info_device['params'][6]['value'] = '#' . $eqLogic->getCmd('info', 'track_album')->getId() . '#';
		$info_device['params'][7]['value'] = '#' . $eqLogic->getCmd('info', 'track_artist')->getId() . '#';
		$info_device['params'][8]['value'] = network::getNetworkAccess('external') . '/plugins/sonos3/sonos_' . $eqLogic->getId() . '.jpg';
		return $info_device;
	}
	
	public function dontRemoveCmd() {
		return true;
	}
	
	public function imperihomeAction($_action, $_value) {
		$eqLogic = $this->getEqLogic();
		switch ($_action) {
			case 'setvolume':
			$eqLogic->getCmd('action', 'setVolume')->execCmd(array('slider' => $_value));
			break;
			case 'play':
			$eqLogic->getCmd('action', 'play')->execCmd();
			break;
			case 'pause':
			$eqLogic->getCmd('action', 'pause')->execCmd();
			break;
			case 'next':
			$eqLogic->getCmd('action', 'next')->execCmd();
			break;
			case 'previous':
			$eqLogic->getCmd('action', 'previous')->execCmd();
			break;
			case 'stop':
			$eqLogic->getCmd('action', 'stop')->execCmd();
			break;
			case 'mute':
			if ($eqLogic->getCmd('info', 'mute_state')->execCmd() == 1) {
				$eqLogic->getCmd('action', 'unmute')->execCmd();
			} else {
				$eqLogic->getCmd('action', 'mute')->execCmd();
			}
			break;
		}
		return;
	}
	
	public function imperihomeCmd() {
		return ($this->getLogicalId() == 'state');
	}
	
	public function execute($_options = array()) {
		if ($this->getType() == 'info') {
			return;
		}
		$eqLogic = $this->getEqLogic();
		$sonos = sonos3::getSonos();
		$controller = $eqLogic->getController();
		if (!is_object($controller)) {
			throw new Exception(__('Impossible de récuperer le sonos : ', __FILE__) . $eqLogic->getHumanName());
		}
		if ($this->getLogicalId() == 'play') {
			$state = $eqLogic->getCmd(null, 'state');
			$track_title = $eqLogic->getCmd(null, 'track_title');
			if (is_object($state) && is_object($track_title) && $track_title->execCmd() == __('Aucun', __FILE__) && $state->execCmd() == __('Lecture', __FILE__)) {
				return $controller->unmute();
			}
			try {
				$controller->play();
			} catch (Exception $e) {
				
			}
		} elseif ($this->getLogicalId() == 'stop') {
			$state = $eqLogic->getCmd(null, 'state');
			$track_title = $eqLogic->getCmd(null, 'track_title');
			if ($track_title->execCmd() == __('Aucun', __FILE__) && $state->execCmd() == __('Arrêté', __FILE__)) {
				return;
			}
			if ($track_title->execCmd() == __('Aucun', __FILE__) && $state->execCmd() == __('Lecture', __FILE__)) {
				return $controller->mute();
			}
			try {
				$controller->pause();
			} catch (Exception $e) {
				
			}
		} elseif ($this->getLogicalId() == 'pause') {
			$state = $eqLogic->getCmd(null, 'state');
			$track_title = $eqLogic->getCmd(null, 'track_title');
			if ($track_title->execCmd() == __('Aucun', __FILE__) && $state->execCmd() == __('Arrêté', __FILE__)) {
				return;
			}
			if ($track_title->execCmd() == __('Aucun', __FILE__) && $state->execCmd() == __('Lecture', __FILE__)) {
				$controller->mute();
			}
			try {
				$controller->pause();
			} catch (Exception $e) {
				
			}
		} elseif ($this->getLogicalId() == 'previous') {
			$controller->previous();
		} elseif ($this->getLogicalId() == 'next') {
			$controller->next();
		} elseif ($this->getLogicalId() == 'mute') {
			$controller->mute();
		} elseif ($this->getLogicalId() == 'unmute') {
			$controller->unmute();
		} elseif ($this->getLogicalId() == 'repeat') {
			$controller->setRepeat(!$controller->getRepeat());
		} elseif ($this->getLogicalId() == 'shuffle') {
			$controller->setShuffle(!$controller->getShuffle());
		} elseif ($this->getLogicalId() == 'setVolume') {
			if ($_options['slider'] < 0) {
				$_options['slider'] = 0;
			} else if ($_options['slider'] > 100) {
				$_options['slider'] = 100;
			}
			$controller->setVolume($_options['slider']);
		} elseif ($this->getLogicalId() == 'play_playlist') {
			if (!$controller->isUsingQueue()) {
				$controller->useQueue();
			}
			$controller->soap("AVTransport", "RemoveAllTracksFromQueue");
			$queue = $controller->getQueue();
			$uri = $eqLogic->getPlayListsUri($_options['title']);
			if ($uri == null) {
				throw new Exception(__('Playlist non trouvé : ', __FILE__) . trim($_options['title']));
			}
			if (isset($_options['message']) && $_options['message'] == 'random') {
				try {
					$controller->setShuffle(true);
				} catch (Exception $e) {
					log::add('sonos3', 'warning', $this->getHumanName() . ' : ' . $e->getMessage());
				}
			}
			try {
				$queue->addTrack($uri);
			} catch (Exception $e) {
				
			}
			$controller->play();
			$loop = 1;
			while (true) {
				if ($controller->getStateName() == 'PLAYING') {
					break;
				}
				if (($loop % 4) === 0) {
					$controller->play();
				}
				if ($loop > 20) {
					break;
				}
				usleep(500000);
				$loop++;
			}
		} elseif ($this->getLogicalId() == 'play_favourite') {
			if (!$controller->isUsingQueue()) {
				$controller->useQueue();
			}
			$controller->soap("AVTransport", "RemoveAllTracksFromQueue");
			$queue = $controller->getQueue();
			$favourite = $eqLogic->getFavouritesUri($_options['title']);
			if ($favourite == null) {
				throw new Exception(__('Favoris non trouvé : ', __FILE__) . trim($_options['title']));
			}
			if (isset($_options['message']) && $_options['message'] == 'random') {
				try {
					$controller->setShuffle(true);
				} catch (Exception $e) {
					log::add('sonos3', 'warning', $this->getHumanName() . ' : ' . $e->getMessage());
				}
			}
			try {
				$controller->soap("AVTransport", "AddURIToQueue", [
					"EnqueuedURI" => $favourite['uri'],
					"EnqueuedURIMetaData" => $favourite['metadata'],
					"DesiredFirstTrackNumberEnqueued" => 0,
					"EnqueueAsNext" => 0,
				]);
			} catch (Exception $e) {
				
			}
			
			$controller->play();
			$loop = 1;
			while (true) {
				if ($controller->getStateName() == 'PLAYING') {
					break;
				}
				if (($loop % 4) === 0) {
					$controller->play();
				}
				if ($loop > 20) {
					break;
				}
				usleep(500000);
				$loop++;
			}
		} elseif ($this->getLogicalId() == 'play_radio') {
			$radio = $sonos->getRadio();
			$stations = $radio->getFavouriteStations();
			foreach ($stations as $station) {
				if ($station->getTitle() == $_options['title']) {
					$controller->useStream($station)->play();
					break;
				}
			}
		} elseif ($this->getLogicalId() == 'add_speaker') {
			$speaker = $sonos->getSpeakerByRoom($_options['title']);
			$controller->addSpeaker($speaker);
		} elseif ($this->getLogicalId() == 'remove_speaker') {
			$speaker = $sonos->getSpeakerByRoom($_options['title']);
			$controller->removeSpeaker($speaker);
		} elseif ($this->getLogicalId() == 'line_in') {
			$controller->useLineIn()->play();
		} elseif ($this->getLogicalId() == 'tts') {
			$_options['message'] = str_replace(array('[', ']', '#', '{', '}'), '', $_options['message']);
			$path = explode('/', trim(config::byKey('tts_path', 'sonos3'), '/'));
			$server = new Server(config::byKey('tts_host', 'sonos3'), config::byKey('tts_username', 'sonos3'), config::byKey('tts_password', 'sonos3'));
			$share = $server->getShare($path[0]);
			$adapter = new SmbAdapter($share);
			$filesystem = new Filesystem($adapter);
			$folder = array_pop($path);
			$directory = new Directory($filesystem, config::byKey('tts_host', 'sonos3') . '/' . implode('/', $path), $folder);
			$track = new TextToSpeech(trim($_options['message']), $directory, new JeedomProvider(network::getNetworkAccess('internal') . '/core/api/tts.php?apikey=' . config::byKey('api', 'core')));
			$loop = 1;
			while (true) {
				try {
					if ($_options['title'] != '' && is_numeric($_options['title'])) {
						$controller->interrupt($track, $_options['title']);
					} else {
						$controller->interrupt($track);
					}
					break;
				} catch (Exception $e) {
					log::add('sonos3', 'debug', $e->getMessage());
				}
				if ($loop > 20) {
					break;
				}
				usleep(500000);
				$loop++;
			}
			
		}
	}
	
	/*     * **********************Getteur Setteur*************************** */
}
