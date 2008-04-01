<?php
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
require_once("check_priv.php");
require_once('class_dossier.php');
require_once ('class_widget.php');
require_once ('class_pre_operation.php');
/* $Revision$ */
/*! \file
 * \brief Obsolete
 */
$gDossier=dossier::id();

html_page_start($_SESSION['g_theme']);

include_once ("postgres.php");
/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new User($rep);
$User->Check();
$cn=DbConnect(dossier::id());
include_once ("postgres.php");
echo_debug('user_advanced.php',__LINE__,"user is ".$_SESSION['g_user']);


// We don't check permissions here in fact, permission are tested in the
// functions 

// Show the top menus
include_once ("user_menu.php");
echo '<div class="u_tmenu">';

//echo ShowMenuCompta("user_advanced.php?".dossier::get());
echo ShowMenuCompta(7);
echo '</div>';
$p_action="";


$p_action=(isset($_REQUEST['p_action']))?$_REQUEST['p_action']:"";
switch ($p_action) {
 case 'preod':
   $high=9;
   break;
 case 'periode';
 $high=2;
 break;
 case 'verif';
 $high=10;
 break;
 case 'central';
 $high=3;
 break;
case 'defreport':
  $high=6;
  break;
 default:
   $high=0;
   
 }
echo ShowMenuAdvanced($high);

if ($p_action == "periode" ) {
  if ( $User->admin == 0 && check_action($gDossier,$_SESSION['g_user'],GESTION_PERIODE) == 0 )
	NoAccess();
    
  $p_action=$_REQUEST['p_action'];
  include_once("periode.inc.php");
}
//--------------------------------------------------
// Predefined operation
//--------------------------------------------------

if ($p_action=="preod") {
  require_once('preod.inc.php');

  exit();
 }
//----------------------------------------------------------------------
// Verification solde
//----------------------------------------------------------------------
if ( $p_action=='verif' ) {
  require_once ('verif_bilan.inc.php');
  exit();
 }
if ( $p_action=='central') 
  require_once ('central.inc.php');


if ($p_action=='defreport') {
  require_once('report.inc.php');
}

html_page_stop();
?>
