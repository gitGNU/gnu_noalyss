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
global $g_user;

$gDossier=dossier::id();


$cn=new Database(dossier::id());
$menu_action="?ledger_type=fin&ac=".$_REQUEST['ac']."&".dossier::get();

$Ledger=new Acc_Ledger_Fin($cn,0);

//--------------------------------------------------------------------------------
// Encode a new financial operation
//--------------------------------------------------------------------------------

if ( isset($_REQUEST['p_jrn']))
	$Ledger->id=$_REQUEST['p_jrn'];
else
{
	$def_ledger=$Ledger->get_first('fin');
	$Ledger->id=$def_ledger['jrn_def_id'];
}
$jrn_priv=$g_user->get_ledger_access($Ledger->id);
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
		echo '<h2 class="info">'._('Confirmation').' </h2>';
		echo '<form name="form_detail" class="print" enctype="multipart/form-data"  METHOD="POST">';
		echo HtmlInput::hidden('ac',$_REQUEST['ac']);
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
		echo '<div id="jrn_name_div">';
		echo '<h2 id="jrn_name" style="display:inline">' . $Ledger->get_name() . '</h2>';
		echo '</div>';

		echo '<div class="content">';
		$a= $Ledger->insert($_POST);
		echo '<h2 class="info">'._('Enregistrement').' </h2>';
		echo '<div class="content">';
		echo $a;
		echo '</div>';
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


echo '<form class="print" name="form_detail" enctype="multipart/form-data" METHOD="POST">';
echo HtmlInput::hidden('ledger_type','fin');
echo HtmlInput::hidden('ac',$_REQUEST['ac']);
$array=( isset($correct))?$_POST:null;

// show select ledger
echo $Ledger->input($array);
echo HtmlInput::button('add_item',_('Ajout article'),   ' onClick="ledger_fin_add_row()"');
echo HtmlInput::submit('save',_('Sauve'));
echo HtmlInput::reset(_('Effacer'));

echo create_script(" get_last_date();ajax_saldo('first_sold');");
exit();


exit();