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
/*! \file
 * \brief handle your own report: create or view report
 */
include_once ("ac_common.php");
include_once ("user_menu.php");

html_page_start($_SESSION['g_theme']);

require_once('class_dossier.php');
$gDossier=dossier::id();

include_once ("postgres.php");
/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new User($rep);
$User->Check();

include_once("form_inc.php");

include_once ("user_menu.php");
echo '<div class="u_tmenu">';
echo ShowMenuCompta("user_advanced.php?".dossier::get());
echo '</div>';

include ("check_priv.php");

$cn=DbConnect($gDossier);
echo ShowMenuAdvanced(6);
// Get The priv on the selected folder
$User->can_request($cn,FORM);




if ( isset ($_POST["record"] )) {
  //  echo '<DIV class="u_redcontent">';
  AddForm($cn,$_POST);
  //echo "</DIV>";
}
if ( isset ($_POST["del_form"]) ) {
  //  echo '<DIV class="u_redcontent">';
  DeleteForm($cn,$_POST['fr_id']);
  // echo "</DIV>";
}

ShowMenuComptaForm();

if ( isset( $_REQUEST['PHPSESSID'])) {
	$sessid = $_REQUEST['PHPSESSID'];
}


if ( isset ($_GET["action"]) ) {
  $action=$_GET["action"];
  if ($action == "add" )
    {
      echo '<DIV class="u_redcontent">';
      EncodeForm(10,$sessid);
      echo "</DIV>";
    }
  if ($action=="view" ) {
      echo '<DIV class="u_redcontent">';
    if ( ! $_GET["fr_id"] ) {
      echo_error("fr_id n'est pas donné");
      return;
    }
    ViewForm($cn,$sessid,$_GET["fr_id"]);
    echo "</DIV>";
  }
} // if $_GET
if ( isset ($_POST["add_line"]) ) {
  echo '<DIV class="u_redcontent">';
  $line=$_POST["line"];
  EncodeForm($line+1,$sessid,$_POST);
  echo "</DIV>";
}
if ( isset ($_POST["update"]) ) {
  echo '<DIV class="u_redcontent">';
  UpdateForm($cn,$_POST);
    ViewForm($cn,$sessid,$_POST["fr_id"]);

  echo "</DIV>";
}


//echo "</DIV>";
html_page_stop();
?>
