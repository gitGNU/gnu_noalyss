<?php  
/*
 *   This file is part of PHPCOMPTA.
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
 *   along with PHPCOMPTA; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
// Auteur Dany De Bontridder ddebontridder@yahoo.fr
// /* $Revision$ */
/*! \file
 * \brief Show the balance and let you print it or export to PDF
 *        file included by user_impress
 *
 * some variable are already defined ($cn, $User ...)
 */

include_once ("ac_common.php");
include_once("class_acc_balance.php");
require_once("class_iselect.php");
require_once("class_iposte.php");
require_once("class_ispan.php");
require_once("class_icheckbox.php");
require_once("class_ihidden.php");
require_once('class_acc_ledger.php');
require_once('class_periode.php');

$User->can_request(IMPBAL);


echo '<div class="content">';

// Show the form for period
echo '<FORM action="?p_action=impress&type=bal" method="post">';
echo dossier::hidden();
$w=new ISelect();
$w->table=1;
// filter on the current year
/*!\todo use the new HtmlInput object : IPeriod */
$filter_year=" where p_exercice='".$User->get_exercice()."'";

$periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by p_start,p_end");
$w->label="Depuis";
if ( isset ($_POST['from_periode']) )
  $w->selected=$_POST['from_periode'];

 /*!\todo use the new HtmlInput object : IPeriod */
echo $w->input('from_periode',$periode_start);
$w->label=" jusqu'à ";
$periode_end=make_array($cn,"select p_id,to_char(p_end,'DD-MM-YYYY') from parm_periode $filter_year order by p_start,p_end");
if ( isset ($_POST['to_periode']) )
  $w->selected=$_POST['to_periode'];

echo $w->input('to_periode',$periode_end);
//-------------------------------------------------
$l=new Acc_Ledger($cn,0);
$journal=$l->select_ledger('ALL',3);

/*  add a all ledger choice */
$blank=array('value'=>-1,'label'=>'Tous les journaux ');
$array=$journal->value;
$array[]=$blank;
$journal->value=$array;

$journal->name="p_jrn";


if ( isset($_POST['p_jrn'])) $journal->selected=$_POST['p_jrn'];
	else
	$journal->selected=-1;
echo JS_SEARCH_POSTE;
echo "Journal = ".$journal->input();
$from_poste=new IPoste();
$from_poste->name="from_poste";
$from_poste->extra2=null;
$from_poste->value=(isset($_POST['from_poste']))?$_POST['from_poste']:"";
$from_span=new ISpan("from_poste_label","from_poste_label");

$to_poste=new IPoste();
$to_poste->name="to_poste";
$to_poste->extra2=null;
$to_poste->value=(isset($_POST['to_poste']))?$_POST['to_poste']:"";
$to_span=new ISpan("to_poste_label","to_poste_label");
$c=new ICheckBox();
$c->label="centralisé";
echo $c->input('central');

echo "<div>";
echo "Plage de postes :".$from_poste->input();
echo $from_span->input();
echo " jusque :".$to_poste->input();
echo $to_span->input();
echo "</div>";

echo '<input type="submit" name="view" value="Visualisation">';
echo '</form>';
echo '<hr>';
//-----------------------------------------------------
// Form
//-----------------------------------------------------
// Show the export button
if ( isset ($_POST['view']  ) ) {

  $hid=new IHidden();

  echo "<table>";
  echo '<TR>';
  echo '<TD><form method="POST" ACTION="print_balance.php">'.
	dossier::hidden().
    HtmlInput::submit('bt_pdf',"Export PDF").
    $hid->input("p_action","impress").
    $hid->input("from_periode",$_POST['from_periode']).
    $hid->input("to_periode",$_POST['to_periode']).
    $hid->input("p_jrn",$_POST['p_jrn']).
    $hid->input("from_poste",$_POST['from_poste']).
    $hid->input("to_poste",$_POST['to_poste']);
  echo "</form></TD>";
  echo '<TD><form method="POST" ACTION="bal_csv.php">'.
    HtmlInput::submit('bt_csv',"Export CSV").
	dossier::hidden().
    $hid->input("p_action","impress").
    $hid->input("from_periode",$_POST['from_periode']).
    $hid->input("to_periode",$_POST['to_periode']).
    $hid->input("p_jrn",$_POST['p_jrn']).
    $hid->input("from_poste",$_POST['from_poste']).
    $hid->input("to_poste",$_POST['to_poste']);

  echo "</form></TD>";

  echo "</TR>";

  echo "</table>";
}


//-----------------------------------------------------
// Display result
//-----------------------------------------------------
if ( isset($_POST['view'] ) ) {
  $bal=new Acc_Balance($cn);
  $bal->jrn=$_POST['p_jrn'];  
  $bal->from_poste=$_POST['from_poste'];
  $bal->to_poste=$_POST['to_poste'];

  $t_cent="";
  //$per=join(',',$periode);
  if ( isset($_POST['central']) ) {
    $bal->central='Y';
    $t_cent="centralisée";
  }
  else
  $bal->central='N';

  $row=$bal->get_row($_POST['from_periode'],
		  $_POST['to_periode']);
	$periode=new Periode($cn);
    $a=$periode->get_date_limit($_POST['from_periode']);
    $b=$periode->get_date_limit($_POST['to_periode']);
    echo "<h2 class=\"info\"> période du ".$a['p_start']." au ".$b['p_end']."</h2>";

  echo '<table width="100%">';  
  echo '<th>Poste Comptable</th>';
  echo '<th>Libell&eacute;</th>';
  echo '<th>D&eacute;bit</th>';
  echo '<th>Cr&eacute;dit</th>';
  echo '<th>Solde D&eacute;biteur </th>';
  echo '<th>Solde Cr&eacute;diteur</th>';

  $i=0;
  foreach ($row as $r) {
    $i++;
    if ( $i%2 == 0 )
      $tr="even";
    else
      $tr="odd";

    echo '<TR class="'.$tr.'">';
    echo td($r['poste']);
    echo td(h($r['label']));
    echo td($r['sum_deb']);
    echo td($r['sum_cred']);
    echo td($r['solde_deb']);
    echo td('solde_cred']);
    echo '</TR>';
  }
  echo '</table>';

 }// end submit
  echo "</div>";
?>
