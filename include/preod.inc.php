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

/*!\file
 * \brief included file for managing the predefined operation
 */
require_once('class_widget.php');
require_once('postgres.php');
require_once('ac_common.php');
require_once('class_pre_operation.php');

  echo '<div class="content">';
  echo '<form method="GET">';
  $sel=new widget('select');
  $sel->name="jrn";
  $sel->value=make_array($cn,"select jrn_def_id,jrn_def_name from ".
						 " jrn_def order by jrn_def_name");
  // Show a list of ledger
  $sa=(isset($_REQUEST['sa']))?$_REQUEST['sa']:"";
  $sel->selected=$sa;
  echo 'Choississez un journal '.$sel->IOValue();
  $wCheck=new widget("checkbox");
  if ( isset($_REQUEST['direct'])) {
    $wCheck->selected=true;
  }
  echo 'Ecriture directe'.$wCheck->IOValue('direct');

  echo dossier::hidden();
  $hid=new widget("hidden");
  echo $hid->IOValue("sa","jrn");
  echo $hid->IOValue("p_action","preod");
  echo '<hr>';
  echo widget::submit('Accepter','Accepter');
  echo '</form>';

  // if $_REQUEST[sa] == del delete the predefined operation
  if ( $sa == 'del') {
	$op=new Pre_operation($cn);
	if ( isset($_REQUEST['direct']))
	  $op->od_direct='t';
	$op->od_id=$_REQUEST['od_id'];
	$op->delete();
	$sa='jrn';
  }

  // if $_REQUEST[sa] == jrn show the  predefined operation for this
  // ledger
  if ( $sa == 'jrn' ) {
	$op=new Pre_operation($cn);
	$op->set_jrn($_GET['jrn']);
	if ( isset($_GET['direct'])) {
	  $op->od_direct='true';
	} else {
	  $op->od_direct='false';
	}
	$array=$op->get_list_ledger();
	if ( empty($array) == true ) {
	  echo "Aucun enregistrement";
	  exit();
	}

	echo '<table>';
	$count=0;
	foreach ($array as $row ) {

	  if ( $count %2 == 0 )
		echo '<tr class="even">';
	  else 
		echo '<tr>';
	  echo '<td>'.$row['od_name'].'</td>';
	  echo '<td>';
	  echo '<form method="POST">';
	  echo dossier::hidden();
	  echo $hid->IOValue("sa","del");
	  echo $hid->IOValue("p_action","preod");
	  echo $hid->IOValue("del","");
	  echo $hid->IOValue("od_id",$row['od_id']);
	  echo $hid->IOValue("jrn",$_GET['jrn']);

	  $b='<input type="submit" value="Effacer" '.
		' onClick="return confirm(\'Voulez-vous vraiment effacer cette operation ?\');" >';
	  echo $b;
	  echo '</form>';

	  echo '</td>';
	  echo '</tr>';

	}
	echo '</table>';
  }
  echo '</div>';
?>