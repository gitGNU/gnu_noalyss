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
/* $Revision: 1615 $ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/*!\file
 * \brief first page
 */
require_once("class_idate.php");
require_once("class_itext.php");
require_once ('constant.php');
require_once ('ac_common.php');
require_once ('class_user.php');
require_once('class_acc_report.php');
require_once('class_periode.php');
require_once ('user_menu.php');
require_once ('class_dossier.php');
require_once('class_todo_list.php');
require_once("class_itextarea.php");
require_once('class_calendar.php');
require_once('class_acc_ledger.php');

$cn=new Database(dossier::id());
$user=new User($cn);
$user->Check();
if ( $user->check_dossier(dossier::id()) == 'P') {
	redirect("extension.php?".dossier::get(),0);
	exit();
}

html_page_start($_SESSION['g_theme']);
/*  Check Browser version if < IE6 then unsupported */
$browser=$_SERVER['HTTP_USER_AGENT'];
if ( strpos($browser,'MSIE 6')!=false ||
     strpos($browser,'MSIE 5')!=false ) {


echo <<<EOF
    <!--[if lt IE 7]>
  <div style='border: 1px solid #F7941D; background: #FEEFDA; text-align: center; clear: both; height: 75px; position: relative;'>
    <div style='position: absolute; right: 3px; top: 3px; font-family: courier new; font-weight: bold;'><a href='#' onclick='javascript:this.parentNode.parentNode.style.display="none"; return false;'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-cornerx.jpg' style='border: none;' alt='Close this notice'/></a></div>
    <div style='width: 640px; margin: 0 auto; text-align: left; padding: 0; overflow: hidden; color: black;'>
      <div style='width: 75px; float: left;'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-warning.jpg' alt='Warning!'/></div>
      <div style='width: 275px; float: left; font-family: Arial, sans-serif;'>
        <div style='font-size: 14px; font-weight: bold; margin-top: 12px;'>Vous utilisez un navigateur dépassé depuis près de 8 ans!</div>
        <div style='font-size: 12px; margin-top: 6px; line-height: 12px;'>Pour une meilleure expérience web, prenez le temps de mettre votre navigateur à jour.</div>
      </div>
      <div style='width: 75px; float: left;'><a href='http://fr.www.mozilla.com/fr/' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-firefox.jpg' style='border: none;' alt='Get Firefox 3.5'/></a></div>
      <div style='width: 73px; float: left;'><a href='http://www.apple.com/fr/safari/download/' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-safari.jpg' style='border: none;' alt='Get Safari 4'/></a></div>
      <div style='float: left;'><a href='http://www.google.com/chrome?hl=fr' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-chrome.jpg' style='border: none;' alt='Get Google Chrome'/></a></div>
    </div>
  </div>
  <![endif]-->
EOF;
exit();
}
if ( $cn->exist_table('version') == false ) {
 echo '<h2 class="error" style="font-size:12px">'._("Base de donnée invalide").'</h2>';
 $base=dirname($_SERVER['REQUEST_URI']);
 echo HtmlInput::button_anchor('Retour',$base.'/user_login.php');
 exit();
}
if ( DBVERSION < dossier::get_version($cn)) {
 echo '<h2 class="error" style="font-size:12px">'._("Attention: la version de base de donnée est supérieure à la version du programme, vous devriez mettre à jour").'</h2>';
 }
if ( DBVERSION > dossier::get_version($cn)) {
  echo '<h2 class="error" style="font-size:12px">'._("Votre base de données n'est pas à jour").'   ';
  $a=_("cliquez ici pour appliquer le patch");
  $base=dirname($_SERVER['REQUEST_URI']).'/admin/setup.php';
  echo '<a hreF="'.$base.'">'.$a.'</a></h2>';
}

echo '<div class="u_tmenu">';
echo menu_tool('access.php');
echo '</div>';
echo '<div class="content">';
echo JS_TODO;
echo js_include('prototype.js');
echo js_include('infobulle.js');

/* others report */
$cal=new Calendar();
$cal->get_preference();
$Ledger=new Acc_Ledger($cn,0);
$Operation=new Action($cn);
$last_ledger=$Ledger->get_last(10);
$last_operation=$Operation->get_last(10);
ob_start();
require_once('template/dashboard.php');
$ret=ob_get_contents();
ob_end_clean();
echo $ret;

echo '</div>';


