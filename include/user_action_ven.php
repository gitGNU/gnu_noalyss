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
include_once("form_input.php");
include_once("class_widget.php");
$cn=DbConnect($g_dossier);
// default action is insert_vente
if ( ! isset ($_GET['action']) && ! isset ($_POST["action"]) ) {
  //  echo u_ShowMenuJrn($cn,$jrn_type);
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

      $form=FormVente($cn,$g_jrn,$g_user,$HTTP_POST_VARS,false,$nb_number);
      echo '<div class="u_redcontent">';
      echo     "here".    $form;
      echo '</div>';
      
    } 

    // We want to see the encoded invoice 
    if ( isset ($_POST["view_invoice"])) {
      $nb_number=$_POST["nb_item"];
      $form=FormVenteView($cn,$g_jrn,$g_user,$HTTP_POST_VARS,$nb_number);
	  // Check failed : invalid date or quantity
	  if ( $form== null) {
		  echo_error("Cannot validate ");
		  $form=FormVente($cn,$g_jrn,$g_user,$HTTP_POST_VARS,false,$nb_number);
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
      $form=FormVente($cn,$g_jrn,$g_user,null,false);
      echo '<div class="u_redcontent">';
      echo $form;
      echo '</div>';
    }

}


    // Save the invoice 
if ( isset($_POST["record_invoice"])) {
  // Check privilege
  if ( CheckJrn($g_dossier,$g_user,$g_jrn) != 2 )    {
    NoAccess();
    exit -1;
  }

  // echo "RECORD INVOICE";
   RecordInvoice($cn,$HTTP_POST_VARS,$g_user,$g_jrn);
}
if (isset ($_POST['correct_new_invoice'])) {
  // Check privilege
  if ( CheckJrn($g_dossier,$g_user,$g_jrn) != 2 )    {
    NoAccess();
    exit -1;
  }
  
  $nb=$_POST['nb_item'];
  $form=FormVente($cn,$g_jrn,$g_user,$HTTP_POST_VARS,false,$nb);
  echo '<div class="u_redcontent">';
  echo $form;
  echo '</div>';
}
// Save and print the invoice
if ( isset($_POST["record_and_print_invoice"])) {
  // Check privilege
  if ( CheckJrn($g_dossier,$g_user,$g_jrn) != 2 )    {
    NoAccess();
    exit -1;
  }
  
  //  echo "RECORD AND PRINT INVOICE";
  $comment=RecordInvoice($cn,$HTTP_POST_VARS,$g_user,$g_jrn);
      $nb_number=$_POST["nb_item"];
      $form=FormVenteView($cn,$g_jrn,$g_user,$HTTP_POST_VARS,$nb_number,'noform',$comment);

 	echo '<div class="u_redcontent">';
 	echo $form;
 	echo "</div>  ";
}


 if ( $action == 'voir_jrn' ) {
   // Check privilege
   if ( CheckJrn($g_dossier,$g_user,$g_jrn) < 1 )    {
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

echo 'P�riode  '.$w->IOValue("p_periode",$periode_start).$w->Submit('gl_submit','Valider');
?>
</form>
<?
 // Show list of sell
 // Date - date of payment - Customer - amount
   $sql=SQL_LIST_ALL_INVOICE." and jr_tech_per=".$current." and jr_def_id=$g_jrn ";
   $list=ListJrn($cn,$g_jrn,$sql);
   echo $list;
   echo '</div>';
}
if ( $action == 'voir_jrn_non_paye' ) {
   // Check privilege
   if ( CheckJrn($g_dossier,$g_user,$g_jrn) < 1 )    {
     NoAccess();
     exit -1;
   }

// Show list of unpaid sell
// Date - date of payment - Customer - amount
  $sql=SQL_LIST_UNPAID_INVOICE_DATE_LIMIT." and jr_def_id=$g_jrn ";
  $list=ListJrn($cn,$g_jrn,$sql);
  $sql=SQL_LIST_UNPAID_INVOICE." and jr_def_id=$g_jrn ";
  $list2=ListJrn($cn,$g_jrn,$sql);
    echo '<div class="u_redcontent">';
    echo '<h2 class="info"> Echeance d�pass�e </h2>';
    echo $list;
    echo  '<h2 class="info"> Non Pay�e </h2>';
    echo $list2;
    echo '</div>';
}
// if ( $action == 'impress' ) {
// // Print list of sell or print the current invoice
// }

//Search
// if ( $action == 'search' ) {
//    // Check privilege
//    if ( CheckJrn($g_dossier,$g_user,$g_jrn) < 1 )    {
//      NoAccess();
//      exit -1;
//    }

//   // PhpSessid
//   $sessid=(isset ($_POST['PHPSESSID']))?$_POST['PHPSESSID']:$_GET['PHPSESSID'];


// // display a search box
//   $search_box=u_ShowMenuRecherche($cn,$g_jrn,$sessid,$HTTP_POST_VARS);
//   echo '<DIV class="u_redcontent">';
//   echo $search_box; 
//   // if nofirst is set then show result
//   if ( isset ($_GET['nofirst'] ) ) {
//     $a=ListJrn($cn,$g_jrn,"",$HTTP_POST_VARS);
//     echo $a;
//   }
//   echo '</DIV>'; 
// }
include("user_update.php");
?>
