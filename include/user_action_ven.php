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
 * \brief Manage the ledger of type VEN
 */
echo_debug('user_action_ven.php',__LINE__,"include user_action_ven.php");
require_once("user_form_ven.php");
include_once("class_widget.php");
require_once("class_acc_ledger.php");
require_once('class_dossier.php');
$gDossier=dossier::id();
if ( CheckJrn($gDossier,$_SESSION['g_user'],$_GET['p_jrn']) != 2 )    {
  NoAccess();
  exit -1;
 }

$cn=DbConnect($gDossier);

// default action is insert_vente
if ( ! isset ($_REQUEST['action'])) {
  exit;
   } else {
  $action=$_REQUEST['action'];
  $blank=(isset($_GET["blank"]))?1:0;
}
//--------------------------------------------------------------------------------
// use a predefined operation
//--------------------------------------------------------------------------------
if ( $action=="use_opd" ) {
  $op=new Pre_op_ven($cn);
  $op->set_od_id($_REQUEST['pre_def']);
   $op->od_direct='f';

  $p_post=$op->compute_array();
  echo_debug(__FILE__.':'.__LINE__.'- ','p_post = ',$p_post);
  $form=FormVenInput($cn,$_GET['p_jrn'],$User->get_periode(),$p_post,false,$p_post['nb_item']);
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
  echo $hid->IOValue("jrn_type","VEN");
  
  if ($op->count() != 0 )
    echo widget::submit_button('use_opd','Utilisez une op.pr�d�finie');
  echo $op->show_button();
  
  echo '</form>';
  echo '</div>';

  exit();
 }
if ( $action == 'insert_vente' ) {
   
  // Add item
  if (isset($_POST["add_item"]) ) {
    echo_debug('user_action_ven.php',__LINE__,"Add an item");
    $nb_number=$_POST["nb_item"];
    $nb_number++;
    
    $form=FormVenInput($cn,$_GET['p_jrn'],$User->get_periode(),$_POST,false,$nb_number);
    echo '<div class="u_redcontent">';
    echo   $form;
    echo '</div>';

  } // add an item
  
    // We want to see the encoded invoice 
  if ( isset ($_POST["view_invoice"])) {
    $nb_number=$_POST["nb_item"];
      if ( form_verify_input($cn,$_GET['p_jrn'],$User->get_periode(),$_POST,$nb_number) == true)
	{
	  $form=FormVenteView($cn,$_GET['p_jrn'],$User->get_periode(),$_POST,$nb_number);
	  // Check failed : invalid date or quantity
	} else {
	echo_debug(__FILE__.':'.__LINE__," Impossible d'accepter le formulaire");
	$form=FormVenInput($cn,$_GET['p_jrn'],$User->get_periode(),$_POST,false,$nb_number);
      }
      echo '<div class="u_redcontent">';
      echo         $form;
      echo '</div>';
      
    } 

    // We want a blank form
    if ( $blank==1)
      {
       $jrn=new Acc_Ledger($cn,  $_GET['p_jrn']);
	   echo_debug('user_action_ven.php',__LINE__,"Blank form");
	   // Show an empty form of invoice
	   $form=FormVenInput($cn,$_GET['p_jrn'],$User->get_periode(),null,false,$jrn->GetDefLine());
	   echo '<div class="u_redcontent">';
	   echo $form;
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
	   echo $hid->IOValue("jrn_type","VEN");

	   if ($op->count() != 0 )
		 echo widget::submit_button('use_opd','Utilisez une op.pr�d�finie');
	   echo $op->show_button();

	   echo '</form>';
	   echo '</div>';
    }

}


    // Save the invoice 
if ( isset($_POST["record_invoice"])) {
  // Check privilege
  if ( CheckJrn($gDossier,$User,$_GET['p_jrn']) != 2 )    {
    NoAccess();
    exit -1;
  }

  // echo "RECORD INVOICE";
   RecordInvoice($cn,$_POST,$User,$_GET['p_jrn']);
}
if (isset ($_POST['correct_new_invoice'])) {
  // Check privilege
  if ( CheckJrn($gDossier,$_SESSION['g_user'],$_GET['p_jrn']) != 2 )    {
    NoAccess();
    exit -1;
  }
  
  $nb=$_POST['nb_item'];
  $form=FormVenInput($cn,$_GET['p_jrn'],$User->get_periode(),$_POST,false,$nb);
  echo '<div class="u_redcontent">';
  echo $form;
  echo '</div>';
}
// Save and print the invoice
if ( isset($_POST["record_and_print_invoice"])) {
  // Check privilege
  if ( CheckJrn($gDossier,$_SESSION['g_user'],$_GET['p_jrn']) != 2 )    {
    NoAccess();
    exit -1;
  }
  
  $nb_number=$_POST["nb_item"];
  echo_debug(__FILE__.':'.__LINE__.'- record_and_print_invoice');
  if ( form_verify_input($cn,$p_jrn,$User->get_periode(),$_POST,$nb_number)== true) {
    $comment=RecordInvoice($cn,$_POST,$User,$_GET['p_jrn']);
    
    $form=FormVenteView($cn,$p_jrn,$User->get_periode(),$_POST,$nb_number,'noform',$comment);
  } else {
    
    echo("A cause d'erreur la facture ne peut-&egrave;tre valid&eacute; ");
    $form=FormVenteView($cn,$_GET['p_jrn'],$User->get_periode(),$_POST,$nb_number,"form");
  }
  
  echo '<div class="u_redcontent">';
  echo $form;
  echo "</div>  ";
}


 if ( $action == 'voir_jrn' ) {
   // Check privilege
   if ( CheckJrn($gDossier,$_SESSION['g_user'],$_GET['p_jrn']) < 1 )    {
     NoAccess();
     exit -1;
   }
   // Extract the page number we want
   $debut=(isset($_REQUEST['p_page']))?$_REQUEST['p_page']:0;


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

$periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by  p_start,p_end",1);
// User is already set User=new cl_user($cn);
$current=(isset($_GET['p_periode']))?$_GET['p_periode']:$User->get_periode();
$w->selected=$current;

echo 'P�riode  '.$w->IOValue("p_periode",$periode_start).$w->Submit('gl_submit','Valider');
?>
</form>
<?php  
    // Ask to update payment
    if ( isset ( $_POST['paid'])) 
      {
	// reset all the paid flag because the checkbox is post only
	// when checked
	foreach ($_POST as $name=>$paid) 
	  {
	    list($ad) = sscanf($name,"set_jr_id%d");
 	    if ( $ad == null ) continue;
 	    $sql="update jrn set jr_rapt='' where jr_id=$ad";
 	    $Res=ExecSql($cn,$sql);

	  }
	// set a paid flag for the checked box
	foreach ($_POST as $name=>$paid) 
	  {
	    list ($id) = sscanf ($name,"rd_paid%d");

	    if ( $id == null ) continue;

	    $paid=($paid=='on')?'paid':'';
	    $sql="update jrn set jr_rapt='$paid' where jr_id=$id";
	    $Res=ExecSql($cn,$sql);
	  }

      }
 // Show list of sell
 // Date - date of payment - Customer - amount
 if ( $current == -1) {
   $cond=" and jr_tech_per in (select p_id from parm_periode where p_exercice='".$User->get_exercice()."')";
 } else {
   $cond=" and jr_tech_per=".$current;
 }

 $sql=SQL_LIST_ALL_INVOICE.$cond." and jr_def_id=".$_GET['p_jrn'] ;
 $step=$_SESSION['g_pagesize'];
 $page=(isset($_GET['offset']))?$_GET['page']:1;
 $offset=(isset($_GET['offset']))?$_GET['offset']:0;

   list($max_line,$list)=ListJrn($cn,$_GET['p_jrn'],$sql,null,$offset,1);
   $bar=jrn_navigation_bar($offset,$max_line,$step,$page);

   echo "<hr>$bar";
   echo '<form method="POST">';
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


   echo $list;
   if ( $max_line !=0 )
     echo $hid->Submit('paid','Mise � jour paiement');
   echo '</FORM>';
   echo "$bar <hr>";

   echo '</div>';
}
if ( $action == 'voir_jrn_non_paye' ) {
   // Check privilege
   if ( CheckJrn($gDossier,$_SESSION['g_user'],$_GET['p_jrn']) < 1 )    {
     NoAccess();
     exit -1;
   }
    // Ask to update payment
    if ( isset ( $_POST['paid'])) 
      {
	// reset all the paid flag because the checkbox is post only
	// when checked
	foreach ($_POST as $name=>$paid) 
	  {
	    list($ad) = sscanf($name,"set_jr_id%d");
 	    if ( $ad == null ) continue;
 	    $sql="update jrn set jr_rapt='' where jr_id=$ad";
 	    $Res=ExecSql($cn,$sql);

	  }
	// set a paid flag for the checked box
	foreach ($_POST as $name=>$paid) 
	  {
	    list ($id) = sscanf ($name,"rd_paid%d");

	    if ( $id == null ) continue;
	    $paid=($paid=='on')?'paid':'';
	    $sql="update jrn set jr_rapt='$paid' where jr_id=$id";
	    $Res=ExecSql($cn,$sql);
	  }

      }

// Show list of unpaid sell
// Date - date of payment - Customer - amount
  // Nav. bar 
   $step=$_SESSION['g_pagesize'];
   $page=(isset($_GET['offset']))?$_GET['page']:1;
   $offset=(isset($_GET['offset']))?$_GET['offset']:0;

  $sql=SQL_LIST_UNPAID_INVOICE_DATE_LIMIT." and jr_def_id=".$_GET['p_jrn'] ;
  list($max_line,$list)=ListJrn($cn,$_GET['p_jrn'],$sql,null,$offset,1);
  $sql=SQL_LIST_UNPAID_INVOICE." and jr_def_id=".$_GET['p_jrn'] ;
  list($max_line2,$list2)=ListJrn($cn,$_GET['p_jrn'],$sql,null,$offset,1);

  // Get the max line
   $m=($max_line2>$max_line)?$max_line2:$max_line;
   $bar2=jrn_navigation_bar($offset,$m,$step,$page);
   
    echo '<div class="u_redcontent">';
    echo '<FORM METHOD="POST">';
	echo dossier::hidden();  
    echo $bar2;
    echo '<h2 class="info"> Echeance d�pass�e </h2>';
    echo $list;
    echo  '<h2 class="info"> Non Pay�e </h2>';
    echo $list2;
    echo $bar2;
    // Add hidden parameter
    $hid=new widget("hidden");

    $hid->name="p_jrn";
    $hid->value=$p_jrn;
    echo $hid->IOValue();

    $hid->name="action";
    $hid->value="voir_jrn_non_paye";
    echo $hid->IOValue();


    $hid->name="jrn_type";
    $hid->value=$jrn_type;
    echo $hid->IOValue();



    if ( $m != 0 )
      echo $hid->Submit('paid','Mise � jour paiement');

    echo '</FORM>';
    echo '</div>';
}

require_once("user_update.php");
?>
