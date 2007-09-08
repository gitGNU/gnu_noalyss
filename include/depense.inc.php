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
require_once('class_jrn.php');
require_once('user_form_ach.php');
require_once('jrn.php');
require_once("class_document.php");
require_once("class_fiche.php");
require_once("check_priv.php");
/*!\brief the purpose off this file encode expense and  to record them
 *
 */

$msg_tva='<i>Si le montant de TVA est &eacute;gal &agrave; 0, il sera automatiquement calcul&eacute;</i>';

// First we show the menu
// If nothing is asked the propose a blank form
// to enter a new invoice
if ( ! isset ($_REQUEST['p_jrn'])) {
  // no journal are selected so we select the first one
  $p_jrn=GetFirstJrnIdForJrnType(dossier::id(),'ACH'); 

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
// If a list of depense is asked
// 
if ( $sub_action == "list") 
{
  if ( CheckJrn($gDossier,$_SESSION['g_user'],$p_jrn) < 1 )    {
        NoAccess();
        exit -1;
   }
  // show the menu with the list item selected
  echo '<div class="u_subtmenu">';
  echo ShowMenuJrnUser($gDossier,'ACH',0,'<td class="selectedcell">Liste</td>');
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

  

  echo '<form method= "GET" action="commercial.php">';
  echo dossier::hidden();
  $hid=new widget("hidden");
  
  $hid->name="p_action";
  $hid->value="depense";
  echo $hid->IOValue();


  $hid->name="sa";
  $hid->value="list";
  echo $hid->IOValue();



  $w=new widget("select");
  // filter on the current year
  $filter_year=" where p_exercice='".$User->getExercice()."'";

  $periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by p_start,p_end",1);
  // User is already set User=new cl_user($cn);
  $current=(isset($_GET['p_periode']))?$_GET['p_periode']:$User->GetPeriode();
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
  echo $w->IOValue();
  echo $sp->IOValue("qcode_label","QuickCode",$qcode);

  echo $w->Submit('gl_submit','Rechercher');

  echo '<br>'.$retour;

  // Show list of sell
  // Date - date of payment - Customer - amount
  if ( $current != -1 )
    {
      $filter_per=" and jr_tech_per=".$current;
    }
  else 
    {
      $filter_per=" and jr_tech_per in (select p_id from parm_periode where p_exercice=".
	$User->getExercice().")";
    }

  $sql=SQL_LIST_ALL_INVOICE." $filter_per  and jr_def_type='ACH'" ;

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

  list($max_line,$list)=ListJrn($cn,0,"where jrn_def_type='ACH'   $filter_per  $l "
				,null,$offset,1);
  $bar=jrn_navigation_bar($offset,$max_line,$step,$page);

  echo "<hr> $bar";
  echo $list;
  echo "$bar <hr>";
  if ( $max_line !=0 )
    echo $hid->Submit('paid','Mise à jour paiement');
  echo '</FORM>';
  echo $retour;

  echo '</div>';

 exit();
} 
//-----------------------------------------------------
echo '<div class="u_subtmenu">';
echo ShowMenuJrnUser($gDossier,
		     'ACH',
		     $p_jrn,
					 '<td class="cell"><A class="mtitle" HREF="commercial.php?liste&p_action=depense&sa=list&'.$str_dossier.'">Liste</A></td>');
echo '</div>';
//-----------------------------------------------------
// if we request to add an item 
// the $_POST['add_item'] is set
// or if we ask to correct the invoice
if ( isset ($_POST['add_item']) || isset ($_POST["correct"])  ) 
{
 if ( CheckJrn($gDossier,$_SESSION['g_user'],$p_jrn) != 2 )    {
        NoAccess();
        exit -1;
   }

  $nb_item=$_POST['nb_item'];
  if ( isset ($_POST['add_item']))
    $nb_item++;
 // Submit button in the form
  $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">
          <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Enregistrer" ID="SubmitButton">';

  $form=FormAchInput($cn,$p_jrn,$User->GetPeriode(),$_POST,$submit,false,$nb_item);
  echo '<div class="u_redcontent">';
  echo $form;
  echo $msg_tva;
  echo JS_CALC_LINE;

  echo '</div>';
  exit();
}
//-----------------------------------------------------
// we want to save the invoice 
//
if ( isset($_POST['save'])) 
{
 if ( CheckJrn($gDossier,$_SESSION['g_user'],$p_jrn) != 2 )    {
        NoAccess();
        exit -1;
   }
  $nb_number=$_POST["nb_item"];
  if ( form_verify_input ($cn,$p_jrn,$User->GetPeriode(),$_POST,$nb_number) == true ) {
    // we save the expense
    list ($internal,$c)=RecordSell($cn,$_POST,$User,$p_jrn);
    $form=FormAchView($cn,$p_jrn,$User->GetPeriode(),$_POST,"",$_POST['nb_item'],false);
    echo '<div class="u_redcontent">';
    echo '<h2 class="info"> Op&eacute;ration '.$internal.' enregistr&eacute;</h2>';
    echo $form;
    echo '<hr>';
    echo '</form>';
    echo '<A class="mtitle" href="commercial.php?p_action=depense&p_jrn='.$p_jrn.'">
    <input type="button" Value="Autre dépense"></A>';
    exit();
  }
  else 
    {
      $submit='<INPUT TYPE="SUBMIT" name="save" value="Confirmer" onClick="return verify_ca(\'error\');" >';
      $submit.='<input type="button" value="verifie CA" onClick="verify_ca(\'ok\');">';
      $submit.='<INPUT TYPE="SUBMIT" name="correct" value="Corriger">';
      $form=FormAchView($cn,$p_jrn,$User->GetPeriode(),$_POST,$submit,$nb_number,true);
      echo '<div class="u_redcontent">';
      echo $form;
      echo '<hr>';
      echo '</form>';
      return;
    }
}
//-----------------------------------------------------
// we show the confirmation screen
// 
if ( isset ($_POST['view_invoice']) ) 
{
   // Check privilege
   if ( CheckJrn($gDossier,$_SESSION['g_user'],$p_jrn) < 1 )    {
        NoAccess();
        exit -1;
   }
  $nb_number=$_POST["nb_item"];
  $submit='<INPUT TYPE="SUBMIT" name="save" value="Confirmer">';
  $submit.='<INPUT TYPE="SUBMIT" name="correct" value="Corriger">';
  if ( form_verify_input ($cn,$p_jrn,$User->GetPeriode(),$_POST,$nb_number) == true ) {
    // Should use a read only view instead of FormAch
    // where we can check
    $form=FormAchView($cn,$p_jrn,$User->GetPeriode(),$_POST,$submit,$nb_number);
  } else {
    // if something goes wrong, correct it
    $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">
                    <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Enregistrer">';
    $form=FormAchInput($cn,$p_jrn,$User->GetPeriode(),$_POST,$submit, false, $nb_number);
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

  $jrn=new jrn($cn,  $p_jrn);
  echo_debug('depense.inc.php',__LINE__,"Blank form");
 // Submit button in the form
  $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">
          <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Enregistrer" ID="SubmitButton">';
  // Show an empty form of invoice
  $form=FormAchInput($cn,$p_jrn,$User->GetPeriode(),null,$submit,false,$jrn->getDefLine());
  echo '<div class="u_redcontent">';
  echo $form;
  echo $msg_tva;

  echo JS_CALC_LINE;
  echo '</div>';

}
