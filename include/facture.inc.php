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
require_once('class_acc_ledger.php');
require_once('user_form_ven.php');
require_once('jrn.php');
require_once("class_document.php");
require_once("class_fiche.php");
require_once("check_priv.php");
$tag_list='<td class="mtitle"><A class="mtitle" HREF="commercial.php?liste&p_action=facture&sa=list&'.$str_dossier.'">Liste</A>';
$tag_list_sel='<td class="selectedcell">Liste</td>';
$tag_unpaid='</td><td class="mtitle"><A class="mtitle" href="commercial.php?liste&p_action=facture&sa=unpaid&'.$str_dossier.'">Non paye</A></TD>';
$tag_unpaid_sel='<td class="selectedcell">Non paye</td>';

/*!\file
 * \brief the purpose off this file is to create invoices, to record them and to generate
 *        them, and of course to save them into the database
 *
 */


// First we show the menu
// If nothing is asked the propose a blank form
// to enter a new invoice
if ( ! isset ($_REQUEST['p_jrn'])) {
  // no journal are selected so we select the first one
  $p_jrn=GetFirstJrnIdForJrnType($gDossier,'VEN'); 
} else
{
  $p_jrn=$_REQUEST['p_jrn'];
}
// for the back button
$retour="";
$h_url="";

if ( isset ($_REQUEST['url'])) 
{
  $retour=sprintf('<A class="mtitle" HREF="%s"><input type="button" value="Retour"></A>',urldecode($_REQUEST['url']));
  $h_url=sprintf('<input type="hidden" name="url" value="%s">',urldecode($_REQUEST['url']));
}

$sub_action=(isset($_REQUEST['sa']))?$_REQUEST['sa']:"";
//-----------------------------------------------------
// If a list of invoice is asked
// 
if ( $sub_action == "list") 
{
   // Check privilege
  /*   if ( CheckJrn($gDossier,$_SESSION['g_user'],$p_jrn) < 1 )    {
        NoAccess();
        exit -1;
   }
  */
  // show the menu with the list item selected
  echo '<div class="u_subtmenu">';
  echo ShowMenuJrnUser($gDossier,'VEN',0,$tag_list_sel.$tag_unpaid);
  echo '</div>';
  // Ask to update payment
  if ( isset ( $_GET['paid'])) 
    {
      // reset all the paid flag because the checkbox is post only
      // when checked
      foreach ($_GET as $name=>$paid) 
	{
	    list($ad) = sscanf($name,"set_jr_id%d");
 	    if ( $ad == null ) continue;
 	    $sql="update jrn set jr_rapt='' where jr_id=$ad";
 	    $Res=ExecSql($cn,$sql);
	    
	}
	// set a paid flag for the checked box
      foreach ($_GET as $name=>$paid) 
	{
	  list ($id) = sscanf ($name,"rd_paid%d");
	  
	  if ( $id == null ) continue;

	  $paid=($paid=='on')?'paid':'';
	  $sql="update jrn set jr_rapt='$paid' where jr_id=$id";
	  $Res=ExecSql($cn,$sql);
	}
      
      }

  echo '<div class="u_content">';
  echo $retour;
  

  echo '<form method= "GET" action="commercial.php">';
  echo dossier::hidden();

  $hid=new widget("hidden");
  
  $hid->name="p_action";
  $hid->value="facture";
  echo $hid->IOValue();


  $hid->name="sa";
  $hid->value="list";
  echo $hid->IOValue();



  $w=new widget("select");
  //  Add filter on the year
  $filter_year=" where p_exercice='".$User->get_exercice()."'";

  $periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') ".
			    " from parm_periode $filter_year order by p_start,p_end",1);
  // User is already set User=new User($cn);
  $current=(isset($_GET['p_periode']))?$_GET['p_periode']:$User->get_periode();
  $w->selected=$current;

  echo 'Période  '.$w->IOValue("p_periode",$periode_start);
  $qcode=(isset($_GET['qcode']))?$_GET['qcode']:"";
  
  echo JS_SEARCH_CARD;
  $w=new widget('js_search_only');
  $w->name='qcode';
  $w->value=$qcode;
  $w->label='';
  $w->extra='all';
  $w->table=0;
  $sp= new widget("span");

  echo $sp->IOValue("qcode_label","",$qcode);
  echo $w->IOValue();
echo widget::submit('gl_submit','Rechercher');
  // Show list of sell
  // Date - date of payment - Customer - amount
 if ( $current == -1) {
   $cond=" and jr_tech_per in (select p_id from parm_periode where p_exercice='".$User->get_exercice()."')";
 } else {
   $cond=" and jr_tech_per=".$current;
 }
  $sql=SQL_LIST_ALL_INVOICE.$cond." and jr_def_type='VEN'" ;
  $step=$_SESSION['g_pagesize'];
  $page=(isset($_GET['offset']))?$_GET['page']:1;
  $offset=(isset($_GET['offset']))?$_GET['offset']:0;

  $l="";
  // check if qcode contains something
  if ( $qcode != "" )
    {
      $qcode=Formatstring($qcode);
      // add a condition to filter on the quick code
      $l=" and jr_grpt_id in (select j_grpt from jrnx where j_qcode=upper('$qcode')) ";
    }
  /* security  */
  $available_ledger=$User->get_ledger_sql();

  echo_debug(__FILE__.':'.__LINE__.' - available_ledger','',$available_ledger);
  list($max_line,$list)=ListJrn($cn,0,"where jrn_def_type='VEN' $cond $l and $available_ledger "
				,null,$offset,1);
  $bar=jrn_navigation_bar($offset,$max_line,$step,$page);

  echo "<hr> $bar";
  echo $list;
  echo "$bar <hr>";
  if ( $max_line !=0 )
    echo widget::submit('paid','Mise à jour paiement');
  echo '</FORM>';
  echo $retour;

  echo '</div>';

 exit();
} 
if ( $sub_action=="unpaid") {
echo '<div class="u_subtmenu">';
echo ShowMenuJrnUser($gDossier,'VEN',0,$tag_list.$tag_unpaid_sel);
echo '</div>';

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
  /* security put a filter on the ledger */
  $available_ledger=$User->get_ledger_sql();


   $sql=SQL_LIST_UNPAID_INVOICE_DATE_LIMIT." and jrn_def_type='VEN' and $available_ledger";
  list($max_line,$list)=ListJrn($cn,0,$sql,null,$offset,1);
  $sql=SQL_LIST_UNPAID_INVOICE." and ".$available_ledger.
    " and jrn_def_type='VEN'";
  list($max_line2,$list2)=ListJrn($cn,0,$sql,null,$offset,1);

  // Get the max line
   $m=($max_line2>$max_line)?$max_line2:$max_line;
   $bar2=jrn_navigation_bar($offset,$m,$step,$page);
   
    echo '<div class="u_redcontent">';
    echo '<FORM METHOD="POST">';
	echo dossier::hidden();  
    echo $bar2;
    echo '<h2 class="info"> Echeance dépassée </h2>';
    echo $list;
    echo  '<h2 class="info"> Non Payée </h2>';
    echo $list2;
    echo $bar2;
    // Add hidden parameter
    $hid=new widget("hidden");

    $hid->name="sa";
    $hid->value="unpaid";
    echo $hid->IOValue();

    $hid->name="p_action";
    $hid->value="facture";
    echo $hid->IOValue();
    echo '<hr>';

    if ( $m != 0 )
      echo widget::submit('paid','Mise à jour paiement');

    echo '</FORM>';
    echo '</div>';
    exit();
 }
//-----------------------------------------------------
echo '<div class="u_subtmenu">';
echo ShowMenuJrnUser($gDossier,'VEN',$p_jrn,$tag_list.$tag_unpaid);
echo '</div>';
//--------------------------------------------------------------------------------
// use a predefined operation
//--------------------------------------------------------------------------------
if ( $sub_action=="use_opd" ) {
  $op=new Pre_op_ven($cn);
  $op->set_od_id($_REQUEST['pre_def']);
  $p_post=$op->compute_array();
  echo_debug(__FILE__.':'.__LINE__.'- ','p_post = ',$p_post);
  $form=FormVenInput($cn,$_GET['p_jrn'],$User->get_periode(),$p_post,false,$p_post['nb_item']);
  echo '<div class="u_content">';
  echo   $form;

  //--------------------
  // predef op.
  echo '<form method="GET">';
  $op=new Pre_operation($cn);
  $op->p_jrn=$p_jrn;
  $op->od_direct='f';
  $hid=new widget("hidden");
  echo $hid->IOValue("p_action","facture");
  echo dossier::hidden();
  echo $hid->IOValue("p_jrn",$p_jrn);
  echo $hid->IOValue("jrn_type","VEN");
  echo $hid->IOValue("sa","use_opd");
  
  if ($op->count() != 0 )
	echo widget::submit('use_opd','Utilisez une op.prédéfinie');
  echo $op->show_button();

  echo '</form>';

  echo '</div>';
  exit();
 }

//-----------------------------------------------------
// if we request to add an item 
// the $_POST['add_item'] is set
// or if we ask to correct the invoice
if ( isset ($_POST['add_item']) || isset ($_POST["correct_new_invoice"])  ) 
{
 if ( CheckJrn($gDossier,$_SESSION['g_user'],$p_jrn) != 2 )    {
        NoAccess();
        exit -1;
   }

  $nb_item=$_POST['nb_item'];
  if ( isset ($_POST['add_item']))
    $nb_item++;
  $form=FormVenInput($cn,$p_jrn,$User->get_periode(),$_POST,false,$nb_item);
  echo '<div class="u_content">';
  echo $form;
  echo '</div>';
  exit();
}
//-----------------------------------------------------
// we want to save the invoice and to generate a invoice
//
if ( isset($_POST['record_and_print_invoice'])) 
{
 if ( CheckJrn($gDossier,$_SESSION['g_user'],$p_jrn) != 2 )    {
        NoAccess();
        exit -1;
   }
 $nb_number=$_POST['nb_item'];
  // First we save the invoice, the internal code will be used to change the description
  // and upload the file
  if ( form_verify_input($cn,$p_jrn,$User->get_periode(),$_POST,$nb_number)== true) {
    list ($internal,$e)=RecordInvoice($cn,$_POST,$User,$p_jrn);
    $form=FormVenteView($cn,$p_jrn,$User->get_periode(),$_POST,$_POST['nb_item'],'noform','');
  
  echo '<div class="u_content">';
  echo '<h2 class="info"> Op&eacute;ration '.$internal.' enregistr&eacute;</h2>';
  echo $form;
  echo '<hr>';
  
  // Show the details of the encoded invoice 
  // and the url of the invoice
  if ( isset($_POST['gen_invoice'])) 
    {
      	  $doc=new Document($cn);
	  $doc->f_id=$_POST['e_client'];
	  $doc->md_id=$_POST['gen_doc'];
	  $doc->ag_id=0;
	  $str_file=$doc->Generate();
	  // Move the document to the jrn
	  $doc->MoveDocumentPj($internal);
	  // Update the comment with invoice number
	  $sql="update jrn set jr_comment='Facture ".$doc->d_number."' where jr_internal='$internal'";
	  ExecSql($cn,$sql);
	  echo $str_file;
    }
  } else {
    
    echo("A cause d'erreur la facture ne peut-&egrave;tre valid&eacute; ");
    $form=FormVenteView($cn,$_GET['p_jrn'],$User->get_periode(),$_POST,$nb_number,"form");
    
  }

  echo '</form>';
  // Button return 
  printf ('<A class="mtitle" href="?jrn_type=VEN&p_jrn=%d&p_action=facture&%s"><input type="Button" value="Autre Facture"></A>',
		  $p_jrn,dossier::get());
  exit();
}
//-----------------------------------------------------
// we show the confirmation screen it is proposed here to generate the
// invoice
if ( isset ($_POST['view_invoice']) ) 
{
   // Check privilege
   if ( CheckJrn($gDossier,$_SESSION['g_user'],$p_jrn) < 1 )    {
        NoAccess();
        exit -1;
   }
  $nb_number=$_POST["nb_item"];
  if ( form_verify_input($cn,$p_jrn,$User->get_periode(),$_POST,$nb_number) == true)
    {
      $form=FormVenteView($cn,$p_jrn,$User->get_periode(),$_POST,$nb_number);

    } else {
      // Check failed : invalid date or quantity
      echo_error("Cannot validate ");
      $form=FormVenInput($cn,$p_jrn,$User->get_periode(),$_POST,false,$nb_number);
    }

  echo '<div class="u_content">';
  echo         $form;
  echo '</div>';
  exit();

}



//-----------------------------------------------------
// By default we add a new invoice
if ( $p_jrn != -1 ) 
{
 if ( CheckJrn($gDossier,$_SESSION['g_user'],$p_jrn) != 2 )    {
        exit -1;
   }

  $jrn=new Acc_Ledger($cn,  $p_jrn);
  echo_debug('facture.inc.php.php',__LINE__,"Blank form");
  // Show an empty form of invoice
  $form=FormVenInput($cn,$p_jrn,$User->get_periode(),null,false,$jrn->GetDefLine());
  echo '<div class="u_content">';
  echo $form;

  //--------------------
  // predef op.
  echo '<form method="GET">';
  $op=new Pre_operation($cn);
  $op->p_jrn=$p_jrn;
  $op->od_direct='f';
  $hid=new widget("hidden");
  echo $hid->IOValue("p_action","facture");
  echo dossier::hidden();
  echo $hid->IOValue("p_jrn",$p_jrn);
  echo $hid->IOValue("jrn_type","VEN");
  echo $hid->IOValue("sa","use_opd");
  
  if ($op->count() != 0 )
	echo widget::submit('use_opd','Utilisez une op.prédéfinie');
  echo $op->show_button();

  echo '</form>';
  echo '</div>';
}
