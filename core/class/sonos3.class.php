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
use duncan3dc\Sonos\Network;

class sonos3 extends eqLogic {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	public static function getSonos() {
		$cache = new \Doctrine\Common\Cache\FilesystemCache("/tmp/sonos-cache");
		return new Network($cache);
	}

	public static function syncSonos() {
		$sonos = self::getSonos();
		$controllers = $sonos->getControllers();
		foreach ($controllers as $controller) {
			$eqLogic = sonos3::byLogicalId($controller->ip, 'sonos3');
			if (!is_object($eqLogic)) {
				$eqLogic = new self();
				$eqLogic->setLogicalId($controller->ip);
				$eqLogic->setName($controller->room . ' - ' . $controller->name);
				$eqLogic->setEqType_name('sonos3');
				$eqLogic->setIsVisible(1);
				$eqLogic->setIsEnable(1);
				$eqLogic->setCategory('multimedia', 1);
				$eqLogic->save();
			}
		}
	}

	public static function pull() {
		$sonos = self::getSonos();
		foreach (self::byType('sonos3') as $eqLogic) {
			$changed = false;
			$controller = $sonos->getControllerByIp($eqLogic->getLogicalId());
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
			if (is_object($cmd_track_title)) {
				if ($track->title != $cmd_track_title->execCmd(null, 2)) {
					$cmd_track_title->setCollectDate('');
					$cmd_track_title->event($track->title);
					$changed = true;
				}
			}

			$cmd_track_album = $eqLogic->getCmd(null, 'track_album');
			if (is_object($cmd_track_album)) {
				if ($track->album != $cmd_track_album->execCmd(null, 2)) {
					$cmd_track_album->setCollectDate('');
					$cmd_track_album->event($track->album);
					$changed = true;
				}
			}

			$cmd_track_artist = $eqLogic->getCmd(null, 'track_artist');
			if (is_object($cmd_track_artist)) {
				if ($track->artist != $cmd_track_artist->execCmd(null, 2)) {
					$cmd_track_artist->setCollectDate('');
					$cmd_track_artist->event($track->artist);
					$changed = true;
				}
			}

			$cmd_track_image = $eqLogic->getCmd(null, 'track_image');
			if (is_object($cmd_track_image)) {
				if ($track->albumArt != $cmd_track_image->execCmd(null, 2)) {
					$cmd_track_image->setCollectDate('');
					$cmd_track_image->event($track->albumArt);
					file_put_contents(dirname(__FILE__) . '/../../../../plugins/sonos3/sonos_' . $eqLogic->getId() . '.jpg', file_get_contents($track->albumArt));
					$changed = true;
				}
			}

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
		}
		return '';
	}

	/*     * *********************Méthodes d'instance************************* */

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
			$previous->setName(__('Precedent', __FILE__));
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

		$track_duration = $this->getCmd(null, 'track_duration');
		if (!is_object($track_duration)) {
			$track_duration = new sonos3Cmd();
			$track_duration->setLogicalId('track_duration');
			$track_duration->setIsVisible(1);
			$track_duration->setName(__('Durée', __FILE__));
		}
		$track_duration->setType('info');
		$track_duration->setEventOnly(1);
		$track_duration->setSubType('string');
		$track_duration->setEqLogic_id($this->getId());
		$track_duration->save();

		$track_position = $this->getCmd(null, 'track_position');
		if (!is_object($track_position)) {
			$track_position = new sonos3Cmd();
			$track_position->setLogicalId('track_position');
			$track_position->setIsVisible(1);
			$track_position->setName(__('Position', __FILE__));
		}
		$track_position->setType('info');
		$track_position->setEventOnly(1);
		$track_position->setSubType('string');
		$track_position->setEqLogic_id($this->getId());
		$track_position->save();

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
		$play_playlist->setEqLogic_id($this->getId());
		$play_playlist->save();

	}

	public function toHtml($_version = 'dashboard') {
		if ($this->getIsEnable() != 1) {
			return '';
		}
		$_version = jeedom::versionAlias($_version);

		$replace = array(
			'#id#' => $this->getId(),
			'#info#' => (isset($info)) ? $info : '',
			'#name#' => $this->getName(),
			'#eqLink#' => $this->getLinkToConfiguration(),
			'#text_color#' => $this->getConfiguration('text_color'),
			'#background_color#' => $this->getBackgroundColor($version),
		);

		$cmd_state = $this->getCmd(null, 'state');
		if (is_object($cmd_state)) {
			$replace['#state#'] = $cmd_state->execCmd(null, 2);
			if ($replace['#state#'] == __('Lecture', __FILE__)) {
				$replace['#state_nb#'] = 1;
			} else {
				$replace['#state_nb#'] = 0;
			}
		}

		$cmd_volume = $this->getCmd(null, 'volume');
		if (is_object($cmd_volume)) {

		}

		$cmd_track_artist = $this->getCmd(null, 'track_artist');
		if (is_object($cmd_track_artist)) {
			$replace['#title#'] = $cmd_track_artist->execCmd(null, 2);
		}

		$cmd_track_album = $this->getCmd(null, 'track_album');
		if (is_object($cmd_track_album)) {
			$replace['#title#'] .= ' - ' . $cmd_track_album->execCmd(null, 2);
		}

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
			$replace['#thumbnail#'] = '<img style="width : 180px;" src="plugins/sonos3/sonos_' . $this->getId() . '.jpg?' . md5($cmd_track_image->execCmd(null, 2)) . '" />';
		}

		$cmd_volume = $this->getCmd(null, 'volume');
		if (is_object($cmd_volume)) {
			$replace['#volume#'] = $cmd_volume->execCmd(null, 2);
		}

		$cmd_setVolume = $this->getCmd(null, 'setVolume');
		if (is_object($cmd_setVolume)) {
			$replace['#volume_id#'] = $cmd_setVolume->getId();
		}

		$cmd_repeate = $this->getCmd(null, 'repeat_state');
		if (is_object($cmd_repeate)) {
			$replace['#repeat_state#'] = $cmd_repeate->execCmd(null, 2);
		}

		$cmd_shuffle = $this->getCmd(null, 'shuffle_state');
		if (is_object($cmd_shuffle)) {
			$replace['#shuffle_state#'] = $cmd_shuffle->execCmd(null, 2);
		}

		foreach ($this->getCmd('action') as $cmd) {
			$replace['#cmd_' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
		}

		return template_replace($replace, getTemplate('core', $_version, 'eqLogic', 'sonos3'));
	}

	public function getQueue() {
		$sonos = sonos3::getSonos();
		$controller = $sonos->getControllerByIp($this->getLogicalId());
		$queue = $controller->getQueue();
		return $queue->getTracks();
	}

	public function playTrack($_position) {
		$sonos = sonos3::getSonos();
		$controller = $sonos->getControllerByIp($this->getLogicalId());
		$controller->selectTrack($_position);
		$controller->play();
	}

	public function removeTrack($_position) {
		$sonos = sonos3::getSonos();
		$controller = $sonos->getControllerByIp($this->getLogicalId());
		$queue = $controller->getQueue();
		$queue->removeTracks(array($_position));
	}

	/*     * **********************Getteur Setteur*************************** */
}

class sonos3Cmd extends cmd {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

	public function execute($_options = array()) {
		$sonos = sonos3::getSonos();
		$eqLogic = $this->getEqLogic();
		$controller = $sonos->getControllerByIp($eqLogic->getLogicalId());
		if ($this->getLogicalId() == 'play') {
			$controller->play();
		}
		if ($this->getLogicalId() == 'stop') {
			$controller->pause();
		}
		if ($this->getLogicalId() == 'pause') {
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
			$controller->setVolume($_options['slider']);
		}
		if ($this->getLogicalId() == 'play_playlist') {
			$playlist = $sonos->getPlaylistByName(trim($_options['title'] . $_options['message']));
			$tracks = $playlist->getTracks();
			$queue = $controller->getQueue();
			$queue->addTracks($tracks);
		}
	}

	/*     * **********************Getteur Setteur*************************** */
}

?>
