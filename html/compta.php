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
include_once ("ac_common.php");
/* $Revision$ */
session_start();
html_page_start($g_UserProperty['use_theme']);

if ( isset ( $dos ) ) {
  $g_dossier=$dos;
  session_register("g_dossier");
  echo_debug("admin_dossier = $g_dossier ");
} else {
  echo "You must choose a Dossier ";
  //  phpinfo();
  exit -2;
}
include_once ("postgres.php");
echo_debug ("user is $g_user");
/* CheckUser */
CheckUser();


echo_debug("theme ".$g_UserProperty['use_theme']);

if ( $g_UserProperty['use_admin'] == 0 ) {
  
  $r=GetPriv($g_dossier,$g_user);
  if ($r == 0 ){
    /* Cannot Access */
    NoAccess();
  }
}
SyncRight($g_dossier,$g_user);


include ("top_menu_compta.php");

// Show Top Menu 
ShowMenuCompta($g_dossier,$g_UserProperty);

// Show Menu on the right side
ShowMenuComptaRight($g_dossier,$g_UserProperty); 


html_page_stop();

?>
