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
/*! \file
 * \brief Main page for encoding in the ledger
 */

include_once("ac_common.php");
include_once("user_menu.php");
include_once ("constant.php");
include_once ("postgres.php");
include_once ("check_priv.php");
include_once ("class_widget.php");
require_once("jrn.php");

$cn=DbConnect($_SESSION['g_dossier']);
include_once ('class_user.php');
$User=new cl_user($cn);
$User->Check();

html_page_start($User->theme, 
	 "OnLoad=\"SetFocus('e_date',0); AttachEvent(document, 'keydown', HandleSubmit, true);\" ");

// Check if dossier set
if ( ! isset ( $_SESSION['g_dossier'] ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}
/* Get the _REQUEST value for p_jrn (jrn_def.jrn_def_id) and jrn_type (jrn_def.jrn_def_code) */
$p_jrn=(isset($_REQUEST['p_jrn']))?$_REQUEST['p_jrn']:-1;
$jrn_type=(isset($_REQUEST['jrn_type']))?$_REQUEST['jrn_type']:-1;


echo '<div class="u_tmenu">';
echo ShowMenuCompta($_SESSION['g_dossier'],"user_jrn.php?jrn_type=".$jrn_type);
echo '</div>';

if ( $User->admin == 0 ) {
  // check if user can access
  if (CheckAction($_SESSION['g_dossier'],$_SESSION['g_user'],ENCJRN) == 0 ){
    /* Cannot Access */
    NoAccess();
  }
  // if a jrn is asked
  if ( $p_jrn != -1 ) {
	  if (CheckJrn($_SESSION['g_dossier'],$_SESSION['g_user'],$p_jrn) == 0 ){
	    /* Cannot Access */
	    NoAccess();
	    exit -1;
	  }
    } // if isset p_jrn

}

// Show the available jrn
// $result=ShowJrn("user_jrn.php?jrn_type=".$jrn_type);
// echo "<div class=\"u_subtmenu\">";
// echo $result;
// echo "</div>";



// if a journal is selected show the journal's menu
if ( $p_jrn != -1 ) 
{
  // display jrn's menu

  //   echo '</DIV>';
  echo '<div class="u_subt2menu">';      
  // show the available ledger of the type jrn_type
  ShowMenuJrnUser($_SESSION['g_dossier'],$jrn_type,$p_jrn);
  echo '</div>';
  echo '<div class="lmenu">';      
  // show the menu for this journal (Nouveau, voir,...)
  $menu_jrn=ShowMenuJrn($cn,$jrn_type, $p_jrn);
  echo $menu_jrn;

  echo '</div>';

  $g_dossier=$_SESSION['g_dossier'];
  $g_user=$_SESSION['g_user'];
      
  // Execute Action for p_jrn
  if ( $jrn_type=='VEN' )     require('user_action_ven.php');
  if ( $jrn_type=='ACH' )     require('user_action_ach.php');
  if ( $jrn_type=='FIN' )     require('user_action_fin.php');
  if ( $jrn_type=='OD' )     require('user_action_ods.php');
  
}
else 
{
  if ( $jrn_type=='NONE')
    {
      include_once('user_action_gl.php');

    } else {
      // no journal are selected so we select the first one
      $p_jrn=GetFirstJrnIdForJrnType($_SESSION['g_dossier'],$jrn_type); 
      // display jrn's menu
      
  //   echo '</DIV>';
      echo '<div class="u_subt2menu">';      
      ShowMenuJrnUser($_SESSION['g_dossier'],$jrn_type,$p_jrn);
      echo '</div>';
      echo '<div class="lmenu">';      
      $menu_jrn=ShowMenuJrn($cn,$jrn_type, $p_jrn);
      
      echo $menu_jrn;
      echo '</div><br>';
    }

}

html_page_stop();

?>