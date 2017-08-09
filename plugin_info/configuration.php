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
	echo '<div class="alert alert-danger">{{Attention votre version de PHP (' . PHP_VERSION . ') est trop veille il faut au minimum PHP 7.0}}</div>';
}
?>
   <div class="form-group">
     <label class="col-lg-3 control-label">{{Le plugin sonos doit réagir aux interactions :}}</label>
     <div class="col-lg-4">
      <textarea class="configKey form-control" data-l1key="interact::sentence"></textarea>
    </div>
  </div>
  <div class="form-group useShare">
    <label class="col-lg-3 control-label">{{Partage}}</label>
    <div class="col-lg-2">
     <div class="input-group">
       <input class="configKey form-control" data-l1key="tts_host" />
       <div class="input-group-addon">/</div>
       <input class="configKey form-control" data-l1key="tts_path" />
     </div>
   </div>
 </div>
 <div class="form-group useShare">
  <label class="col-lg-3 control-label">{{Nom d'utilisateur pour le partage}}</label>
  <div class="col-lg-2">
    <input class="configKey form-control" data-l1key="tts_username" />
  </div>
</div>
<div class="form-group useShare">
  <label class="col-lg-3 control-label">{{Mot de passe du partage}}</label>
  <div class="col-lg-2">
    <input type="password" class="configKey form-control" data-l1key="tts_password" />
  </div>
</div>
</div>
<div class="form-group">
 <label class="col-lg-3 control-label">{{Découverte}}</label>
 <div class="col-lg-2">
  <a class="btn btn-default" id="bt_syncSonos"><i class='fa fa-refresh'></i> {{Rechercher les équipements Sonos}}</a>
</div>
</div>
</fieldset>
</form>

<script>
  $('.configKey[data-l1key=ttsProvider').on('change',function(){
    $('.configKey[data-l1key=ttsVoxygenVoice').closest('.form-group').hide();
    if($(this).value() == 'voxygen'){
      $('.configKey[data-l1key=ttsVoxygenVoice').closest('.form-group').show();
    }
  });

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
