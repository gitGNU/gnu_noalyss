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

/*!\file 
 * \brief Manage the detail for each hypo by analytic account
*/
/*!
 * \brief Manage the detail for each hypo by analytic account
 */
require_once("class_iselect.php");
require_once("class_itext.php");
require_once ('class_bud_hypo.php');
require_once ('class_anc_account.php');

echo '<div class="content">';



/* 1st  possibility is not defined */
if ( ! isset ($_REQUEST['bh_id'])) {
  $wHypo=new ISelect("","bh_id");
  $wHypo->value=$cn->make_array("select bh_id,bh_name from bud_hypothese",0);
  $wHypo->selected=(isset($_REQUEST['bh_id']))?$_REQUEST['bh_id']:"";
  $wHypo->javascript='onChange="this.form.submit();"';

  echo '<form method="get">';
  echo dossier::hidden();
  echo  "Hypoth&egrave;se ".$wHypo->input();
  echo HtmlInput::submit('recherche','recherche');
  echo HtmlInput::hidden('p_action','detail');

  echo '</form>';

  echo '<hr>';
  exit;
 }
$button_other=HtmlInput::button_href('Autre detail','?gDossier='.dossier::id().
				  '&p_action=detail');

/* 2nd Step BH_ID is defined
 *   if $wPost_Analytic->value is not empty then there is an analytic
 *  plan, a analytic account must be choosen otherwise go directly to
 *  the detail  
 */
$wPost_Analytic=new ISelect("","po_id");
$wPost_Analytic->value=$cn->make_array("select po_id,po_name from poste_analytique join ".
				  " bud_hypothese as a using (pa_id) ".
				  " where a.bh_id=".$_REQUEST['bh_id']);

$po_id=(isset($_REQUEST['po_id']))?$_REQUEST['po_id']:-2;

if (  empty ($wPost_Analytic->value) ) {
  $po_id=-1;
 }

$Hyp=new Bud_Hypo($cn,$_REQUEST['bh_id']);
$Hyp->load();

if ( ! empty ($wPost_Analytic->value) && $po_id==-2  ) {
  $wHypo=new IText("","bh_id",$Hyp->bh_name);
  $wHypo->readonly=true;
  $wPost_Analytic->javascript='onChange="this.form.submit();"';

  echo '<form method="get">';
  echo  "Hypoth&egrave;se ".$wHypo->input();
  echo HtmlInput::hidden('bh_id',$_REQUEST['bh_id']);
  echo HtmlInput::hidden('p_action','detail');
  echo dossier::hidden();
  echo "Poste Analytique ".$wPost_Analytic->input();
  echo HtmlInput::submit('recherche','recherche');
  echo '</form>';
  echo $button_other;

  echo '<hr>';
  exit();
 }

/* 3rd Step 
 * po_id is set now we can add/update/delete details
 */
$wHypo=new IText("","bh_id",$Hyp->bh_name);
$wHypo->readonly=true;
echo "Hypoth&egrave;se ".$wHypo->input();
if ( $po_id !== -1 ) {
  $oPo_id=new Anc_Account($cn,$po_id);
  $oPo_id->get_by_id();
  $wPo_id=new IText("","po_id",$oPo_id->name);
  $wPo_id->readonly=true;
  echo "Poste Analytique ".$wPo_id->input();
 }
echo $button_other;

if ( isset ($_GET['po_id'] ) )
  $str_po_id="&po_id=".$_REQUEST['po_id'];
 else 
   $str_po_id="";
echo HtmlInput::button_href("Recharger","?".dossier::get()."&bh_id=".$_GET['bh_id']."&p_action=detail".$str_po_id);
echo '<hr>';

//----------------------------------------------------------------------
// Show 20 lines by default
//----------------------------------------------------------------------
require_once ('class_bud_data.php');
echo JS_PROTOTYPE;
echo JS_BUD_SCRIPT;
extract($_GET);
$obj=new Bud_Data($cn,$bh_id,$po_id);
$r=$obj->load();
echo $obj->form();

