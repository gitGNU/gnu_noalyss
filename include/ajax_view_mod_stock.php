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
 * @brief show detail of inv.
 *
 */
require_once 'class_stock_goods.php';
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');

$st=new Stock_Goods($cn);
$array=$cn->get_array("select * from stock_goods where c_id=$1",array($_GET['c_id']));
echo HtmlInput::title_box("Détail changement",$_GET['ctl']);
$p_array=array();
$p_array['p_date']=$cn->get_value("select to_char(c_date,'DD.MM.YYYY') from stock_change where c_id=$1",array($_GET['c_id']));
$p_array['p_motif']=$cn->get_value("select c_comment from stock_change where c_id=$1",array($_GET['c_id']));
$p_array['p_depot']=$cn->get_value("select r_id from stock_change where c_id=$1",array($_GET['c_id']));
for ($i=0;$i<count($array);$i++)
{
	$p_array['f_id'.$i]=$array[$i]['f_id'];
	$p_array['sg_code'.$i]=$array[$i]['sg_code'];
	$p_array['sg_quantity'.$i]=$array[$i]['sg_quantity'];
	$p_array['sg_type'.$i]=$array[$i]['sg_type'];
        $p_array['row']=$i+1;
}
echo $st->input($p_array,true);
?>
<form method="POST">
	<?php echo HtmlInput::hidden('c_id',$_GET['c_id']);?>
	<p>
	<?php $ck=new ICheckBox("ok"," 1");
			$ck->label= "Cochez pour confirmer effacement ";
			echo $ck->input();?>
	</p>
	<?php echo HtmlInput::submit("del", "Effacer");?>
	<?php echo HtmlInput::button_close($_GET['ctl'])?>
	<?php echo HtmlInput::hidden('r_id',$p_array['p_depot'])?>
</form>
