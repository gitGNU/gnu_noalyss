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
 * \brief print the all the operation reconciled or not, with or without the same amount
 */
require_once ('class_acc_reconciliation.php');
require_once('function_javascript.php');
echo load_all_script();
/**
 *@file
 *@todo add a form to filter by available ledgers
 */

/**
 *@file
 *@todo add the export to PDF
 */ 
echo '<div class="content">';
$rjrn='';
$radio=new IRadio('choice');
$choice=(isset($_GET['choice']))?$_GET['choice']:0;

echo '<form method="GET">';
echo dossier::hidden().HtmlInput::hidden('p_action','impress').HtmlInput::hidden('type','rec');
echo '<ol style="list-style-type:none;">';

$radio->selected=($choice==0)?true:false;$radio->value=0;
echo '<li>'.$radio->input().'Opérations réconcilées'.'</li>';

$radio->selected=($choice==1)?true:false;$radio->value=1;
echo '<li>'.$radio->input().'Opérations réconcilées avec des montants différents'.'</li>';

$radio->selected=($choice==2)?true:false;$radio->value=2;
echo '<li>'.$radio->input().'Opérations réconcilées avec des montants identiques'.'</li>';

$radio->selected=($choice==3)?true:false;$radio->value=3;
echo '<li>'.$radio->input().'Opérations non réconcilées'.'</li>';

echo '<li>'.HtmlInput::submit('vis',_('Visualisation')).'</li>';
echo '</lo>';
echo '</form>';
echo '<hr>';
echo '</div>';
echo '<div class="content">';
if ( ! isset($_GET['vis'])) exit();
$a=new Acc_Reconciliation($cn);
switch ($choice) {
case 0:
  $array=$a->get_reconciled('');
  break;
case 1:
  $array=$a->get_reconciled_amount('',false);
  break;
case 2:
  $array=$a->get_reconciled_amount('',true);
  break;
case 3:
  $array=$a->get_not_reconciled('');
  break;
default:
  echo "Choix invalid";
  exit();
}
require_once('template/impress_reconciliation.php');
exit();