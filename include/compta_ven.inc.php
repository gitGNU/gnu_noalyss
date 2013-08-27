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
require_once("class_acc_ledger_sold.php");
require_once ('class_pre_op_ven.php');
require_once ('class_document.php');
require_once ('class_acc_ledger_info.php');
require_once('class_ipopup.php');

$gDossier=dossier::id();
$cn=new Database(dossier::id());
//menu = show a list of ledger
$str_dossier=dossier::get();
$ac="ac=".$_REQUEST['ac'];
global $g_parameter;

//----------------------------------------------------------------------
// Encode a new invoice
// empty form for encoding
//----------------------------------------------------------------------

    $Ledger=new Acc_Ledger_Sold($cn,0);

    // Check privilege
    if ( isset($_REQUEST['p_jrn']) &&
            $g_user->check_jrn($_REQUEST['p_jrn']) != 'W' )
    {

        NoAccess();
        exit -1;
    }

    /* if a new invoice is encoded, we display a form for confirmation */
    if ( isset ($_POST['view_invoice'] ) )
    {
        $Ledger=new Acc_Ledger_Sold($cn,$_POST['p_jrn']);
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
            echo h1('Confirmation','');
            echo_warning("Attention, cette opération n'est pas encore sauvée : vous devez encore confirmer");


			echo '<div class="content">';
            echo '<form class="print" enctype="multipart/form-data" method="post">';
            echo dossier::hidden();
            echo $Ledger->confirm($_POST );
			echo HtmlInput::hidden('ac',$_REQUEST['ac']);
            $chk=new ICheckBox();
            $chk->selected=false;
	    echo '<div style="clear:both">';
            
            echo "<h2>Modèle d'opération</h2>";
            echo "Donnez un nom pour sauver cette opération comme modèle <br>";
	    $opd_name=new IText('opd_name');
	    echo "Nom du modèle ".$opd_name->input();
	    echo '</div>';
            echo '<hr>';
	    echo HtmlInput::hidden('ac',$_REQUEST['ac']);
            echo HtmlInput::submit("record",_("Enregistrement"),'onClick="return verify_ca(\'\');"');
            echo HtmlInput::submit('correct',_("Corriger"));
            echo '</form>';

            echo '</div>';
            echo '</div>';
            exit();
        }
    }
    //------------------------------
    /* Record the invoice */
    //------------------------------

    if ( isset($_POST['record']) )
    {
// Check privilege
        if ( $g_user->check_jrn($_REQUEST['p_jrn']) != 'W' )
        {

            NoAccess();
            exit -1;
        }

        $Ledger=new Acc_Ledger_Sold($cn,$_POST['p_jrn']);
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
            $Ledger=new Acc_Ledger_Sold($cn,$_POST['p_jrn']);
            $internal=$Ledger->insert($_POST);

            /* Save the predefined operation */
            if ( isset($_POST['opd_name']) && trim($_POST['opd_name']) != "" )
            {
                $opd=new Pre_op_ven($cn);
                $opd->get_post();
                $opd->save();
            }

            /* Show button  */
            echo '<h1 > Enregistrement </h1>';
            $jr_id=$cn->get_value('select jr_id from jrn where jr_internal=$1',array($internal));

            echo "<h2 >"._('Opération sauvée');
            if ( $Ledger->pj != '') echo ' Piece : '.h($Ledger->pj);
            echo "</h2>";
            if ( strcmp($Ledger->pj,$_POST['e_pj']) != 0 )
            {
                echo '<h3 class="notice"> '._('Attention numéro pièce existante, elle a du être adaptée').'</h3>';
            }

            printf ('Détail opération : <a class="line" style="display:inline" href="javascript:modifyOperation(%d,%d)">%s</a><hr>',
                    $jr_id,dossier::id(),$internal);
	    echo $Ledger->confirm($_POST,true);
            /* Show link for Invoice */
            if (isset ($Ledger->doc) )
            {
                echo '<span class="invoice">';
                echo $Ledger->doc;
                echo '</span>';
            }


            /* Save the additional information into jrn_info */
            $obj=new Acc_Ledger_Info($cn);
            $obj->save_extra($Ledger->jr_id,$_POST);


            echo '</div>';
            exit();
        }
    }
    //  ------------------------------
    /* Display a blank form or a form with predef operation */
    //  ------------------------------

    $array=(isset($_POST['correct'])||isset ($correct))?$_POST:null;
    $Ledger=new Acc_Ledger_Sold($cn,0);
//
// pre defined operation
//

    if ( !isset($_REQUEST ['p_jrn']) )
    {
        $def_ledger=$Ledger->get_first('ven',2);
		if (empty($def_ledger))
	{
		exit('Pas de journal disponible');
	}
        $Ledger->id=$def_ledger['jrn_def_id'];
    }
    else
        $Ledger->id=$_REQUEST ['p_jrn'];
	if (isset ($_REQUEST['p_jrn_predef'])){
		$Ledger->id=$_REQUEST['p_jrn_predef'];
	}

   echo '<div id="predef_form">';
    echo '<form style="display:inline" method="GET" >';
	echo HtmlInput::hidden('ac',$_REQUEST['ac']);
    echo dossier::hidden();
    echo HtmlInput::hidden('p_jrn_predef',$Ledger->id);
    $op=new Pre_op_ven($cn);
    $op->set('ledger',$Ledger->id);
    $op->set('ledger_type',"VEN");
    $op->set('direct','f');
    echo $op->form_get();
    echo '</form>';
    echo '</div>';

   echo '<div class="content">';
 echo "<FORM class=\"print\" NAME=\"form_detail\" METHOD=\"POST\" >";
    /* request for a predefined operation */
    if (  isset($_REQUEST['pre_def']) && ! isset($_POST['correct']) )
    {
        // used a predefined operation
        //
        $op=new Pre_op_ven($cn);
        $op->set_od_id($_REQUEST['pre_def']);
        $p_post=$op->compute_array();
        $Ledger->id=$_REQUEST ['p_jrn_predef'];

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
        echo HtmlInput::hidden("ledger_type","VEN");
	  echo HtmlInput::hidden("ac",$_REQUEST['ac']);
        echo HtmlInput::hidden("sa","p");
        echo $Ledger->input($array);
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

    /* if we suggest the pj n# the run the script */
    if ( $g_parameter->MY_PJ_SUGGEST=='Y')
    {
		echo '<script> update_pj()</script>';
    }
	if (!isset($_POST['e_date']) && $g_parameter->MY_DATE_SUGGEST=='Y')
	{
		echo create_script(" get_last_date()");
	}
	
exit();
?>
