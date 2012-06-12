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
$add_one=HtmlInput::button("add", "Ajout Menu","onclick=\"add_menu({dossier:$gDossier,p_id:$p_id})\"")
?>
<hr>
<h1>Profil <?=$profile->p_name?></h1>

<?
$id=HtmlInput::hidden('p_id',$profile->p_id);
$name=new IText("p_name",$profile->p_name);
$desc=new IText("p_desc",$profile->p_desc);
$with_calc=new ICheckBox("with_calc","t");
$with_calc->set_check($profile->with_calc);

$with_direct_form=new ICheckBox("with_direct_form","t");
$with_direct_form->set_check($profile->with_direct_form);

echo '<form method="POST" onsubmit="return confirm (\'vous confirmez\')">';
echo HtmlInput::hidden('p_id',$profile->p_id);
require_once("template/profile.php");
echo HtmlInput::submit("save_name","Modifier");
echo '</form>';
if ($profile->p_id > 0)
{
	echo '<form method="POST" onsubmit="return confirm (\'vous confirmez\')">';

	echo '
Vous pouvez aussi copier ce profil et puis le corriger';

	echo HtmlInput::hidden('p_id', $profile->p_id);
	echo HtmlInput::submit("clone", "Copier");
	echo '</form>';

	echo '<form method="POST" onsubmit="return confirm (\'vous confirmez\')">';

	echo '
Effacer ce profil';

	echo HtmlInput::hidden('p_id', $profile->p_id);
	echo HtmlInput::submit("delete_profil", "Effacer ce profil");
	echo '</form>';

	//Menu / Module /plugin in this profile
	echo "<h1> Détail du profile</h1>";
	echo "<h2>Menu</h2>";
	echo $add_one;
	$profile_menu = new Profile_Menu($cn);
	$profile_menu->listing_profile($p_id);
	echo "<h2>Impression</h2>";
	$profile_menu->printing($p_id);
	echo $add_one;
	echo "<h2>Action gestion accessible</h2>";
	$profile_menu->available_profile($p_id);
}
?>


