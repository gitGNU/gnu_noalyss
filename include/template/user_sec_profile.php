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
 * @brief show the available profiles for action-management
 *
 */
?>
<form method="POST" class="print">
	<?=HtmlInput::hidden("p_id", $p_id);?>
	<table>
		<tr>
			<th><?=_("Profile")?></th>
			<th><?=_("Accès")?></th>
		</tr>
		<? for ($i=0;$i<count($array);$i++): ?>
		<tr>
			<td>
				<?=$array[$i]['p_name']?>
				<?=HtmlInput::hidden('ua_id[]',$array[$i]['ua_id'])?>
				<?=HtmlInput::hidden('ap_id[]',$array[$i]['p_id'])?>
			</td>
			<td>
				<?
				$isel=new ISelect("right[]");
				$isel->value=$aright_value;
				$isel->selected=$array[$i]['ua_right'];
				echo $isel->input();?>
			</td>
		</tr>
		<?endfor;?>
	</table>
<?=HtmlInput::submit("change_profile", "Sauver")?>
</form>