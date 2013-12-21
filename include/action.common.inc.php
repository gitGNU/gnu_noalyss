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

/**\file
 * \brief this file is common to suivi client, suivi fournisseur, suivi
 * administration.
 * The needed variables are
 * - $cn for the database connection
 * - $sub_action sa from suivi courrier but sc from Suivi client, fournisseur...
 *
 */
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
$supl_hidden = '';
if (isset($_REQUEST['sc']))
	$supl_hidden.=HtmlInput::hidden('sc', $_REQUEST['sc']);
if (isset($_REQUEST['f_id']))
	$supl_hidden.=HtmlInput::hidden('f_id', $_REQUEST['f_id']);
if (isset($_REQUEST['sb']))
	$supl_hidden.=HtmlInput::hidden('sb', $_REQUEST['sb']);
$supl_hidden.=HtmlInput::hidden('ac', $_REQUEST['ac']);
/*-----------------------------------------------------------------------------*/
/* For other action
/*-----------------------------------------------------------------------------*/
if ( isset ($_POST['other_action_bt'])) {
    /**
     * The action id are in the array mag_id
     * the tag to remove are vin the array remtag
     * the tag to add are in the array addtag
     * the state in ag_state
     */
    if (  isset ($_POST['mag_id'])) {
        switch ($_POST['othact']) {
            case 'IMP':
                //Impression
                Follow_Up::action_print($cn,$_POST);
                exit(0);
                break;
            case 'ST':
                // Etat
                Follow_Up::action_set_state($cn, $_POST);
                break;
            case 'ETIREM':
                //tag
                Follow_Up::action_tag_remove($cn, $_POST);
                break;
            case 'ETIADD':
                Follow_Up::action_tag_add($cn, $_POST);
                break;
            case 'ETICLEAR':
                Follow_Up::action_tag_clear($cn,$_POST);
                break;
            case 'DOCREM':
                Follow_Up::action_remove($cn, $_POST);
                break;
        }
    }
}

/* --------------------------------------------------------------------------- */
/* We ask to generate the document */
/* --------------------------------------------------------------------------- */
if (isset($_POST['generate']))
{
	$act = new Follow_Up($cn);
	$act->fromArray($_POST);
	if ($act->ag_id == 0)
	{
		$act->save();
		$ag_id = $act->ag_id;
	}
	else
	{
		$act->Update();
	}
	$act->generate_document($_POST['doc_mod'], $_POST);
	$sub_action = 'detail';
}
/* for delete  */
if (isset($_POST['delete']))
	$sub_action = 'delete';
if ($sub_action == "")
	$sub_action = "list";

// if correction is asked go to directly to add_action
if (isset($_POST['corr']))
{
	$ag_comment = urldecode($_POST['ag_comment']);
	$sub_action = "add_action";
}
// if this page is called from another menu (customer, supplier,...)
// a button back is added
//----------------------------------------------------------------------
// Update the detail
// Add a new action related to this one or update
//----------------------------------------------------------------------
if ($sub_action == "update")
{
	// Update the modification
	if (isset($_POST['save']))
	{
		$act2 = new Follow_Up($cn);
		$act2->fromArray($_POST);
		if ($g_user->can_write_action($act2->ag_id) == false )
		{
			echo '<div class="redcontent">';
			echo '<h2 class="error"> Cette action ne vous est pas autorisée Contactez votre responsable</h2>';
			echo '</div>';
			exit();
		}
		$sub_action = "detail";
		put_global(array(array('key' => "sa", "value" => "detail")));
		$act2->Update() ;
	}
	//----------------------------------------------------------------------
	// Add a related action
	//----------------------------------------------------------------------
	if (isset($_POST['add_action_here']))
	{
		$act = new Follow_Up($cn);


		//----------------------------------------
		// puis comme ajout normal (copier / coller )
		$act->fromArray($_POST);
		$act->ag_id = 0;
		$act->d_id = 0;
		$act->action = $_POST['ag_id'];

		echo '<div class="content">';

		// Add hidden tag
		echo '<form  enctype="multipart/form-data" action="do.php" method="post"">';

		$act->ag_comment = "";
		if (isset($_REQUEST['qcode_dest']))
			$act->qcode_dest = $_REQUEST['qcode_dest'];
		echo $act->Display('NEW', false, $base, $retour);

		echo '<input type="hidden" name="ac" value="' . $_REQUEST['ac'] . '">';
		echo '<input type="hidden" name="sa" value="save_action_st2">';
		echo '<input type="submit" class="button" name="save_action_st2" value="' . _('Enregistrer') . '">';
		echo '<input type="submit" class="button" name="generate" value="' . _('Génère le document') . '"></p>';
		echo $supl_hidden;
		echo '</form>';
		echo '</div>';
	}
}
//--------------------------------------------------------------------------------
// Show the detail of an action
// permit the update
if ($sub_action == 'detail')
{
	echo '<div class="content">';
	$act = new Follow_Up($cn);
	$act->ag_id = $ag_id;

	echo $act->get();
	if ($g_user->can_write_action($ag_id) == true)
	{
		echo '<form  enctype="multipart/form-data"  class="print" action="do.php"  method="post"   >';
		echo $supl_hidden;
		echo HtmlInput::hidden('ac', $_REQUEST['ac']);
		echo dossier::hidden();
		echo $act->Display('UPD', false, $base, $retour);
		echo '<input type="hidden" name="sa" value="update">';
		echo HtmlInput::submit("save", "Sauve");
		echo HtmlInput::submit("add_action_here", _("Ajoute une action à celle-ci"));
		echo HtmlInput::submit("delete", _("Efface cette action"), ' onclick="return confirm(\'' . _("Vous confirmez l\'effacement") . '\')" ');
		echo $retour;
		echo '</form>';
	}
	else if ($g_user->can_read_action($ag_id) == true || $act->ag_dest == -1)
	{
		echo $act->Display('READ', false, $base, $retour);
	}
	else
	{
		echo h2info(_("Ce document n'est pas accessible"));
		exit();
	}


	echo '</div>';
}
//-------------------------------------------------------------------------------
// Delete an action
if ($sub_action == 'delete')
{
	// confirmed
	$cn->start();
	$act = new Follow_Up($cn);
	$act->ag_id = $_REQUEST['ag_id'];
	$act->get();
	if ($g_user->can_write_action($_REQUEST['ag_id'])==true)	$act->remove();
	$sub_action = "list";
	$cn->commit();
	Follow_Up::show_action_list($cn, $base);
	if (isset($act->ag_ref))
		echo hb(_('Action ') . $act->ag_ref . _(' effacée'));
	exit();
}

//--------------------------------------------------------------------------------
// Show a list of the action
if ($sub_action == "list")
{
	// Add a button to export to Csv
	echo '<form method="GET" style="display:inline;" ACTION="export.php">';
	echo HtmlInput::request_to_hidden(array("closed_action","remind_date_end","remind_date","sag_ref", "remind_date","only_internal", "state", "gDossier", "qcode", "start_date", "end_date", "ag_id", "ag_dest_query",
		"tdoc",   "query","date_start","date_end","hsstate","searchtag"));
	echo HtmlInput::hidden("act", "CSV:ActionGestion");
	echo HtmlInput::submit("follow_up_csv", "Export CSV",'','smallbutton');
	echo "</form>";
	Follow_Up::show_action_list($cn, $base);
}
//--------------------------------------------------------------------------------
// Add an action
if ($sub_action == "add_action")
{
	$act = new Follow_Up($cn);
	$act->fromArray($_POST);
	$act->ag_id = 0;
	$act->d_id = 0;
	echo '<div class="content">';
	// Add hidden tag
	echo '<form method="post" action="do.php" name="form_add" id="form_add" enctype="multipart/form-data" >';
	echo $supl_hidden;
	echo dossier::hidden();


	$act->ag_comment = (isset($_POST['ag_comment'])) ? Decode($_POST['ag_comment']) : "";
	if (isset($_REQUEST['qcode']))
		$act->qcode_dest = $_REQUEST['qcode'];
	echo $act->Display('NEW', false, $base, $retour);

	echo '<input type="hidden" name="ac" value="' . $_REQUEST["ac"] . '">';
	echo '<input type="hidden" name="sa" value="save_action_st2">';
	echo '<input type="hidden" name="save_action_st2" value="save_action_st2">';
	echo '<input type="submit" class="button" name="save_action_st2" value="' . _('Enregistrer') . '">';
	echo '</form>';

	echo '</div>';
}
//--------------------------------------------------------------------------------
// Save Follow_Up
// Stage 2 : Save the action + Files and generate eventually a document
//--------------------------------------------------------------------------------
if ($sub_action == "save_action_st2")
{
	$act = new Follow_Up($cn);
	$act->fromArray($_POST);
	$act->d_id = 0;
	$act->md_id = (isset($_POST['gen_doc'])) ? $_POST['gen_doc'] : 0;

	// insert into action_gestion
	echo $act->save();
	$url = "?$base&sa=detail&ag_id=" . $act->ag_id . '&' . dossier::get();
	echo '<p><a class="mtitle" href="' . $url . '">' . hb('Action Sauvée  : ' . $act->ag_ref) . '</a></p>';

	Follow_Up::show_action_list($cn,$base);
	$url = "?$base&sa=detail&ag_id=" . $act->ag_id . '&' . dossier::get();
	echo '<p><a class="mtitle" href="' . $url . '">' . hb('Action Sauvée  : ' . $act->ag_ref) . '</a></p>';
}
?>

