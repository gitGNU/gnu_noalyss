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
    function __construct($p_cn,$id=-1)
    {
        $this->cn=$p_cn;
        $this->data=new Tag_SQL($p_cn,$id);
    }
    /**
     * Show the list of available tag
     * @return HTML
     */
    function show_list()
    {
        $ret=$this->data->seek(' order by t_tag');
        if ( $this->cn->count($ret) == 0) return "";
        require_once 'template/tag_list.php';
    }
    /**
     * let select a tag to add
     */
    function select()
    {
        $ret=$this->data->seek(' order by t_tag');
        require_once 'template/tag_select.php';
    }
    /**
     * Display a inner window with the detail of a tag
     */
    function form_add()
    {
        $data=$this->data;
        require_once 'template/tag_detail.php';
    }
    /**
     * Show the tag you can add to a document
     */
    function show_form_add()
    {
        echo '<h2>'.' Ajout d\'un dossier (ou  tag)'.'</h2>';
       
        $this->form_add();
    }
    function save($p_array)
    {
        if ( trim($p_array['t_tag'])=="" ) return ;
        $this->data->t_id=$p_array['t_id'];
        $this->data->t_tag=  strip_tags($p_array['t_tag']);
        $this->data->t_description=strip_tags($p_array['t_description']);
        $this->data->save();
    }
    function remove($p_array)
    {
        $this->data->t_id=$p_array['t_id'];
        $this->data->delete();
    }
    /**
     * Show a button to select tag for Search
     * @return HTML
     */
    static  function button_search()
    {
        $r="";
        $r.=HtmlInput::button("choose_tag", "Etiquette", 'onclick="search_display_tag('.Dossier::id().')"', "smallbutton");
        return $r;
    }
    /**
     * let select a tag to add to the search
     */
    function select_search()
    {
        $ret=$this->data->seek(' order by t_tag');
        require_once 'template/tag_search_select.php';
    }
    /**
     * In the screen search add this data to the cell
     */
    function update_search_cell() {
        echo '<span id="sp_'.$this->data->t_id.'" style="border:1px solid black;margin-right:5px;">';
        echo h($this->data->t_tag);
        echo HtmlInput::hidden('tag[]', $this->data->t_id);
        $js=sprintf("$('sp_".$this->data->t_id."').remove()='';");
        echo '<span style="background-color:red;text-align:center;border-top:1px solid black; border-right:1px solid black;border-bottom:1px solid black;">';
        echo HtmlInput::anchor('X', "javascript:void(0)", "onclick=\"$js\"");
        echo '</span>';
        echo '</span>';
    }
    /**
     * clear the search cell
     */
    static function add_clear_button() {
        $clear=HtmlInput::button('clear', 'X', 'onclick="search_clear_tag('.Dossier::id().');"', 'smallbutton');
        return $clear;
    }
}

?>
