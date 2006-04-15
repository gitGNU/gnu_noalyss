<?
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
/*+++
function    widget
purpose     create the widget
parameters  p_type the type of the widget
            p_name     name of widget
            value      widget's value
            readonly   true or false
+++*/
class widget {
  var $type;
  var $name;
  var $value;
  var $readonly;
  var $size;
  var $selected;
  var $table;
  var $label;
  var $disabled;
  var $extra;
  var $extra2;
  var $tabindex;
  function widget($p_type="") {
    $this->type=$p_type;
    $this->readonly=false;
    $this->size=20;
    $this->width=50;
    $this->heigh=20;
    $this->value="";
    $this->selected="";
    $this->table=0;
    $this->label="";
    $this->disabled=false;
    $this->tabindex=32767;
  }
  function SetReadOnly($p_read) {
    $this->readonly=$p_read;
  }
  /*  function IOValue($p_name,$p_value="",$p_label="") 
   *****************************************************
   *  purpose : create the corresponding INPUT tag 
   *        
   *  parameters: $p_name is the INPUT NAME
   *          $p_value is the INPUT VALUE or an array for select
   *          $p_label is the label of the INPUT
   *  return : string containing the tag
   */
  function IOValue($p_name=null,$p_value=null,$p_label="") {
    
    if ( $p_name != null)
      $this->name=$p_name;
    $this->value=($p_value===null)?$this->value:$p_value;
    $this->label=($p_label == "")?$this->label:$p_label;
    
    // Input text type
    $disabled = $this->disabled ? "DISABLED" : "";
    if (strtoupper($this->type)=="TEXT") {
      if ( $this->readonly==false) {
	$r="<INPUT TYPE=\"TEXT\" NAME=\"$this->name\" VALUE=\"$this->value\" TABINDEX=\"$this->tabindex\" SIZE=\"$this->size\" ".$disabled.">";} else {
	    $r=sprintf('<span>%s</span><input type="hidden" name="%s" value="%s">', $this->value,$this->name,$this->value);
	}
	
      if ($this->table==1) {
	if ( $this->label != "") {
	  $r="<TD>".$this->label."</TD><TD>".$r."</TD>";
	}else {
	  $r="<TD>".$r."</TD>";
	}
      }
      return $r;
    }
    // Hidden field
    if (strtoupper($this->type)=="HIDDEN") {
      $r='<INPUT TYPE="HIDDEN" name="'.$this->name.'" value="'.$this->value.'">';
      if ( $this->readonly==true) return "";
      return $r;
    }
    // Select value
    if ( strtoupper($this->type) == "SELECT") {
      if ($this->readonly==false ){
	      //echo "<b>Selected <b>".$this->selected;
      $r="<SELECT NAME=\"$this->name\">";
      for ( $i=0;$i<sizeof($this->value);$i++) {
	$checked=($this->selected==$this->value[$i]['value'])?"SELECTED":"";
	$r.='<OPTION VALUE="'.$this->value[$i]['value'].'" '.$checked.'>';
	$r.=$this->value[$i]['label'];
      }
      $r.="</SELECT>";
      } else {
	echo_debug('class_widget.php',__LINE__,"this->selected = ".$this->selected); 
	for ( $i=0;$i<sizeof($this->value);$i++) {
	  echo_debug('class_widget.php',__LINE__,"check for ".$this->value[$i]['value']);
	  if ($this->selected==$this->value[$i]['value'] ) {
	    $r=$this->value[$i]['label'];
 	
	  }
	}
      }
      if ( $this->table==1) {
	$r="<TD> $r </TD>";
	if ( $this->label != "") $r="<TD> $this->label</TD>".$r;
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
	$check=( $this->selected==true )?"Yes":"no";
	$r=$check;
      } else {
	$check=( $this->selected==true )?"checked":"unchecked";
	$r='<input type="CHECKBOX" name="'.$this->name.'"';
	$r.="  $check";
	$r.=' '.$disabled.'>';
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
	$r='<TEXTAREA name="'.$this->name.'"';
	$r.=" rows=\"$this->heigh\" ";
	$r.=" cols=\"$this->width\" ";
	$r.=' '.$disabled.'>';
	$r.=$this->value;

	$r.="</TEXTAREA>";
      } else {
	$r='<p>';
	$r.=$this->value;
	$r.='</p>';
      }
      if ($this->table==1) {
	$r="<TD> $this->label </TD><TD> $r </TD>";
      }
      return $r;
    }

    //file
    if (strtoupper($this->type)=="FILE") {
      if ( $this->readonly == false ) {
	$r='<INPUT TYPE="file" name="'.$this->name.'" VALUE="'.$this->value.'">';

      }
      if ( $this->table==1) $r="<TD>$this->label</TD><TD>$r</TD>"; 
      return $r;
    }
  // input type == js_search_poste => button search for the account
    if ( strtolower($this->type)=="js_search_poste") {
     
      $l_sessid=$_REQUEST['PHPSESSID'];
      if ( $this->readonly == false ) {
      // Do we need to filter ??
      if ( $this->extra2 == null ) {
      $r=sprintf('<TD>
         <INPUT TYPE="button" onClick=SearchPoste(\'%s\',\'%s\',\'%s\') value="Search">
            %s</TD><TD> 

             <INPUT TYPE="Text" NAME="%s" VALUE="%s" SIZE="8">
                 </TD>',
		 $l_sessid,
		 $this->name,
		 $this->extra,
		 $this->label,
		 $this->name,
		 $this->value 
		 );

    } else { // $p_list is not null, so we have a filter
      $r=sprintf('<TD>
         <INPUT TYPE="button" onClick=SearchPosteFilter(\'%s\',\'%s\',\'%s\',\'%s\') value="Search">
            %s</TD><TD> 

             <INPUT TYPE="Text" NAME="%s" VALUE="%s" SIZE="8">
                 </TD>',
		 $l_sessid,
		 $this->name,
		 $this->extra2,
		 $this->extra,
		 $this->label,
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
      return $r;

    } // end js_search_poste

  // input type == js_search => button search for card
  if ( strtolower($this->type)=="js_search") {
    $l_sessid=$_REQUEST['PHPSESSID'];
    if  ( $this->readonly == false ) {
      $r=sprintf('<TD>
         <INPUT TYPE="button" onClick=NewCard(\'%s\',\'%s\',\'%s\',\'%s\') value="New">
         <INPUT TYPE="button" onClick=SearchCard(\'%s\',\'%s\',\'%s\',\'%s\') value="Search">
            %s</TD><TD> <INPUT TYPE="Text" NAME="%s" VALUE="%s" SIZE="8" TABINDEX="%s">
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
	       $this->value, 
         $this->tabindex
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
  // type=span
  if ( strtolower($this->type)=="span") {
    $r=sprintf('<span id="%s">%s</span>',
	       $this->name,
	       $this->value);
    return $r;
  }// end type = span

   // input type == js_tva
   if ( strtolower($this->type)=="js_tva") {
     $id=sprintf("<span id=%s></span>",$this->label);
        $r=sprintf('%s<TD> <INPUT TYPE="Text" NAME="%s" VALUE="%s" SIZE="3" onChange="ChangeTVA(\'%s\',\'%s\');">',
 		  $id,
 	       $this->name,
 	       $this->value,
 	       $this->label,
 	       $this->name);
     $l_sessid=$_REQUEST['PHPSESSID'];
     $r.=sprintf("<input type=\"button\" value=\"Tva\" 
     	onClick=\"
        	           ShowTva('%s','%s');\"></TD>",
 		$l_sessid,$this->name);
     return $r;
   }

  // input type == js_concerned => button search for the concerned operations
  if ( strtolower($this->type)=="js_concerned") {
    if ( $this->readonly == false) {
      $l_sessid=$_REQUEST['PHPSESSID'];
      $r=sprintf('<TD>
         <INPUT TYPE="button" onClick=SearchJrn(\'%s\',\'%s\') value="Search">
            %s</TD><TD> 

             <INPUT TYPE="Text" NAME="%s" VALUE="%s" SIZE="8">
                 </TD>',
		 $l_sessid,
		 $this->name,
		 $this->label,
		 $this->name,
		 $this->value 
		 );
    } else {
      $r=sprintf("<TD><span>%s</span>",$this->value);
      $r.=sprintf('<input type="hidden" name="%s" value="%s"></TD>', $this->name,$this->value);
    }

    return $r;
  }// end js_concerned
  return "INVALID WIDGET ";
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
}
