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
require_once("user_form_ach.php");
require_once ("preference.php");
require_once ("user_common.php");
require_once("class_widget.php");
$cn=DbConnect($_SESSION['g_dossier']);

if ( ! isset ($_GET['action']) && ! isset ($_POST["action"]) ) {
  exit;

}
$action=(isset($_GET['action']))?$_GET['action']:$_POST['action'];

// action = new
if ( $action == 'new' ) {
// We request a new form
  // Check privilege
  if ( CheckJrn($_SESSION['g_dossier'],$_SESSION['g_user'],$_GET['p_jrn']) != 2 )    {
       NoAccess();
       exit -1;
  }

	if ( isset($_GET['blank'] )) {
	  // Submit button in the form
	  $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">
                    <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Sauver">';
	  $r=FormAchInput($cn,$_GET['p_jrn'],$User->GetPeriode(),$HTTP_POST_VARS,$submit,false);
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

	  $r=FormAchInput($cn,$_GET['p_jrn'],$User->GetPeriode(),$HTTP_POST_VARS,$submit,false,  $nb_number);
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

	  $r=FormAchInput($cn,$_GET['p_jrn'],$User->GetPeriode(),$HTTP_POST_VARS,$submit,false,  $nb_number);
	  echo '<div class="u_redcontent">';
	  echo $r;
	  echo "<div><h4>On-line calculator</h4>".JS_CALC_LINE."<div>";
	  echo "</div>";
	  return;
	}


	// View the charge and show a submit button to save it 
	if ( isset ($_POST['view_invoice']) and 
	     ! isset ($_POST['save'])) {
	$nb_number=$_POST["nb_item"];
	$submit='<INPUT TYPE="SUBMIT" name="save" value="Confirmer">';
	$submit.='<INPUT TYPE="SUBMIT" name="correct" value="Corriger">';
	if ( form_verify_input ($cn,$_GET['p_jrn'],$User->GetPeriode(),$HTTP_POST_VARS,$nb_number) == true ) {
	  // Should use a read only view instead of FormAch
	  // where we can check
	  $r=FormAchView($cn,$_GET['p_jrn'],$User->GetPeriode(),$HTTP_POST_VARS,$submit,$nb_number);
	} else {
	  // if something goes wrong, correct it
	  $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">
                    <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Sauver">';
	  $r=FormAchInput($cn,$_GET['p_jrn'],$User->GetPeriode(),$submit,$HTTP_POST_VARS,  $nb_number);
	}
	echo '<div class="u_redcontent">';
	echo $r;
	echo "<div><h4>On-line calculator</h4>".JS_CALC_LINE."<div>";
	echo "</div>";
	}
	// Save the charge into database
	if ( isset($_POST['save'] )) {
	  // Get number of  lines
	  $nb_number=$_POST["nb_item"];

	  // submit button in the form
	  $submit='<h2 class="info">Recorded</h2>';
	  $r=RecordSell($cn,$HTTP_POST_VARS,$User,$_GET['p_jrn']);
	  $r=FormAchView($cn,$_GET['p_jrn'],$User->GetPeriode(),$HTTP_POST_VARS,"",$nb_number,false);
	  echo '<div class="u_redcontent">';
	  echo $submit;
	  echo $r;
	  echo "</div>";
	  
	}
	

}
if ( $action == 'voir_jrn' ) {
  // Check privilege
  if ( CheckJrn($_SESSION['g_dossier'],$_SESSION['g_user'],$_GET['p_jrn']) < 1  )    {
       NoAccess();
       exit -1;
  }
?>
<div class="u_redcontent">

<?
echo "<form method= \"post\" action=\"user_jrn.php?action=voir_jrn&p_jrn=$p_jrn\">";

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
   $sql=SQL_LIST_ALL_INVOICE." and jr_tech_per=".$current." and jr_def_id=".$_GET['p_jrn'];

   $step=$_SESSION['g_pagesize'];
   $page=(isset($_GET['offset']))?$_GET['page']:1;
   $offset=(isset($_GET['offset']))?$_GET['offset']:0;

   list ($max_ligne,$list)=ListJrn($cn,$_GET['p_jrn'],$sql,null,$offset);
   $bar=jrn_navigation_bar($offset,$max_ligne,$step,$page);

   echo $bar;
   echo $list;
   echo $bar;

   echo '</div>';
}
if ( $action == 'voir_jrn_non_paye' ) {
  // Check privilege
  if ( CheckJrn($_SESSION['g_dossier'],$_SESSION['g_user'],$_GET['p_jrn']) < 1 )    {
       NoAccess();
       exit -1;
  }

// Show list of unpaid sell
// Date - date of payment - Customer - amount
  $sql=SQL_LIST_UNPAID_INVOICE_DATE_LIMIT." and jr_def_id=".$_GET['p_jrn']  ;
  $offset=(isset($_GET['offset']))?$_GET['offset']:0;
  $step=$_SESSION['g_pagesize'];
  $page=(isset($_GET['offset']))?$_GET['page']:1;

  list ($max_line,$list)=ListJrn($cn,$_GET['p_jrn'],$sql,null,$offset);
  //  $bar=jrn_navigation_bar($offset,$max_ligne,$step,$page);


  $sql=SQL_LIST_UNPAID_INVOICE." and jr_def_id=".$_GET['p_jrn']  ;
  list ($max_line2,$list2)=ListJrn($cn,$_GET['p_jrn'],$sql,null,$offset);

  // Get the max line
  $m=($max_line2>$max_line)?$max_line2:$max_line;
  $bar2=jrn_navigation_bar($offset,$m,$step,$page);

    echo '<div class="u_redcontent">';
    echo '<h2 class="info"> Echeance dépassée </h2>';
    echo $bar2;
    echo $list;


    echo  '<h2 class="info"> Non Payée </h2>';
    echo $list2;
    echo $bar2;
    echo '</div>';
}

//Search
if ( $action == 'search' ) {
  // Check privilege
  if ( CheckJrn($_SESSION['g_dossier'],$_SESSION['g_user'],$_GET['p_jrn']) < 1 )    {
       NoAccess();
       exit -1;
  }

  // PhpSessid
  $sessid=$_REQUEST['PHPSESSID'];
  // Search modules
  $offset=(isset($_GET['offset']))?$_GET['offset']:0;
  $step=$_SESSION['g_pagesize'];
  $page=(isset($_GET['offset']))?$_GET['page']:1;

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
include("user_update.php");
?>
