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

/**
 *@file
 *@todo add a form to filter by available ledgers
 */

/**
 *@file
 *@todo add the export to PDF
 */ 
$rjrn='';
$radio=new IRadio('choice');
echo '<form method="GET">';
echo '<ol style="list-style-type:none;">';
echo '<li>'.$radio->input().'Opérations réconcilées';
echo '<li>'.$radio->input().'Opérations réconcilées avec des montants différents';
echo '<li>'.$radio->input().'Opérations réconcilées avec des montants identiques';
echo '<li>'.$radio->input().'Opérations non réconcilées';
echo '</lo>';

echo HtmlInput::submit(_('Visualisation'),'vis');
echo '</form>';


