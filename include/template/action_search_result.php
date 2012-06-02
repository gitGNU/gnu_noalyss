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
 * @brief show the result of a search into a inner windows
 *
 */
?>
<form onsubmit="set_action_related('fresultaction');return false;" id="fresultaction">
	<?=HtmlInput::hidden('ctlc',$_GET['ctlc'])?>
	<?=HtmlInput::submit("save_action", "Mettre à jour")?>
<table class="result">

	<tr>
		<th>

		</th>
		<th>
			Date
		</th>
		<th>
			Ref
		</th>
		<th>
			Titre
		</th>
		<th>
			Destinataire
		</th>
		<th>
			Type
		</th>
	</tr>
<? for ($i=0;$i<$limit;$i++):?>
	<? $class=($i%2==0)?' class="odd" ':' class="info"'; ?>
	<tr  <?=$class?>>
		<td>
			<?
			$ck=new ICheckBox('ag_id[]');
			 $ck->value=$a_row[$i]['ag_id'];
			 echo $ck->input();
			?>
		</td>
		<td >
			<?=h($a_row[$i]['my_date'])?>
		</td>
		<td>
			<?=h($a_row[$i]['ag_ref'])?>
		</td>
		<td>
			<?=h($a_row[$i]['sub_ag_title'])?>
		</td>
		<td>
			<?=h($a_row[$i]['name'])?>
		</td>
		<td>
			<?=h($a_row[$i]['dt_value'])?>
		</td>
	</tr>

<? endfor;?>
</table>
	<?=HtmlInput::submit("save_action", "Mettre à jour")?>
</form>