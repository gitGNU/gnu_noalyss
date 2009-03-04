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

/* $Revision: 2350 $ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/*! \file
 * \brief This class is used to create all the HTML INPUT TYPE
 */

/*!
 * \brief class widget This class is used to create all the HTML INPUT TYPE
 *        and some specials which works with javascript like 
 *        js_search.
 *
 * special value 
 *    js_search and js_search_only :you need to add a span widget the name
 *    of the js_* widget + '_label' , the member extra contains cred,deb to 
 *    filter the search of cred of deb of a jrn or contains a string with 
 *    a list of frd_id.
 *    Possible type 
 *    $type 
 *      - TEXT 
 *      - HIDDEN
 *      - BUTTON in this->js you have the javascript code
 *      - SELECT the options are passed via this->value, this array is
 *        build thanks the make_array function, each array (of the
 *        array) aka row must contains a field value and a field label
 *      - PASSWORD
 *      - CHECKBOX
 *      - RADIO
 *      - TEXTAREA
 *      - RICHTEXT
 *      - FILE
 *      - JS_SEARCH_POSTE  call a popup window for searching the account
 *      - JS_SEARCH call a popup window for searching a quickcode or to add one
 *      - JS_SEARCH_ONLY like JS_SEARCH but without adding a quickcode
 *      - JS_SEARCH_CARD_CTRL like js_search_only but the tag to update is given
 *      - SPAN
 *      - JS_TVA        open a popup window for the VAT
 *      - JS_CONCERNED  open a popup window for search a operation, if extra == 0 then
 *                      get the amount thx javascript
 *      - js_DATE show a calendar
 *
 *    For JS_SEARCH_POST,JS_SEARCH or JS_SEARCH_ONLY
 *     - $extra contains 'cred', 'deb', 'all' or a list of fiche_def_ref (frd_id) 
 *           to filter the search/add for the card
 *        
 *     - $extra2 filter on the card parameter, which are given in Avance->journaux menu, 
 *            it is the journal id. If empty, there is no link with a ledger
 *
 */
class HtmlInput {

  var $type;                      /*!<  $type type of the widget */
  var $name;                      /*!<  $name field NAME of the INPUT */    
  var $value;                     /*!<  $value what the INPUT contains */
  var $readOnly;                  /*!<  $readonly true : we cannot change value */
  var $size;                      /*!<  $size size of the input */
  var $selected;                  /*!<  $selected for SELECT RADIO and CHECKBOX the selected value */
  var $table;                     /*!<  $table =1 add the table tag */
  var $label;                     /*!<  $label the question before the input */
  var $disabled;                  /*!<  $disabled poss. value == true or nothing, to disable INPUT*/
  var $extra;                     /*!<  $extra different usage, it depends of the $type */
  var $extra2;                    /*!<  $extra2 different usage,
									it depends of the $type */
  var $javascript;				   /*!< $javascript  is the javascript to add to the widget */
  var $ctrl;						/*!<$ctrl is the control to update (see js_search_card_control) */

  var $tabindex; 
  function __construct($p_name="",$p_value="") {
	$this->name=$p_name;
    $this->readOnly=false;
    $this->size=20;
    $this->width=50;
    $this->heigh=20;
    $this->value=$p_value;
    $this->selected="";
    $this->table=0;
    $this->disabled=false;
    $this->javascript="";
	$this->extra2="all";
  }
  function setReadOnly($p_read) {
    $this->readonly=$p_read;
  }
  /*!  function input($p_name,$p_value="") 
   *****************************************************
   * \brief  create the corresponding INPUT tag 
   *        
   *  \param $p_name is the INPUT NAME
   * \param         $p_value is the INPUT VALUE or an array for select
   *  \return  string containing the tag
   */
  function input($p_name=null,$p_value=null) {
    
    if ( $p_name != null)
      $this->name=$p_name;
    $this->value=($p_value===null)?$this->value:$p_value;

    
    $disabled = $this->disabled ? "readonly" : "";


   




    //#############################################################################
    // Rich Text
    /*!\brief
     * \note for the type RICHTEXT we need to include the javascript file
     *       into the head \see commercial.php html_page_start($_SESSION['g_theme'],"","richtext.js");
     */

    if ( strtoupper($this->type)=='RICHTEXT')
      {
	    $r= ' <script language="JavaScript" type="text/javascript"> '.
	      '<!-- '."\n".
	      "\nfunction submitForm() {\n".
	      " updateRTE('".$this->name."');\n ".
	      " return true; \n".
	      "} \n".
	      'initRTE("image/", "", "");'."\n".
	      '//-->'."\n".
	      '</script>'.
	      '<noscript><p><b>Javascript must be enabled to use this form.</b></p></noscript>'.
	      'Note Interne : '.
	      '<script language="JavaScript" type="text/javascript">'."\n".
	      '<!--'."\n";
	      //Usage: writeRichText(fieldname, html, width, height, buttons, readOnly)

	    echo_debug('class_widget',__LINE__,'to write is '.$this->name);
	    /*\! brief 
	     *\note the value must be urlencoded
	     */
	    //  Removing new line
	    
	    //	    $msg=urlencode($this->value);
	    $msg=$this->value;
	    $msg=str_replace("%OA","",$msg);
	    $msg=str_replace("%OD","",$msg);
	    $msg=str_replace("\n","",$msg);
	    $msg=str_replace("\r","",$msg);

	    $read=($this->readonly==false)?"false":"true";	    


	    $r.=sprintf(" writeRichText('%s','%s',%d,%d,true,%s);\n",
			$this->name,
			$msg,
			$this->width,
			$this->heigh, 
			$read);
	    $r.= "\n//-->".
	      "</script>";
	    echo_debug ('class_widget',__LINE__,"writeRichText '$r'");

	    return $r;
	  
      }
      
    //#############################################################################
    // js_search_poste_only
    if ( strtolower($this->type == 'js_search_poste_only')) {

      if ( ! isset($this->ctrl) ) $this->ctrl='none';
      if ( ! isset($this->extra2)) $this->extra2='poste';
      $r=sprintf('
         <INPUT class="inp" TYPE="button" onClick=SearchPoste(\'%s\','.dossier::id().',\'%s\',0,\'%s\',\'N\',\'%s\') value="Poste?">
            %s
                 ',
		 $_REQUEST['PHPSESSID'],
		 $this->extra,
		 $this->extra2,
		 $this->ctrl,
		 $this->label
		 );
      return $r;
      }
    //#############################################################################
    // input type == js_search_poste => button search for the account
    if ( strtolower($this->type)=="js_search_poste") {
      if ( ! isset($this->ctrl) ) $this->ctrl='none';
      $l_sessid=$_REQUEST['PHPSESSID'];
      if ( $this->readonly == false ) {
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
      } else {
      $r=sprintf('<TD><input type="hidden" name="%s" value="%s">
                  %s

                 </TD>',
		 $this->name, 
		 $this->value ,
		 $this->value 
		 );

      } //else if readonly == true
      if  ( $this->table==0) {
	$r=str_replace ('<TD>','',$r);
	$r=str_replace ('</TD>','',$r);
      }
      return $r;

    } // end js_search_poste
//#############################################################################
  // input type == js_search => button search for card
  if ( strtolower($this->type)=="js_search") {
 
  }// poste==js_search

//#############################################################################
  // input type == js_search => button search for card
  /*!\brief js_search_only only for searching a card no new button
   */
  if ( strtolower($this->type)=="js_search_only") {
    $l_sessid=$_REQUEST['PHPSESSID'];

    if ( $this->javascript=="") { /* if javascript is empty then we
				     add a default behaviour */
      $this->javascript=sprintf('onBlur="ajaxFid(\'%s\',\'%s\',\'%s\',\'%s\',\'%s\')"',
				$this->name,
				$this->extra, //deb or cred
				$l_sessid,
				'js_search_only',
				'none'
				);
    }
      if  ( $this->readonly == false ) {
      if ( $this->extra2 == "" ) $this->extra2="QuickCode";
      if ( $this->table==1)
	{
	  $r=sprintf('<TD>
         <INPUT TYPE="button" onClick="SearchCard(\'%s\',\'%s\',\'%s\')" value="%s">
            %s</TD><TD> <INPUT class="input_text"  TYPE="Text"  " '.
		     ' NAME="%s" ID="%s" VALUE="%s" SIZE="8" %s>',
		     $l_sessid,
		     $this->extra,
		     $this->name,
		     $this->extra2,
		     $this->label,
		     $this->name,
		     $this->name,
		     $this->value,
		     $this->javascript
		     );
	}
      else
	{
	  $r=sprintf('
         <INPUT TYPE="button" onClick="SearchCard(\'%s\',\'%s\',\'%s\')" value="%s">
            %s <INPUT TYPE="Text"  style="border:solid 1px blue;" '.
		     ' NAME="%s" ID="%s" VALUE="%s" SIZE="8"  %s">',
		     $l_sessid,
		     $this->extra,
		     $this->name,
		     $this->extra2,
		     $this->label,
		     $this->name,
		     $this->name,
		     $this->value,
		     $this->javascript
	       );
	}
    } else {
      // readonly == true
      if ( $this->table == 1 )
	{
	
	  $r=sprintf('<TD>            %s</TD>
                 <TD> %s
                 <INPUT TYPE="hidden" NAME="%s" VALUE="%s" SIZE="8">
                 ',
	       $this->label,
		     $this->value, 
	       $this->name,
		     $this->value 
		     );
	}
      else {
	// readonly == true and table == 0
	$r=sprintf('%s',
	       $this->value
	       );

      }

    }
    return $r;
  }// poste==js_search_only
  if ( strtolower($this->type)=="js_search_noadd") {
    $l_sessid=$_REQUEST['PHPSESSID'];

    if ( $this->javascript=="") { /* if javascript is empty then we
				     add a default behaviour */
      $this->javascript=sprintf('onBlur="ajaxFid(\'%s\',\'%s\',\'%s\',\'%s\',\'%s\')"',
				$this->name,
				$this->extra, //deb or cred
				$l_sessid,
				'js_search_only',
				'none'
				);
    }
      if ( $this->extra2 == "" ) $this->extra2="QuickCode";
	{
	  $r=sprintf('<TD>
         <INPUT TYPE="button" onClick="SearchCard(\'%s\',\'%s\',\'%s\',1)" value="%s">
            %s</TD><TD> <INPUT class="input_text"  TYPE="Text"  " '.
		     ' NAME="%s" ID="%s" VALUE="%s" SIZE="8" %s>',
		     $l_sessid,
		     $this->extra,
		     $this->name,
		     $this->extra2,
		     $this->label,
		     $this->name,
		     $this->name,
		     $this->value,
		     $this->javascript
		     );
	}

    return $r;
  }// poste==js_search_noadd

//#############################################################################
  // input type == js_search => button search for card
  /*!\brief js_search_only only for searching a card  when it is needed to update a other control
  * \todo to finish + ajax
   */
  if ( strtolower($this->type)=="js_search_card_control") {
    $l_sessid=$_REQUEST['PHPSESSID'];

    if ( $this->javascript=="") { /* if javascript is empty then we
				     add a default behaviour */
      $this->javascript=sprintf('onBlur="ajaxFid(\'%s\',\'%s\',\'%s\',\'%s\',\'%s\')"',
				$this->name,
				$this->extra, //deb or cred
				$l_sessid,
				"searchcardControl",
				$this->ctrl
				);
    }
      if  ( $this->readonly == false ) {
	if ( $this->extra2 == "" ) $this->extra2="QuickCode";
	$r=sprintf('<table><tr><TD>
         <INPUT TYPE="button" onClick="searchCardCtrl(\'%s\',\'%s\',\'%s\',\'%s\')" value="%s">
            %s</TD><TD> <INPUT class="input_text"  TYPE="Text"  " '.
		     ' NAME="%s" ID="%s" VALUE="%s" SIZE="8" %s></tr></table>',
		   $l_sessid,
		   $this->extra,
		   $this->name,
		   $this->ctrl,
		   $this->extra2,
		   $this->label,
		   $this->name,
		   $this->name,
		   $this->value,
		   $this->javascript
		   );
      } else {
	// readonly == true and table == 0
	$r=sprintf('%s', $this->value  );
      }
      return $r;	
  }

 //#####################################################################
  // JS_BUD_SEARCH_POSTE
  //#####################################################################
  if ( strtolower($this->type)=="js_bud_search_poste") {
	if ( $this->readonly==true) return "";
	$dis= ( $this->disabled==true) ? "disabled":"";
	  
	$this->javascript="bud_search_poste('".$_REQUEST['PHPSESSID']."',".dossier::id().",'$this->name')";
	$r='<input type="BUTTON" value="?"'.$dis.
	  ' onClick="'.$this->javascript.'" style="display:line">';
	$r.='<input type="text" readonly id="'.$this->name.'" name="'.
	  $this->name.'" value ="'.$this->value.'" size="35">';

	$r.='<input type="hidden" name="'.$this->name.'_hidden" id="'.$this->name.'_hidden" value="'.$this->extra.'">';
	return $r;
  }

  } //end function
  //#####################################################################
  /* Debug
   */
  function debug() {
    echo "Type ".$this->type."<br>";
    echo "name ".$this->name."<br>";
    echo "value". $this->value."<br>";
    $readonly=($this->readonly==false)?"false":"true";
    echo "read only".$readonly."<br>";
  }
  static   function submit ($p_name,$p_value,$p_javascript="") {
    
    return '<INPUT TYPE="SUBMIT" NAME="'.$p_name.'" VALUE="'.$p_value.'" '.$p_javascript.'>';
  }
  static   function button ($p_name,$p_value,$p_javascript="") {
    
    return '<INPUT TYPE="button" NAME="'.$p_name.'" ID="'.$p_name.'" VALUE="'.$p_value.'" '.$p_javascript.'>';
  }

  static function reset ($p_value) {
    return '<INPUT TYPE="RESET"  VALUE="'.$p_value.'">';
  }
  static function hidden($p_name,$p_value) {
    return '<INPUT TYPE="hidden" id="'.$p_name.'" NAME="'.$p_name.'" VALUE="'.$p_value.'">';
  }
  static function button_href($p_name,$p_value) {
    $str='&PHPSESSID='.$_REQUEST['PHPSESSID'];
//    $p_value.=$str;
	return sprintf('<button onClick="window.location=\'%s\'">%s</button>',
				   $p_value,
				   $p_name);

  }
  static function infobulle($p_comment){
    $r='<A HREF="#" style="display:inline;color:black;background-color:yellow" onClick="showBulle(\''.$p_comment.'\')" onmouseout="hideBulle(0)">?</A>';
    return $r;
  }

}
