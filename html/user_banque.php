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
if ( isset( $p_jrn )) {
  session_register("g_jrn");
  $g_jrn=$p_jrn;
} else {
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

ShowMenuJrnUser($g_dossier,$g_UserProperty,BQE,$g_jrn);

html_page_stop();
?>
