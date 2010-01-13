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
 * 
 */
require_once('class_html_input.php');
require_once('class_itext.php');
require_once('class_ibutton.php');
require_once('class_ipopup.php');
require_once('function_javascript.php');
/**
 *@brief show a button, for selecting a account and a input text for manually inserting an account
 * the different value of table are 
 * - 0 no table, it means no TD tags
 * - 1 the button and the text are separated by TD tags
 * - 2 the button and the text are in the same column (TD)
 *\note we use the set_attribute for giving parameter to search_account
 * attribute are
 *  - phpsessid
 *  - gDossier
 *  - jrn  if set there is a filter on a ledger, in  that case, contains the jrn_id (0 for no filter)
 *  - ipopup ipopup to open
 *  - account field to update with the account_number,
 *  - label  field to update  control with account_label,
 *  - bracket if true return the account_number between bracket
 *  - noquery don't start a search with the content
 *  - no_overwrite do not overwrite the existant content
 *  - query value to seek
 *\see ajax_poste.php
 *\code
require_once('class_iposte.php');
echo IPoste::ipopup('ipop_account');
// The popup 
$value->set_attribute('ipopup','ipop_account');
// the input text to update
$value->set_attribute('account','p_value');
\endcode
 */
class IPoste extends HtmlInput
{
  static function ipopup($p_name) {
    $ip=new IPopup($p_name);
    $ip->title='Plan comptable';
    $ip->value='';
    $ip->set_height('80%');
    $ip->set_zindex(20);
    return $ip->input();
  }
  /*!\brief create the javascript for adding the javascript properties
   * onto the *button*
   *\return a javascript surrounded by the tag <SCRIPT>
   */
  public function get_js_attr(){
    $attr="";
    /* Add properties at the widget */
    for ($i=0;$i< count($this->attribute);$i++){ 
      list($name,$value)=$this->attribute[$i];
      $tmp1=sprintf("$('%s_bt').%s='%s';",
		    $this->name,
		    $name,
		    $value);
      $attr.=$tmp1;
    }
    $attr=create_script($attr);
    return $attr;
  }

  public function dsp_button() {
    $ib=new IButton($this->name.'_bt');
    $ib->javascript='search_poste(this)';

    /*  add the property */
    $sc=$this->get_js_attr();
    return $ib->input().$sc;
  }
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

    /* create the text  */
    $itext=new IText($this->name,$this->value);
    $itext->size=(isset($this->size))?$this->size:10;

    /* create the button */
    $ibutton=$this->dsp_button();
    $r=$ibutton.$itext->input();
    if ( $this->table==1) $r=td($r);

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
