
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
include_once("preference.php");
include_once ("class_widget.php");
include_once("class_acc_balance.php");
$User->can_request($cn,BALANCE);


echo '<div class="u_content">';

//-----------------------------------------------------
// Form
//-----------------------------------------------------
// Show the export button
if ( isset ($_POST['view']  ) ) {
  $submit=new widget();
  $hid=new widget("hidden");

  echo "<table>";
  echo '<TR>';
  echo '<TD><form method="POST" ACTION="print_balance.php">'.
	dossier::hidden().
    $submit->Submit('bt_pdf',"Export PDF").
    $hid->IOValue("p_action","impress").
    $hid->IOValue("from_periode",$_POST['from_periode']).
    $hid->IOValue("to_periode",$_POST['to_periode']).
    $hid->IOValue("p_jrn",$_POST['p_jrn']).
    $hid->IOValue("from_poste",$_POST['from_poste']).
    $hid->IOValue("to_poste",$_POST['to_poste']);
  echo "</form></TD>";
  echo '<TD><form method="POST" ACTION="bal_csv.php">'.
    $submit->Submit('bt_csv',"Export CSV").
	dossier::hidden().
    $hid->IOValue("p_action","impress").
    $hid->IOValue("from_periode",$_POST['from_periode']).
    $hid->IOValue("to_periode",$_POST['to_periode']).
    $hid->IOValue("p_jrn",$_POST['p_jrn']).
    $hid->IOValue("from_poste",$_POST['from_poste']).
    $hid->IOValue("to_poste",$_POST['to_poste']);

  echo "</form></TD>";

  echo "</TR>";

  echo "</table>";
}

// Show the form for period
echo '<FORM action="?p_action=impress&type=bal" method="post">';
echo dossier::hidden();
$w=new widget("select");
$w->table=1;
// filter on the current year
$filter_year=" where p_exercice='".$User->get_exercice()."'";

$periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by p_start,p_end");
$w->label="Depuis";
if ( isset ($_POST['from_periode']) )
  $w->selected=$_POST['from_periode'];

echo $w->IOValue('from_periode',$periode_start);
$w->label=" jusqu'à ";
$periode_end=make_array($cn,"select p_id,to_char(p_end,'DD-MM-YYYY') from parm_periode $filter_year order by p_start,p_end");
if ( isset ($_POST['to_periode']) )
  $w->selected=$_POST['to_periode'];

echo $w->IOValue('to_periode',$periode_end);
//-------------------------------------------------
$journal=new widget("select");
$journal->name="p_jrn";
$journal->value=make_array($cn,"select jrn_def_id as value, jrn_def_name as label from jrn_Def",1);
if ( isset($_POST['p_jrn'])) $journal->selected=$_POST['p_jrn'];
echo JS_SEARCH_POSTE;
echo "Journal = ".$journal->IOValue();
$from_poste=new widget("js_search_poste");
$from_poste->name="from_poste";
$from_poste->extra2=null;
$from_poste->value=(isset($_POST['from_poste']))?$_POST['from_poste']:"";
$from_span=new widget("span","from_poste_label","from_poste_label");

$to_poste=new widget("js_search_poste");
$to_poste->name="to_poste";
$to_poste->extra2=null;
$to_poste->value=(isset($_POST['to_poste']))?$_POST['to_poste']:"";
$to_span=new widget("span","to_poste_label","to_poste_label");
$c=new widget("checkbox");
$c->label="centralisé";
echo $c->IOValue('central');

echo "<div>";
echo "Plage de postes :".$from_poste->IOValue();
echo $from_span->IOValue();
echo " jusque :".$to_poste->IOValue();
echo $to_span->IOValue();
echo "</div>";
//$a=FormPeriodeMult($cn);
//echo $a;

echo '<input type="submit" name="view" value="ok">';


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
    $a=get_periode($cn,$_POST['from_periode']);
    $b=get_periode($cn,$_POST['to_periode']);
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
    echo '<TD>'.$r['poste'].'</TD>';
    echo '<TD>'.$r['label'].'</TD>';
    echo '<TD>'.$r['sum_deb'].'</TD>';
    echo '<TD>'.$r['sum_cred'].'</TD>';
    echo '<TD>'.$r['solde_deb'].'</TD>';
    echo '<TD>'.$r['solde_cred'].'</TD>';
    echo '</TR>';
  }
  echo '</table>';

 }// end submit
  echo "</div>";
?>
