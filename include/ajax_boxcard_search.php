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
	select distinct vw.f_id,vw_name,vw_first_name,vw_description,fd_label,quick_code,tva_num,(select ad_value from fiche_Detail where f_id=pc.f_id and ad_id=5) as poste
	from vw_fiche_attr as vw
	join fiche_def as fd on (vw.fd_id=fd.fd_id)
	left join fiche_detail as pc on (pc.f_id=vw.f_id)
	where
	ad_value ~* $1
	order by 2
";
$array=$cn->get_array($sql,array($_GET['card']));
echo HtmlInput::title_box("Résultat recherche", "boxsearch_card_div");
$max=(count($array)>MAX_CARD_SEARCH)?MAX_CARD_SEARCH:count($array);
?>
<?php if (count($array)>MAX_CARD_SEARCH ): ?>
<h2 class="notice">Résultat limité à <?php echo MAX_CARD_SEARCH?>, nombre de fiches trouvées : <?php echo count($array)?> </h2>

<?php endif?>
Filtre  <?php echo HtmlInput::infobulle(26);echo HtmlInput::filter_table("tb_fiche", "0,1,2,3,4,5", 1); ?>
<table id="tb_fiche" class="sorttable" style="width:100%">
	<tr>
		<th>
			Quick Code <?php echo HtmlInput::infobulle(17); ?>
		</th>
		<th class=" sorttable_sorted">
			Nom
		<span id="sorttable_sortfwdind">&nbsp;&#x25BE;</span>
		</th>
		<th>
			Categorie
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
<?php if (count($array)==0) : ?>
	<h2 class="notice"> Aucun résultat</h2>
<?php endif?>
<?php for ($i=0;$i<$max;$i++):?>
	<tr class="<?php echo ($i%2 == 0)?'even':'odd';?>">

		<td>
			<?php echo HtmlInput::card_detail($array[$i]['quick_code'])?>
		</td>
		<td>
			<?php echo h($array[$i]['vw_name'])?>&nbsp;
			<?php echo h($array[$i]['vw_first_name'])?>
		</td>
		<td>
			<?php echo h($array[$i]['fd_label'])?>
		</td>
		<td>
			<?php echo h($array[$i]['vw_description'])?>

		</td>
		<td>
			<?php echo h($array[$i]['tva_num'])?>

		</td>
		<td style="text-align:right">
			<?php echo HtmlInput::history_account($array[$i]['poste'],$array[$i]['poste'])?>

		</td>
	</tr>


<?php endfor; ?>
</table>
<?php echo HtmlInput::button_close("boxsearch_card_div")?>
