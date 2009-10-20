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
require_once('ac_common.php');

class IPopup 
{
  var $name;			/*!< name name and id of the div */
  function __construct($p_name) 
  {
    $this->name=$p_name;
  }
  function input() {
    $r="";
    $r=sprintf('<div id="%s_fond" name="fond" class="popup_back">',$this->name);
    if ( isset($this->title) && trim($this->title) != "" ) {
      $r.=sprintf('<div name ="%s_border" id="%s_border" class="popup_border_title">',
	       $this->name,
	       $this->name);
      $r.=$this->title;
    } else {
      $r.=sprintf('<div name ="%s_border" id="%s_border" class="popup_border_notitle">',
	       $this->name,
	       $this->name);
    }
    $javascript=sprintf("javascript:hide('%s_border');hide('%s_fond')",
			$this->name,
			$this->name);
    $r.='<div style="position:absolute;top:0px;right:10px;font-weight:normal;font-size:8px;background-color:blue;color:black;text-align:right">';
    $r.=sprintf('<a style="color:white;text-decoration:none" href="%s">Fermer</a></div>',
		$javascript);
    $r.=sprintf('<div name ="%s_content" id="%s_content" class="popup_content"> %s </div></div>',
		$this->name,
		$this->name,
		$this->value);
    $r.='</div>';
    return $r;
  }
  static function test_me() {
    echo include_javascript('js/scripts.js');
    require_once('class_iselect.php');
    $select=new ISelect('a');
    $select->value=array(array ('value'=>0,'label'=>'Première valeur'),
			 array ('value'=>0,'label'=>'Première valeur'),
			 array ('value'=>0,'label'=>'Première valeur'));
    for ($e=0;$e<50;$e++){echo $select->input();if ($e%10 == 0 ) echo '<hr>';}
    $a=new IPopup('pop1');
    $a->value="";
    for ($e=0;$e<500;$e++){$a->value.="<p>Il etait une fois dans  un pays vraiment lointain où même plus loin que ça</p>";}
    echo $a->input();
echo <<<EOF
      <input type="button" onclick="hide('pop1');hide('pop1_border')" value="cacher">
      <input type="button" onclick="show('pop1');show('pop1_border')" value="montrer">
EOF;
    $a=new IPopup('pop2');
    $a->title="Retrouvez une saucisse";
    echo $a->input();
echo <<<EOF
      <input type="button" onclick="hide('pop2');hide('pop2_border')" value="cacher">
      <input type="button" onclick="show('pop2');show('pop2_border')" value="montrer">
EOF;

  }
}