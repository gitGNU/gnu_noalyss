<?php

/**
 * @file
 * @brief Manage the table public.profile
 *
 *
  Example
  @code

  @endcode
 */
require_once('class_database.php');
require_once('ac_common.php');
require_once 'class_phpcompta_sql.php';

/**
 * @brief Manage the table public.profile
 */
class Profile_sql extends Phpcompta_SQL
{
	/* example private $variable=array("easy_name"=>column_name,"email"=>"column_name_email","val3"=>0); */

	function __construct(& $p_cn, $p_id = -1)
	{
		$this->table = "public.profile";
		$this->primary_key = "p_id";

		$this->name = array(
			"p_id" => "p_id"
			, "p_name" => "p_name"
			, "p_desc" => "p_desc"
			, "with_calc" => "with_calc"
			, "with_direct_form" => "with_direct_form"
		);
		$this->type = array(
			"p_id" => "numeric"
			, "p_name" => "text"
			, "p_desc" => "text"
			, "with_calc" => "text"
			, "with_direct_form" => "text"
		);
		$this->default = array(
			"p_id" => "auto",
		);
		global $cn;

		parent::__construct($cn,$p_id);

	}

}

// Profile_sql::test_me();
?>
