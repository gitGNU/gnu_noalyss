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

class IBox extends HtmlInput
{
  var $name;			/*!< name name and id of the div */
  function __construct($p_name)
  {
    $this->name=$p_name;
    $this->parameter='';
    $this->attribute=array();
    $this->drag=false;
    $this->blocking=true;
  }
  /*!\brief set the attribute thanks javascript as the width, the position ...
   *\param $p_name attribute name valid value are id, cssclass and html
   *\param $p_val val of the attribute
   *\note add to  the this->attribut, it will be used in input()
   */
  function set_attribute($p_name,$p_val) {
    $this->attribute[]=array($p_name,$p_val);
  }
  function set_dragguable($p_value){
  	$this->drag=$p_value;
  }

  function input() {
    $r="";
    $object=$this->make_object();
    $r.=sprintf(" add_div (%s)",$object);
    $result=create_script($r);
    if ($this->drag==true){
    /* add draggable possibility */
      $r.=sprintf("  new Draggable('%s',{starteffect:function(){
      new Effect.Highlight('%s',{scroll:window,queue:'end'});  } });"
		       ,$this->name
		       ,$this->name);

    }
    return $resultxs;
  }

  static function test_me() {
    echo js_include('js/scripts.js');

  }
}
