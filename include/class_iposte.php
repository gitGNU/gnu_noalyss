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
class IPoste extends HtmlInput
{
  /*!\brief show the html  input of the widget*/
  public function input($p_name=null,$p_value=null)
  {
    $this->name=($p_name==null)?$this->name:$p_name;
    $this->value=($p_value==null)?$this->value:$p_value;
    if ( $this->readOnly==true) return $this->display();
    //--
    if ( ! isset($this->ctrl) ) $this->ctrl='none';
    $l_sessid=$_REQUEST['PHPSESSID'];
    if ( ! isset($this->javascript)) $this->javascript="";

    // Do we need to filter ??
    if ( $this->extra2 == null ) {
      $r=sprintf('<TD>
         <INPUT class="inp" TYPE="button" onClick=SearchPoste(\'%s\','.dossier::id().',\'%s\',\'%s\',\'label\',\'Y\') %s value="Poste?">
            %s</TD><TD> 

             <INPUT style="border:groove 1px blue;"  TYPE="Text" NAME="%s" ID="%s" VALUE="%s" SIZE="8">
                 </TD>',
		 $l_sessid,
		 $this->name,
		 $this->extra,
		 $this->ctrl,
		 $this->label,
		 $this->name,
		 $this->name,
		 $this->value 
		 );
	  
    } else { // $p_list is not null, so we have a filter
      $r=sprintf('<TD>
         <INPUT TYPE="button" onClick="SearchPosteFilter(\'%s\','.dossier::id().',\'%s\',\'%s\',\'%s\',\'%s\')" %s value="Poste?">
            %s</TD><TD> 

             <INPUT style="border:groove 1px blue;" TYPE="Text" NAME="%s" id="%s" VALUE="%s" SIZE="8">
                 </TD>',
		 $l_sessid,
		 $this->name,
		 $this->extra2,
		 $this->extra,
		 $this->ctrl,
		 $this->javascript,
		 $this->label,
		 $this->name,
		 $this->name,
		 $this->value 
		 );
	  
    } //else


    if  ( $this->table==0) {
      $r=str_replace ('<TD>','',$r);
      $r=str_replace ('</TD>','',$r);
    }
    return $r;


    //--

  }
  /*!\brief print in html the readonly value of the widget*/
  public function display()
  {
    $r=sprintf('<TD><input type="hidden" name="%s" value="%s">
                  %s

                 </TD>',
	       $this->name, 
	       $this->value ,
	       $this->value 
	       );

    return $r;

  }
  static public function test_me()
  {

  }
}
