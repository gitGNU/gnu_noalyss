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
 * \brief show the lettering by account
 */
echo js_include('prototype.js');
echo js_include('scriptaculous.js');
echo js_include('effects.js');
echo js_include('controls.js');
echo js_include('dragdrop.js');
echo js_include('accounting_item.js');
echo js_include('acc_ledger.js');

require_once('class_lettering.php');

echo IPoste::ipopup('ipop_account');
echo '<div class="content">';

echo '<FORM METHOD="GET">';
echo dossier::hidden();
echo HtmlInput::phpsessid();
echo HtmlInput::hidden('p_action','let');
echo HtmlInput::hidden('sa','poste');
echo '<table width="50%">';

$poste=new IPoste();
$poste->name="acc";
$poste->table=1;
$poste->set_attribute('phpsessid',$_REQUEST['PHPSESSID']);
$poste->set_attribute('jrn',0);
$poste->set_attribute('gDossier',dossier::id());
$poste->set_attribute('ipopup','ipop_account');
$poste->set_attribute('label','account_label');
$poste->set_attribute('account','acc');
if (isset($_GET['acc'])) $poste->value=$_GET['acc'];
$poste_span=new ISpan('account_label');
$r= td(_('Lettrage pour le poste comptable ')).
  $poste->input().
  td($poste_span->input());
echo tr($r);
// limit of the year
$exercice=$User->get_exercice();
$periode=new Periode($cn);
list($first_per,$last_per)=$periode->get_limit($exercice);

$start=new IDate('start');
$start->value=(isset($_GET['start']))?$_GET['start']:$first_per->first_day();
$r=td(_('Date début'));
$r.=td($start->input());
echo tr($r);

$end=new IDate('end');
$end->value=(isset($_GET['end']))?$_GET['end']:$last_per->last_day();
$r=td(_('Date fin'));
$r.=td($end->input());
echo tr($r);

// type of lettering : all, lettered, not lettered
$sel=new ISelect('type_let');
$sel->value=array(
		  array('value'=>0,'label'=>_('Toutes opérations')),
		  array('value'=>1,'label'=>_('Opérations lettrées')),
		  array('value'=>2,'label'=>_('Opérations NON lettrées'))
		  );
if (isset($_GET['type_let'])) $sel->selected=$_GET['type_let'];

$r= td("Filtre ").
  td($sel->input());

echo tr($r);
echo '</table>';
echo '<br>';
echo HtmlInput::submit("seek",_('Recherche'));
echo '</FORM>';

if (! isset($_REQUEST['seek'])) exit;
echo '<hr>';
//--------------------------------------------------------------------------------
// record the data
//--------------------------------------------------------------------------------
if ( isset($_POST['record'])) {
  $letter=new Lettering_Account($cn);
  $letter->save($_POST);
}
//--------------------------------------------------------------------------------
// Show the result
//--------------------------------------------------------------------------------
echo '<div id="list">';
$letter=new Lettering_Account($cn);
$letter->set_parameter('account',$_GET['acc']);
$letter->set_parameter('start',$_GET['start']);
$letter->set_parameter('end',$_GET['end']);

if ( $sel->selected == 0 )
  echo $letter->show_list('all');

echo '</div>';
echo '<div id="detail" style="display:none">';
echo '<IMG SRC=image/loading.gif>';
echo '</div>';