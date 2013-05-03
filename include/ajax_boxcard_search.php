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
 * @brief show result card search
 *
 */
$sql="
	select vw.f_id,vw_name,vw_first_name,vw_description,fd_label,quick_code,pc.ad_value as poste,tva_num
	from vw_fiche_attr as vw
	join fiche_def as fd on (vw.fd_id=fd.fd_id)
	left join (select f_id,ad_value from fiche_detail where ad_id=5) as pc on (pc.f_id=vw.f_id)
	where
	vw_name ~* $1
	or vw_first_name ~* $1
	or vw_description ~* $1
	or tva_num ~* $1
	or pc.ad_value like $1||'%'
	or quick_code like upper($1||'%')
	order by 2
";
$array=$cn->get_array($sql,array($_GET['card']));
echo HtmlInput::title_box("Résultat recherche", "boxsearch_card_div");
$max=(count($array)>MAX_CARD_SEARCH)?MAX_CARD_SEARCH:count($array);
?>
<? if (count($array)>MAX_CARD_SEARCH ): ?>
<h2 class="notice">Résultat limité à <?=MAX_CARD_SEARCH?>, nombre de fiches trouvées : <?=count($array)?> </h2>

<? endif?>
<table class="sortable">
	<tr>
		<th>
			Categorie
		</th>
		<th>
			Quick Code
		</th>
		<th>
			Nom
		</th>
		<th>
			Description
		</th>
		<th>
			Numéro TVA
		</th>
		<th>
			Poste comptable
		</th>

	</tr>
<? if (count($array)==0) : ?>
	<h2 class="notice"> Aucun résultat</h2>
<?endif?>
<? for ($i=0;$i<$max;$i++):?>
	<tr>
		<td>
			<?=h($array[$i]['fd_label'])?>
		</td>
		<td>
			<?=HtmlInput::card_detail($array[$i]['quick_code'])?>
		</td>
		<td>
			<?=h($array[$i]['vw_name'])?>&nbsp;
			<?=h($array[$i]['vw_first_name'])?>
		</td>
		<td>
			<?=h($array[$i]['vw_description'])?>

		</td>
		<td>
			<?=h($array[$i]['tva_num'])?>

		</td>
		<td>
			<?=h($array[$i]['poste'])?>

		</td>
	</tr>


<? endfor; ?>
</table>
<?=HtmlInput::button_close("boxsearch_card_div")?>