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

	private $_controller = null;

	/*     * ***********************Methode static*************************** */

	public static function restore() {
		try {
			self::syncSonos();
		} catch (Exception $e) {
		}
	}

	public static function dependancy_info() {
		$return = array();
		$return['log'] = 'sonos_update';
		$return['state'] = 'ok';
		$return['progress_file'] = jeedom::getTmpFolder(__CLASS__) . '/dependance';
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
		return array('script' => dirname(__FILE__) . '/../../resources/install_#stype#.sh ' . jeedom::getTmpFolder(__CLASS__) . '/dependance', 'log' => log::getPathToLog(__CLASS__ . '_update'));
	}

	public static function deamon_info() {
		$return = array();
		$return['log'] = '';
		$return['state'] = 'nok';
		$cron = cron::byClassAndFunction(__CLASS__, 'pull');
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
		$cron = cron::byClassAndFunction(__CLASS__, 'pull');
		if (!is_object($cron)) {
			throw new Exception(__('Tache cron introuvable', __FILE__));
		}
		$cron->run();
	}

	public static function deamon_stop() {
		$cron = cron::byClassAndFunction(__CLASS__, 'pull');
		if (!is_object($cron)) {
			throw new Exception(__('Tache cron introuvable', __FILE__));
		}
		$cron->halt();
	}

	public static function deamon_changeAutoMode($_mode) {
		$cron = cron::byClassAndFunction(__CLASS__, 'pull');
		if (!is_object($cron)) {
			throw new Exception(__('Tache cron introuvable', __FILE__));
		}
		$cron->setEnable($_mode);
		$cron->save();
	}

	public static function interact($_query, $_parameters = array()) {
		if (trim(config::byKey('interact::sentence', __CLASS__)) == '') {
			return null;
		}
		$ok = false;
		$files = array();
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
		if (is_object($data['object'])) {
			$founds = $data['object']->getEqLogic(true, false, __CLASS__);
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
		$playlists = config::byKey('playlist', __CLASS__);
		if (is_array($playlists)) {
			foreach ($playlists as $uri => $name) {
				if (interactQuery::autoInteractWordFind($data['query'], $name)) {
					$sonos->getCmd(null, 'play_playlist')->execCmd(array('title' => $name));
					return array('reply' => __('Ok j\'ai lancé', __FILE__) . ' : ' . $name);
				}
			}
		}
		$favourites = config::byKey('favourites', __CLASS__);
		if (is_array($favourites)) {
			foreach ($favourites as $favourite) {
				if (interactQuery::autoInteractWordFind($data['query'], $favourite['name'])) {
					$sonos->getCmd(null, 'play_favourite')->execCmd(array('title' => $favourite['name']));
					return array('reply' => __('Ok j\'ai lancé', __FILE__) . ' : ' . $favourite['name']);
				}
			}
		}
		return array('reply' => 'Playlist ou favoris non trouvé');
	}

	public static function getSonos($_discover = false, $_ip = null) {
		if (self::$_sonos === null || $_discover) {
			if ($_discover) {
				self::$_sonos = new duncan3dc\Sonos\Network();
			} else {
				$devices = new \duncan3dc\Sonos\Devices\Collection();
				$devices->setLogger(log::getLogger(__CLASS__));
				if ($_ip !== null) {
					$devices->addIp($_ip);
					$sonos =  new duncan3dc\Sonos\Network($devices);
					$sonos->setLogger(log::getLogger(__CLASS__));
					return $sonos;
				} else {
					$eqLogics = eqLogic::byType(__CLASS__, true);
					if (count($eqLogics) != 0) {
						foreach ($eqLogics as $eqLogic) {
							$devices->addIp($eqLogic->getLogicalId());
						}
					}
				}
				self::$_sonos = new duncan3dc\Sonos\Network($devices);
			}
			self::$_sonos->setLogger(log::getLogger(__CLASS__));
		}
		return self::$_sonos;
	}

	public static function cronDaily() {
		try {
			if (date('i') == 0 && date('s') < 10) {
				sleep(10);
			}
			$plugin = plugin::byId(__CLASS__);
			$plugin->deamon_start(true);
		} catch (\Exception $e) {
		}
	}

	/**
	 * set model in eqLogic configuration from controller name and save eqLogic
	 *
	 * @param string $controllerName
	 * @return void
	 */
	private function setModel($controllerName) {
		if (stripos($controllerName, 'PLAY:1') !== false) {
			$this->setConfiguration('model', 'PLAY1');
		} else if (stripos($controllerName, 'PLAY:3') !== false) {
			$this->setConfiguration('model', 'PLAY3');
		} else if (stripos($controllerName, 'PLAY:5') !== false) {
			$this->setConfiguration('model', 'PLAY5');
		} else if (stripos($controllerName, 'PLAYBAR') !== false) {
			$this->setConfiguration('model', 'PLAYBAR');
		} else if (stripos($controllerName, 'PLAYBASE') !== false) {
			$this->setConfiguration('model', 'PLAYBASE');
		} else if (stripos($controllerName, 'CONNECT:AMP') !== false) {
			$this->setConfiguration('model', 'CONNECTAMP');
		} else if (stripos($controllerName, 'CONNECT') !== false) {
			$this->setConfiguration('model', 'CONNECT');
		} else if (stripos($controllerName, 'BEAM') !== false) {
			$this->setConfiguration('model', 'BEAM');
		} else if (stripos($controllerName, 'ONE') !== false) {
			$this->setConfiguration('model', 'ONE');
		} else if (stripos($controllerName, 'SYMFONISK_LIGHT') !== false) {
			$this->setConfiguration('model', 'SYMFONISK_LIGHT');
		} else if (stripos($controllerName, 'SYMFONISK') !== false) {
			$this->setConfiguration('model', 'SYMFONISK');
		} else if (stripos($controllerName, 'SYMFONISK_INWALL') !== false) {
			$this->setConfiguration('model', 'SYMFONISK_INWALL');
		} else if (stripos($controllerName, 'PORT') !== false) {
			$this->setConfiguration('model', 'PORT');
		} else if (stripos($controllerName, 'MOVE') !== false) {
			$this->setConfiguration('model', 'MOVE');
		} else if (stripos($controllerName, 'FIVE') !== false) {
			$this->setConfiguration('model', 'FIVE');
		} else if (stripos($controllerName, 'ROAM') !== false) {
			$this->setConfiguration('model', 'ROAM');
		}
		$this->save(true);
	}

	public static function syncSonos() {
		$sonos = self::getSonos(true);
		try {
			$controllers = $sonos->getControllers();
		} catch (\Exception $e) {
			self::$_sonos = null;
			$sonos = self::getSonos();
			$controllers = $sonos->getControllers();
		}
		$speakers = self::getSpeaker();
		$speakers_array = array();
		foreach ($speakers as $speaker) {
			$speakers_array[$speaker->getIp()] = $speaker->getRoom();
		}
		foreach ($controllers as $controller) {
			/** @var sonos3 */
			$eqLogic = self::byLogicalId($controller->getIp(), __CLASS__);
			log::add(__CLASS__, 'info', "Controller found: {$controller->getName()}");
			if (!is_object($eqLogic)) {
				$eqLogic = new self();
				$eqLogic->setLogicalId($controller->getIp());
				$eqLogic->setName($controller->getRoom() . ' - ' . $controller->getName());
				$object = jeeObject::byName($controller->getRoom());
				if (is_object($object)) {
					$eqLogic->setObject_id($object->getId());
					$eqLogic->setName($controller->getName());
				}
				$eqLogic->setEqType_name(__CLASS__);
				$eqLogic->setIsVisible(1);
				$eqLogic->setIsEnable(1);
				$eqLogic->save();
			}
			$eqLogic->setModel($controller->getName());
		}
		$eqLogics = eqLogic::byType(__CLASS__);
		if (count($eqLogics) != 0) {
			foreach ($eqLogics as $eqLogic) {
				$eqLogic->setConfiguration('speakers', json_encode($speakers_array));
				$eqLogic->save();
			}
		}
		self::getRadioStations();
		self::getPlayLists();
		self::getFavourites();
	}

	public static function pull($_eqLogic_id = null) {
		if (self::$_eqLogics == null) {
			self::$_eqLogics = self::byType(__CLASS__);
		}
		foreach (self::$_eqLogics as &$eqLogic) {
			if ($_eqLogic_id != null && $_eqLogic_id != $eqLogic->getId()) {
				continue;
			}
			if ($eqLogic->getIsEnable() == 0) {
				$eqLogic->refresh();
			}
			if ($eqLogic->getLogicalId() == '' || $eqLogic->getIsEnable() == 0) {
				continue;
			}
			try {
				$changed = false;
				try {
					$controller = $eqLogic->getController(true);
				} catch (\Exception $e) {
					self::$_sonos = null;
					continue;
				}
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
				$changed = $eqLogic->checkAndUpdateCmd('state', $state, false) || $changed;
				$changed = $eqLogic->checkAndUpdateCmd('volume', $controller->getVolume(), false) || $changed;
				$changed = $eqLogic->checkAndUpdateCmd('shuffle_state', $shuffle, false) || $changed;
				$changed = $eqLogic->checkAndUpdateCmd('mute_state', $mute, false) || $changed;
				$changed = $eqLogic->checkAndUpdateCmd('repeat_state', $repeat, false) || $changed;
				$changed = $eqLogic->checkAndUpdateCmd('track_title', $title, false) || $changed;
				$changed = $eqLogic->checkAndUpdateCmd('track_album', $album, false) || $changed;
				$changed = $eqLogic->checkAndUpdateCmd('track_artist', $artist, false) || $changed;
				if ($track->getAlbumArt() != '') {
					if ($eqLogic->checkAndUpdateCmd('track_image', $track->getAlbumArt())) {
						file_put_contents(dirname(__FILE__) . '/../../../../plugins/sonos3/sonos_' . $eqLogic->getId() . '.jpg', file_get_contents($track->getAlbumArt()));
						$dominantColor = getDominantColor(dirname(__FILE__) . '/../../../../plugins/sonos3/sonos_' . $eqLogic->getId() . '.jpg', 2, true);
						$eqLogic->checkAndUpdateCmd('dominantColor', $dominantColor[0]);
						$eqLogic->checkAndUpdateCmd('dominantColor2', $dominantColor[1]);
						$changed = true;
					}
				} else if (file_exists(dirname(__FILE__) . '/../../../../plugins/sonos3/sonos_' . $eqLogic->getId() . '.jpg')) {
					unlink(dirname(__FILE__) . '/../../../../plugins/sonos3/sonos_' . $eqLogic->getId() . '.jpg');
				}
				if ($changed) {
					$eqLogic->refreshWidget();
				}
			} catch (Exception $e) {
				if ($_eqLogic_id != null) {
					log::add(__CLASS__, 'error', $e->getMessage());
				} else {
					$eqLogic->refresh();
					if ($eqLogic->getIsEnable() == 0) {
						continue;
					}
				}
			} catch (Error $ex) {
				if ($_eqLogic_id != null) {
					log::add(__CLASS__, 'error', $ex->getMessage());
				} else {
					$eqLogic->refresh();
					if ($eqLogic->getIsEnable() == 0) {
						continue;
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
		$sonos = self::getSonos();
		$playlists = $sonos->getPlaylists();
		$array = array();
		foreach ($playlists as $playlist) {
			$array[$playlist->getUri()] = $playlist->getName();
		}
		config::save('playlist', $array, __CLASS__);
		foreach (self::byType(__CLASS__) as $sonos3) {
			$cmd = $sonos3->getCmd('action', 'play_playlist');
			if (is_object($cmd)) {
				$cmd->setDisplay('title_possibility_list', json_encode(array_values($array)));
				$cmd->save();
			}
		}
		return $playlists;
	}

	public static function getFavourites() {
		$sonos = self::getSonos();
		$favourites = $sonos->getFavourites()->listFavorite();
		config::save('favourites', $favourites, __CLASS__);
		$array = array();
		foreach ($favourites as $favourite) {
			$array[$favourite['uri']] = $favourite['name'];
		}
		foreach (self::byType(__CLASS__) as $sonos3) {
			$cmd = $sonos3->getCmd('action', 'play_favourite');
			if (is_object($cmd)) {
				$cmd->setDisplay('title_possibility_list', json_encode(array_values($array)));
				$cmd->save();
			}
		}
		return $favourites;
	}

	public static function getRadioStations() {
		$radios = self::getSonos()->getRadio()->getFavouriteStations();
		$array = array();
		foreach ($radios as $radio) {
			$array[] = $radio->getTitle();
		}
		foreach (self::byType(__CLASS__) as $sonos3) {
			$cmd = $sonos3->getCmd('action', 'play_radio');
			if (is_object($cmd)) {
				$cmd->setDisplay('title_possibility_list', json_encode($array));
				$cmd->save();
			}
		}
		return $radios;
	}

	public static function getSpeaker() {
		return self::getSonos()->getSpeakers();
	}

	/*     * *********************Méthodes d'instance************************* */

	public function getPlayListsUri($_playlist) {
		$_playlist = trim(trim($_playlist), '"');
		$playlists = config::byKey('playlist', __CLASS__);
		if (is_array($playlists)) {
			foreach ($playlists as $uri => $name) {
				if (strtolower($name) == strtolower($_playlist)) {
					return $uri;
				}
			}
		}
		$sonos = self::getPlayLists();
		$playlists = config::byKey('playlist', __CLASS__);
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
		$favourites = config::byKey('favourites', __CLASS__);
		if (is_array($favourites)) {
			foreach ($favourites as $favourite) {
				if (strtolower($favourite['name']) == strtolower($_favourite)) {
					return $favourite;
				}
			}
		}
		$sonos = self::getFavourites();
		$favourites = config::byKey('favourites', __CLASS__);
		if (is_array($favourites)) {
			foreach ($favourites as $favourite) {
				if (strtolower($favourite['name']) == strtolower($_favourite)) {
					return $favourite;
				}
			}
		}
		return null;
	}

	public function getController($_justHim = false) {
		if ($this->_controller == null) {
			if ($_justHim) {
				$this->_controller = self::getSonos(false, $this->getLogicalId())->getControllerByIp($this->getLogicalId());
			} else {
				$this->_controller = self::getSonos(false)->getControllerByIp($this->getLogicalId());
			}
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
			$state->setName(__('Statut', __FILE__));
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
		$play->setGeneric_type('MEDIA_RESUME');
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
		$stop->setGeneric_type('MEDIA_STOP');
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
		$pause->setGeneric_type('MEDIA_PAUSE');
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
		$next->setGeneric_type('MEDIA_NEXT');
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
		$previous->setGeneric_type('MEDIA_PREVIOUS');
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
			$mute_state->setName(__('Muet statut', __FILE__));
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
			$repeat_state->setName(__('Répéter statut', __FILE__));
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
			$shuffle_state->setName(__('Aléatoire statut', __FILE__));
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
			$volume->setName(__('Volume statut', __FILE__));
		}
		$volume->setGeneric_type('VOLUME');
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
		$setVolume->setGeneric_type('SET_VOLUME');
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
		$add_speaker->setDisplay('title_placeholder', __('Nom de la pièce', __FILE__));
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
		$remove_speaker->setDisplay('title_placeholder', __('Nom de la pièce', __FILE__));
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


		$dominantColor2 = $this->getCmd(null, 'dominantColor2');
		if (!is_object($dominantColor2)) {
			$dominantColor2 = new sonos3Cmd();
			$dominantColor2->setLogicalId('dominantColor2');
			$dominantColor2->setName(__('Couleur dominante 2', __FILE__));
		}
		$dominantColor2->setType('info');
		$dominantColor2->setSubType('string');
		$dominantColor2->setEqLogic_id($this->getId());
		$dominantColor2->save();

		if ($this->getChanged()) {
			self::deamon_start();
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
			if ($_version != 'mobile' && $cmd->getLogicalId() == 'play_favourite') {
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

		$cmd_track_image = $this->getCmd(null, 'track_image');
		if (is_object($cmd_track_image)) {
			$img = dirname(__FILE__) . '/../../../../plugins/sonos3/sonos_' . $this->getId() . '.jpg';
			if (file_exists($img) && filesize($img) > 500) {
				$replace['#thumbnail#'] = 'plugins/sonos3/sonos_' . $this->getId() . '.jpg?' . md5($cmd_track_image->execCmd());
			} else {
				$replace['#thumbnail#'] = 'plugins/sonos3/plugin_info/sonos3_alt_icon.png';
			}
		}
		return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'eqLogic', __CLASS__)));
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
		return 'plugins/sonos3/core/img/' . str_replace(':', '', $this->getConfiguration('model')) . '.png';
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
		$eqLogic = $this->getEqLogic();
		$controller = $eqLogic->getController(true);
		if (!is_object($controller)) {
			throw new Exception(__('Impossible de récuperer le sonos', __FILE__) . ' : ' . $eqLogic->getHumanName());
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
			try {
				if (!$controller->isUsingQueue()) {
					$controller->useQueue();
				}
			} catch (\Exception $e) {
			}
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
			$eqLogic->getCmd(null, 'volume')->event($_options['slider']);
		} elseif ($this->getLogicalId() == 'play_playlist') {
			if (!$controller->isUsingQueue()) {
				$controller->useQueue();
			}
			$controller->soap("AVTransport", "RemoveAllTracksFromQueue");
			$queue = $controller->getQueue();
			$uri = $eqLogic->getPlayListsUri($_options['title']);
			if ($uri == null) {
				throw new Exception(__('Playlist non trouvée', __FILE__) . ' : ' . trim($_options['title']));
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
				throw new Exception(__('Favoris non trouvés', __FILE__) . ' : ' . trim($_options['title']));
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
			$radio = sonos3::getSonos()->getRadio();
			$stations = $radio->getFavouriteStations();
			foreach ($stations as $station) {
				if ($station->getTitle() == $_options['title']) {
					$controller->useStream($station)->play();
					break;
				}
			}
		} elseif ($this->getLogicalId() == 'add_speaker') {
			$speaker = sonos3::getSonos()->getSpeakerByRoom($_options['title']);
			$controller->addSpeaker($speaker);
		} elseif ($this->getLogicalId() == 'remove_speaker') {
			$speaker = sonos3::getSonos()->getSpeakerByRoom($_options['title']);
			$controller->removeSpeaker($speaker);
		} elseif ($this->getLogicalId() == 'line_in') {
			$controller->useLineIn()->play();
		} elseif ($this->getLogicalId() == 'tts') {
			$_options['message'] = $_options['message'];
			$path = explode('/', sanitizeAccent(trim(config::byKey('tts_path', 'sonos3')), '/'));
			$server = new Server(config::byKey('tts_host', 'sonos3'), config::byKey('tts_username', 'sonos3'), config::byKey('tts_password', 'sonos3'));
			$share = $server->getShare($path[0]);
			$adapter = new SmbAdapter($share);
			$filesystem = new Filesystem($adapter);
			$folder = array_pop($path);
			$directory = new Directory($filesystem, config::byKey('tts_host', 'sonos3') . '/' . implode('/', $path), $folder);
			$track = new TextToSpeech(trim($_options['message']), $directory, new JeedomProvider(network::getNetworkAccess('internal') . '/core/api/tts.php?apikey=' . jeedom::getApiKey('apitts')));
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
