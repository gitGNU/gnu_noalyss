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
 *      - SPAN
 */
class HtmlInput
{

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
    function __construct($p_name="",$p_value="")
    {
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
        $this->attribute=array();

    }
    function setReadOnly($p_read)
    {
        $this->readOnly=$p_read;
    }
    /*!\brief set the extra javascript property for the INPUT field
     *\param $p_name name of the parameter
     *\param $p_value default value of this parameter
     */
    public function set_attribute($p_name,$p_value)
    {
        $this->attribute[]=array($p_name,$p_value);
        $this->$p_name=$p_value;
    }
    /**
     *@brief you can add attribute to this in javascript
     * this function is a wrapper and create a script (in js) to modify
     * "this" (in javascript) with the value of obj->attribute from PHP
     *@return return string with the javascript code
     */
    public function get_js_attr()
    {
        require_once('function_javascript.php');
        $attr="";
        if ( count($this->attribute) == 0) return "";

        /* Add properties at the widget */
        for ($i=0;$i< count($this->attribute);$i++)
        {
            list($name,$value)=$this->attribute[$i];
            $tmp1=sprintf("$('%s').%s='%s';",
                          $this->name,
                          $name,
                          $value);
            $attr.=$tmp1;
        }
        $attr=create_script($attr);
        return $attr;
    }
    /**
     * Make a JSON object, this method create a javascript object
     * with the attribute set, it returns a javascript string with the object
     * @param $p_name : name of the object, can be null. If the name is not null, return
     * $p_name={} otherwise only the object {}
     * @return javascript string with the object
     * @note: there is not check on the key->value, so you could need to escape
     * special char as quote, single-quote...
     * @code
    $a=new IButton()
    $a->set_attribute('prop','1');
    $a->set_attribute('prop','2');
    $a->set_attribute('prop','3');
    $string = $a->make_object('property');
    echo $string => property={'prop':'1','prop2':'2','prop3':'3'};
    $string = $a->make_object(null);
    echo $string => {'prop':'1','prop2':'2','prop3':'3'};
    @endcode
    */
    public function make_object($p_name=null)
    {
        $name=($p_name != null)?$p_name.'=':'';
        if ( count($this->attribute) == 0) return $name."{}";
        $ret=$name."{";
        $and='';

        for ($i=0;$i< count($this->attribute);$i++)
        {
            list($name,$value)=$this->attribute[$i];
            $tmp1=sprintf($and."'%s':'%s'",
                          $name,
                          $value);
            $ret.=$tmp1;
            $and=',';
        }

        $ret.='}';
        return $ret;
    }
    //#####################################################################
    /* Debug
     */
    function debug()
    {
        echo "Type ".$this->type."<br>";
        echo "name ".$this->name."<br>";
        echo "value". $this->value."<br>";
        $readonly=($this->readonly==false)?"false":"true";
        echo "read only".$readonly."<br>";
    }
    static   function submit ($p_name,$p_value,$p_javascript="")
    {

        return '<INPUT TYPE="SUBMIT" class="button" NAME="'.$p_name.'" VALUE="'.$p_value.'" '.$p_javascript.'>';
    }
    static   function button ($p_name,$p_value,$p_javascript="")
    {

        return '<INPUT TYPE="button" class="button" NAME="'.$p_name.'" ID="'.$p_name.'" VALUE="'.$p_value.'" '.$p_javascript.'>';
    }

    static function reset ($p_value)
    {
        return '<INPUT TYPE="RESET" class="button" VALUE="'.$p_value.'">';
    }
    static function hidden($p_name,$p_value)
    {
        return '<INPUT TYPE="hidden" id="'.$p_name.'" NAME="'.$p_name.'" VALUE="'.$p_value.'">';
    }

    static function extension()
    {
        return self::hidden('plugin_code',$_REQUEST['plugin_code']);
    }

    /*!\brief create a button with a ref
     *\param $p_label the text
     *\param $p_value the location of the window,
     *\param $p_name the id of the span
     *\param $p_javascript javascript for this button
     *\return string with htmlcode
     */
    static function button_anchor($p_label,$p_value,$p_name="",$p_javascript="")
    {
        $r=sprintf('<span id="%s" > <A class="button" style="display:inline;"  href="%s" %s >%s</A></span>',
                   $p_name,
                   $p_value,
                   $p_javascript,
                   $p_label);
        return $r;
    }
    static function infobulle($p_comment)
    {
        $r='<A HREF="#" style="display:inline;color:black;background-color:yellow;padding-left:4px;width:2em;padding-right:4px;text-decoration:none;" onmouseover="showBulle(\''.$p_comment.'\')"  onclick="showBulle(\''.$p_comment.'\')" onmouseout="hideBulle(0)">?</A>';
        return $r;
    }
    static function warnbulle($p_comment)
    {
        $r='<A HREF="#" style="display:inline;color:red;background-color:white;padding-left:4px;padding-right:4px;text-decoration:none;" onmouseover="showBulle(\''.$p_comment.'\')"  onclick="showBulle(\''.$p_comment.'\')" onmouseout="hideBulle(0)">&Delta;</A>';
        return $r;
    }
    /**
     * return a string containing the html code for calling the modifyOperation
     */
    static function detail_op($p_jr_id,$p_mesg)
    {
        return sprintf('<A class="detail" style="text-decoration:underline;display:inline" HREF="javascript:modifyOperation(%d,%d)">%s</A>',
                       $p_jr_id,dossier::id(),$p_mesg);
    }
    /**
     * return a string containing the html code for calling the modifyModeleDocument
     */
    static function detail_modele_document($p_id,$p_mesg)
    {
        return sprintf('<A class="detail" style="text-decoration:underline" HREF="javascript:modifyModeleDocument(%d,%d)">%s</A>',
                       $p_id,dossier::id(),$p_mesg);
    }

    /**
     * return a string containing the html code for calling the removeStock
     */
    static function remove_stock($p_id,$p_mesg)
    {
        return sprintf('<A class="detail" style="text-decoration:underline" HREF="javascript:removeStock(%d,%d)">%s</A>',
                       $p_id,dossier::id(),$p_mesg);
    }

    /**
     * display a div with the history of the card
     */
    static function history_card($f_id,$p_mesg,$p_style="")
    {
        $view_history= sprintf('<A class="detail"  style="text-decoration:underline;%s" HREF="javascript:view_history_card(\'%s\',\'%s\')" >%s</A>',
                               $p_style,$f_id, dossier::id(), $p_mesg);
        return $view_history;
    }
    /**
     * display a div with the history of the card
     */
    static function history_card_button($f_id,$p_mesg)
    {
      static $e=0;
      $e++;
      $js= sprintf('onclick="view_history_card(\'%s\',\'%s\')"',
                               $f_id, dossier::id());
      $view_history=HtmlInput::button("hcb"+$e,$p_mesg,$js);
      return $view_history;
    }

    /**
     * display a div with the history of the account
     */
    static function history_account($p_account,$p_mesg,$p_style="")
    {
        $view_history= sprintf('<A class="detail" style="text-decoration:underline;%s" HREF="javascript:view_history_account(\'%s\',\'%s\')" >%s</A>',
                               $p_style,$p_account, dossier::id(), $p_mesg);
        return $view_history;
    }

    /**
     * return the html code to create an hidden div and a button
     * to show this DIV. This contains all the available ledgers
     * for the user in READ or RW
     *@param $p_array is an array obtains thanks User::get_ledger
     *@param $selected is an array of checkbox
     *@note the choosen ledger are stored in the array r_jrn (_GET)
     */
    static function select_ledger($p_array,$p_selected,$div='')
    {
        ob_start();
        $ledger=new IButton('l');
        $ledger->label="choix des journaux";
        $ledger->javascript=" show_ledger_choice()";
        echo $ledger->input();

        /* create a hidden div for the ledger */
        echo '<div id="div_jrn'.$div.'" >';
        echo '<h2 class="info">Choix des journaux</h2>';

        echo '<ul>';
        for ($e=0;$e<count($p_array);$e++)
        {
            $row=$p_array[$e];
            $r=new ICheckBox('r_jrn['.$e.']',$row['jrn_def_id']);
            $idx=$row['jrn_def_id'];
            if ( $p_selected != null && isset($p_selected[$e]))
            {
                $r->selected=true;
            }
            echo '<li style="list-style-type: none;">'.$r->input().$row['jrn_def_name'].'('.$row['jrn_def_type'].')</li>';

        }
        echo '</ul>';
        $hide=new IButton('l');
        $hide->label="Valider";
        $hide->javascript=" hide_ledger_choice() ";
        echo $hide->input();

        echo '</div>';
        $ret=ob_get_contents();
        ob_clean();
        return $ret;
    }
    /**
     *create a hidden plus button to select the cat of ledger
     *@note the selected value is stored in the array p_cat
     */
    static function select_cat($array_cat)
    {
        ob_start();
        $ledger=new IButton('l');
        $ledger->label="Catégorie";
        $ledger->javascript=" show_cat_choice()";
        echo $ledger->input();

        /* create a hidden div for the ledger */
        echo '<div id="div_cat">';
        echo '<h2 class="info">Choix des categories</h2>';
        $selected=(isset($_GET['r_cat']))?$_GET['r_cat']:null;

        echo '<ul>';
        for ($e=0;$e<count($array_cat);$e++)
        {
            $row=$array_cat[$e];
            $re=new ICheckBox('r_cat['.$e.']',$row['cat']);

            if ( $selected != null && isset($selected[$e]))
            {
                $re->selected=true;
            }
            echo '<li style="list-style-type: none;">'.$re->input().$row['name'].'('.$row['cat'].')</li>';

        }
        echo '</ul>';
        $hide=new IButton('l');
        $hide->label="Valider";
        $hide->javascript=" hide_cat_choice() ";
        echo $hide->input();

        echo '</div>';
        $r=ob_get_contents();
        ob_clean();
        return $r;
    }
    static function display_periode($p_id)
    {
      $r=sprintf('<a href="javascript:void(0)" onclick="display_periode(%d,%d)">Modifier</a>',
		 dossier::id(),
		 $p_id);
      return $r;
    }
    /**
     *close button for the HTML popup
     *@see add_div modify_operation
     *@param $div_name is the name of the div to remove
     */
    static function button_close($div_name)
    {
      $a=new IButton('Fermer','Fermer');
      $a->label="Fermer";
      $a->javascript="removeDiv('".$div_name."')";
      $html=$a->input();

      return $html;

    }
    /**
     * Return a html string with an anchor which close the inside popup. (top-right corner)
     *@param name of the DIV to close
     */
    static function anchor_close($div)
    {
	$r='';
	$r.='<div style="float:right;margin-right:2px;margin-top:1px;padding:0">';
	$r.= '<A id="close_div" HREF="javascript:void(0)" onclick="removeDiv(\''.$div.'\');">Fermer</A>';
	$r.='</div>';
	return $r;
    }
    /**
     * button Html
     *@param $action action action to perform (message) without onclick
     *@param $javascript javascript to execute
     */
    static function button_action($action,$javascript,$id="xx")
    {
		$r="";
		$r.='<input type="button" id="'.$id.'"class="button" onclick="'.$javascript.'" value="'.h($action).'">';
		return $r;

    }
    /**
     * Return a html string with an anchor to hide a div, put it in the right corner
     *@param $action action action to perform (message)
     *@param $javascript javascript
     *@note not protected against html
     *@see Acc_Ledger::display_search_form
     */
    static function anchor_hide($action,$javascript)
    {
	$r='';
	$r.='<div style="float:right;right;margin:2;">';
	$r.= '<A id="close_div" HREF="javascript:void(0)" onclick="'.$javascript.'"">'.$action.'</A>';
	$r.='</div>';
	return $r;
    }

    /**
     * Javascript to print the current window
     */
    static function print_window()
    {
	$r='';
	$r.=HtmlInput::button('print','Imprimer','onclick="window.print();"');
	return $r;
    }
    /**
     *show the detail of a card
     */
    static function card_detail($p_qcode,$pname='',$p_style="")
    {
      //if ($pname=='')$pname=$p_qcode;
      $r="";
      $r.=sprintf('<a href="javascript:void(0)" %s onclick="fill_ipopcard({qcode:\'%s\'})">%s [%s]</a>',
		  $p_style,$p_qcode,$pname,$p_qcode);
      return $r;
    }
    /**
     *transform request data  to hidden
     *@param $array is an of indices
     *@param $request name of the superglobal $_POST $_GET $_REQUEST(default)
     *@return html string with the hidden data
     */
    static function array_to_hidden($array,$global_array )
    {

      $r="";

      if ( count($global_array )==0) return '';
      foreach ($array  as $a)
	{
	  if (isset($global_array [$a])) $r.=HtmlInput::hidden($a,$global_array [$a]);
	}

      return $r;
    }
    /**
     *transform $_GET   data  to hidden
     *@param $array is an of indices
     *@see HtmlInput::request_to_hidden
     *@return html string with the hidden data
     */
    static function get_to_hidden($array)
    {
      $r=self::array_to_hidden($array,$_GET );
      return $r;
    }

    /**
     *transform $_POST  data  to hidden
     *@param $array is an of indices
     *@see HtmlInput::request_to_hidden
     *@return html string with the hidden data
     */
    static function post_to_hidden($array)
    {
      $r=self::array_to_hidden($array,$_POST );
      return $r;
    }

    /**
     *transform $_REQUEST   data  to hidden
     *@param $array is an of indices
     *@see HtmlInput::request_to_hidden
     *@return html string with the hidden data
     */
    static function request_to_hidden($array)
    {
      $r=self::array_to_hidden($array,$_REQUEST  );
      return $r;
    }

    /**
     *transform request data  to string
     *@param $array is an of indices
     *@param $request name of the superglobal $_POST $_GET $_REQUEST(default)
     *@return html string with the string data
     */
    static function array_to_string($array,$global_array )
    {

      $r="?";

      if ( count($global_array )==0) return '';
      $and="";
      foreach ($array  as $a)
	{
	  if (isset($global_array [$a]))
	     $r.=$and."$a=".$global_array [$a];
	  $and="&amp;";
	}

      return $r;
    }
    /**
     *transform $_GET   data  to string
     *@param $array is an of indices
     *@see HtmlInput::request_to_string
     *@return html string with the string data
     */
    static function get_to_string($array)
    {
      $r=self::array_to_string($array,$_GET );
      return $r;
    }

    /**
     *transform $_POST  data  to string
     *@param $array is an of indices
     *@see HtmlInput::request_to_string
     *@return html string with the string data
     */
    static function post_to_string($array)
    {
      $r=self::array_to_string($array,$_POST );
      return $r;
    }

    /**
     *transform $_REQUEST   data  to string
     *@param $array is an of indices
     *@see HtmlInput::request_to_string
     *@return html string with the string data
     */
    static function request_to_string($array)
    {
      $r=self::array_to_string($array,$_REQUEST  );
      return $r;
    }

    /**
     * generate an unique id for a widget,
     *@param $p_prefix prefix
     *@see HtmlInput::IDate
     *@return string with a unique id
     */
    static function generate_id($p_prefix)
    {
      $r=sprintf('%s_%d',$p_prefix,mt_rand(0,999999));
      return $r;
    }
    /**
     * return default if the value if the array doesn't exist
     *@param $ind the index to check
     *@param $default the value to return
     *@param $array the array
     */
    static function default_value($ind,$default,$array)
    {
      if ( ! isset($array[$ind]))
	{
	  return $default;
	}
      return $array[$ind];
    }
	static function title_box($name,$div)
	{
		$r=HtmlInput::anchor_close($div);
		$r.=h2info($name);
		return $r;
	}
        /**
         *Return a simple anchor with a url or a javascript
         * if $p_js is not null then p_url will be javascript:void(0)
         * we don't add the event onclick. You must give p_url OR p_js
         * default CSS class=line
         * @param string $p_text text of the anchor
         * @param string $p_url  url
         * @param string $p_js javascript
         */
      static function anchor($p_text,$p_url="",$p_js="")
      {
          if ($p_js != "")
          {
              $p_url="javascript:void(0)";
          }


          $str=sprintf('<a class="line" href="%s" %s>%s</a>',
                  $p_url,$p_js,$p_text);
          return $str;
      }
}