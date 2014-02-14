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
 * @brief Show a list of card
 *
 */
?>
<?php echo  $bar?>
<form method="POST" class="print" style="display:inline" onsubmit="return confirm('<?php echo _("Vous confirmez ?")?>')">
	<table class="result">
		<tr>
			<th>
				<?php echo _("Quick Code")?>
			</th>
			<th>
				<?php echo _("Nom")?>

			</th>
			<th>
				<?php echo _("Poste Comptable")?>
			</th>
			<?php if ($allcard == 1 ) : ?>
			<th>
				<?php echo _("Catégorie")?>
			</th>
			<?php endif; ?>
			<th>
				<?php echo _("Selection")?>
			</th>
		</tr>
		<?php for ($i = 0; $i < $nb_line; $i++) :?>
			<?php $row = Database::fetch_array($res, $i);?>
			<?php $class=($i%2 == 0)?' class="even" ':' class="odd" ';?>
			<tr <?php echo $class?> >
				<td>
					<?php echo  HtmlInput::card_detail($row['qcode'], "", ' class="line" ')?>

				</td>
				<td>
					<?php echo  h($row['name'])?>
				</td>
				<td>
					<?php echo HtmlInput::history_account($row['poste'],$row['poste'])?>
				</td>
				<?php if ($allcard == 1 ) : ?>
				<td>
					<?php echo  h($row['fd_label'])?>
				</td>
				<?php endif; ?>
				<td>
					<?php 
					if ($write == 1)
					{
						$ck = new ICheckBox('f_id[]', $row['f_id']);
						echo $ck->input();
					}
					?>
				</td>
			</tr>
		<?php endfor;?>


	</table>
	<?php echo $str_add_card?>
<?php echo HtmlInput::hidden('action',"1");?>
<?php echo HtmlInput::submit('delete',_('Effacer la sélection '))?>
<?php if ( $allcard ==  0  ): ?>
<?php echo HtmlInput::submit('move',_('Déplacer la sélection  vers'))?>
<?php 
$iselect=new ISelect('move_to');
$iselect->value=$cn->make_array("select fd_id,fd_label from fiche_def order by 2");
echo $iselect->input();
?>

<?php endif ; ?>
</form>