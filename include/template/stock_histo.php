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
 * @brief show the result of stock history
 *
 */

?>
<div class="content">
	<?=$nav_bar?>
<table class="result">
	<tr>
		<th><?=$tb->get_header(0);?></th>
		<th><?=$tb->get_header(1);?></th>
		<th><?=$tb->get_header(2);?></th>
		<th><?=$tb->get_header(3);?></th>
		<th>Opération</th>
		<th><?=$tb->get_header(4);?></th>
		<th><?=$tb->get_header(5);?></th>
		<th><?=$tb->get_header(6);?></th>
		<th><?=$tb->get_header(7);?></th>
	</tr>
	<?

	for ($i=0;$i<$max_row;$i++):
		$row=Database::fetch_array($res, $i);
		$class=($i%2==0)?' class="even" ':' class="odd" ';
	?>
	<tr <?=$class?>>
		<td>
			<?=$row['cdate']?>
		</td>
		<td>
			<?=HtmlInput::card_detail($row['sg_code'],"",' class="line" ')?>
		</td>
		<td>
			<?=$row['r_name']?>
		</td>
		<td>
			<? if (trim($row['qcode'])!='') : ?>
			<?=HtmlInput::card_detail($row['qcode'],$row['fname'],' class="line" ')?>
			<? endif; ?>
		</td>
		<td>
			<? if (trim($row['jr_internal'])!='') : ?>
			<?=HtmlInput::detail_op($row['jr_id'],$row['jr_internal'])?>
			<? endif; ?>
		</td>
		<td>
			<?=$row['ccomment']?>
		</td>
		<td class="num">
			<?=nbm($row['j_montant'])?>
		</td>
		<td class="num">
			<?=nbm($row['sg_quantity'])?>
		</td>
		<td>
			<?=$row['direction']?>
		</td>
	</tr>
	<? endfor;?>
</table>
	<?=$nav_bar?>
</div>