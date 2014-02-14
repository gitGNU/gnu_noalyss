<?php

/*
 *   This file is part of NOALYSS.
 *
 *   NOALYSS is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   NOALYSS is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with NOALYSS; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

// Copyright Author Dany De Bontridder danydb@aevalys.eu

/**
 * @file
 * @brief show the form to add a menu
 */
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
$type=$_GET['type'];
if ( $type=='me')
{
$ame_code_dep=$cn->make_array("
	select me_code,me_code||' '||me_menu||' '||coalesce(me_description,'') from
	menu_ref
	where
	me_file is null and me_javascript is null and me_url is null and me_type<>'PR' and me_type <> 'SP'
	and me_code in (select me_code from profile_menu where p_id=".sql_string($p_id).")".
	"	UNION ALL
		select me_code,me_code||' '||me_menu||' '||coalesce(me_description,'') from menu_ref
	where
		me_code='EXT'
	order by 1
	",1);
$ame_code=$cn->make_array("
select me_code,me_code||' '||coalesce(me_menu,'')||' '||coalesce(me_description,'')
	||'('|| case when me_type='SP' then 'Special'
		when me_type='PR' then 'Impression'
		when me_type='PL' then 'Plugin'
		when me_type='ME' and me_file is null and me_javascript is null and me_url is null then 'Module - Menu principal'
		when me_type='ME' then 'Menu'
		else
		me_type
		end||')'
	from
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
echo HtmlInput::title_box(_("Nouveau menu"), $ctl);
?>
<form method="POST" onsubmit="return confirm('<?php echo _('Vous confirmez');?> ?')">
	<?php 
	echo HtmlInput::hidden('tab','profile_menu_div');
	?>
	<?php echo HtmlInput::hidden('p_id',$p_id)?>
<table>
<tr>
	<td>Code</td>
	<td><?php echo $me_code->input()?></td>
</tr>
<tr>
	<td>Dépendant de <?php echo HtmlInput::infobulle(20)?></td>
	<td><?php echo $me_code_dep->input()?></td>
</tr>

<tr>
	<td>Ordre d'apparition</td>
	<td><?php echo $p_order->input()?></td>
</tr>
<tr>
	<td>Menu par défaut</td>
	<td><?php echo $pm_default->input()?></td>
</tr>
<tr>
	<td>Type de menu</td>
	<td><?php echo $p_type->input()?></td>
</tr>
</table>
<?php 
echo HtmlInput::submit('add_menu',"Valider");
echo '</form>';
}
if ($type=='pr')
{

$ame_code=$cn->make_array("
select me_code,me_code||' '||coalesce(me_menu,'')||' '||coalesce(me_description,'')
	from
	menu_ref
	where me_type='PR'
	and me_code not in (select me_code from profile_menu where p_id=".sql_string($p_id).")
	order by 1
	");

$me_code=new ISelect('me_code');
$me_code->value=$ame_code;

	echo HtmlInput::title_box(_("Nouveau menu"), $ctl);
	if (count($ame_code)==0)
	{
		echo h2(_("Aucune impression disponible à ajouter"),'class="notice"');
		return;
	}
?>
<form method="POST" onsubmit="return confirm('<?php echo _('Vous confirmez ?')?>">
	<?php 
	echo HtmlInput::hidden('tab','profile_print_div');
	?>
	<?php echo HtmlInput::hidden('p_id',$p_id)?>
	<?php echo HtmlInput::hidden('p_order',10)?>
	<?php echo HtmlInput::hidden('me_code_dep','')?>
	<?php echo HtmlInput::hidden('p_type','PR')?>
<table>
<tr>
	<td>Code</td>
	<td><?php echo $me_code->input()?></td>
</tr>

</table>
<?php 
echo HtmlInput::submit('add_impress',_("Valider"));
echo '</form>';
}

?>
