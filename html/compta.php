<?
/*
 *   This file is part of WCOMPTA.
 *
 *   WCOMPTA is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   WCOMPTA is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with WCOMPTA; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
// Auteur Dany De Bontridder ddebontridder@yahoo.fr
include ("ac_common.php");
/* $Revision$ */
html_page_start();
if ( isset ( $dos ) ) {
  $g_dossier=$dos;
  session_register("g_dossier");
  echo_debug("admin_dossier = $g_dossier ");
} else {
  echo "You must choose a Dossier ";
  phpinfo();
  exit -2;
}
include ("postgres.php");
/* CheckUser */
CheckUser();

if ( CheckAdmin() == 0 ) {
  
  $r=GetPriv($g_dossier,$user);
  if ($r == 0 ){
    /* Cannot Access */
    NoAccess();
  }
}
SyncRight($g_dossier,$user);

$g_UserType=GetUserType($user);
session_register($g_UserType);

switch ($g_UserType) {
	case 'compta':
		include ("top_menu_compta.php");
		break;
	case 'developper':
		include ("top_menu_compta.php");
		break;
	case 'user':
		include ("top_menu_compta.php");
		break;
	default:
		echo_error("Error type doesn't exist");
		break;
}
ShowMenuCompta($g_dossier);
ShowMenuComptaRight($g_dossier); 


html_page_stop();

?>
