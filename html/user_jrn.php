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
include_once("ac_common.php");
include("top_menu_compta.php");
include_once ("constant.php");

html_page_start($g_UserProperty['use_theme']);
if ( ! isset ( $g_dossier ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}
include_once ("postgres.php");
include_once ("check_priv.php");
/* Admin. Dossier */
CheckUser();
if ( isset( $_GET['p_jrn'] )) {
  session_register("g_jrn");
  $g_jrn=$_GET['p_jrn'];
} else {
  if ( ! isset ($g_jrn) ) $g_jrn=-1;
}
if ( isset ($_GET['JRN_TYPE'] ) ) {
  $g_jrn=-1;
}

$l_Db=sprintf("dossier%d",$g_dossier);
$cn=DbConnect($l_Db);

ShowMenuCompta($g_dossier,$g_UserProperty);
ShowMenuComptaRight($g_dossier,$g_UserProperty);
if ( $g_UserProperty['use_admin'] == 0 ) {
  $r=CheckAction($g_dossier,$g_user,ENCJRN);
  if ($r == 0 ){
    /* Cannot Access */
    NoAccess();
  }
  if ( isset ($g_jrn)) {
  	$right=CheckJrn($g_dossier,$g_user,$g_jrn);
	  if ($right == 0 ){
	    /* Cannot Access */
	    NoAccess();
	    exit -1;
	  }
    } // if isset g_jrn

}
if ( isset ($_GET['JRN_TYPE'] ) ) {
  ShowMenuJrnUser($g_dossier,$g_UserProperty,$_GET['JRN_TYPE'],$g_jrn);
} else {
  echo_debug("Selected is $g_jrn");
  // Get the jrn_type_id
  include_once('jrn.php');
  $JrnProp=GetJrnProp($g_dossier,$g_jrn);
  $jrn_type=$JrnProp['jrn_def_type'];
  echo_debug("Type is $jrn_type");
  echo_debug("Jrn_def_type = $jrn_type");
 ShowMenuJrnUser($g_dossier,$g_UserProperty,$jrn_type,$g_jrn);
}

  // if a journal is selected show the journal's menu
if ( $g_jrn != -1 ) {
 
  // Get the jrn_type_id
  include_once('jrn.php');
  $JrnProp=GetJrnProp($g_dossier,$g_jrn);
  $jrn_type=$JrnProp['jrn_def_type'];

  // display jrn's menu
  include_once('user_menu.php');
   $menu_jrn=u_ShowMenuJrn($cn,$jrn_type);
   echo '<div class="searchmenu">';
   echo $menu_jrn;
   echo '</DIV>';

  // Execute Action for g_jrn
  if ( $jrn_type=='VEN' )     include('user_action_ven.php');
  if ( $jrn_type=='ACH' )     include('user_action_ach.php');
  if ( $jrn_type=='FIN' )     include('user_action_fin.php');
  if ( $jrn_type=='OD ' )     include('user_action_ods.php');
  } // if isset g_jrn

html_page_stop();
?>
