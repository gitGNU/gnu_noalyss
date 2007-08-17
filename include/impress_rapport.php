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
include_once("class_widget.php");
/*! \file
 * \brief print first the report in html and propose to print it in pdf
 *        file included by user_impress
 *
 * some variable are already defined ($cn, $User ...)

 */

//-----------------------------------------------------
// If print is asked
// First time in html
// after in pdf or cvs
//-----------------------------------------------------
if ( isset( $_POST['bt_html'] ) ) {
  include("class_rapport.php");
  $Form=new rapport($cn,$_POST['form_id']);
  $Form->GetName();
  // step asked ?
  //--
  if ($_POST['p_step'] == 0 ) {
    $array=$Form->GetRow( $_POST['from_periode'],
			  $_POST['to_periode']
			  );
  } else {
    // step are asked
    //--
    for ($e=$_POST['from_periode'];$e<=$_POST['to_periode'];$e+=$_POST['p_step'])
      {

	$periode=getPeriodeName($cn,$e);
	if ( $periode == null ) continue;
	$array[]=$Form->GetRow($e,$e);
	$periode_name[]=$periode;
      }
  }


  $rep="";
  $submit=new widget();
  $hid=new widget("hidden");
  echo '<div class="u_content">';
  $t=($_POST['from_periode']==$_POST['to_periode'])?"":" -> ".getPeriodeName($cn,$_POST['to_periode'],'p_end');
  echo '<h2 class="info">'.$Form->id." ".$Form->name.
    " - ".getPeriodeName($cn,$_POST['from_periode'],'p_start').
    " ".$t.
    '</h2>';
  echo '<table >';
  echo '<TR>';
  echo '<TD><form method="GET" ACTION="?">'.
	dossier::hidden().
    $submit->Submit('bt_other',"Autre Rapport").
    $hid->IOValue("type","rapport").$hid->IOValue("p_action","impress")."</form></TD>";

  echo '<TD><form method="POST" ACTION="form_pdf.php">'.
    $submit->Submit('bt_pdf',"Export PDF").
	dossier::hidden().
    $hid->IOValue("type","rapport").
    $hid->IOValue("p_action","impress").
    $hid->IOValue("form_id",$Form->id).
    $hid->IOValue("from_periode",$_POST['from_periode']).
    $hid->IOValue("to_periode",$_POST['to_periode']).
    $hid->IOValue("p_step",$_POST['p_step']);


  echo "</form></TD>";
  echo '<TD><form method="POST" ACTION="form_csv.php">'.
    $submit->Submit('bt_csv',"Export CSV").
	dossier::hidden().
    $hid->IOValue("type","form").
    $hid->IOValue("p_action","impress").
    $hid->IOValue("form_id",$Form->id).
    $hid->IOValue("from_periode",$_POST['from_periode']).
    $hid->IOValue("to_periode",$_POST['to_periode']).
    $hid->IOValue("p_step",$_POST['p_step']);

  echo "</form></TD>";

  echo "</TR>";

  echo "</table>";
  if ( count($Form->row ) == 0 ) 
  	exit;

      if ( $_POST['p_step'] == 0) 
	{ // check the step
	  // show tables
	  ShowReportResult($Form->row);
	} 
	else
	  {
	    $a=0;
	    foreach ( $array as $e) {
	      echo '<h2 class="info">Periode : '.$periode_name[$a]."</h2>";
	      $a++;
	      ShowReportResult($e);
	    }
	  }

  echo "</div>";
  exit;
}

//-----------------------------------------------------
// Show the jrn and date
//-----------------------------------------------------
include_once("postgres.php");
$ret=make_array($cn,"select fr_id,fr_label
                 from formdef
                 order by fr_label");
if ( sizeof($ret) == 0 ) {
  echo "Aucun Rapport";
  return;
 }
//-----------------------------------------------------
// Form
//-----------------------------------------------------
echo '<div class="u_content">';
echo '<FORM ACTION="?p_action=impress&type=rapport" METHOD="POST">';
echo 	dossier::hidden();

echo '<TABLE><TR>';
$w=new widget("select");
$w->table=1;
$w->label="Choississez le rapport";
print $w->IOValue("form_id",$ret);
print '</TR>';
print '<TR>';
// filter on the current year
$filter_year=" where p_exercice='".$User->getExercice()."'";

$periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by p_start,p_end");
$w->label="Depuis";
print $w->IOValue('from_periode',$periode_start);
$w->label=" jusqu'à ";
$periode_end=make_array($cn,"select p_id,to_char(p_end,'DD-MM-YYYY') from parm_periode  $filter_year order by p_start,p_end");
print $w->IOValue('to_periode',$periode_end);
print "</TR>";
$aStep=array(
	     array('value'=>0,'label'=>'Pas d\'étape'),
	     array('value'=>1,'label'=>'1 mois')
	     );
$w->label='Par étape de';
echo '<TR> '.$w->IOValue('p_step',$aStep);
echo '</TR>';

echo '</TABLE>';
print $w->Submit('bt_html','Impression');

echo '</FORM>';
echo '</div>';
//-----------------------------------------------------
// Function
//-----------------------------------------------------
 function ShowReportResult($p_array) {
   
   echo '<TABLE class="result">';
   echo "<TR>".
     "<TH> Description </TH>".
     "<TH> montant </TH>".
     "</TR>";
   $i=0;
   foreach ( $p_array as $op ) { 
     $i++;
     $class= ( $i % 2 == 0 )?' class="odd"':"";
   
     echo "<TR $class>".
       "<TD>".$op['desc']."</TD>".
       "<TD align=\"right\">".sprintf("% 8.2f",$op['montant'])."</TD>".
       "</TR>";
   }
   echo "</table>";

 }

?>
