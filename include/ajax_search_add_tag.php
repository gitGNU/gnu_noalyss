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
if ( !defined ('ALLOWED') )  die('Appel direct ne sont pas permis');

require_once 'class_tag.php';
ob_start();
if ($_GET['clear']==1) {
    /* Add a clear button */
    echo Tag::add_clear_button($_GET['pref']);
}
$tag=new Tag($cn,$_GET['id']);
$tag->update_search_cell($_GET['pref']);

$response=  ob_get_clean();
ob_end_clean();
$html=escape_xml($response);
header('Content-type: text/xml; charset=UTF-8');
echo <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<data>
<ctl></ctl>
<html>$html</html>
</data>
EOF;
exit();


?>

