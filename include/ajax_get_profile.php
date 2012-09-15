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

/**
 * @file
 * @brief show the profile detail, included from ajax_misc.php
 * @see ajax_misc.php scripts.js profile.inc.php
 *
 */
require_once 'class_profile_sql.php';
require_once 'class_profile_menu.php';
$profile=new Profile_sql($cn,$p_id);
$gDossier=Dossier::id();
$add_menu=HtmlInput::button("add", "Ajout Menu","onclick=\"add_menu({dossier:$gDossier,p_id:$p_id,type:'me'})\"");
$add_impression=HtmlInput::button("add", "Ajout Menu","onclick=\"add_menu({dossier:$gDossier,p_id:$p_id,type:'pr'})\"");
?>
<hr>
<h1>Profil <?=$profile->p_name?></h1>
<? if ($p_id > 0 ) : ?>
<a href="javascript:void(0)" class="line" onclick="profile_show('profile_gen_div')"><?=_('Nom')?></a>&nbsp;
<a href="javascript:void(0)" class="line" onclick="profile_show('profile_menu_div')"><?=_('Détail Menus')?></a>&nbsp;
<a href="javascript:void(0)" class="line" onclick="profile_show('profile_print_div')"><?=_('Détail Impressions')?></a>&nbsp;
<a href="javascript:void(0)" class="line" style="" onclick="profile_show('profile_gestion_div')"><?=_('Action Gestion')?> </a>&nbsp;
<a href="javascript:void(0)" class="line" onclick="profile_show('profile_repo_div')"><?=_('Dépôts')?></a>&nbsp;
<? endif; ?>

<?
$id=HtmlInput::hidden('p_id',$profile->p_id);
$name=new IText("p_name",$profile->p_name);
$desc=new IText("p_desc",$profile->p_desc);
$with_calc=new ICheckBox("with_calc","t");
$with_calc->set_check($profile->with_calc);

$with_direct_form=new ICheckBox("with_direct_form","t");
$with_direct_form->set_check($profile->with_direct_form);

// If $p_id == -1 it is a new profile
if ( $p_id > 0 )
{
	echo '<div style="display:none" id="profile_gen_div">';
}
else
{
	echo '<div  id="profile_gen_div">';
}
echo '<form method="POST" onsubmit="return confirm (\'vous confirmez\')">';
echo HtmlInput::hidden('tab','profile_gen_div');
echo HtmlInput::hidden('p_id',$profile->p_id);
require_once("template/profile.php");
echo HtmlInput::submit("save_name","Modifier");
echo '</form>';
if ($profile->p_id > 0)
{
	echo '<form method="POST" onsubmit="return confirm (\'vous confirmez\')">';

	echo 'Vous pouvez aussi copier ce profil et puis le corriger';

	echo HtmlInput::hidden('p_id', $profile->p_id);
	echo HtmlInput::submit("clone", "Copier");
	echo '</form>';

	echo '<form method="POST" onsubmit="return confirm (\'vous confirmez\')">';

	echo 'Effacer ce profil';

	echo HtmlInput::hidden('p_id', $profile->p_id);
	echo HtmlInput::submit("delete_profil", "Effacer ce profil");
	echo '</form>';
        echo '</div>';
        echo '<div style="display:none" id="profile_menu_div">';
	//Menu / Module /plugin in this profile
	echo "<h2>Menu</h2>";
	echo $add_menu;
	$profile_menu = new Profile_Menu($cn);
	$profile_menu->listing_profile($p_id);
        echo '</div>';
        echo '<div style="display:none" id="profile_print_div">';
	echo "<h2>Impression</h2>";
	$profile_menu->printing($p_id);
	echo $add_impression;
        echo '</div>';
        echo '<div style="display:none" id="profile_gestion_div">';
	echo "<h2>Action gestion accessible</h2>";
	$profile_menu->available_profile($p_id);
        echo '</div>';
        echo '<div style="display:none" id="profile_repo_div">';
	echo "<h2>Dépôt de stock accessible</h2>";
	$profile_menu->available_repository($p_id);
        echo '</div>';
        if ( isset ($_POST['tab']))
        {
            echo create_script("profile_show('".$_POST['tab']."');");
        }
}
else
{
        echo '</div>';
}
?>


