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
 * include from adm.inc.php and concerned only the customer card and
 * the customer category
 * parameter
 *  - p_action = adm
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

    $adm=new Fiche($cn,$f_id);
    $adm->Save();

}

echo '<div class="u_content" style="width:50%">';
$f_id=$_REQUEST['f_id'];
echo '<div class="content">';
if ( isset($_POST['mod'])) echo hb('Information sauvée');

$adm=new Fiche($cn,$f_id);
$p_readonly=($g_user->check_action(FICADD)==0)?true:false;
if ( ! $p_readonly) echo '<form method="post">';
echo dossier::hidden();
echo HtmlInput::hidden('sb','detail');
echo HtmlInput::hidden('dc','cc');
echo $adm->Display($p_readonly);
$w=new IHidden();
$w->name="p_action";
$w->value="adm";
echo $w->input();
$w->name="f_id";
$w->value=$f_id;
echo $w->input();
if ( ! $p_readonly)
{
	echo HtmlInput::submit('mod',_('Sauver les modifications'));
	echo HtmlInput::reset(_("Annuler"));
	echo HtmlInput::submit('delete_card','Effacer cette fiche','onclick="return confirm(\'Confirmer effacement ?\');"');
	echo '</form>';
}
echo $return->input();
echo '</div>';


