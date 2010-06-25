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

@html_page_start($_SESSION['g_theme']);
$cn=new Database(dossier::id());
$user=new User($cn);
$user->check();
$only_plugin=$user->check_dossier(dossier::id());


/* javascript file */
echo js_include('prototype.js');
echo js_include('scriptaculous.js');
echo js_include('effects.js');
echo js_include('controls.js');
echo js_include('dragdrop.js');
echo js_include('acc_ledger.js');
echo js_include('accounting_item.js');
echo js_include('card.js');
echo js_include('compute.js');
echo js_include('compute_direct.js');
echo JS_INFOBULLE;

/* show button to return to access */
echo "<h2 class=\"info\">".dossier::name()."</h2>";
if ( $only_plugin != 'P' ) {
	// user with only plugin cannot go back to the dashboard
/* return button */
$msg=_('Retour au tableau de bord');
$hidden=dossier::hidden();
echo '
<div style="position:absolute;top:3px;right:30px" class="noprint">
<form method="get" action="access.php" style="display:inline">'.
  $hidden;

echo HtmlInput::button_anchor(_('Préférence'),'user_pref.php'); 
echo '
  <input type="SUBMIT" class="button" value="'.$msg.'">
</form>
</div>
';


} else {
  $msg=_('Retour accueil');
?>
    <div style="position:absolute;top:3px;right:30px" class="noprint">
    <form method="get" action="access.php" style="display:inline">
<?php
       if ($only_plugin !='P')
	 echo HtmlInput::button_anchor('Retour Accueil','user_login.php');
  echo HtmlInput::button_anchor(_('Préférence'),'user_pref.php');
  echo HtmlInput::button_anchor(_('Déconnexion'),'logout.php?');

  
  ?>
    </form>
    </div>
<?php
}
/* show all the extension we can access */
$a=new ISelect('plugin_code');
$a->value=Extension::make_array($cn);
$a->selected=(isset($_REQUEST['plugin_code']))?strtoupper($_REQUEST['plugin_code']):'';

/* no plugin available */
if ( count($a->value) == 0 ) {alert(j(_("Aucune extension  disponible")));exit;}

/* only one plugin available then we don't propose a choice*/
if ( count($a->value)==1 ) {
  $_REQUEST['plugin_code']=$a->value[0]['value'];
} else {
  echo '<form method="get" action="extension.php">';
  echo $hidden;
  echo _('Extension').$a->input().HtmlInput::submit('go',_("Choix de l'extension"));
  echo '</form>';
  echo '<hr>';
}

require_once('ext_inc.php');
