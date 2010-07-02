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

require_once("class_ihidden.php");
require_once("class_iselect.php");
require_once("class_icheckbox.php");

require_once('class_dossier.php');
$gDossier=dossier::id();
//-----------------------------------------------------
// Show the jrn and date
//-----------------------------------------------------
require_once('class_database.php');

if ( $User->Admin() == 0 && $User->is_local_admin()==0) {
  $sql="select jrn_def_id,jrn_def_name
                             from jrn_def join jrn_type on jrn_def_type=jrn_type_id
                             join user_sec_jrn on uj_jrn_id=jrn_def_id 
                             where
                             uj_login='$User->login'
                             and uj_priv !='X'
                             ";
  $ret=$cn->make_array($sql);
} else {
  $ret=$cn->make_array("select jrn_def_id,jrn_def_name
                             from jrn_def join jrn_type on jrn_def_type=jrn_type_id");

 } 

// Count the forbidden journaux
    $NoPriv=$cn->count_sql("select jrn_def_id,jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_type_id,jrn_desc,uj_priv,
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

echo '<div class="content">';
echo '<FORM METHOD="GET">'.dossier::hidden();
echo HtmlInput::hidden('p_action','impress');
echo HtmlInput::hidden('type','jrn');

echo '<TABLE  ><TR>';
$w=new ISelect();
$w->table=1;
$label="Choississez le journal";
$w->selected=(isset($_GET['jrn_id']))?$_GET['jrn_id']:'';
print td($label).$w->input("jrn_id",$ret);
print '</TR>';
print '<TR>';
// filter on the current year
$filter_year=" where p_exercice='".$User->get_exercice()."'";

$periode_start=$cn->make_array("select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by p_start,p_end");
$w->selected=(isset($_GET['from_periode']))?$_GET['from_periode']:'';
print td('Depuis').$w->input('from_periode',$periode_start);
print '</TR>';
print '<TR>';

$periode_end=$cn->make_array("select p_id,to_char(p_end,'DD-MM-YYYY') from parm_periode $filter_year order by p_start,p_end");
$w->selected=(isset($_GET['to_periode']))?$_GET['to_periode']:'';
print td('Jusque ').$w->input('to_periode',$periode_end);
print "</TR><TR>";
$a=array(
	 array('value'=>0,'label'=>'Detaillé'),
	 array('value'=>1,'label'=>'Simple')
	 );
$w->selected=1;
print '</TR>';
print '<TR>';
$w->selected=(isset($_GET['p_simple']))?$_GET['p_simple']:'';
echo td('Style d\'impression').$w->input('p_simple',$a);
print "</TR>";
echo '</TABLE>';
print HtmlInput::submit('bt_html','Visualisation');

echo '</FORM>';



//-----------------------------------------------------
// If print is asked
// First time in html
// after in pdf or cvs
//-----------------------------------------------------
if ( isset( $_REQUEST['bt_html'] ) ) {
require_once("class_acc_ledger.php");

 $d=var_export($_GET,true);
  $Jrn=new Acc_Ledger($cn,$_GET['jrn_id']);
  $Jrn->get_name();
  if ( $_GET['p_simple']==0 ) 
    {
      $Row=$Jrn->get_row( $_GET['from_periode'],
		    $_GET['to_periode']
		    );
    }
  else 
    {
      $Row=$Jrn->get_rowSimple($_GET['from_periode'],
			 $_GET['to_periode']
			 );
    }
  $rep="";
  $hid=new IHidden();
  echo '<div class="content">';
  echo '<h2 class="info">'.h($Jrn->name).'</h2>';
  echo "<table>";
  echo '<TR>';
  echo '<TD><form method="GET" ACTION="?">'.dossier::hidden().
    $hid->input("type","jrn").$hid->input('p_action','impress')."</form></TD>";

  echo '<TD><form method="GET" ACTION="jrn_pdf.php">'.dossier::hidden().
    HtmlInput::submit('bt_pdf',"Export PDF").
    $hid->input("type","jrn").
    $hid->input("p_action","impress").
    $hid->input("jrn_id",$Jrn->id).
    $hid->input("from_periode",$_GET['from_periode']).
    $hid->input("to_periode",$_GET['to_periode']);
  echo $hid->input("p_simple",$_GET['p_simple']);

  echo "</form></TD>";
  echo '<TD><form method="GET" ACTION="jrn_csv.php">'.dossier::hidden().
    HtmlInput::submit('bt_csv',"Export CSV").
    $hid->input("type","jrn").
    $hid->input("p_action","impress").
    $hid->input("jrn_id",$Jrn->id).
    $hid->input("from_periode",$_GET['from_periode']).
    $hid->input("to_periode",$_GET['to_periode']);
  echo $hid->input("p_simple",$_GET['p_simple']);
  echo "</form></TD>";

  echo "</TR>";

  echo "</table>";
  if ( count($Jrn->row ) == 0 
       && $Row==null) 
  	exit;

  echo '<TABLE class="result">';

  if ( $_GET['p_simple'] == 0 ) {
    // detailled printing
    //---
    foreach ( $Jrn->row as $op ) { 
      echo "<TR>";
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
  // set a filter for the FIN
  $a_parm_code=$cn->get_array("select p_value from parm_code where p_code in ('BANQUE','COMPTE_COURANT','CAISSE')");
  $sql_fin="(";
  $or="";
  foreach ($a_parm_code as $code) {
    $sql_fin.="$or j_poste::text like '".$code['p_value']."%'";
    $or=" or ";
  }
  $sql_fin.=")";

      foreach ($Row as $line)
	{
	  echo "<tr>";
	  echo "<TD>".$line['num']."</TD>";
	  echo "<TD>".$line['date']."</TD>";
	  echo "<TD>".h($line['comment'])."</TD>";
	  echo "<TD>".$line['jr_internal']."</TD>";
	  //	  echo "<TD>".$line['pj']."</TD>";
	// If the ledger is financial :
	// the credit must be negative and written in red
  	// Get the jrn type
	if ( $line['jrn_def_type'] == 'FIN' ) {
	  $positive = $cn->count_sql("select * from jrn inner join jrnx on jr_grpt_id=j_grpt ".
		   " where jr_id=".$line['jr_id']." and $sql_fin ".
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
  
  $solde=$Jrn->get_solde( $_GET['from_periode'],
			  $_GET['to_periode']
			  );
  echo "solde d&eacute;biteur:".$solde[0]."<br>";
  echo "solde cr&eacute;diteur:".$solde[1];
  
  echo "</div>";
  exit;
}

echo '</div>';
?>
