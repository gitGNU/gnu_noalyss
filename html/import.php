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
if ( ! isset ( $_SESSION['g_dossier'] ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}
$cn=DbConnect($_SESSION['g_dossier']);
include ('class_user.php');
$User=new cl_user($cn);
$User->Check();

html_page_start($User->theme);


/* Admin. Dossier */

include_once("import_inc.php");

include_once ("user_menu.php");
echo '<div class="u_tmenu">';
echo ShowMenuCompta($_SESSION['g_dossier'],"user_advanced.php");
echo '</div>';
$cn=DbConnect($_SESSION['g_dossier']);
if ( $User->CheckAction($cn,IMP_BQE)==0){
  /* Cannot Access */
  NoAccess();
 }
echo ShowMenuAdvanced("import.php");

echo '<div class="lmenu">';
ShowMenuImport();
echo '</div>';
if ( isset( $_REQUEST['PHPSESSID'])) {
	$sessid = $_REQUEST['PHPSESSID'];
}
echo JS_SEARCH_POSTE;

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
    if(isset($_POST['count'])) {
      UpdateCSV($cn);
    }
    echo '<DIV class="u_redcontent">';
    VerifImport($cn);
    echo "</DIV>";
  }
  if ($action == "transfer" ) {
    echo '<DIV class="u_redcontent">';
    TransferCSV($cn, $User->GetPeriode());
    echo "</DIV>";
  }
} 

html_page_stop();
?>
