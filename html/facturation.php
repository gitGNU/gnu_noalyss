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
include ("ac_common.php");
include ("check_priv.php");
/* $Revision$ */
html_page_start($g_UserProperty['use_theme']);
if ( ! isset ( $g_dossier ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}
include_once ("postgres.php");
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();
include ("top_menu_compta.php");
ShowMenuCompta($g_dossier,$g_UserProperty);

if ( $g_UserProperty['use_admin']==0 ) {
  $r=CheckAction($g_dossier,$g_user,FACT);
  if ($r == 0 ){
    /* Cannot Access */
    NoAccess();
  }
}
include_once("facture_inc.php");

$l_dossier=sprintf("dossier%d",$g_dossier);
$cn=DbConnect($l_dossier);
ShowMenuComptaLeft($g_dossier,MENU_FACT);
ShowMenuComptaRight($g_dossier,$g_UserProperty); 
if ( $_GET["action"] ) {
  if ($_GET["fact"] == "all" ) {
    ViewFactAll($cn);
  }
  if ($_GET["fact"]=="impaye") {
    ViewFactImpaye($cn);
  }
}//get action

html_page_stop();

?>
