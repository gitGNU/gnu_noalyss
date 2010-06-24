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
 * \brief create a popup in html above the current layer
 * the html inside the popup cannot contain any floating elt as div..
 *
 */
require_once('function_javascript.php');
require_once('class_html_input.php');
/**
 *@brief create a DIV and call an ajax function
\verbatim

exemple d'utilisation

$ibox=new ibox('id')
$ibox->html='loading';
$ibox->callback='ajax.php'; // uniquement fichier PHP (1)
$ibox->queryString='?gDossier=15&id=5';
$ibox->style='style_css',
$ibox->cssclass='width:80%';
$ibox->label='Cliquez moi';
$ibox->onclick='add_div()'; // if you click
$ibox->ajax_success='autre'; // fonction qui affiche traite le callback, par défault, il s'agit de la fonction refresh_box qui afficher seulement  le contenu dans le div
$ibox->ajax_error='ajax_error' // fonction pour traiter les erreurs ajax
\endverbatim
*/

class IBox extends HtmlInput
{
  var $name;			/*!< name name and id of the div */
  function __construct($p_name,$label)
  {
    $this->name=$p_name;
    $this->parameter='';
    $this->attribute=array();
    $this->drag=false;
    $this->blocking=true;
    $this->queryString='';
    $this->html='<img src="image/loading.gif">';
    $this->callback='';
    $this->label=$label;
    $this->handle_callback='refresh_box';
    $this->cssclass='';
    $this->style='';
    $this->ajax_success='success_box';
    $this->ajax_error='error_box';

  }
  /*!\brief set the attribute thanks javascript as the width, the position ...
   *\param $p_name attribute name valid value are id, cssclass and html
   *\param $p_val val of the attribute
   *\note add to  the this->attribut, it will be used in input()
   */
  /*  function set_attribute($p_name,$p_val) {
    $this->attribute[]=array($p_name,$p_val);
    }*/
  function set_dragguable($p_value){
  	$this->drag=$p_value;
  }

  function input() {
    $r="";
    $this->set_attribute('id',$this->name);
    $this->set_attribute('html',$this->html);
    $this->set_attribute('cssclass',$this->cssclass);
    $this->set_attribute('style',$this->style);
    $this->set_attribute('js_success',$this->ajax_success);
    $this->set_attribute('js_error',$this->ajax_error);
    $this->set_attribute('qs',$this->queryString);
    $this->set_attribute('callback',$this->callback);
    $this->set_attribute('drag',$this->drag);
    $obj=$this->make_object();
    $obj=str_replace('"',"&quot;",$obj);
    $r='<a class="button" href="#" onclick="show_box(eval('.$obj.'))">'.$this->label.'</a>';

    return $r;
  }

  static function test_me() {
    echo js_include('prototype.js');
    echo js_include('scriptaculous.js');
    echo js_include('effects.js');
    echo js_include('dragdrop.js');
    echo js_include('scripts.js');

    // Simple box no ajax
    $simple=new IBox('alert1','click-moi');
    $simple->html="Attention !!!";    
    $simple->style="background:red;border:1px solid rose;width:200;height:50px;";
    $simple->drag=false; 
    echo $simple->input();

    // Dragguable
    echo '<div id="drag_content"></div>';
    $drag=new IBox('drag','drag');
    $drag->cssclass="popup_border_title";
    $drag->html=" Drag me ";
    $drag->drag=true;
    echo $drag->input();
    // with ajax
    $ajax=new IBox('ajax','ajax');
    $ajax->cssclass="popup_content";
    $ajax->style="width:1;left:1";
    $ajax->queryString="?gDossier=48&op=sf&c=av_text5&q=58&ctl=drag";
    $ajax->callback="ajax_poste.php";
    echo $ajax->input();

  }
}
