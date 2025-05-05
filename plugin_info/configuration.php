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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<form class="form-horizontal">
	<fieldset>
		<?php
		if (version_compare(PHP_VERSION, '7.0') < 0) {
			echo '<div class="alert alert-danger">{{Attention votre version de PHP (' . PHP_VERSION . ') est trop veille, il faut au minimum PHP 7.0.}}</div>';
		}
		?>
		<legend><i class="far fa-comments"></i> {{Interactions}}</legend>
		<div class="form-group">
			<label class="col-sm-3 control-label">{{Le plugin sonos doit réagir aux interactions}}</label>
			<div class="col-sm-7">
				<textarea class="configKey form-control" data-l1key="interact::sentence"></textarea>
			</div>
		</div>
		<legend><i class="fas fa-share-alt"></i> {{Partage}}</legend>
		<div class="form-group">
			<label class="col-sm-3 control-label">{{Partage}}</label>
			<div class="col-sm-7">
				<div class="input-group">
					<div class="input-group-addon roundedLeft">{{Hôte}}</div>
					<input class="configKey form-control" title="Nom d'hôte ou IP" data-l1key="tts_host">
					<div class="input-group-addon">/</div>
					<input class="configKey form-control" title="Nom du partage (pas de '/'!)" data-l1key="tts_share">
					<div class="input-group-addon">/</div>
					<input class="configKey form-control roundedRight" title="Chemin (peut contenir des '/')" data-l1key="tts_path">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">{{Nom d'utilisateur du partage}}</label>
			<div class="col-sm-7">
				<input class="configKey form-control" data-l1key="tts_username">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">{{Mot de passe du partage}}</label>
			<div class="col-sm-7">
				<div class="input-group">
					<input type="text" class="configKey form-control roundedLeft inputPassword" data-l1key="tts_password" autocomplete="off" />
					<span class="input-group-btn">
						<a class="btn btn-default form-control bt_showPass roundedRight"><i class="fas fa-eye"></i></a>
					</span>
				</div>
			</div>
		</div>
		<legend><i class="fas fa-search"></i> {{Découverte}}</legend>
		<div class="form-group">
			<label class="col-sm-3 control-label">{{Réseau à scanner}}
				<sup><i class="fas fa-question-circle tooltips" title="{{Si et seulement si Jeedom ne se trouve pas sur le même réseau que vos enceintes Sonos}}"></i></sup>
			</label>
			<div class="col-sm-7">
				<input class="configKey form-control" data-l1key="networksToScan">
			</div>
		</div>
	</fieldset>
</form>