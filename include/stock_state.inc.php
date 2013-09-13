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
 * @brief show the state of the repository
 *
 */
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
global $cn,$g_parameter,$g_user;
require_once 'class_stock.php';

// Show the form
// Get by exercice
// Get type = table or list
$iexercice=new ISelect('state_exercice');
$iexercice->value=$cn->make_array("select  max(p_end) as date_end, p_exercice from parm_periode  group by p_exercice order by 2 desc");
$iexercice->selected=(isset($_GET['state_exercice']))?$_GET['state_exercice']:"";

$presentation=new ISelect("present");
$presentation->value=array (
		array("value"=>"T","label"=>"Tableau récapitulatif"),
		array("value"=>"L","label"=>"Liste")
);
$presentation->selected=(isset($_GET['present']))?$_GET['present']:"T";
require_once 'template/stock_state_search.php';

$stock=new Stock($cn);


$stock->summary($_GET);

?>
