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
 * \brief this file is to be included to handle the financial ledger
 */
require_once ('class_acc_ledger_fin.php');
require_once('class_ipopup.php');

$gDossier=dossier::id();
$p_action=(isset ($_REQUEST['p_action']))?$_REQUEST['p_action']:'';


$cn=new Database(dossier::id());
$menu_action="?p_action=fin&".dossier::get();
$menu=array(
          array($menu_action.'&sa=n',_('Nouvel extrait'),_('Encodage d\'un nouvel extrait'),1),
          array($menu_action.'&sa=l',_('Liste'),_('Liste opération bancaire'),2),
          array($menu_action.'&sa=s',_('Solde'),_('Solde des comptes'),3),
          array($menu_action.'&sa=r',_('Rapprochements banquaires'),_('Rapprochements banquaires'),4),
          array('?p_action=impress&type=jrn&'.dossier::get(),_('Impression'),_('Impression'))
          );
$sa=(isset($_REQUEST['sa']))?$_REQUEST['sa']:-1;

switch ($sa)
{
case 'n':
    $def=1;
    break;
case 'l':
    $def=2;
    break;
case 's':
    $def=3;
    break;
case 'r':
    $def=4;
    break;
default:
    $def=1;
}
echo '<div class="lmenu">';
echo ShowItem($menu,'H','mtitle','mtitle',$def);
echo '</div>';

$href=basename($_SERVER['PHP_SELF']);
$Ledger=new Acc_Ledger_Fin($cn,0);

//--------------------------------------------------------------------------------
// Encode a new financial operation
//--------------------------------------------------------------------------------
if ( $def == 1 )
{

    $href=basename($_SERVER['PHP_SELF']);

    if ( isset($_REQUEST['p_jrn']))
        $Ledger->id=$_REQUEST['p_jrn'];
    else
    {
        $def_ledger=$Ledger->get_first('fin');
        $Ledger->id=$def_ledger['jrn_def_id'];
    }
    $jrn_priv=$User->get_ledger_access($Ledger->id);
    // Check privilege
    if ( isset($_REQUEST['p_jrn']) && ( $jrn_priv == 'X'))
    {
        NoAccess();
        exit -1;
    }

    //----------------------------------------
    // Confirm the operations
    //----------------------------------------
    if ( isset($_POST['save']))
    {
        try
        {
            $Ledger->verify($_POST);
        }
        catch (Exception $e)
        {
            alert($e->getMessage());
            $correct=1;
        }
        if ( ! isset ($correct ))
        {
            echo '<div class="content">';
            echo '<form name="form_detail" class="print" enctype="multipart/form-data" ACTION="'.$href.'" METHOD="POST">';
            echo HtmlInput::hidden('p_action','fin');
            echo $Ledger->confirm($_POST);
            echo HtmlInput::submit('confirm',_('Confirmer'));
            echo HtmlInput::submit('correct',_('Corriger'));

            echo '</form>';
            echo '</div>';
            exit();
        }
    }
    //----------------------------------------
    // Confirm and save  the operations
    // into the database
    //----------------------------------------
    if ( isset($_POST['confirm']))
    {
        try
        {
            $Ledger->verify($_POST);
        }
        catch (Exception $e)
        {
            alert($e->getMessage());
            $correct=1;
        }
        if ( !isset($correct))
        {
            echo '<div class="content">';
            $a= $Ledger->insert($_POST);
            echo '<h2 class="info"  style="margin-left:20%;width:60%;margin-right:20%;">'._('Opération  sauvée').' </h2>';
            echo $a;
            echo '</div>';
            echo '<div class="content">';
            echo HtmlInput::button_anchor(_('Nouvel extrait'),$href.'?p_action=fin&sa=n&'.dossier::get());
            echo '</div>';
            exit();
        }
    }
    //----------------------------------------
    // Correct the operations
    //----------------------------------------
    if ( isset($_POST['correct']))
    {
        $correct=1;
    }
    //----------------------------------------
    // Blank form
    //----------------------------------------
    echo '<div class="content">';


    echo '<form class="print" name="form_detail" enctype="multipart/form-data" ACTION="'.$href.'" METHOD="POST">';
    echo HtmlInput::hidden('p_action','fin');
    echo HtmlInput::hidden('sa','n');
    $array=( isset($correct))?$_POST:null;
    // show select ledger
    echo $Ledger->input($array);
    echo HtmlInput::button('add_item',_('Ajout article'),   ' onClick="ledger_fin_add_row()"');
    echo HtmlInput::submit('save',_('Sauve'));
    echo HtmlInput::reset(_('Effacer'));

    echo create_script(" get_last_date();ajax_saldo('first_sold');");
    exit();

}
//--------------------------------------------------------------------------------
// Show the listing
//--------------------------------------------------------------------------------
if ( $def == 2)
{

    $Ledger=new Acc_Ledger_Fin($cn,0);
    if ( !isset($_REQUEST['p_jrn']))
    {
        $Ledger->id=-1;
    }
    else
        $Ledger->id=$_REQUEST['p_jrn'];
    echo '<div class="content">';
    echo $Ledger->display_search_form();
    $p_array=$_GET;
    /* by default we should the default period */
    if ( ! isset($p_array['date_start']))
    {
        $period=$User->get_periode();
        $per=new Periode($cn,$period);
        list($date_start,$date_end)=$per->get_date_limit();
        $p_array['date_start']=$date_start;
        $p_array['date_end']=$date_end;
    }
    /*  compute the sql stmt */
    list($sql,$where)=$Ledger->build_search_sql($p_array);

    $max_line=$cn->count_sql($sql);

    $step=$_SESSION['g_pagesize'];
    $page=(isset($_GET['offset']))?$_GET['page']:1;
    $offset=(isset($_GET['offset']))?$_GET['offset']:0;
    $bar=jrn_navigation_bar($offset,$max_line,$step,$page);

    echo HtmlInput::hidden("sa","lnp");
    echo HtmlInput::hidden("p_action","ach");
    echo dossier::hidden();
    echo $bar;
    list($count,$html)= $Ledger->list_operation($sql,$offset);
    echo $html;
    echo $bar;
   /*
     * Export to csv
     */
    $r=HtmlInput::get_to_hidden(array('l','date_start','date_end','desc','amount_min','amount_max','qcode','accounting','unpaid','gDossier','ledger_type','p_action'));
    if (isset($_GET['r_jrn'])) {
      foreach ($_GET['r_jrn'] as $k=>$v)
	$r.=HtmlInput::hidden('r_jrn['.$k.']',$v);
    }
    echo '<form action="histo_csv.php" method="get">';
    echo $r;
    echo HtmlInput::submit('viewsearch','Export vers CSV');

    echo '</form>';

    echo '</div>';
    exit();
}
//--------------------------------------------------------------------------------
// Show the saldo
//--------------------------------------------------------------------------------
if ( $def==3)
{
    require_once ('class_acc_parm_code.php');
    echo '<div class="content">';
    $fiche=new Fiche($cn);

    $array=$fiche->get_bk_account();

    echo '<div class="content">';
    echo dossier::hidden();
    echo '<table style="margin-left:10%;width:60%" class="result">';
    echo tr(th('Quick Code').th('Compte en banque',' style="text-align:left"').th('solde opération',' style="text-align:right"')
	    .th('solde extrait/relevé',' style="text-align:right"')
	    .th('différence',' style="text-align:right"'));
    // Filter the saldo
    //  on the current year
    $filter_year="  j_tech_per in (select p_id from parm_periode where  p_exercice='".$User->get_exercice()."')";
    // for highligting tje line
    $idx=0;
    bcscale(2);
    // for each account
    for ( $i = 0; $i < count($array);$i++)
    {
        // get the saldo
        $m=$array[$i]->get_solde_detail($filter_year);

        $solde=$m['debit']-$m['credit'];

        // print the result if the saldo is not equal to 0
        if ( $m['debit'] != 0.0 || $m['credit'] != 0.0)
        {
            /*  get saldo for not reconcilied operations  */
            $saldo_not_reconcilied=$array[$i]->get_bk_balance($filter_year." and (trim(jr_pj_number) ='' or jr_pj_number is null)" );

            /*  get saldo for reconcilied operation  */
	    
	    $saldo_reconcilied=$array[$i]->get_bk_balance($filter_year." and ( trim(jr_pj_number) != '' and jr_pj_number is not null)" );

            if ( $idx%2 != 0 )
                $odd="odd";
            else
                $odd="";

            $idx++;
            echo "<tr class=\"$odd\">";
            echo "<TD >".
            IButton::history_card($array[$i]->id,$array[$i]->strAttribut(ATTR_DEF_QUICKCODE)).
            "</TD>";

	    $saldo_rec=bcsub($saldo_reconcilied['debit'],$saldo_reconcilied['credit']);
	    $diff=bcsub($saldo_not_reconcilied['debit'],$saldo_not_reconcilied['credit']);
            echo "<TD >".
            $array[$i]->strAttribut(ATTR_DEF_NAME).
            "</TD>".
            "<TD align=\"right\">".
	      nbm($solde).
            "</TD>".
            "<TD align=\"right\">".
	      nbm($saldo_rec).
            "</TD>".
            "<TD align=\"right\">".
	      nbm($diff).
            "</TD>".
            "</TR>";
        }
    }// for
    echo "</table>";
    echo "</div>";
    exit();
}
//--------------------------------------------------
// Reconcilied
//--------------------------------------------------
if ($def==4)
{
    echo '<div class="content">';
    $Ledger=new Acc_Ledger_Fin($cn,0);
    if ( !isset($_REQUEST['p_jrn']))
    {
        $a=$Ledger->get_first('fin');
        $Ledger->id=$a['jrn_def_id'];
    }
    else
        $Ledger->id=$_REQUEST['p_jrn'];
    $jrn_priv=$User->get_ledger_access($Ledger->id);
    if ( isset($_GET["p_jrn"]) && $jrn_priv=="X")
    {
        NoAccess();
        exit();
    }
    //-------------------------
    // save
    //-------------------------
    if (isset ($_POST['save']))
    {
        if (trim($_POST['ext']) != '' && isset($_POST['op']))
        {
            $array=$_POST['op'];
            $tot=0;
            for ($i=0;$i<count($array);$i++)
            {
                $cn->exec_sql('update jrn set jr_pj_number=$1 where jr_id=$2',
                              array($_POST['ext'],$array[$i]));
                $tot=bcadd($tot,$cn->get_value('select qf_amount from quant_fin where jr_id=$1',array($array[$i])));
            }
            $diff=bcsub($_POST['start_extrait'],$_POST['end_extrait']);
            if ( $diff != 0 && $diff != $tot )
            {
                echo_warning("D'après l'extrait il y aurait du avoir un montant de $diff à rapprocher alors qu'il n'y a que $tot rapprochés");
            }
        }
    }
    //-------------------------
    // show the operation of this ledger
    // without receipt number
    //-------------------------
    echo '<div class="content">';
    echo '<form method="get">';
    echo HtmlInput::get_to_hidden(array('gDossier','p_action','sa'));
    $wLedger=$Ledger->select_ledger('FIN',3);
    if ($wLedger == null ) exit ('Pas de journal disponible');
    
    $wLedger->javascript="onchange='this.form.submit()';";
    echo $wLedger->input();
    echo HtmlInput::submit('ref','Rafraîchir');
    echo '</form>';

    echo '<form method="post" id="rec1">';

    echo dossier::hidden();
    echo HtmlInput::get_to_hidden(array('sa','p_action','p_jrn'));

    $operation=$cn->get_array("select jr_id,jr_internal,jr_comment,to_char(jr_date,'DD.MM.YYYY') as fmt_date,jr_montant
                              from jrn where jr_def_id=$1 and (jr_pj_number is null or jr_pj_number='') order by jr_date",
                              array($Ledger->id));
    echo '<span id="bkname">'.hb(h($Ledger->get_bank_name())).'</span>';
    echo '<p>';
    $iextrait=new IText('ext');
    $iextrait->value=$Ledger->guess_pj();
    $nstart_extrait=new INum('start_extrait');
    $nend_extrait=new INum('end_extrait');

    echo "Extrait / relevé :".$iextrait->input();
    echo 'solde Début'.$nstart_extrait->input();
    echo 'solde Fin'.$nend_extrait->input();
    echo IButton::tooggle_checkbox('rec1');
    echo '</p>';

    echo '<table class="result" style="width:80%;margin-left:10%">';
    $r=th('Date');
    $r.=th('Libellé');
    $r.=th('N° interne');
    $r.=th('Montant',' style="text-align:right"');
    $r.=th('Selection',' style="text-align:center" ');
    echo tr($r);
    $iradio=new ICheckBox('op[]');
    $tot_not_reconcilied=0;
    $diff=0;
    for ($i=0;$i<count($operation);$i++)
    {
        $row=$operation[$i];
        $r='';
        $js=HtmlInput::detail_op($row['jr_id'],$row['jr_internal']);
        $r.=td($row['fmt_date']);
        $r.=td($row['jr_comment']);
        $r.=td($js);
        $r.=td(sprintf("%.2f",$row['jr_montant']),' class="num" ');

        $tot_not_reconcilied+=$row['jr_montant'];
        $diff+=$cn->get_value('select qf_amount from quant_fin where jr_id=$1',array($row['jr_id']));
        $iradio->value=$row['jr_id'];
        $r.=td(HtmlInput::hidden('jrid[]',$row['jr_id']).$iradio->input(),' style="text-align:center" ');
        if ( $i % 2 == 0 )
            echo tr($r,' class="odd" ');
        else
            echo tr($r);
    }
    echo '</table>';
    $bk_card=new Fiche($cn);
    $bk_card->id=$Ledger->get_bank();
    $filter_year="  j_tech_per in (select p_id from parm_periode where  p_exercice='".$User->get_exercice()."')";

    /*  get saldo for not reconcilied operations  */
    $saldo_not_reconcilied=$bk_card->get_solde_detail($filter_year." and j_grpt in (select jr_grpt_id from jrn where trim(jr_pj_number) ='' or jr_pj_number is null)" );

    /*  get saldo for reconcilied operation  */
    $saldo_reconcilied=$bk_card->get_solde_detail($filter_year." and j_grpt in (select jr_grpt_id from jrn where trim(jr_pj_number) != '' and jr_pj_number is not null)" );

    /* solde compte */
    $saldo=$bk_card->get_solde_detail($filter_year);

    echo '<table>';
    echo '<tr>';
    echo td("Solde compte  ");
    echo td(sprintf('%.2f',($saldo['debit']-$saldo['credit'])),' style="text-align:right"');
    echo '</tr>';

    echo '<tr>';
    echo td("Solde non rapproché ");
    echo td(sprintf('%.2f',($saldo_not_reconcilied['debit']-$saldo_not_reconcilied['credit'])),' style="text-align:right"');
    echo '</tr>';

    echo '<tr>';
    echo td("Solde  rapproché ");
    echo td(sprintf('%.2f',($saldo_reconcilied['debit']-$saldo_reconcilied['credit'])),' style="text-align:right"');
    echo '</tr>';


    echo '<tr>';
    echo td("Total montant ");
    echo td(sprintf('%.2f',($tot_not_reconcilied)),' style="text-align:right"');
    echo '</tr>';

    echo '</table>';

    echo HtmlInput::submit('save','Mettre à jour le n° de relevé banquaire');
    echo '</form>';
    echo '</div>';
    exit();
}

