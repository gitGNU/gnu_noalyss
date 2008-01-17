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
require_once('user_form_fin.php');
require_once('jrn.php');
require_once("class_document.php");
require_once("class_fiche.php");
require_once("class_parm_code.php");
require_once("check_priv.php");
require_once ('class_pre_op_fin.php');

/*!\file
 * \brief the purpose off this file encode expense and  to record them
 *
 */


// First we show the menu
// If nothing is asked the propose a blank form
// to enter a new invoice
if ( ! isset ($_REQUEST['p_jrn'])) {
  // no journal are selected so we select the first one
  $p_jrn=GetFirstJrnIdForJrnType($gDossier,'FIN'); 

} else
{
  $p_jrn=$_REQUEST['p_jrn'];
}

if ( CheckJrn($gDossier,$_SESSION['g_user'],$p_jrn) < 1 )    {
        NoAccess();
        exit -1;
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

//----------------------------------------------------------------------
// ask the saldo of the bank
if ( $sub_action == "solde" )
{
   // Check privilege
   if ( CheckJrn($gDossier,$_SESSION['g_user'],$p_jrn) < 1 )    {
        NoAccess();
        exit -1;
   }
  echo '<div class="u_subtmenu">';

  echo ShowMenuJrnUser($gDossier,'FIN',0,'<td class="mtitle"><A class="mtitle" HREF="commercial.php?liste&'.dossier::get().'&p_action=bank&sa=list">Liste</A></td>'.
'<td class="selectedcell">Solde</td>');
 echo '</div>';
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
  echo '<div class="u_content">';

  echo "<table>";
  // Filter the saldo
  //  on the current year
  $filter_year=" and j_tech_per in (select p_id from parm_periode where  p_exercice='".$User->get_exercice()."')";

  // for each account
  for ( $i = 0; $i < pg_NumRows($ResAccount);$i++) {
    // get the saldo
    $l=pg_fetch_array($ResAccount,$i);
    $m=get_solde($cn,$l['pcm_val'],$filter_year);
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
  exit();
}
//-----------------------------------------------------
// If a list of depense is asked
// 
if ( $sub_action == "list") 
{
  // show the menu with the list item selected
  echo '<div class="u_subtmenu">';
  echo ShowMenuJrnUser($gDossier,'FIN',0,'<td class="selectedcell">Liste</td>'.
		       '<td class="mtitle"><A class="mtitle" HREF="commercial.php?liste&p_action=bank&sa=solde&'.dossier::get().'">Solde</A></td>');
  echo '</div>';

  echo '<div class="u_content">';

  
  echo '<form>';
  echo dossier::hidden();
  $hid=new widget("hidden");
  
  $hid->name="p_action";
  $hid->value="bank";
  echo $hid->IOValue();


  $hid->name="sa";
  $hid->value="list";
  echo $hid->IOValue();



  $w=new widget("select");
  // filter on the current year
  $filter_year=" where p_exercice='".$User->get_exercice()."'";

  $periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by p_start,p_end",1);
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
  $w->extra='4';
  $sp= new widget("span");
  echo $sp->IOValue("qcode_label",$qcode)."</TD></TR>";
  echo $w->IOValue();
  echo widget::submit('gl_submit','Rechercher');
  echo '</form>';
  echo $retour;
  // Show list of sell
  // Date - date of payment - Customer - amount
  if ( $current != -1 )
    {
      $filter_per=" and jr_tech_per=".$current;
    }
  else 
    {
      $filter_per=" and jr_tech_per in (select p_id from parm_periode where p_exercice=".
	$User->get_exercice().")";
    }
  /* security  */
  $available_ledger=" and ".$User->get_ledger_sql();

  // Show list of sell
  // Date - date of payment - Customer - amount
  $sql=SQL_LIST_ALL_INVOICE.$filter_per." and jr_def_type='FIN'".
    " $available_ledger" ;
  $step=$_SESSION['g_pagesize'];
  $page=(isset($_GET['offset']))?$_GET['page']:1;
  $offset=(isset($_GET['offset']))?$_GET['offset']:0;

  $l="";
  // check if qcode contains something
  if ( $qcode != "" )
    {
      // add a condition to filter on the quick code
      $l=" and jr_grpt_id in (select j_grpt from jrnx where j_qcode='$qcode') ";
    }

  list($max_line,$list)=ListJrn($cn,0,"where jrn_def_type='FIN' $filter_per $l $available_ledger "
				,null,$offset,0);
  $bar=jrn_navigation_bar($offset,$max_line,$step,$page);

  echo "<hr> $bar";
  echo $list;
  echo "$bar <hr>";
  echo $retour;

  echo '</div>';

 exit();
} 
//-----------------------------------------------------
echo '<div class="u_subtmenu">';
echo ShowMenuJrnUser($gDossier,'FIN',$p_jrn,
					 '<td class="mtitle"><A class="mtitle" HREF="commercial.php?liste&p_action=bank&sa=list&'.dossier::get().'">Liste</A></td>'.
					 '<td class="mtitle"><A class="mtitle" HREF="commercial.php?liste&p_action=bank&sa=solde&'.dossier::get().'">Solde</A></td>');
echo '</div>';
//--------------------------------------------------------------------------------
// use a predefined operation
//--------------------------------------------------------------------------------
if ( $sub_action=="use_opd" ) {
  $op=new Pre_op_fin($cn);
  $op->set_od_id($_REQUEST['pre_def']);
  $p_post=$op->compute_array();
  echo_debug(__FILE__.':'.__LINE__.'- ','p_post = ',$p_post);
  // submit button in the form
  $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">'.
	'<INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Enregistrer">';

  $form=FormFin($cn,$_GET['p_jrn'],$User->get_periode(),$submit,$p_post,false,$p_post['nb_item']);
  echo '<div class="u_content">';
  echo   $form;
  echo '</div>';
  exit();
 }


//-----------------------------------------------------
// if we request to add an item 
// the $_POST['add_item'] is set
// or if we ask to correct the invoice
if ( isset ($_POST['add_item']) || isset ($_POST['correct'])  ) 
{
 if ( CheckJrn($gDossier,$_SESSION['g_user'],$p_jrn) != 2 )    {
        NoAccess();
        exit -1;
   }

  $nb_item=$_POST['nb_item'];
  if ( isset  ($_POST['add_item']))
    $nb_item++; 
 // Submit button in the form
  $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">
          <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Enregistrer" ID="SubmitButton">';
  $form=FormFin($cn,$p_jrn,$User->get_periode(),$submit,$_POST,false,  $nb_item);
  //$form=FormFin($cn,$p_jrn,$User->get_periode(),$submit,$_POST,false,  $nb_number);

  echo '<div class="u_content">';
  echo $form;
  echo JS_CALC_LINE;
  echo '</div>';
  exit();
}
//-----------------------------------------------------
// Save : record 
//
if ( isset($_POST['save'])) 
{
 if ( CheckJrn($gDossier,$_SESSION['g_user'],$p_jrn) != 2 )    {
        NoAccess();
        exit -1;
   }

  // we save the expense
  $r=RecordFin($cn,$_POST,$User,$p_jrn);
  $nb_number=$_POST['nb_item'];
  
  $submit='<h2 class="info"> Op&eacute;ration '.$r.' enregistr&eacute;</h2>';

  $form=FormFin($cn,$p_jrn,$User->get_periode(),$submit,$_POST,true,$nb_number,true);

  echo '<div class="u_content">';
  echo $form;
  echo '<hr>';
  echo '</form>';
  echo '<A href="commercial.php?p_action=bank&p_jrn='.$p_jrn.'&'.dossier::get().'">
    <input type="button" Value="Nouveau"></A>';
  exit();
}
//-----------------------------------------------------
// we show the confirmation screen
// 
if ( isset ($_POST['view_invoice']) ) 
{
 if ( CheckJrn($gDossier,$_SESSION['g_user'],$p_jrn) != 2 )    {
        NoAccess();
        exit -1;
   }

  $nb_number=$_POST["nb_item"];
  $submit='<INPUT TYPE="SUBMIT" name="save" value="Confirmer">';
  $submit.='<INPUT TYPE="SUBMIT" name="correct" value="Corriger">';
  if ( form_verify_input ($cn,$p_jrn,$User->get_periode(),$_POST,$nb_number) != null ) {
    // Should use a read only view instead of FormFin
    // where we can check
    $form=FormFin($cn,$p_jrn,$User->get_periode(),$submit,$_POST,true,  $nb_number,false);
  } else {
    // if something goes wrong, correct it
    $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">
                    <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Enregistrer">';
    $form=FormFin($cn,$p_jrn,$User->get_periode(),$submit,$_POST,false,  $nb_number);

  }
  
  echo '<div class="u_redcontent">';
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
  echo_debug('depense.inc.php',__LINE__,"Blank form");
 // Submit button in the form
  $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">
          <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Enregistrer" ID="SubmitButton">';
  // Show an empty form of invoice
  $form=FormFin($cn,$p_jrn,$User->get_periode(),$submit,null,false,$jrn->GetDefLine('deb'));
  echo '<div class="u_content">';
  echo $form;
  echo '<form method="GET">';
  $op=new Pre_operation($cn);
  $op->p_jrn=$p_jrn;
  $op->od_direct='f';

  $hid=new widget("hidden");
  echo $hid->IOValue("p_action","bank");
  echo $hid->IOValue("sa","use_opd");
  echo dossier::hidden();
  echo $hid->IOValue("p_jrn",$p_jrn);
  echo $hid->IOValue("jrn_type","FIN");
  
  if ($op->count() != 0 )
	echo widget::submit('use_opd','Utilisez une op.prédéfinie');
  echo $op->show_button();
  
  echo '</form>';

  echo JS_CALC_LINE;
  echo '</div>';
}
