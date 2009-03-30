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
require_once('class_html_input.php');
 class IText extends HtmlInput
{
	/*!\brief show the html  input of the widget*/
	public function input($p_name=null,$p_value=null)
 	{
		$this->name=($p_name==null)?$this->name:$p_name;
		$this->value=($p_value==null)?$this->value:$p_value;
		if ( $this->readOnly==true) return $this->display();

		$t= ((isset($this->title)))?'title="'.$this->title.'"   ':' ';

		$extra=(isset($this->extra))?$this->extra:"";

		$style='class="input_text"';

		$this->value=str_replace('"','',$this->value);
		$r='<INPUT '.$style.' TYPE="TEXT" id="'.
		$this->name.'"'.$t.
		'NAME="'.$this->name.'" VALUE="'.$this->value.'"  '.
		'SIZE="'.$this->size.'" '.$this->javascript."  $this->extra >";
		/* add tag for column if inside a table */
		if ( $this->table == 1 )		  $r='<td>'.$r.'</td>';

		return $r;

	}
	/*!\brief print in html the readonly value of the widget*/
	public function display()
 	{
		$t= ((isset($this->title)))?'title="'.$this->title.'"   ':' ';

		$extra=(isset($this->extra))?$this->extra:"";

		$readonly=" readonly ";$style='style="border:solid 1px grey;color:black;background:lightblue;"';
		$this->value=str_replace('"','',$this->value);
		$r='<INPUT '.$style.' TYPE="TEXT" id="'.
		$this->name.'"'.$t.
		'NAME="'.$this->name.'" VALUE="'.$this->value.'"  '.
		'SIZE="'.$this->size.'" '.$this->javascript." $readonly $this->extra >";

	/* add tag for column if inside a table */
		if ( $this->table == 1 )		  $r='<td>'.$r.'</td>';

		return $r;

	}
	static public function test_me()
 	{

	}
}
