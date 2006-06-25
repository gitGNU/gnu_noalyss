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
include_once("class_widget.php");
/*! \file
 * \brief Print account (html or pdf)
 *        file included from user_impress
 *
 * some variable are already defined $cn, $User ...
 * 
 */

////////////////////////////////////////////////////////////////////////////////
// If print is asked
// First time in html
// after in pdf or cvs
////////////////////////////////////////////////////////////////////////////////
if ( isset( $_POST['bt_html'] ) ) {
include("class_poste.php");
  $Poste=new poste($cn,$_POST['poste_id']);
  $Poste->GetName();
  list($array,$tot_deb,$tot_cred)=$Poste->GetRow( $_POST['from_periode'],
						  $_POST['to_periode']
						  );

  $rep="";
  $submit=new widget();
  $hid=new widget("hidden");
  echo '<div class="u_content">';
  echo '<h2 class="info">'.$Poste->id." ".$Poste->name.'</h2>';
  echo "<table >";
  echo '<TR>';
  echo '<TD><form method="GET" ACTION="user_impress.php">'.
    $submit->Submit('bt_other',"Autre poste").
    $hid->IOValue("type","poste")."</form></TD>";

  echo '<TD><form method="POST" ACTION="poste_pdf.php">'.
    $submit->Submit('bt_pdf',"Export PDF").
    $hid->IOValue("type","poste").
    $hid->IOValue("poste_id",$Poste->id).
    $hid->IOValue("from_periode",$_POST['from_periode']).
    $hid->IOValue("to_periode",$_POST['to_periode']);

  echo "</form></TD>";
  echo '<TD><form method="POST" ACTION="poste_csv.php">'.
    $submit->Submit('bt_csv',"Export CSV").
    $hid->IOValue("type","poste").
    $hid->IOValue("poste_id",$Poste->id).
    $hid->IOValue("from_periode",$_POST['from_periode']).
    $hid->IOValue("to_periode",$_POST['to_periode']);

  echo "</form></TD>";

  echo "</TR>";

  echo "</table>";
  if ( count($Poste->row ) == 0 ) 
  	exit;

  echo "<TABLE class=\"result\" width=\"100%\">";
      echo "<TR>".
	"<TH> Code interne </TH>".
	"<TH> Date</TH>".
	"<TH> Description </TH>".
	"<TH> Débit  </TH>".
	"<TH> Crédit </TH>".
	"</TR>";

  foreach ( $Poste->row as $op ) { 
      echo "<TR>".
	"<TD>".$op['jr_internal']."</TD>".
	"<TD>".$op['j_date']."</TD>".
	"<TD>".$op['description']."</TD>".
	"<TD>".$op['deb_montant']."</TD>".
	"<TD>".$op['cred_montant']."</TD>".
	"</TR>";
    
  }
  $solde_type=($tot_deb>$tot_cred)?"solde débiteur":"solde créditeur";
  $diff=abs($tot_deb-$tot_cred);
  echo "<TR>".
    "<TD>$solde_type</TD>".
    "<TD>$diff</TD>".
    "<TD></TD>".
    "<TD>$tot_deb</TD>".
    "<TD>$tot_cred</TD>".
    "</TR>";

  echo "</table>";
  echo "</div>";
  exit;
}

////////////////////////////////////////////////////////////////////////////////
// Show the jrn and date
////////////////////////////////////////////////////////////////////////////////
include_once("postgres.php");
$ret=make_array($cn,"select distinct j_poste::text ,j_poste::text||'  '||pcm_lib
                 from jrnx inner join tmp_pcmn on (pcm_val=j_poste)
                 order by j_poste::text");
////////////////////////////////////////////////////////////////////////////////
// Form
////////////////////////////////////////////////////////////////////////////////
if ( $ret==null) 
     exit('Les journaux sont vides');
echo '<div class="u_content">';
echo '<FORM ACTION="?type=poste" METHOD="POST">';
echo '<TABLE><TR>';
$w=new widget("select");
$w->table=1;
$w->label="Choississez le poste";
print $w->IOValue("poste_id",$ret);
print '</TR>';
print '<TR>';
// filter on the current year
$filter_year=" where p_exercice='".$User->getExercice()."'";
$periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by p_id");
$w->label="Depuis";
print $w->IOValue('from_periode',$periode_start);
$w->label=" jusqu'à ";
$periode_end=make_array($cn,"select p_id,to_char(p_end,'DD-MM-YYYY') from parm_periode order $filter_year by p_id");
print $w->IOValue('to_periode',$periode_end);
print "</TR>";
print "<TR><TD>";
$all=new widget("checkbox");
$all->label="Tous les postes";
$all->disabled=true;
echo $all->IOValue("poste_id","");
echo '</TABLE>';
print $w->Submit('bt_html','Impression');

echo '</FORM>';
echo '</div>';
?>
