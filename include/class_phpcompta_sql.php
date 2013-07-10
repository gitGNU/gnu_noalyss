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
 * this wrapper is used to created easily a wrapper to a table
 *
 *@class
 * Match a table into an object, you need to add the code for each table
 *@note : the primary key must be an integer
 *
 * @code
class table_name_sql extends phpcompta_sql
{

	function __construct($p_id=-1)
	{
		$this->table = "schema.table";
		$this->primary_key = "o_id";

		$this->name=array(
			"id"=>"o_id",
			"dolibarr"=>"o_doli",
			"date"=>"o_date",
			"qcode"=>"o_qcode",
			"fiche"=>"f_id",


		);

		$this->type = array(
			"o_id"=>"numeric",
			"o_doli"=>"numeric",
			"o_date"=>"date",
			"o_qcode"=>"text",
			"f_id"=>"numeric",

			);

		$this->default = array(
			"o_id" => "auto",
		);
		$this->date_format = "DD.MM.YYYY";
		global $cn;

		parent::__construct($cn,$p_id);
	}

}
 * @endcode
 *
 */
class phpcompta_sql
{

	function __construct(&$p_cn, $p_id = -1)
	{
		$this->cn = $p_cn;
		$pk=$this->primary_key;
		$this->$pk= $p_id;

			/* Initialize an empty object */
			foreach ($this->name as $key )
			{
				$this->$key= null;
			}
			$this->$pk= $p_id;
			/* load it */
			$this->load();
	}
	public function save() {
	    if (  $this->p_id == -1 )
		  $this->insert();
		else
			$this->update();
  }
	public function getp($p_string)
	{
		if (array_key_exists( $p_string,$this->name))
		{
			$idx = $this->name[$p_string];
			return $this->$idx;
		}
		else
			throw new Exception(__FILE__ . ":" . __LINE__ . $p_string . 'Erreur attribut inexistant '.$p_string);
	}

	public function setp($p_string, $p_value)
	{
		if (array_key_exists( $p_string,$this->name))
		{
			$idx = $this->name[$p_string];
			$this->$idx = $p_value;
		}
		else
			throw new Exception(__FILE__ . ":" . __LINE__ . $p_string . 'Erreur attribut inexistant '.$p_string);
	}

	public function insert()
	{
		$this->verify();
		$sql = "insert into " . $this->table . " ( ";
		$sep = "";
		$par = "";
		$idx = 1;
		$array = array();
		foreach ($this->name as $key=>$value)
		{
			if (isset($this->default[$value]) && $this->default[$value] == "auto" && $this->$value ==null )
				continue;
			if ( $value==$this->primary_key && $this->$value == -1 ) continue;
			$sql.=$sep.$value;
			switch ($this->type[$value])
			{
				case "date":
					if ($this->date_format=="")						throw new Exception('Format Date invalide');
					$par .=$sep. 'to_date($' . $idx . ",'" . $this->date_format . "')" ;
					break;
				default:
					$par .= $sep."$" . $idx ;
			}

			$array[] = $this->$value;
			$sep = ",";
			$idx++;
		}
		$sql.=") values (" . $par . ") returning " . $this->primary_key;
		$pk=$this->primary_key;
		$this->$pk = $this->cn->get_value($sql, $array);
	}

	public function delete()
	{
		$pk=$this->primary_key;
		$sql = " delete from " . $this->table . " where " . $this->primary_key . "=" . sql_string($this->$pk);
		$this->cn->exec_sql($sql);
	}

	public function update()
	{
		$this->verify();
		$pk=$this->primary_key;
		$sql = "update " . $this->table . "  ";
		$sep = "";
		$idx = 1;
		$array = array();
		$set=" set ";
		foreach ($this->name as $key=>$value)
		{
			if (isset($this->default[$value]) && $this->default[$value] == "auto"  )
				continue;
			switch ($this->type[$value])
			{
				case "date":
					$par =$value. '=to_date($' . $idx . ",'" . $this->date_format . "')" ;
					break;
				default:
					$par = $value."= $" . $idx ;
			}
			$sql.=$sep." $set " . $par ;
			$array[] = $this->$value;
			$sep = ",";$set="";$idx++;
		}
		$sql.=" where " . $this->primary_key . " =" . $this->$pk;
	    $this->cn->exec_sql($sql, $array);

	}

	public function load()
	{
		$sql = " select ";
		$sep="";$par="";

		foreach ($this->name as $key)
		{

			switch ($this->type[$key])
			{
				case "date":
					$sql .= $sep.'to_char(' . $key . ",'" . $this->date_format . "') as ".$key ;
					break;
				default:
					$sql.=$sep.$key ;
			}
			$sep = ",";
		}
		$pk=$this->primary_key;
		$sql.=" from ".$this->table;
		$sql.=" where " . $this->primary_key . " = " . $this->$pk;
		$result = $this->cn->get_array($sql);
		if ($this->cn->count() == 0 ) {
			$this->$pk=-1;
			return ;
		}

		foreach ($result[0] as $key=>$value) {
			$this->$key=$value;
		}
	}

	public function get_info()
	{
		return var_export($this, true);
	}

	public function verify()
	{
		foreach($this->name as $key){
			if ( trim($this->$key)=='') $this->$key=null;
		}
		return 0;
	}
	/**
	 * Transform an array into object
	 * @param type $p_array
	 * @return object
	 */
	public function from_array($p_array)
	{
		foreach ($this->name as $key=>$value)
		{
			if ( isset ($p_array[$value]))
			{
				$this->$value=$p_array[$value];
			}
			else
			{
				$this->$value=null;
			}
		}
		return $this;
	}
	  /**
   *@brief retrieve array of object thanks a condition
   *@param $cond condition (where clause) (optional by default all the rows are fetched)
   * you can use this parameter for the order or subselect
   *@param $p_array array for the SQL stmt
   *@see Database::exec_sql get_object  Database::num_row
   *@return the return value of exec_sql
   */
	 function seek($cond='', $p_array=null)
	{
		$sql = "select * from ".$this->table."  $cond";
		$ret = $this->cn->exec_sql($sql, $p_array);
		return $ret;
	}
	 /**
    *get_seek return the next object, the return of the query must have all the column
    * of the object
    *@param $p_ret is the return value of an exec_sql
    *@param $idx is the index
    *@see seek
    *@return object
    */
	public function next($ret,$i) {
		$array=$this->cn->fetch_array($ret,$i);
		return $this->from_array($array);
	}
	/**
	 *@see next
	 */
 public function get_object($p_ret,$idx)
    {
    return $this->next($p_ret, $i);
   }
}


?>
