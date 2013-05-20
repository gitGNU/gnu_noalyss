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
 * \brief display a form to change the name of a predefined operation
 */
ob_start();
require_once 'class_pre_operation.php';
$op=new Pre_Operation($cn,$_GET['id']);
$array=$op->load();
echo HtmlInput::anchor_close('mod_predf_op');
echo h2info('Modification du nom');

echo '
    <form method="POST" onsubmit="save_predf_op(this);return false;">';
$name = new IText('opd_name');
$name->value = $op->od_name;
$name->size = 60;
echo "Nom =" . $name->input();
echo dossier::hidden() . HtmlInput::hidden('od_id', $_GET['id']);
echo "<hr>";
//////////////////////////////////////////////////////////////////////////////
// Detail operation 
//////////////////////////////////////////////////////////////////////////////
echo $op->display();


echo HtmlInput::submit('save', 'Sauve');
echo HtmlInput::button('close', 'Annuler', 'onclick="removeDiv(\'mod_predf_op\')"');
echo '</form>';


$html = ob_get_contents();
ob_end_clean();
$html = escape_xml($html);
//echo $html;exit();
header('Content-type: text/xml; charset=UTF-8');
echo <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<data>
<ctl>mod_predf_op</ctl>
<code>$html</code>
</data>
EOF;
