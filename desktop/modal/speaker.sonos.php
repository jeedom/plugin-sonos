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
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$sonos = sonos3::byId(init('id'));
if (!is_object($sonos)) {
	throw new Exception("Equipement non trouvé");
}
?>
<div id='div_speakerSonosAlert' style="display: none;"></div>
<table class="table table-condensed">
    <thead>
        <tr>
            <th style="width : 60px;">{{Action}}</th>
            <th>{{Radio}}</th>
        </tr>
    </thead>
    <tbody>
        <?php
foreach (sonos3::getSpeaker() as $speaker) {
	echo '<tr>';
	echo '<td>';
	echo '<a class="removeSpeaker btn btn-xs btn-danger" data-sonos_id="' . init('id') . '" data-name="' . $speaker->room . '"><i class="fa fa-minus-circle"></i></a> ';
	if ($speaker->ip != $sonos->getLogicalId()) {
		echo '<a class="addSpeaker btn btn-xs btn-success" data-sonos_id="' . init('id') . '" data-name="' . $speaker->room . '"><i class="fa fa-plus-circle"></i></a>';
	}
	echo '</td>';
	echo '<td>';
	echo $speaker->room;
	echo '</td>';
	echo '</tr>';
}
?>
   </tbody>
</table>

<script>
 $('.addSpeaker').on('click',function(){
    var id = $(this).attr('data-sonos_id');
    var name = $(this).attr('data-name');
 $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "plugins/sonos3/core/ajax/sonos3.ajax.php", // url du fichier php
            data: {
                action: "addSpeaker",
                id :id,
                speaker : name
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error,$('#div_speakerSonosAlert'));
            },
            success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_speakerSonosAlert').showAlert({message: data.result, level: 'danger'});
                return;
            }
        }
    });
});

 $('.removeSpeaker').on('click',function(){
    var id = $(this).attr('data-sonos_id');
    var name = $(this).attr('data-name');
 $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "plugins/sonos3/core/ajax/sonos3.ajax.php", // url du fichier php
            data: {
                action: "removeSpeaker",
                id :id,
                speaker : name
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error,$('#div_speakerSonosAlert'));
            },
            success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_speakerSonosAlert').showAlert({message: data.result, level: 'danger'});
                return;
            }
        }
    });
});
</script>




