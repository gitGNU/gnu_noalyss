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
 * \brief display, add, delete and modify forecast
 */

require_once 'class_anticipation.php';
echo '<div class="content">';

$sa = (isset($_REQUEST['sa'])) ? $_REQUEST['sa'] : '';
/* * ********************************************************************
 * Remove a anticipation
 *
 *
 * ******************************************************************** */
if (isset($_GET['del']))
{
    $forecast = new Forecast($cn, $_GET['f_id']);
    $forecast->delete();
}
/*
 * Cloning
 */
if (isset($_REQUEST ['clone']))
{
    echo "<h2> cloning</h2>";
    /*
     * We need to clone the forecast
     */
    $anti = new Forecast($cn, $_REQUEST ['f_id']);
    $anti->object_clone();
}
/* * ********************************************************************
 * Save the modification mod_cat_save
 *
 *
 * ******************************************************************** */
if (isset($_POST['mod_cat_save']))
{
    /*
     * We save the forecast
     */
    $anti = new Forecast($cn, $_POST['f_id']);
    try
    {
	$cn->start();
	/* Save forecast */
	$anti->set_parameter('name', $_POST['an_name']);
	$anti->set_parameter('start_date', $_POST['start_date']);
	$anti->set_parameter('end_date', $_POST['end_date']);

	$anti->save();

	/* add new category */
	for ($i = 0; $i < MAX_CAT; $i++)
	{
	    if (isset($_POST['fr_cat_new' . $i]))
	    {
		if (strlen(trim($_POST['fr_cat_new' . $i])) != 0)
		{
		    $c = new Forecast_Cat($cn);
		    $c->set_parameter('order', $_POST['fc_order_new' . $i]);
		    $c->set_parameter('desc', $_POST['fr_cat_new' . $i]);
		    $c->set_parameter('forecast', $_POST['f_id']);
		    $c->save();
		}
	    }
	}

	/* update existing cat */
	foreach ($_POST as $key => $value)
	{
	    $var = sscanf($key, 'fr_cat%d');
	    $idx = sprintf("fr_cat%d", $var[0]);
	    if (isset($_POST[$idx]))
	    {
		$fc = new Forecast_Cat($cn, $var[0]);
		if (strlen(trim($_POST[$idx])) == 0)
		{
		    $fc->delete();
		}
		else
		{
		    $fc->set_parameter('order', $_POST['fc_order' . $var[0]]);
		    $fc->set_parameter('desc', $_POST['fr_cat' . $var[0]]);
		    $fc->set_parameter('forecast', $_POST['f_id']);
		    $fc->save();
		}
	    }
	}

	$cn->commit();
    }
    catch (Exception $e)
    {
	alert($e->getMessage());
	$cn->rollback();
    }
    $sa = 'vw';
}
/* * ********************************************************************
 * Save first the data for new
 *
 *
 * ******************************************************************** */
if ($sa == 'new' || isset($_POST['step3']))
{
    $correct = 0;
    if (isset($_POST['step3']))
    {
	/* save all the items */
	try
	{
	    $cn->start();
	    for ($i = 0; $i < $_POST['nbrow']; $i++)
	    {

		// Delete if needed
		if (isset($_POST['fi_id' . $i]))
		{
		    if (strlen(trim($_POST['an_cat_acc' . $i])) == 0 && strlen(trim($_POST['an_qc' . $i])) == 0)
		    {
			$e = new Forecast_item($cn);
			$e->set_parameter("id", $_POST['fi_id' . $i]);
			$e->delete();
		    }
		}

		if (strlen(trim($_POST['an_cat_acc' . $i])) != 0 || strlen(trim($_POST['an_qc' . $i])) != 0)
		{
		    /* we save only if there is something */
		    $e = new Forecast_item($cn);
		    if (isset($_POST['fi_id' . $i]))
		    {
			$e->set_parameter("id", $_POST['fi_id' . $i]);
		    }
		    $e->set_parameter('text', $_POST['an_label' . $i]);
		    $e->set_parameter('amount', $_POST['an_cat_amount' . $i]);
		    $e->set_parameter('debit', $_POST['an_deb' . $i]);
		    $e->set_parameter('cat_id', $_POST['an_cat' . $i]);
		    $e->set_parameter('account', $_POST['an_cat_acc' . $i]);
		    $e->set_parameter('periode', $_POST['month' . $i]);
		    $f = new Fiche($cn);
		    if ($f->get_by_qcode($_POST['an_qc' . $i], false) == 0)
			$e->set_parameter('card', $f->id);
		    else
			$e->set_parameter('card', null);
		    $e->set_parameter('order', $i);
		    $e->save();
		}
	    }
	    $cn->commit();
	    $sa = 'vw'; // to avoid to restart the add of new anticipation
	}
	catch (Exception $e)
	{
	    $cn->rollback();
	    alert($e->getMessage());
	    $correct = 1;
	}
    }
    /* Second step : we save the name and category
     * and propose the items we add the item */
    if ($correct == 2 || isset($_POST['step2']))
    {
	try
	{
	    $cn->start();
	    /* Save forecast */
	    $a = new Forecast($cn);
	    $a->set_parameter('name', $_POST['an_name']);
	    $a->set_parameter('start_date', $_POST['start_date']);
	    $a->set_parameter('end_date', $_POST['end_date']);


	    $a->save();
	    $id = $a->get_parameter("id");
	    /* save cat */
	    for ($i = 0; $i < MAX_CAT; $i++)
	    {
		if (strlen(trim($_POST['fr_cat' . $i])) != 0)
		{
		    $c = new Forecast_Cat($cn);
		    $c->set_parameter('order', $_POST['fr_order' . $i]);
		    $c->set_parameter('desc', $_POST['fr_cat' . $i]);
		    $c->set_parameter('forecast', $id);
		    $c->save();
		}
	    }
	    $cn->commit();
	}
	catch (Exception $e)
	{
	    alert($e->getMessage());
	    $correct = 1;
	    unset($_POST['step2']);
	    $cn->rollback();
	}
    }
}
/* * ********************************************************************
 * Display menu
 *
 *
 * ******************************************************************** */
// display button add and list of forecast to display
$aForecast = Forecast::load_all($cn);
$menu = array();
$get_dossier = dossier::get();


for ($i = 0; $i < count($aForecast); $i++)
{
    $href = "?ac=" . $_REQUEST['ac'] . "&sa=vw&" . $get_dossier . '&f_id=' . $aForecast[$i]['f_id'];
    $name = h($aForecast[$i]['f_name']);
    $menu[] = array($href, $name, $name, $aForecast[$i]['f_id']);
}

$href = "?ac=" . $_REQUEST['ac'] . "&sa=new&" . $get_dossier;
$menu[] = array($href, _("Ajout prévision"), _("Ajout d'une prévision"), 0);
$def = (isset($_REQUEST['f_id'])) ? $_REQUEST['f_id'] : -1;
echo '<div class="topmenu">';
echo ShowItem($menu, 'H', 'mtitle', 'mtitle', $def);
echo '</div>';
/* * ********************************************************************
 * Ask for a new anticipation (forecast)
 *
 *
 * ******************************************************************** */
if ($sa == 'new')
{
    /* Second step : we save the name and category
     * and propose the items we add the item */
    if ($correct == 2 || isset($_POST['step2']))
    {
	/* Propose a form for the items
	 */
	$anticip = new Anticipation($cn, $a->get_parameter("id"));
	echo '<div class="content">';
	echo ICard::ipopup('ipopcard');
	echo IPoste::ipopup('ipop_account');
	$search_card = new IPopup('ipop_card');
	$search_card->title = _('Recherche de fiche');
	$search_card->value = '';
	echo $search_card->input();

	echo '<form method="post" action="?">';
	echo dossier::hidden();
	echo HtmlInput::hidden('sa', 'new');
	echo HtmlInput::hidden('ac', $_REQUEST['ac']);
	echo HtmlInput::hidden('f_id', $id);
	echo $anticip->form_item();
	echo HtmlInput::submit('step3', _('Sauver'));
	echo '</form>';
	echo '</div>';
    }
    /* First step, the name and the category */
    if (!isset($_POST['step2']) || $correct == 1)
    {
	$anc = new Anticipation($cn);
	echo '<div class="content">';
	/* display a blank form for name and category */
	echo '<form method="post" action="?">';
	echo dossier::hidden();
	echo HtmlInput::hidden('sa', 'new');
	echo HtmlInput::hidden('ac', $_REQUEST['ac']);
	echo $anc->form_cat();
	echo HtmlInput::submit('step2', _('Sauver'));
	echo '</form>';
	echo '</div>';
    }
}
/* * ********************************************************************
 * If we request to modify the category or the name
 *
 *
 * ******************************************************************** */
if (isset($_GET['mod_cat']))
{
    $anc = new Anticipation($cn, $_GET['f_id']);
    echo '<div class="content">';
    /* display a blank form for name and category */
    echo '<form method="post" action="?">';
    echo dossier::hidden();
    echo HtmlInput::hidden('sa', 'mod');
    echo HtmlInput::hidden('ac', $_REQUEST['ac']);
    echo $anc->form_cat();
    echo HtmlInput::submit('mod_cat_save', _('Sauver'));

    echo '</form>';
    echo '</div>';
}
/* * ********************************************************************
 * If we request to modify the items
 *
 *
 * ******************************************************************** */
if (isset($_GET['mod_item']))
{

    /* Propose a form for the items
     */
    $anticip = new Anticipation($cn, $_GET['f_id']);
    echo '<div class="content">';
    echo ICard::ipopup('ipopcard');
    echo IPoste::ipopup('ipop_account');
    $search_card = new IPopup('ipop_card');
    $search_card->title = _('Recherche de fiche');
    $search_card->value = '';
    echo $search_card->input();

    echo '<form method="post" action="?">';
    echo dossier::hidden();
    echo HtmlInput::hidden('sa', 'new');
    echo HtmlInput::hidden('ac', $_REQUEST['ac']);
    echo HtmlInput::hidden('f_id', $_GET['f_id']);
    echo $anticip->form_item();
    echo HtmlInput::submit('step3', _('Sauver'));
    echo '</form>';
    echo '</div>';
}
/* * ********************************************************************
 * if a forecast is asked we display the result
 *
 *
 * ******************************************************************** */
if (isset($_REQUEST['f_id']) && $sa == "vw")
{
    echo '<div class="content">';
    $forecast = new Anticipation($cn);
    $forecast->set_parameter("id", $_REQUEST['f_id']);
    try
    {
	echo $forecast->display();
	echo '<div class="noprint">';
	echo '<form method="get">';
	echo dossier::hidden();
	echo HtmlInput::hidden('f_id', $_REQUEST['f_id']);
	echo HtmlInput::submit('mod_cat', _('Modifier nom ou catégories'));
	echo HtmlInput::submit('mod_item', _('Modifier éléments'));
	//echo HtmlInput::submit('cvs',_('Export CVS'));
	echo HtmlInput::submit('del', _('Effacer'), 'onclick="return confirm(\'' . _('Vous confirmez l\\\' effacement') . '\')"');
	echo HtmlInput::submit('clone', _('Cloner'), 'onclick="return confirm(\'' . _('Vous confirmez le clonage ') . '\')"');
	echo HtmlInput::hidden('ac', $_REQUEST['ac']);
	echo '</form>';
	echo '</div>';
	echo '</div>';
	exit();
    }
    catch (Exception $e)
    {
	echo "<div class=\"error\"><p>" . _("Erreur : ") . $e->getMessage() . '</p><p>' . _('Vous devez corriger') . '</p></div>';
	$anc = new Anticipation($cn, $_GET['f_id']);
	echo '<div class="content">';
	/* display a blank form for name and category */
	echo '<form method="post" action="?">';
	echo dossier::hidden();
	echo HtmlInput::hidden('sa', 'mod');
	echo HtmlInput::hidden('ac', $_REQUEST['ac']);
	echo $anc->form_cat();
	echo HtmlInput::submit('mod_cat_save', _('Sauver'));
	echo '</form>';
	echo '</div>';
    }
}
?>
</div>