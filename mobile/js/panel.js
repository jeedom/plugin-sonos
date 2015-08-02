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

 function initSonos3Panel(_object_id) {
    jeedom.object.all({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (objects) {
            var li = ' <ul data-role="listview">';
            for (var i in objects) {
                if (objects[i].isVisible == 1) {
                    var icon = '';
                    if (isset(objects[i].display) && isset(objects[i].display.icon)) {
                        icon = objects[i].display.icon;
                    }
                    li += '<li></span><a href="#" class="link" data-page="panel" data-plugin="sonos3" data-title="' + icon.replace(/\"/g, "\'") + ' ' + objects[i].name + '" data-option="' + objects[i].id + '"><span>' + icon + '</span> ' + objects[i].name + '</a></li>';
                }
            }
            li += '</ul>';
            panel(li);
        }
    });
    displaySonos(_object_id);

    $(window).on("orientationchange", function (event) {
        setTileSize('.eqLogic');
        $('#div_displayEquipement').packery({gutter : 4});
    });
}

function displaySonos(_object_id) {
    $.showLoading();
    $.ajax({
        type: 'POST',
        url: 'plugins/sonos3/core/ajax/sonos3.ajax.php',
        data: {
            action: 'getSonos',
            object_id: _object_id,
            version: 'mview'
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) {
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#div_displayEquipement').empty();
            for (var i in data.result.eqLogics) {
                $('#div_displayEquipement').append(data.result.eqLogics[i]).trigger('create');
            }
            setTileSize('.eqLogic');
            $('#div_displayEquipement').packery({gutter : 4});
            $.hideLoading();
        }
    });
}