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

html_page_start($g_UserProperty['use_theme']);

if ( isset ( $dos ) ) {
  $g_dossier=$dos;
  session_register("g_dossier");
  echo_debug("admin_dossier = $g_dossier ");
  $g_name=GetDossierName($g_dossier);
  session_register("g_name");

} else {
  echo "You must choose a Dossier ";
  exit -2;
}
include_once ("postgres.php");
echo_debug ("user is $g_user");
/* CheckUser */
CheckUser();

// Synchronize rights
SyncRight($g_dossier,$g_user);

// Get The priv on the selected folder
if ( $g_UserProperty['use_admin'] == 0 ) {
  
  $r=GetPriv($g_dossier,$g_user);
  if ($r == 0 ){
    /* Cannot Access */
    NoAccess();
  }
}




include_once ("user_menu.php");
include_once ("top_menu_compta.php");
ShowMenuCompta($g_dossier,$g_UserProperty);
ShowMenuComptaRight($g_dossier,$g_UserProperty); 
html_page_stop();

?>
