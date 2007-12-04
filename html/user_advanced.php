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
$User=new cl_user($rep);
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

 default:
   $high=0;
   
 }
echo ShowMenuAdvanced($high);

if ($p_action == "periode" ) {
  if ( $User->admin == 0 && CheckAction($gDossier,$_SESSION['g_user'],GESTION_PERIODE) == 0 )
	NoAccess();
    
  $p_action=$_REQUEST['p_action'];
  include_once("periode.inc.php");
}
//--------------------------------------------------
// Predefined operation
//--------------------------------------------------

if ($p_action=="preod") {
  echo '<div class="u_content">';
  echo '<form method="GET">';
  $sel=new widget('select');
  $sel->name="jrn";
  $sel->value=make_array($cn,"select jrn_def_id,jrn_def_name from ".
						 " jrn_def order by jrn_def_name");
  // Show a list of ledger
  $sa=(isset($_REQUEST['sa']))?$_REQUEST['sa']:"";
  $sel->selected=$sa;
  echo 'Choississez un journal '.$sel->IOValue();
  $wCheck=new widget("checkbox");
  if ( isset($_REQUEST['direct'])) {
    $wCheck->selected=true;
  }
  echo 'Ecriture directe'.$wCheck->IOValue('direct');

  echo dossier::hidden();
  $hid=new widget("hidden");
  echo $hid->IOValue("sa","jrn");
  echo $hid->IOValue("p_action","preod");
  echo '<hr>';
  echo widget::submit_button('Accepter','Accepter');
  echo '</form>';

  // if $_REQUEST[sa] == del delete the predefined operation
  if ( $sa == 'del') {
	$op=new Pre_operation($cn);
	$op->od_id=$_REQUEST['od_id'];
	$op->delete();
	$sa='jrn';
  }

  // if $_REQUEST[sa] == jrn show the  predefined operation for this
  // ledger
  if ( $sa == 'jrn' ) {
	$op=new Pre_operation($cn);
	$op->set_jrn($_GET['jrn']);
	if ( isset($_GET['direct'])) {
	  $op->od_direct='true';
	} else {
	  $op->od_direct='false';
	}
	$array=$op->get_list_ledger();
	if ( empty($array) == true ) {
	  echo "Aucun enregistrement";
	  exit();
	}

	echo '<table>';
	$count=0;
	foreach ($array as $row ) {

	  if ( $count %2 == 0 )
		echo '<tr class="even">';
	  else 
		echo '<tr>';
	  echo '<td>'.$row['od_name'].'</td>';
	  echo '<td>';
	  echo '<form method="POST">';
	  echo dossier::hidden();
	  echo $hid->IOValue("sa","del");
	  echo $hid->IOValue("p_action","preod");
	  echo $hid->IOValue("del","");
	  echo $hid->IOValue("od_id",$row['od_id']);
	  echo $hid->IOValue("jrn",$_GET['jrn']);

	  $b='<input type="submit" value="Effacer" '.
		' onClick="return confirm(\'Voulez-vous vraiment effacer cette operation ?\');" >';
	  echo $b;
	  echo '</form>';

	  echo '</td>';
	  echo '</tr>';

	}
	echo '</table>';
  }
  echo '</div>';
  exit();
 }
//----------------------------------------------------------------------
// Verification solde
//----------------------------------------------------------------------
if ( $p_action=='verif' ) {
  echo '<div class="u_content">';
  $User->db=$cn;
  $sql_year=" and c_periode in (select p_id from parm_periode where p_exercice='".$User->GetExercice()."')";

  echo '<ol>';
  $deb=getDbValue($cn,"select sum (c_montant) from centralized where c_debit='t' $sql_year ");
  $cred=getDbValue($cn,"select sum (c_montant) from centralized where c_debit='f' $sql_year ");

  if ( $cred == $deb ) { 
    $result ='<span style="color:green;font-size:120%;font-weight:bold;"> OK </span>';}
  else  { 
    $result ='<span style="color:red;font-size:120%;font-weight:bold;"> NON OK </span>';}

  printf ('<li> Solde Grand Livre centralis&eacute;: debit %f credit %f %s</li>',$deb,$cred,$result);

  $sql="select jrn_def_id,jrn_def_name from jrn_def";
  $res=ExecSql($cn,$sql);
  $jrn=pg_fetch_all($res);
  foreach ($jrn as $l) {
    $id=$l['jrn_def_id'];
    $name=$l['jrn_def_name'];
    $deb=getDbValue($cn,"select sum (c_montant) from centralized where c_debit='t' and c_jrn_def=$id $sql_year ");
    $cred=getDbValue($cn,"select sum (c_montant) from centralized where c_debit='f' and c_jrn_def=$id  $sql_year ");

  if ( $cred == $deb ) { 
    $result ='<span style="color:green;font-size:120%;font-weight:bold;"> OK </span>';}
  else  { 
    $result ='<span style="color:red;font-size:120%;font-weight:bold;"> NON OK </span>';}

  printf ('<li> Journal %s Solde   centralis&eacute;: debit %f credit %f %s</li>',$name,$deb,$cred,$result);
    
  }
  echo '</ol>';
  echo '<ol>';
  $sql_year=" and j_tech_per in (select p_id from parm_periode where p_exercice='".$User->GetExercice()."')";

  $deb=getDbValue($cn,"select sum (j_montant) from jrnx where j_debit='t' $sql_year ");
  $cred=getDbValue($cn,"select sum (j_montant) from jrnx where j_debit='f' $sql_year ");

  if ( $cred == $deb ) { 
    $result ='<span style="color:green;font-size:120%;font-weight:bold;"> OK </span>';}
  else  { 
    $result ='<span style="color:red;font-size:120%;font-weight:bold;"> NON OK </span>';}

  printf ('<li> Total solde Grand Livre : debit %f credit %f %s</li>',$deb,$cred,$result);
  $sql="select jrn_def_id,jrn_def_name from jrn_def";
  $res=ExecSql($cn,$sql);
  $jrn=pg_fetch_all($res);
  foreach ($jrn as $l) {
    $id=$l['jrn_def_id'];
    $name=$l['jrn_def_name'];
    $deb=getDbValue($cn,"select sum (j_montant) from jrnx where j_debit='t' and j_jrn_def=$id $sql_year ");
    $cred=getDbValue($cn,"select sum (j_montant) from jrnx where j_debit='f' and j_jrn_def=$id  $sql_year ");

  if ( $cred == $deb ) { 
    $result ='<span style="color:green;font-size:120%;font-weight:bold;"> OK </span>';}
  else  { 
    $result ='<span style="color:red;font-size:120%;font-weight:bold;"> NON OK </span>';}

  printf ('<li> Journal %s total : debit %f credit %f %s</li>',$name,$deb,$cred,$result);
    
  }

  echo '</div>';
 }


html_page_stop();
?>
