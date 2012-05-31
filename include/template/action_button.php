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
 * @brief show button in the list of actions
 *
 */
?>
<div class="content" >
	<div>
		<form  method="get" action="do.php">
			<?php echo dossier::hidden();
			?>
			<input type="submit" class="button" name="submit_query" value="<?= _("Ajout Action")?>">
			<input type="hidden" name="ac" value="<?= $_REQUEST['ac']?>">
			<input type="hidden" name="sa" value="add_action">
			<?= $supl_hidden?>
			<input id="bt_search" type="button" class="button" onclick="$('search_action').style.display='block'" value="<?= _('Recherche')?>">



		</form>
	</div>