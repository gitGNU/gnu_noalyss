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
/*! \file
 * \brief ask for Printing the ledger (pdf,html)
 */

include_once("class_widget.php");
require_once('class_dossier.php');
$gDossier=dossier::id();

//-----------------------------------------------------
// If print is asked
// First time in html
// after in pdf or cvs
//-----------------------------------------------------
if ( isset( $_POST['bt_html'] ) ) {
include("class_acc_ledger.php");
 $p_cent=( isset ( $_POST['cent']) )?'on':'off';
  // $POST=from_periode, to_periode, jrn_id, cent
 $d=var_export($_POST,true);
 echo_debug('impress_jrn.php',__LINE__,$d);
  $Jrn=new Acc_Ledger($cn,$_POST['jrn_id']);
  $Jrn->GetName();
  if ( $_POST['p_simple']==0 ) 
    {
      $Jrn->GetRow( $_POST['from_periode'],
		    $_POST['to_periode'],
		    $p_cent);
    }
  else 
    {
      $Row=$Jrn->GetRowSimple($_POST['from_periode'],
			 $_POST['to_periode'],
			 $p_cent);
      //      var_dump($Row);
    }
  $rep="";
  $submit=new widget();
  $hid=new widget("hidden");
  echo '<div class="u_content">';
  echo '<h2 class="info">'.$Jrn->name.'</h2>';
  echo "<table>";
  echo '<TR>';
  echo '<TD><form method="GET" ACTION="?">'.dossier::hidden().
    $submit->Submit('bt_other',"Autre journal").
    $hid->IOValue("type","jrn").$hid->IOValue('p_action','impress')."</form></TD>";

  echo '<TD><form method="GET" ACTION="jrn_pdf.php">'.dossier::hidden().
    $submit->Submit('bt_pdf',"Export PDF").
    $hid->IOValue("type","jrn").
    $hid->IOValue("p_action","impress").
    $hid->IOValue("central",$p_cent).
    $hid->IOValue("jrn_id",$Jrn->id).
    $hid->IOValue("from_periode",$_POST['from_periode']).
    $hid->IOValue("to_periode",$_POST['to_periode']);
  echo $hid->IOValue("p_simple",$_POST['p_simple']);

  echo "</form></TD>";
  echo '<TD><form method="GET" ACTION="jrn_csv.php">'.dossier::hidden().
    $submit->Submit('bt_csv',"Export CSV").
    $hid->IOValue("type","jrn").
    $hid->IOValue("p_action","impress").
    $hid->IOValue("central",$p_cent).
    $hid->IOValue("jrn_id",$Jrn->id).
    $hid->IOValue("from_periode",$_POST['from_periode']).
    $hid->IOValue("to_periode",$_POST['to_periode']);
  echo $hid->IOValue("p_simple",$_POST['p_simple']);
  echo "</form></TD>";

  echo "</TR>";

  echo "</table>";
  if ( count($Jrn->row ) == 0 
       && $Row==null) 
  	exit;

  echo '<TABLE class="result">';

  if ( $_POST['p_simple'] == 0 ) {
    // detailled printing
    //---
    foreach ( $Jrn->row as $op ) { 
      echo "<TR>";
      // centralized
      if ( $p_cent == 'on') {
	echo "<TD>".$op['j_id']."</TD>";
      }
      echo "<TD>".$op['internal']."</TD>".
	"<TD>".$op['j_date']."</TD>".
	"<TD>".$op['poste']."</TD>".
	"<TD>".$op['description']."</TD>".
	"<TD>".$op['deb_montant']."</TD>".
	"<TD>".$op['cred_montant']."</TD>".
	"</TR>";
    }// end loop
  } // if
  else 
    {
      include_once("jrn.php");
      // Simple printing
      //---

      echo "<TR>".
	"<th> operation </td>".
	"<th>Date</th>".
	"<th> commentaire </th>".
	"<th>internal</th>".
	/* "<th>Pièce justificative</th>". */
	"<th> montant</th>".
	"</TR>";

      foreach ($Row as $line)
	{
	  echo "<tr>";
	  echo "<TD>".$line['num']."</TD>";
	  echo "<TD>".$line['date']."</TD>";
	  echo "<TD>".$line['comment']."</TD>";
	  echo "<TD>".$line['jr_internal']."</TD>";
	  //	  echo "<TD>".$line['pj']."</TD>";
	// If the ledger is financial :
	// the credit must be negative and written in red
  	// Get the jrn type
	if ( $line['jrn_def_type'] == 'FIN' ) {
	  $positive = CountSql($cn,"select * from jrn inner join jrnx on jr_grpt_id=j_grpt ".
		   " where jr_id=".$line['jr_id']." and (j_poste like '55%' or j_poste like '57%' )".
			       " and j_debit='f'");
	
        echo "<TD align=\"right\">";
	echo ( $positive != 0 )?"<font color=\"red\">  - ".sprintf("%8.2f",$line['montant'])."</font>":sprintf("%8.2f",$line['montant']);
	echo "</TD>";
	}
	else 
	  {
	    echo "<TD align=\"right\">".sprintf("% 8.2f",$line['montant'])."</TD>";
	  }

	  echo "</tr>";
	}
      
    } //else
  echo "</table>";
  // show the saldo
  
  $solde=$Jrn->get_solde( $_POST['from_periode'],
						  $_POST['to_periode'],
						  $p_cent);
  echo "solde d&eacute;biteur:".$solde[0]."<br>";
  echo "solde cr&eacute;diteur:".$solde[1];
  
  echo "</div>";
  exit;
}

//-----------------------------------------------------
// Show the jrn and date
//-----------------------------------------------------
include_once("postgres.php");

if ( $User->Admin() ==0) {
  $ret=make_array($cn,"select jrn_def_id,jrn_def_name
                             from jrn_def join jrn_type on jrn_def_type=jrn_type_id
                             join user_sec_jrn on uj_jrn_id=jrn_def_id 
                             where
                             uj_login='$User->id'
                             and uj_priv !='X'
                             ");
    } else {
  $ret=make_array($cn,"select jrn_def_id,jrn_def_name
                             from jrn_def join jrn_type on jrn_def_type=jrn_type_id");

 } 

// Count the forbidden journaux
    $NoPriv=CountSql($cn,"select jrn_def_id,jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_type_id,jrn_desc,uj_priv,
                                jrn_deb_max_line,jrn_cred_max_line
                             from jrn_def join jrn_type on jrn_def_type=jrn_type_id
                             join  user_sec_jrn on uj_jrn_id=jrn_def_id 
                             where  
                             uj_login='$User->id'
                             and uj_priv ='X'
                   ");
    // Pour voir tout les journal ?
    if ( $NoPriv == 0 ) {
      $a=count($ret);
      $all=array('value'=>0,'label'=>'Grand Livre');
      $ret[$a]=$all;
   }
if ( count($ret) < 1 ) 
  NoAccess();
//-----------------------------------------------------
// Form
//-----------------------------------------------------
echo '<div class="u_content">';
echo '<FORM ACTION="?p_action=impress&type=jrn" METHOD="POST">'.dossier::hidden();
echo '<TABLE width="90%" align="center"><TR>';
$w=new widget("select");
$w->table=1;
$w->label="Choississez le journal";
print $w->IOValue("jrn_id",$ret);
print '</TR>';
print '<TR>';
// filter on the current year
$filter_year=" where p_exercice='".$User->getExercice()."'";

$periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by p_start,p_end");
$w->label="Depuis";
print $w->IOValue('from_periode',$periode_start);
$w->label=" jusqu'à ";
$periode_end=make_array($cn,"select p_id,to_char(p_end,'DD-MM-YYYY') from parm_periode $filter_year order by p_start,p_end");
print $w->IOValue('to_periode',$periode_end);
print "</TR><TR>";
$centralise=new widget("checkbox");
$centralise->label="Depuis les journaux centralisés";
$centralise->table=1;
print $centralise->IOValue('cent');
$a=array(
	 array('value'=>0,'label'=>'Detaillé'),
	 array('value'=>1,'label'=>'Simple')
	 );
$w->selected=1;
echo $w->IOValue('p_simple',$a,'Style d\'impression');
print "</TR>";
echo '</TABLE>';
print $w->Submit('bt_html','Impression');

echo '</FORM>';
  echo '<span class="notice"> Attention : en-cas d\'impression de journaux centralis&eacute;s, dans le PDF, les montants d&eacute;bit et cr&eacute;dit calcul&eacute;s  par page sont la somme des montants de la page uniquement. Si une op&eacute;ration est sur 2 pages ces montants diff&egrave;reront évidemment. Ces montants doivent &ecirc;tre &eacute;gaux sur la derni&egrave;re page. Pour v&eacute;rifier la balance, utilisez la balance des comptes ou Avanc&eacute;->V&eacute;rification</span>';

echo '</div>';
?>
