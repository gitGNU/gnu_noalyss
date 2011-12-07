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
 * @brief show the form to add a menu
 */
$ame_code_dep=$cn->make_array("
	select me_code,me_code||' '||me_menu||' '||coalesce(me_description,'') from
	menu_ref
	where
	me_file is null and me_javascript is null and me_type<>'PR'
		UNION ALL
		select me_code,me_code||' '||me_menu||' '||coalesce(me_description,'') from menu_ref
	where
		me_code='EXT'
	order by 1
	",1);
$ame_code=$cn->make_array("
	select me_code,me_code||' '||me_menu||' '||coalesce(me_description,'') from
	menu_ref
	order by 1
	");
$p_order=new INum("p_order","10");
$atype=$cn->make_array("select pm_type,pm_desc from profile_menu_type order by 1");

$me_code=new ISelect('me_code');
$me_code->value=$ame_code;

$me_code_dep=new ISelect('me_code_dep');
$me_code_dep->value=$ame_code_dep;

$p_type=new ISelect('p_type');
$p_type->value=$atype;
$pm_default=new ICheckBox('pm_default');
echo HtmlInput::title_box("Nouveau menu", $ctl);
?>
<form method="POST" onsubmit="return confirm('Vous confirmez ?')">
	<?=HtmlInput::hidden('p_id',$p_id)?>
<table>
<tr>
	<td>Code</td>
	<td><?=$me_code->input()?></td>
</tr>
<tr>
	<td>Dépendant de </td>
	<td><?=$me_code_dep->input()?></td>
</tr>

<tr>
	<td>Ordre d'apparition</td>
	<td><?=$p_order->input()?></td>
</tr>
<tr>
	<td>Menu par défaut</td>
	<td><?=$pm_default->input()?></td>
</tr>
<tr>
	<td>Type de menu</td>
	<td><?=$p_type->input()?></td>
</tr>
</table>
<?
echo HtmlInput::submit('add_menu',"Valider");
echo '</form>';
?>
