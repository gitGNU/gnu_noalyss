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
 * @brief history of manuel change
 *
 */
if ( isset($_POST['del']))
{
	if (isset($_POST['ok']))
	{
		if ($g_user->can_write_repo($_POST['r_id']))
		{
			$cn->exec_sql('delete from stock_change where c_id=$1',array($_POST['c_id']));
		}
		else
		{
			alert("Vous ne pouvez pas modifier ce dépôt");
		}
	}
	else
	{
		alert("Opération non effacée: vous n'avez pas confirmé");
	}
}
$profile=$g_user->get_profile();
$a_change=$cn->get_array("select *,to_char(c_date,'DD.MM.YY') as str_date from stock_change as sc
			join stock_repository as sr on (sc.r_id=sr.r_id)
			where sc.r_id in (select r_id from profile_sec_repository where p_id=$profile)
		order by c_date");
$gDossier=dossier::id();
?>
<div class="content">
<table class="result">
	<tr>

		<th>
			Date
		</th>
		<th>
			Commentaire
		</th>
		<th>
			Dépot
		</th>
		<th>
			Utilisateur
		</th>
			<th>

		</th>
	</tr>
	<? for ($e=0;$e<count($a_change);$e++): ?>
	<? $class=($e%2==0)?' class="even" ':' class="odd" '; ?>
	<tr <?=$class?>>

		<td>
			<?=  $a_change[$e]['str_date']?>
		</td>
		<td>
			<?=h($a_change[$e]['c_comment'])?>
		</td>
		<td>
			<?=h($a_change[$e]['r_name'])?>
		</td>
		<td>
			<?=$a_change[$e]['tech_user']?>
		</td>
		<td>
			<?=HtmlInput::button_action("Détail",sprintf("stock_inv_detail('%s','%s')",$gDossier,$a_change[$e]['c_id']));?>
		</td>

	</tr>
	<? endfor; ?>
</table>
</div>
