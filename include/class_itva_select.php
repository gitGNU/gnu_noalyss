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
/* $Revision: 1615 $ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/*!\file
 * \brief Html Input
 */
require_once('class_iselect.php');
class ITva_Select extends ISelect
{
    public function __construct($p_cn)
    {
        $this->db=$p_cn;
    }

    /*!\brief show the html  input of the widget*/
    public function input($p_name=null,$p_value=null)
    {
        $this->name=($p_name==null)?$this->name:$p_name;
        $this->value=($p_value==null)?$this->value:$p_value;
        if ( $this->readOnly==true) return $this->display();

        $select_tva=$this->db->make_array("select tva_id,tva_label from tva_rate order by tva_label asc",0);
        $this->value=$select_tva;
        $a="<SELECT  id=\"$this->name\" NAME=\"$this->name\" $this->javascript >";

        if (empty($this->value)) return '';
        for ( $i=0;$i<sizeof($this->value);$i++)
        {
            $checked=($this->selected==$this->value[$i]['value'])?"SELECTED":"";
            $a.='<OPTION VALUE="'.$this->value[$i]['value'].'" '.$checked.'>';
            $a.=$this->value[$i]['label'];
        }
        $a.="</SELECT>";
        if ( $this->table == 1 )		  $a='<td>'.$a.'</td>';

        return $a;

    }
    /*!\brief print in html the readonly value of the widget*/
    public function display()
    {
        $select_tva=$this->db->make_array("select tva_id,tva_label from tva_rate order by tva_label asc",0);
        $this->value=$select_tva;
        $r=parent::display();
        return $r;
    }
    static public function test_me()
    {
    }
}
