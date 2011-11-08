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
require_once 'class_profile_sql.php';
global $cn;
echo '<div class="content">';
$table=new Sort_Table();
$url=$_SERVER['REQUEST_URI'];

$table->add('Nom',$url,"order by p_name asc","order by p_name desc","na","nd");
$table->add('Description',$url,"order by p_desc asc","order by p_desc desc","da","dd");
$table->add('Calculatrice visible',$url,"order by with_calc asc","order by with_calc desc","ca","cd");
$table->add('Form Direct visible',$url,"order by with_direct_form asc","order by with_direct_form desc","fa","fd");

$ord=(isset($_REQUEST['ord']))?$_REQUEST['ord']:'na';

$order=$table->get_sql_order($ord);

$menu=new Profile_sql($cn);
$ret=$menu->seek($order);
echo '<table class="result">';
echo '<tr>';
echo '<th>'.$table->get_header(0).'</th>';
echo '<th>'.$table->get_header(1).'</th>';
echo '<th>'.$table->get_header(2).'</th>';
echo '<th>'.$table->get_header(3).'</th>';
echo '</tr>';

for ($i=0;$i<Database::num_row($ret);$i++)
{
	$row=$menu->get_object($ret, $i);
	echo '<tr>';
	echo td($row->p_name);
	echo td($row->p_desc);
	echo td($row->with_calc);
	echo td($row->with_direct_form);
	echo '</tr>';
}
echo '</table>';
echo '</div>';

?>
