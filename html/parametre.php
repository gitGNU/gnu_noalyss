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
/* $Revision$ */
/*! \file
 * \brief Parametre module
 */

include_once ("ac_common.php");
include_once ("postgres.php");
require_once("class_iselect.php");
require_once("class_itext.php");
require_once('class_dossier.php');
$gDossier=dossier::id();

include_once ("postgres.php");
/* Admin. Dossier */
$cn=DbConnect($gDossier);
include_once ("class_user.php");
$User=new User($cn);
$User->Check();
$User->check_dossier($gDossier);

html_page_start($_SESSION['g_theme']);

include_once("user_menu.php");
echo '<div class="u_tmenu">';

echo menu_tool('parametre.php');

echo   '<div style="float:left;background-color:#879ED4;width:100%;">';


$authorized =0;
foreach ( array(PARCA,PARPER,PARFIC,PARDOC,PARJRN,PARTVA,
		PARMP, PARPOS,PARCOORD,PARSEC) 
	  as $a) 
  {
    if ( $User->check_action($a) == 1 ) {
      $authorized=1;break;
    }
  }

if ($authorized==0)
  $User->can_request(9999,1);

// First action
$p_action="";
if ( isset($_REQUEST["p_action"]) ) {
  $p_action=$_REQUEST["p_action"];
}
switch ($p_action) {
 case 'jrn':
   $default=10;
   break;
 case 'pcmn':
   $default=11;
   break;
 case 'company':
   $default=1;
   break;
 case 'divers':
   $default=2;
   break;
 case 'tva':
   $default=3;
   break;
 case 'poste':
   $default=4;
   break;
 case 'fiche':
   $default=5;
   break;
 case 'sec':
   $default=8;
   break;

 case 'document':
   $default=7;
   break;
 case 'company':
   $default=1;
   break;
 case 'preod':
   $default=12;
   break;
 default:
   $default="parametre.php?p_action=".$p_action;
 }

echo ShowMenuParam($default);
echo '</div>';
echo '</div>';

// sub action
$action="";
if ( isset ($_POST['action']) ) {
  $action=$_POST['action'];
}
echo_debug("parametre",__LINE__,$_POST);
//-----------------------------------------------------
// TVA RATE
//-----------------------------------------------------
if ( $p_action == "tva" ) 
{
  echo '</div>';
  $User->can_request(PARTVA,1);
  require_once("tva.inc.php");
  // 
}
//-----------------------------------------------------
// Account 
//-----------------------------------------------------
if ( $p_action == "poste" ) 
{
  $User->can_request(PARPOS,1);
  require_once('poste.inc.php');

}
//-----------------------------------------------------
// fiche 
//-----------------------------------------------------
if ( $p_action == "fiche" ) 
{
  $User->can_request(PARFIC,1);
  require_once('fiche_def.inc.php');
  return;
}

if ( $p_action == 'divers') {
  $s=dossier::get().'&PHPSESSID='.$_REQUEST['PHPSESSID'];

  $array = array (
		  array('parametre.php?p_action=divers&sa=mp&'.$s,
			'Moyen de paiement','Moyen de paiement',2)
		  );
  $sa=(isset($_REQUEST['sa']))?$_REQUEST['sa']:'';
  $sb=(isset($_REQUEST['sb']))?$_REQUEST['sb']:'';
  $def=0;
  switch ($sa) {
  
  case 'mp':
    $def=2;
    break;
  }
  echo '<div class="lmenu">';
  echo ShowItem($array,'H','mtitle','mtitle',$def);
  echo '</div>';
  
  if ( $sa=='mp') {
    $User->can_request(PARMP,1);
    require_once('payment_middle.inc.php');
    exit;
  }
}
//-----------------------------------------------------
// Coord societe
//-----------------------------------------------------
if ( $p_action=='company') { 
  $User->can_request(PARCOORD,1);
  echo '<div class="content">';
  require_once("class_own.php");
  if ( isset ($_POST['record_company'] )) {
    $m=new Own($cn);
    extract($_POST);
    $m->MY_NAME=$p_name;
    $m->MY_TVA=$p_tva;
    $m->MY_STREET=$p_street;
    $m->MY_NUMBER=$p_no;
    $m->MY_CP=$p_cp;
    $m->MY_COMMUNE=$p_Commune;
    $m->MY_TEL=$p_tel;
    $m->MY_FAX=$p_fax;
    $m->MY_PAYS=$p_pays;
    $m->MY_CHECK_PERIODE=$p_check_periode;
    if ( $User->check_action(PARCA)!=0)$m->MY_ANALYTIC=$p_compta;
    if ( $User->check_action(PARSTR)!=0) $m->MY_STRICT=$p_strict;
    if ( $User->check_action(PARTVA)!=0)$m->MY_TVA_USE=$p_tva_use;
    $m->MY_PJ_SUGGEST=$p_pj;
    $m->Update();
  }

  $my=new Own($cn);
  ///// Compta analytic
  $array=array (
				array("value"=>"ob",'label'=>"obligatoire"),
				array("value"=>"op",'label'=>"optionnel"),
				array("value"=>"nu",'label'=>"non utilisé")
				);
  $strict_array=array(
		      array('value'=>'N','label'=>'Non'),
		      array('value'=>'Y','label'=>'Oui')
		      );

  $compta=new ISelect();
  $compta->table=1;
  $compta->selected=$my->MY_ANALYTIC;

  $strict=new ISelect();
  $strict->table=1;
  $strict->selected=$my->MY_STRICT;

  $tva_use=new ISelect();
  $tva_use->table=1;
  $tva_use->selected=$my->MY_TVA_USE;

  $pj_suggest=new ISelect();
  $pj_suggest->table=1;
  $pj_suggest->selected=$my->MY_PJ_SUGGEST;
  
  $check_periode=new ISelect();
  $check_periode->table=1;
  $check_periode->selected=$my->MY_CHECK_PERIODE;

  // other parameters
  $all=new IText();
  $all->table=1;
  echo '<form method="post" action="?p_action=company">';
  echo dossier::hidden();
  echo "<table class=\"result\">";
  echo "<tr>".td('Nom société').$all->input("p_name",$my->MY_NAME)."</tr>";
  echo "<tr>".td("Téléphone").$all->input("p_tel",$my->MY_TEL)."</tr>";
  echo "<tr>".td("Fax").$all->input("p_fax",$my->MY_FAX)."</tr>";
  echo "<tr>".td("Rue ").$all->input("p_street",$my->MY_STREET)."</tr>";
  echo "<tr>".td("Numéro").$all->input("p_no",$my->MY_NUMBER)."</tr>";
  echo "<tr>".td("Code Postal").$all->input("p_cp",$my->MY_CP)."</tr>";
  echo "<tr>".td("Commune").$all->input("p_Commune",$my->MY_COMMUNE)."</tr>";
  echo "<tr>".td("Pays").$all->input("p_pays",$my->MY_PAYS)."</tr>";
  echo "<tr>".td("Numéro de Tva").$all->input("p_tva",$my->MY_TVA)."</tr>";
  if ( $User->check_action(PARCA)==0) $compta->setReadonly(true);
  echo "<tr>".td("Utilisation de la compta. analytique").$compta->input("p_compta",$array)."</tr>";
  if ( $User->check_action(PARSTR)==0) $strict->setReadonly(true);
  echo "<tr>".td("Utilisation du mode strict ").$strict->input("p_strict",$strict_array)."</tr>";
  if ( $User->check_action(PARTVA)==0) $tva_use->setReadonly(true);
  echo "<tr>".td("Assujetti à la tva").$tva_use->input("p_tva_use",$strict_array)."</tr>";
  echo "<tr>".td("Suggérer le numéro de pièce justificative").$pj_suggest->input("p_pj",$strict_array)."</tr>";
  echo '<tr>'.td('Afficher la période comptable pour éviter les erreurs de date').$check_periode->input('p_check_periode',$strict_array).'</tr>';
  echo "</table>";
  echo HtmlInput::submit("record_company","Enregistre");
  echo "</form>";
  echo '</div>';
  exit();
 }
//-----------------------------------------------------
// Document 
//-----------------------------------------------------
echo "</DIV>";
if ( $p_action == 'document' ) {
  $User->can_request(PARDOC,1);
  echo '<div class="content">';
  require_once('document_modele.inc.php');
  echo '</div>';
}  
//----------------------------------------------------------------------
// Plan Comptable
//----------------------------------------------------------------------
if ( $p_action == 'pcmn' ) {
  $User->can_request(PARPCMN,1);
  require_once('param_pcmn.inc.php');
}  
//----------------------------------------------------------------------
// Security
//----------------------------------------------------------------------
if ( $p_action == 'sec' ) {
  $User->can_request(PARSEC,1);
  require_once('param_sec.inc.php');
}  
//----------------------------------------------------------------------
// Predefined operation
//----------------------------------------------------------------------
if ( $p_action == 'preod' ) {
  $User->can_request(PARPREDE,1);
  require_once('preod.inc.php');
}  

//---------------------------------------------------------------------------
// Definition of report
//---------------------------------------------------------------------------
if ( $p_action == 'defrapport' ) {
  $User->can_request(PARRAP,1);
  require_once('report.inc.php');
}
//----------------------------------------------------------------------
// Ledger parameter
//----------------------------------------------------------------------
if ( $p_action == 'jrn' ) {
  $User->can_request(PARJRN,1);
  $sa=(isset($_REQUEST['sa']))?$_REQUEST['sa']:"";
  //  echo '<div class="content">';
  if ( $sa == "add") 
    require_once ('param_jrn_add.inc.php');
  elseif ($sa=='detail') 
    require_once ('param_jrn_detail.inc.php');
  else
    require_once('param_jrn.inc.php');
  //  echo '</div>';
}  



html_page_stop();
?>
