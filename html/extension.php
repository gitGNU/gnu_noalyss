<?php
/*
 *   This file is part of PhpCompta.
 *
 *   PhpCompta is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   PhpCompta is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with PhpCompta; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
/* $Revision$ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/*!\file
 * \brief this file includes the called plugin. It  check first
 * the security. Load several javascript files
 */
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
echo '<div class="topmenu">';
@html_page_start($_SESSION['g_theme']);
$cn=new Database(dossier::id());
$user=new User($cn);
$user->check();
$only_plugin=$user->check_dossier(dossier::id());


/* javascript file */
echo load_all_script();

/* show button to return to access */

/* show all the extension we can access */
$a=new ISelect('plugin_code');
$a->value=Extension::make_array($cn);
$a->selected=(isset($_REQUEST['plugin_code']))?strtoupper($_REQUEST['plugin_code']):'';

/* no plugin available */
if ( count($a->value) == 0 )
{
    alert(j(_("Aucune extension  disponible")));
    exit;
}

/* only one plugin available then we don't propose a choice*/
if ( count($a->value)==1 )
{
    $_REQUEST['plugin_code']=$a->value[0]['value'];
}
else
{
    echo '<form method="get" action="extension.php">';
    echo Dossier::hidden();
    echo _('Extension').$a->input().HtmlInput::submit('go',_("Choix de l'extension"));
    echo '</form>';
    echo '<hr>';
}

require_once('ext_inc.php');
