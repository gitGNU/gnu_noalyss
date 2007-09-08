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

/* \brief this file is included for printing the analytic
 * accountancy. 
 *
 */
require_once('class_operation.php');
require_once('class_plananalytic.php');
require_once('ac_common.php');
require_once('class_widget.php');

//-- the menu
$menu=array(array("?p_action=ca_imp&sub=listing&$str_dossier","Listing","Listing des op&eacute;rations","listing"),
			array("?p_action=ca_imp&sub=bs&$str_dossier","Balance simple","Balance simple d'un plan analytique","bs"),
			array("?p_action=ca_imp&sub=bc2&$str_dossier","Balance crois&eacute;","Balance crois&eacute; de 2 plans analytiques","bc2")
			);
$sub=(isset($_GET['sub']))?$_GET['sub']:'no';
echo ShowItem($menu,"H","mtitle","mtitle",$sub);

$hidden=new widget("hidden");
$str_hidden=$hidden->IOValue("p_action","ca_imp");
$str_hidden.=$hidden->IOValue("sub",$sub);

// select following the sub action
//------------------------------------------------------------------------------
// listing
if ( $sub=='listing') {
  $from=new widget  ('text','from','from');
  $from->size=10;
  $from->value=(isset($_GET['from']))?$_GET['from']:"";

  $to=new widget('text','to','to');
  $to->value=(isset($_GET['to']))?$_GET['to']:"";
  $to->size=10;

  $plan_id=new widget("select","pa_id","pa_id");
  $plan_id->selected=(isset($_GET['pa_id']))?$_GET['pa_id']:"Plan 2";

  echo '<form method="get">';
  echo dossier::hidden();
  echo $hidden->IOValue("result","1");
  echo $str_hidden;
  echo "Depuis : ".$from->IOValue();
  echo "jusque : ".$to->IOValue();
  $plan=new PlanAnalytic($cn);
  $plan_id->value=make_array($cn,"select pa_id, pa_name from plan_analytique order by pa_name");
  echo "Plan Analytique :".$plan_id->IOValue();
  echo $plan_id->Submit("recherche","recherche");
  echo '</form>';
  echo '<span class="notice"> Les dates sont en format DD.MM.YYYY</span>';
  //---- result
  if ( isset($_GET['result']) ){
	echo '<div class="u_redcontent">';
	$submit=new widget();
	$submit->table=0;
	//--------------------------------
	// export Buttons 
	//---------------------------------
	echo '<form method="GET" action="ca_list_pdf.php" style="display:inline">';
	echo $str_hidden;
	echo dossier::hidden();
	echo $hidden->IOValue("to",$_GET['to']);
	echo $hidden->IOValue("to",$_GET['from']);
	echo $hidden->IOValue("to",$_GET['pa_id']);
	echo $submit->Submit('bt_pdf',"Export en PDF");
	echo '</form>';

	echo '<form method="GET" action="ca_list_csv.php"  style="display:inline">';
	echo $hidden->IOValue("to",$_GET['to']);
	echo $hidden->IOValue("to",$_GET['from']);
	echo $hidden->IOValue("to",$_GET['pa_id']);
	echo $str_hidden;
	echo dossier::hidden();
	echo $submit->Submit('bt_csv',"Export en CSV");
	echo '</form>';

	//---Html
	$op=new operation ($cn);
	$op->pa_id=$_GET['pa_id'];
	$array=$op->get_list($_GET['from'],$_GET['to']);
	if ( empty($array) ) { echo "aucune donn&eacute;e"; return;}
	echo '<table>';
	echo '<tr>'.
	  '<th>Date</th>'.
	  '<th>Nom</th>'.
	  '<th>Description</th>'.
	  '<th>Montant</th>'.
	  '<th>D/C</th>'.
	  '</tr>';
	foreach ( $array as $row ) {
	  echo '<tr>';
	  echo 
		'<td>'.$row['oa_date'].'</td>'.
		'<td>'.$row['po_name'].'</td>'.
		'<td>'.$row['oa_description'].'</td>'.
		'<td>'.$row['oa_amount'].'</td>'.
		'<td>'.(($row['oa_debit']=='f')?'CREDIT':'DEBIT').'</td>';
	  echo '</tr>';
	}
	echo '</table>';
	echo '</div>';
  }
 }

//------------------------------------------------------------------------------
// Simple balance 
if ($sub == 'bs') {
 }

//------------------------------------------------------------------------------
// crossed balance
if ( $sub == 'bc2') {
 }

