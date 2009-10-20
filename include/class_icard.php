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
 * \brief Input HTML for the card show buttons, in the file, you have to add JS_SEARCH
 *
 */

  /*!\brief Input HTML for the card show buttons, in the file, you have to add JS_SEARCH
   *
   * - label is the label in the button
   * - table = 1 then add the tag TD between the button and the input text
   * - if noadd is defined and set to no then you can not add a card
   * - if the this->jrn is -1 then all the card will be shown
   * - extra contents the type (all, deb or cred, a list of FD_ID between parent.  or a SQL clause
   * \see SearchCard fiche_search.php
   */
require_once('class_html_input.php');
require_once('class_ipopupsearchcard.php');
class ICard extends HtmlInput
{
  /*!\brief return a string with the HTML of the button search */
  public function dbutton() {
    if (!isset ($this->jrn) ) $this->jrn=-1;
    
    $add='yes';
    if ( isset($this->noadd) && $this->noadd=="no") $add='no';
    $r="";
    /* add properties thanks javascript */
    $docId='obj_'.$this->name;
    $javascript=sprintf("<script>var $docId=new Object();$docId.jrn='%s';$docId.add='%s';$docId.dossier='%s';$docId.type='%s'</script>",
			$this->name,
			$this->jrn,$add,dossier::id(),$this->extra);
    $r.=$javascript;
    /* create the button */
    $r.= sprintf('<INPUT TYPE="button" onClick="SearchCard(\'%s\',\'%s\',obj_%s)" value="QuickCode">',
		$_REQUEST['PHPSESSID'],
		 $this->name,
		$this->name
		);
    return $r;
  }
  /*!\brief show the html  input of the widget*/
  public function input($p_name=null,$p_value=null)
  {
    $this->name=($p_name==null)?$this->name:$p_name;
    $this->value=($p_value==null)?$this->value:$p_value;
    if ( $this->readOnly==true) return $this->display();
    $l_sessid=$_REQUEST['PHPSESSID'];
    if ( $this->javascript=="") { 
      if ( isset($this->jrn)) {
	/* if javascript is empty then we   add a default behaviour */
	$this->javascript=sprintf('onBlur="ajaxFid(\'%s\',\'%s\',\'%s\',\'%s\',\'%s\',%s)"',
				  $this->name,
				  $this->extra, //deb or cred
				  $l_sessid,
				  'js_search_only',
				  'none',
				  $this->jrn
				  );
      }else {      /* if javascript is empty then we   add a default behaviour */
	$this->javascript=sprintf('onBlur="ajaxFid(\'%s\',\'%s\',\'%s\',\'%s\',\'%s\')"',
				  $this->name,
				  $this->extra, //deb or cred
				  $l_sessid,
				  'js_search_only',
				  'none'
				  );
      }
    }
    if ( $this->extra2 == "" ) $this->extra2="QuickCode";
    if ( $this->table==1)
      {
	$r=sprintf('<TD> %s
			%s</TD><TD> <INPUT class="input_text"  TYPE="Text"  " '.
		   ' NAME="%s" ID="%s" VALUE="%s" SIZE="8" %s>',
		   $this->dbutton(),
		   $this->label,
		   $this->name,
		   $this->name,
		   $this->value,
		   $this->javascript
		   );
      }
    else
      {
	$r=sprintf('%s %s <INPUT TYPE="Text"  style="border:solid 1px blue;" '.
		   ' NAME="%s" ID="%s" VALUE="%s" SIZE="8"  %s">',
		   $this->dbutton(),
		   $this->label,
		   $this->name,
		   $this->name,
		   $this->value,
		   $this->javascript
		   );
      }
    //--    
    return $r;
    
  }
  /*!\brief Two forms can not be mixed so the div for the input must be displayed after the last form
   *\param none
   *\return HTML String
   *\note
   *\see
   *\todo
   */
  function formPopup() {
    $this->ipopup=new IPopupSearchCard($this->name);
    return $this->ipopup->input();
  }

  /*!\brief print in html the readonly value of the widget*/
  public function display()
  {
    $r=sprintf('         <INPUT TYPE="hidden" NAME="%s" VALUE="%s" SIZE="8">',
	       $this->name,
	       $this->value 
	       );
    return $r;

  }
  static public function test_me()
  {
    $a=new ICard('testme');
    if ( isset($_REQUEST['test_select']) )
	 echo HtmlInput::hidden('test_select',$_REQUEST['test_select']);
    echo $a->input();
  }
}
