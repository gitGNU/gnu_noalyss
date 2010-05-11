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
require_once('class_dossier.php');
require_once ('class_pre_operation.php');
/* $Revision$ */
/*! \file
 * \brief call the different advanced parameters as report creation, periode,
 * ...
 */
$gDossier=dossier::id();

html_page_start($_SESSION['g_theme']);

require_once('class_database.php');
/* Admin. Dossier */
$rep=new Database();
include_once ("class_user.php");
$User=new User($rep);
$User->Check();
$User->check_dossier(dossier::id());
$cn=new Database(dossier::id());
$User->db=$cn;

require_once('class_database.php');
require_once('class_ipopup.php');
echo js_include('accounting_item.js');
echo js_include('prototype.js');
echo js_include('scriptaculous.js');
echo js_include('effects.js');
echo js_include('controls.js');
echo js_include('dragdrop.js');
echo js_include('acc_ledger.js');
echo js_include('ajax_fiche.js');
echo JS_CARD;
echo ICard::ipopup('ipopcard');
echo IPoste::ipopup('ipop_account');
$search_card=new IPopup('ipop_card');
$search_card->title=_('Recherche de fiche');
$search_card->value='';
echo $search_card->input();



// We don't check permissions here in fact, permission are tested in the
// functions 

// Show the top menus
include_once ("user_menu.php");
echo '<div class="u_tmenu">';


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
case 'ouv':
  $high=8;
  break;
default:
   $high=0;
   
 }
echo ShowMenuAdvanced($high);

if ($p_action == "periode" ) {

  if ( $User->check_action(PARPER) == 0 && $User->check_action(PARCLO) == 0 )
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
/* --------------------------------------------------
Import writing (opening exercice)
-------------------------------------------------- */
if ( $p_action == 'ouv') {
  require_once('opening.inc.php');
}
html_page_stop();
?>
