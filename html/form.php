<?
/*
 *   This file is part of WCOMPTA.
 *
 *   WCOMPTA is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   WCOMPTA is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with WCOMPTA; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
/* $Revision$ */
// Auteur Dany De Bontridder ddebontridder@yahoo.fr
/* $Revision$ */

include_once ("ac_common.php");
html_page_start($g_UserProperty['use_theme']);

if ( ! isset ( $g_dossier ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}
include_once ("postgres.php");
/* Admin. Dossier */
CheckUser();

include_once("form_inc.php");

include_once ("top_menu_compta.php");
ShowMenuCompta($g_dossier,$g_UserProperty);
include ("check_priv.php");

ShowMenuComptaRight($g_dossier,$g_UserProperty);

if ( $g_UserProperty['use_admin'] == 0 ) {
  $r=CheckAction($g_dossier,$g_user,FORM);
  if ($r == 0 ){
    /* Cannot Access */
    NoAccess();
  }
}

ShowMenuComptaForm($g_dossier);

$l_Db=sprintf("dossier%d",$g_dossier);
$cn=DbConnect($l_Db);
if ( isset($_GET["PHPSESSID"] )) {
  $sessid=$_GET["PHPSESSID"];
} 
if ( isset($_POST["PHPSESSID"] )) {
  $sessid=$_POST["PHPSESSID"];
} 

if ( isset ($_GET["action"]) ) {
  $action=$_GET["action"];
  if ($action == "add" )
    {
      echo '<DIV class="ccontent">';
      EncodeForm(1,$sessid);
      echo "</DIV>";
    }
  if ($action=="view" ) {
      echo '<DIV class="ccontent">';
    if ( ! $_GET["fr_id"] ) {
      echo_error("fr_id n'est pas donné");
      return;
    }
    ViewForm($cn,$sessid,$_GET["fr_id"]);
    echo "</DIV>";
  }
} // if $_GET
if ( isset ($_POST["add_line"]) ) {
  echo '<DIV class="ccontent">';
  $line=$_POST["line"];
  EncodeForm($line+1,$sessid,$HTTP_POST_VARS);
  echo "</DIV>";
}
if ( isset ($_POST["update"]) ) {
  echo '<DIV class="ccontent">';
  UpdateForm($cn,$HTTP_POST_VARS);
  echo "</DIV>";
}
if ( isset ($_POST["record"] )) {
  echo '<DIV class="ccontent">';
  AddForm($cn,$HTTP_POST_VARS);
  echo "</DIV>";
}

//echo '<DIV CLASS="ccontent">';

//echo "</DIV>";
html_page_stop();
?>
