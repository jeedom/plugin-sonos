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

try {
	require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
	include_file('core', 'authentification', 'php');

	if (!isConnect('admin')) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}

	if (init('action') == 'syncSonos') {
		sonos3::syncSonos();
		ajax::success();
	}

	if (init('action') == 'getQueue') {
		$sonos = sonos3::byId(init('id'));
		if (!is_object($sonos)) {
			ajax::success();
		}
		ajax::success($sonos->getQueue());
	}

	if (init('action') == 'playTrack') {
		$sonos = sonos3::byId(init('id'));
		if (!is_object($sonos)) {
			ajax::success();
		}
		ajax::success($sonos->playTrack(init('position')));
	}

	if (init('action') == 'removeTrack') {
		$sonos = sonos3::byId(init('id'));
		if (!is_object($sonos)) {
			ajax::success();
		}
		ajax::success($sonos->removeTrack(init('position')));
	}

	if (init('action') == 'emptyQueue') {
		$sonos = sonos3::byId(init('id'));
		if (!is_object($sonos)) {
			ajax::success();
		}
		ajax::success($sonos->emptyQueue());
	}

	if (init('action') == 'playPlaylist') {
		$sonos = sonos3::byId(init('id'));
		if (!is_object($sonos)) {
			ajax::success();
		}
		$cmd = $sonos->getCmd(null, 'play_playlist');
		$cmd->execCmd(array('title' => init('playlist')));
		ajax::success();
	}

	if (init('action') == 'playRadio') {
		$sonos = sonos3::byId(init('id'));
		if (!is_object($sonos)) {
			ajax::success();
		}
		$cmd = $sonos->getCmd(null, 'play_radio');
		$cmd->execCmd(array('title' => init('radio')));
		ajax::success();
	}

	if (init('action') == 'addSpeaker') {
		$sonos = sonos3::byId(init('id'));
		if (!is_object($sonos)) {
			ajax::success();
		}
		$cmd = $sonos->getCmd(null, 'add_speaker');
		$cmd->execCmd(array('title' => init('speaker')));
		ajax::success();
	}

	if (init('action') == 'removeSpeaker') {
		$sonos = sonos3::byId(init('id'));
		if (!is_object($sonos)) {
			ajax::success();
		}
		$cmd = $sonos->getCmd(null, 'remove_speaker');
		$cmd->execCmd(array('title' => init('speaker')));
		ajax::success();
	}

	if (init('action') == 'getSonos') {
		if (init('object_id') == '') {
			$object = object::byId($_SESSION['user']->getOptions('defaultDashboardObject'));
		} else {
			$object = object::byId(init('object_id'));
		}
		if (!is_object($object)) {
			$object = object::rootObject();
		}
		$return = array();
		$return['eqLogics'] = array();
		if (init('object_id') == '') {
			foreach (object::all() as $object) {
				foreach ($object->getEqLogic(true, false, 'sonos3') as $sonos) {
					$return['eqLogics'][] = $sonos->toHtml(init('version'));
				}
			}
		} else {
			foreach ($object->getEqLogic(true, false, 'sonos3') as $sonos) {
				$return['eqLogics'][] = $sonos->toHtml(init('version'));
			}
			foreach (object::buildTree($object) as $child) {
				$sonoss = $child->getEqLogic(true, false, 'sonos3');
				if (count($sonoss) > 0) {
					foreach ($sonoss as $sonos) {
						$return['eqLogics'][] = $sonos->toHtml(init('version'));
					}
				}
			}
		}
		ajax::success($return);
	}

	throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
?>
