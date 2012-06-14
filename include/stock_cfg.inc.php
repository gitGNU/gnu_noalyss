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
 * @brief Manage the repository
 *
 */
require_once 'class_stock_sql.php';
require_once 'class_sort_table.php';

global $g_user, $cn,$g_parameter;

if ($g_parameter->MY_STOCK == 'N')
{
	echo '<h2 class="notice">';
	echo _("Vous n'utilisez pas de gestion de stock");
	echo '</h2>';
	exit();
}
if ( isset ($_POST['add_stock']))
{
	$st=new Stock_Sql();
	$st->from_array($_POST);
	$st->insert();

}
if ( isset ($_POST['mod_stock']))
{
	$st=new Stock_Sql($_POST['r_id']);
	$st->from_array($_POST);
	$st->update();

}
$tb=new Sort_Table();
$p_url=HtmlInput::get_to_string(array("ac","gDossier"));

$tb->add(_("Nom"), $p_url, " order by r_name asc", "order by r_name desc", "ona", "ond");
$tb->add(_("Adresse"), $p_url, " order by r_adress asc", "order by r_adress desc", "oaa", "oad");
$tb->add(_("Ville"), $p_url, " order by r_city asc", "order by r_city desc", "ova", "ovd");
$tb->add(_("Pays"), $p_url, " order by r_country asc", "order by r_country desc", "opa", "opd");
$tb->add(_("Téléphone"), $p_url, " order by r_phone asc", "order by r_phone desc", "opa", "opd");

$sql="select * from stock_repository ";

$ord=(isset($_GET['ord']))?$_GET['ord']:"ona";

$order=$tb->get_sql_order($ord);

$array=$cn->get_array($sql." ".$order);

?>
<div class="content">

<table class="result">
	<tr>
		<th><?=$tb->get_header(0)?></th>
		<th><?=$tb->get_header(1)?></th>
		<th><?=$tb->get_header(2)?></th>
		<th><?=$tb->get_header(3)?></th>
		<th><?=$tb->get_header(4)?></th>
	</tr>
<? for ($i=0;$i<count($array);$i++): ?>
	<tr>
		<td>
			<?=h($array[$i]['r_name'])?>
		</td>
		<td>
			<?=h($array[$i]['r_adress'])?>
		</td>
		<td>
			<?=h($array[$i]['r_city'])?>
		</td>
		<td>
			<?=h($array[$i]['r_country'])?>
		</td>
		<td>
			<?=h($array[$i]['r_phone'])?>
		</td>
		<td>
			<?
				$js=' onclick="stock_repo_change(\''.dossier::id().'\',\''.$array[$i]['r_id'].'\')"';
				echo HtmlInput::button("mod", _("Modifier"), $js);
			?>
		</td>
	</tr>

<?endfor;?>
</table>
	<?=HtmlInput::button("show_add_depot_d", "Ajout d'un dépot", "onclick=\"$('add_depot_d').show();\"");?>
	<div id="add_depot_d" class="inner_box" style="display:none">
	<?=HtmlInput::title_box("Ajouter un dépôt","add_depot_d","hide")?>
	<form method="post">
		<table>
			<tr>
				<td>
					Nom
				</td>
				<td>
					<? $name=new IText("r_name",""); echo $name->input();?>
				</td>
			</tr>
			<tr>
				<td>
					Adresse
				</td>
				<td>
					<? $name=new IText("r_adress",""); echo $name->input();?>
				</td>
			</tr>
			<tr>
				<td>
					Ville
				</td>
				<td>
					<? $name=new IText("r_city",""); echo $name->input();?>
				</td>
			</tr>
			<tr>
				<td>
					Pays
				</td>
				<td>
					<? $name=new IText("r_country",""); echo $name->input();?>
				</td>
			</tr>
			<tr>
				<td>
					Téléphone
				</td>
				<td>
					<? $name=new IText("r_phone",""); echo $name->input();?>
				</td>
			</tr>

		</table>
		<?=HtmlInput::submit("add_stock","Sauver")?>
	</form>
	</div>
</div>