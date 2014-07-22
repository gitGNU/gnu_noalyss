<?php
/*
 *   This file is part of NOALYSS.
 *
 *   NOALYSS is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   NOALYSS is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with NOALYSS; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */
// Copyright Author Dany De Bontridder danydb@aevalys.eu

/**
 * @file
 * @brief Manage the table public.menu_ref
 *
 *
 */
require_once('class_database.php');
require_once('ac_common.php');

/**
 * @brief Manage the table public.menu_ref
 */
class Menu_Ref_sql
{
	/* example private $variable=array("easy_name"=>column_name,"email"=>"column_name_email","val3"=>0); */

	protected $variable = array(
		"me_code" => "me_code"
		, "me_menu" => "me_menu"
		, "me_file" => "me_file"
		, "me_url" => "me_url"
		, "me_description" => "me_description"
		, "me_parameter" => "me_parameter"
		, "me_javascript" => "me_javascript"
		, "me_type" => "me_type"
	);

	function __construct(& $p_cn, $p_id=-1)
	{
		$this->cn = $p_cn;
		$this->me_code = $p_id;

		if ($p_id == -1)
		{
			/* Initialize an empty object */
			foreach ($this->variable as $key => $value)
				$this->$value = null;
			$this->me_code = $p_id;
		}
		else
		{
			/* load it */

			$this->load();
		}
	}

	public function get_parameter($p_string)
	{
		if (array_key_exists($p_string, $this->variable))
		{
			$idx = $this->variable[$p_string];
			return $this->$idx;
		}
		else
			throw new Exception(__FILE__ . ":" . __LINE__ . $p_string . 'Erreur attribut inexistant');
	}

	public function set_parameter($p_string, $p_value)
	{
		if (array_key_exists($p_string, $this->variable))
		{
			$idx = $this->variable[$p_string];
			$this->$idx = $p_value;
		}
		else
			throw new Exception(__FILE__ . ":" . __LINE__ . $p_string . 'Erreur attribut inexistant');
	}

	public function get_info()
	{
		return var_export($this, true);
	}

	public function verify()
	{
		// Verify that the elt we want to add is correct
		/* verify only the datatype */
		if (trim($this->me_menu) == '')
			$this->me_menu = null;
		if (trim($this->me_file) == '')
			$this->me_file = null;
		if (trim($this->me_url) == '')
			$this->me_url = null;
		if (trim($this->me_description) == '')
			$this->me_description = null;
		if (trim($this->me_parameter) == '')
			$this->me_parameter = null;
		if (trim($this->me_javascript) == '')
			$this->me_javascript = null;
		if (trim($this->me_type) == '')
			$this->me_type = null;
	}

	/**
	 * @brief retrieve array of object thanks a condition
	 * @param $cond condition (where clause) (optional by default all the rows are fetched)
	 * you can use this parameter for the order or subselect
	 * @param $p_array array for the SQL stmt
	 * @see Database::exec_sql get_object  Database::num_row
	 * @return the return value of exec_sql
	 */
	public function seek($cond='', $p_array=null)
	{
		$sql = "select * from public.menu_ref  $cond";
		$aobj = array();
		$ret = $this->cn->exec_sql($sql, $p_array);
		return $ret;
	}

	/**
	 * get_seek return the next object, the return of the query must have all the column
	 * of the object
	 * @param $p_ret is the return value of an exec_sql
	 * @param $idx is the index
	 * @see seek
	 * @return object
	 */
	public function get_object($p_ret, $idx)
	{
		// map each row in a object
		$oobj = new Menu_Ref_sql($this->cn);
		$array = Database::fetch_array($p_ret, $idx);
		foreach ($array as $idx => $value)
		{
			$oobj->$idx = $value;
		}
		return $oobj;
	}

	public function insert()
	{
		if ($this->verify() != 0)
			return;

			$sql = "insert into public.menu_ref(me_menu
					,me_file
					,me_url
					,me_description
					,me_parameter
					,me_javascript
					,me_type
					,me_code) values ($1
					,$2
					,$3
					,$4
					,$5
					,$6
					,$7
					,$8
					) returning me_code";

			$this->me_code = $this->cn->get_value(
					$sql, array($this->me_menu
				, $this->me_file
				, $this->me_url
				, $this->me_description
				, $this->me_parameter
				, $this->me_javascript
				, $this->me_type
				, $this->me_code)
			);

	}

	public function update()
	{
		if ($this->verify() != 0)
			return;
		/*   please adapt */
		$sql = " update public.menu_ref set me_menu = $1
					,me_file = $2
					,me_url = $3
					,me_description = $4
					,me_parameter = $5
					,me_javascript = $6
					,me_type = $7
					 where me_code= $8";
		$res = $this->cn->exec_sql(
				$sql, array($this->me_menu
			, $this->me_file
			, $this->me_url
			, $this->me_description
			, $this->me_parameter
			, $this->me_javascript
			, $this->me_type
			, $this->me_code)
		);
	}

	/**
	 * @brief load a object
	 * @return 0 on success -1 the object is not found
	 */
	public function load()
	{

		$sql = "select me_menu
						,me_file
						,me_url
						,me_description
						,me_parameter
						,me_javascript
						,me_type
						 from public.menu_ref where me_code=$1";
		/* please adapt */
		$res = $this->cn->get_array(
				$sql, array($this->me_code)
		);

		if (count($res) == 0)
		{
			/* Initialize an empty object */
			foreach ($this->variable as $key => $value)
				$this->$key = '';

			return -1;
		}
		foreach ($res[0] as $idx => $value)
		{
			$this->$idx = $value;
		}
		return 0;
	}

	public function delete()
	{
		$sql = "delete from public.menu_ref where me_code=$1";
		$res = $this->cn->exec_sql($sql, array($this->me_code));
	}

	/**
	 * Unit test for the class
	 */
	static function test_me()
	{
	}

}

// Menu_Ref_sql::test_me();
?>
