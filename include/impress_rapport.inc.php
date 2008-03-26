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
if ( isset( $_GET['bt_html'] ) ) {
  require_once("class_acc_report.php");
  $Form=new rapport($cn,$_GET['form_id']);
  $Form->get_name();
  // step asked ?
  //--
  if ($_GET['p_step'] == 0 ) {
	if ( $_GET ['type_periode'] == 0 )
	  $array=$Form->get_row( $_GET['from_periode'],$_GET['to_periode'], $_GET['type_periode']);
	else 
	  $array=$Form->get_row( $_GET['from_date'],$_GET['to_date'], $_GET['type_periode']);
  } else {
    // step are asked
    //--
    for ($e=$_GET['from_periode'];$e<=$_GET['to_periode'];$e+=$_GET['p_step'])
      {

		$periode=getPeriodeName($cn,$e);
		if ( $periode == null ) continue;
		$array[]=$Form->get_row($e,$e,$_GET['type_periode']);
		$periode_name[]=$periode;
      }
  }


  $rep="";
  $submit=new widget();
  $hid=new widget("hidden");
  echo '<div class="u_content">';
  if ( $_GET['type_periode'] == 0) {
	$t=($_GET['from_periode']==$_GET['to_periode'])?"":" -> ".getPeriodeName($cn,$_GET['to_periode'],'p_end');
	echo '<h2 class="info">'.$Form->id." ".$Form->name.
	  " - ".getPeriodeName($cn,$_GET['from_periode'],'p_start').
	  " ".$t.
	  '</h2>';
  } else {
	echo '<h2 class="info">'.$Form->id." ".$Form->name.
	  ' Date :'.
	  $_GET['from_date'].
	  " au ".
	  $_GET['to_date'].
	  '</h2>';
  }
	echo '<table >';
  echo '<TR>';
  echo '<TD><form method="GET" ACTION="?">'.
	dossier::hidden().
    widget::submit('bt_other',"Autre Rapport").
    $hid->IOValue("type","rapport").$hid->IOValue("p_action","impress")."</form></TD>";

  echo '<TD><form method="GET" ACTION="form_pdf.php">'.
    widget::submit('bt_pdf',"Export PDF").
	dossier::hidden().
    $hid->IOValue("type","rapport").
    $hid->IOValue("p_action","impress").
    $hid->IOValue("form_id",$Form->id).
    $hid->IOValue("from_periode",$_GET['from_periode']).
    $hid->IOValue("to_periode",$_GET['to_periode']).
    $hid->IOValue("p_step",$_GET['p_step']).
    $hid->IOValue("from_date",$_GET['from_date']).
	$hid->IOValue("to_date",$_GET['to_date']).
	$hid->IOValue("type_periode",$_GET['type_periode']);



  echo "</form></TD>";
  echo '<TD><form method="GET" ACTION="form_csv.php">'.
    widget::submit('bt_csv',"Export CSV").
	dossier::hidden().
    $hid->IOValue("type","form").
    $hid->IOValue("p_action","impress").
    $hid->IOValue("form_id",$Form->id).
    $hid->IOValue("from_periode",$_GET['from_periode']).
    $hid->IOValue("to_periode",$_GET['to_periode']).
    $hid->IOValue("p_step",$_GET['p_step']).
    $hid->IOValue("from_date",$_GET['from_date']).
    $hid->IOValue("to_date",$_GET['to_date']).
	$hid->IOValue("type_periode",$_GET['type_periode']);

  echo "</form></TD>";

  echo "</TR>";

  echo "</table>";
  if ( count($Form->row ) == 0 ) 
  	exit;

      if ( $_GET['p_step'] == 0) 
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
echo '<FORM METHOD="GET">';
$hidden=new widget("hidden");
echo $hidden->IOValue("p_action","impress");
echo $hidden->IOValue("type","rapport");
echo 	dossier::hidden();

echo '<TABLE border="2"><TR>';
$w=new widget("select");
$w->table=1;
$w->label="Choississez le rapport";
print $w->IOValue("form_id",$ret);
print '</TR>';
print '<TR>';
// filter on the current year
$filter_year=" where p_exercice='".$User->get_exercice()."'";

$periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by p_start,p_end");
$w->label="P&eacute;riode comptable : Depuis";
print $w->IOValue('from_periode',$periode_start);
$w->label=" jusqu'à ";
$periode_end=make_array($cn,"select p_id,to_char(p_end,'DD-MM-YYYY') from parm_periode  $filter_year order by p_start,p_end");
print $w->IOValue('to_periode',$periode_end);
print "</TR>";
//--- by date
$date=new widget('js_date');
$date->table=1;
$date->label="Calendrier depuis :";
echo $date->IOValue('from_date');
$date->label="jusque";
echo $date->IOValue('to_date');
//-- calendrier ou periode comptable
$aCal=array(
			   array('value'=>0,'label'=>'P&eacute;riode comptable'),
			   array('value'=>1,'label'=>'Calendrier')
			   );
$w->label='Type de date : ';
echo '<tr>';
echo $w->IOValue('type_periode',$aCal);
echo '</Tr>';
$aStep=array(
	     array('value'=>0,'label'=>'Pas d\'étape'),
	     array('value'=>1,'label'=>'1 mois')
	     );
$w->label='Par étape de';
echo '<TR> '.$w->IOValue('p_step',$aStep);
echo '</TR>';

echo '</TABLE>';
echo '<span class="notice"> Attention : vous ne pouvez pas utiliser les &eacute;tapes avec les dates calendriers.</span>';
echo '<br>';
echo '<span class="notice"> Les clauses FROM sont ignorés avec les dates calendriers</span>';
echo '<br>';
print widget::submit('bt_html','Visualisation');

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
