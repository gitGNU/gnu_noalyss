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

/*! \file
 * \brief This class is used to create all the HTML INPUT TYPE
 */

/*!
 * \brief class widget This class is used to create all the HTML INPUT TYPE
 *        and some specials which works with javascript like 
 *        js_search.
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
class widget {

  var $type;                      /*!<  $type type of the widget */
  var $name;                      /*!<  $name field NAME of the INPUT */    
  var $value;                     /*!<  $value what the INPUT contains */
  var $readonly;                  /*!<  $readonly true : we cannot change value */
  var $size;                      /*!<  $size size of the input */
  var $selected;                  /*!<  $selected for SELECT RADIO and CHECKBOX the selected value */
  var $table;                     /*!<  $table =1 add the table tag */
  var $label;                     /*!<  $label the question before the input */
  var $disabled;                  /*!<  $disabled poss. value == true or nothing, to disable INPUT*/
  var $extra;                     /*!<  $extra different usage, it depends of the $type */
  var $extra2;                    /*!<  $extra2 different usage,
									it depends of the $type */
  var $javascript;

  var $tabindex; 
  function widget($p_type="",$p_label="",$p_name="",$p_value="") {
    $this->type=$p_type;
	$this->name=$p_name;
    $this->readonly=false;
    $this->size=20;
    $this->width=50;
    $this->heigh=20;
    $this->value=$p_value;
    $this->selected="";
    $this->table=0;
    $this->label=$p_label;
    $this->disabled=false;
    $this->javascript="";
	$this->extra2="all";
  }
  function SetReadOnly($p_read) {
    $this->readonly=$p_read;
  }
  /*!  function IOValue($p_name,$p_value="",$p_label="") 
   *****************************************************
   * \brief  create the corresponding INPUT tag 
   *        
   *  \param $p_name is the INPUT NAME
   * \param         $p_value is the INPUT VALUE or an array for select
   * \param         $p_label is the label of the INPUT
   *  \return  string containing the tag
   */
  function IOValue($p_name=null,$p_value=null,$p_label="") {
    
    if ( $p_name != null)
      $this->name=$p_name;
    $this->value=($p_value===null)?$this->value:$p_value;
    $this->label=($p_label == "")?$this->label:$p_label;

    // Input text type
    $disabled = $this->disabled ? "readonly" : "";
    if (strtoupper($this->type)=="TEXT") {
      $extra=(isset($this->extra))?$this->extra:"";
      if ( $this->readonly==true ){
	$readonly=" readonly ";$style='style="border:solid 1px grey;color:black;background:lightblue;"';
      } else {
	$readonly="  ";$style='style="border:solid 1px blue;"';
      }
	$this->value=str_replace('"','',$this->value);
	$r='<INPUT '.$style.' TYPE="TEXT" id="'.
	  $this->name.'"'.
	  'NAME="'.$this->name.'" VALUE="'.$this->value.'"  '.
	  'SIZE="'.$this->size.'" "'.$this->javascript." ".$disabled." $readonly $this->extra >";
	
      if ($this->table==1) {
	if ( $this->label != "") {
	  $r="<TD  style=\"border:groove 1px blue;\">".$this->label."</TD><TD>".$r."</TD>";
	}else {
	  $r="<TD>".$r."</TD>";
	}
      }
      return $r;
    }
    // Hidden field
    if (strtoupper($this->type)=="HIDDEN") {
      $r='<INPUT TYPE="HIDDEN" id="'.$this->name.'" name="'.$this->name.'" value="'.$this->value.'">';
      if ( $this->readonly==true) return "";
      return $r;
    }
    // Select value
    if ( strtoupper($this->type) == "SELECT") {
      if ($this->readonly==false )
	{
	  $disabled=($this->disabled==true)?"disabled":"";
	  //echo "<b>Selected <b>".$this->selected;
	  $r="<SELECT  id=\"$this->name\" NAME=\"$this->name\" $this->javascript $disabled>";
	  for ( $i=0;$i<sizeof($this->value);$i++) 
	    {
	      $checked=($this->selected==$this->value[$i]['value'])?"SELECTED":"";
	      $r.='<OPTION VALUE="'.$this->value[$i]['value'].'" '.$checked.'>';
	      $r.=$this->value[$i]['label'];
	    }
	  $r.="</SELECT>";
	} 
      if ( $this->readonly==true) 
	{
	  $r="";
	  echo_debug('class_widget.php',__LINE__,"this->selected = ".$this->selected); 
	  for ( $i=0;$i<sizeof($this->value);$i++) 
	    {
	      echo_debug('class_widget.php',__LINE__,"check for ".$this->value[$i]['value']);
	      if ($this->selected==$this->value[$i]['value'] ) 
		{
		  $r=$this->value[$i]['label'];
 	
		}
	    }
	}
      if ( $this->table==1) {
	$r="<TD> $r </TD>";
	if ( $this->label != "") $r="<TD style=\"border: 1px groove blue;\"> $this->label</TD>".$r;
      }
      return $r;
    }
    // Password
    if (strtoupper($this->type)=="PASSWORD") {
      if ( $this->readonly==true) return "";
      $r='<input type="password" name="'.$this->name;
      $r.='">';
      if ($this->table==1) {
	$r="<TD> $this->label </TD><TD> $r </TD>";
      }
      return $r;
    }

    // Checkbox
    if (strtoupper($this->type)=="CHECKBOX") {
      if ( $this->readonly == true) {
	$check=( $this->selected==true )?"checked":"unchecked";
	$r='<input type="CHECKBOX" id="'.$this->name.'" name="'.$this->name.'"';
	$r.="  $check";
	$r.=' disabled>';

      } else {
	$check=( $this->selected==true )?"checked":"unchecked";
	$r='<input type="CHECKBOX" id="'.$this->name.'" name="'.$this->name.'"';
	$r.="  $check";
	$r.=' '.$disabled."  ".$this->javascript.'>';
      }
      if ($this->table==1) {
	$r="<TD> $this->label </TD><TD> $r </TD>";
      } else {
	$r=$r." $this->label";
      }
      return $r;
    }

    //radio
    if (strtoupper($this->type)=="RADIO") {
      if ( $this->readonly == true) {
	$check=( $this->selected==true || $this->selected=='t' )?"Yes":"no";
	$r=$check;
      } else {
	$check=( $this->selected==true||$this->selected=='t' )?"checked":"unchecked";
	$r='<input type="RADIO" name="'.$this->name.'"';
	$r.=" VALUE=\"$this->value\"";
	$r.="  $check";
	$r.=' '.$disabled.'>';
      }
      if ($this->table==1) {
	$r="<TD> $this->label </TD><TD> $r </TD>";
      } else {
      	$r=$this->label.$r;
      }
      return $r;
    }

    //textarea
    if (strtoupper($this->type)=="TEXTAREA") {
      if ( $this->readonly == false ) {
	$r="";
	$r.='<TEXTAREA name="'.$this->name.'"';
	$r.=" rows=\"$this->heigh\" ";
	$r.=" cols=\"$this->width\" ";
	$r.=' '.$disabled.'>';
	$r.=$this->value;

	$r.="</TEXTAREA>";
      } else {
	$r='<p>';
	$r.=$this->value;
	$r.=sprintf('<input type="hidden" name="%s" value="%s">',
		    $this->name,urlencode($this->value));
	$r.='</p>';

      }
      if ($this->table==1) {
	$r="<TD> $this->label </TD><TD> $r </TD>";
      }
      return $r;
    }

    //----------------------------------------------------------------------

    //----------------------------------------------------------------------
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

    //----------------------------------------------------------------------
    //file
    if (strtoupper($this->type)=="FILE") {
      if ( $this->readonly == false ) {
	$r='<INPUT class="inp" TYPE="file" name="'.$this->name.'" VALUE="'.$this->value.'">';

      }
      if ( $this->table==1) $r="<TD>$this->label</TD><TD>$r</TD>"; 
      return $r;
    }
    //----------------------------------------------------------------------
    // input type == js_search_poste => button search for the account
    if ( strtolower($this->type)=="js_search_poste") {
     
      $l_sessid=$_REQUEST['PHPSESSID'];
      if ( $this->readonly == false ) {
      // Do we need to filter ??
      if ( $this->extra2 == null ) {
      $r=sprintf('<TD>
         <INPUT class="inp" TYPE="button" onClick=SearchPoste(\'%s\','.dossier::id().',\'%s\',\'%s\') value="?">
            %s</TD><TD> 

             <INPUT style="border:groove 1px blue;"  TYPE="Text" NAME="%s" ID="%s" VALUE="%s" SIZE="8">
                 </TD>',
		 $l_sessid,
		 $this->name,
		 $this->extra,
		 $this->label,
		 $this->name,
		 $this->name,
		 $this->value 
		 );

    } else { // $p_list is not null, so we have a filter
      $r=sprintf('<TD>
         <INPUT TYPE="button" onClick=SearchPosteFilter(\'%s\','.dossier::id().',\'%s\',\'%s\',\'%s\') value="?">
            %s</TD><TD> 

             <INPUT style="border:groove 1px blue;" TYPE="Text" NAME="%s" id="%s" VALUE="%s" SIZE="8">
                 </TD>',
		 $l_sessid,
		 $this->name,
		 $this->extra2,
		 $this->extra,
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

  // input type == js_search => button search for card
  if ( strtolower($this->type)=="js_search") {
    $l_sessid=$_REQUEST['PHPSESSID'];
    if  ( $this->readonly == false ) {
      $r=sprintf('<TD>
         <INPUT TYPE="button" onClick=NewCard(\'%s\',\'%s\',\'%s\',\'%s\') value="+">
         </TD><TD>
         <INPUT TYPE="button" onClick=SearchCard(\'%s\',\'%s\',\'%s\',\'%s\') value="?">
         %s 
         <INPUT  style="border:solid 1px blue;"  TYPE="Text"  ID="%s"  NAME="%s" VALUE="%s" SIZE="8" onBlur="ajaxFid(\'%s\',\'%s\',\'%s\')">

                 ',
	       $l_sessid,
	       $this->extra, // deb or cred
	       $this->name,
	       $this->extra2, //jrn
	       $l_sessid,
	       $this->extra,
	       $this->name,
	       $this->extra2,
	       $this->label,
	       $this->name,
	       $this->name,
	       $this->value,
	       $this->name,
	       $this->extra, //deb or cred
	       $this->extra2 //jrn
	       );
    } else {
      // readonly == true
      $r=sprintf('<TD>            %s</TD>
                 <TD> 
                 <INPUT TYPE="hidden" NAME="%s" VALUE="%s" SIZE="8">
                 </TD>',
	       $this->label,
	       $this->name,
	       $this->value 
		 );

    }
    return $r;
  }// poste==js_search


  // input type == js_search => button search for card
  /*!\brief js_search_only only for searching a card no new button
   */
  if ( strtolower($this->type)=="js_search_only") {
    $l_sessid=$_REQUEST['PHPSESSID'];
    if  ( $this->readonly == false ) {
      if ( $this->table==1)
	{
	  $r=sprintf('<TD>
         <INPUT TYPE="button" onClick="SearchCard(\'%s\',\'%s\',\'%s\',\'%s\')" value="QuickCode">
            %s</TD><TD> <INPUT style="border:solid 1px blue;"  TYPE="Text"  style="border:solid 1px blue;" '.
		     ' NAME="%s" ID="%s" VALUE="%s" SIZE="8" onBlur="ajaxFid(\'%s\',\'%s\',\'%s\')">',
		     $l_sessid,
		     $this->extra,
		     $this->name,
		     $this->extra2,
		     $this->label,
		     $this->name,
		     $this->name,
		     $this->value,
		     $this->name,
		     $this->extra, //deb or cred
		     $this->extra2 //jrn

		     );
	}
      else
	{
	  $r=sprintf('
         <INPUT TYPE="button" onClick="SearchCard(\'%s\',\'%s\',\'%s\',\'%s\')" value="QuickCode">
            %s <INPUT TYPE="Text"  style="border:solid 1px blue;" '.
		     ' NAME="%s" ID="%s" VALUE="%s" SIZE="8"  onBlur="ajaxFid(\'%s\',\'%s\',\'%s\')">',
		     $l_sessid,
		     $this->extra,
		     $this->name,
		     $this->extra2,
		     $this->label,
		     $this->name,
		     $this->name,
		     $this->value,
		     $this->name,
		     $this->extra, //deb or cred
		     $this->extra2 //jrn

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





  // type=span
  if ( strtolower($this->type)=="span") {
    $r=sprintf('<span style="inline" id="%s"  >%s </span>',
	       $this->name,
	       $this->value
	       );

    return $r;
  }// end type = span

   // input type == js_tva
   if ( strtolower($this->type)=="js_tva") {
     $id=sprintf("<span id=%s></span>",$this->label);
     $r=sprintf('%s<TD>Tva</TD><TD> <INPUT TYPE="Text"  style="border:solid 1px blue;" '.
		   ' NAME="%s" VALUE="%s" SIZE="3" onChange="ChangeTVA(\'%s\',\'%s\');">',
 		  $id,
 	       $this->name,
 	       $this->value,
 	       $this->label,
 	       $this->name);
     $l_sessid=$_REQUEST['PHPSESSID'];
     $r.=sprintf("<input type=\"button\" value=\"Tva\" 
     	onClick=\"
        	           ShowTva('%s',%d,'%s');\"></TD>",
				 $l_sessid,dossier::id(),$this->name);
     return $r;
   }

  // input type == js_concerned => button search for the concerned operations
  if ( strtolower($this->type)=="js_concerned") {
    $td="";$etd="";
    if ( $this->table == 1 ) { $td='<td>'; $etd='</td>';}

    if ( $this->readonly == false) {
      $l_sessid=$_REQUEST['PHPSESSID'];

      $r=sprintf("$td
     <INPUT TYPE=\"button\" onClick=SearchJrn('%s',".dossier::id().",'%s',%s,'%s') value=\"?\">
       %s $etd  $td 
      <INPUT TYPE=\"text\"  style=\"color:black;background:lightyellow;border:solid 1px grey;\"  NAME=\"%s\" ID=\"%s\" VALUE=\"%s\" SIZE=\"8\" readonly>
                 $etd",
				 $l_sessid, 
				 $this->name,
				 $this->extra, 
				 $this->extra2,
				 $this->label, 
				 $this->name, 
				 $this->name, 
				 $this->value 
		 );
  } else {
    $r=sprintf("$td<span>%s <b>%s</b></span>",$this->label,$this->value);
    $r.=sprintf('<input type="hidden" name="%s" value="%s">'.$etd, $this->name,$this->value);
  }

    return $r;
  }// end js_concerned
  //----------------------------------------------------------------------
  // BUTTON
  //----------------------------------------------------------------------
  if ( strtolower($this->type)=="button") {
    $extra= ( isset($this->extra))?$this->extra:"";
	if ( $this->readonly==true) return "";
	$r='<input type="BUTTON" name="'.$this->name.'"'.
	  ' id="'.$this->name.'"'.
	  ' value="'.$this->label.'"'.
	  ' onClick="'.$this->javascript.'"'.$extra.'>';
	return $r;
  }
 //----------------------------------------------------------------------
  // JS_BUD_SEARCH_POSTE
  //----------------------------------------------------------------------
  if ( strtolower($this->type)=="js_bud_search_poste") {
	if ( $this->readonly==true) return "";
	$dis= ( $this->disabled==true) ? "disabled":"";
	  
	$this->javascript="bud_search_poste('".$_REQUEST['PHPSESSID']."',".dossier::id().",'$this->name')";
	$r='<input type="BUTTON" value="?"'.$dis.
	  ' onClick="'.$this->javascript.'" style="display:line">';
	$r.='<input type="text" readonly id="'.$this->name.'" name="'.
	  $this->name.'" value ="'.$this->value.'" size="35">';

	$r.='<input type="hidden" name="'.$this->name.'_hidden" id="'.$this->name.'_hidden" value="'.$this->value.'">';
	return $r;
  }

 
  //------------------------------------------------------------------------
  // JS_DATE
  //------------------------------------------------------------------------
  if ( strtolower($this->type)=="js_date") {
	if ( $this->readonly) {
	  $r="<span> Date : ".$this->value;
	  $r='<input type="hidden" name="'.$this->name.'"'.
		'id="'.$this->name.'"'.
		' value = "'.$this->value.'"></span>';
	} else {
	  $r='<input type="text" name="'.$this->name.'" id="'.$this->name.'"'.
		'style="border:solid 1px blue;"'.
		'size="10"'.
		' value ="'.$this->value.'"'.
		'/>'.
		'<img src="image/x-office-calendar.png" id="'.$this->name.'_trigger"'.
		' style="cursor: pointer; border: 1px solid red;" '.
		'onmouseover="this.style.background=\'red\';" onmouseout="this.style.background=\'\'" />';
	  $r.='<script type="text/javascript">'.
		'Calendar.setup({'.
		//	'date : "'.$this->value.'",
        'inputField     :    "'.$this->name.'",     // id of the input field
        ifFormat       :    "%d.%m.%Y",      // format of the input field
        button         :    "'.$this->name.'_trigger",  // trigger for the calendar (button ID)
        align          :    "Bl",           // alignment (defaults to "Bl")
        singleClick    :    true
    });
</script>
'; 
	}
	if ($this->table==1) {
	  if ( $this->label != "") {
		$r="<TD  style=\"border:groove 1px blue;\">".$this->label."</TD><TD>".$r."</TD>";
	  }else {
		$r="<TD>".$r."</TD>";
	  }
	}
	  
	return $r;
	
  }
  return "INVALID WIDGET $this->type ";
  } //end function
  /* Debug
   */
  function debug() {
    echo "Type ".$this->type."<br>";
    echo "name ".$this->name."<br>";
    echo "value". $this->value."<br>";
    $readonly=($this->readonly==false)?"false":"true";
    echo "read only".$readonly."<br>";
  }
  function Submit ($p_name,$p_value) {
    return '<INPUT TYPE="SUBMIT" NAME="'.$p_name.'" VALUE="'.$p_value.'">';
  }
  static   function submit_button ($p_name,$p_value,$p_javascript="") {
    
    return '<INPUT TYPE="SUBMIT" NAME="'.$p_name.'" VALUE="'.$p_value.'" '.$p_javascript.'>';
  }

  function Reset ($p_value) {
    return '<INPUT TYPE="SUBMIT"  VALUE="'.$p_value.'">';
  }
  static function hidden($p_name,$p_value) {
    return '<INPUT TYPE="hidden" id="'.$p_name.'" NAME="'.$p_name.'" VALUE="'.$p_value.'">';
  }
  static function button_href($p_name,$p_value) {
    return sprintf('<A class="mtitle" HREF="%s"><input type="button" value="%s"></A>',
		   $p_value,
		   $p_name);
  }

}
