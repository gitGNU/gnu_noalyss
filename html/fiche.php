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
include_once ("top_menu_compta.php");
echo '<SCRIPT LANGUAGE="javascript" SRC="win_search_poste.js"></SCRIPT>';
CheckUser();
include ("check_priv.php");
ShowMenuCompta($g_dossier,$g_UserProperty);


if ( isset($_POST["PHPSESSID"] )) {
  $sessid=$_POST["PHPSESSID"];
} else {
  $sessid=$_GET["PHPSESSID"];
}
$search='<INPUT TYPE="BUTTON" VALUE="Cherche" OnClick="SearchPoste(\''.$sessid."')\">";


include_once("fiche_inc.php");
$l_Db=sprintf("dossier%d",$g_dossier);
$cn=DbConnect($l_Db);

if ( isset($_POST['add_modele']) ) {
  AddModele($cn,$HTTP_POST_VARS);
}




ShowMenuComptaRight($g_dossier,$g_UserProperty);
if ( $g_UserProperty['use_admin'] == 0 ) {
  $r=CheckAction($g_dossier,$g_user,FICHE);
  if ($r == 0 ){
    /* Cannot Access */
    NoAccess();
  }
}

ShowMenuComptaLeft($g_dossier,MENU_FICHE);
if ( isset ( $_GET["action"]) ) {
  $action=$_GET["action"];
  if ( isset ($_GET["fiche"]) && $action=="vue" ) {
    echo '<DIV class="redcontent">';
    ViewFiche($cn,$_GET["fiche"]);
    echo '</DIV>';
  }
  if ($action== "detail" ) {
    echo '<DIV class="redcontent">';
    ViewFicheDetail($cn,$_GET["fiche_id"]);
    echo '</DIV>';
  }
  if ($action == "add_modele" ) {
    echo '<DIV class="redcontent">';
    DefModele($search);
    echo '</DIV>';
  }
  if ($action == "modifier" ) {
    echo '<DIV class="redcontent">';
    UpdateModele($cn,$_GET["fiche"],$search);
  }
  if ($action== "delete" ) {
    echo '<DIV class="redcontent">';
    Remove($cn,$_GET["fiche_id"]);

    ViewFiche($cn,$_GET["f_fd_id"]);
    echo "</DIV>";
  }  
}
if (isset( $_POST["update_modele"] )) {
  echo '<DIV class="redcontent">';
  SaveModele($cn,$HTTP_POST_VARS);
  echo '</DIV>';
}

if ( isset ($_POST["record_model"]) ){
  echo '<DIV class="redcontent">';
  if ( strlen(trim($_POST["nom_mod"])) == 0 ) {
    EncodeModele($search);
  }else {
    DefModele($search,$HTTP_POST_VARS);
  }
  echo '</DIV>';
}
if ( isset ($_POST["add_ligne"] )) {
  echo '<DIV class="redcontent">';
  DefModele($search,$HTTP_POST_VARS,$_POST["inc"]+1);
  echo '</DIV>';
}

if ( isset ($_POST["fiche"]) && isset ($_POST["add"]) ) {
  echo '<DIV class="redcontent">';
  EncodeFiche($cn,$_POST["fiche"]);
  echo '</DIV>';
}

if ( isset ($_POST["add_fiche"]) ) {
  echo '<DIV class="redcontent">';
  AddFiche($cn,$_POST["fiche"],$HTTP_POST_VARS);
  ViewFiche($cn,$_POST["fiche"]);
  echo '</DIV>';
}
if ( isset ($_POST["update_fiche"])) {
  echo '<DIV class="redcontent">';
  $a=UpdateFiche($cn,$HTTP_POST_VARS);
  ViewFiche($cn,$_POST["f_fd_id"]);

  echo '</DIV>';
}
html_page_stop();
?>
