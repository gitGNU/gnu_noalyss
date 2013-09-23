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
require_once 'class_tag_sql.php';

class Tag
{
    function __construct($p_cn)
    {
        $this->cn=$p_cn;
        $this->data=new Tag_SQL($p_cn);
    }
    function show_list()
    {
        $ret=$this->data->seek(' order by t_tag');
        if ( $this->cn->count($ret) == 0) return "";
        require_once 'template/tag_list.php';
    }
    function select()
    {
        $ret=$this->data->seek(' order by t_tag');
        if ( $this->cn->count($ret) == 0) return "aucun tag trouvé";
        require_once 'template/tag_select.php';
    }
    function form_add()
    {
        $data=$this->data;
        require_once 'template/tag_detail.php';
    }
    function show_form_add()
    {
        echo '<h2>'.' Ajout d\'un dossier (ou  tag)'.'</h2>';
       
        $this->form_add();
    }
    function save($p_array)
    {
        $this->data->t_id=$p_array['t_id'];
        $this->data->t_tag=str_ireplace('<script>','<_script_>',$p_array['t_tag']);
        $this->data->t_description=str_ireplace('<script>','<_script_>',$p_array['t_description']);
        $this->data->save();
    }
}

?>
