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
require_once("class_widget.php");
require_once('class_dossier.php');
$gDossier=dossier::id();

include_once ("postgres.php");
/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new User($rep);
$User->Check();

html_page_start($_SESSION['g_theme']);
include_once("preference.php");
include_once("user_menu.php");
echo '<div class="u_tmenu">';

echo menu_tool('param');
echo '<div style="float:left">';

include_once ("check_priv.php");

$cn=DbConnect($gDossier);

$User->can_request($cn,PARM);

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
  require_once("tva.inc.php");
  // 
}
//-----------------------------------------------------
// Account 
//-----------------------------------------------------
if ( $p_action == "poste" ) 
{
  require_once('poste.inc.php');

}
//-----------------------------------------------------
// fiche 
//-----------------------------------------------------
if ( $p_action == "fiche" ) 
{
  require_once('fiche_def.inc.php');
  return;
}

if ( $p_action == 'divers') {
  $s=dossier::get().'&PHPSESSID='.$_REQUEST['PHPSESSID'];

  $array = array (/*array('parametre.php?p_action=divers&sa=devise&'.$s,
		    'Devise','Devise',1),*/
		  array('parametre.php?p_action=divers&sa=mp&'.$s,
			'Moyen de paiement','Moyen de paiement',2)
		  );
  $sa=(isset($_REQUEST['sa']))?$_REQUEST['sa']:'';
  $sb=(isset($_REQUEST['sb']))?$_REQUEST['sb']:'';
  $def=0;
  switch ($sa) {
  case 'devise':
    $def=1;
    break;
  case 'mp':
    $def=2;
    break;
  }
  echo '<div class="lmenu">';
  echo ShowItem($array,'H','mtitle','mtitle',$def);
  echo '</div>';

  if ( $sa=='devise') {
    echo '<DIV CLASS="u_redcontent">';
    //-----------------------------------------------------
    // Currency
    //-----------------------------------------------------
    if ( $sb == "c" ) {
      $p_mid=$_GET['p_mid'];
      $p_rate=$_GET['p_rate'];
      $p_code=$_GET['p_code'];
      
      echo '<TR> <FORM ACTION="parametre.php" METHOD="POST">';
      echo dossier::hidden();
      echo '<INPUT TYPE="HIDDEN" VALUE="'.$p_mid.'" NAME="p_id">';
      echo '<TD> <INPUT TYPE="text" NAME="p_devise" VALUE="'.$p_code.'"></TD>';
      echo '<TD> <INPUT TYPE="text" NAME="p_rate" VALUE="'.$p_rate.'"></TD>';
      echo '<TD> <INPUT TYPE="SUBMIT" NAME="action" Value="Change"</TD>';
      echo '</FORM></TR>';
    }
    if ( $sb == "ch") {
      $p_devise=$_GET['p_code'];
      $p_id=$_GET['p_id'];
      $p_rate=$_GET['p_rate'];
      $Res=ExecSql($cn,"update parm_money set pm_code='$p_devise',pm_rate=$p_rate where pm_id=$p_id");
      ShowDevise($cn);
      
    }
    if ( $sb == "a") {
      $p_devise=$_POST['p_devise'];
      $p_rate=$_POST['p_rate'];
      $Res=ExecSql($cn,"insert into parm_money ( pm_code,pm_rate) values ('$p_devise',$p_rate) ");
      ShowDevise($cn);

    }
    
    if ( $sb == "d") {
      $p_id=$_GET['p_mid'];
      $Res=ExecSql($cn,"delete from parm_money  where pm_id=$p_id");
      ShowDevise($cn);
    }
    
    
    if ( $p_action=="divers") {
      ShowDevise($cn);
    }
  }
  if ( $sa=='mp') {

    require_once('payment_middle.inc.php');
    exit;
  }
}
//-----------------------------------------------------
// Coord societe
//-----------------------------------------------------
if ( $p_action=='company') { 
  echo '<div class="content">';
  require_once("class_own.php");
  require_once("class_widget.php");
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
    $m->MY_ANALYTIC=$p_compta;
    $m->MY_STRICT=$p_strict;
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

  $compta=new widget("select");
  $compta->table=1;
  $compta->selected=$my->MY_ANALYTIC;

  $strict=new widget("select");
  $strict->table=1;
  $strict->selected=$my->MY_STRICT;

  // other parameters
  $all=new widget("text");
  $all->table=1;
  echo '<form method="post" action="?p_action=company">';
  echo dossier::hidden();
  echo "<table class=\"result\">";
  echo "<tr>".$all->IOValue("p_name",$my->MY_NAME,"Nom société")."</tr>";
  echo "<tr>".$all->IOValue("p_tel",$my->MY_TEL,"Téléphone")."</tr>";
  echo "<tr>".$all->IOValue("p_fax",$my->MY_FAX,"Fax")."</tr>";
  echo "<tr>".$all->IOValue("p_street",$my->MY_STREET,"Rue ")."</tr>";
  echo "<tr>".$all->IOValue("p_no",$my->MY_NUMBER,"Numéro")."</tr>";
  echo "<tr>".$all->IOValue("p_cp",$my->MY_CP,"Code Postal")."</tr>";
  echo "<tr>".$all->IOValue("p_Commune",$my->MY_COMMUNE,"Commune")."</tr>";
  echo "<tr>".$all->IOValue("p_pays",$my->MY_PAYS,"Pays")."</tr>";
  echo "<tr>".$all->IOValue("p_tva",$my->MY_TVA,"Numéro de Tva")."</tr>";
  echo "<tr>".$compta->IOValue("p_compta",$array,"Utilisation de la compta. analytique")."</tr>";
  echo "<tr>".$strict->IOValue("p_strict",$strict_array,"Utilisation du mode strict ")."</tr>";

  echo "</table>";
  $submit=new widget("submit");
  echo widget::submit("record_company","Enregistre");
  echo "</form>";
  echo '</div>';
  exit();
 }
//-----------------------------------------------------
// Document 
//-----------------------------------------------------
echo "</DIV>";
if ( $p_action == 'document' ) {
  echo '<div class="content">';
  require_once('document_modele.inc.php');
  echo '</div>';
}  
//----------------------------------------------------------------------
// Plan Comptable
//----------------------------------------------------------------------
if ( $p_action == 'pcmn' ) {
  require_once('param_pcmn.inc.php');
}  
//----------------------------------------------------------------------
// Security
//----------------------------------------------------------------------
if ( $p_action == 'sec' ) {
  require_once('param_sec.inc.php');
}  
//----------------------------------------------------------------------
// Predefined operation
//----------------------------------------------------------------------
if ( $p_action == 'preod' ) {
  require_once('preod.inc.php');
}  

//---------------------------------------------------------------------------
// Definition of report
//---------------------------------------------------------------------------
if ( $p_action == 'defrapport' ) {
  require_once('report.inc.php');
}
//----------------------------------------------------------------------
// Ledger parameter
//----------------------------------------------------------------------
if ( $p_action == 'jrn' ) {
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
