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
include_once("class_widget.php");
////////////////////////////////////////////////////////////////////////////////
// If print is asked
// First time in html
// after in pdf or cvs
////////////////////////////////////////////////////////////////////////////////
if ( isset( $_POST['bt_html'] ) ) {
include("class_form.php");
  $Form=new formulaire($cn,$_POST['form_id']);
  $Form->GetName();
  $array=$Form->GetRow( $_POST['from_periode'],
			$_POST['to_periode']
			);

  $rep="";
  $submit=new widget();
  $hid=new widget("hidden");
  echo '<div class="u_redcontent">';
  $t=($_POST['from_periode']==$_POST['to_periode'])?"":" -> ".getPeriodeName($cn,$_POST['to_periode'],'p_end');
  echo '<h2 class="info">'.$Form->id." ".$Form->name.
    " - ".getPeriodeName($cn,$_POST['from_periode'],'p_start').
    " ".$t.
    '</h2>';
  echo "<table>";
  echo '<TR>';
  echo '<TD><form method="GET" ACTION="user_impress.php">'.
    $submit->Submit('bt_other',"Autre Formulaire").
    $hid->IOValue("type","form")."</form></TD>";

  echo '<TD><form method="POST" ACTION="form_pdf.php">'.
    $submit->Submit('bt_pdf',"Export PDF").
    $hid->IOValue("type","form").
    $hid->IOValue("form_id",$Form->id).
    $hid->IOValue("from_periode",$_POST['from_periode']).
    $hid->IOValue("to_periode",$_POST['to_periode']);

  echo "</form></TD>";
  echo '<TD><form method="POST" ACTION="form_csv.php">'.
    $submit->Submit('bt_csv',"Export CSV").
    $hid->IOValue("type","form").
    $hid->IOValue("form_id",$Form->id).
    $hid->IOValue("from_periode",$_POST['from_periode']).
    $hid->IOValue("to_periode",$_POST['to_periode']);

  echo "</form></TD>";

  echo "</TR>";

  echo "</table>";
  if ( count($Form->row ) == 0 ) 
  	exit;

  echo "<TABLE width=\"100%\">";
      echo "<TR>".
	"<TH> Description </TH>".
	"<TH> montant </TH>".
	"</TR>";

  foreach ( $Form->row as $op ) { 
      echo "<TR>".
	"<TD>".$op['desc']."</TD>".
	"<TD align=\"right\">".sprintf("% 8.2f",$op['montant'])."</TD>".
	"</TR>";
    
  }
  echo "</table>";
  echo "</div>";
  exit;
}

////////////////////////////////////////////////////////////////////////////////
// Show the jrn and date
////////////////////////////////////////////////////////////////////////////////
include_once('form_input.php');
include_once("postgres.php");
$ret=make_array($cn,"select fr_id,fr_label
                 from formdef
                 order by fr_label");
////////////////////////////////////////////////////////////////////////////////
// Form
////////////////////////////////////////////////////////////////////////////////
echo '<div class="redcontent">';
echo '<FORM ACTION="?type=form" METHOD="POST">';
echo '<TABLE><TR>';
$w=new widget("select");
$w->table=1;
$w->label="Choississez le formulaire";
print $w->IOValue("form_id",$ret);
print '</TR>';
print '<TR>';
$periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode order by p_id");
$w->label="Depuis";
print $w->IOValue('from_periode',$periode_start);
$w->label=" jusqu'� ";
$periode_end=make_array($cn,"select p_id,to_char(p_end,'DD-MM-YYYY') from parm_periode order by p_id");
print $w->IOValue('to_periode',$periode_end);
print "</TR>";
echo '</TABLE>';
print $w->Submit('bt_html','Impression');

echo '</FORM>';
echo '</div>';
?>
