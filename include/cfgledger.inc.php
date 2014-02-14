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
 * @file
 *
 * @brief Create, update and delete ledgers
 *
 */
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
require_once('class_dossier.php');
require_once ("ac_common.php");
require_once('class_database.php');
require_once ("class_user.php");
require_once ("user_menu.php");
require_once 'class_acc_ledger.php';

$gDossier=dossier::id();
global $cn;

$ledger=new Acc_Ledger($cn,-1);
$sa=HtmlInput::default_value("sa","",$_REQUEST);
//////////////////////////////////////////////////////////////////////////
// Perform request action : update
//////////////////////////////////////////////////////////////////////////
if (isset($_POST['update']))
{
	try
	{
		$ledger->id=$_POST['p_jrn'];
		if ( $ledger->load() == -1) throw new Exception (_('Journal inexistant'));
		$ledger->verify_ledger($_POST);
		$ledger->update($_POST);
	} catch (Exception $e)
	{
		alert($e->getMessage());
	}
}

//////////////////////////////////////////////////////////////////////////
// Perform request action : delete
//////////////////////////////////////////////////////////////////////////
if (isset($_POST['efface']))
{
	$ledger->jrn_def_id=$_POST['p_jrn'];
	$ledger->id=$_POST['p_jrn'];
	$ledger->load();
	$name=$ledger->get_name();
	try {
		$ledger->delete_ledger();
		$sa="";
		echo '<div id="jrn_name_div">';
		echo '<h2 id="jrn_name">'.h($name). "  est effacé"."</h2>";
		echo '</div>';
	}
	catch (Exception $e)
	{
		alert ($e->getMessage());
	}

}

//////////////////////////////////////////////////////////////////////////
// Perform request action : add
//////////////////////////////////////////////////////////////////////////
if (isset($_POST['add']))
{
	try
	{
		$ledger->verify_ledger($_POST);
		$ledger->save_new($_POST);
		$sa="detail";
		$_REQUEST['p_jrn']=$ledger->jrn_def_id;
	}
	catch (Exception $e)
	{
		alert($e->getMessage());
	}
}

//////////////////////////////////////////////////////////////////////////
// Display list of ledgers
//////////////////////////////////////////////////////////////////////////
echo '<div class="lmenu">';
echo $ledger->listing();
echo '</div>';



//////////////////////////////////////////////////////////////////////////
//Display detail of ledger
//////////////////////////////////////////////////////////////////////////

switch ($sa)
{
	case 'detail': /* detail of a ledger */
		try
		{
			$ledger->id=$_REQUEST['p_jrn'];
			echo '<div class="redcontent">';
			echo '<form method="POST">';
			echo $ledger->display_ledger();
			echo '<INPUT TYPE="SUBMIT" class="button" VALUE="'._("Sauve").'" name="update">
			<INPUT TYPE="RESET" class="button" VALUE="Reset">
			<INPUT TYPE="submit" class="button"  name="efface" value="'._("Efface").'" onClick="return confirm(\'Vous effacez ce journal ?\')">';
			echo '</FORM>';
			echo "</div>";
		}
		catch (Exception $e)
		{
			alert($e->getMessage());
		}
		break;
	case 'add': /* Add a new ledger */
		echo '<div class="redcontent">';
		echo '<FORM METHOD="POST">';
		$ledger->input_new();
		echo HtmlInput::submit('add','Sauver');
		echo '<INPUT TYPE="RESET" class="button" VALUE="Reset">';
		echo '</FORM>';
		echo "</DIV>";
}





html_page_stop();



?>
