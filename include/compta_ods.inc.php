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

/* !\file
 *
 *
 * \brief to write directly into the ledgers,the stock and the tables
 * quant_purchase and quant_sold are not changed by this
 *
 */
require_once("class_icheckbox.php");
require_once ('class_acc_ledger.php');
require_once ('class_acc_reconciliation.php');
require_once('ac_common.php');
require_once('class_periode.php');
require_once('function_javascript.php');
require_once('class_ipopup.php');

global $g_user;

$cn = new Database(dossier::id());

$id = (isset($_REQUEST['p_jrn'])) ? $_REQUEST['p_jrn'] : -1;
$ledger = new Acc_Ledger($cn, $id);
$first_ledger = $ledger->get_first('ODS');
$ledger->id = ($ledger->id == -1) ? $first_ledger['jrn_def_id'] : $id;

/* !\brief show a form for quick_writing */
$id = (isset($_REQUEST['p_jrn'])) ? $_REQUEST['p_jrn'] : -1;
$def = -1;
$ledger->with_concerned = true;




if ($g_user->check_jrn($id) == 'X')
{
	NoAccess();
	exit - 1;
}
if (!isset($_POST['summary']) && !isset($_POST['save']))
{
	require('operation_ods_new.inc.php');
	exit();
}
elseif (isset($_POST['summary']))
{
	require_once 'operation_ods_confirm.inc.php';
	exit();
}
elseif (isset($_POST['save']))
{
	$array = $_POST;

	try
	{
		$ledger->save($array);
		$jr_id = $cn->get_value('select jr_id from jrn where jr_internal=$1', array($ledger->internal));

		echo '<h2> Op&eacute;ration enregistr&eacute;e  Piece ' . h($ledger->pj) . '</h2>';
		if (strcmp($ledger->pj, $_POST['e_pj']) != 0)
		{
			echo '<h3 class="notice">' . _('Attention numéro pièce existante, elle a du être adaptée') . '</h3>';
		}
		printf('<a class="detail" style="display:inline" href="javascript:modifyOperation(%d,%d)">%s</a><hr>', $jr_id, dossier::id(), $ledger->internal);

		// show feedback
		echo $ledger->confirm($_POST, true);
	}
	catch (Exception $e)
	{
		require('operation_ods_new.inc.php');
		alert($e->getMessage());
	}
	exit();
}
exit();



if ( $g_user->check_jrn($id) == 'X' )
{
    alert(_("L'acces a ce journal est interdit, \n contactez votre responsable"));
    exit();
}

//======================================================================
// See the ledger listing
if ($sa == 'l' && $id != -1)
{
// Check privilege

    if (  $g_user->check_jrn($id) == 'X')
    {
        NoAccess();
        exit -1;
    }
    show_qw_menu(1);
    echo '<div class="content">';
    $Ledger=new Acc_Ledger($cn,$id);

    $type=$Ledger->get_type();
    $href=basename($_SERVER['PHP_SELF']);

    echo '<form method="GET" action="'.$href.'">';
    echo HtmlInput::hidden("sa","l");
    echo HtmlInput::hidden("p_jrn",$id);
    echo HtmlInput::hidden("ac",$_REQUEST['sa']);
    echo dossier::hidden();
    echo $Ledger->search_form($type,0);
    echo HtmlInput::submit("qwlist",_("Recherche"));
    echo '</form>';

    $array=$_GET;
    list($sql,$where)=$Ledger->build_search_sql($array);

    $max_line=$cn->count_sql($sql);

    $step=$_SESSION['g_pagesize'];
    $page=(isset($_GET['offset']))?$_GET['page']:1;
    $offset=(isset($_GET['offset']))?$_GET['offset']:0;
    $bar=jrn_navigation_bar($offset,$max_line,$step,$page);


    echo $bar;
    list($count,$html)= $Ledger->list_operation($sql,$offset,0);
    echo $html;
    echo $bar;
   /*
     * Export to csv
     */
    $r=HtmlInput::get_to_hidden(array('l','date_start','date_end','desc','amount_min','amount_max','qcode','accounting','unpaid','gDossier','ledger_type','ac'));
    if (isset($_GET['r_jrn'])) {
      foreach ($_GET['r_jrn'] as $k=>$v)
	$r.=HtmlInput::hidden('r_jrn['.$k.']',$v);
    }
    echo '<form action="export.php" method="get">';
    echo $r;
    echo HtmlInput::hidden('act','CSV/histo');
    echo HtmlInput::submit('viewsearch','Export vers CSV');

    echo '</form>';

    echo '</div>';
    exit();
}

//======================================================================
// User can write ?
// Write into the ledger

if ( $g_user->check_jrn($id) == 'X' )
{
    alert(_("Vous ne pouvez pas accèder à ce journal, contactez votre responsable"));
    exit -1;
}

if ( $g_user->check_jrn($id)=='W' )
{
    if ( ($sa=='n' || isset($_POST['correct_it'])) && ! isset($_POST['summary']))
    {
        $array=$_POST;
        $default_periode=$g_user->get_periode();
        /* check if the ledger is closed */

        show_qw_menu();
        show_direct_form($cn,$ledger,$array);
        exit();
    }

// reload with a predefined operation
//
    if ( isset ($_GET['use_opd']))
    {
        $op=new Pre_op_advanced($cn);
        $p_post=null;
        if ( isset($_REQUEST['pre_def']) && $_REQUEST['pre_def'] != '')
        {
            $op->set_od_id($_REQUEST['pre_def']);
            //$op->p_jrn=$id;

            $p_post=$op->compute_array();
        }
        show_qw_menu();
        show_direct_form($cn,$ledger,$p_post);

        exit();

    }

    if ( isset($_POST['save_it' ]))
    {
        $array=$_POST;

        try
        {
            $ledger->save($array);
            $jr_id=$cn->get_value('select jr_id from jrn where jr_internal=$1',array($ledger->internal));

            echo '<h2> Op&eacute;ration enregistr&eacute;e  Piece '.h($ledger->pj).'</h2>';
            if ( strcmp($ledger->pj,$_POST['e_pj']) != 0 )
            {
                echo '<h3 class="notice">'._('Attention numéro pièce existante, elle a du être adaptée').'</h3>';
            }
            printf ('<a class="detail" style="display:inline" href="javascript:modifyOperation(%d,%d)">%s</a><hr>',
                    $jr_id,dossier::id(),$ledger->internal);

            echo HtmlInput::button_anchor(_('Autre opération dans ce journal'),
                                          "?".dossier::get().
                                              '&show_form'.
                                              '&p_action=quick_writing&p_jrn='.
                                              $_REQUEST['p_jrn']);

        }
        catch (Exception $e)
        {
            alert ($e->getMessage());
            show_qw_menu();
            show_direct_form($cn,$ledger,$_POST);
        }
        exit();
    }
} // if check_jrn=='W'
else
{
    show_qw_menu();
}
