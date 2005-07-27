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
include_once("user_menu.php");
include_once ("constant.php");
include_once ("postgres.php");
include_once ("check_priv.php");
include_once ("class_widget.php");

$cn=DbConnect($_SESSION['g_dossier']);
include ('class_user.php');
$User=new cl_user($cn);
$User->Check();

html_page_start($User->theme, "OnLoad=\"SetFocus('e_date',0)\"");
if ( ! isset ( $_SESSION['g_dossier'] ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}
/* Admin. Dossier */

if ( isset( $_GET['p_jrn'] )) 
{
  $p_jrn=$_GET['p_jrn'];
} else 
{
  $p_jrn=-1;
}

if ( isset ($_GET['JRN_TYPE'] ) ) {
  $p_jrn=-1;
}


ShowMenuCompta($_SESSION['g_dossier']);

if ( $User->admin == 0 ) {
  // check if user can access
  if (CheckAction($_SESSION['g_dossier'],$_SESSION['g_user'],ENCJRN) == 0 ){
    /* Cannot Access */
    NoAccess();
  }
  if ( isset ($p_jrn)) {
	  if (CheckJrn($_SESSION['g_dossier'],$_SESSION['g_user'],$p_jrn) == 0 ){
	    /* Cannot Access */
	    NoAccess();
	    exit -1;
	  }
    } // if isset p_jrn

}
// if show
if ( isset ($_GET['show'])) {
  $result=ShowJrn();
   echo "<DIV class=\"u_subtmenu\">";
   echo $result;
   echo "</DIV>";
   exit();
}
// if type of journal is asked
if ( isset ($_GET['JRN_TYPE'] ) ) {
  $jrn_type=$_GET['JRN_TYPE'];

  $result=ShowJrn("user_jrn.php?JRN_TYPE=".$jrn_type);
   echo "<DIV class=\"u_subtmenu\">";
   echo $result;
  ShowMenuJrnUser($_SESSION['g_dossier'],$_GET['JRN_TYPE'],$p_jrn);
   echo "</DIV>";
 if ( $jrn_type=='NONE' )     include('user_action_gl.php');
} else {

  echo_debug("Selected is ".$p_jrn);
  // Get the jrn_type_id
  include_once('jrn.php');
  $JrnProp=GetJrnProp($_SESSION['g_dossier'],$p_jrn);
  $jrn_type=$JrnProp['jrn_def_type'];
  echo_debug("Type is $jrn_type");
  echo_debug("Jrn_def_type = $jrn_type");

 echo '<div class="u_subtmenu">';
 echo ShowJrn("user_jrn.php?JRN_TYPE=".$jrn_type);
 ShowMenuJrnUser($_SESSION['g_dossier'],$jrn_type,$p_jrn);
 echo '</div>';
}


  // if a journal is selected show the journal's menu
if ( $p_jrn != -1 ) {
 $result=ShowJrn( "user_jrn.php?JRN_TYPE=".$jrn_type);
  // Get the jrn_type_id
  include_once('jrn.php');
  $JrnProp=GetJrnProp($_SESSION['g_dossier'],$p_jrn);
  $jrn_type=$JrnProp['jrn_def_type'];
  // display jrn's menu

   $menu_jrn=ShowMenuJrn($cn,$jrn_type, $p_jrn);
   //      echo '<div class="searchmenu">';
   //   echo $result;
   echo $menu_jrn;
   //   echo '</DIV>';
   $g_dossier=$_SESSION['g_dossier'];
   $g_user=$_SESSION['g_user'];
  // Execute Action for p_jrn
 
  if ( $jrn_type=='VEN' )     include('user_action_ven.php');
  if ( $jrn_type=='ACH' )     include('user_action_ach.php');
  if ( $jrn_type=='FIN' )     include('user_action_fin.php');
  if ( $jrn_type=='OD ' )     include('user_action_ods.php');
  } // if isset p_jrn

html_page_stop();
?>
