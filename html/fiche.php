<?
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

include_once ("ac_common.php");
//phpinfo();
html_page_start($_SESSION['use_theme']);

if ( ! isset ( $_SESSION['g_dossier'] ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}
include_once ("postgres.php");
include_once ("user_menu.php");

echo JS_SEARCH_POSTE;
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();
include ("check_priv.php");
ShowMenuCompta($_SESSION['g_dossier']);


if ( isset($_POST["PHPSESSID"] )) {
  $sessid=$_POST["PHPSESSID"];
} else {
  $sessid=$_GET["PHPSESSID"];
}
$search='<INPUT TYPE="BUTTON" VALUE="Cherche" OnClick="SearchPoste(\''.$sessid."')\">";


include_once("fiche_inc.php");

$cn=DbConnect($_SESSION['g_dossier']);

// Security check
$read=$User->CheckAction($cn,FICHE_READ);
$write=$User->CheckAction($cn,FICHE_WRITE);
if ($read+$write == 0 ){
  /* Cannot Access */
  NoAccess();
}

// Creation of a new model of card
// in the database
if ( isset($_POST['add_modele'])  and $write != 0) {
  // insert the model of card in database
  AddModele($cn,$HTTP_POST_VARS);
}

ShowMenuFiche($_SESSION['g_dossier']);

if ( isset ( $_GET["action"]) ) {
  $action=$_GET["action"];
  // View the details of the selected cat. of cards
  if ( isset ($_GET["fiche"]) && $action=="vue" ) {
    echo '<DIV class="redcontent">';
    ViewFiche($cn,$_GET["fiche"]);
    echo '</DIV>';
  }// Display the detail of a card
  if ($action== "detail" ) {
    echo '<DIV class="redcontent">';
    if ( $write == 0) echo '<H2 class="info"> Vos changements ne seront pas sauvés</h2>';
    ViewFicheDetail($cn,$_GET["fiche_id"]);
    echo '</DIV>';
  }
  // Display the form where you can enter
  // the property of the card model
  if ($action == "add_modele" and $write !=0) {
    echo '<DIV class="redcontent">';
    DefModele($cn,$search);
    echo '</DIV>';
  }
  // Modify a card Model
  if ($action == "modifier" ) {
    echo '<DIV class="redcontent">';
    if ( $write ==0)  
      echo "<h2 class=\"error\"> Pas d'accès </h2>";
    else
      UpdateModele($cn,$_GET["fiche"],$search);
    echo '</DIV>';
  }
  // delete a card
  if ($action== "delete"  ) {
    echo '<DIV class="redcontent">';
    if ( $write ==0)  
      echo "<h2 class=\"error\"> Pas d'accès </h2>";
    else
      {
	Remove($cn,$_GET["fiche_id"]);
	
	ViewFiche($cn,$_GET["f_fd_id"]);
      }
    echo "</DIV>";
  }  
}
// Add a line in the card model
if ( isset ($_GET["add_ligne"])  ) {
  echo '<DIV class="redcontent">';
    if ( $write ==0)  
      echo "<h2 class=\"error\"> Pas d'accès </h2>";
    else
      {
	SaveModeleName($cn,$_GET["fd_id"],$_GET["label"]);
	InsertModeleLine($cn,$_GET["fd_id"],$_GET["ad_id"]);
	UpdateModele($cn,$_GET["fd_id"],$search);
      }
  echo '</DIV>';
}
// Change the name of the card  model
if ( isset ($_GET["change_name"] )  ) {
  echo '<DIV class="redcontent">';
    if ( $write ==0)  
      echo "<h2 class=\"error\"> Pas d'accès </h2>";
    else
      {
	SaveModeleName($cn,$_GET["fd_id"],$_GET["label"]);
	UpdateModele($cn,$_GET["fd_id"],$search);
      }
  echo '</DIV>';
}

// Display a blank  card from the selected category
if ( isset ($_POST["fiche"]) && isset ($_POST["add"] ) ) {
  echo '<DIV class="redcontent">';
    if ( $write ==0)  
      echo "<h2 class=\"error\"> Pas d'accès </h2>";
    else
      EncodeFiche($cn,$_POST["fiche"]);
  echo '</DIV>';
}
// Add the data (attribute) of the card
if ( isset ($_POST["add_fiche"]) ) {
  echo '<DIV class="redcontent">';
  
  if ( $write ==0)  
    echo "<h2 class=\"error\"> Pas d'accès </h2>";
  else
    {
      AddFiche($cn,$_POST["fiche"],$HTTP_POST_VARS);
      ViewFiche($cn,$_POST["fiche"]);
    }
  echo '</DIV>';
}
// Update a card
if ( isset ($_POST["update_fiche"])  ) {
  echo '<DIV class="redcontent">';
      if ( $write ==0)  
      echo "<h2 class=\"error\"> Pas d'accès </h2>";
    else
      {
	$a=UpdateFiche($cn,$HTTP_POST_VARS);
      }
      $fd_id=GetFicheDef($cn,$_POST["f_id"]);
      ViewFiche($cn,$fd_id);


  echo '</DIV>';
}
html_page_stop();
?>
