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
 * @brief show a depot
 *
 */
require_once 'class_stock_sql.php';
$st=new Stock_Sql($_GET['r_id']);

?>
<?=HtmlInput::title_box("Ajouter un dépôt","change_stock_repo_div","close")?>
	<form method="post">
		<?=HtmlInput::hidden("r_id",$_GET['r_id']);?>
		<table>
			<tr>
				<td>
					Nom
				</td>
				<td>
					<? $name=new IText("r_name",$st->r_name); echo $name->input();?>
				</td>
			</tr>
			<tr>
				<td>
					Adresse
				</td>
				<td>
					<? $name=new IText("r_adress",$st->r_adress); echo $name->input();?>
				</td>
			</tr>
			<tr>
				<td>
					Ville
				</td>
				<td>
					<? $name=new IText("r_city",$st->r_city); echo $name->input();?>
				</td>
			</tr>
			<tr>
				<td>
					Pays
				</td>
				<td>
					<? $name=new IText("r_country",$st->r_country); echo $name->input();?>
				</td>
			</tr>
			<tr>
				<td>
					Téléphone
				</td>
				<td>
					<? $name=new IText("r_phone",$st->r_phone); echo $name->input();?>
				</td>
			</tr>

		</table>
		<?=HtmlInput::submit("mod_stock","Sauver")?>
	</form>