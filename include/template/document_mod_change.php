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
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/* !\file
 * \brief this file respond to an ajax request to modify a type of document
 */
require_once 'class_document_type.php';
echo HtmlInput::title_box(_('Type de document'),'change_doc_div');
?>
<form method="GET" id="cat_doc_f" onsubmit="cat_doc_change_record('cat_doc_f');">
<table>
<tr>
  <td> <?=_('Nom')?>
  </td>
  <td>
  </td>
</tr>

<tr>
  <td><?=_('Préfixe')?>
  </td>
  <td>
  </td>
</tr>

<tr>
  <td><?=_('Prochain numéro')?>
  </td>
  <td>
  </td>
</tr>

</table>

  <? echo HtmlInput::submit("save",_('Sauver'));?>
</form>