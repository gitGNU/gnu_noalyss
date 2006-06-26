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
/* $Revision$ */
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
require_once('class_jrn.php');
require_once('user_form_fin.php');
require_once('jrn.php');
require_once("class_document.php");
require_once("class_fiche.php");
/*!\file
 * \brief the purpose off this file encode expense and  to record them
 *
 */


// First we show the menu
// If nothing is asked the propose a blank form
// to enter a new invoice
if ( ! isset ($_REQUEST['p_jrn'])) {
  // no journal are selected so we select the first one
  $p_jrn=GetFirstJrnIdForJrnType($_SESSION['g_dossier'],'FIN'); 

} else
{
  $p_jrn=$_REQUEST['p_jrn'];
}
// for the back button
$retour="";
$h_url="";

if ( isset ($_REQUEST['url'])) 
{
  $retour=sprintf('<A HREF="%s"><input type="button" value="Retour"></A>',urldecode($_REQUEST['url']));
  $h_url=sprintf('<input type="hidden" name="url" value="%s">',urldecode($_REQUEST['url']));
}

$sub_action=(isset($_REQUEST['sa']))?$_REQUEST['sa']:"";
//-----------------------------------------------------
// If a list of depense is asked
// 
if ( $sub_action == "list") 
{

  // show the menu with the list item selected
  echo '<div class="u_subtmenu">';
  echo ShowMenuJrnUser($_SESSION['g_dossier'],'FIN',0,'<td class="selectedcell">Liste</td>');
  echo '</div>';

  echo '<div class="u_redcontent">';

  

  $hid=new widget("hidden");
  
  $hid->name="p_action";
  $hid->value="bank";
  echo $hid->IOValue();


  $hid->name="sa";
  $hid->value="list";
  echo $hid->IOValue();



  $w=new widget("select");

  $periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode order by p_id");
  // User is already set User=new cl_user($cn);
  $current=(isset($_GET['p_periode']))?$_GET['p_periode']:$User->GetPeriode();
  $w->selected=$current;

  echo 'Période  '.$w->IOValue("p_periode",$periode_start).$w->Submit('gl_submit','Valider');
   $qcode=(isset($_GET['qcode']))?$_GET['qcode']:"";
 echo JS_SEARCH_CARD;
 $w=new widget('js_search_only');
 $w->name='qcode';
 $w->value=$qcode;
 $w->label='qcode';
 $w->extra='4,8,9,14';
 $sp= new widget("span");
 echo $sp->IOValue("qcode_label",$qcode)."</TD></TR>";
 echo $w->IOValue();

  echo $retour;
  // Show list of sell
  // Date - date of payment - Customer - amount
  $sql=SQL_LIST_ALL_INVOICE." and jr_tech_per=".$current." and jr_def_type='FIN'" ;
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

  list($max_line,$list)=ListJrn($cn,0,"where jrn_def_type='FIN' and jr_tech_per=$current $l "
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
echo ShowMenuJrnUser($_SESSION['g_dossier'],'FIN',$p_jrn,'<td class="cell"><A class="mtitle" HREF="commercial.php?liste&p_action=bank&sa=list">Liste</A></td>');
echo '</div>';
//-----------------------------------------------------
// if we request to add an item 
// the $_POST['add_item'] is set
// or if we ask to correct the invoice
if ( isset ($_POST['add_item']) || isset ($_POST['correct'])  ) 
{
  $nb_item=$_POST['nb_item'];
  if ( isset  ($_POST['add_item']))
    $nb_item++; 
 // Submit button in the form
  $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">
          <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Sauver" ID="SubmitButton">';
  $form=FormFin($cn,$_GET['p_jrn'],$User->GetPeriode(),$submit,$_POST,false,  $nb_item);
  //$form=FormFin($cn,$p_jrn,$User->GetPeriode(),$submit,$_POST,false,  $nb_number);

  echo '<div class="u_redcontent">';
  echo $form;
  echo '</div>';
  exit();
}
//-----------------------------------------------------
// Save : record 
//
if ( isset($_POST['save'])) 
{
  // we save the expense
  $r=RecordFin($cn,$_POST,$User,$p_jrn);
  $nb_number=$_POST['nb_item'];
  
  $submit='<h2 class="info"> Op&eacute;ration '.$r.' enregistr&eacute;</h2>';

  $form=FormFin($cn,$p_jrn,$User->GetPeriode(),$submit,$_POST,true,$nb_number,true);

  echo '<div class="u_redcontent">';
  echo $form;
  echo '<hr>';
  echo '</form>';
  echo '<A href="commercial.php?p_action=bank&p_jrn='.$_GET['p_jrn'].'">
    <input type="button" Value="Nouveau"></A>';
  exit();
}
//-----------------------------------------------------
// we show the confirmation screen
// 
if ( isset ($_POST['view_invoice']) ) 
{
  $nb_number=$_POST["nb_item"];
  $submit='<INPUT TYPE="SUBMIT" name="save" value="Confirmer">';
  $submit.='<INPUT TYPE="SUBMIT" name="correct" value="Corriger">';
  if ( form_verify_input ($cn,$p_jrn,$User->GetPeriode(),$_POST,$nb_number) != null ) {
    // Should use a read only view instead of FormFin
    // where we can check
    $form=FormFin($cn,$p_jrn,$User->GetPeriode(),$submit,$_POST,true,  $nb_number,false);
  } else {
    // if something goes wrong, correct it
    $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">
                    <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Sauver">';
    $form=FormFin($cn,$p_jrn,$User->GetPeriode(),$submit,$_POST,false,  $nb_number);

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
  $jrn=new jrn($cn,  $p_jrn);
  echo_debug('depense.inc.php',__LINE__,"Blank form");
 // Submit button in the form
  $submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">
          <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Sauver" ID="SubmitButton">';
  // Show an empty form of invoice
  $form=FormFin($cn,$p_jrn,$User->GetPeriode(),$submit,null,false,$jrn->GetDefLine('deb'));
  echo '<div class="u_redcontent">';
  echo $form;
  echo '</div>';
}
