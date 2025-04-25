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

	if (!isConnect()) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}

	ajax::init();

	if (init('action') == 'sync') {
		sonos3::syncAll();
		ajax::success();
	}

	// if (init('action') == 'playTrack') {
	// 	/** @var sonos3 */
	// 	$sonos = sonos3::byId(init('id'));
	// 	if (!is_object($sonos)) {
	// 		ajax::success();
	// 	}
	// 	ajax::success($sonos->playTrack(init('position')));
	// }

	if (init('action') == 'playPlaylist') {
		/** @var sonos3 */
		$sonos = sonos3::byId(init('id'));
		if (!is_object($sonos)) {
			ajax::success();
		}
		$cmd = $sonos->getCmd(null, 'play_playlist');
		$cmd->execCmd(array('title' => init('playlist')));
		ajax::success();
	}

	if (init('action') == 'playRadio') {
		/** @var sonos3 */
		$sonos = sonos3::byId(init('id'));
		if (!is_object($sonos)) {
			ajax::success();
		}
		$cmd = $sonos->getCmd(null, 'play_radio');
		$cmd->execCmd(array('title' => init('radio')));
		ajax::success();
	}

	if (init('action') == 'playFavorite') {
		/** @var sonos3 */
		$sonos = sonos3::byId(init('id'));
		if (!is_object($sonos)) {
			ajax::success();
		}
		$cmd = $sonos->getCmd(null, 'play_favorite');
		$cmd->execCmd(array('title' => init('favorite')));
		ajax::success();
	}

	if (init('action') == 'join') {
		/** @var sonos3 */
		$sonos = sonos3::byId(init('id'));
		if (!is_object($sonos)) {
			ajax::success();
		}
		$cmd = $sonos->getCmd('action', 'join');
		$cmd->execCmd(array('title' => init('speaker')));
		ajax::success();
	}

	if (init('action') == 'unjoin') {
		/** @var sonos3 */
		$sonos = sonos3::byId(init('id'));
		if (!is_object($sonos)) {
			ajax::success();
		}
		$cmd = $sonos->getCmd('action', 'unjoin');
		$cmd->execCmd();
		ajax::success();
	}

	if (init('action') == 'getSonos') {
		if (init('object_id') == '') {
			$object = jeeObject::byId($_SESSION['user']->getOptions('defaultDashboardObject'));
		} else {
			$object = jeeObject::byId(init('object_id'));
		}
		if (!is_object($object)) {
			$object = jeeObject::rootObject();
		}
		$return = array();
		$return['eqLogics'] = array();
		if (init('object_id') == '') {
			foreach (jeeObject::all() as $object) {
				foreach ($object->getEqLogic(true, false, 'sonos3') as $sonos) {
					$return['eqLogics'][] = $sonos->toHtml(init('version'));
				}
			}
		} else {
			foreach ($object->getEqLogic(true, false, 'sonos3') as $sonos) {
				$return['eqLogics'][] = $sonos->toHtml(init('version'));
			}
			foreach (jeeObject::buildTree($object) as $child) {
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

	throw new Exception(__('Aucune méthode correspondante à', __FILE__) . ' : ' . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayException($e), $e->getCode());
}
