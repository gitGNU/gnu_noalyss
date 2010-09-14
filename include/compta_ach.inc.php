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
 * \brief file included to manage all the sold operation
 */
require_once("class_icheckbox.php");
require_once("class_acc_ledger_purchase.php");
require_once ('class_pre_op_ach.php');
require_once('class_ipopup.php');
$gDossier=dossier::id();
echo ICard::ipopup('ipopcard');
echo ICard::ipopup('ipop_newcard');
echo IPoste::ipopup('ipop_account');
$search_card=new IPopup('ipop_card');
$search_card->title=_('Recherche de fiche');
$search_card->value='';
echo $search_card->input();
$pop_tva=new IPopup('popup_tva');
$pop_tva->title=_('Choix TVA');
$pop_tva->value='';
echo $pop_tva->input();
$p_action=(isset($_REQUEST['p_action']))?$_REQUEST['p_action']:'';

$cn=new Database(dossier::id());
//menu = show a list of ledger
$str_dossier=dossier::get();

$array=array(
           array('?p_action=ach&sa=n&'.$str_dossier,_('Nouvelle dépense'),_('Nouvel achat ou dépense'),1),
           array('?p_action=ach&sa=l&'.$str_dossier,_('Liste achat'),_('Liste des achats'),2),
           array('?p_action=ach&sa=lnp&'.$str_dossier,_('Liste dépenses non payées'),_('Liste des ventes non payées'),3),
           array('commercial.php?p_action=supplier&'.$str_dossier,_('Fournisseurs'),_('Fournisseurs')),
           array('?p_action=impress&type=jrn&'.$str_dossier,_('Impression'),_('Impression'))
       );

$sa=(isset ($_REQUEST['sa']))?$_REQUEST['sa']:-1;
$def=1;
switch ($sa)
{
case 'n':
    $def=1;
    $use_predef=0;
    break;
case 'p':
    $def=1;
    $use_predef=1;
    break;
case 'l':
    $def=2;
    break;
case 'lnp':
    $def=3;
    break;
}
//if ( $_REQUEST['p_action'] == 'fournisseur') $def=5;
echo '<div class="lmenu">';
echo ShowItem($array,'H','mtitle','mtitle',$def);
echo '</div>';
$href=basename($_SERVER['PHP_SELF']);

//----------------------------------------------------------------------
// Encode a new invoice
// empty form for encoding
//----------------------------------------------------------------------
if ( $def==1 || $def == 4 )
{
// Check privilege
    if ( isset($_REQUEST['p_jrn']))
        if (     $User->check_jrn($_REQUEST['p_jrn']) != 'W' )
        {
            NoAccess();
            exit -1;
        }

    /* if a new invoice is encoded, we display a form for confirmation */
    if ( isset ($_POST['view_invoice'] ) )
    {
        $Ledger=new Acc_Ledger_Purchase($cn,$_POST['p_jrn']);
        try
        {
            $Ledger->verify($_POST);
        }
        catch (Exception $e)
        {
            alert($e->getMessage());
            $correct=1;
        }
        // if correct is not set it means it is correct
        if ( ! isset($correct))
        {
            echo '<div class="content">';

            echo '<form action="'.$href.'"  enctype="multipart/form-data" method="post">';
            echo HtmlInput::hidden('sa','n');
            echo HtmlInput::hidden('p_action','ach');
            echo dossier::hidden();
            echo $Ledger->confirm($_POST );

            $chk=new ICheckBox();
            $chk->selected=false;
            echo "<br>Sauvez cette op&eacute;ration comme modèle ?";
            echo $chk->input('opd_save');
            echo '<hr>';
            echo HtmlInput::submit("record",_("Enregistrement"),'onClick="return verify_ca(\'\');"');
            echo HtmlInput::submit('correct',_("Corriger"));
            echo '</form>';

            echo '</div>';

            exit();
        }
    }
    //------------------------------
    /* Record the invoice */
    //------------------------------

    if ( isset($_POST['record']) )
    {
        $Ledger=new Acc_Ledger_Purchase($cn,$_POST['p_jrn']);
        try
        {
            $Ledger->verify($_POST);
        }
        catch (Exception $e)
        {
            alert($e->getMessage());
            $correct=1;
        }
        if ( ! isset($correct))
        {
            echo '<div class="content">';
            $Ledger=new Acc_Ledger_Purchase($cn,$_POST['p_jrn']);
            $internal=$Ledger->insert($_POST);


            /* Save the predefined operation */
            if ( isset($_POST['opd_save']) && $User->check_action(PARPREDE)==1)
            {
                $opd=new Pre_op_ach($cn);
                $opd->get_post();
                $opd->save();
            }

            /* Show button  */
            $jr_id=$cn->get_value('select jr_id from jrn where jr_internal=$1',array($internal));

            echo '<h2 class="info"  style="margin-left:20%;width:60%;margin-right:20%;">'.$Ledger->get_name().'</h2>';
            echo "<h2 >"._('Opération sauvée')." $internal ";
            if ( $Ledger->pj != '') echo ' Piece : '.h($Ledger->pj);
            echo "</h2>";
            if ( strcmp($Ledger->pj,$_POST['e_pj']) != 0 )
            {
                echo '<h3 class="notice"> '._('Attention numéro pièce existante, elle a du être adaptée').'</h3>';
            }
            if (isset($Ledger->doc))
            {
                echo $Ledger->doc.'<hr>';
            }
            /* Save the additional information into jrn_info */
            $obj=new Acc_Ledger_Info($cn);
            $obj->save_extra($Ledger->jr_id,$_POST);
            printf ('<a class="detail" style="display:inline" href="javascript:modifyOperation(%d,%d)">%s</a><hr>',
                    $jr_id,dossier::id(),$internal);
            echo HtmlInput::button_anchor(_('Nouvelle dépense'),$href.'?p_action=ach&sa=n&'.dossier::get());
            echo '</div>';
            exit();
        }
    }
    //  ------------------------------
    /* Display a blank form or a form with predef operation */
    /* or a form for correcting */
    //  ------------------------------

    echo '<div class="content">';
    echo "<FORM NAME=\"form_detail\" METHOD=\"POST\" >";

    $array=(isset($_POST['correct'])||isset ($correct))?$_POST:null;
    $Ledger=new Acc_Ledger_Purchase($cn,0);
//
// pre defined operation
//

    if ( !isset($_REQUEST ['p_jrn']))
    {
        $def_ledger=$Ledger->get_first('ach');
        $Ledger->id=$def_ledger['jrn_def_id'];
    }
    else
        $Ledger->id=$_REQUEST ['p_jrn'];


    /* request for a predefined operation */
    if ( isset($use_predef) && $use_predef == 1 && isset($_REQUEST['pre_def']) )
    {
        // used a predefined operation
        //
        $op=new Pre_op_ach($cn);
        $op->set_od_id($_REQUEST['pre_def']);
        $p_post=$op->compute_array();
        $Ledger->id=$_REQUEST ['p_jrn_predef'];
        $p_post['p_jrn']=$Ledger->id;
        echo $Ledger->input($p_post);
        echo '<div class="content">';
        echo $Ledger->input_paid();
        echo '</div>';
        echo '<script>';
        echo 'compute_all_ledger();';
        echo '</script>';
    }
    else
    {
        echo $Ledger->input($array);
        echo HtmlInput::hidden("p_action","ach");
        echo HtmlInput::hidden("sa","p");
        echo '<div class="content">';
        echo $Ledger->input_paid();
        echo '</div>';
        echo '<script>';
        echo 'compute_all_ledger();';
        echo '</script>';

    }
    echo '<div class="content">';
    echo HtmlInput::button('act',_('Actualiser'),'onClick="compute_all_ledger();"');
    echo HtmlInput::submit("view_invoice",_("Enregistrer"));
    echo HtmlInput::reset(_('Effacer '));
    echo '</div>';
    echo "</FORM>";

    echo '<form method="GET" action="'.$href.'">';
    echo HtmlInput::hidden("sa","p");
    echo HtmlInput::hidden("p_action","ach");
    echo dossier::hidden();
    echo HtmlInput::hidden('p_jrn_predef',$Ledger->id);
    $op=new Pre_op_ach($cn);
    $op->set('ledger',$Ledger->id);
    $op->set('ledger_type',"ACH");
    $op->set('direct','f');
    echo $op->form_get();

    echo '</form>';
    echo create_script(" get_last_date()");

    echo '</div>';


    exit();
}
//-------------------------------------------------------------------------------
// Listing
//--------------------------------------------------------------------------------
if ( $def == 2 )
{
    echo '<div class="content">';
// Check privilege
    if ( isset($_REQUEST['p_jrn']) &&
            $User->check_jrn($_REQUEST['p_jrn']) == 'X')
    {

        NoAccess();
        exit -1;
    }

    $Ledger=new Acc_Ledger_Purchase($cn,0);
    if ( !isset($_REQUEST['p_jrn']))
    {
        $Ledger->id=-1;
    }
    else
        $Ledger->id=$_REQUEST['p_jrn'];
    echo $Ledger->display_search_form();
    //------------------------------
    // UPdate the payment
    //------------------------------
    if ( isset ( $_GET ['paid']))
    {
        $Ledger->update_paid($_GET);
    }
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


    echo '<form method="GET" id="fpaida" action="'.$href.'">';
    echo HtmlInput::hidden("sa","l");
    echo HtmlInput::hidden("p_action","ach");
    echo dossier::hidden();
    echo $bar;
    list($count,$html)= $Ledger->list_operation($sql,$offset,1);
    echo $html;
    echo $bar;
    echo '<p>'.HtmlInput::submit('paid',_('Mise à jour paiement')).IButton::select_checkbox('fpaida').IButton::unselect_checkbox('fpaida').'</p>';

    echo '</form>';

    echo '</div>';
    exit();

}
//---------------------------------------------------------------------------
// Listing unpaid
//---------------------------------------------------------------------------
if ( $def==3 )
{
    echo '<div class="content">';
// Check privilege
    if ( isset($_REQUEST['p_jrn']) &&
            $User->check_jrn($_REQUEST['p_jrn']) == 'X')
    {
        NoAccess();
        exit -1;
    }

    $Ledger=new Acc_Ledger_Purchase($cn,0);
    if ( !isset($_REQUEST['p_jrn']))
    {
        $Ledger->id=-1;
    }
    else
        $Ledger->id=$_REQUEST['p_jrn'];
    echo $Ledger->display_search_form();
    //------------------------------
    // UPdate the payment
    //------------------------------
    if ( isset ( $_GET ['paid']))
    {
        $Ledger->update_paid($_GET);
    }
    /*  compute the sql stmt */
    list($sql,$where)=$Ledger->build_search_sql($_GET);
    if ( trim($where) != '')
        $sql .= ' and '.SQL_LIST_UNPAID_INVOICE;
    else
        $sql .= ' where '.SQL_LIST_UNPAID_INVOICE;

    $max_line=$cn->count_sql($sql);

    $step=$_SESSION['g_pagesize'];
    $page=(isset($_GET['offset']))?$_GET['page']:1;
    $offset=(isset($_GET['offset']))?$_GET['offset']:0;
    $bar=jrn_navigation_bar($offset,$max_line,$step,$page);


    echo '<form method="GET" id="fpaida" action="'.$href.'">';
    echo HtmlInput::hidden("sa","lnp");
    echo HtmlInput::hidden("p_action","ach");
    echo dossier::hidden();
    echo $bar;
    list($count,$html)= $Ledger->list_operation($sql,$offset,1);
    echo $html;
    echo $bar;

    echo '<p>'.HtmlInput::submit('paid',_('Mise à jour paiement')).IButton::select_checkbox('fpaida').IButton::unselect_checkbox('fpaida').'</p>';
    echo '</form>';

    echo '</div>';
    exit();
}
