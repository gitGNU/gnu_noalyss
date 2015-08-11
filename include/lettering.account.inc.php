<?php
/*
 *   This file is part of NOALYSS.
 *
 *   NOALYSS is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   NOALYSS is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with NOALYSS; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Copyright Author Dany De Bontridder danydb@aevalys.eu

/*!\file
 * \brief show the lettering by account
 */

if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
require_once NOALYSS_INCLUDE.'/class_lettering.php';

echo '<div class="content">';
echo '<div id="search">';
echo '<FORM METHOD="GET">';
echo dossier::hidden();
echo HtmlInput::hidden('ac',$_REQUEST['ac']);
echo HtmlInput::hidden('sa','poste');

$poste=new IPoste();
$poste->name="acc";
$poste->table=0;
$poste->set_attribute('jrn',0);
$poste->set_attribute('gDossier',dossier::id());
$poste->set_attribute('ipopup','ipop_account');
$poste->set_attribute('label','account_label');
$poste->set_attribute('account','acc');
$acc_lib="";
if (isset($_GET['acc'])) { 
    $poste->value=$_GET['acc']; 
    $acc_lib=$cn->get_value('select pcm_lib from tmp_pcmn where pcm_val=upper($1)',array($poste->value));
}

$poste_span=new ISpan('account_label');
$poste_span->value=$acc_lib;

$r= td(_('Lettrage pour le poste comptable ')).
    td($poste->input()).
    td($poste_span->input());

echo '<table width="50%">';
echo tr($r);
// limit of the year
$exercice=$g_user->get_exercice();
$periode=new Periode($cn);
list($first_per,$last_per)=$periode->get_limit($exercice);

$start=new IDate('start');
if ( isset ($_GET['start']) && isDate($_GET['start']) == null )
{
    echo alert(_('Date malformée, désolé'));
	$_GET['start']=$first_per->first_day();

}
$start->value=(isset($_GET['start']))?$_GET['start']:$first_per->first_day();

$r=td(_('Date début'));
$r.=td($start->input());
echo tr($r);

$end=new IDate('end');
if ( isset($_GET['end']) && isDate($_GET['end']) == null )
{
    echo alert(_('Date malformée, désolé'));
	$_GET['end']=$last_per->last_day();

}
$end->value=(isset($_GET['end']))?$_GET['end']:$last_per->last_day();
$r=td(_('Date fin'));
$r.=td($end->input());
echo tr($r);

// type of lettering : all, lettered, not lettered
$sel=new ISelect('type_let');
$sel->value=array(
                array('value'=>0,'label'=>_('Toutes opérations')),
                array('value'=>1,'label'=>_('Opérations lettrées')),
				array('value'=>3,'label'=>_('Opérations lettrées montants différents')),
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
    return;
}
$letter=new Lettering_Account($cn);
$letter->set_parameter('account',$_GET['acc']);
$letter->set_parameter('start',$_GET['start']);
$letter->set_parameter('end',$_GET['end']);

if ( $sel->selected == 0 )
    echo $letter->show_list('all');
if ( $sel->selected == 1 )
    echo $letter->show_list('letter');
if ( $sel->selected == 2 )
    echo $letter->show_list('unletter');
if ( $sel->selected == 3 )
    echo $letter->show_list('letter_diff');
echo '</div>';
echo '<div id="detail" style="display:none">';
echo 'Un instant...';
echo '<IMG SRC=image/loading.gif>';
echo '</div>';
