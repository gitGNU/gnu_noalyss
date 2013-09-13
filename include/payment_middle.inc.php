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
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
require_once('class_acc_payment.php');
require_once 'class_sort_table.php';
//---------------------------------------------------------------------------
// Common variable
$td='<TD>';
$etd='</td>';
$tr='<tr>';
$etr='</tr>';
$th='<th>';
$eth='</th>';

/*!\file
 * \brief payment mode
 */
$sb=HtmlInput::default_value('sb', "", $_REQUEST);
echo '<div class="content">';

//----------------------------------------------------------------------
// change
if ( $sb=='change')
{
    if ( !isset($_GET['id'])) exit;
    $row=new Acc_Payment($cn,$_GET['id']);
    $row->load();
    $javascript="return confirm('"._('Vous confirmez')."')";
    echo '<form method="post" onsubmit="'.$javascript.'">';
    echo dossier::hidden();
    echo HtmlInput::hidden('sa','mp');
    echo HtmlInput::hidden('sb','save');
    echo HtmlInput::hidden('id',$row->get_parameter("id"));

    echo $row->form();
    echo HtmlInput::submit('save',_('Sauve'));
    echo HtmlInput::submit('delete',_('Efface'));
    echo HtmlInput::button_anchor(_('Retour sans sauver'),
                                  '?p_action=divers&sa=mp&'.dossier::get()."&ac=".$_REQUEST['ac']
                                     );
    echo '</form>';
    exit();
}
//----------------------------------------------------------------------
// Save the change
//
if ( $sb=='save')
{
    $row=new Acc_Payment($cn,$_POST ['id']);
    $row->from_array($_POST);
    $row->update();

}
//---------------------------------------------------------------------------
// Delete a card
//---------------------------------------------------------------------------
if (isset($_POST['delete']))
{
    $row=new Acc_Payment($cn,$_POST['id']);
    $row->from_array($_POST);
    $row->delete();
}
//---------------------------------------------------------------------------
// Insert a new mod of payment
//---------------------------------------------------------------------------
if ( isset($_POST['insert']))
{
    $row=new Acc_Payment($cn);
    $row->from_array($_POST);
    $row->insert();
	$sb="list";
}

//---------------------------------------------------------------------------
// Show form to enter a new one
//---------------------------------------------------------------------------
if ($sb=='ins')
{
    $mp=new Acc_Payment($cn);
    $r=$mp->blank();
    echo '<form method="POST" onsubmit="return confirm(\'Vous confirmez ?\')">';
    echo dossier::hidden();
	HtmlInput::hidden('ac',$_REQUEST['ac']).HtmlInput::hidden('sb','insert');
    echo $r;
    echo HtmlInput::submit('insert',_('Enregistre'));
    echo HtmlInput::button_anchor(_('Retour sans sauver'),
                                  '?p_action=divers&sa=mp&'.dossier::get()."&ac=".$_REQUEST['ac']
                                     );
    echo '</form>';

    exit();
}
//--------------------------------------------------------------------------------
//LIST
//--------------------------------------------------------------------------------
/* Get the data from database */
$header=new Sort_Table();
$base_url=$_SERVER['PHP_SELF']."?".Dossier::get()."&ac=".$_REQUEST['ac'];

$header->add("Libelle",$base_url,"order by mp_lib asc","order by mp_lib desc",'la','ld');
$header->add("Pour le journal",$base_url,"order by jrn_def_name asc","order by jrn_def_name  desc",'ja','jd');
$header->add("Type de fiche",$base_url,"order by fd_label asc","order by fd_label desc",'tc','td');
$header->add("Enregistré dans le journal",$base_url,"order by jrn_target asc","order by jrn_target desc",'jta','jtd');
$header->add("Avec la fiche",$base_url,"order by vw_name asc","order by vw_name desc",'na','nd');

$order=(isset($_REQUEST['ord']))?$_REQUEST['ord']:'la';

$sql=$header->get_sql_order($order);

$array=$cn->get_array("
	select
			mp_id,mp_lib,mp_jrn_def_id,mp_fd_id,mp_qcode,j.jrn_def_id,
			j.jrn_def_name as jrn_def_name,
			j2.jrn_def_name as jrn_target,
			fd_label,
			coalesce(mp_qcode,'A choisir à l''encodage') as vw_name
			from mod_payment as mp
			left join jrn_def as j on (j.jrn_def_id=mp.jrn_def_id)
			left join jrn_def as j2 on (j2.jrn_def_id=mp.mp_jrn_def_id)
			left join fiche_def as fd on (mp.mp_fd_id=fd.fd_id)
			$sql
	");
/* if there are data show them in a table */
if ( ! empty ($array))
{

	require_once 'template/list_mod_payment.php';
	echo HtmlInput::button_anchor("Ajout", $base_url."&sb=ins");
}
echo '</div>';
?>
