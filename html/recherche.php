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
include_once("ac_common.php");
include("user_menu.php");
include_once ("constant.php");
include_once("jrn.php");
include_once("user_common.php");

html_page_start($_SESSION['use_theme']);
if ( ! isset ( $_SESSION['g_dossier'] ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}
include_once ("postgres.php");
include_once ("check_priv.php");
/* Admin. Dossier */

$cn=DbConnect($_SESSION['g_dossier']);
include ('class_user.php');
$User=new cl_user($cn);
$User->Check();

ShowMenuCompta($_SESSION['g_dossier']);

if ( $User->admin == 0 ) {
  // check if user can access
  if (CheckAction($_SESSION['g_dossier'],$User->id,ENCJRN) == 0 ){
    /* Cannot Access */
    NoAccess();
  }
}

   // PhpSessid
   $sessid=$_REQUEST['PHPSESSID'];

// display a search box
   $search_box=u_ShowMenuRecherche($cn,0,$sessid,$HTTP_POST_VARS);
   echo '<DIV class="recherche">';
  echo $search_box; 
   // if nofirst is set then show result
   if ( isset ($_GET['nofirst'] ) ) {
     $a=ListJrn($cn,0,"",$HTTP_POST_VARS);
     echo $a;
   }
   echo '</DIV>'; 
 
