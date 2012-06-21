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
 * @brief Manage the status of the document (document_state)
 *
 */
global $cn;

if ( isset($_POST['add']))
{
	if (trim ($_POST['s_value'])!="")
	{
		$cn->exec_sql('insert into document_state(s_value) values ($1)',array($_POST['s_value']));
	}
}
$a_stat=$cn->get_array("select s_value   from document_state order by 1");
?>

<table>
	<? for ($i=0;$i<count($a_stat);$i++):?>

	<tr>
		<td>
			<?=h($a_stat[$i]['s_value'])?>
		</td>
	</tr>
	<?	endfor;?>
</table>
<form method="post" onsubmit="return confirm ('Vous confirmez ?'); ">
	<? $value=new IText("s_value",""); echo $value->input()?>
	<?=HtmlInput::submit("add", "Ajouter")?>
</form>