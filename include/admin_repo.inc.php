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
/*! \file
 * \brief Administration of the repository : creation of user, folder, security,
 *        templates... Accessible only by the administrator
 */
if ( ! defined ('ALLOWED')) { die (_('Non autorisé'));}

include_once NOALYSS_INCLUDE."/class/class_user.php";
require_once NOALYSS_INCLUDE."/lib/user_common.php";
include_once NOALYSS_INCLUDE."/lib/ac_common.php";
require_once NOALYSS_INCLUDE.'/lib/class_database.php';
require_once NOALYSS_INCLUDE."/lib/user_menu.php";
$action = HtmlInput::default_value_request("action", "");

$rep=new Database();
$User=new User($rep);
$User->Check();


if ($User->admin != 1)
{
    html_page_start($User->theme);
    echo "<h2 class=\"warning\">";
    echo _("Vous n'êtes pas administateur");
    echo "</h2>";
    html_page_stop();
    return;
}
// For a backup , we must avoid to send anything before the 
// dump file
if ( $action== 'backup') {
        /* take backup */
        require_once NOALYSS_INCLUDE."/backup.inc.php";
        exit();
}
html_page_start();
load_all_script();
echo '<H2 class="info"> '._('Administration').'</H2>';
echo '<div class="topmenu">';

echo MenuAdmin()."</div>";

?>
<DIV >
<?php
echo js_include("admin.js");
if ( $action=="user_mgt" )
{
    //----------------------------------------------------------------------
    // User management
    //----------------------------------------------------------------------
    require_once NOALYSS_INCLUDE."/user.inc.php";
}
// action=user_mgt
if ( $action=="dossier_mgt")
{
    //-----------------------------------------------------------------------
    // action = dossier_mgt
    //-----------------------------------------------------------------------
    require_once NOALYSS_INCLUDE."/dossier.inc.php";
}
if ( $action== "modele_mgt" )
{
    //-----------------------------------------------------------------------
    //  Template Management
    //-----------------------------------------------------------------------
    require_once NOALYSS_INCLUDE."/modele.inc.php";
} // action is set
if ( $action== 'restore')
{
    // Backup and restaure folders
    require_once NOALYSS_INCLUDE."/restore.inc.php";
}
if ($action== 'audit_log')
{
    /* List the connexion successuf and failed */
    require_once NOALYSS_INCLUDE."/audit_log.php";
}
if ( $action == "logout") {
     require_once 'logout.php';
}
?>
</DIV>
<?php

html_page_stop();
?>
