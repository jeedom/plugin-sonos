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
use duncan3dc\Sonos\Directory;
use duncan3dc\Sonos\Network;
use duncan3dc\Sonos\Speaker;
use duncan3dc\Sonos\Tracks\TextToSpeech;
use duncan3dc\Speaker\Providers\GoogleProvider;
use duncan3dc\Speaker\Providers\VoxygenProvider;
use Icewind\SMB\Server;
use League\Flysystem\Filesystem;
use RobGridley\Flysystem\Smb\SmbAdapter;

class sonos3 extends eqLogic {
	/*     * *************************Attributs****************************** */

	private static $_sonos = null;
	private static $_sonosAddOK = false;

	/*     * ***********************Methode static*************************** */

	public static function updateSonos() {
		log::remove('sonos_update');
		$cmd = 'sudo /bin/bash ' . dirname(__FILE__) . '/../../ressources/install.sh';
		$cmd .= ' >> ' . log::getPathToLog('sonos_update') . ' 2>&1 &';
		exec($cmd);
	}

	public static function health() {
		$return = array();
		$cron = cron::byClassAndFunction('sonos3', 'pull');
		$running = false;
		if (is_object($cron)) {
			$running = $cron->running();
		}
		$return[] = array(
			'test' => __('Tâche de synchronisation', __FILE__),
			'result' => ($running) ? __('OK', __FILE__) : __('NOK', __FILE__),
			'advice' => ($running) ? '' : __('Allez sur la page du moteur des tâches et vérifiez lancer la tache sonos3::pull', __FILE__),
			'state' => $running,
		);
		return $return;
	}

	public static function getSonos($_emptyCache = false) {
		if ($_emptyCache) {
			shell_exec('rm -rf /tmp/sonos-cache');
			shell_exec('sudo rm -rf /tmp/sonos-cache');
		} else if (self::$_sonos !== null) {
			return self::$_sonos;
		}
		$cache = new \Doctrine\Common\Cache\FilesystemCache("/tmp/sonos-cache");
		self::$_sonos = new Network($cache);
		return self::$_sonos;
	}

	public static function cronDaily() {
		shell_exec('rm -rf /tmp/sonos-cache');
		shell_exec('sudo rm -rf /tmp/sonos-cache');
		self::getSonos(true);
	}

	public static function syncSonos() {
		$sonos = self::getSonos(true);
		$controllers = $sonos->getControllers();
		$speakers = sonos3::getSpeaker();
		foreach ($controllers as $controller) {
			$eqLogic = sonos3::byLogicalId($controller->ip, 'sonos3');
			if (!is_object($eqLogic)) {
				$eqLogic = new self();
				$eqLogic->setLogicalId($controller->ip);
				$eqLogic->setName($controller->room . ' - ' . $controller->name);
				$object = object::byName($controller->room);
				if (is_object($object)) {
					$eqLogic->setObject_id($object->getId());
					$eqLogic->setName($controller->name);
				}
				if (strpos($controller->name, 'PLAY:1') !== false) {
					$eqLogic->setConfiguration('model', 'PLAY1');
				}
				if (strpos($controller->name, 'PLAY:3') !== false) {
					$eqLogic->setConfiguration('model', 'PLAY3');
				}
				if (strpos($controller->name, 'PLAY:5') !== false) {
					$eqLogic->setConfiguration('model', 'PLAY5');
				}
				if (strpos($controller->name, 'PLAYBAR') !== false) {
					$eqLogic->setConfiguration('model', 'PLAYBAR');
				}
				if (strpos($controller->name, 'CONNECT') !== false) {
					$eqLogic->setConfiguration('model', 'CONNECT');
				}
				if (strpos($controller->name, 'CONNECT:AMP') !== false) {
					$eqLogic->setConfiguration('model', 'CONNECTAMP');
				}
				$eqLogic->setEqType_name('sonos3');
				$eqLogic->setIsVisible(1);
				$eqLogic->setIsEnable(1);
			}
			$speakers_array = array();
			foreach ($speakers as $speaker) {
				$speakers_array[$speaker->ip] = $speaker->room;
			}
			$eqLogic->setConfiguration('speakers', json_encode($speakers_array));
			$eqLogic->save();
		}
	}

	public static function pull($_eqLogic_id = null) {
		foreach (self::byType('sonos3') as $eqLogic) {
			if ($_eqLogic_id != null && $_eqLogic_id != $eqLogic->getId()) {
				continue;
			}
			if ($eqLogic->getIsEnable() == 0) {
				continue;
			}
			if ($eqLogic->getLogicalId() == '') {
				continue;
			}
			try {
				$changed = false;
				$controller = self::getControllerByIp($eqLogic->getLogicalId());
				if ($controller == null) {
					continue;
				}
				$cmd_state = $eqLogic->getCmd(null, 'state');
				if (is_object($cmd_state)) {
					$state = self::convertState($controller->getStateName());
					if ($state != $cmd_state->execCmd(null, 2)) {
						$cmd_state->setCollectDate('');
						$cmd_state->event($state);
						$changed = true;
					}
				}

				$cmd_volume = $eqLogic->getCmd(null, 'volume');
				if (is_object($cmd_volume)) {
					$volume = $controller->getVolume();
					if ($volume != $cmd_volume->execCmd(null, 2)) {
						$cmd_volume->setCollectDate('');
						$cmd_volume->event($volume);
						$changed = true;
					}
				}

				$cmd_suffle = $eqLogic->getCmd(null, 'shuffle_state');
				if (is_object($cmd_suffle)) {
					$shuffle = $controller->getShuffle();
					if ($shuffle == '') {
						$shuffle = 0;
					}
					if ($shuffle != $cmd_suffle->execCmd(null, 2)) {
						$cmd_suffle->setCollectDate('');
						$cmd_suffle->event($shuffle);
						$changed = true;
					}
				}

				$cmd_mute = $eqLogic->getCmd(null, 'mute_state');
				if (is_object($cmd_mute)) {
					$mute = $controller->isMuted();
					if ($mute == '') {
						$mute = 0;
					}
					if ($mute != $cmd_mute->execCmd(null, 2)) {
						$cmd_mute->setCollectDate('');
						$cmd_mute->event($mute);
						$changed = true;
					}
				}

				$cmd_repeat = $eqLogic->getCmd(null, 'repeat_state');
				if (is_object($cmd_repeat)) {
					$repeat = $controller->getRepeat();
					if ($repeat == '') {
						$repeat = 0;
					}
					if ($repeat != $cmd_repeat->execCmd(null, 2)) {
						$cmd_repeat->setCollectDate('');
						$cmd_repeat->event($repeat);
						$changed = true;
					}
				}

				$track = $controller->getStateDetails();
				$cmd_track_title = $eqLogic->getCmd(null, 'track_title');
				$title = $track->title;
				if ($title == '') {
					$title = __('Aucun', __FILE__);
				}
				if (is_object($cmd_track_title)) {
					if ($title != $cmd_track_title->execCmd(null, 2)) {
						$cmd_track_title->setCollectDate('');
						$cmd_track_title->event($title);
						$changed = true;
					}
				}

				$cmd_track_album = $eqLogic->getCmd(null, 'track_album');
				$album = $track->album;
				if ($album == '') {
					$album = __('Aucun', __FILE__);
				}
				if (is_object($cmd_track_album)) {
					if ($album != $cmd_track_album->execCmd(null, 2)) {
						$cmd_track_album->setCollectDate('');
						$cmd_track_album->event($album);
						$changed = true;
					}
				}

				$cmd_track_artist = $eqLogic->getCmd(null, 'track_artist');
				$artist = $track->artist;
				if ($artist == '') {
					$artist = __('Aucun', __FILE__);
				}
				if (is_object($cmd_track_artist)) {
					if ($artist != $cmd_track_artist->execCmd(null, 2)) {
						$cmd_track_artist->setCollectDate('');
						$cmd_track_artist->event($artist);
						$changed = true;
					}
				}

				$cmd_track_image = $eqLogic->getCmd(null, 'track_image');
				if ($track->albumArt != '') {
					if (is_object($cmd_track_image)) {
						if ($track->albumArt != $cmd_track_image->execCmd(null, 2)) {
							$cmd_track_image->setCollectDate('');
							$cmd_track_image->event($track->albumArt);
							file_put_contents(dirname(__FILE__) . '/../../../../plugins/sonos3/sonos_' . $eqLogic->getId() . '.jpg', file_get_contents($track->albumArt));
							$changed = true;
						}
					}
				} else {
					if (file_exists(dirname(__FILE__) . '/../../../../plugins/sonos3/sonos_' . $eqLogic->getId() . '.jpg')) {
						unlink(dirname(__FILE__) . '/../../../../plugins/sonos3/sonos_' . $eqLogic->getId() . '.jpg');
					}
				}

				$queue = array();
				foreach ($eqLogic->getQueue() as $track) {
					$queue[] = $track->title . ' - ' . $track->artist;
				}
				$jQueue = json_encode($queue);
				if ($eqLogic->getConfiguration('queue') != $jQueue) {
					$eqLogic->setConfiguration('queue', $jQueue);
					$eqLogic->save();
					$changed = true;
				}

				if ($changed) {
					$mc = cache::byKey('sonosWidgetmobile' . $eqLogic->getId());
					$mc->remove();
					$mc = cache::byKey('sonosWidgetdashboard' . $eqLogic->getId());
					$mc->remove();
					$eqLogic->refreshWidget();
				}
				if ($eqLogic->getConfiguration('sonosNumberFailed', 0) > 0) {
					$eqLogic->setConfiguration('sonosNumberFailed', 0);
					$eqLogic->save();
				}
			} catch (Exception $e) {
				if ($_eqLogic_id != null) {
					log::add('sonos', 'error', $e->getMessage());
				} else {
					if ($eqLogic->getConfiguration('sonosNumberFailed', 0) > 150) {
						log::add('sonos', 'error', __('Erreur sur ', __FILE__) . $eqLogic->getHumanName() . ' : ' . $e->getMessage());
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
			$array[] = $playlist->getName();
		}
		foreach (sonos3::byType('sonos3') as $sonos3) {
			$cmd = $sonos3->getCmd('action', 'play_playlist');
			$cmd->setDisplay('title_possibility_list', json_encode($array));
			$cmd->save();
		}
		return $playlists;
	}

	public function getRadioStations() {
		$sonos = sonos3::getSonos();
		$radios = $sonos->getRadio()->getFavouriteStations();
		$playlists = $sonos->getPlaylists();
		$array = array();
		foreach ($radios as $radio) {
			$array[] = $radio->getName();
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

	public function getSpeaker() {
		$sonos = sonos3::getSonos();
		return $sonos->getSpeakers();
	}

	public function getControllerByIp($_ip) {
		$controller = null;
		$sonos = sonos3::getSonos();

		try {
			if (!self::$_sonosAddOK) {
				$speakers = array();
				foreach (self::byType('sonos3') as $eqLogic) {
					if ($eqLogic->getIsEnable() == 0) {
						continue;
					}
					if ($eqLogic->getLogicalId() == '') {
						continue;
					}
					$speakers[$eqLogic->getLogicalId()] = new Speaker($eqLogic->getLogicalId());
				}
				$sonos->setSpeakers($speakers);
				self::$_sonosAddOK = true;
			}
			$controller = $sonos->getControllerByIp($_ip);
		} catch (Exception $e) {

		}
		if ($controller == null) {
			try {
				$controller = $sonos->getControllerByIp($_ip);
			} catch (Exception $e) {

			}
		}
		try {
			if ($controller == null) {
				$sonos = sonos3::getSonos(true);
				$controller = $sonos->getControllerByIp($_ip);
			}
		} catch (Exception $e) {

		}

		return $controller;
	}

	/*     * *********************Méthodes d'instance************************* */

	public function preSave() {
		$this->setCategory('multimedia', 1);
	}

	public function postSave() {
		$state = $this->getCmd(null, 'state');
		if (!is_object($state)) {
			$state = new sonos3Cmd();
			$state->setLogicalId('state');
			$state->setIsVisible(1);
			$state->setName(__('Status', __FILE__));
		}
		$state->setType('info');
		$state->setSubType('string');
		$state->setEventOnly(1);
		$state->setEqLogic_id($this->getId());
		$state->save();

		$play = $this->getCmd(null, 'play');
		if (!is_object($play)) {
			$play = new sonos3Cmd();
			$play->setLogicalId('play');
			$play->setIsVisible(1);
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
			$stop->setIsVisible(1);
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
			$pause->setIsVisible(1);
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
			$next->setIsVisible(1);
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
			$previous->setIsVisible(1);
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
			$mute->setIsVisible(1);
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
			$unmute->setIsVisible(1);
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
			$mute_state->setIsVisible(1);
			$mute_state->setName(__('Muet status', __FILE__));
		}
		$mute_state->setType('info');
		$mute_state->setSubType('binary');
		$mute_state->setEventOnly(1);
		$mute_state->setEqLogic_id($this->getId());
		$mute_state->save();

		$repeat = $this->getCmd(null, 'repeat');
		if (!is_object($repeat)) {
			$repeat = new sonos3Cmd();
			$repeat->setLogicalId('repeat');
			$repeat->setIsVisible(1);
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
			$repeat_state->setIsVisible(1);
			$repeat_state->setName(__('Répéter status', __FILE__));
		}
		$repeat_state->setType('info');
		$repeat_state->setSubType('binary');
		$repeat_state->setEventOnly(1);
		$repeat_state->setEqLogic_id($this->getId());
		$repeat_state->save();

		$shuffle = $this->getCmd(null, 'shuffle');
		if (!is_object($shuffle)) {
			$shuffle = new sonos3Cmd();
			$shuffle->setLogicalId('shuffle');
			$shuffle->setIsVisible(1);
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
			$shuffle_state->setIsVisible(1);
			$shuffle_state->setName(__('Aléatoire status', __FILE__));
		}
		$shuffle_state->setType('info');
		$shuffle_state->setSubType('binary');
		$shuffle_state->setEventOnly(1);
		$shuffle_state->setEqLogic_id($this->getId());
		$shuffle_state->save();

		$volume = $this->getCmd(null, 'volume');
		if (!is_object($volume)) {
			$volume = new sonos3Cmd();
			$volume->setLogicalId('volume');
			$volume->setIsVisible(1);
			$volume->setName(__('Volume status', __FILE__));
		}
		$volume->setUnite('%');
		$volume->setType('info');
		$volume->setEventOnly(1);
		$volume->setSubType('numeric');
		$volume->setEqLogic_id($this->getId());
		$volume->save();

		$setVolume = $this->getCmd(null, 'setVolume');
		if (!is_object($setVolume)) {
			$setVolume = new sonos3Cmd();
			$setVolume->setLogicalId('setVolume');
			$setVolume->setIsVisible(1);
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
			$track_title->setIsVisible(1);
			$track_title->setName(__('Piste', __FILE__));
		}
		$track_title->setType('info');
		$track_title->setEventOnly(1);
		$track_title->setSubType('string');
		$track_title->setEqLogic_id($this->getId());
		$track_title->save();

		$track_artist = $this->getCmd(null, 'track_artist');
		if (!is_object($track_artist)) {
			$track_artist = new sonos3Cmd();
			$track_artist->setLogicalId('track_artist');
			$track_artist->setIsVisible(1);
			$track_artist->setName(__('Artiste', __FILE__));
		}
		$track_artist->setType('info');
		$track_artist->setEventOnly(1);
		$track_artist->setSubType('string');
		$track_artist->setEqLogic_id($this->getId());
		$track_artist->save();

		$track_album = $this->getCmd(null, 'track_album');
		if (!is_object($track_album)) {
			$track_album = new sonos3Cmd();
			$track_album->setLogicalId('track_album');
			$track_album->setIsVisible(1);
			$track_album->setName(__('Album', __FILE__));
		}
		$track_album->setType('info');
		$track_album->setEventOnly(1);
		$track_album->setSubType('string');
		$track_album->setEqLogic_id($this->getId());
		$track_album->save();

		$track_position = $this->getCmd(null, 'track_image');
		if (!is_object($track_position)) {
			$track_position = new sonos3Cmd();
			$track_position->setLogicalId('track_image');
			$track_position->setIsVisible(1);
			$track_position->setName(__('Image', __FILE__));
		}
		$track_position->setType('info');
		$track_position->setEventOnly(1);
		$track_position->setSubType('string');
		$track_position->setEqLogic_id($this->getId());
		$track_position->save();

		$play_playlist = $this->getCmd(null, 'play_playlist');
		if (!is_object($play_playlist)) {
			$play_playlist = new sonos3Cmd();
			$play_playlist->setLogicalId('play_playlist');
			$play_playlist->setIsVisible(1);
			$play_playlist->setName(__('Jouer playlist', __FILE__));
		}
		$play_playlist->setType('action');
		$play_playlist->setSubType('message');
		$play_playlist->setDisplay('message_placeholder', __('Options', __FILE__));
		$play_playlist->setDisplay('title_placeholder', __('Titre de la playlist', __FILE__));
		$play_playlist->setEqLogic_id($this->getId());
		$play_playlist->save();

		$play_radio = $this->getCmd(null, 'play_radio');
		if (!is_object($play_radio)) {
			$play_radio = new sonos3Cmd();
			$play_radio->setLogicalId('play_radio');
			$play_radio->setIsVisible(1);
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
			$add_speaker->setIsVisible(1);
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
			$remove_speaker->setIsVisible(1);
			$remove_speaker->setName(__('Supprimer un haut parleur', __FILE__));
		}
		$remove_speaker->setType('action');
		$remove_speaker->setSubType('message');
		$remove_speaker->setDisplay('message_disable', 1);
		$remove_speaker->setDisplay('title_placeholder', __('Nom de la piece', __FILE__));
		$remove_speaker->setEqLogic_id($this->getId());
		$remove_speaker->save();

		$tts = $this->getCmd(null, 'tts');
		if (!is_object($tts)) {
			$tts = new sonos3Cmd();
			$tts->setLogicalId('tts');
			$tts->setIsVisible(1);
			$tts->setName(__('Dire', __FILE__));
		}
		$tts->setType('action');
		$tts->setSubType('message');
		$tts->setDisplay('title_disable', 0);
		$tts->setDisplay('title_placeholder', __('Volume', __FILE__));
		$tts->setDisplay('message_placeholder', __('Message', __FILE__));
		$tts->setEqLogic_id($this->getId());
		$tts->save();
		try {
			self::getRadioStations();
			self::getPlayLists();
		} catch (Exception $e) {

		}
	}

	public function toHtml($_version = 'dashboard') {
		if ($this->getIsEnable() != 1) {
			return '';
		}
		if (!$this->hasRight('r')) {
			return '';
		}
		$version = jeedom::versionAlias($_version);
		if ($this->getDisplay('hideOn' . $version) == 1) {
			return '';
		}
		$mc = cache::byKey('sonosWidget' . $_version . $this->getId());
		if ($mc->getValue() != '') {
			return preg_replace("/" . preg_quote(self::UIDDELIMITER) . "(.*?)" . preg_quote(self::UIDDELIMITER) . "/", self::UIDDELIMITER . mt_rand() . self::UIDDELIMITER, $mc->getValue());
		}
		$replace = array(
			'#id#' => $this->getId(),
			'#info#' => (isset($info)) ? $info : '',
			'#name#' => $this->getName(),
			'#eqLink#' => ($this->hasRight('w')) ? $this->getLinkToConfiguration() : '#',
			'#text_color#' => $this->getConfiguration('text_color'),
			'#background_color#' => '#5d9cec',
			'#hideThumbnail#' => 0,
			'#object_name#' => '',
			'#version#' => $_version,
			'#style#' => '',
			'#uid#' => 'sonos' . $this->getId() . self::UIDDELIMITER . mt_rand() . self::UIDDELIMITER,
		);
		if ($_version == 'dview' || $_version == 'mview') {
			$object = $this->getObject();
			$replace['#name#'] = (is_object($object)) ? $object->getName() . ' - ' . $replace['#name#'] : $replace['#name#'];
		}
		if (($_version == 'dview' || $_version == 'mview') && $this->getDisplay('doNotShowNameOnView') == 1) {
			$replace['#name#'] = '';
		}
		if (($_version == 'mobile' || $_version == 'dashboard') && $this->getDisplay('doNotShowNameOnDashboard') == 1) {
			$replace['#name#'] = '';
		}

		$cmd_state = $this->getCmd(null, 'state');
		if (is_object($cmd_state)) {
			$replace['#state#'] = $cmd_state->execCmd(null, 2);
			if ($replace['#state#'] == __('Lecture', __FILE__)) {
				$replace['#state_nb#'] = 1;
			} else {
				$replace['#state_nb#'] = 0;
			}
		}

		foreach ($this->getCmd('action') as $cmd) {
			$replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
			if ($cmd->getLogicalId() == 'play_playlist') {
				$replace['#playlist#'] = $cmd->getDisplay('title_possibility_list');
			}
			if ($cmd->getLogicalId() == 'play_radio') {
				$replace['#radio#'] = $cmd->getDisplay('title_possibility_list');
			}
		}

		foreach ($this->getCmd('info') as $cmd) {
			$replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
			$replace['#' . $cmd->getLogicalId() . '#'] = $cmd->execCmd(null, 2);
		}
		if ($replace['#mute_state#'] == 1) {
			$replace['#mute_id#'] = $replace['#unmute_id#'];
		}

		$cmd_track_artist = $this->getCmd(null, 'track_artist');
		if (is_object($cmd_track_artist)) {
			$replace['#title#'] = $cmd_track_artist->execCmd(null, 2);
		}

		$cmd_track_album = $this->getCmd(null, 'track_album');
		if (is_object($cmd_track_album)) {
			$replace['#title#'] .= ' - ' . $cmd_track_album->execCmd(null, 2);
		}
		$replace['#title#'] = trim(trim(trim($replace['#title#']), ' - ' . __('Aucun', __FILE__)));

		$cmd_track_title = $this->getCmd(null, 'track_title');
		if (is_object($cmd_track_title)) {
			$replace['#title#'] .= ' - ' . $cmd_track_title->execCmd(null, 2);
		}
		$replace['#title#'] = trim(trim(trim($replace['#title#']), '-'));

		if (strlen($replace['#title#']) > 12) {
			$replace['#title#'] = '<marquee behavior="scroll" direction="left" scrollamount="2">' . $replace['#title#'] . '</marquee>';
		}

		$cmd_track_image = $this->getCmd(null, 'track_image');
		if (is_object($cmd_track_image)) {
			$img = dirname(__FILE__) . '/../../../../plugins/sonos3/sonos_' . $this->getId() . '.jpg';
			if (file_exists($img) && filesize($img) > 500) {
				$replace['#thumbnail#'] = 'plugins/sonos3/sonos_' . $this->getId() . '.jpg?' . md5($cmd_track_image->execCmd(null, 2));
			} else {
				$replace['#thumbnail#'] = 'plugins/sonos3/doc/images/sonos3_alt_icon.png';
			}
		}

		$parameters = $this->getDisplay('parameters');
		if (is_array($parameters)) {
			foreach ($parameters as $key => $value) {
				$replace['#' . $key . '#'] = $value;
			}
		}

		$_version = jeedom::versionAlias($_version);
		$html = template_replace($replace, getTemplate('core', $_version, 'eqLogic', 'sonos3'));
		cache::set('sonosWidget' . $_version . $this->getId(), $html, 0);
		return $html;
	}

	public function getQueue() {
		$sonos = sonos3::getSonos();
		$controller = self::getControllerByIp($this->getLogicalId());
		$queue = $controller->getQueue();
		return $queue->getTracks();
	}

	public function playTrack($_position) {
		$controller = self::getControllerByIp($this->getLogicalId());
		if (!$controller->isUsingQueue()) {
			$controller->useQueue();
		}
		$controller->selectTrack($_position);
		$controller->play();
	}

	public function removeTrack($_position) {
		$controller = self::getControllerByIp($this->getLogicalId());
		$queue = $controller->getQueue();
		$queue->removeTrack($_position);
	}

	public function emptyQueue($_position) {
		$controller = self::getControllerByIp($this->getLogicalId());
		$queue = $controller->getQueue();
		$queue->clear();
	}

	/*     * **********************Getteur Setteur*************************** */
}

class sonos3Cmd extends cmd {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

	public function execute($_options = array()) {
		if ($this->getType() == 'info') {
			return;
		}
		try {
			$eqLogic = $this->getEqLogic();
			$sonos = sonos3::getSonos();
			$controller = sonos3::getControllerByIp($eqLogic->getLogicalId());
			if ($this->getLogicalId() == 'play') {
				if ($eqLogic->getConfiguration('model') == 'PLAYBAR') {
					$state = $eqLogic->getCmd(null, 'state');
					$track_title = $eqLogic->getCmd(null, 'track_title');
					if (is_object($state) && is_object($track_title)) {
						if ($track_title->execCmd(null, 2) == __('Aucun', __FILE__) && $state->execCmd(null, 2) == __('Lecture', __FILE__)) {
							return $controller->unmute();
						}
					}
				}
				$controller->play();
			}
			if ($this->getLogicalId() == 'stop') {
				if ($eqLogic->getConfiguration('model') == 'PLAYBAR') {
					$state = $eqLogic->getCmd(null, 'state');
					$track_title = $eqLogic->getCmd(null, 'track_title');
					if (is_object($state) && is_object($track_title)) {
						if ($track_title->execCmd(null, 2) == __('Aucun', __FILE__) && $state->execCmd(null, 2) == __('Lecture', __FILE__)) {
							return $controller->mute();
						}
					}
				}
				$controller->pause();
			}
			if ($this->getLogicalId() == 'pause') {
				if ($eqLogic->getConfiguration('model') == 'PLAYBAR') {
					$state = $eqLogic->getCmd(null, 'state');
					$track_title = $eqLogic->getCmd(null, 'track_title');
					if (is_object($state) && is_object($track_title)) {
						if ($cmd_track_title->execCmd(null, 2) == __('Aucun', __FILE__) && $state->execCmd(null, 2) == __('Lecture', __FILE__)) {
							return $controller->mute();
						}
					}
				}
				$controller->pause();
			}
			if ($this->getLogicalId() == 'previous') {
				$controller->previous();
			}
			if ($this->getLogicalId() == 'next') {
				$controller->next();
			}
			if ($this->getLogicalId() == 'mute') {
				$controller->mute();
			}
			if ($this->getLogicalId() == 'unmute') {
				$controller->unmute();
			}
			if ($this->getLogicalId() == 'repeat') {
				$controller->setRepeat(!$controller->getRepeat());
			}
			if ($this->getLogicalId() == 'shuffle') {
				$controller->setShuffle(!$controller->getShuffle());
			}
			if ($this->getLogicalId() == 'setVolume') {
				if ($_options['slider'] < 0) {
					$_options['slider'] = 0;
				}
				if ($_options['slider'] > 100) {
					$_options['slider'] = 100;
				}
				$controller->setVolume($_options['slider']);
			}
			if ($this->getLogicalId() == 'play_playlist') {
				if (!$controller->isUsingQueue()) {
					$controller->useQueue();
				}
				$queue = $controller->getQueue();
				$playlist = $sonos->getPlaylistByName(trim(trim($_options['title']), '"'));
				if ($playlist == null) {
					foreach ($sonos->getPlaylists() as $playlist_search) {
						if (str_replace('  ', ' ', $playlist_search->getName()) == $_options['title']) {
							$playlist = $playlist_search;
							break;
						}
					}
				}
				if ($playlist == null) {
					throw new Exception(__('Playlist non trouvé : ', __FILE__) . trim($_options['title']));
				}
				$tracks = $playlist->getTracks();
				$queue->clear();
				if (count($tracks) > 1) {
					if (isset($_options['message']) && $_options['message'] == 'random') {
						shuffle($tracks);
					}
					$queue->addTrack($tracks[0]);
					$controller->play();
					unset($tracks[0]);
					$queue->addTracks($tracks);
				} else {
					$queue->addTracks($tracks);
					$controller->play();
				}
			}
			if ($this->getLogicalId() == 'play_radio') {
				$radio = $sonos->getRadio();
				$stations = $radio->getFavouriteStations();
				if ($stations->getName() == $_options['title']) {
					$controller->useStream($show)->play();
				}
			}
			if ($this->getLogicalId() == 'add_speaker') {
				$speaker = $sonos->getSpeakerByRoom($_options['title']);
				$controller->addSpeaker($speaker);
			}

			if ($this->getLogicalId() == 'remove_speaker') {
				$speaker = $sonos->getSpeakerByRoom($_options['title']);
				$controller->removeSpeaker($speaker);
			}
			if ($this->getLogicalId() == 'tts') {
				$path = explode('/', trim(config::byKey('tts_path', 'sonos3'), '/'));
				$server = new Server(config::byKey('tts_host', 'sonos3'), config::byKey('tts_username', 'sonos3'), config::byKey('tts_password', 'sonos3'));
				$share = $server->getShare($path[0]);
				$adapter = new SmbAdapter($share);
				$filesystem = new Filesystem($adapter);
				$folder = array_pop($path);
				$directory = new Directory($filesystem, config::byKey('tts_host', 'sonos3') . '/' . implode('/', $path), $folder);
				if (config::byKey('ttsProvider', 'sonos3') != 'voxygen' && strlen($_options['message']) > 100) {
					$_options['message'] = substr($_options['message'], 0, 100);
				}
				$track = new TextToSpeech(trim($_options['message']), $directory, new GoogleProvider);
				$track->setLanguage("fr");
				$track->setProvider(new VoxygenProvider);
				$track->getProvider()->setVoice(config::byKey('ttsVoxygenVoice', 'sonos3', 'Helene'));
				if ($_options['title'] != '' && is_numeric($_options['title'])) {
					$controller->interrupt($track, $_options['title']);
				} else {
					$controller->interrupt($track);
				}
			} else {
				sonos3::pull($eqLogic->getId());
			}
		} catch (Exception $e) {
			log::add('sonos', 'info', $e->getMessage());
		}
	}

	/*     * **********************Getteur Setteur*************************** */
}

?>
