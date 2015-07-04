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
      <label class="col-lg-4 control-label">{{Fournisseur TTS}}</label>
      <div class="col-lg-2">
        <select class="configKey tooltips form-control" data-l1key="ttsProvider">
          <option value="google_translate">Google Translate</option>
          <option value="google">Google</option>
          <option value="voxygen">Voxygen</option>
        </select>
      </div>
      <div class="col-lg-2">
       <select class="configKey tooltips form-control" data-l1key="ttsVoxygenVoice">
         <optgroup label="Arabic">
           <option value="Adel">Adel</option>
         </optgroup>
         <optgroup label="Deutch">
           <option value="Matthias">Matthias</option>
           <option value="Sylvia">Sylvia</option>
         </optgroup>
         <optgroup label="English U.K.">
           <option value="Bibi">Bibi</option>
           <option value="Bronwen">Bronwen</option>
           <option value="Elizabeth">Elizabeth</option>
           <option value="Paul">Paul</option>
         </optgroup>
         <optgroup label="English U.S.">
           <option value="Amanda">Amanda</option>
           <option value="Phil">Phil</option>
         </optgroup>
         <optgroup label="Español">
           <option value="Marta">Marta</option>
         </optgroup>
         <optgroup label="Français">
           <option value="Loic">Loic</option>
           <option value="Agnes">Agnes</option>
           <option value="Melodine">Melodine</option>
           <option value="Chut">Chut</option>
           <option value="Bicool">Bicool</option>
           <option value="Philippe">Philippe</option>
           <option value="Electra">Electra</option>
           <option value="Damien">Damien</option>
           <option value="DarkVadoor">DarkVadoor</option>
           <option value="Ramboo">Ramboo</option>
           <option value="John">John</option>
           <option value="Helene" selected>Helene</option>
           <option value="Eva">Eva</option>
           <option value="JeanJean">JeanJean</option>
           <option value="Papi">Papi</option>
           <option value="Robot">Robot</option>
           <option value="Sidoo">Sidoo</option>
           <option value="Sorciere">Sorciere</option>
           <option value="Yeti">Yeti</option>
           <option value="Zozo">Zozo</option>
         </optgroup>
         <optgroup label="Italiano">
           <option value="Sonia">Sonia</option>
         </optgroup>
       </select>
     </div>
   </div>
   <div class="form-group useShare">
    <label class="col-lg-4 control-label">{{[TTS] Chemin local du répertoire partagé}}</label>
    <div class="col-lg-2">
      <input class="configKey tooltips form-control" data-l1key="localpath" placeholder="/local/path" />
    </div>
  </div>
  <div class="form-group useShare">
    <label class="col-lg-4 control-label">{{[TTS] Chemin Sonos du répertoire partagé}}</label>
    <div class="col-lg-2">
      <input class="configKey tooltips form-control" data-l1key="pathToSmb" placeholder="hostname/path/to/smb" />
    </div>
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
  $('.configKey[data-l1key="ttsProvider"]').on('change', function () {
   $('.configKey[data-l1key="ttsVoxygenVoice"]').toggle( this.value == 'voxygen');
   if( this.value == 'google_translate'){
    $('.useShare').hide();
  }else{
    $('.useShare').show();
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
