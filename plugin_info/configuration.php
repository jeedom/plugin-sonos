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
if (!isConnect()) {
	include_file('desktop', '404', 'php');
	die();
}
?>
<form class="form-horizontal">
	<fieldset>
      <div class="form-group">
        <label class="col-lg-4 control-label">{{[TTS] Chemin local du répertoire partagé}}</label>
        <div class="col-lg-2">
            <input class="configKey tooltips form-control" data-l1key="localpath" placeholder="hostname/path/to/smb" />
        </div>
    </div>
        <div class="form-group">
        <label class="col-lg-4 control-label">{{[TTS] Chemin Sonos du répertoire paratagé}}</label>
        <div class="col-lg-2">
            <input class="configKey tooltips form-control" data-l1key="pathToSmb" placeholder="hostname/path/to/smb" />
        </div>
    </div>
		<div class="form-group">
			<label class="col-lg-4 control-label">{{Découverte}}</label>
			<div class="col-lg-2">
				<a class="btn btn-default" id="bt_syncSonos"><i class='fa fa-refresh'></i> {{Rechercher les équipements Sonos}}</a>
			</div>
		</div>
	</fieldset>
</form>

<script>
	$('#bt_syncSonos').on('click', function () {
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "plugins/sonos3/core/ajax/sonos3.ajax.php", // url du fichier php
            data: {
            	action: "syncSonos",
            },
            dataType: 'json',
            error: function (request, status, error) {
            	handleAjaxError(request, status, error);
            },
            success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
            	$('#div_alert').showAlert({message: data.result, level: 'danger'});
            	return;
            }
            $('#div_alert').showAlert({message: '{{Synchronisation réussie}}', level: 'success'});
        }
    });
    });
</script>
