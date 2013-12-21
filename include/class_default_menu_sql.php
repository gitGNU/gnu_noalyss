<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_default_menu_sql
 *
 * @author dany
 */
require_once 'class_phpcompta_sql.php';

class Default_Menu_SQL extends Phpcompta_SQL
{
    var $md_id;
    var $md_code;
    var $me_code;

    function __construct(&$p_cn, $p_id = -1)
    {
        $this->table = "public.menu_default";
        $this->primary_key = "md_id";

        $this->name = array(
            "md_id"=>"md_id",
            "md_code" => "md_code",
            "me_code" => "me_code"
        );
        $this->type = array(
            "md_id"=>"md_id"
            ,"md_code" => "text"
            , "me_code" => "text"
        );
        $this->default = array(
            "md_id"
        );
        global $cn;

        parent::__construct($cn, $p_id);
    }

}
