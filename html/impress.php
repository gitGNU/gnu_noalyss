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
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
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
include ("check_priv.php");
include_once ("top_menu_compta.php");
ShowMenuCompta($g_dossier,$g_UserProperty);
if ( isset ($g_jrn) ) {
  $p_id=$g_jrn;
} else {
  if (isset ($_GET["p_id"]) )   $p_id=$_GET["p_id"];
}

include_once("impress_inc.php");
ShowMenuComptaRight($g_dossier,$g_UserProperty);
if ( $g_UserProperty['use_admin'] == 0 ) {
  $r=CheckAction($g_dossier,$g_user,IMP);
  if ($r == 0 ){
    /* Cannot Access */
    NoAccess();
  }
  if ( isset ($_GET["type"])) {
    if ( $type="jrn") {
      $right=CheckJrn($g_dossier,$g_user,$p_id);
      if ($right == 0 ){
	/* Cannot Access */
	NoAccess();
	exit -1;
      }
    }
  }
}

$l_Db=sprintf("dossier%d",$g_dossier);
$cn=DbConnect($l_Db);
ShowMenuJrnUserImp($cn,$g_user,$g_dossier);


// Ask the period
if ( isset ( $_GET["action"]) ) {
  echo '<DIV class="redcontent">';
  ViewImp($HTTP_GET_VARS,$cn);
  echo '</DIV>';
}//if ( isset ( $_GET["action"] )) 

// The period is given, now show the result
if ( isset ( $_POST["print"]) ) {
  echo '<DIV class="redcontent">';
  $result=Imp($HTTP_POST_VARS,$cn);
  if ($result== NO_PERIOD_SELECTED) {
    echo "<SCRIPT>alert(\"Aucune période choisie\"); </SCRIPT>";
    return;
  }
  if ($result== NO_POST_SELECTED) {
    echo "<SCRIPT>alert(\"Aucune post choisie\"); </SCRIPT>";
    return;
  }

  if ( $_POST["action"]=="viewhtml") {
    echo "<TABLE>";
    echo $result;
    echo "<TABLE>";
  }

  echo '</DIV>';
}//if ( isset ( $_GET["action"] )) 

html_page_stop();
?>
