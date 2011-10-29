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
require_once ('class_acc_bilan.php');
require_once('class_exercice.php');

//ini_set("memory_limit","150M");
/*! \file
 * \brief form who call the printing of the bilan in RTF
 *        file included by user_impress
 *
 * some variable are already defined ($cn, $g_user ...)
 */

//-----------------------------------------------------
// Show the jrn and date
//-----------------------------------------------------
require_once('class_database.php');
global $g_user;
//-----------------------------------------------------
// Form
//-----------------------------------------------------

$bilan=new Acc_Bilan($cn);
$bilan->get_request_get();
echo '<div class="content">';
$exercice=(isset($_GET['exercice']))?$_GET['exercice']:$g_user->get_exercice();

/*
 * Let you change the exercice
 */
echo '<fieldset><legend>'._('Choississez un autre exercice').'</legend>';;
echo '<form method="GET">';
echo 'Choississez un autre exercice :';
$ex=new Exercice($cn);
$wex=$ex->select('exercice',$exercice,' onchange="submit(this)"');
echo $wex->input();
echo dossier::hidden();
echo HtmlInput::get_to_hidden(array('ac','type'));
echo '</form>';
echo '</fieldset>';

$filter_year=" where p_exercice='".FormatString($exercice)."'";
echo '<FORM  METHOD="GET">';
echo HtmlInput::hidden('type','bilan');
echo dossier::hidden();
echo $bilan->display_form ($filter_year);
echo HtmlInput::submit('verif',_('Verification comptabilite'));
echo HtmlInput::get_to_hidden(array('ac','exercice'));
echo '</FORM>';



if ( isset($_GET['verif']))
{
    echo '<h2> Etape 2 :Impression </h2>';

    $bilan->get_request_get();
    $bilan->verify();

    echo '<FORM METHOD="GET" ACTION="export.php">';
    echo dossier::hidden();
    echo HtmlInput::get_to_hidden(array('exercice'));
    echo HtmlInput::hidden('b_id',$_GET['b_id']);
    echo HtmlInput::hidden('act','OTH/Bilan');

    echo HtmlInput::hidden('from_periode',$bilan->from);
    echo HtmlInput::hidden('to_periode',$bilan->to);
    echo HtmlInput::submit('Impression','Impression');
    echo '</form>';

}
echo _('<span class="notice"> Attention : si le bilan n\'est pas équilibré.<br> Vérifiez <ul>
       <li>L\'affectation du résultat est fait</li>
       <li>Vos comptes actifs ont  un solde débiteur (sauf les comptes dit inversés)</li>
       <li> les comptes passifs ont un solde créditeur (sauf les comptes dit inversés) </li>
       </ul>
       Utilisez la balance des comptes pour vérifier. </span>');

echo '</div>';
?>
