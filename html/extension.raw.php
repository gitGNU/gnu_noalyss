<?php
/*
 *   This file is part of NOALYSS.
 *
 *   NOALYSS is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   NOALYSS is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with NOALYSS; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Copyright Author Dany De Bontridder danydb@aevalys.eu

/*!\file
 * \brief this file includes the called plugin. It  check first
 * the security. Load several javascript files
 */
require_once '../include/constant.php';
require_once('class_database.php');
require_once('class_dossier.php');
require_once("ac_common.php");
require_once("constant.php");
require_once('function_javascript.php');
require_once('class_extension.php');
require_once ('class_html_input.php');
require_once('class_iselect.php');
require_once ('constant.security.php');
require_once ('class_user.php');

global $g_user,$cn,$g_parameter;

$cn=new Database(dossier::id());
$g_user=new User($cn);
$g_user->check();
$only_plugin=$g_user->check_dossier(dossier::id());

$ext=new Extension($cn);

if ( $ext->search($_REQUEST['plugin_code']) != -1 )
  {
    /* security */
    if ( !isset ($_SESSION['g_user']) || $ext->can_request($_SESSION['g_user']) == 0 )
      {
		exit();
      }
    /* call the ajax script */
    require_once('ext'.DIRECTORY_SEPARATOR.dirname(trim($ext->getp('me_file'))).DIRECTORY_SEPARATOR.'raw.php');
  }
else
  {
    alert(j(_("Cette extension n'existe pas ")));
    exit();
  }
?>