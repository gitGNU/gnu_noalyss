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
echo_debug("include user_action_ven.php");
include_once("form_input.php");
if ( ! isset ($_GET['action']) && ! isset ($_POST["action"]) ) {
    return;
}
$dossier=sprintf("dossier%d",$g_dossier);
$cn=DbConnect($dossier);
$action=(isset($_GET['action']))?$_GET['action']:$_POST['action'];
if ( $action == 'insert_vente' ) {
// Check privilege
    if ( CheckJrn($g_dossier,$g_user,$g_jrn) != 2 )    {
       NoAccess();
       exit -1;
    }
   
    // Add item
        if (isset($_POST["add_item"]) ) {
      echo_debug("Add an item");
      $nb_number=$_POST["nb_item"];
      $nb_number++;

      $form=FormVente($cn,$g_jrn,$g_user,$HTTP_POST_VARS,false,$nb_number);
      echo '<div class="u_redcontent">';
      echo         $form;
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
    if ( isset($_GET["blank"]))
      {
      echo_debug("Blank form");
      // Show an empty form of invoice
      $form=FormVente($cn,$g_jrn,$g_user,null,false);
      echo '<div class="u_redcontent">';
      echo $form;
      echo '</div>';
    }

}


    // Save the invoice 
if ( isset($_POST["record_invoice"])) {
  // echo "RECORD INVOICE";
   RecordInvoice($cn,$HTTP_POST_VARS,$g_user,$g_jrn);
}

// Save and print the invoice
if ( isset($_POST["record_and_print_invoice"])) {
  //  echo "RECORD AND PRINT INVOICE";
  $comment=RecordInvoice($cn,$HTTP_POST_VARS,$g_user,$g_jrn);
      $nb_number=$_POST["nb_item"];
      $form=FormVenteView($cn,$g_jrn,$g_user,$HTTP_POST_VARS,$nb_number,'pdf',$comment);
	echo '<div class="u_redcontent">';
	echo $form;
	echo "</div>  ";
}


 if ( $action == 'voir_jrn' ) {
 // Show list of sell
 // Date - date of payment - Customer - amount
   $sql=SQL_LIST_ALL_INVOICE." and jr_tech_per=".GetUserPeriode($cn,$g_user)." and jr_def_id=$g_jrn";
   $list=ListJrn($cn,$g_jrn,$sql);
   echo '<div class="u_redcontent">';
   echo $list;
   echo '</div>';
}
if ( $action == 'voir_jrn_non_paye' ) {
// Show list of unpaid sell
// Date - date of payment - Customer - amount
  $sql=SQL_LIST_UNPAID_INVOICE_DATE_LIMIT." and jr_def_id=$g_jrn";
  $list=ListJrn($cn,$g_jrn,$sql);
  $sql=SQL_LIST_UNPAID_INVOICE." and jr_def_id=$g_jrn";
  $list2=ListJrn($cn,$g_jrn,$sql);
    echo '<div class="u_redcontent">';
    echo '<h2 class="info"> Echeance dépassée </h2>';
    echo $list;
    echo  '<h2 class="info"> Non Payée </h2>';
    echo $list2;
    echo '</div>';
}
// if ( $action == 'impress' ) {
// // Print list of sell or print the current invoice
// }

//Search
if ( $action == 'search' ) {
  // PhpSessid
  $sessid=(isset ($_POST['PHPSESSID']))?$_POST['PHPSESSID']:$_GET['PHPSESSID'];


// display a search box
  $search_box=u_ShowMenuRecherche($cn,$g_jrn,$sessid,$HTTP_POST_VARS);
  echo '<DIV class="u_redcontent">';
  echo $search_box; 
  // if nofirst is set then show result
  if ( isset ($_GET['nofirst'] ) )
    ViewJrn($g_dossier,$g_user,$g_jrn,$HTTP_POST_VARS);
  echo '</DIV>'; 
}

?>
