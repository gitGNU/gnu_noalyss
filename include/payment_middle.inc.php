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
require_once('class_acc_payment.php');
//---------------------------------------------------------------------------
// Common variable
$td='<TD>';$etd='</td>';$tr='<tr>';$etr='</tr>';$th='<th>';$eth='</th>';

/*!\file
 * \brief payment mode
 */

echo '<div class="content">';

//----------------------------------------------------------------------
// change
if ( $sb=='change') {
  echo JS_LEDGER;
  echo JS_AJAX_FICHE;
  echo JS_PROTOTYPE;
  if ( !isset($_GET['id'])) exit;
  $row=new Acc_Payment($cn,$_GET['id']);
  $row->load();
  echo '<form method="post" action="parametre.php">';
  echo dossier::hidden();
  echo HtmlInput::hidden('p_jrn',0);
  echo HtmlInput::hidden('p_action','divers');
  echo HtmlInput::hidden('sa','mp');
  echo HtmlInput::hidden('sb','save');
  echo HtmlInput::hidden('mp_type',$row->get_parameter('type'));
  echo HtmlInput::hidden('mp_lib',$row->get_parameter('lib'));

  echo $row->form();
  echo HtmlInput::submit('save','Sauve');
  echo HtmlInput::button_href('Retour sans sauvez',
			   '?p_action=divers&sa=mp&'.dossier::get()
			   );
  echo '</form>';
  exit();
}
//----------------------------------------------------------------------
// Save the change
//
if ( $sb=='save'){
  $row=new Acc_Payment($cn,$_POST ['id']);
  $row->from_array($_POST);
  $row->update();

}

//----------------------------------------------------------------------
// LEDGER PURCHASE
//----------------------------------------------------------------------

echo '<fieldset>';
echo '<legend>Journaux d\' achat</legend>';
/* Get the data from database */
$mp=new Acc_Payment($cn);
$mp->set_parameter('type','ACH');
$array=$mp->get_all();
/* if there are data show them in a table */
if ( ! empty ($array)) {
  echo '<table style="border: 2px outset blue; width: 100%;" >';
  echo $tr.$th.'Libell&eacute;'.$eth.$th.'Type de fiche'
  .$eth.$th.'enregistr&eacute; dans le journal'.$eth.
    $th.' Avec la fiche'.$eth.$th.'Action'.$eth.$etr;
  foreach ($array as $row) {
    echo $tr;
    echo $row->row();
    echo $td.HtmlInput::button_href('Modifie','?p_action=divers&sa=mp&sb=change&'.dossier::get().
				 '&id='.$row->get_parameter('id'));
    echo $etr;

  }
  echo '</table>';
}
echo '</fieldset>';

//----------------------------------------------------------------------
// LEDGER SOLD
//----------------------------------------------------------------------

echo '<fieldset>';
echo '<legend>Journaux de vente</legend>';
$mp=new Acc_Payment($cn);
$mp->set_parameter('type','VEN');
$array=$mp->get_all();
/* if there are data show them in a table */
if ( ! empty ($array)) {
  echo '<table style="border: 2px outset blue; width: 100%;" >';
  echo $tr.$th.'Libell&eacute;'.$eth.$th.'Type de fiche'
  .$eth.$th.'enregistr&eacute; dans le journal'.$eth.
    $th.' Avec la fiche'.$eth.$th.'Action'.$eth.$etr;
  foreach ($array as $row) {
    echo $tr;
    echo $row->row();
    echo $td.HtmlInput::button_href('Modifie','?p_action=divers&sa=mp&sb=change&'.dossier::get().
				 '&id='.$row->get_parameter('id'));
    echo $etr;

  }
  echo '</table>';
}

echo '</fieldset>';
echo '</div>';
?>