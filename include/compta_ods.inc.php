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
	try {
			$ledger->verify($_POST);
			require_once 'operation_ods_confirm.inc.php';
	} catch (Exception $e)
	{
		echo alert($e->getMessage());
		require('operation_ods_new.inc.php');

	}
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

