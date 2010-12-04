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
 * \brief this file will handle all the actions for a specific customer (
 * contact,operation,invoice and financial)
 * include from client.inc.php and concerned only the customer card and
 * the customer category
 * parameter 
 *  - p_action = client
 *  - sb = detail 
 *  - sc = dc
 */
//----------------------------------------------------------------------------
// Save modification
//---------------------------------------------------------------------------
if ( isset ($_POST['mod']))
{

    // modification is asked
    $f_id=$_REQUEST['f_id'];

    $client=new Customer($cn,$f_id);
    $client->Save();

}

echo '<div class="u_content">';
$f_id=$_REQUEST['f_id'];
echo '<div class="content">';
if ( isset($_POST['mod'])) echo hb(_('Information sauvée'));

$client=new Customer($cn,$f_id);
echo JS_INFOBULLE;
echo '<form method="post">';
echo dossier::hidden();
echo HtmlInput::hidden('sb','detail');
echo HtmlInput::hidden('dc','cc');
echo $client->Display(false);
$w=new IHidden();
$w->name="p_action";
$w->value="client";
echo $w->input();
$w->name="f_id";
$w->value=$f_id;
echo $w->input();

echo HtmlInput::submit('mod',_('Sauver les modifications'));
echo HtmlInput::reset(_("Annuler"));
echo HtmlInput::submit('delete_card',_('Effacer cette fiche'),'onclick="return confirm(\'Confirmer effacement ?\');"');
echo '</form>';
echo $return;
echo '</div>';


