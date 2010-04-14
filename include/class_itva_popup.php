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
require_once ('class_ipopup.php');
require_once('class_ibutton.php');

 class ITva_Popup extends HtmlInput
{
  public function __construct($p_name=null) {
    $this->name=$p_name;
  }

  /*!\brief show the html  input of the widget*/
  public function input($p_name=null,$p_value=null)
  {
    $this->name=($p_name==null)?$this->name:$p_name;
    $this->value=($p_value==null)?$this->value:$p_value;
    $this->js=(isset($p_js))?$this->js:"";
    if ( $this->readOnly==true) return $this->display();

    $str='<input type="TEXT" name="%s" value="%s" id="%s" size="3" "%s">';
    $r=sprintf($str,$this->name,$this->value,$this->name,$this->js);

    return $r;

  }
  /**
   *@brief show a button, if it is pushed show a popup to select the need vat
   *@note
   * - a ipopup must be created before with the name popup_tva
   * - the javascript scripts.js must be loaded 
   *@return string with html code
   */
  function dbutton() {
    if( trim($this->name)=='') throw new Exception (_('Le nom ne peut être vide'));
    // button
    $bt=new IButton('bt_'.$this->name);
    $bt->label=_('tva');
    $bt->set_attribute('gDossier',dossier::id());
    $bt->set_attribute('ctl',$this->name);
    $bt->set_attribute('popup','popup_tva');
    $bt->javascript='popup_select_tva(this)';
    return $bt->input();
  }
  /*!\brief print in html the readonly value of the widget*/
  public function display()
  {
    $r='<input text name="%s" value="%s" id="%s" disabled>';
    $res=sprinf($r,$this->name,$this->value,$this->name);
    return $res;
  }
  static public function test_me()
  {
    $a=new IPopup('popup_tva');
    $a->set_title('Choix de la tva');
    echo $a->input();
    $tva=new ITva_Popup("tva1");
    echo $tva->input();
    echo $tva->dbutton();
  }
}
