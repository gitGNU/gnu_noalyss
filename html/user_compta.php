<?
/*
 *   This file is part of PHPCOMPTA
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
 *   along with PHPCOMPTA; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
// Auteur Dany De Bontridder ddebontridder@yahoo.fr
include_once ("ac_common.php");
/* $Revision$ */
include_once ("class_user.php");
include_once ("postgres.php");

$rep=DbConnect();

$User=new cl_user($rep);
$User->Check();

html_page_start($User->theme);

if ( isset ( $_GET['dos'] ) ) {
  $g_dossier=$_GET['dos'];
  $_SESSION[ "g_dossier"]=$g_dossier;
  echo_debug('user_compta.php',__LINE__,"admin_dossier = $g_dossier ");
  $g_name=GetDossierName($g_dossier);
  $_SESSION["g_name"]=$g_name;

} else {
  echo "You must choose a Dossier ";
  exit -2;
}
echo_debug('user_compta.php',__LINE__,"user is ".$_SESSION['g_user']);

// Synchronize rights
SyncRight($_SESSION['g_dossier'],$_SESSION['g_user']);

// Get The priv on the selected folder
if ( $User->admin == 0 ) {
  
  $r=GetPriv($_SESSION['g_dossier'],$_SESSION['g_user']);
  if ($r == 0 ){
    /* Cannot Access */
    NoAccess();
  }
}




include_once ("user_menu.php");

ShowMenuCompta($g_dossier);

html_page_stop();

?>
