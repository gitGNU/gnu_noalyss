<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *@file
 *@brief Menu_Ref let you manage the available menu
 */
require_once 'class_menu_ref_sql.php';
class Menu_Ref extends Menu_Ref_sql
{
    function format_code()
    {
        $this->me_code=strtoupper($this->me_code);
        $this->me_code=trim($this->me_code);
        $this->me_code=str_replace('<','',$this->me_code);
        $this->me_code=str_replace('>','',$this->me_code);
    }
    function verify()
    {
        parent::verify();
        if ( $this->me_code == -1)
        {
            $this->format_code();
            if ( $this->cn->get_value("select count(*) from menu_ref where me_code=$1",array($this->me_code)) > 0)
                    return -1;
            if (trim($this->me_code)=='')
                    return -2;
        }
        return 0;
    }
    
}

?>
