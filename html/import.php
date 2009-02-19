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
  // Author Olivier Dzwoniarkiewicz
  /*! \file
   * \brief for importing Bank operations
   */

include_once("ac_common.php");
include_once("user_menu.php");
include_once ("constant.php");
include_once ("postgres.php");
include_once ("check_priv.php");
include_once ("class_widget.php");

require_once('class_dossier.php');
$gDossier=dossier::id();

$cn=DbConnect($gDossier);

include ('class_user.php');
$User=new User($cn);
$User->Check();
$User->check_dossier(dossier::id());

html_page_start($User->theme);
echo JS_PROTOTYPE;

/* Admin. Dossier */

include_once("import_inc.php");

include_once ("user_menu.php");
echo '<div class="u_tmenu">';
echo ShowMenuCompta("user_advanced.php?".dossier::get());
echo '</div>';


echo ShowMenuAdvanced(7);
$User->can_request(GEBQ,1);

echo JS_AJAX_FICHE;
echo '<div class="lmenu">';
ShowMenuImport();
echo '</div>';
if ( isset( $_REQUEST['PHPSESSID'])) {
  $sessid = $_REQUEST['PHPSESSID'];
}


// if action is set proceed to it
if ( isset ($_GET["action"]) ) {
  $action=$_GET["action"];
  // menu = import cvs
  if ($action == "import" ) {
    if(isset($_FILES['fupload'])) {
      // load the table with the cvs' content
      echo '<DIV class="u_redcontent">';
      ImportCSV($cn,$_FILES['fupload']['tmp_name'],$_POST['import_bq'],$_POST['format_csv'],$_POST['import_jrn']);
      echo "</DIV>";
    } else {
      echo '<DIV class="u_redcontent">';
      ShowFormTransfert($cn);
      echo "</DIV>";
    }
  }
  if ($action == "verif" ) {
    echo '<DIV class="u_redcontent">';
    VerifImport($cn);
    echo "</DIV>";
  }

  if ($action == "transfer" ) {

    echo '<DIV class="u_redcontent">';
    echo '<span class="notice"> Seulement les opérations de la période par défaut (voir préférence) seront transfèrées</span><hr>';
    //   TransferCSV($cn, 
    ConfirmTransfert($cn,$User->get_periode());
    echo "</DIV>";
  }
} 
/*-----------------------------------------------
 * transfert or remove the wrong record 
 *
 *-----------------------------------------------*/
if ( isset ($_POST['action'])) {
  $action=$_POST['action'];
  if ($action == "transfer" ) {
    echo '<DIV class="u_redcontent">';
    TransferCSV($cn, $User->get_periode());
    echo "</DIV>";
  }

  if ($action == "remove" ) {
    echo '<DIV class="u_redcontent">';
    RemoveCSV($cn);
    ConfirmTransfert($cn,$User->get_periode());
    echo "</DIV>";
  }

}
html_page_stop();
?>
