<?
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
echo_debug(__FILE__,__LINE__,"include user_action_ach.php");
include_once("form_input.php");
include_once ("preference.php");
include_once ("user_common.php");
include_once("class_widget.php");
$cn=DbConnect($_SESSION['g_dossier']);

// phpinfo();
if ( ! isset ($_GET['action']) && ! isset ($_POST["action"]) ) {
  //  echo u_ShowMenuJrn($cn,$jrn_type);
  exit;

}
$action=(isset($_GET['action']))?$_GET['action']:$_POST['action'];

// action = new
if ( $action == 'new' ) {
// We request a new form
  // Check privilege
  if ( CheckJrn($_SESSION['g_dossier'],$_SESSION['g_user'],$_SESSION['g_jrn']) != 2 )    {
       NoAccess();
       exit -1;
  }

	if ( isset($_GET['blank'] )) {
	  // Submit button in the form
	  $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">
                    <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Sauver">';
	  // add a one-line calculator


	  $r=FormAch($cn,$_SESSION['g_jrn'],$_SESSION['g_user'],$submit,null,false);
	  echo '<div class="u_redcontent">';
	  echo $r;
	  echo "<div><h4>On-line calculator</h4>".JS_CALC_LINE."<div>";
	  echo "</div>";


	}

	// Add an item
	if ( isset ($_POST['add_item'])) {
	  // Add a line
	  $nb_number=$_POST["nb_item"];
	  $nb_number++;

	  // submit button in the form
	  $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">
                    <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Sauver">';

	  $r=FormAch($cn,$_SESSION['g_jrn'],$_SESSION['g_user'],$submit,$HTTP_POST_VARS,false,  $nb_number);
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
	  $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">
                    <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Sauver">';

	  $r=FormAch($cn,$_SESSION['g_jrn'],$_SESSION['g_user'],$submit,$HTTP_POST_VARS,false,  $nb_number);
	  echo '<div class="u_redcontent">';
	  echo $r;
	  echo "<div><h4>On-line calculator</h4>".JS_CALC_LINE."<div>";
	  echo "</div>";
	}


	// View the charge and show a submit button to save it 
	if ( isset ($_POST['view_invoice']) ) {
	$nb_number=$_POST["nb_item"];
	$submit='<INPUT TYPE="SUBMIT" name="save" value="Confirmer">';
	$submit.='<INPUT TYPE="SUBMIT" name="correct" value="Corriger">';

	$r=FormAch($cn,$_SESSION['g_jrn'],$_SESSION['g_user'],$submit,$HTTP_POST_VARS,true,$nb_number);

	// if something goes wrong, correct it
	if ( $r == null ) {
	  $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">
                    <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Sauver">';
	  $r=FormAch($cn,$_SESSION['g_jrn'],$_SESSION['g_user'],$submit,$HTTP_POST_VARS,false,  $nb_number);
	}
	echo '<div class="u_redcontent">';
	echo $r;
	echo "<div><h4>On-line calculator</h4>".JS_CALC_LINE."<div>";
	echo "</div>";
	}
	// Save the charge into database
	if ( isset($_POST['save'] )) {
	  $r=RecordAchat($cn,$HTTP_POST_VARS,$_SESSION['g_user'],$_SESSION['g_jrn']);
	  // Get number of  lines
	  $nb_number=$_POST["nb_item"];

	  // submit button in the form
	  $submit='<h2 class="info">Recorded</h2>';

	  $r.=FormAch($cn,$_SESSION['g_jrn'],$_SESSION['g_user'],$submit,$HTTP_POST_VARS,true,  $nb_number,true);
	  echo '<div class="u_redcontent">';
	  echo $r;
	  echo "</div>";
	  
	}
	

}
if ( $action == 'voir_jrn' ) {
  // Check privilege
  if ( CheckJrn($_SESSION['g_dossier'],$_SESSION['g_user'],$_SESSION['g_jrn']) < 1  )    {
       NoAccess();
       exit -1;
  }
?>
<div class="u_redcontent">
<form method="post" action="user_jrn.php?action=voir_jrn">
<?
$w=new widget("select");

$periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode order by p_id");
$User=new cl_user($cn);
$current=(isset($_POST['p_periode']))?$_POST['p_periode']:$User->GetPeriode();
$w->selected=$current;

echo 'Période  '.$w->IOValue("p_periode",$periode_start).$w->Submit('gl_submit','Valider');
?>
</form>
<?

 // Show list of sell
  echo_debug ("user_action_ach.php");
 // Date - date of payment - Customer - amount
   $sql=SQL_LIST_ALL_INVOICE." and jr_tech_per=".$current." and jr_def_id=".$_SESSION['g_jrn'];
   $list=ListJrn($cn,$_SESSION['g_jrn'],$sql);

   echo $list;
   echo '</div>';
}
if ( $action == 'voir_jrn_non_paye' ) {
  // Check privilege
  if ( CheckJrn($_SESSION['g_dossier'],$_SESSION['g_user'],$_SESSION['g_jrn']) < 1 )    {
       NoAccess();
       exit -1;
  }

// Show list of unpaid sell
// Date - date of payment - Customer - amount
  $sql=SQL_LIST_UNPAID_INVOICE_DATE_LIMIT." and jr_def_id=".$_SESSION['g_jrn']  ;
  $list=ListJrn($cn,$_SESSION['g_jrn'],$sql);
  $sql=SQL_LIST_UNPAID_INVOICE." and jr_def_id=".$_SESSION['g_jrn']  ;
  $list2=ListJrn($cn,$_SESSION['g_jrn'],$sql);
    echo '<div class="u_redcontent">';
    echo '<h2 class="info"> Echeance dépassée </h2>';
    echo $list;
    echo  '<h2 class="info"> Non Payée </h2>';
    echo $list2;
    echo '</div>';
}

//Search
if ( $action == 'search' ) {
  // Check privilege
  if ( CheckJrn($_SESSION['g_dossier'],$_SESSION['g_user'],$_SESSION['g_jrn']) < 1 )    {
       NoAccess();
       exit -1;
  }

  // PhpSessid
  $sessid=(isset ($_POST['PHPSESSID']))?$_POST['PHPSESSID']:$_GET['PHPSESSID'];

// display a search box
  $search_box=u_ShowMenuRecherche($cn,$_SESSION['g_jrn'],$sessid,$HTTP_POST_VARS);
  echo '<DIV class="u_redcontent">';
  echo $search_box; 
  // if nofirst is set then show result
  if ( isset ($_GET['nofirst'] ) )     {
    $a=ListJrn($cn,$_SESSION['g_jrn'],"",$HTTP_POST_VARS);
    echo $a;
  }
  echo '</DIV>'; 
}
include("user_update.php");
?>
