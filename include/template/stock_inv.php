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
/* $Revision$ */

// Copyright Author Dany De Bontridder danydb@aevalys.eu

/**
 * @file
 * @brief show the input
 *
 */
?>
<div class="content">
	<form method="POST" class="print" onsubmit="return confirm('Vous confirmez ?')">
<table>
	<tr><td>
			Date
		</td>
		<td>
			<?php echo $date->input()?>
		</td>
	</tr>
	<tr>
		<td>
			Dépot
		</td>
		<td>
			<?php echo $idepo->input()?>
		</td>
	</tr>
	<tr>
		<td>
			Motif de changement
		</td>
		<td>
			<?php echo $motif->input()?>
		</td>
	</tr>
</table>
<table id="stock_tb" style="width: 80%">
	<tr>
		<th style="text-align: left">
			Code Stock
		</th>
<?php if ( $p_readonly == true ) :?>
		<th style="text-align: left">
			Fiche
		</th>
<?php endif;?>
		<th style="text-align:right">
			Quantité
		</th>
	</tr>
<?php for($i=0;$i<$nb;$i++): ?>
	<tr>
		<td>
<?php if ( $p_readonly == false) : ?>
			<?php echo $sg_code[$i]->input()?>
			<?php echo $sg_code[$i]->search()?>
			<?php echo $label[$i]->input()?>
<?php else: ?>
			<?php if ( trim($sg_code[$i]->value) != "")  echo HtmlInput::card_detail($sg_code[$i]->value,h($sg_code[$i]->value),' class="line"',true)?>
<?php endif ?>

		</td>
<?php if ( $p_readonly == true && isset ($fiche[$i])) :?>
		<td>
			<?php echo HtmlInput::card_detail($fiche[$i]->get_quick_code(),h($fiche[$i]->getName()),' class="line"');?>
		</td>
<?php endif;?>
		<TD class="num"">
		<?php if ($sg_quantity[$i]->value==0 && $p_readonly==true):?>

		<?php else : ?>
			<?php echo $sg_quantity[$i]->input()?>
			<?php endif;?>
		</td>
		<TD class="num"">
			<?php if (isset ($sg_type[$i])):?>
			<?php echo $sg_type[$i]?>
			<?php endif;?>
		</td>
	</tr>
<?php endfor; ?>
</table>
<?php if ($p_readonly == false) echo HtmlInput::button_action('Ajouter une ligne','stock_add_row();')?>
<?php if ($p_readonly == false) echo HtmlInput::submit('save','Sauver')?>
<?php if ($p_readonly == false) echo HtmlInput::hidden('row',$nb)?>
	</form>
</div>