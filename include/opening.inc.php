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
 * \brief The opening of the exercices. it takes the saldo of the
 * choosen foolder / exercice and import it as a misc operation in the
 * current folder
 */
$sa=(isset($_REQUEST['sa']))?$_REQUEST['sa']:'';
$User=new User(new Database(dossier::id()));
$User->Check();
$User->can_request(PAREO,1);
require_once("class_iselect.php");
require_once('class_acc_ledger.php');
/* --------------------------------------------------
 * step 1 if nothing is asked we show the available folders
*/
if ($sa == '')
{
    echo '<div class="content">';
    echo '<fieldset><legend> Etape 1 </legend>';

    echo 'Choississez le dossier où sont les soldes à importer';
    $avail=GetAvailableFolder($User->id,$User->Admin());

    if ( empty( $avail) )
    {
        echo '*** Aucun dossier ***';
        exit();
    }
    echo '<form class="print" method="post">';
    echo HtmlInput::hidden('p_action','ouv');
    echo HtmlInput::hidden('sa','step2');
    echo dossier::hidden();
    $wAvail=new ISelect();
    /* compute select list */
    $array=array();
    $i=0;
    foreach ($avail as $r)
    {
        $array[$i]['value']=$r['dos_id'];
        $array[$i]['label']=$r['dos_name'];
        $i++;
    }

    $wAvail->value=$array;
    echo 'Choix du dossier :'.$wAvail->input('f');
    echo HtmlInput::submit('ok','Continuer');

    echo '</form>';
    echo '</fieldset>';
    echo '</div>';
    exit();
}
/* --------------------------------------------------
 * Step 2 choose now the exercice of this folder
 */
$back='user_advanced.php?p_action=ouv&'.dossier::get();
if ( $sa=='step2')
{
    echo '<div class="content">'.
    '<fieldset><legend>Etape 2</legend>'.
    '<h2 class="info">'.dossier::name($_REQUEST['f']).'</h2>'.
    '<form class="print" method="post">'.
    ' Choississez l\'exercice du dossier ';
    echo dossier::hidden();
    echo HtmlInput::hidden('p_action','ouv');
    echo HtmlInput::hidden('sa','step3');
    echo HtmlInput::hidden('f',$_REQUEST['f']);
    $cn=new Database($_REQUEST['f']);
    $periode=$cn->make_array("select distinct p_exercice,p_exercice from parm_periode order by p_exercice");
    $w=new ISelect();
    $w->table=0;
    $w->label='Periode';
    $w->readonly=false;
    $w->value=$periode;
    $w->name="p_periode";
    echo 'P&eacute;riode : '.$w->input();
    echo HtmlInput::submit('ok','Continuer');
    echo dossier::hidden();
    echo "</form>";
    echo HtmlInput::button_anchor('Retour',$back);
    exit(0);
}
/* --------------------------------------------------
 * select the ledger where we will import the data
 */
if ( $sa == 'step3')
{
    echo '<div class="content">'.
    '<fieldset><legend>Etape 3</legend>'.
    '<h2 class="info">'.dossier::name($_REQUEST['f']).'</h2>'.
    '<form class="print" method="post">'.
    ' Choississez le journal qui contiendra l\'opération d\'ouverture ';
    echo dossier::hidden();
    echo HtmlInput::hidden('p_action','ouv');
    echo HtmlInput::hidden('sa','step4');
    echo HtmlInput::hidden('f',$_REQUEST['f']);
    echo HtmlInput::hidden('p_periode',$_REQUEST['p_periode']);
    $wLedger=new ISelect();
    $User=new User(new Database(dossier::id()));
    $avail=$User->get_ledger('ODS');
    /* compute select list */
    $array=array();
    $i=0;
    foreach ($avail as $r)
    {
        $array[$i]['value']=$r['jrn_def_id'];
        $array[$i]['label']=$r['jrn_def_name'];
        $i++;
    }
    $wLedger->value=$array;
    echo $wLedger->input('p_jrn');
    echo HtmlInput::submit('ok','Continuer');
    echo dossier::hidden();
    echo "</form>";
    echo HtmlInput::button_anchor('Retour',$back);
    exit(0);

}
/* --------------------------------------------------
 * Step 4 we import data from the selected folder and year and
 * transform it into a misc operation
 */
if ( $sa=='step4')
{
    echo '<div class="content">';
    echo '<fieldset><legend> Dernière étape</legend>';
    $cn_target=new Database($_REQUEST['f']);
    $saldo=new Acc_Ledger($cn_target,0);
    $array=$saldo->get_saldo_exercice($_REQUEST['p_periode']);
    /*  we need to transform the array into a Acc_Ledger array */
    $result=array();
    $result['desc']='Ecriture d\'ouverture';
    $result['nb_item']=sizeof($array);
    $result['p_jrn']=$_REQUEST['p_jrn'];
    $idx=0;

    foreach ($array as $row )
    {
        $qcode='qc_'.$idx;
        $poste='poste'.$idx;
        $amount='amount'.$idx;
        $ck='ck'.$idx;
        $result[$qcode] = $row['j_qcode'];
        if ( trim($row['j_qcode'])=='')
            $result[$poste] = $row['j_poste'];
        $result[$amount] = abs($row['solde']);
        if ( $row['solde'] > 0 ) $result[$ck]='on';
        $idx++;
    }
    $cn=new Database(dossier::id());
    $User=new User($cn);
    $jrn=new Acc_Ledger($cn,$_REQUEST['p_jrn']);
    echo '<form class="print" method="post" action="compta.php">';
    echo HtmlInput::hidden('p_action','quick_writing');
    echo dossier::hidden();
    echo HtmlInput::hidden('p_jrn',$_REQUEST['p_jrn']);
    echo $jrn->show_form($result,0);
    echo '<hr>';
    echo '<h2 class="notice">Ne corrigez pas encore, cliquez continuer pour passer à l\'étape suivante</h2>';
    echo HtmlInput::submit('correct_it','Continuer');
    echo '</form>';
    echo HtmlInput::button_anchor('Retour',$back);

    echo '</div>';
}
