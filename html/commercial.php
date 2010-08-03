<?php
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
/*! \file
 * \brief Base of the module "Gestion", the p_action indicates what
 *        file must included and this file will manage the request
 *        (customer, supplier, contact,...)
 */
include_once ("ac_common.php");
require_once("constant.php");
require_once('class_database.php');
require_once('class_dossier.php');
require_once('function_javascript.php');
echo load_all_script();

$gDossier=dossier::id();

$g_name=dossier::name();


require_once('class_database.php');
/* Admin. Dossier */
$rep=new Database($gDossier);
require_once ("class_user.php");
$User=new User($rep);
$User->Check();
if ($User->check_dossier($gDossier)=='P')  { 	
  redirect("extension.php?".dossier::get(),0);
  exit();
}

//-----------------------------------------------------
// update preference
//-----------------------------------------------------
if ( isset ( $_POST['style_user']) ) {
	$User->update_global_pref('THEME',$_POST['style_user']);
      $_SESSION['g_theme']=$_POST['style_user'];

}
// Met a jour le pagesize
if ( isset ( $_POST['p_size']) ) {
	$User->update_global_pref('PAGESIZE',$_POST['p_size']);
      $_SESSION['g_pagesize']=$_POST['p_size'];

}

///
html_page_start($_SESSION['g_theme'],"","");

if ( ! isset ( $gDossier ) ) {
  echo _("Vous devez choisir un dossier ");
  exit -2;
}
include_once("user_menu.php");
$str_dossier=dossier::get();

$p_action=(isset ($_REQUEST['p_action']))?$_REQUEST['p_action']:"";
echo '<div class="u_tmenu">';
echo menu_tool('commercial.php');

echo '<div class="topmenu">';
$def=0;
if  ( isset($_REQUEST['p_action'])) {
  switch($_REQUEST['p_action']) {
  case'tdb':
    $def=1;
   break;
  case'client':
    $def=2;
    break;
  case'tdb':
    $def=1;
    break;
  case'supplier':
    $def=3;
    break;
  case'adm':
    $def=4;
    break;
  case'stock':
    $def=5;
    break;
  case'fiche':
    $def=6;
    break;
  case'prev':
    $def=7;
    break;
  case'suivi_courrier':
    $def=8;
    break;
  case'impress':
    $def=9;
    break;

  }
}
echo ShowItem(array(
		    array('?p_action=client&'.$str_dossier,_('Client'),'',2),
		    array('?p_action=supplier&'.$str_dossier,_('Fournisseur'),'',3),
		    array('?p_action=adm&'.$str_dossier,_('Administration'),'',4),
		    array('?p_action=impress&'.$str_dossier,_('Impression'),'',9),
		    array('?p_action=stock&'.$str_dossier,_('Stock'),'',5),
		    array('?p_action=fiche&'.$str_dossier,_('Fiche'),'',6),
		    array('?p_action=prev&'.$str_dossier,_('Prevision'),'',7),
		    array('?p_action=suivi_courrier&my_action&'.$str_dossier,_('Suivi'),'',8),
		    ),
	      'H',"mtitle","mtitle",$def,' style="width:94%;margin-left:3%"');

echo '</div>';
echo '</div>';

$cn=new Database($gDossier);

echo JS_LEDGER;
echo JS_AJAX_FICHE;

//-----------------------------------------------------
// p_action == pref
//-----------------------------------------------------
if ( $p_action == "pref" )
{
  require_once("pref.inc.php");
}
//-----------------------------------------------------
// p_action == impression
//-----------------------------------------------------
if ( $p_action == "impress" )
{
  require_once("impress.inc.php");
}


//-----------------------------------------------------
// p_action == adm
//-----------------------------------------------------
if ( $p_action == "adm" )
{
  $User->can_request(GEADM,1);
  require_once("adm.inc.php");
}
//-----------------------------------------------------
// p_action == client
//-----------------------------------------------------
if ( $p_action == "client" )
{
  $User->can_request(GECUST,1);
  require_once("client.inc.php");
}
// $p_action == fournisseur
//-----------------------------------------------------
// Fournisseur
if ( $p_action == 'supplier')
{
  $User->can_request(GESUPPL,1);
  require_once("supplier.inc.php");
}

//-----------------------------------------------------
// action
if ( $p_action == 'suivi_courrier')
{
  $User->can_request(GECOUR,1);
  require_once("action.inc.php");
}
//-----------------------------------------------------
// Contact
if ( $p_action == 'fiche')
{
  require_once("fiche.inc.php");
}
//-----------------------------------------------------
// Impression
if ( $p_action == 'impress')
{
  if ( $User->check_action(IMPRAP)==1 ||
       $User->check_action(IMPJRN)==1 ||
       $User->check_action(IMPFIC)==1 ||
       $User->check_action(IMPPOSTE)==1 ||
       $User->check_action(IMPBIL)==1 )
    require_once("impress.inc.php");
  else
    $User->can_request(9999,1);
}
if ( $p_action == 'fiche') {
  require_once('fiche.inc.php');
}
if ( $p_action == 'stock') {
  require_once('stock.inc.php');
}
if ( $p_action=='periode') {
  if ( $User->check_action(PARPER)==1 ||
       $User->check_action(PARCLO)==1)
    require_once ('periode.inc.php');
  else
    $User->can_request(9999,1);
 }
//-----------------------------------------------------
// Expense
if ( $p_action == 'defreport')
{
  $User->can_request(PARPREDE,1);
  require_once("report.inc.php");
}
/*----------------------------------------------------------------------
 * Prevision
 *
 *----------------------------------------------------------------------*/
if ( $p_action=='prev') {
  $User->can_request(PREVCON,1);
  require_once ('forecast.inc.php');
}
