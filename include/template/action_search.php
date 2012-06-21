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
 * @brief display the form for searching action
 *
 */
?>
<? if (! $inner ) : ?>
<div id="search_action" class="op_detail_frame" style="position:absolute;display:none;margin-left:120px;width:70%;clear:both;z-index:2;height:auto;border:1px #000080 solid">
	<? echo HtmlInput::anchor_hide('Fermer', "$('search_action').style.display='none';");?>
<? endif; ?>
<? if ( $inner ) : ?>
<div id="search_action" class="op_detail_frame">
	<? echo HtmlInput::anchor_hide('Fermer', "removeDiv('search_action_div');");?>

	<? endif; ?>
	<h2 class="info">
		<?= _('Recherche avancée')?>
	</h2>
<? if (! $inner ) : ?>
	<form method="get" action="do.php" style="padding:10px">
<? endif; ?>
<? if ( $inner ) : ?>
	<form method="get" id="fsearchaction" style="padding:10px" onsubmit="result_search_action('fsearchaction');return false;">
		<?=HtmlInput::hidden('ctlc',$_GET['ctlc'])?>
<? endif; ?>
		<?= dossier::hidden()?>
		<table style="width:100%">
			<tr>
				<td style="width:180px;text-align:right"> Date de rappel après</td>
				<td>
					<?=$remind_date->input();?>
				</td>
			<tr>
			<tr>
				<td style="width:180px;text-align:right"> Date de rappel avant</td>
				<td>
					<?=$remind_date_end->input();?>
				</td>
			<tr>
			<td style="width:180px;text-align:right"> Référence</td>
				<td>
					<?=$osag_ref->input();?>
				</td>
			<tr>
			<tr>
				<td style="width:180px;text-align:right"> Numéro document</td>
				<td>
					<? $num=new INum('ag_id');echo $num->input();?>
				</td>
			<tr>
				<td style="width:180px;text-align:right"><? echo _('Destinataire')?></td>
				<? $label=$w->id."_label";?>
				<td ><?= $w->input() . $w->search()?><span id="<?=$label?>"></span></td>
			<tr>
			<tr>
				<td style="text-align:right" ><?= _("Profile")?></td>
				<td><?= $str_ag_dest?></td>
			</tr>
			<tr>
				<td style="text-align:right" ><?= _("Etat")?></td>
				<td><?= $type_state->input()?></td>
			</tr>
			<tr>
				<td style="text-align:right" ><?= _("Exclure Etat")?></td>
				<td><?= $hsExcptype_state->input()?></td>
			</tr>
			<td style="text-align:right"><? printf(_('contenant le mot'))?></td>
			<td ><input class="input_text" style="width:100%" type="text" name="query" value="<?= $a?>"></td>
			</tr>
			<tr>
				<td style="text-align:right"><?= _('Type de document')?></td>
				<td><? echo $type_doc->input();?></td>
			</tr>
			<tr>
				<td style="text-align:right">
					<? printf(_("Après le "))?>
				</td>
				<td >
					<?= $start->input()?>
				</td>
			</tr>
			<tr>
				<td style="text-align:right"><?= _('Avant le')?></td>
				<td>
					<?= $end->input()?>
				</td>
			</tr>
			</tr>
			<tr>
				<td style="text-align:right"><?= _('les actions  affectées à d\'autres')?></td>
				<td><?= $my_action->input()?>
				</td>
			</tr>
			<tr>
				<td style="text-align:right"><?= _('Uniquement actions internes')?></td>
				<td><?= $only_internal->input()?>
				</td>
			</tr>
		</table>
		<input type="submit" class="button" name="submit_query" value="<?= _('recherche')?>">
		<input type="hidden" name="sa" value="list">

		<?= $supl_hidden?>
		<? echo HtmlInput::button_anchor(_('Fermer'), 'javascript:void(0)', 'fsearch_form', 'onclick="$(\'search_action\').style.display=\'none\';"');?>
	</form>
</div>

