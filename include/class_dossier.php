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

/*!\file
 * \brief the class for the dossier, everywhere we need to know to
 * which folder we are connected, because we can't use $_SESSION, we
 * need to pass the dossier_id via a _GET or a POST variable
 */

/*! \brief manage the current dossier, everywhere we need to know to
 * which folder we are connected, because we can't use $_SESSION, we
 * need to pass the dossier_id via a _GET or a POST variable
 *  private static $variable=array("id"=>"dos_id",
				 "name"=>"dos_name",
				 "desc"=>"dos_description");
 *
 */
require_once('class_database.php');
require_once('ac_common.php');

class dossier
{
    private static $variable=array("id"=>"dos_id",
                                   "name"=>"dos_name",
                                   "desc"=>"dos_description");
    function __construct($p_id)
    {
        $this->cn=new Database(); 	// Connect to the repository
        $this->dos_id=$p_id;
    }
    /*!\brief return the $_REQUEST['gDossier'] after a check */
    static function id()
    {
        self::check();
        return $_REQUEST['gDossier'];
    }

    /*!
     * \param  p_type string : all for all dossiers lim for only the 
     *             dossier where we've got rights
     * 
     * Show the folder where user have access. Return    : nothing
     ++*/
    function show_dossier($p_type,$p_first=0,$p_max=10,$p_Num=0)
    {
        $l_user=$_SESSION['g_user'];
        if ( $p_max == 0 )
        {
            $l_step="";
        }
        else
        {
            $l_step="LIMIT $p_max OFFSET $p_first";
        }

        if ( $p_type == "all")
        {
            $l_sql="select *, 'W' as priv_priv from ac_dossier ORDER BY dos_name  ";
            $p_Num=$this->cn->count_sql($l_sql);
        }
        else
        {
            $l_sql="select * from jnt_use_dos
                   natural join ac_dossier
                   natural join ac_users
                   inner join priv_user on priv_jnt=jnt_id where
                   use_login='".$l_user."' and priv_priv !='NO'
                   order by dos_name ";
            $p_Num=$this->cn->count_sql($l_sql);
        }
        $l_sql=$l_sql.$l_step;
        $p_res=$this->cn->exec_sql($l_sql);


        $Max=$this->cn->size();
        if ( $Max == 0 ) return null;
        for ( $i=0;$i<$Max; $i++)
        {
            $row[]=$this->cn->fetch($p_res);
        }
        return $row;
    }

    /*!
     * \brief Return all the users
     * as an array
     */
    function get_user()
    {
        $sql="select * from ac_users where use_login!='phpcompta'";
        $Res=$this->cn->exec_sql($sql);
        $Num=$this->cn->size();
        if ( $Num == 0 ) return null;
        for ($i=0;$i < $Num; $i++)
        {
            $User[]=$this->cn->fetch($i);
        }
        return $User;
    }

    /*!\brief check if gDossier is set */
    static function check()
    {
        if ( ! isset ($_REQUEST['gDossier']) )
        {
            echo_error ('Dossier inconnu ');
            exit('Dossier invalide ');
        }
        $id=$_REQUEST['gDossier'];
        if ( is_numeric ($id) == 0 ||
                strlen($id)> 6 ||
                $id > 999999)
            exit('gDossier Invalide : '.$id);

    }
    /*!\brief return a string to put to gDossier into a GET */
    static function get()
    {
        self::check();
        return "gDossier=".$_REQUEST['gDossier'];

    }

    /*!\brief return a string to set gDossier into a FORM */
    static function hidden()
    {
        self::check();
        return '<input type="hidden" id="gDossier" name="gDossier" value="'.$_REQUEST['gDossier'].'">';
    }
    /*!\brief retrieve the name of the current dossier */
    static function name($id=0)
    {
        self::check();

        $cn=new Database();
        $id=($id==0)?$_REQUEST['gDossier']:$id;
        $name=$cn->get_value("select dos_name from ac_dossier where dos_id=$1",array($_REQUEST['gDossier']));
        return $name;
    }

    public function get_parameter($p_string)
    {
        if ( array_key_exists($p_string,self::$variable) )
        {
            $idx=self::$variable[$p_string];
            return $this->$idx;
        }
        else
            exit (__FILE__.":".__LINE__.'Erreur attribut inexistant');
    }
    public function set_parameter($p_string,$p_value)
    {
        if ( array_key_exists($p_string,self::$variable) )
        {
            $idx=self::$variable[$p_string];
            $this->$idx=$p_value;
        }
        else
            exit (__FILE__.":".__LINE__.'Erreur attribut inexistant');


    }
    public function get_info()
    {
        return var_export(self::$variable,true);
    }

    public function save()
    {
        $this->update();
    }

    public function update()
    {
        if ( strlen(trim($this->dos_name))== 0 ) return;

        if ( $this->cn->get_value("select count(*) from ac_dossier where dos_name=$1 and dos_id<>$2",
                                  array($this->dos_name,$this->dos_id)) !=0 )
            return ;

        $sql="update ac_dossier set dos_name=$1,dos_description=$2 ".
             " where dos_id = $3";
        $res=$this->cn->exec_sql(
                 $sql,
                 array(trim($this->dos_name),
                       trim($this->dos_description),
                       $this->dos_id)
             );
    }

    public function load()
    {

        $sql="select dos_name,dos_description from ac_dossier where dos_id=$1";

        $res=$this->cn->exec_sql(
                 $sql,
                 array($this->dos_id)
             );

        if ( Database::num_row($res) == 0 ) return;
        $row=Database::fetch_array($res,0);
        foreach ($row as $idx=>$value)
        {
            $this->$idx=$value;
        }

    }

    static function get_version($p_cn)
    {
        return $p_cn->get_value('select val from version');
    }
}
