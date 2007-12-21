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

/* \brief Misc Operation for analytic accountancy
 *
 */
require_once("class_anc_account.php");
require_once ("class_widget.php");
require_once ("class_anc_operation.php");
require_once ("class_anc_plan.php");
require_once ("class_anc_group_operation.php");


$pa=new Anc_Plan($cn);
$m=$pa->get_list();
if ( ! $m )  { echo '<h2 class="info"> Aucun plan analytique d&eacute;fini</h2>';exit();}
echo '<div class="u_subt2menu">';
echo '<table>';
echo '<tr>';
echo '</div>';

//----------------------------------------------------------------------
// show the left menu
//----------------------------------------------------------------------
echo '
<div class="lmenu">
<table>
<tr>
    <td  class="mtitle" >
     <A class="mtitle" HREF="?p_action=ca_od&new&'.$str_dossier.'"> Nouveau </A>
 </td>
    <td  class="mtitle" >
     <A class="mtitle" HREF="?p_action=ca_od&see&'.$str_dossier.'"> Liste op&eacute;rations </A
 </td>
</tr>
</table>
</div>';
//----------------------------------------------------------------------
// the pa_id is set 
//
//----------------------------------------------------------------------
if ( isset($_GET['see'])) {

  // Show the list for the period
  // and exit
  //-----------------------------
  echo JS_AJAX_OP;
  $a=new Anc_Operation($cn);

echo '
<div class="u_redcontent">
<form method= "get">
';

 echo dossier::hidden();
 $hid=new widget("hidden");
 
 $hid->name="p_action";
 $hid->value="ca_od";
 echo $hid->IOValue();
 
 $hid->name="see";
 $hid->value="";
 echo $hid->IOValue();

 $w=new widget("select");
 $w->name="p_periode";
 // filter on the current year
 $filter_year=" where p_exercice='".$User->getExercice()."'";
 
 $periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by  p_start,p_end",1);
 $User=new cl_user($cn);
 $current=(isset($_GET['p_periode']))?$_GET['p_periode']:$User->GetPeriode();
 $w->selected=$current;
 
 echo 'P&eacute;riode  '.$w->IOValue("p_periode",$periode_start).$w->Submit('gl_submit','Valider').'</form>';

  echo '<div class="u_redcontent">';
  echo $a->html_table($current);
   echo '</div>';
  exit();
 }
if ( isset($_POST['save'])) {
  // record the operation and exit
  // and exit
  //-----------------------------
  echo '<div class="u_redcontent">'.
	'Op&eacute;ration sauv&eacute;e';
  $a=new Anc_Group_Operation($cn);

  $a->load_from_array($_POST);

  $a->save();
  echo $a->show();
  echo '</div>';
  exit();
 }

if ( isset($_GET['new'])) {
	//show the form for entering a new Anc_Operation
	//------------------------------------------
  $a=new Anc_Group_Operation($cn);
  echo JS_CAOD_COMPUTE;
  $wSubmit=new widget('hidden',"p_action","ca_od");
  $wSubmit->table=0;
  echo '<div class="u_redcontent">';
  echo '<form method="post">';
  echo dossier::hidden();
  echo $wSubmit->IOValue();
  echo $a->form();
  echo $wSubmit->Submit("save","Sauver");
  echo '</form>';
  echo '<div class="info">
    D&eacute;bit = <span id="totalDeb"></span>
    Cr&eacute;dit = <span id="totalCred"></span>
    Difference = <span id="totalDiff"></span>
  </div>
    ';

  echo '</div>';
  exit();
  }

?>
<div class="u_redcontent">
