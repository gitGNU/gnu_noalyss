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

// Copyright Author Dany De Bontridder dany@alchimerys.be

/**
 * @file
 * @brief Inventory : add manuel change
 *
 */
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
require_once 'class_stock_goods.php';
global $cn;

$inv=new Stock_Goods($cn);
if ( isset ($_POST['save']))
{
	try
	{
		$inv->record_save($_POST);
		echo h2info("Opération sauvée");
		$inv->input($_POST,true);

	}catch(Exception $e)
	{
		 alert($e->getMessage());
		 $inv->input($_POST);
	}
}
echo $inv->input();

?>
