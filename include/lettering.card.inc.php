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
require_once ('class_ipopup.php');
require_once('class_lettering.php');
echo JS_CARD;


$search_card=new IPopup('ipop_card');
$search_card->title=_('Recherche de fiche');
$search_card->value='';
echo $search_card->input();

echo '<div class="content">';
echo '<div id="search">';
echo '<FORM METHOD="GET">';
echo dossier::hidden();
echo HtmlInput::hidden('p_action','let');
echo HtmlInput::hidden('sa','qc');
echo HtmlInput::hidden('p_jrn','0');
echo '<table width="50%">';

$poste=new ICard('acc');
$poste->name="acc";
$poste->extra="all";
$poste->set_attribute('popup','ipopcard');
$poste->set_attribute('typecard','all');
$poste->set_callback('filter_card');



if (isset($_GET['acc'])) $poste->value=strtoupper(trim($_GET['acc']));
$poste_span=new ISpan('account_label');
$r= td(_('Lettrage pour la fiche ')).
    td($poste->input().$poste->search()).
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
echo '</div>';
if (! isset($_REQUEST['seek'])) exit;
echo '<hr>';
//--------------------------------------------------------------------------------
// record the data
//--------------------------------------------------------------------------------
if ( isset($_POST['record']))
{
    $letter=new Lettering_Account($cn);
    $letter->save($_POST);
}
//--------------------------------------------------------------------------------
// Show the result
//--------------------------------------------------------------------------------
echo '<div id="list">';
if ( isDate($_GET['start']) == null || isDate($_GET['end']) == null )
{
    echo alert(_('Date malformée, désolé'));
    exit();
}

$letter=new Lettering_Card($cn);
$quick_code=strtoupper(trim($_GET['acc']));
$letter->set_parameter('quick_code',$quick_code);
$letter->set_parameter('start',$_GET['start']);
$letter->set_parameter('end',$_GET['end']);

if ( $sel->selected == 0 )
    echo $letter->show_list('all');
if ( $sel->selected == 1 )
    echo $letter->show_list('letter');
if ( $sel->selected == 2 )
    echo $letter->show_list('unletter');

echo '</div>';
echo '<div id="detail" style="display:none">';
echo 'Un instant...';
echo '<IMG SRC=image/loading.gif>';
echo '</div>';
