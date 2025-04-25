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
  require_once __DIR__ . '/../../../../core/php/core.inc.php';
  include_file('core', 'authentification', 'php');

  if (!isConnect()) {
    throw new Exception(__('401 - Accès non autorisé', __FILE__));
  }

  $id = rand();

  $image_url = base64_decode($_GET["img"]);
  if ($image_url === false || $image_url == '') {
    log::add('sonos3', 'debug', "empty url => return alt icon");
    $image_content = file_get_contents(__DIR__ . '/../../plugin_info/sonos3_alt_icon.png');
  } else {
    $image_content = sonos3::getImageContent($image_url);
  }

  if ($image_content === false) die();

  $finfo = new finfo(FILEINFO_MIME_TYPE);
  $type = $finfo->buffer($image_content);

  if (strstr($type, 'image/')) {
    header("Content-type: {$type};");
    echo $image_content;
  }

  die();
} catch (Exception $e) {
  ajax::error(displayException($e), $e->getCode());
}
