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
require_once 'class_menu_ref_sql.php';

class Extension extends Menu_Ref_sql
{
    public function verify()
    {
        // Verify that the elt we want to add is correct
        if (trim($this->me_code)=="") throw new Exception('Le code ne peut pas être vide');
        if (trim($this->me_menu)=="") throw new Exception('Le nom ne peut pas être vide');
        if (trim($this->me_file)=="") throw new Exception('Chemin incorrect');
        if (file_exists('..'.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'ext'.DIRECTORY_SEPARATOR.$this->me_file) == false)
            throw new Exception ('Extension non trouvée, le chemin est-il correct?');
    }
    /*!@brief search a extension, the what is the column (extends_code */
    function search($p_what)
    {
		$this->me_code=strtoupper($p_what);
		if ( $this->load() == -1) return null;
		return 1;
    }
    /*!\brief return 1 if the user given in parameter can access this extension
     * otherwise returns 0
     *\param $p_login the user login
     *\return 1 has access, 0 has no access
     */
    function can_request($p_login)
    {
		$cnt=$this->cn->get_value("select count(*) from menu_ref
										join profile_menu using (me_code)
										join profile_user using (p_id)
										where
										me_code=$1
										and user_name=$2",
								array($this->me_code,$p_login));
		if ( $cnt > 0)        return 1;
		return 0;
    }
    /*!@brief make an array of the available plugin for the current user
     * @return  an array
     *@see ISelect
     */
    static function make_array($cn)
    {
        $sql="select DISTINCT me_code as value, me_menu as label from ".
             " menu_ref join profile_menu using (me_code)
				 join profile_user using (p_id) where ".
             " user_name=$1 and me_type='PL' ORDER BY ME_MENU";
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
        Extension::check_plugin_version();
    }
	function insert_plugin()
	{
		try
		{
			$this->cn->start();
			$this->verify();
			// check if duplicate
			$this->me_code = strtoupper($this->me_code);
			$count = $this->cn->get_value("select count(*) from menu_ref where me_code=$1", array($this->me_code));
			if ($count != 0)
				throw new Exception("Doublon");
			$this->me_type = 'PL';
			$this->insert();
			/**
			 * insert into default profile
			 */
			$this->cn->exec_sql("insert into profile_menu(me_code,me_code_dep,p_type_display,p_id)
					values ($1,$2,$3,$4)",array($this->me_code,'EXT','S',1));
			$this->cn->commit();
		}
		catch (Exception $exc)
		{
			echo alert($exc->getMessage());
		}
	}
	function update_plugin()
	{
		try
		{
			$this->cn->start();
			$this->verify();
			$this->me_type = 'PL';
			$this->update();
			$this->cn->commit();
		}
		catch (Exception $exc)
		{
			echo alert($exc->getMessage());
		}
	}
	function remove_plugin()
	{
		try
		{
			$this->cn->start();
			$this->delete();
			$this->cn->commit();
		}
		catch (Exception $exc)
		{
			echo alert($exc->getMessage());
		}
	}
	/**
	 *remove all the schema from the plugins
	 * @param Database $p_cn
	 */
	static function clean(Database $p_cn)
	{
		$a_ext=array("tva_belge","amortissement","impdol","coprop","importbank");
		for($i=0;$i<count($a_ext);$i++){
			if ($p_cn->exist_schema($a_ext[$i])) {
				$p_cn->exec_sql("drop schema ".$a_ext[$i]." cascade");
			}
		}
	}
        static function check_plugin_version()
        {
            global $g_user,$version_plugin;
            if ($g_user->Admin() == 1)
            {
                if (SITE_UPDATE_PLUGIN != "")
                {
                    $update = @file_get_contents(SITE_UPDATE_PLUGIN);
                    if ($update > $version_plugin)
                    {
                        echo '<div class="inner_box" style="margin-left:0px;margin-top:3px;left:3px">';
                        echo '<p class="notice">';
                        echo "Mise à jour disponible des plugin PhpCompta version actuelle : $update votre version $version_plugin";
                        echo '</p>';
                        echo '</div>';
                    }
                }
            }
        }
}

