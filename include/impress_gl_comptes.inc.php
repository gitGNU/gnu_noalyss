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
require_once("class_ispan.php");
require_once("class_icard.php");
require_once("class_iselect.php");
require_once("class_icheckbox.php");
require_once('class_acc_operation.php');
/*! \file
 * \brief Print account (html or pdf)
 *        file included from user_impress
 *
 * some variable are already defined $cn, $User ...
 *
 */
//-----------------------------------------------------
// Show the jrn and date
//-----------------------------------------------------
require_once('class_database.php');

//-----------------------------------------------------
// Form
//-----------------------------------------------------
echo '<div class="content">';

echo '<FORM action="?" METHOD="GET">';
echo HtmlInput::hidden('p_action','impress');
echo HtmlInput::hidden('type','gl_comptes');
echo dossier::hidden();
echo '<TABLE><TR>';

$cn=new Database(dossier::id());
$periode=new Periode($cn);
$a=$periode->get_limit($User->get_exercice());
// $a is an array
$first_day=$a[0]->first_day();
$last_day=$a[1]->last_day();

// filter on period
$date_from=new IDate('from_periode');
$date_to=new IDate('to_periode');
$year=$User->get_exercice();
$date_from->value=(isset($_REQUEST['from_periode']))?$_REQUEST['from_periode']:$first_day;
$date_to->value=(isset($_REQUEST['to_periode']))?$_REQUEST['to_periode']:$last_day;
echo td(_('Depuis').$date_from->input());
echo td(_('Jusque ').$date_to->input());
//
echo '</TABLE>';
print HtmlInput::submit('bt_html','Visualisation');

echo '</FORM>';
echo '<hr>';
echo '</div>';

//-----------------------------------------------------
// If print is asked
// First time in html
// after in pdf or cvs
//-----------------------------------------------------
if ( isset( $_REQUEST['bt_html'] ) )
{
    require_once("class_acc_account_ledger.php");

    if ( isset($_GET['poste_id']) && strlen(trim($_GET['poste_id'])) != 0 && isNumber($_GET['poste_id']) )
    {
        if ( isset ($_GET['poste_fille']) )
        {
            $parent=$_GET['poste_id'];
            $a_poste=$cn->get_array("select pcm_val from tmp_pcmn where pcm_val::text like '$parent%' order by pcm_val::text");
        }
        elseif ( $cn->count_sql('select * from tmp_pcmn where pcm_val='.FormatString($_GET['poste_id'])) != 0 )
        {
            $a_poste=array( array('pcm_val' => $_GET['poste_id']));
        }
    }
    else
    {
        $a_poste=$cn->get_array("select pcm_val from tmp_pcmn order by pcm_val::text");
    }

    if ( sizeof($a_poste) == 0 )
    {
        die("Nothing here. Strange.");
        exit;
    }
    if ( isDate($_REQUEST['from_periode'])==null || isDate($_REQUEST['to_periode'])==null)
    {
        echo alert('Date malformée, désolée');
        exit();
    }
    echo '<div class="content">';


    echo '<table class="result">';
    foreach ($a_poste as $poste_id )
    {
        $Poste=new Acc_Account_Ledger ($cn, $poste_id['pcm_val']);
        $Poste->load();
        $Poste->get_row_date( $_GET['from_periode'], $_GET['to_periode']);
        if ( empty($Poste->row))
        {
            continue;
        }

        echo '<tr>
        <td colspan="8">
        <h2 class="info">'. $poste_id['pcm_val'].' '.h($Poste->label).'</h2>
        </td>
        </tr>';

        echo '<tr>
        <td>Date</td>
        <td>R&eacute;f&eacute;rence</td>
        <td>Libell&eacute;</td>
        <td>Pi&egrave;ce</td>
        <td align="right">D&eacute;bit</td>
        <td align="right">Cr&eacute;dit</td>
        <td align="right">Solde</td>
        <td></td>
        </tr>';

        $solde = 0.0;
        $solde_d = 0.0;
        $solde_c = 0.0;

        foreach ($Poste->row as $detail)
        {

            /*
                   [0] => 1 [jr_id] => 1
                   [1] => 01.02.2009 [j_date_fmt] => 01.02.2009
                   [2] => 2009-02-01 [j_date] => 2009-02-01
                   [3] => 0 [deb_montant] => 0
                   [4] => 12211.9100 [cred_montant] => 12211.9100
                   [5] => Ecriture douverture [description] => Ecriture douverture
                   [6] => Opération Diverses [jrn_name] => Opération Diverses
                   [7] => f [j_debit] => f
                   [8] => 17OD-01-1 [jr_internal] => 17OD-01-1
                   [9] => ODS1 [jr_pj_number] => ODS1 ) 1
             */

            if ($detail['cred_montant'] > 0)
            {
                $solde   += $detail['cred_montant'];
                $solde_c += $detail['cred_montant'];
            }
            if ($detail['deb_montant'] > 0)
            {
                $solde   -= $detail['deb_montant'];
                $solde_d += $detail['deb_montant'];
            }

            echo '<tr>
            <td>'.$detail['j_date_fmt'].'</td>
            <td>'.$detail['jr_internal'].'</td>
            <td>'.$detail['description'].'</td>
            <td>'.$detail['jr_pj_number'].'</td>
            <td align="right">'.($detail['deb_montant']  > 0 ? sprintf("%.2f", $detail['deb_montant'])  : '').'</td>
            <td align="right">'.($detail['cred_montant'] > 0 ? sprintf("%.2f", $detail['cred_montant']) : '').'</td>
            <td align="right">'.sprintf("%.2f", $solde).'</td>
            <td>'.''.'</td>
            </tr>';
        }
        echo '<tr>
        <td>'.''.'</td>
        <td>'.''.'</td>
        <td>'.'<b>'.'Total du compte '.$poste_id['pcm_val'].'</b>'.'</td>
        <td>'.''.'</td>
        <td align="right">'.'<b>'.($solde_d  > 0 ? sprintf("%.2f", $solde_d)  : '').'</b>'.'</td>
        <td align="right">'.'<b>'.($solde_c  > 0 ? sprintf("%.2f", $solde_c)  : '').'</b>'.'</td>
        <td align="right">'.'<b>'.sprintf("%.2f", abs($solde_c-$solde_d)).'</b>'.'</td>
        <td>'.($solde_c > $solde_d ? 'C' : 'D').'</td>
        </tr>';
    }
    echo '</table>';
    echo Acc_Account_Ledger::HtmlTableHeader("gl_comptes");
    echo "</div>";
    exit;
}
?>
