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
 * @brief
 *
 */
require_once 'class_phpcompta_sql.php';

class Stock_Sql extends PhpCompta_Sql {
	function __construct($p_id=-1)
	{
		$this->table = "public.stock_repository";
		$this->primary_key = "r_id";

		$this->name=array(
			"id"=>"r_id",
			"name"=>"r_name",
			"adress"=>"r_adress",
			"city"=>"r_city",
			"country"=>"r_country",
			"phone"=>"r_phone"
		);

		$this->type = array(
			"r_id"=>"numeric",
			"r_name"=>"text",
			"r_adress"=>"text",
			"r_city"=>"text",
			"r_country"=>"text",
			"r_phone"=>"text"

			);

		$this->default = array(
			"r_id" => "auto",
		);
		global $cn;

		parent::__construct($cn,$p_id);
	}
}
?>
