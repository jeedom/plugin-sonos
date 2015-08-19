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
<div id='div_queueSonosAlert' style="display: none;"></div>
<a class="btn btn-danger pull-right" id="bt_emptyQueue" data-sonos_id="<?php echo init('id');?>"><i class="fa fa-trash-o"></i> {{Vider}}</a>
<br/><br/>
<table class="table table-condensed">
    <thead>
        <tr>
            <th style="width : 60px;">{{Action}}</th>
            <th>{{Artiste}}</th>
            <th>{{Album}}</th>
            <th>{{Piste}}</th>
        </tr>
    </thead>
    <tbody>
        <?php
$i = 0;
foreach ($sonos->getQueue() as $track) {
	echo '<tr>';
	echo '<td>';
	echo '<a class="removeTrack btn btn-xs btn-danger" data-position="' . $i . '" data-sonos_id="' . init('id') . '"><i class="fa fa-trash-o"></i></a> ';
	echo '<a class="playTrack btn btn-xs btn-primary" data-position="' . $i . '" data-sonos_id="' . init('id') . '"><i class="fa fa-play"></i></a>';
	echo '</td>';
	echo '<td>';
	echo $track->artist;
	echo '</td>';
	echo '<td>';
	echo $track->album;
	echo '</td>';
	echo '<td>';
	echo $track->title;
	echo '</td>';
	echo '</tr>';
	$i++;
}
?>
   </tbody>
</table>

<script>
  $('#bt_emptyQueue').on('click',function(){
    var id = $(this).attr('data-sonos_id');

 $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "plugins/sonos3/core/ajax/sonos3.ajax.php", // url du fichier php
            data: {
                action: "emptyQueue",
                id :id,
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error,$('#div_queueSonosAlert'));
            },
            success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_queueSonosAlert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#md_modal2').dialog('close');
        }
    });
});


  $('.playTrack').on('click',function(){
    var id = $(this).attr('data-sonos_id');
    var position = $(this).attr('data-position');
 $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "plugins/sonos3/core/ajax/sonos3.ajax.php", // url du fichier php
            data: {
                action: "playTrack",
                id :id,
                position : position
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error,$('#div_queueSonosAlert'));
            },
            success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_queueSonosAlert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#md_modal2').dialog('close');
        }
    });
});


  $('.removeTrack').on('click',function(){
    var id = $(this).attr('data-sonos_id');
    var position = $(this).attr('data-position');
 $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "plugins/sonos3/core/ajax/sonos3.ajax.php", // url du fichier php
            data: {
                action: "removeTrack",
                id :id,
                position : position
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error,$('#div_queueSonosAlert'));
            },
            success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_queueSonosAlert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#md_modal2').load('index.php?v=d&plugin=sonos3&modal=queue.sonos&id=' + id).dialog('open');
        }
    });
});
</script>




