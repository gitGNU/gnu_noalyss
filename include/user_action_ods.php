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
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/* $Revision$ */
/*! \file
 * \brief included file for the miscellaneous operation ledger 
 */

echo_debug('user_action_ods.php',__LINE__,"include user_action_ods.php");
include_once("user_form_ods.php");
include_once("class_widget.php");
require_once('class_dossier.php');
require_once ('class_pre_operation.php');
require_once ('class_pre_op_ods.php');
require_once ('class_own.php');
require_once ('class_acc_ledger.php');

$gDossier=dossier::id();

$cn=DbConnect($gDossier);
$own=new own($cn);
if ( ! isset ($_GET['action']) && ! isset ($_POST["action"]) ) {
  exit;

}
include_once ("preference.php");
include_once ("user_common.php");


$action=(isset($_GET['action']))?$_GET['action']:$_POST['action'];
//--------------------------------------------------------------------------------
// use a predefined operation
//--------------------------------------------------------------------------------
if ( $action=="use_opd" ) {
  $op=new Pre_op_ods($cn);
  $op->set_od_id($_REQUEST['pre_def']);
  $p_post=$op->compute_array();
  echo_debug(__FILE__.':'.__LINE__.'- ','p_post = ',$p_post);
  $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout poste">'.
	'<INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Confirmer">';

  //  $form=FormVenInput($cn,$_GET['p_jrn'],$User->get_periode(),$p_post,false,$p_post['nb_item']);
  $form=FormODS($cn,$_REQUEST['p_jrn'],$User->get_periode(),$submit,$p_post,false,$p_post['nb_item']);

  echo '<div class="u_redcontent">';
  echo   $form;
  //--------------------
  // predef op.
  echo '<form method="GET">';
  $op=new Pre_operation($cn);
  $op->p_jrn=$_GET['p_jrn'];
  $op->od_direct='f';
  
  $hid=new widget("hidden");
  echo $hid->IOValue("action","use_opd");
  echo dossier::hidden();
  echo $hid->IOValue("p_jrn",$_GET['p_jrn']);
  echo $hid->IOValue("jrn_type","ODS");
  
  if ($op->count() != 0 )
    echo widget::submit_button('use_opd','Utilisez une op.prédéfinie');
  echo $op->show_button();
	
  echo '</form>';
  
  echo '</div>';
  exit();
 }

// action = new
if ( $action == 'new' ) {

  // Check privilege
  if ( CheckJrn($gDossier,$_SESSION['g_user'],$_REQUEST['p_jrn']) != 2 )    {
       NoAccess();
       exit -1;
  }
// We request a new form
  if ( isset($_GET['blank'] )) {
    // Submit button in the form
    $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout poste">
                    <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Confirmer">';
    // add a one-line calculator
    $jrn=new Acc_Ledger($cn,$_GET['p_jrn']);
    $prop=$jrn->get_propertie();
    $line=$prop['jrn_deb_max_line'];
    $r=FormODS($cn,$_REQUEST['p_jrn'],$User->get_periode(),$submit,null,false,$line);
     echo '<div class="u_redcontent">';
    echo $r;
    echo "<div>";
	//--------------------
	// predef op.
	echo '<form method="GET">';
	$op=new Pre_operation($cn);
	$op->p_jrn=$_GET['p_jrn'];
	$op->od_direct='f';

	$hid=new widget("hidden");
	echo $hid->IOValue("action","use_opd");
	echo dossier::hidden();
	echo $hid->IOValue("p_jrn",$_GET['p_jrn']);
	echo $hid->IOValue("jrn_type","ODS");
	
	if ($op->count() != 0 )
	  echo widget::submit_button('use_opd','Utilisez une op.prédéfinie');
	echo $op->show_button();
	
	echo '</form>';
	
	echo "<h4>On-line calculator</h4>".JS_CALC_LINE."<div>";
    echo "</div>";
	

  }

	// Add an item
	if ( isset ($_POST['add_item'])) {
	  // Add a line
	  $nb_number=$_POST["nb_item"];
	  $nb_number++;

	  // submit button in the form
	  $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout Poste">
                    <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Enregistrer">';

	  $r=FormODS($cn,$_POST['p_jrn'],$User->get_periode(),$submit,$_POST,false,  $nb_number);
	  echo '<div class="u_redcontent">';
	  echo $r;
	  echo "<div><h4>On-line calculator</h4>".JS_CALC_LINE."<div>";
	  echo "</div>";
	}
	// Correct it
	if ( isset ($_POST['correct'])) {
	  // Get number of  lines
	  $nb_number=$_POST["nb_item"];

	  // submit button in the form
	  $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout Poste">
                    <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Enregistrer">';

	  $r=FormODS($cn,$_POST['p_jrn'],$User->get_periode(),$submit,$_POST,false,  $nb_number);
	  echo '<div class="u_redcontent">';
	  echo $r;
	  echo "<div><h4>On-line calculator</h4>".JS_CALC_LINE."<div>";
	  echo "</div>";
	}


	// View the charge and show a submit button to save it 
  // TODO: the name 'view_invoice' should be changed to something more self-explaining, like
  // 'submit_od', no? 
	if ( isset ($_POST['view_invoice']) ) {
	  $nb_number=$_POST["nb_item"];
	  $submit='<INPUT TYPE="SUBMIT" name="save" value="Confirmer"  onClick="return verify_ca(\'error\');">';
	  if ( $own->MY_ANALYTIC != "nu" )
		$submit.='<input type="button" value="verifie CA" onClick="verify_ca(\'ok\');">';
	  $submit.='<INPUT TYPE="SUBMIT" name="correct" value="Corriger">';

	  $r=FormODS($cn,$_POST['p_jrn'],$User->get_periode(),$submit,$_POST,true,$nb_number);

	// if something goes wrong, correct it
	  if ( $r == null ) {
	    // submit button in the form
	    $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout Poste">
                    <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Enregistrer">';
	    
	    $r=FormODS($cn,$_POST['p_jrn'],$User->get_periode(),$submit,$_POST,false,  $nb_number);
	  }else {
	  $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout Poste">
                    <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Enregistrer">';


	  }
	  echo '<div class="u_redcontent">';
	  echo $r;
	  echo "<div><h4>On-line calculator</h4>".JS_CALC_LINE."<div>";
	  echo "</div>";
	}
	// Save the change into database
	if ( isset($_POST['save'] )) {
	  $r=RecordODS($cn,$_POST,$User,$_POST['p_jrn']);
	  // Get number of  lines
	  $nb_number=$_POST["nb_item"];
	  if ( $r != null ) {
	    // submit button in the form
	    $submit='<h2 class="info">Recorded'.$r.'</h2>';
	    
	    $r.=FormODS($cn,$_POST['p_jrn'],$User->get_periode(),$submit,$_POST,true,  $nb_number,true);
	  }else {
	    // CA incorrecte
	    $submit='<INPUT TYPE="SUBMIT" name="save" value="Confirmer"  onClick="return verify_ca(\'error\');">';
		if ( $own->MY_ANALYTIC != "nu" )
		  $submit.='<input type="button" value="verifie CA" onClick="verify_ca(\'ok\');">';
	    $submit.='<INPUT TYPE="SUBMIT" name="correct" value="Corriger">';
	    
	    $r=FormODS($cn,$_POST['p_jrn'],$User->get_periode(),$submit,$_POST,true,$nb_number);

	  }
	  echo '<div class="u_redcontent">';
	  echo $r;
	  echo "</div>";
	  
	}
	

}
if ( $action == 'voir_jrn' ) {
  // Check privilege
  if ( CheckJrn($gDossier,$_SESSION['g_user'],$_REQUEST['p_jrn']) < 1 )    {
       NoAccess();
       exit -1;
  }

 // Show list of cells
  echo_debug ("user_action_ods.php");
 // Date - date of payment - Customer - amount
?>
<div class="u_redcontent">

<form method= "get" action="user_jrn.php">

<?php
   echo dossier::hidden();    
$hid=new widget("hidden");

$hid->name="p_jrn";
$hid->value=$p_jrn;
echo $hid->IOValue();

$hid->name="action";
$hid->value="voir_jrn";
echo $hid->IOValue();


$hid->name="jrn_type";
$hid->value=$jrn_type;
echo $hid->IOValue();



$w=new widget("select");
// filter on the current year
$filter_year=" where p_exercice='".$User->get_exercice()."'";

$periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by p_start,p_end",1);
$User=new cl_user($cn);
$current=(isset($_GET['p_periode']))?$_GET['p_periode']:$User->get_periode();
$w->selected=$current;

echo 'Période  '.$w->IOValue("p_periode",$periode_start).$w->Submit('gl_submit','Valider');
?>
</form>
<?php  
    if ( $current == -1) {
      $cond=" and jr_tech_per in (select p_id from parm_periode where p_exercice='".$User->get_exercice()."')";
    } else {
      $cond=" and jr_tech_per=".$current;
    }
 
	$sql=SQL_LIST_ALL_INVOICE.$cond." and jr_def_id=".$_GET['p_jrn'] ;
	$step=$_SESSION['g_pagesize'];
	$page=(isset($_GET['offset']))?$_GET['page']:1;
	$offset=(isset($_GET['offset']))?$_GET['offset']:0;
	list ($max_line,$list)=ListJrn($cn,$_GET['p_jrn'],$sql,null,$offset);
	$bar=jrn_navigation_bar($offset,$max_line,$step,$page);
	echo $bar;
	echo $list;
	echo $bar;
	echo '</div>';
}

//Search
if ( $action == 'search' ) {
  // Check privilege
  if ( CheckJrn($gDossier,$_SESSION['g_user'],$_GET['p_jrn']) <1 )    {
       NoAccess();
       exit -1;
  }

  // PhpSessid
  $sessid=(isset ($_POST['PHPSESSID']))?$_POST['PHPSESSID']:$_GET['PHPSESSID'];

// display a search box
  $search_box=u_ShowMenuRecherche($cn,$_GET['p_jrn'],$sessid,$_POST);
  echo '<DIV class="u_redcontent">';
  echo $search_box; 
  // if nofirst is set then show result
  if ( isset ($_GET['nofirst'] ) )     {
    list ($max_line,$a)=ListJrn($cn,$_GET['p_jrn'],"",$_POST);
    echo $a;
  }
  echo '</DIV>'; 
}
require_once("user_update.php");
?>
