<?php

/*
 *   This file is part of NOALYSS.
 *
 *   NOALYSS is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   NOALYSS is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with NOALYSS; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

// Copyright Author Dany De Bontridder danydb@aevalys.eu

/**
 *\file
 * \brief file included to manage all the sold operation
 */
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
require_once("class_icheckbox.php");
require_once("class_acc_ledger_purchase.php");
require_once ('class_pre_op_ach.php');
require_once('class_ipopup.php');
$gDossier = dossier::id();
global $g_parameter;
$cn = new Database(dossier::id());
//menu = show a list of ledger
$str_dossier = dossier::get();
$ac = "ac=" . $_REQUEST['ac'];

// Check privilege
if (isset($_REQUEST['p_jrn']))
	if ($g_user->check_jrn($_REQUEST['p_jrn']) != 'W')
	{
		NoAccess();
		exit - 1;
	}

/* if a new invoice is encoded, we display a form for confirmation */
if (isset($_POST['view_invoice']))
{
	$Ledger = new Acc_Ledger_Purchase($cn, $_POST['p_jrn']);
	try
	{
		$Ledger->verify($_POST);
	}
	catch (Exception $e)
	{
		alert($e->getMessage());
		$correct = 1;
	}
	// if correct is not set it means it is correct
	if (!isset($correct))
	{
		echo '<div class="content">';
		 echo h1('Confirmation','');
                echo_warning("Attention, cette opération n'est pas encore sauvée : vous devez encore confirmer");


		echo '<form enctype="multipart/form-data" method="post" class="print">';
		echo dossier::hidden();

		echo $Ledger->confirm($_POST);
		echo HtmlInput::hidden('ac', $_REQUEST['ac']);
		echo '<div style="clear:both">';

                echo Pre_operation::save_propose();
                echo '</div>';
		echo HtmlInput::submit("record", _("Enregistrement"), 'onClick="return verify_ca(\'\');"');
		echo HtmlInput::submit('correct', _("Corriger"));
		echo '</form>';
		echo '</div>';
		echo '</div>';

		exit();
	}
}
//------------------------------
/* Record the invoice */
//------------------------------

if (isset($_POST['record']))
{
	$Ledger = new Acc_Ledger_Purchase($cn, $_POST['p_jrn']);
	try
	{
		$Ledger->verify($_POST);
	}
	catch (Exception $e)
	{
		alert($e->getMessage());
		$correct = 1;
	}
	// record the invoice
	if (!isset($correct))
	{
		echo '<div class="content">';

		$Ledger = new Acc_Ledger_Purchase($cn, $_POST['p_jrn']);
		$internal = $Ledger->insert($_POST);


		/* Save the predefined operation */
                if ( isset($_POST['opd_name']) && trim($_POST['opd_name']) != "" )
		{
			$opd = new Pre_op_ach($cn);
			$opd->get_post();
			$opd->save();
		}

		/* Show button  */
		$jr_id = $cn->get_value('select jr_id from jrn where jr_internal=$1', array($internal));

		echo '<h1> Enregistrement </h1>';
		// echo "<h2 >" . _('Opération sauvée') . " $internal ";
		// if ($Ledger->pj != '')
		//	echo ' Piece : ' . h($Ledger->pj);
		// echo "</h2>";
		// if (strcmp($Ledger->pj, $_POST['e_pj']) != 0)
		// {
		//	echo '<h3 class="notice"> ' . _('Attention numéro pièce existante, elle a du être adaptée') . '</h3>';
		// }
		/* Save the additional information into jrn_info */
		$obj = new Acc_Ledger_Info($cn);
		$obj->save_extra($Ledger->jr_id, $_POST);
		//printf('<a class="line" style="display:inline" href="javascript:modifyOperation(%d,%d)">%s</a><hr>', $jr_id, dossier::id(), $internal);
		// Feedback
		echo $Ledger->confirm($_POST, true);
		if (isset($Ledger->doc))
		{
                     echo '<h2>Document</h2>';
                     echo $Ledger->doc;
		}

		echo '</div>';
		exit();
	}
}
//  ------------------------------
/* Display a blank form or a form with predef operation */
/* or a form for correcting */
//  ------------------------------

echo '<div class="content">';
//


$array = (isset($_POST['correct']) || isset($correct)) ? $_POST : null;
$Ledger = new Acc_Ledger_Purchase($cn, 0);


if (!isset($_REQUEST ['p_jrn']))
{
	$def_ledger = $Ledger->get_first('ach',2);
	if ( empty ($def_ledger))
	{
		exit('Pas de journal disponible');
	}
	$Ledger->id = $def_ledger['jrn_def_id'];
}
else
	$Ledger->id = $_REQUEST ['p_jrn'];

if (isset ($_REQUEST['p_jrn_predef'])){
	$Ledger->id=$_REQUEST['p_jrn_predef'];
}
// pre defined operation
//
echo '<div id="predef_form">';
echo HtmlInput::hidden('p_jrn_predef', $Ledger->id);
$op = new Pre_op_ach($cn);
$op->set('ledger', $Ledger->id);
$op->set('ledger_type', "ACH");
$op->set('direct', 'f');
$url=http_build_query(array('p_jrn_predef'=>$Ledger->id,'ac'=>$_REQUEST['ac'],'gDossier'=>dossier::id()));
echo $op->form_get('do.php?'.$url);
echo '</div>';
echo '</div>';

echo '<div class="content">';
echo "<FORM class=\"print\"NAME=\"form_detail\" METHOD=\"POST\" >";
/* request for a predefined operation */
if ( isset($_REQUEST['pre_def']) && !isset($_POST['correct']))
{
	// used a predefined operation
	//
        $op = new Pre_op_ach($cn);
	$op->set_od_id($_REQUEST['pre_def']);
	$p_post = $op->compute_array();
	$Ledger->id = $_REQUEST ['p_jrn_predef'];
	$p_post['p_jrn'] = $Ledger->id;
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
	echo HtmlInput::hidden("p_action", "ach");
	echo HtmlInput::hidden("sa", "p");
	echo '<div class="content">';
	echo $Ledger->input_paid();
	echo '</div>';
	echo '<script>';
	echo 'compute_all_ledger();';
	echo '</script>';
}
echo '<div class="content">';
echo HtmlInput::button('act', _('Actualiser'), 'onClick="compute_all_ledger();"');
echo HtmlInput::submit("view_invoice", _("Enregistrer"));
echo HtmlInput::reset(_('Effacer '));
echo '</div>';
echo "</FORM>";

if (!isset($_POST['e_date']) && $g_parameter->MY_DATE_SUGGEST=='Y')
	echo create_script(" get_last_date()");
echo create_script(" update_name()");
echo '</div>';


exit();
// end record invoice
?>