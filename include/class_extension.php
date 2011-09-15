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
 * \brief the extension class manages the plugins for the security, the access
 * the inclusion...
 */
/*!\brief manage the extension, it involves the table extension
 * 
 * Data member 
 * - $cn database connection
 * - $variable :
 *    -  id (extension.ex_id)
 *    -  name (extension.ex_name)
 *    - plugin_code (extension.ex_code)
 *    - desc (extension.ex_desc)
 *    - enable (extension.ex_enable)
 *    - filepath (extension.ex_file)
 */
class Extension
{
    private static $variable=array('id'=>'ex_id',
                                   'name'=>'ex_name',
                                   'code'=>'ex_code',
                                   'desc'=>'ex_desc',
                                   'enable'=>'ex_enable',
                                   'filepath'=>'ex_file');
    /*!\brief constructor
     *\param a database connextion
     */
    function __construct ($p_init)
    {
        $this->cn=$p_init;
        $this->ex_id=0;
    }
    public function get_parameter($p_string)
    {
        if ( array_key_exists($p_string,self::$variable) )
        {
            $idx=self::$variable[$p_string];
            return $this->$idx;
        }
        else
            exit (__FILE__.":".__LINE__.'Erreur attribut inexistant'.$p_string);
    }
    public function set_parameter($p_string,$p_value)
    {
        if ( array_key_exists($p_string,self::$variable) )
        {
            $idx=self::$variable[$p_string];
            $this->$idx=$p_value;
        }
        else
            exit (__FILE__.":".__LINE__.'Erreur attribut inexistant '.$p_string);


    }
    public function get_info()
    {
        return var_export(self::$variable,true);
    }
    public function verify()
    {
        // Verify that the elt we want to add is correct
        if (trim($this->ex_code)=="") throw new Exception('Le code ne peut pas être vide');
        if (trim($this->ex_name)=="") throw new Exception('Le nom ne peut pas être vide');
        if (trim($this->ex_file)=="") throw new Exception('Chemin incorrect');
        if (file_exists('..'.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'ext'.DIRECTORY_SEPARATOR.$this->ex_file) == false)
            throw new Exception ('Extension non trouvée, le chemin est-il correct?');
    }
    /*!\brief call insert for a new plugin or update if the plugin exist */
    public function save()
    {
        if (  $this->get_parameter("id") == 0 )
            $this->insert();
        else
            $this->update();
    }
    /*!\brief insert a plugin
    * \return error = -1 otherwiser the id */
    public function insert()
    {
        if ( $this->verify() != 0 ) return -1;
        if ( $this->cn->count_sql('select * from extension where upper(ex_code)=upper($1)',
                                  array($this->ex_code)) > 0)
            throw new Exception ('Ce code existe déjà');
        if ( $this->cn->count_sql('select * from extension where upper(ex_name)=upper($1)',
                                  array($this->ex_name)) > 0)
            throw new Exception ('Ce nom existe déjà');

        $sql='INSERT INTO extension( ex_code, ex_desc, ex_file, ex_enable,ex_name) '.
             ' VALUES ($1, $2, $3,$4,$5) returning ex_id';
        $res=$this->cn->exec_sql(
                 $sql,
                 array($this->ex_code,
                       $this->ex_desc,
                       $this->ex_file,
                       $this->ex_enable,
                       $this->ex_name)
             );
        $this->ex_id=Database::fetch_result($res,0,0);
    }
    /*!\brief update a plugin
    * \return error = -1 otherwiser 0 */
    public function update()
    {
        if ( $this->verify() != 0 ) return -1;
        $sql='UPDATE extension '.
             'SET ex_code=$1, ex_desc=$2, ex_file=$3, ex_enable=$4,ex_name=$5 '.
             ' WHERE ex_id=$6 ';
        $this->cn->exec_sql($sql,
                            array($this->ex_code,
                                  $this->ex_desc,
                                  $this->ex_file,
                                  $this->ex_enable,
                                  $this->ex_name,
                                  $this->ex_id)
                           );
        return 0;
    }
    /*!\brief load a plugin */
    public function load()
    {
        $sql='SELECT ex_id, ex_name,ex_code, ex_desc, ex_file, ex_enable '.
             '  FROM extension where ex_id=$1';
        $res=$this->cn->exec_sql($sql,array($this->ex_id));
        if ( Database::num_row($res) == 0 ) return -1;
        $row=Database::fetch_array($res,0);
        foreach ($row as $idx=>$value)
        {
            $this->$idx=$value;
        }
        return 0;
    }
    /*!\brief delete a plugin */
    public function delete()
    {
        $sql="delete from extension where ex_id=$1";
        $res=$this->cn->exec_sql($sql,array($this->ex_id));
    }
    /*!\brief load all the plugin and returns an array
     *\param $p_cn database connx 
     */
    static public function listing($p_cn)
    {
        $sql="SELECT ex_id, ex_name,ex_code, ex_desc, ex_file, ex_enable  FROM extension order by ex_code";
        $array=$p_cn->get_array($sql);
        return $array;
    }
    /*!\brief transform an array into a valid object
     *\param $array with the value
     *\verbatim
    code => ''
    desc =>''
    enable=>''
    file=>''
    actif=>''
    ex_id=>''
    \endverbatim
     *\see ajax_extension.php
     */
    function fromArray($p_array)
    {
        $this->ex_id=$p_array['ex_id'];
        $this->ex_name=$p_array['name'];
        $this->ex_code=$p_array['code'];
        $this->ex_desc=$p_array['desc'];
        $this->ex_enable=$p_array['enable'];
        $this->ex_file=$p_array['file'];

    }
    /*!@brief search a extension, the what is the column (extends_code */
    function search($p_what,$p_value)
    {
        switch($p_what)
        {
        case 'code':
                $cond=" where ex_code = upper($1) ";
            break;
        default:
            return -1;
        }

        $sql="select ex_id from extension $cond";
        $res=$this->cn->get_value($sql, array($p_value));
        if ( $res=="") return -1;

        $this->ex_id=$res;
        $this->load();

    }
    /*!\brief return 1 if the user given in parameter can access this extension
     * otherwise returns 0
     *\param $p_login the user login
     *\return 1 has access, 0 has no access 
     */
    function can_request($p_login)
    {
        $sql="select use_access from user_sec_extension where ".
             "upper(use_login ) = upper($1) and ex_id=$2";
        $ret=$this->cn->get_value($sql,array($p_login,$this->ex_id));
        if ( $this->cn->count() == 0 ) return 0;
        if ( $ret=='Y') return 1;
        return 0;
    }
    /*!@brief save the security in the table
     *@param $p_array is an array of value 
     * indices are :
     * - "is_" + login 
     * - value is Y or N
     * you must add the value of gDossier
    \code
    [is_phpcompta]=>N
    [gDossier]=>xx
    \endcode
     */
    function save_security($p_array)
    {
        $d=$p_array['gDossier'];
        $cn=new Database($d);
        $list_user=User::get_list($d);
        for ($i=0;$i<sizeof($list_user);$i++)
        {
            $ix="is_".$list_user[$i]['use_login'];
            /* if exist */
            if ( isset($p_array[$ix] ))
            {
                if (	   $cn->count_sql("select ex_id from user_sec_extension where use_login=$1 and ex_id=$2"
                                       ,array($list_user[$i]['use_login'],$this->ex_id)) == 0 )
                {
                    /* insert */
                    $sql="INSERT INTO user_sec_extension(
                         ex_id, use_login, use_access)  VALUES ($1,$2,$3)";
                    $cn->exec_sql($sql,array($this->ex_id,$list_user[$i]['use_login'],$p_array[$ix]));

                }
                else
                {
                    /* update */
                    $sql="update user_sec_extension set use_access=$1 where ex_id=$2 and use_login=$3";
                    $cn->exec_sql($sql,array($p_array[$ix],$this->ex_id,$list_user[$i]['use_login']));
                }
            }

        }
    }
    /*!@brief make an array of the available plugin for the current user
     * @return  an array
     *@see ISelect
     */
    static function make_array($cn)
    {
        $sql="select ex_code as value, ex_name as label from user_sec_extension ".
             " join extension using (ex_id) where ex_enable='Y' and use_access='Y' ".
             " and use_login=$1";
        $a=$cn->get_array($sql,array($_SESSION['g_user']));
        return $a;
    }
    static function check_version($i)
    {
        global $version_phpcompta;
        if ( ! isset ($version_phpcompta) || $version_phpcompta < $i )
        {
            alert('Cette extension ne fonctionne pas sur cette version de PhpCompta'.
                  ' Veuillez mettre votre programme a jour. Version minimum '.$i);
            exit();
        }
    }
    /*!\brief test this class
     */
    static function test_me()
    {
        $cn=new Database(dossier::id());
        print '<h1>Save</h1>';
        $ext=new Extension($cn);
        /* create a plugin */
        $ext->set_parameter('code','test');
        $ext->set_parameter('enable','Y');
        $ext->set_parameter('desc','plugin de test');
        $ext->set_parameter('filepath','test.php');
        $ext->save();
        print '<h1>Show</h1>';
        /* show plugins */
        $res=$cn->get_array('select * from extension');
        print_r($res);
        /* delete plugin */
        print '<h1>Load</h1>';
        $ext->load();
        print_r($ext);
        print '<h1>Delete</h1>';
        $ext->delete();
    }

}

/* test::test_me(); */
