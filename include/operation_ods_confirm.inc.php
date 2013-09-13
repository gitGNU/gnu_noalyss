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

/**\file
 *
 *
 * \brief confirm ODS operation
 *
 */
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
echo '<div class="content">';
echo h2("Confirmation",'class="info"');
echo '<div id="jrn_name_div">';
echo '<h2 id="jrn_name" style="display:inline">' . $ledger->get_name() . '</h2>';
echo '</div>';

echo '<FORM METHOD="POST" class="print">';
echo HtmlInput::request_to_hidden(array('ac'));
echo $ledger->confirm($_POST,false);

echo '<hr>';
$chk=new ICheckBox();
$chk->selected=false;
echo $chk->input('opd_save');
echo "Sauvez cette op&eacute;ration comme modèle d'opération ?";
echo '<br/>';
$opd_name=new IText('opd_name');
echo "Nom du modèle ".$opd_name->input();
echo '<hr>';
echo HtmlInput::submit("save","Confirmer");
echo HtmlInput::submit("correct","Corriger");

echo '</FORM>';
?>
