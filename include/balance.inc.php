<?php
/*
 *   This file is part of PHPCOMPTA.
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
 *   along with PHPCOMPTA; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
// Auteur Dany De Bontridder ddebontridder@yahoo.fr
// /* $Revision$ */
/*! \file
 * \brief Show the balance and let you print it or export to PDF
 *        file included by user_impress
 *
 * some variable are already defined ($cn, $User ...)
 */

include_once ("ac_common.php");
include_once("class_acc_balance.php");
require_once("class_iselect.php");
require_once("class_ispan.php");
require_once("class_icheckbox.php");
require_once("class_ihidden.php");
require_once('class_acc_ledger.php');
require_once('class_periode.php');

$User->can_request(IMPBAL);
require_once('class_ipopup.php');
echo IPoste::ipopup('ipop_account');
echo '<div class="content">';

// Show the form for period
echo '<FORM  method="get">';
echo HtmlInput::hidden('p_action','impress');
echo HtmlInput::hidden('type','bal');

echo dossier::hidden();
// filter on the current year
$from=(isset($_GET["from_periode"]))?$_GET['from_periode']:"";
$input_from=new IPeriod("from_periode",$from);
$input_from->show_end_date=false;
$input_from->type=ALL;
$input_from->cn=$cn;
$input_from->filter_year=true;
$input_from->user=$User;
echo 'Depuis :'.$input_from->input();
// filter on the current year
$to=(isset($_GET["to_periode"]))?$_GET['to_periode']:"";
$input_to=new IPeriod("to_periode",$to);
$input_to->show_start_date=false;
$input_to->filter_year=true;
$input_to->type=ALL;
$input_to->cn=$cn;
$input_to->user=$User;
echo ' jusque :'.$input_to->input();

//-------------------------------------------------


/*  add a all ledger choice */
echo 'Filtre ';
$rad=new IRadio();
$array_ledger=$User->get_ledger('ALL',3);
$selected=(isset($_GET['r_jrn']))?$_GET['r_jrn']:null;
$select_cat=(isset($_GET['r_cat']))?$_GET['r_cat']:null;
$array_cat=Acc_Ledger::array_cat();

echo '<ul style="list-style-type:none">';
if ( ! isset($_GET['p_filter']) || $_GET['p_filter']==0) $rad->selected='t';
else $rad->selected=false;
echo '<li>'.$rad->input('p_filter',0).'Aucun filtre, tous les journaux'.'</li>';
if (  isset($_GET['p_filter']) && $_GET['p_filter']==1) $rad->selected='t';
else $rad->selected=false;
echo '<li>'.$rad->input('p_filter',1).'Filtré par journal'.HtmlInput::select_ledger($array_ledger,$selected).'</li>';
if (  isset($_GET['p_filter']) && $_GET['p_filter']==2) $rad->selected='t';
else $rad->selected=false;
echo '<li>'.$rad->input('p_filter',2).'Filtré par catégorie'.HtmlInput::select_cat($array_cat).'</li>';
echo '</ul>';

$from_poste=new IPoste();
$from_poste->name="from_poste";
$from_poste->set_attribute('ipopup','ipop_account');
$from_poste->set_attribute('label','from_poste_label');
$from_poste->set_attribute('account','from_poste');

$from_poste->value=(isset($_GET['from_poste']))?$_GET['from_poste']:"";
$from_span=new ISpan("from_poste_label","");

$to_poste=new IPoste();
$to_poste->name="to_poste";
$to_poste->set_attribute('ipopup','ipop_account');
$to_poste->set_attribute('label','to_poste_label');
$to_poste->set_attribute('account','to_poste');

$to_poste->value=(isset($_GET['to_poste']))?$_GET['to_poste']:"";
$to_span=new ISpan("to_poste_label","");

echo "<div>";
echo "Plage de postes :".$from_poste->input();
echo $from_span->input();
echo " jusque :".$to_poste->input();
echo $to_span->input();
echo "</div>";

echo HtmlInput::submit("view","Visualisation");
echo '</form>';
echo '<hr>';
//-----------------------------------------------------
// Form
//-----------------------------------------------------
// Show the export button
if ( isset ($_GET['view']  ) )
{

    $hid=new IHidden();

    echo "<table>";
    echo '<TR>';
    echo '<TD><form method="GET" ACTION="print_balance.php">'.
    dossier::hidden().
    HtmlInput::submit('bt_pdf',"Export PDF").
    HtmlInput::hidden("p_action","impress").
    HtmlInput::hidden("from_periode",$_GET['from_periode']).
    HtmlInput::hidden("to_periode",$_GET['to_periode']);
    echo HtmlInput::hidden('p_filter',$_GET['p_filter']);
    for ($e=0;$e<count($array_ledger);$e++)
        if (isset($selected[$e]))
            echo    HtmlInput::hidden("r_jrn[$e]",$e);
    for ($e=0;$e<count($array_cat);$e++)
        if (isset($select_cat[$e]))
            echo    HtmlInput::hidden("r_cat[$e]",$e);

    echo HtmlInput::hidden("from_poste",$_GET['from_poste']).
    HtmlInput::hidden("to_poste",$_GET['to_poste']);
    echo "</form></TD>";

    echo '<TD><form method="GET" ACTION="bal_csv.php">'.
    HtmlInput::submit('bt_csv',"Export CSV").
    dossier::hidden().
    HtmlInput::hidden("p_action","impress").
    HtmlInput::hidden("from_periode",$_GET['from_periode']).
    HtmlInput::hidden("to_periode",$_GET['to_periode']);
    echo HtmlInput::hidden('p_filter',$_GET['p_filter']);
    if (isset($_GET ['r_jrn']))
        if (isset($selected[$e]))
            echo    HtmlInput::hidden("r_jrn[$e]",$e);
    for ($e=0;$e<count($array_cat);$e++)
        if (isset($select_cat[$e]))
            echo    HtmlInput::hidden("r_cat[$e]",$e);

    echo   HtmlInput::hidden("from_poste",$_GET['from_poste']).
    HtmlInput::hidden("to_poste",$_GET['to_poste']);

    echo "</form></TD>";

    echo "</TR>";

    echo "</table>";
}


//-----------------------------------------------------
// Display result
//-----------------------------------------------------
if ( isset($_GET['view'] ) )
{
    $bal=new Acc_Balance($cn);
    if ( $_GET['p_filter']==1)
    {
        for ($e=0;$e<count($array_ledger);$e++)
            if (isset($selected[$e]))
                $bal->jrn[]=$array_ledger[$e]['jrn_def_id'];
    }
    if ( $_GET['p_filter'] == 0 )
    {
        $bal->jrn=null;
    }
    if ( $_GET['p_filter'] == 2 && isset ($_GET['r_cat']))
    {
        $bal->filter_cat($_GET['r_cat']);
    }
    $bal->from_poste=$_GET['from_poste'];
    $bal->to_poste=$_GET['to_poste'];

    $row=$bal->get_row($_GET['from_periode'],
                       $_GET['to_periode']);
    $periode=new Periode($cn);
    $a=$periode->get_date_limit($_GET['from_periode']);
    $b=$periode->get_date_limit($_GET['to_periode']);
    echo "<h2 class=\"info\"> période du ".$a['p_start']." au ".$b['p_end']."</h2>";

    echo '<table width="100%">';
    echo '<th>Poste Comptable</th>';
    echo '<th>Libell&eacute;</th>';
    echo '<th>D&eacute;bit</th>';
    echo '<th>Cr&eacute;dit</th>';
    echo '<th>Solde D&eacute;biteur </th>';
    echo '<th>Solde Cr&eacute;diteur</th>';

    $i=0;
    foreach ($row as $r)
    {
        $i++;
        if ( $i%2 == 0 )
            $tr="even";
        else
            $tr="odd";
        $view_history= sprintf('<A class="detail" style="text-decoration:underline" HREF="javascript:view_history_account(\'%s\',\'%s\')" >%s</A>',
                               $r['poste'], $gDossier, $r['poste']);

        echo '<TR class="'.$tr.'">';
        echo td($view_history);
        echo td(h($r['label']));
        echo td(nbm($r['sum_deb']),'style="text-align:right"');
	echo td(nbm($r['sum_cred']),'style="text-align:right"');
	echo td(nbm($r['solde_deb']),'style="text-align:right"');
	echo td(nbm($r['solde_cred']),'style="text-align:right"');
        echo '</TR>';
    }
    echo '</table>';

}// end submit
echo "</div>";
?>
