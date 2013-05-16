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
 * @brief add, modify or delete plugin
 *
 */
$msg=($new==1)?"Nouvelle extension":"Modification ".$me_menu->value;
echo HtmlInput::title_box($msg, $ctl);
?>
<form method="POST" onsubmit="return confirm('Vous confirmez')">
<table>
	<tr>
		<TD>Label</td>
		<td><?php echo $me_menu->input();?></td>
	</tr>
	<tr>
		<TD>Code</td>
		<td><?php echo $me_code->input();?></td>
	</tr>
	<tr>
		<TD>Description</td>
		<td><?php echo $me_description->input();?></td>
	</tr>
	<tr>
		<TD>Fichier</td>
		<td><?php echo $me_file->input();?></td>
	</tr>
</table>
	<?php 
	if ($new ==1 )
	{
		echo HtmlInput::submit("save_plugin","Ajouter ce plugin");
	} else {
		$delete=new ICheckBox('delete_pl');
		echo "<p>Voulez-vous effacer ce plugin ? ".$delete->input()."</p>";
		echo HtmlInput::submit("mod_plugin","Modifier ce plugin");

	}
	?>
</form>