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
 *  - name is the name and id of the input
 *  - extra amount of the operation to reconcile
 *  - extra2 ledger (all, ods,...)
 */
require_once('class_html_input.php');
class IConcerned extends HtmlInput
{

	public function __construct($p_name='',$p_value='')
	{
		$this->name=$p_name;
		$this->value=$p_value;
		$this->amount_id=null;
		$this->ledger='ALL';
	}
    /*!\brief show the html  input of the widget*/
    public function input($p_name=null,$p_value=null)
    {
        $this->name=($p_name==null)?$this->name:$p_name;
        $this->value=($p_value==null)?$this->value:$p_value;
        if ( $this->readOnly==true) return $this->display();



        $r=sprintf("
                   <INPUT TYPE=\"button\" onClick=search_reconcile(".dossier::id().",'%s','%s','%s') value=\"?\">
                   <INPUT TYPE=\"text\"  style=\"color:black;background:lightyellow;border:solid 1px grey;\"  NAME=\"%s\" ID=\"%s\" VALUE=\"%s\" SIZE=\"8\" readonly>
                   ",
                   $this->name,
                   $this->amount_id,
                   $this->ledger,
                   $this->name,
                   $this->name,
                   $this->value
                  );
        return $r;
    }
    /*!\brief print in html the readonly value of the widget*/
    public function display()
    {
        $r=sprintf("<span><b>%s</b></span>",$this->value);
        $r.=sprintf('<input type="hidden" name="%s" value="%s">', $this->name,$this->value);
        return $r;

    }
    static public function test_me()
    {
    }
}
