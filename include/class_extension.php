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
	/**
	 * @todo to be rewritten
	 */
    public function verify()
    {
        // Verify that the elt we want to add is correct
        if (trim($this->me_code)=="") throw new Exception('Le code ne peut pas être vide');
        if (trim($this->me_name)=="") throw new Exception('Le nom ne peut pas être vide');
        if (trim($this->me_file)=="") throw new Exception('Chemin incorrect');
        if (file_exists('..'.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'ext'.DIRECTORY_SEPARATOR.$this->me_file) == false)
            throw new Exception ('Extension non trouvée, le chemin est-il correct?');
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
        $this->me_menu=$p_array['name'];
        $this->me_code=$p_array['code'];
        $this->me_desc=$p_array['desc'];
        $this->me_file=$p_array['file'];
		$this->me_type='PL';
    }
    /*!@brief search a extension, the what is the column (extends_code */
    function search($p_what)
    {
		$this->me_code=$p_what;
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
        $sql="select me_code as value, me_menu as label from ".
             " menu_ref join profile_menu using (me_code)
				 join profile_user using (p_id) where ".
             " user_name=$1 and me_type='PL' and me_code_dep='EXTENSION'";
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
}

