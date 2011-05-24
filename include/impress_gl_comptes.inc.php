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

$letter=new ICheckbox('letter');
$letter->selected=(isset($_REQUEST['letter']))?true:false;

$from_poste=new IPoste('from_poste');
$from_poste->value=HtmlInput::default_value('from_poste','',$_REQUEST);
$from_poste->set_attribute('account','from_poste');

$to_poste=new IPoste('to_poste');
$to_poste->value=HtmlInput::default_value('to_poste','',$_REQUEST);
$to_poste->set_attribute('account','to_poste');

echo '<tr>';
echo td(_('Depuis le poste')).td($from_poste->input());
echo '</tr>';

echo '<tr>';
echo td(_("Jusqu'au poste")).td($to_poste->input());
echo '</tr>';

echo '<tr>';
echo td('Uniquement les comptes non lettrés');
echo td($letter->input());
echo '</tr>';
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
    echo Acc_Account_Ledger::HtmlTableHeader("gl_comptes");
    $sql='select pcm_val from tmp_pcmn ';
    $cond_poste='';

    if ($from_poste->value != '') 
      {
	$cond_poste = '  where ';
	$cond_poste .=' pcm_val >= upper (\''.Database::escape_string($from_poste->value).'\')';
      }

    if ( $to_poste->value != '')
      {
	if  ( $cond_poste == '') 
	  {
	    $cond_poste =  ' where pcm_val <= upper (\''.Database::escape_string($to_poste->value).'\')';
	  }
	else
	  {
	    $cond_poste.=' and pcm_val <= upper (\''.Database::escape_string($to_poste->value).'\')';
	  }
      }

    $sql=$sql.$cond_poste.'  order by pcm_val::text';

    $a_poste=$cn->get_array($sql);

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
	$l=(isset($_REQUEST['letter']))?2:0;

        $Poste->get_row_date( $_GET['from_periode'], $_GET['to_periode'],$l);
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
        <td align="right">Let.</td>
        </tr>';

        $solde = 0.0;
        $solde_d = 0.0;
        $solde_c = 0.0;
	bcscale(2);
        foreach ($Poste->row as $detail)
        {
	  if ($a==0) {var_dump($detail);$a=1;}
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
	      $solde=bcadd($solde, $detail['cred_montant']);
	      $solde_c=bcadd($solde_c,$detail['cred_montant']);
            }
            if ($detail['deb_montant'] > 0)
            {
	      $solde   = bcsub($solde,$detail['deb_montant']);
	      $solde_d = bcadd($solde_d,$detail['deb_montant']);
            }
	    $letter=($detail['letter']!=-1)?hi($detail['letter']):'';
            echo '<tr>
            <td>'.$detail['j_date_fmt'].'</td>
            <td>'.HtmlInput::detail_op($detail['jr_id'],$detail['jr_internal']).'</td>
            <td>'.$detail['description'].'</td>
            <td>'.$detail['jr_pj_number'].'</td>
            <td align="right">'.($detail['deb_montant']  > 0 ? nbm($detail['deb_montant'])  : '').'</td>
            <td align="right">'.($detail['cred_montant'] > 0 ? nbm($detail['cred_montant']) : '').'</td>
            <td align="right">'.nbm($solde).'</td>
            <td  style="text-align:right;color:red">'.$letter.'</td>
            </tr>';
        }
        echo '<tr>
        <td>'.''.'</td>
        <td>'.''.'</td>
        <td>'.'<b>'.'Total du compte '.$poste_id['pcm_val'].'</b>'.'</td>
        <td>'.''.'</td>
        <td align="right">'.'<b>'.($solde_d  > 0 ? nbm( $solde_d)  : '').'</b>'.'</td>
        <td align="right">'.'<b>'.($solde_c  > 0 ? nbm( $solde_c)  : '').'</b>'.'</td>
        <td align="right">'.'<b>'.nbm( abs($solde_c-$solde_d)).'</b>'.'</td>
        <td>';
	if ($solde_c > $solde_d ) echo "Crédit";
	if ($solde_c < $solde_d )  echo "Débit";
	if ($solde_c == $solde_d )  echo "=";

      echo '</td>'.
        '</tr>';
    }
    echo '</table>';
    echo Acc_Account_Ledger::HtmlTableHeader("gl_comptes");
    echo "</div>";
    exit;
}
?>
