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
 * \brief printing of category of card  : balance, historic
 */
include_once('class_database.php');
include_once('class_fiche.php');
require_once('class_lettering.php');

$gDossier=dossier::id();
$cn=new Database($gDossier);
/**
 * Show first the form
 */
/* category */
$categorie=new ISelect('cat');
$categorie->value=$cn->make_array('select fd_id,fd_label from fiche_def order by fd_label');
$categorie->selected=(isset($_GET['cat']))?$_GET['cat']:0;
$str_categorie=$categorie->input();

/* periode */
$exercice=$User->get_exercice();
$iperiode=new Periode($cn);
list ($first,$last)=$iperiode->get_limit($exercice);

$periode_start=new IDate('start');
$periode_end=new IDate('end');

$periode_start->value=(isset($_GET['start']))?$_GET['start']:$first->first_day();
$periode_end->value=(isset($_GET['end']))?$_GET['end']:$last->last_day();

$str_start=$periode_start->input();
$str_end=$periode_end->input();

/* histo ou summary */
$histo=new ISelect('histo');
$histo->value=array(
                  array('value'=>0,'label'=>_('Historique')),
                  array('value'=>1,'label'=>_('Historique Lettré')),
                  array('value'=>2,'label'=>_('Historique non Lettré')),
                  array('value'=>3,'label'=>_('Résumé')),
                  array('value'=>4,'label'=>_('Balance')),
              );
$histo->javascript='onchange="if (this.value==3) {
                   g(&quot;trstart&quot;).style.display=&quot;none&quot;;g(&quot;trend&quot;).style.display=&quot;none&quot;;}
                   else  {g(&quot;trstart&quot;).style.display=&quot;&quot;;g(&quot;trend&quot;).style.display=&quot;&quot;}"';

$histo->selected=(isset($_GET['histo']))?$_GET['histo']:3;
$str_histo=$histo->input();
echo '<div class="content">';
echo '<FORM method="GET">';
echo dossier::hidden();
echo HtmlInput::hidden('p_action',$_GET['p_action']);
echo HtmlInput::hidden('type',$_GET['type']);
require_once('template/impress_cat_card.php');
echo HtmlInput::submit('cat_display',_('Recherche'));
echo '</FORM>';
$str="if (g('histo').value==3) {
     g('trstart').style.display='none';g('trend').style.display='none';}
     else  {g('trstart').style.display='';g('trend').style.display='';}";
echo create_script($str);
echo '<hr>';
//-----------------------------------------------------
if ( ! isset($_GET['cat_display']))
    exit();

$array=Fiche::get_fiche_def($cn,$_GET['cat'],'name_asc');

/*
 * You show now the result
 */
if ($array == null  )
{
    echo '<h2 class="info2"> Aucune fiche trouvée</h2>';
    exit();
}
// summary
if ( $_GET['histo'] == 3 )
{
    $cat_card=new Fiche_Def($cn);
    $cat_card->id=$_GET['cat'];
    $aHeading=$cat_card->getAttribut();
    require_once('template/result_cat_card_summary.php');

    $hid=new IHidden();
    echo '<form method="GET" ACTION="fiche_csv.php">'.dossier::hidden().
    HtmlInput::submit('bt_csv',"Export CSV").
    $hid->input("type","fiche").
    $hid->input("p_action","impress").
    $hid->input("fd_id",$_REQUEST['cat']);
    echo "</form>";

    exit();
}
$export_pdf='<FORM METHOD="get" ACTION="category_pdf.php">';
$export_pdf.=HtmlInput::hidden('cat',$_GET['cat']);
$export_pdf.=HtmlInput::hidden('start',$_GET['start']);
$export_pdf.=HtmlInput::hidden('end',$_GET['end']);
$export_pdf.=HtmlInput::hidden('histo',$_GET['histo']);
$export_pdf.=dossier::hidden();
$export_pdf.=HtmlInput::submit('pdf','Export en PDF');
$export_pdf.='</FORM>';
echo $export_pdf;
// Balance
if ( $_GET['histo'] == 4 )
{
    if ( isDate($_REQUEST['start']) == null || isDate ($_REQUEST['end']) == null )
    {
        alert('Date invalide !');
        exit;
    }

    $fd=new Fiche_Def($cn,$_REQUEST['cat']);
    if ( $fd->hasAttribute(ATTR_DEF_ACCOUNT) == false )
    {
        echo "Cette catégorie n'ayant pas de poste comptable n'a pas de balance";
        exit;
    }
    $aCard=$cn->get_array("select f_id from vw_fiche_attr where fd_id=$1 order by vw_name ",array($_REQUEST['cat']));
    if ( empty($aCard))
    {
        echo "Aucune fiche trouvée";
        exit;
    }
    echo '<table class="result" style="width:80%;margin-left:10%">';
    echo tr(
        th('Quick Code').
        th('Libellé').
        th('Débit','style="text-align:right"').
        th('Crédit','style="text-align:right"').
        th('Solde','style="text-align:right"').
        th('D/C','style="text-align:right"')
    );
    $idx=0;
    for ($i=0;$i < count($aCard);$i++)
    {
        $filter= " (j_date >= to_date('".$_REQUEST['start']."','DD.MM.YYYY') ".
                 " and  j_date <= to_date('".$_REQUEST['end']."','DD.MM.YYYY')) ";
        $oCard=new Fiche($cn,$aCard[$i]['f_id']);
        $solde=$oCard->get_solde_detail($filter);
        if ( $solde['debit'] == 0 && $solde['credit']==0) continue;
        $class='';
        if ( $idx%2 == 0) $class='class="odd"';
        $idx++;
        echo tr(
            td(HtmlInput::history_card($oCard->id,$oCard->strAttribut(ATTR_DEF_QUICKCODE))).
            td($oCard->strAttribut(ATTR_DEF_NAME)).
            td(sprintf('%.02f',$solde['debit']),'style="text-align:right"').
            td(sprintf('%.02f',$solde['credit']),'style="text-align:right"').
            td(sprintf('%.02f',abs($solde['solde'])),'style="text-align:right"').
            td((($solde['solde']<0)?'CRED':'DEB'),'style="text-align:right"'),
            $class
        );

    }
    echo '</table>';
    echo $export_pdf;
    exit();
}
if ( isDate($_REQUEST['start']) == null || isDate ($_REQUEST['end']) == null )
{
    alert('Date invalide !');
    exit;
}
// for the lettering
foreach($array as $row)
{
    $letter=new Lettering_Card($cn);
    $letter->set_parameter('quick_code',$row->strAttribut(ATTR_DEF_QUICKCODE));
    $letter->set_parameter('start',$_GET['start']);
    $letter->set_parameter('end',$_GET['end']);
    // all
    if ( $_GET['histo'] == 0 )
    {
        $letter->get_all();
    }

    // lettered
    if ( $_GET['histo'] == 1 )
    {
        $letter->get_letter();
    }
    // unlettered
    if ( $_GET['histo'] == 2 )
    {
        $letter->get_unletter();
    }
    /* skip if nothing to display */
    if (count($letter->content) == 0 ) continue;

    echo '<h2 style="font-size:14px;text-align:left;margin-left:10;padding-left:50;border:solid 1px blue;width:25%">'.$row->strAttribut(ATTR_DEF_NAME).'</h2>';

    echo '<table style="width:80%;padding-left:10%;padding-right:10%">';
    echo '<tr>';
    echo th(_('Date'));
    echo th(_('ref'));
    echo th(_('Interne'));
    echo th(_('Comm'));
    echo th(_('Montant'),' colspan="2"');
    echo th(_('Prog.'));
    echo th(_('Let.'));
    echo '</tr>';
    $amount_deb=0;
    $amount_cred=0;
    $prog=0;
    for ($i=0;$i<count($letter->content);$i++)
    {
        if ($i%2 == 0 )
            echo '<tr class="even">';
        else
            echo '<tr class="odd">';
        $row=$letter->content[$i];
        echo td($row['j_date_fmt']);
        echo td($row['jr_pj_number']);
        echo td(HtmlInput::detail_op($row['jr_id'],$row['jr_internal']));
        echo td($row['jr_comment']);
        if ( $row['j_debit'] == 't')
        {
            echo td(sprintf('%.2f',$row['j_montant']),' style="text-align:right"');
            $amount_deb+=$row['j_montant'];
            $prog+=$row['j_montant'];
            echo td("");
        }
        else
        {
            echo td("");
            echo td(sprintf('%.2f',$row['j_montant']),' style="text-align:right"');
            $amount_cred+=$row['j_montant'];
            $prog-=$row['j_montant'];
        }
        echo td(sprintf('%.2f',$prog),'style="text-align:right"');
        if ($row['letter'] != -1 ) echo td($row['letter']);
        else echo td('');
        echo '</tr>';
    }
    echo '</table>';
    echo '<table>';
    echo '<tr>';
    echo td(_('Debit'));
    echo td($amount_deb,' style="font-weight:bold;text-align:right"');
    echo '</tr>';
    echo '<tr>';
    echo td(_('Credit'));
    echo td($amount_cred,' style="font-weight:bold;text-align:right"');
    echo '</tr>';
    echo '<tr>';
    if ( $amount_deb>$amount_cred) $s='solde débiteur';
    else $s='solde crediteur';
    echo td($s);
    echo td(abs(round($amount_cred-$amount_deb,2)),' style="font-weight:bold;text-align:right"');
    echo '</tr>';
    echo '</table>';
}
echo $export_pdf;


?>

