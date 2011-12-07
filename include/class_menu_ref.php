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
		try
		{
        parent::verify();
        if ( $this->me_code == -1)
        {
            $this->format_code();
            if ( $this->cn->get_value("select count(*) from menu_ref where me_code=$1",array($this->me_code)) > 0)
                    throw new Exception ('Doublon');
            if (trim($this->me_code)=='')
                    throw new Exception ('Ce menu existe déjà');
        }
        if ( ! file_exists('../include/'.$this->me_file)) throw new Exception ('Ce menu fichier '.$this->me_file." n'existe pas");

        return 0;
		} catch (Exception $e)
		{
			alert($e->getMessage());
			return -1;
		}
    }

}

?>
