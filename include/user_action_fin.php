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
 * \brief included file for the financial ledger 
 */

echo_debug('user_action_fin.php',__LINE__,"include user_action_fin.php");
// include_once("form_input.php");
require_once("user_form_fin.php");
include_once("class_widget.php");
require_once("class_parm_code.php");
require_once("class_jrn.php");
require_once ('class_pre_op_fin.php');

$cn=DbConnect($gDossier);

if ( ! isset ($_GET['action']) && ! isset ($_POST["action"]) ) {  
  return;
}
include_once ("preference.php");
include_once ("user_common.php");

$action=(isset($_GET['action']))?$_GET['action']:$_POST['action'];
//--------------------------------------------------------------------------------
// use a predefined operation
//--------------------------------------------------------------------------------
if ( $action=="use_opd" ) {
  $op=new Pre_op_fin($cn);
  $op->set_od_id($_REQUEST['pre_def']);
  $p_post=$op->compute_array();
  echo_debug(__FILE__.':'.__LINE__.'- ','p_post = ',$p_post);
  // submit button in the form
  $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">'.
	'<INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Enregistrer">';

  $form=FormFin($cn,$_GET['p_jrn'],$User->GetPeriode(),$submit,$p_post,false,$p_post['nb_item']);
  echo '<div class="u_redcontent">';
  echo   $form;
  echo '</div>';
  exit();
 }

//-----------------------------------------------------
// action = new
//-----------------------------------------------------
if ( $action == 'new' ) {
  // Check privilege
  if ( CheckJrn($gDossier,$_SESSION['g_user'],$_GET['p_jrn']) != 2 )    {
       NoAccess();
       exit -1;
  }

// We request a new form
	if ( isset($_GET['blank'] )) {
	  // Submit button in the form
	  $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">
                    <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Enregistrer">';
	  // add a one-line calculator
	  $p_jrn=$_GET['p_jrn'];
	  $jrn=new jrn($cn,  $p_jrn);

	  $r=FormFin($cn,$p_jrn,$User->GetPeriode(),$submit,null,false,$jrn->GetDefLine());
	  echo '<div class="u_redcontent">';
	  echo $r;
	  echo "<div>";
	     //--------------------
	  // predef op.
	  echo '<form method="GET">';
	  $op=new Pre_operation($cn);
	  $op->p_jrn=$_GET['p_jrn'];
	  $hid=new widget("hidden");
	  echo $hid->IOValue("action","use_opd");
	  echo dossier::hidden();
	  echo $hid->IOValue("p_jrn",$_GET['p_jrn']);
	  echo $hid->IOValue("jrn_type","FIN");
	  
	  echo widget::submit_button('use_opd','Utilisez une op.prédéfinie');
	  echo $op->show_button();
   
	  echo '</form>';

	  echo "<h4>On-line calculator</h4>".JS_CALC_LINE."</div>";
	
	  echo "</div>";


	}

	// Add an item
	if ( isset ($_POST['add_item'])) {
	  // Add a line
	  $nb_number=$_POST["nb_item"];
	  $nb_number++;

	  // submit button in the form
	  $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">
                    <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Enregistrer">';

	  $r=FormFin($cn,$_GET['p_jrn'],$User->GetPeriode(),$submit,$_POST,false,  $nb_number);
	  echo '<div class="u_redcontent">';
	  echo $r;
	  echo "<div><h4>On-line calculator</h4>".JS_CALC_LINE."</div>";

	  echo "</div>";
	}
	// Correct it
	if ( isset ($_POST['correct'])) {
	  // Get number of  lines
	  $nb_number=$_POST["nb_item"];

	  // submit button in the form
	  $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">
                    <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Enregistrer">';

	  $r=FormFin($cn,$_GET['p_jrn'],$User->GetPeriode(),$submit,$_POST,false,  $nb_number);
	  echo '<div class="u_redcontent">';
	  echo $r;
	  echo "<div><h4>On-line calculator</h4>".JS_CALC_LINE."</div>";

	  echo "</div>";
	}


	// View the charge and show a submit button to save it 
	if ( isset ($_POST['view_invoice']) ) {
	$nb_number=$_POST["nb_item"];
	$r=form_verify_input($cn,$_GET['p_jrn'],$User->GetPeriode(),$_POST,$nb_number);
	// if something goes wrong correct it
	if ( $r == null ) 
	  {
	    // submit button in the form
	    $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">
                    <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Enregistrer">';
	    
	    $r=FormFin($cn,$_GET['p_jrn'],$User->GetPeriode(),$submit,$_POST,false,  $nb_number);
	  }
	else 
	  {
	    $submit='<INPUT TYPE="SUBMIT" name="save" value="Confirmer">';
	    $submit.='<INPUT TYPE="SUBMIT" name="correct" value="Corriger">';
	    
	    $r=FormFin($cn,$_GET['p_jrn'],$User->GetPeriode(),$submit,$_POST,true,$nb_number);
	  }

	echo '<div class="u_redcontent">';
	echo $r;
	echo "<div><h4>On-line calculator</h4>".JS_CALC_LINE."</div>";
	echo "</div>";
	}
	// Save the charge into database
	if ( isset($_POST['save'] )) {
	  $r=RecordFin($cn,$_POST,$User,$_GET['p_jrn']);
	  // Get number of  lines
	  $nb_number=$_POST["nb_item"];

	  // submit button in the form
	  $submit='<h2 class="info">Recorded '.$r.'</h2>';

	  $r.=FormFin($cn,$_GET['p_jrn'],$User->GetPeriode(),$submit,$_POST,true,  $nb_number,true);
	  echo '<div class="u_redcontent">';
	  echo $r;
	  echo "</div>";
	  
	}
	

}
//-----------------------------------------------------
// see jrn
//-----------------------------------------------------
if ( $action == 'voir_jrn' ) {
  // Check privilege
  if ( CheckJrn($gDossier,$_SESSION['g_user'],$_GET['p_jrn']) < 1 )    {
       NoAccess();
       exit -1;
  }
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
$filter_year=" where p_exercice='".$User->getExercice()."'";

$periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by  p_start,p_end",1);
$User=new cl_user($cn);
$current=(isset($_GET['p_periode']))?$_GET['p_periode']:$User->GetPeriode();
$w->selected=$current;

echo 'Période  '.$w->IOValue("p_periode",$periode_start).$w->Submit('gl_submit','Valider');
?>
</form>
<?php  

 // Show list of sell
  echo_debug ("user_action_jrn.php");
 if ( $current == -1) {
   $cond=" and jr_tech_per in (select p_id from parm_periode where p_exercice='".$User->getExercice()."')";
 } else {
   $cond=" and jr_tech_per=".$current;
 }

 // Date - date of payment - Customer - amount
   $sql=SQL_LIST_ALL_INVOICE.$cond." and jr_def_id=".$_GET['p_jrn'];

  // Nav. bar 
   $step=$_SESSION['g_pagesize'];
   $page=(isset($_GET['offset']))?$_GET['page']:1;
   $offset=(isset($_GET['offset']))?$_GET['offset']:0;
   // SQL
   list($max_line,$list)=ListJrn($cn,$_GET['p_jrn'],$sql,null,$offset);

   $bar=jrn_navigation_bar($offset,$max_line,$step,$page);
	
	echo $bar;
	echo $list;
	echo $bar;
   echo '</div>';
}
//-----------------------------------------------------
// balance
//-----------------------------------------------------
if ( $action == 'solde' ) {
  require_once("poste.php");
  // find the bank account
 // NOTE : those values are in a table because
 // they are _national_ parameters
  $banque=new parm_code($cn,'BANQUE');
  $caisse=new parm_code($cn,'CAISSE');
  $vir_interne=new parm_code($cn,'VIREMENT_INTERNE');
  $accountSql="select distinct pcm_val::text,pcm_lib from 
            tmp_pcmn 
            where pcm_val like '".$banque->p_value."%' or pcm_val like '".$vir_interne->p_value."%' 
            or pcm_val like '".$caisse->p_value."%'
            order by pcm_val::text";
  $ResAccount=ExecSql($cn,$accountSql);
  echo '<div class="u_redcontent">';
  echo "<table>";
  // for each account
  for ( $i = 0; $i < pg_NumRows($ResAccount);$i++) {
    // get the saldo
    $l=pg_fetch_array($ResAccount,$i);
    $m=GetSolde($cn,$l['pcm_val']);
    // print the result if the saldo is not equal to 0
    if ( $m != 0.0 ) {
      echo "<tr>";
      echo "<TD>".
	$l['pcm_val'].
	"</TD>".
	"<TD>".
	$l['pcm_lib'].
	"</TD>"."<TD>".
	$m.
	"</TD>"."</TR>";
    }
  }// for
  echo "</table>";
  echo "</div>";
 }
//-----------------------------------------------------
include("user_update.php");


?>
