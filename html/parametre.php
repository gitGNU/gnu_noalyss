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
require_once('class_database.php');
require_once("class_iselect.php");
require_once("class_itext.php");
require_once('class_dossier.php');
require_once('class_iposte.php');
echo js_include('prototype.js');
echo js_include('scriptaculous.js');
echo js_include('effects.js');
echo js_include('controls.js');
echo js_include('scripts.js');
echo js_include('acc_ledger.js');
echo js_include('card.js');
echo js_include('accounting_item.js');
echo js_include('ajax_fiche.js');
echo js_include('dragdrop.js');

$gDossier=dossier::id();

require_once('class_database.php');
/* Admin. Dossier */
$cn=new Database($gDossier);
include_once ("class_user.php");
$User=new User($cn);
$User->Check();
$User->check_dossier($gDossier);

html_page_start($_SESSION['g_theme']);

include_once("user_menu.php");
echo '<div class="u_tmenu">';

echo menu_tool('parametre.php');

echo   '<div class="topmenu">';


$authorized =0;
foreach ( array(PARCA,PARPER,PARFIC,PARDOC,PARJRN,PARTVA,
		PARMP, PARPOS,PARCOORD,PARSEC,EXTENSION) 
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
 case 'ext':
   $default=3;
   break;
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

//-----------------------------------------------------
// divers 
//-----------------------------------------------------
if ( $p_action == 'divers') {
  $s=dossier::get();

  $array = array (
		  array('parametre.php?p_action=divers&sa=mp&'.$s,_('Moyen de paiement'),_('Moyen de paiement'),2),
		  array('parametre.php?p_action=divers&sa=tva&'.$s,_('Tva'),_('Taux et poste comptable tva'),4),
		  array('parametre.php?p_action=divers&sa=poste&'.$s,_('Poste Comptable'),_('Poste comptable constant'),7),
		  array('parametre.php?p_action=divers&sa=fiche&'.$s,_('Catégorie de fiche'),_('Modifie les classe de base, les attribut,...'),5),
		  array('parametre.php?p_action=divers&sa=cdoc&'.$s,_('Catégorie de documents'),_('Ajoute des catégories de documents,...'),6)
		  );
  $sa=(isset($_REQUEST['sa']))?$_REQUEST['sa']:'';
  $sb=(isset($_REQUEST['sb']))?$_REQUEST['sb']:'';
  $def=0;
  switch ($sa) {
    case 'mp':
    $def=2;
    break;
    case 'tva':
    $def=4;
    break;
  case 'poste':
    $def=7;
    break;
  case 'fiche':
    $def=5;
    break;
  case 'cdoc':
    $def=6;
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

  //-----------------------------------------------------
  // TVA RATE
  //-----------------------------------------------------
  if ( $sa == "tva" ) 
    {
      echo '</div>';
      $User->can_request(PARTVA,1);
      require_once("tva.inc.php");
      // 
    }
  //-----------------------------------------------------
  // Accounting item
  //-----------------------------------------------------
  if ( $sa == "poste" ) 
    {
      $User->can_request(PARPOS,1);
      require_once('poste.inc.php');

    }
  //-----------------------------------------------------
  // fiche 
  //-----------------------------------------------------
  if ( $sa == "fiche" ) 
    {
      $User->can_request(PARFIC,1);
      require_once('fiche_def.inc.php');
      return;
    }
  //----------------------------------------------------
  // Cat. Document
  //----------------------------------------------------
  if ( $def== 6) {
    $User->can_request(PARCATDOC,1);
    require_once('cat_document.inc.php');
    return;
  }

 }
//-----------------------------------------------------
// Extension
//-----------------------------------------------------

if ( $p_action=='ext') {
  $User->can_request(EXTENSION,1);
  require_once('extension.inc.php');
  exit;
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
    $m->MY_DATE_SUGGEST=$p_date_suggest;
    if ( $User->check_action(PARCA)!=0)$m->MY_ANALYTIC=$p_compta;
    if ( $User->check_action(PARSTR)!=0) $m->MY_STRICT=$p_strict;
    if ( $User->check_action(PARTVA)!=0)$m->MY_TVA_USE=$p_tva_use;
    $m->MY_PJ_SUGGEST=$p_pj;
    $m->Update();
  }

  $my=new Own($cn);
  ///// Compta analytic
  $array=array (
		array("value"=>"ob",'label'=>_("obligatoire")),
		array("value"=>"op",'label'=>_("optionnel")),
		array("value"=>"nu",'label'=>_("non utilisé"))
				);
  $strict_array=array(
		      array('value'=>'N','label'=>_('Non')),
		      array('value'=>'Y','label'=>_('Oui'))
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
 
  $date_suggest=new ISelect();
  $date_suggest->table=1;
  $date_suggest->selected=$my->MY_DATE_SUGGEST;
 
  $check_periode=new ISelect();
  $check_periode->table=1;
  $check_periode->selected=$my->MY_CHECK_PERIODE;

  // other parameters
  $all=new IText();
  $all->table=1;
  echo '<form method="post" action="?p_action=company">';
  echo dossier::hidden();
  echo "<table class=\"result\" style=\"width:auto\">";
  echo "<tr>".td(_('Nom société'),'style="text-align:right"').$all->input("p_name",$my->MY_NAME)."</tr>";
  $all->value='';
  echo "<tr>".td(_("Téléphone"),'style="text-align:right"').$all->input("p_tel",$my->MY_TEL)."</tr>";
  $all->value='';
  echo "<tr>".td(_("Fax"),'style="text-align:right"').$all->input("p_fax",$my->MY_FAX)."</tr>";
  $all->value='';
  echo "<tr>".td(_("Rue "),'style="text-align:right"').$all->input("p_street",$my->MY_STREET)."</tr>";
  $all->value='';
  echo "<tr>".td(_("Numéro"),'style="text-align:right"').$all->input("p_no",$my->MY_NUMBER)."</tr>";
  $all->value='';
  echo "<tr>".td(_("Code Postal"),'style="text-align:right"').$all->input("p_cp",$my->MY_CP)."</tr>";
  $all->value='';
  echo "<tr>".td(_("Commune"),'style="text-align:right"').$all->input("p_Commune",$my->MY_COMMUNE)."</tr>";
  $all->value='';
  echo "<tr>".td(_("Pays"),'style="text-align:right"').$all->input("p_pays",$my->MY_PAYS)."</tr>";
  $all->value='';
  echo "<tr>".td(_("Numéro de Tva"),'style="text-align:right"').$all->input("p_tva",$my->MY_TVA)."</tr>";
  if ( $User->check_action(PARCA)==0) $compta->setReadonly(true);
  echo "<tr>".td(_("Utilisation de la compta. analytique"),'style="text-align:right"').$compta->input("p_compta",$array)."</tr>";
  if ( $User->check_action(PARSTR)==0) $strict->setReadonly(true);
  echo "<tr>".td(_("Utilisation du mode strict "),'style="text-align:right"').$strict->input("p_strict",$strict_array)."</tr>";
  if ( $User->check_action(PARTVA)==0) $tva_use->setReadonly(true);
  echo "<tr>".td(_("Assujetti à la tva"),'style="text-align:right"').$tva_use->input("p_tva_use",$strict_array)."</tr>";
  echo "<tr>".td(_("Suggérer le numéro de pièce justificative"),'style="text-align:right"').$pj_suggest->input("p_pj",$strict_array)."</tr>";
  echo "<tr>".td(_("Suggérer la date"),'style="text-align:right"').$date_suggest->input("p_date_suggest",$strict_array)."</tr>";
  echo '<tr>'.td(_('Afficher la période comptable pour éviter les erreurs de date'),'style="text-align:right"').$check_periode->input('p_check_periode',$strict_array).'</tr>';
  echo "</table>";
  echo HtmlInput::submit("record_company",_("Sauve"));
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
