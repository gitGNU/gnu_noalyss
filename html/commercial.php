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

echo JS_AJAX_FICHE;
require_once('class_dossier.php');
$gDossier=dossier::id();

$g_name=dossier::name();


require_once('class_database.php');
/* Admin. Dossier */
$rep=new Database($gDossier);
require_once ("class_user.php");
$User=new User($rep);
$User->Check();
$User->check_dossier($gDossier);

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
html_page_start($_SESSION['g_theme'],"","richtext.js");

if ( ! isset ( $gDossier ) ) {
  echo "Vous devez choisir un dossier ";
  exit -2;
}
include_once("user_menu.php");
$str_dossier=dossier::get();

$p_action=(isset ($_REQUEST['p_action']))?$_REQUEST['p_action']:"";
// TODO Menu with all the customer
//echo '<div class="u_tmenu">';
///echo '<div style="float:left">';
echo '<div class="u_tmenu">';
echo menu_tool('commercial.php');

echo '<div style="float:left;background-color:#879ED4;width:100%;">';

echo ShowItem(array(
		    array('?p_action=tdb&'.$str_dossier,'Tableau de bord'),
		    array('?p_action=client&'.$str_dossier,'Client'),
		    array('?p_action=fournisseur&'.$str_dossier,'Fournisseur'),
		    array('?p_action=adm&'.$str_dossier,'Administration'),
		    array('?p_action=stock&'.$str_dossier,'Stock'),
		    array('?p_action=fiche&'.$str_dossier,'Fiche'),
		    array('?p_action=prev&'.$str_dossier,'Prevision'),
		    array('?p_action=suivi_courrier&'.$str_dossier,'Suivi Courrier'),
		    ),
	      'H',"mtitle","mtitle","?p_action=$p_action&".$str_dossier,' width="100%"');

echo '</div>';
echo '</div>';

$cn=new Database($gDossier);

echo JS_VIEW_JRN_MODIFY;
echo JS_AJAX_FICHE;

//-----------------------------------------------------
// p_action == pref
//-----------------------------------------------------
if ( $p_action == "pref" ) 
{
  require_once("pref.inc.php");
}

//-----------------------------------------------------
// p_action == client
//-----------------------------------------------------
if ( $p_action == "client" ) 
{
  $User->can_request(GECUST,1);
  $action=0;
  require_once("client.inc.php");
}// $p_action == fournisseur
//-----------------------------------------------------
// Fournisseur
if ( $p_action == 'fournisseur') 
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
// p_action == facture
/*!
 \todo remove it : obsolete 
*/
//-----------------------------------------------------
if ( $p_action == "ven" ) 
{
  require_once("compta_ven.inc.php");
}
//-----------------------------------------------------
// Contact
if ( $p_action == 'fiche') 
{
  require_once("fiche.inc.php");
}
//-----------------------------------------------------
/*!
\todo remove it : obsolete 
*/
// Expense
if ( $p_action == 'ach') 
{
  require_once("compta_ach.inc.php");
}

//-----------------------------------------------------
// Banque
if ( $p_action == 'bank') 
{
  require_once("compta_fin.inc.php");
}
if ( $p_action=='quick_writing') {
  require_once ('quick_writing.inc.php');
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
if ( $p_action=='central') {
  $User->can_request(PARCENT,1);
  require_once ('central.inc.php');
 }
//-----------------------------------------------------
// Expense
if ( $p_action == 'defreport') 
{
  $User->can_request(PARPREDE,1);
  require_once("report.inc.php");
}
