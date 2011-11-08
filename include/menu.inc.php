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

/* !\file
 *
 *
 * \brief Show the table menu and let you add your own
 *
 */
require_once 'class_menu_ref_sql.php';
require_once 'class_sort_table.php';
echo '<div class="content">';
/**
 * if post save then we save a new one
 */

/**
 * if post update then we update
 */

/**
 * if delete then delete
 */

//////////////////////////////////////////////////////////////////////////////
// Show the list of menu
//////////////////////////////////////////////////////////////////////////////
global $cn;

$table=new Sort_Table();
$url=$_SERVER['REQUEST_URI'];

$table->add('Code',$url,"order by me_code asc","order by me_code desc","codea","coded");
$table->add('Menu',$url,"order by me_menu asc","order by me_menu desc","menua","menud");
$table->add('Description',$url,"order by me_description asc","order by me_description desc","desa","desd");
$table->add('URL',$url,"order by me_url asc","order by me_url desc","urla","urld");
$table->add('Paramètre',$url,"order by me_parametere asc","order by me_parameter desc","paa","pad");
$table->add('Javascript',$url,"order by me_javascript asc","order by me_javascript desc","jsa","jsd");
$table->add('Type',$url,"order by me_type asc","order by me_type desc","ta","td");

$ord=(isset($_REQUEST['ord']))?$_REQUEST['ord']:'codea';

$order=$table->get_sql_order($ord);

$menu=new Menu_Ref_sql($cn);
$ret=$menu->seek($order);
echo '<p class="info"> le type vaut :
	<ul>
	<li> ME pour Menu</li>
	<li> PR pour les impressions </li>
	<li> PL pour les plugins</li>
	<li> SP pour des valeurs spéciales</li>
	</p>';
echo '<table class="result">';
echo '<tr>';
echo '<th>'.$table->get_header(0).'</th>';
echo '<th>'.$table->get_header(1).'</th>';
echo '<th>'.$table->get_header(2).'</th>';
echo '<th>'.$table->get_header(3).'</th>';
echo '<th>'.$table->get_header(4).'</th>';
echo '<th>'.$table->get_header(5).'</th>';
echo '<th>'.$table->get_header(6).'</th>';
echo '</tr>';

for ($i=0;$i<Database::num_row($ret);$i++)
{
	$row=$menu->get_object($ret, $i);
	echo '<tr>';
	echo td($row->me_code);
	echo td($row->me_menu);
	echo td($row->me_description);
	echo td($row->me_url);
	echo td($row->me_parameter);
	echo td($row->me_javascript);
	echo td($row->me_type);
	echo '</tr>';
}
echo '</table>';

?>
