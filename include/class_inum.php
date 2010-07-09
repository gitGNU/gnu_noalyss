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
 * \brief for the numeric input text field
 */
require_once('class_itext.php');
/*!\brief 
 * This class handles only the numeric input, the input will
 * call a javascript
 * to change comma to period  and will round it (2 decimal)
 *
 */
class INum extends IText
{
  function __construct($name='',$value='') {
    parent::__construct($name,$value);
    $this->javascript='onchange="format_number(this);"';
    $this->size=9;
    $this->style='style="text-align:right;border:1px solid blue;margin:2px"';
  } 
  /*!\brief print in html the readonly value of the widget*/
  public function display()
  {
    $this->size=9;
    $readonly=" readonly ";$style='style="border:solid 1px blue;color:black;background:#EDEDED;text-align:right"';
    $this->value=str_replace('"','',$this->value);
    $r='<INPUT '.$style.' TYPE="TEXT" id="'.
      $this->name.'"'.
      'NAME="'.$this->name.'" VALUE="'.$this->value.'"  '.
      'SIZE="'.$this->size.'" '.$this->javascript." $readonly $this->extra >";
    
    /* add tag for column if inside a table */
    if ( $this->table == 1 )		  $r='<td>'.$r.'</td>';
    
    return $r;
    
  }
  
}


