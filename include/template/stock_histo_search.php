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
 * @brief show box for search
 *
 */
?>
<div id="histo_search_d" class="inner_box" style="width:60%;height:280px;display:none">
	<?= HtmlInput::title_box("Recherche", "histo_search_d", "hide")?>
	<form method="GET">
		<?= HtmlInput::get_to_hidden(array("gDossier", "ac"))?>
		<table>
			<tr>
				<td> Fiche </td>
				<td> <?= $wcard->input()?><?=$wcard->search()?> </td>
			</tr>
			<tr>
				<td> A partir de </td>
				<td> <?= $wdate_start->input()?> </td>
			</tr>
			<tr>
				<td> Jusque </td>
				<td> <?= $wdate_end->input()?> </td>
			</tr>
			<tr>
				<td> Dépôt </td>
				<td> <?= $wrepo->input()?> </td>
			</tr>
			<tr>
				<td> Montant supérieur ou égal à  </td>
				<td> <?= $wamount_start->input()?> </td>
			</tr>
			<tr>
				<td> Montant inférieur ou égal à  </td>
				<td> <?= $wamount_end->input()?> </td>
			</tr>
			<tr>
				<td> Code Stock </td>
				<td> <?= $wcode_stock->input()?> </td>
			</tr>
			<tr>
				<td> Direction </td>
				<td> <?= $wdirection->input()?> </td>
			</tr>
		</table>
		<?=HtmlInput::submit("search_histo_b","Recherche")?>
	</form>
</div>

