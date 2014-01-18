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

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/**
 * @file
 * @brief
 *
 */
// retrieve data
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
$profile=$cn->get_value("select p_id from profile_menu where pm_id=$1",array($pm_id));
$a_value=$cn->make_array("select me_code,me_code||' '||me_menu||' '||coalesce(me_description,'') from menu_ref",0);
$ame_code_dep=$cn->make_array("
	select me_code,me_code||' '||me_menu||' '||coalesce(me_description,'') from
	menu_ref
	where
	me_file is null and me_javascript is null and me_url is null and me_type<>'PR' and me_type <> 'SP'
	and me_code in (select me_code from profile_menu where p_id=".sql_string($profile).")".
	"	UNION ALL
		select me_code,me_code||' '||me_menu||' '||coalesce(me_description,'') from menu_ref
	where
		me_code='EXT'
	order by 1
	",1);
$a_type=$cn->make_array("select pm_type,pm_desc from profile_menu_type",1);

$array=$cn->get_array("select p_id,pm_id,me_code,me_code_dep,p_order,p_type_display,pm_default
	from profile_menu
	where pm_id=$1",array($pm_id));
if ( empty($array)) {
		alert("Code invalide");
		exit();
}


echo HtmlInput::title_box($array[0]['me_code'],'divdm'.$pm_id);

$me_code=new ISelect('me_code');
$me_code->value=$a_value;
$me_code->selected=$array[0]['me_code'];

$me_code_dep=new ISelect('me_code_dep');
$me_code_dep->value=$ame_code_dep;
$me_code_dep->selected=$array[0]['me_code_dep'];

$p_order=new Inum('p_order',$array[0]['p_order']);
$pm_default=new ICheckBox('pm_default','1');
$pm_default->set_check($array[0]['pm_default']);

?>
<form method="POST" onsubmit="return confirm('Vous confirmez ?')">
	<?php echo HtmlInput::hidden('pm_id',$array[0]['pm_id'])?>
	<?php echo HtmlInput::hidden('p_id',$array[0]['p_id'])?>
<table>
<tr>
	<td>Code</td>
	<td><?php echo $me_code->input()?></td>
</tr>
<?php 
if ($array[0]['p_type_display']!='P'):
?>
<tr>
	<td>Dépendant de </td>
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
<?php endif;?>
</table>
	<p>
Cochez cette case si vous souhaitez effacer ce menu
<?php 
$delete=new ICheckBox('delete',"1");
echo $delete->input();
?>
</p>
<?php 
if ($array[0]['p_type_display']!='P'):
?>
	<p>
Cochez cette case si vous souhaitez effacer ce menu ainsi que ceux qui en dépendent
<?php 
$delete=new ICheckBox('del_dep',"1");
echo $delete->input();
?>
</p>
<?php endif;?>
<?php 
echo HtmlInput::submit('mod',"Valider");
echo '</form>';


?>
