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
/* $Revision: 1649 $ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/*!\file
 * \brief manage the table forecast
 */
/*!
 * \brief manage the table forecast
 */
class Forecast
{
    private static $variable=array ("id"=>"f_id","name"=>"f_name");
    private $cn;
    /**
     * @brief constructor
     * @param $p_init Database object
     */
    function __construct ($p_init,$p_id=0)
    {
        $this->cn=$p_init;
        $this->f_id=$p_id;
    }
    public function get_parameter($p_string)
    {
        if ( array_key_exists($p_string,self::$variable) )
        {
            $idx=self::$variable[$p_string];
            return $this->$idx;
        }
        else
            exit (__FILE__.":".__LINE__."[$p_string]".'Erreur attribut inexistant');
    }
    public function set_parameter($p_string,$p_value)
    {
        if ( array_key_exists($p_string,self::$variable) )
        {
            $idx=self::$variable[$p_string];
            $this->$idx=$p_value;
        }
        else
            exit (__FILE__.":".__LINE__."[$p_string]".'Erreur attribut inexistant');
    }
    public function get_info()
    {
        return var_export(self::$variable,true);
    }

    public function verify()
    {
        // Verify that the elt we want to add is correct
        // the f_name must be unique (case insensitive)
        if ( strlen(trim($this->f_name))==0) throw new Exception(_('Le nom ne peut pas être vide'));
        return 0;
    }
    public function save()
    {
        /* please adapt */
        if (  $this->get_parameter("id") == 0 )
            $this->insert();
        else
            $this->update();
    }

    public function insert()
    {
        if ( $this->verify() != 0 ) return;
        $sql="insert into forecast (f_name) ".
             " values ($1)  returning f_id";
        $res=$this->cn->exec_sql(
                 $sql,
                 array($this->f_name)
             );
        $this->f_id=Database::fetch_result($res,0,0);
    }

    /**
     *@brief update the forecast table
     */
    public function update()
    {
        if ( $this->verify() != 0 ) return;

        $sql="update forecast set f_name=$1 ".
             " where f_id = $2";
        $res=$this->cn->exec_sql(
                 $sql,
                 array($this->f_name, $this->f_id)
             );

    }
    /**
     *@brief load all the existing forecast
     *@param $p_cn is an Database object
     *@return array of f_id and f_name
     */
    public static function load_all($p_cn)
    {
        $sql="select f_id, f_name from forecast";
        $ret=$p_cn->get_array($sql);
        return $ret;
    }
    public function load()
    {
        $sql="select f_name from forecast where f_id=$1";
        $res=$this->cn->exec_sql(
                 $sql,
                 array($this->f_id)
             );
        if ( Database::num_row($res) == 0 ) return -1;
        $row=Database::fetch_array($res,0);
        foreach ($row as $idx=>$value)
        {
            $this->$idx=$value;
        }

    }
    public function delete()
    {
        $sql="delete from forecast where f_id=$1";
        $res=$this->cn->exec_sql($sql,array($this->f_id));
    }
    /**
     * @brief unit test
     */
    static function test_me()
    {}

}
?>
