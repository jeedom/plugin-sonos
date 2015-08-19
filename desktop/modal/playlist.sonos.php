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
?>
<div id='div_playListSonosAlert' style="display: none;"></div>
<table class="table table-condensed">
    <thead>
        <tr>
            <th>{{Action}}</th>
            <th>{{Playlist}}</th>
        </tr>
    </thead>
    <tbody>
        <?php
foreach (sonos3::getPlayLists() as $playlist) {
	echo '<tr>';
	echo '<td>';
	echo '<a class="playPlaylist btn btn-xs btn-primary" data-sonos_id="' . init('id') . '" data-name="' . $playlist->getName() . '"><i class="fa fa-play"></i></a>';
	echo '</td>';
	echo '<td>';
	echo $playlist->getName();
	echo '</td>';
	echo '</tr>';
}
?>
   </tbody>
</table>

<script>
   $('.playPlaylist').on('click',function(){
    var id = $(this).attr('data-sonos_id');
    var name = $(this).attr('data-name');
 $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "plugins/sonos3/core/ajax/sonos3.ajax.php", // url du fichier php
            data: {
                action: "playPlaylist",
                id :id,
                playlist : name
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error,$('#div_playListSonosAlert'));
            },
            success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_playListSonosAlert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#md_modal2').dialog('close');
        }
    });
});

</script>




