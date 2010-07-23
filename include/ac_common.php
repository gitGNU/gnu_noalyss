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

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/*! \file
 * \brief common utilities for a lot of procedure, classe
 */

include_once("constant.php");
require_once('class_database.php');
require_once('class_periode.php');

/*!\brief to protect again bad characters which can lead to a cross scripting attack
	the string to be diplayed must be protected
*/
function h($p_string) { return htmlspecialchars($p_string);}
function hi($p_string) { return '<i>'.htmlspecialchars($p_string).'</i>';}
function hb($p_string) { return '<b>'.htmlspecialchars($p_string).'</b>';}
function th($p_string,$p_extra='') { return '<th  '.$p_extra.'>'.htmlspecialchars($p_string).'</th>';}
function h2info($p_string) { return '<h2 class="info">'.htmlspecialchars($p_string).'</h2>';}
/*!\brief surround the string with td
*\param $p_string string to surround by TD
*\param $p_extra extra info (class, style, javascript...)
* \return string surrounded by td
*/
function td($p_string='',$p_extra=''){ return '<td  '.$p_extra.'>'.$p_string.'</td>';}
function tr($p_string,$p_extra=''){ return '<tr  '.$p_extra.'>'.$p_string.'</tr>';}
/*!\brief escape correctly php string to javascript */
function j($p_string) { $a=preg_replace("/\r?\n/", "\\n", addslashes($p_string)); return $a;}
/**
 * format the number for the CSV export
 *@param $p_number number
 */
function nb($p_number) {
  $r=sprintf('%.2f',$p_number);
  $r=str_replace('.',',',$r);
  return $r;
}

/*!
 * \brief  log error into the /tmp/phpcompta_error.log it doesn't work on windows
 *
 * \param p_log message
 * \param p_line line number
 * \param p_message is the message
 *
 * \return nothing
 *
 */
function echo_error      ($p_log, $p_line="", $p_message="") {
  echo "ERREUR :".$p_log." ".$p_line." ".$p_message;
  $fdebug=fopen($_ENV['TMP'].DIRECTORY_SEPARATOR."phpcompta_error.log","a+");
  if ($fdebug != null ) {
    fwrite($fdebug,date("Ymd H:i:s").$p_log." ".$p_line." ".$p_message."\n");
    fclose($fdebug);
  }
}

/*!
 * \brief  Compare 2 dates
 * \param p_date
 * \param p_date_oth
 *
 * \return
 *      - == 0 les dates sont identiques
 *      - > 0 date1 > date2
 *      - < 0 date1 < date2
 */
function cmpDate ($p_date,$p_date_oth) {
  date_default_timezone_set ('Europe/Brussels');

  $l_date=isDate($p_date);
  $l2_date=isDate($p_date_oth);
  if ($l_date == null || $l2_date == null ) {
    throw new Exception ("erreur date [$p_date] [$p_date_oth]");
  }
  $l_adate=explode(".",$l_date);
  $l2_adate=explode(".",$l2_date);
  $l_mkdate=mktime(0,0,0,$l_adate[1],$l_adate[0],$l_adate[2]);
  $l2_mkdate=mktime(0,0,0,$l2_adate[1],$l2_adate[0],$l2_adate[2]);
  // si $p_date > $p_date_oth return > 0
  return $l_mkdate-$l2_mkdate;
}
/*!
 * \brief check if the argument is a number
 *
 * \param $p_int number to test
 *
 * \return
 *        - 1 it's a number
 *        - 0 it is not
 */
function isNumber(&$p_int) {
  if ( strlen (trim($p_int)) == 0 ) return 0;
  if ( is_numeric($p_int) === true)
    return 1;
  else
    return 0;


}

/*!
 * \brief Verifie qu'une date est bien formaté
 *           en d.m.y et est valable
 * \param $p_date
 *
 * \return
 *	- null si la date est invalide ou malformaté
 *      - $p_date si tout est bon
 *
 */

function isDate ( $p_date) {
  if ( strlen (trim($p_date)) == 0 ) return null;
  if (! myereg ("^[0-9]{1,2}\.[0-9]{1,2}\.20[0-9]{2}",$p_date) ) {

    return null;
  } else {
    $l_date=explode(".",$p_date);

    if ( sizeof ($l_date) != 3 )
      return null;

    if ( $l_date[2] > 2020 ) {
      return null;
    }

    if ( checkdate ($l_date[1],$l_date[0],$l_date[2]) == false) {
      return null;
    }

  }// !ereg
  return $p_date;
}
/*!
 * \brief Default page header for each page
 *
 * \param p_theme default theme
 * \param $p_script
 * \param $p_script2  another js script
 *
 * \return none
 */
function html_page_start($p_theme="",$p_script="",$p_script2="")
{

 $cn=new Database();
 if ( $p_theme != "") {
   $Res=$cn->exec_sql("select the_filestyle from theme
                   where the_name='".$p_theme."'");
    if (Database::num_row($Res)==0)
      $style="style.css";
    else {
      $s=Database::fetch_array($Res,0);
      $style=$s['the_filestyle'];
    }
 }else {
   $style="style.css";
 } // end if
 echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 FINAL//EN">';
 echo "<HTML>";

 if ( $p_script2 != "" )
   $p_script2='<script src="'.$p_script2.'" type="text/javascript"></script>';

 echo "<HEAD>
      <TITLE>PhpCompta</TITLE>
      <META http-equiv=\"Content-Type\" content=\"text/html; charset=UTF8\">
      <LINK REL=\"stylesheet\" type=\"text/css\" href=\"$style\" media=\"screen\">
      <link rel=\"stylesheet\" type=\"text/css\" href=\"style-print.css\" media=\"print\">".
   $p_script2. "
	<script src=\"js/scripts.js\" type=\"text/javascript\"></script>";
 echo '<script language="javascript" src="js/calendar.js"></script>
<script type="text/javascript" src="js/lang/calendar-en.js"></script>
<script language="javascript" src="js/calendar-setup.js"></script>
<LINK REL="stylesheet" type="text/css" href="calendar-blue.css" media="screen">
</HEAD>
';

 echo "<BODY $p_script>";
 // language
 if ( isset($_SESSION['g_lang'])){
   set_language();
 }

 /* If we are on the user_login page */
 if ( basename($_SERVER['PHP_SELF']) == 'user_login.php') {
  return;
 }

}
/*!
 * \brief Minimal  page header for each page, used for small popup window
 *
 * \param p_theme default theme
 * \param $p_script
 * \param $p_script2  another js script
 *
 * \return none
 */
function html_min_page_start($p_theme="",$p_script="",$p_script2="")
{

 $cn=new Database();
 if ( $p_theme != "") {
   $Res=$cn->exec_sql("select the_filestyle from theme
                   where the_name='".$p_theme."'");
    if (Database::num_row($Res)==0)
      $style="style.css";
    else {
      $s=Database::fetch_array($Res,0);
      $style=$s['the_filestyle'];
    }
 }else {
   $style="style.css";
 } // end if
 echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 FINAL//EN">';
 echo "<HTML>";


 if ( $p_script2 != "" )
   $p_script2='<script src="'.$p_script2.'" type="text/javascript"></script>';

 echo "<HEAD>
      <TITLE>PhpCompta</TITLE>
      <META http-equiv=\"Content-Type\" content=\"text/html; charset=UTF8\">
      <LINK REL=\"stylesheet\" type=\"text/css\" href=\"$style\" media=\"screen\">
      <link rel=\"stylesheet\" type=\"text/css\" href=\"style-print.css\" media=\"print\">".
   $p_script2. "
	<script src=\"js/scripts.js\" type=\"text/javascript\"></script>";
 echo '</HEAD>
';

 echo "<BODY $p_script>";
 /* If we are on the user_login page */
 if ( basename($_SERVER['PHP_SELF']) == 'user_login.php') {
  return;
 }

}

/*!
 * \brief end tag
 *
 */
function html_page_stop()
{
	echo "</BODY>";
	echo "</HTML>";
}
/*!
 * \brief Echo no access and stop
 *
 * \return nothing
 */


function NoAccess($js=1)
{
  if ( $js == 1 )
    {
      echo "<script>";
      echo "alert ('"._('Cette action ne vous est pas autorisée Contactez votre responsable')."');";
      echo "</script>";
    }
  else
    {
      echo '<div class="u_redcontent">';
      echo '<h2 class="error">'._(' Cette action ne vous est pas autorisée Contactez votre responsable').'</h2>';
      echo '</div>';
    }
      exit -1;
}
/*!
 * \brief Fix the problem with the quote char for the database
 *
 * \param $p_string
 * \return a string which won't let strange char for the database
 */
function FormatString($p_string)
{
  $p_string=trim($p_string);
  if (strlen($p_string) == 0 ) return null;
  $p_string=str_replace("\'","'",$p_string);
  $p_string=str_replace("''","'",$p_string);
  $p_string=str_replace("'","\'",$p_string);
  return $p_string;
}

/*!
/* \brief store the string which print
 *           the content of p_array in a table
 *           used to display the menu
 * \param  $p_array array like ( 0=>HREF reference, 1=>visible item (name),2=>Help(opt),
 * 3=>selected (opt) 4=>javascript (normally a onclick event) (opt)
 * \param $p_dir direction of the menu (H Horizontal  V vertical)
 * \param $class CSS for TD tag
 * \param $class_ref CSS for the A tag
 * \param $default selected item
 * \param $p_extra extra code for the table tag (CSS or javascript)
 *
/* \return : string */
function ShowItem($p_array,$p_dir='V',$class="mtitle",$class_ref="mtitle",$default="",$p_extra="")
{

  $ret="<TABLE $p_extra>";
  // direction Vertical
  if ( $p_dir == 'V') {
    foreach ($p_array as $all=>$href){
      $javascript=(isset ($href[4]))?$href[4]:"";
      $title="";
      $set="XX";
      if ( isset ($href[2] ))
		$title=$href[2];
      if ( isset($href[3] ))
	$set=$href[3];

      if ( $set == $default )
	$ret.='<TR><TD CLASS="selectedcell"><A class="'.$class_ref.'" HREF="'.$href[0].'" title="'.$title.'" '.$javascript.'>'.$href[1].'</A></TD></TR>';
      else
	$ret.='<TR><TD CLASS="'.$class.'"><A class="'.$class_ref.'" HREF="'.$href[0].'" title="'.$title.'" '.$javascript.'>'.$href[1].'</A></TD></TR>';
    }
  }
      //direction Horizontal
  else if ( $p_dir == 'H' ) {

    $ret.="<TR>";
    foreach ($p_array as $all=>$href){
      $title="";
      $javascript=(isset ($href[4]))?$href[4]:"";
      
      $set="A";
      if ( isset ($href[2] ))
		$title=$href[2];

	  if ( isset($href[3]))
		$set=$href[3];

      if ( $default=== $href[0]||$set===$default ) {
	$ret.='<TD CLASS="selectedcell"><A class="'.$class_ref.'" HREF="'.$href[0].'" title="'.$title.'" '.$javascript.'>'.$href[1].'</A></TD>';
      } else {
	$ret.='<TD CLASS="'.$class.'"><A class="'.$class_ref.'" HREF="'.$href[0].'" title="'.$title.'" '.$javascript.'>'.$href[1].'</A></TD>';
      }

    }
    $ret.="</TR>";
  }
    $ret.="</TABLE>";
  return $ret;
}
/*!
 * \brief warns
 *
 * \param p_string error message
 * gen :
 *	- none
 * \return:
 *      - none
 */
function echo_warning($p_string)
{
  echo '<H2 class="error">'.$p_string."</H2>";
}
/*!
 * \brief Show the periode which found thanks its id
 *
 *
 * \param  $p_cn database connection
 * \param p_id
 * \param pos Start or end
 *
 * \return: string
 */
function getPeriodeName($p_cn,$p_id,$pos='p_start') {
  if ( $pos != 'p_start' and
       $pos != 'p_end')
    echo_error('ac_common.php'."-".__LINE__.'  UNDEFINED PERIODE');
  $ret=$p_cn->get_value("select to_char($pos,'Mon YYYY') as t from parm_periode where p_id=$p_id");
  return $ret;
}


/*!
 * \brief Return the period corresponding to the
 *           date
 *
 * \param p_cn database connection
 * \param p_date the month + year 'MM.YYYY'
 *
 * \return:
 *       parm_periode.p_id
 */
function getPeriodeFromMonth($p_cn,$p_date) {
  $R=$p_cn->get_value("select p_id from parm_periode where
              to_char(p_start,'DD.MM.YYYY') = '01.$p_date'");
  if ( $R == "" )
    return -1;
  return $R;
}
/*!\brief Decode the html for the widegt richtext and remove newline
 *\param $p_html string to decode
 * \return the html code without new line
 */

function Decode($p_html){
  $p_html=str_replace('%0D','',$p_html);
  $p_html=str_replace('%0A','',$p_html);
  $p_html=urldecode($p_html);
  return $p_html;
}
/*!\brief Create the condition to filter on the j_tech_per
 *        thanks a from and to date.
 * \param $p_cn database conx
 * \param $p_from start date (date)
 * \param $p_to  end date (date)
 * \param $p_form if the p_from and p_to are date or p_id
 * \param $p_field column name
 * \return a string containg the query
 */
function sql_filter_per($p_cn,$p_from,$p_to,$p_form='p_id',$p_field='jr_tech_per')
{

  if ( $p_form != 'p_id' &&
       $p_form != 'date' )
    {
      echo_error (__FILE__,__LINE__,'Mauvais parametres ');
      exit(-1);
    }
  if ( $p_form == 'p_id' )
    {
      // retrieve the date
      $pPeriode=new Periode($p_cn);
      $a_start=$pPeriode->get_date_limit($p_from);
      $a_end=$pPeriode->get_date_limit($p_to);
      if ( $a_start == null || $a_end == null  )
		throw new Exception(__FILE__.__LINE__.'Attention periode '.
			' non trouvee periode p_from='.$p_from.
			'p_to_periode = '.$p_to);


      $p_from=$a_start['p_start'];
      $p_to=$a_end['p_end'];
    }
  if ( $p_from == $p_to )
    $periode=" $p_field = (select p_id from parm_periode ".
      " where ".
      " p_start = to_date('$p_from','DD.MM.YYYY')) ";
  else
    $periode = "$p_field in (select p_id from parm_periode ".
      " where p_start >= to_date('$p_from','DD.MM.YYYY') and p_end <= to_date('$p_to','DD.MM.YYYY')) ";
  return $periode;
}

/*!\brief alert in javascript
 *\param $p_msg is the message
 *\param $buffer if false, echo directly and execute the javascript, if $buffer is true, the alert javascript
 * is in the return string
 *\return string with alert javascript if $buffer is true
 */
function alert($p_msg,$buffer=false)
{
  $r= '<script language="javascript">';
  $r.= 'alert(\''.j($p_msg).'\')';
  $r.= '</script>';

  if ($buffer) return $r;
  echo $r;

}
/**
 *@brief set the lang thanks the _SESSION['g_lang'] var.
 */
function set_language() {
   $dir="";
   // set differently the language depending of the operating system
   if( what_os() == 1 ) {
     $dir=setlocale(LC_MESSAGES,$_SESSION['g_lang']);
   if ( $dir == "") {
     $g_lang='fr_FR.utf8';
     $dir=setlocale(LC_MESSAGES,$g_lang);
     echo '<span class="notice">'.$_SESSION['g_lang'].' domaine non supporté</h2>';
     }
   bindtextdomain('messages','./lang');
   textdomain('messages');
   bind_textdomain_codeset('messages','UTF8');
   
   return; 
   }
   // for windows
   putenv('LANG='.$_SESSION['g_lang']);
   $dir=setlocale(LC_ALL,$_SESSION['g_lang']);
   bindtextdomain('messages','.\\lang');
   textdomain('messages');
   bind_textdomain_codeset('messages','UTF8');
}
/**
 *@brief try to determine on what os you are running the pĥpcompte
 *server
 *@return 
 *  0 it is a windows
 *  1 it is a Unix like
 */
function what_os() {
  $inc_path=get_include_path();
  
  if ( strpos($inc_path,";") != 0 ) {
    $os=0;			/* $os is 0 for windoz */
  } else {
    $os=1;			/* $os is 1 for unix */
  }
  return $os;
}
  /**
   *@brief shrink the date, make a date shorter for the printing
   *@param $p_date format DD.MM.YYYY
   *@return date in the format DDMMYY (size = 13 mm in arial 8)
   */
function shrink_date ($p_date) {
    $date=str_replace('.','',$p_date);
    $str_date=substr($date,0,4).substr($date,6,2);
    return $str_date;
}
  /**
   *@brief format the date, when taken from the database the format
   * is MM-DD-YYYY
   *@param $p_date format YYYY-MM-DD
   *@return date in the format DD.MM.YYYY
   */
function format_date ($p_date) {
  $date=explode('-',$p_date);
  if ( count($date) != 3) return $p_date;
  $str_date=$date[2].'.'.$date[1].'.'.$date[0];
  return $str_date;
}
/**
 *@brief ereg is not supported from the version 5.3 and is marked as
 *obsolete, this function will call preg_match and returns
 * false is nothing is found or the length of the string found
 *@param 
 *@param
 *@return
 *@see
 */
function myereg($p_pattern,$p_string,&$p_array=null) {
  $version=phpversion();
  if ( substr($version,0,3) == '5.2' ) {
    /* mimic old ereg */
    return ereg($p_pattern,$p_string,$p_array);
  } else {
    /* use the new preg_match */
    /**
     *@todo ereg are obsolete from the version 5.3.0
     * we have to emulate it 
     */
    /*      $a=preg_match_all($p_pattern,$p_string,$p_array);
    if ( $a == 0 ) return false;
    return true;*/
    $a=@ereg($p_pattern,$p_string,$p_array);
    return $a;
  }
}
/**
 *@brief remove the quote or double quote them
 *@param $q string
 *@return string correctly quoted
 */
function sql_string($q) {
  $ret=str_replace("'","''",$q);
  return $ret;
}
?>
