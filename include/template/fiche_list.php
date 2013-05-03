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
 * @brief Show a list of card
 *
 */
?>
<?= $bar?>
<form method="POST" class="print" style="display:inline" onsubmit="return confirm('Vous confirmez ?')">
	<table class="result">
		<tr>
			<th>
				Quick Code
			</th>
			<th>
				Nom

			</th>
			<? if ($allcard == 1 ) : ?>
			<th>
				Catégorie
			</th>
			<? endif; ?>
			<th>
				Selection
			</th>
		</tr>
		<? for ($i = 0; $i < $nb_line; $i++) :?>
			<? $row = Database::fetch_array($res, $i);?>
			<? $class=($i%2 == 0)?' class="even" ':' class="odd" ';?>
			<tr <?=$class?> >
				<td>
					<?= HtmlInput::card_detail($row['qcode'], "", ' class="line" ')?>

				</td>
				<td>
					<?= h($row['name'])?>
				</td>
				<? if ($allcard == 1 ) : ?>
				<td>
					<?= h($row['fd_label'])?>
				</td>
				<? endif; ?>
				<td>
					<?
					if ($write == 1)
					{
						$ck = new ICheckBox('f_id[]', $row['f_id']);
						echo $ck->input();
					}
					?>
				</td>
			</tr>
		<? endfor;?>


	</table>
	<?=$str_add_card?>
<?=HtmlInput::hidden('action',"1");?>
<?=HtmlInput::submit('delete','Effacer la sélection ')?>
<? if ( $allcard ==  0  ): ?>
<?=HtmlInput::submit('move','Déplacer la sélection  vers')?>
<?
$iselect=new ISelect('move_to');
$iselect->value=$cn->make_array("select fd_id,fd_label from fiche_def order by 2");
echo $iselect->input();
?>

<? endif ; ?>
</form>