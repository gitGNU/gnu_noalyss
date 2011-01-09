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
 *@todo add the export to PDF
 */
$aledger=$User->get_ledger('ALL',3);
echo '<div class="noprint">';
echo '<div class="content">';
$rjrn='';
$radio=new IRadio('choice');
$choice=(isset($_GET['choice']))?$_GET['choice']:0;
$r_jrn=(isset($_GET['r_jrn']))?$_GET['r_jrn']:'';
echo '<form method="GET">';
echo dossier::hidden().HtmlInput::hidden('p_action','impress').HtmlInput::hidden('type','rec');
echo 'Filtre par journal :'.HtmlInput::select_ledger($aledger,$r_jrn );
echo '<br/>';
/*
 * Limit by date, default current exercice
 */
list($start,$end)=$User->get_limit_current_exercice();
$dstart=new IDate('p_start');
$dstart->value=(isset($_REQUEST['p_start']))?$_REQUEST['p_start']:$start;

$dend=new IDate('p_end');
$dend->value=(isset($_REQUEST['p_end']))?$_REQUEST['p_end']:$end;

echo "Depuis ".$dstart->input()." jusque ".$dend->input();
echo '<ol style="list-style-type:none;">';

$radio->selected=($choice==0)?true:false;
$radio->value=0;
echo '<li>'.$radio->input().'Opérations rapprochées'.'</li>';

$radio->selected=($choice==1)?true:false;
$radio->value=1;
echo '<li>'.$radio->input().'Opérations rapprochées avec des montants différents'.'</li>';

$radio->selected=($choice==2)?true:false;
$radio->value=2;
echo '<li>'.$radio->input().'Opérations rapprochées avec des montants identiques'.'</li>';

$radio->selected=($choice==3)?true:false;
$radio->value=3;
echo '<li>'.$radio->input().'Opérations non rapprochées'.'</li>';

echo '</ol>';




echo HtmlInput::submit('vis',_('Visualisation'));
echo '</form>';
echo '<hr>';
echo '</div>';
echo '</div>';
echo '<div class="content">';
if ( ! isset($_GET['vis'])) exit();
$a=new Acc_Reconciliation($cn);
$a->a_jrn=$r_jrn;
$a->start_day=$dstart->value;
$a->end_day=$dend->value;

switch ($choice)
{
case 0:
    $array=$a->get_reconciled();
    break;
case 1:
    $array=$a->get_reconciled_amount(false);
    break;
case 2:
    $array=$a->get_reconciled_amount(true);
    break;
case 3:
    $array=$a->get_not_reconciled();
    break;
default:
    echo "Choix invalid";
    exit();
}
require_once('template/impress_reconciliation.php');
exit();
exit();
exit();