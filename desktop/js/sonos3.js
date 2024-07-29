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

function printEqLogic(_eqLogic) {
  if (_eqLogic.configuration.model && _eqLogic.configuration.model != '') {
    $('#img_sonosModel').attr('src', 'plugins/sonos3/core/img/' + _eqLogic.configuration.model + '.png')
  } else {
    $('#img_sonosModel').attr('src', 'plugins/sonos3/plugin_info/sonos3_icon.png')
  }
}

function addCmdToTable(_cmd) {
  if (!isset(_cmd)) {
    _cmd = { configuration: {} }
  }
  if (!isset(_cmd.configuration)) {
    _cmd.configuration = {}
  }
  let tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">'
  tr += '<td class="hidden-xs">'
  tr += '<span class="cmdAttr" data-l1key="id"></span>'
  tr += '</td>'
  tr += '<td>'
  tr += '<div class="input-group">'
  tr += '<input class="cmdAttr form-control input-sm roundedLeft" data-l1key="name" placeholder="{{Nom}}">'
  tr += '<span class="input-group-btn"><a class="cmdAction btn btn-sm btn-default" data-l1key="chooseIcon" title="{{Choisir une icône}}"><i class="fas fa-icons"></i></a></span>'
  tr += '<span class="cmdAttr input-group-addon roundedRight" data-l1key="display" data-l2key="icon" style="font-size:19px;padding:0 5px 0 0!important;"></span>'
  tr += '</div>'
  tr += '</td>'
  tr += '<td>'
  tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>'
  tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>'
  tr += '</td>'
  tr += '<td>';
  tr += '<span class="cmdAttr" data-l1key="htmlstate"></span>';
  tr += '</td>';
  tr += '<td>'
  if (is_numeric(_cmd.id)) {
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> '
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>'
  }
  tr += '</td>'
  tr += '</tr>'
  $('#table_cmd tbody').append(tr)
  const $tr = $('#table_cmd tbody tr').last()
  $tr.setValues(_cmd, '.cmdAttr')
  if (isset(_cmd.type)) {
    $('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type))
  }
  jeedom.cmd.changeType($tr, init(_cmd.subType))
  $tr.find('.cmdAttr[data-l1key=type],.cmdAttr[data-l1key=subType]').prop("disabled", true);
}

$('.eqLogicAction[data-action=sync]').on('click', function () {
  $.ajax({
    type: "POST",
    url: "plugins/sonos3/core/ajax/sonos3.ajax.php",
    data: {
      action: "sync",
    },
    dataType: 'json',
    error: function (request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function (data) {
      if (data.state != 'ok') {
        $('#div_alert').showAlert({ message: data.result, level: 'danger' });
        return;
      }
      $('#div_alert').showAlert({ message: '{{Synchronisation réussie.}}', level: 'success' });
      setTimeout(function () {
        window.location.replace("index.php?v=d&m=sonos3&p=sonos3");
      }, 3000);
    }
  });
});