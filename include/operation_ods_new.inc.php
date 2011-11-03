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
/* $Revision: 4267 $ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/*!\file
 *
 *
 * \brief to write into the ledgers ODS a new operation
 */
require_once 'class_pre_op_ods.php';
require_once 'class_iconcerned.php';

global $g_user;
$cn=new Database(dossier::id());

$id=(isset ($_REQUEST['p_jrn']))?$_REQUEST['p_jrn']:-1;
$ledger=new Acc_Ledger($cn,$id);
$first_ledger=$ledger->get_first('ODS');
$ledger->id=($ledger->id==-1)?$first_ledger['jrn_def_id']:$id;

// check if we can write in the ledger
if ( $g_user->check_jrn($ledger->id)=='X')
{
	alert("Vous ne pouvez pas écrire dans ce journal, contacter votre administrateur");
	exit();
}
echo '<div class="content">';
echo '<div id="predef_form">';
echo '<form method="GET" action="do.php">';
echo HtmlInput::hidden("action", "use_opd");
echo HtmlInput::hidden("ac",$_REQUEST['ac']);
echo dossier::hidden();
echo HtmlInput::hidden('p_jrn_predef', $ledger->id);
$op = new Pre_op_ods($cn);
$op->set('ledger', $ledger->id);
$op->set('ledger_type', "ODS");
$op->set('direct', 't');
echo $op->form_get();

echo '</form>';
echo '</div>';
echo '<div id="jrn_name_div">';
echo '<h2 id="jrn_name" style="display:inline">' . $ledger->get_name() . '</h2>';
echo '</div>';

// Show the predef operation
// Don't forget the p_jrn
$p_post=$_POST;
if ( isset ($_GET['action']) && ! isset($_POST['correct']))
{
	if ( $_GET['action']=='use_opd')
	{
		// get data from predef. operation
		$op=new Pre_op_advanced($cn);
        $p_post=null;
        if ( isset($_REQUEST['pre_def']) && $_REQUEST['pre_def'] != '')
        {
            $op->set_od_id($_REQUEST['pre_def']);
            $p_post=$op->compute_array();
        }
	}
}


echo '<form method="post"  class="print">';
echo dossier::hidden();
echo HtmlInput::request_to_hidden(array('ac'));

echo $ledger->input($p_post);


echo HtmlInput::button('add', _('Ajout d\'une ligne'), 'onClick="quick_writing_add_row()"');

echo HtmlInput::submit('summary', _('Sauvez'));

echo '<table class="info_op">'.
 '<tr>'.td(_('Débit')) . '<td id="totalDeb"></td>' .
 td(_('Crédit')) . ' <td id="totalCred"></td>' .
 td(_('Difference')) . ' <td id="totalDiff"></td>';
echo '</table>';

$iconcerned=new IConcerned('jrn_concerned');
$iconcerned->extra=0;
echo "Opération rapprochée : ".$iconcerned->input();
echo '</form>';

echo "<script>checkTotalDirect();</script>";

echo create_script(" get_last_date()");

echo '</div>';

?>
