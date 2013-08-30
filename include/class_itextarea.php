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
 * \brief Html Input
 */
require_once('class_html_input.php');
class ITextarea extends HtmlInput
{
    /*!\brief show the html  input of the widget*/
    public function input($p_name=null,$p_value=null)
    {
        $this->name=($p_name==null)?$this->name:$p_name;
        $this->value=($p_value==null)?$this->value:$p_value;
	if ( !isset ($this->style )) $this->style=' class="itextarea" ';
	$this->id=($this->id=="")?$this->name:$this->id;

        if ( $this->readOnly==true) return $this->display();

        $r="";
        $r.='<TEXTAREA '.$this->style.'  name="'.$this->name.'" id="'.$this->id.'"';
        /*$r.=" rows=\"$this->heigh\" ";
        $r.=" cols=\"$this->width\" "; */
        $r.='>';
        $r.=$this->value;

        $r.="</TEXTAREA>";
        return $r;
    }


    /*!\brief print in html the readonly value of the widget*/
    public function display()
    {
        $r='<p>';
        $r.=h($this->value);
        $r.=sprintf('<input type="hidden" name="%s" value="%s">',
                    $this->name,h($this->value));
        $r.='</p>';

    }
    static public function test_me()
    {
    }
}
