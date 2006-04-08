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
echo_debug(__FILE__,__LINE__,"include user_action_ven.php");
require_once("user_form_ven.php");
include_once("class_widget.php");
$cn=DbConnect($_SESSION['g_dossier']);
// default action is insert_vente
if ( ! isset ($_GET['action']) && ! isset ($_POST["action"]) ) {
  exit;
   } else {
  $action=(isset($_GET['action']))?$_GET['action']:$_POST['action'];
  $blank=(isset($_GET["blank"]))?1:0;
}

if ( $action == 'insert_vente' ) {
   
    // Add item
        if (isset($_POST["add_item"]) ) {
	  echo_debug(__FILE__,__LINE__,"Add an item");
	  $nb_number=$_POST["nb_item"];
	  $nb_number++;
	  
	  $form=FormVenInput($cn,$_GET['p_jrn'],$User->GetPeriode(),$HTTP_POST_VARS,false,$nb_number);
	  echo '<div class="u_redcontent">';
	  echo   $form;
	  echo '</div>';

	} // add an item

    // We want to see the encoded invoice 
    if ( isset ($_POST["view_invoice"])) {
      $nb_number=$_POST["nb_item"];
      if ( form_verify_input($cn,$_GET['p_jrn'],$User->GetPeriode(),$HTTP_POST_VARS,$nb_number) == true)
	{
	  $form=FormVenteView($cn,$_GET['p_jrn'],$User->GetPeriode(),$HTTP_POST_VARS,$nb_number);
	  // Check failed : invalid date or quantity
	} else {
	    echo_error("Cannot validate ");
	    $form=FormVenInput($cn,$_GET['p_jrn'],$User->GetPeriode(),$HTTP_POST_VARS,false,$nb_number);
	  }
      echo '<div class="u_redcontent">';
      echo         $form;
      echo '</div>';
      
    } 

    // We want a blank form
    if ( $blank==1)
      {
      echo_debug(__FILE__,__LINE__,"Blank form");
      // Show an empty form of invoice
      $form=FormVenInput($cn,$_GET['p_jrn'],$User->GetPeriode(),null,false);
      echo '<div class="u_redcontent">';
      echo $form;
      echo '</div>';
    }

}


    // Save the invoice 
if ( isset($_POST["record_invoice"])) {
  // Check privilege
  if ( CheckJrn($_SESSION['g_dossier'],$User,$_GET['p_jrn']) != 2 )    {
    NoAccess();
    exit -1;
  }

  // echo "RECORD INVOICE";
   RecordInvoice($cn,$HTTP_POST_VARS,$User,$_GET['p_jrn']);
}
if (isset ($_POST['correct_new_invoice'])) {
  // Check privilege
  if ( CheckJrn($_SESSION['g_dossier'],$_SESSION['g_user'],$_GET['p_jrn']) != 2 )    {
    NoAccess();
    exit -1;
  }
  
  $nb=$_POST['nb_item'];
  $form=FormVenInput($cn,$_GET['p_jrn'],$User->GetPeriode(),$HTTP_POST_VARS,false,$nb);
  echo '<div class="u_redcontent">';
  echo $form;
  echo '</div>';
}
// Save and print the invoice
if ( isset($_POST["record_and_print_invoice"])) {
  // Check privilege
  if ( CheckJrn($_SESSION['g_dossier'],$_SESSION['g_user'],$_GET['p_jrn']) != 2 )    {
    NoAccess();
    exit -1;
  }
  
  //  echo "RECORD AND PRINT INVOICE";

  $comment=RecordInvoice($cn,$HTTP_POST_VARS,$User,$_GET['p_jrn']);

  $nb_number=$_POST["nb_item"];
  if ( form_verify_input($cn,$p_jrn,$User->GetPeriode(),$HTTP_POST_VARS,$nb_number)== true) {
    $form=FormVenteView($cn,$p_jrn,$User->GetPeriode(),$HTTP_POST_VARS,$nb_number,'noform',$comment);
  } else {
	    echo_error("Cannot validate ");
	    $form=FormVenInput($cn,$_GET['p_jrn'],$User,$HTTP_POST_VARS,false,$nb_number);
  }
  
  echo '<div class="u_redcontent">';
  echo $form;
  echo "</div>  ";
}


 if ( $action == 'voir_jrn' ) {
   // Check privilege
   if ( CheckJrn($_SESSION['g_dossier'],$_SESSION['g_user'],$_GET['p_jrn']) < 1 )    {
     NoAccess();
     exit -1;
   }
   // Extract the page number we want
   $debut=(isset($_REQUEST['p_page']))?$_REQUEST['p_page']:0;


?>
<div class="u_redcontent">
<?
echo "<form method=\"GET\" action=\"user_jrn.php?action=voir_jrn&p_jrn=$p_jrn\">";

$w=new widget("select");

$periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode order by p_id");
// User is already set User=new cl_user($cn);
$current=(isset($_POST['p_periode']))?$_POST['p_periode']:$User->GetPeriode();
$w->selected=$current;

echo 'Période  '.$w->IOValue("p_periode",$periode_start).$w->Submit('gl_submit','Valider');
?>
</form>
<?
 // Show list of sell
 // Date - date of payment - Customer - amount
   $sql=SQL_LIST_ALL_INVOICE." and jr_tech_per=".$current." and jr_def_id=".$_GET['p_jrn'] ;
   $step=$_SESSION['g_pagesize'];
   $page=(isset($_GET['offset']))?$_GET['page']:1;
   $offset=(isset($_GET['offset']))?$_GET['offset']:0;

   list($max_line,$list)=ListJrn($cn,$_GET['p_jrn'],$sql,null,$offset);
   $bar=jrn_navigation_bar($offset,$max_line,$step,$page);

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
  // Nav. bar 
   $step=$_SESSION['g_pagesize'];
   $page=(isset($_GET['offset']))?$_GET['page']:1;
   $offset=(isset($_GET['offset']))?$_GET['offset']:0;

  $sql=SQL_LIST_UNPAID_INVOICE_DATE_LIMIT." and jr_def_id=".$_GET['p_jrn'] ;
  list($max_line,$list)=ListJrn($cn,$_GET['p_jrn'],$sql,null,$offset);
  $sql=SQL_LIST_UNPAID_INVOICE." and jr_def_id=".$_GET['p_jrn'] ;
  list($max_line2,$list2)=ListJrn($cn,$_GET['p_jrn'],$sql,null,$offset);

  // Get the max line
   $m=($max_line2>$max_line)?$max_line2:$max_line;
   $bar2=jrn_navigation_bar($offset,$m,$step,$page);
   
    echo '<div class="u_redcontent">';
    echo $bar2;
    echo '<h2 class="info"> Echeance dépassée </h2>';
    echo $list;
    echo  '<h2 class="info"> Non Payée </h2>';
    echo $list2;
    echo $bar2;
    echo '</div>';
}

include("user_update.php");
?>
