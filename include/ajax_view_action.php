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
 * @brief show the detail of an action
 *
 */
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
require_once 'class_follow_up.php';
echo HtmlInput::title_box(_("Détail action"), $div);
$act = new Follow_Up($cn);
$act->ag_id = $ag_id;
$act->get();
if ($g_user->can_write_action($ag_id) == true || $g_user->can_read_action($ag_id) == true || $act->ag_dest == -1)
{
	echo $act->Display('READ', false, "ajax", "");
	$action=HtmlInput::array_to_string(array("gDossier","ag_id"), $_GET)."&ac=FOLLOW&sa=detail";
	if ( $_GET['mod']== 1) :
	?>
<a href="<?php echo $action?>" target="_blank" class="button">Modifier </a>
    <?php 
	endif;
}
else
{
	echo h2(_("Ce document n'est pas accessible"),"error");
	?>
	<div style="margin:0;padding:0;background-color:red;text-align:center;">
<h2 class="error">Accès interdit : vous n'avez pas accès à cette information, contactez votre responsable</h2>;
</div>
	<?php 
}
echo HtmlInput::button_close($div);

?>