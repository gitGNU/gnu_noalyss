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

/* !\file 
 */

/* \brief to write directly into the ledgers,the stock and the tables
 * quant_purchase and quant_sold are not changed by this
 *
 */
require_once ('class_acc_ledger.php');
require_once ('class_acc_reconciliation.php');

$cn=DbConnect(dossier::id());
$id=(isset ($_REQUEST['p_jrn']))?$_REQUEST['p_jrn']:-1;

function show_direct_form($cn,$ledger,$p_array) {
  $id=(isset ($_REQUEST['p_jrn']))?$_REQUEST['p_jrn']:-1;
  echo JS_COMPUTE_DIRECT;
  echo Acc_Reconciliation::$javascript;

  // Show the predef operation
  // Don't forget the p_jrn 
  echo '<form>';
  echo dossier::hidden();
  echo widget::hidden('p_action',$_REQUEST['p_action']);
  
  echo '<input type="hidden" value="'.$id.'" name="p_jrn">';
  $op=new Pre_operation($cn);
  $op->p_jrn=$id;
  $op->od_direct='t';
  if ($op->count() != 0 )
    echo widget::submit_button('use_opd','Utilisez une op.pr&eacute;d&eacute;finie');
  echo $op->show_button();
  echo '</form>';

  echo '<form method="post" action="?">';
  echo dossier::hidden();
  echo widget::hidden('p_action',$_REQUEST['p_action']);

  echo $ledger->show_form($p_array);

  echo widget::submit_button('summary','Sauvez');
  echo '<div class="info">
    D&eacute;bit = <span id="totalDeb"></span>
    Cr&eacute;dit = <span id="totalCred"></span>
    Difference = <span id="totalDiff"></span>
</div>
    ';


  echo '</form>';
   echo "<div>".JS_CALC_LINE."</div>";

  echo '</div>';
}

$ledger=new Acc_Ledger($cn,$id);
$ledger->with_concerned=true;
// no ledger selected, propose one
if ($id == -1 )
  {
    echo '<div class="u_content">';

     // Vide
     echo '<FORM method="get" action="?">';
     echo dossier::hidden();
     echo widget::hidden('p_action',$_REQUEST['p_action']);
     echo $ledger->select_ledger()->IOValue();
     echo widget::submit_button('show_form','Choix du journal');
     echo '</form>';
     echo '</div>';
     exit();
  }
if ( $User->AccessJrn($cn,$id) == false ) {
  echo "
<script> alert(\"L'acces a ce journal est interdit, contactez votre responsable\");</script>";
  exit();
 }

  
echo '<div class="u_content">';
echo '<h2 class="info"> Journal : '.$ledger->GetName().'</h2>';
echo widget::button_href('Autre journal','?p_action='.$_REQUEST['p_action'].'&'.dossier::get());

if ( isset($_GET['show_form']) || isset($_POST['correct_it']) ) {
$array=$_POST;
$default_periode=$User->GetPeriode();
list($date,$devnull)=GetPeriode($cn,$default_periode);
$array['date']=$date;
  show_direct_form($cn,$ledger,$array);
  exit();
 }

// reload with a predefined operation
//
if ( isset ($_GET['use_opd'])) {
  $op=new Pre_op_advanced($cn);
  $op->set_od_id($_REQUEST['pre_def']);
  //$op->p_jrn=$id;
  
  $p_post=$op->compute_array();
  show_direct_form($cn,$ledger,$p_post);

  exit();
  
 }
if ( isset($_POST['summary'])) {
       echo '<form method="post"  action="?">';
       echo $ledger->show_form($_POST,1);
       echo dossier::hidden();
       echo widget::hidden('p_action',$_REQUEST['p_action']);

       echo widget::submit_button('save_it',"Sauver");
       echo widget::submit_button('correct_it','Corriger');
       
       $chk=new widget('checkbox');
       $chk->selected=false;
       echo "Sauvez l'op&eacute;ration ?";
       echo $chk->IOValue('save_opd');

       echo '</form>';
       exit();

 }
if ( isset($_POST['save_it' ])) {
  $array=$_POST;

  try {
    $ledger->save($array);
    echo '<h2> Op&eacute;ration enregistr&eacute;e</h2>';
    //    echo $ledger->show_form($array,true);
  } catch (AcException $e) {
    echo '<script>alert (\''.$e->getMessage()."'); </script>";
    show_direct_form($cn,$ledger,$_POST);
  }
  exit();
 }
