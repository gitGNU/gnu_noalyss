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

/*!\file
 * \brief this file is included to perform modification on category of document
 * table document_type
 */

// show list of document
require_once ('class_document_type.php');
if ( isset($_POST['add']) )
{
    $catDoc=new Document_Type($cn);
    $catDoc->insert($_POST['cat']);
}
$aList=Document_Type::get_list($cn);
$addCat=new IText('cat');
$str_addCat=$addCat->input();
$str_submit=HtmlInput::submit('add',_('Ajout'));
echo '<div class="content">';
require_once('template/list_category_document.php');
echo '</div>';
?>
?>
?>