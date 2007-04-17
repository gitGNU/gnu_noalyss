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

include_once("debug.php");
include_once("constant.php");
require_once('preference.php');
require_once ("postgres.php");
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
	$fdebug=fopen($_ENV['TMP'].DIRECTORY_SEPARATOR."phpcompta_error.log","a+");
	fwrite($fdebug,date("Ymd H:i:s").$p_log." ".$p_line." ".$p_message."\n");
	fclose($fdebug);
	echo_debug($p_log,$p_line,$p_message);
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
  $l_date=isDate($p_date);
  $l2_date=isDate($p_date_oth);
  if ($l_date == null || $l2_date == null ) {
    echo "erreur date";
    return null;
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
function isNumber($p_int) {
  if ( strlen (trim($p_int)) == 0 ) return 0;
  if ( is_numeric($p_int) == true)
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
  if (! ereg ("^[0-9]{1,2}\.[0-9]{1,2}\.20[0-9]{2}",$p_date) ) {

    return null;
  } else {
    $l_date=explode(".",$p_date);

    if ( sizeof ($l_date) != 3 )
      return null;

    if ( $l_date[2] > 2020 ) {
      return null;
    }
    echo_debug('ac_common.php',__LINE__,' date = '.var_export($l_date,true));

    if ( checkdate ($l_date[1],$l_date[0],$l_date[2]) == false) {
      return null;
    }

  }// !ereg
  return $p_date;
}
/*! 
 * \brief call cmpDate & add quotes
 *        
 * \return the date or the quoted string null 
 */
function formatDate($p_date) {
  if ( isDate($p_date)== null) return "null";
  return "'".$p_date."'";
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

 ini_set('magic_quotes_gpc','Off');

 $cn=DbConnect();
 if ( $p_theme != "") {
   $Res=ExecSql($cn,"select the_filestyle from theme
                   where the_name='".$p_theme."'");
    if (pg_NumRows($Res)==0) 
      $style="style.css";
    else {
      $s=pg_fetch_array($Res,0);
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
      <META http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\">
      <LINK REL=\"stylesheet\" type=\"text/css\" href=\"$style\" media=\"screen\">
      <link rel=\"stylesheet\" type=\"text/css\" href=\"style-print.css\" media=\"print\">".
   $p_script2. "
	</HEAD><script src=\"js/scripts.js\" type=\"text/javascript\"></script>";
 echo "<BODY $p_script>";
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
 * \brief Button logout
 *        

 * \return nothing
 */

function html_button_logout() {
	echo "<A class=\"mtitle\" HREF=logout.php> Logout </A>";
}
/*! 
 * \brief Echo no access and stop 
 *        
 * \return nothing
 */


function NoAccess() 
{
  echo "<script>";
  echo "alert ('Cette action ne vous est pas autorisée Contactez votre responsable');";
  echo "</script>";
  exit -1;
}
/*! 
 * \brief Fix the problem with the quote char for the database
 *        
 * \param $p_string 
 *
 * \return a string which won't let strange char for the database
 */
function FormatString($p_string) 
{
  if ( !isset ($p_string) ) {
    echo_error("ac_common.php FormatString p_string empty");
    return null;
  }
  $p_string=trim($p_string);
  if (strlen($p_string) == 0 ) return null;
  $p_string=str_replace("\'","'",$p_string);
  $p_string=str_replace("''","'",$p_string);
  $p_string=str_replace("'","\'",$p_string);
  return $p_string;
}

/*! 
/* \brief store the string which print */
/*           the content of p_array in a table */
/*           used to display the menu */
/* \param  array */
/* \return : string */
function ShowItem($p_array,$p_dir='V',$class="mtitle",$class_ref="mtitle",$default="",$p_extra="")
{
  $ret="<TABLE $p_extra>";
  // direction Vertical
  if ( $p_dir == 'V') {
    foreach ($p_array as $all=>$href){
      $title="";
      if ( isset ($href[2] )) 
	$title=$href[2];
      $ret.='<TR><TD CLASS="'.$class.'"><A class="'.$class_ref.'" HREF="'.$href[0].'" title="'.$title.'">'.$href[1].'</A></TD></TR>';
    }
  }
      //direction Horizontal
  else if ( $p_dir == 'H' ) {
    $ret.="<TR>";
    foreach ($p_array as $all=>$href){
      $title="";
      if ( isset ($href[2] )) 
	$title=$href[2];
      if ( $default== $href[0]) {
      $ret.='<TD CLASS="selectedcell">'.$href[1].'</TD>';

      } else {
      $ret.='<TD CLASS="'.$class.'"><A class="'.$class_ref.'" HREF="'.$href[0].'" title="'.$title.'">'.$href[1].'</A></TD>';
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
  echo '<H2 class="info">'.$p_string."</H2>";
}
/*! 
 **************************************************
 * \brief make a array with the sql
 *        
 * \param  $p_cn dbatabase connection
 * \param $p_sql related sql 
 *  \param $p_null if the array start with a null value
 *
 * \return: a double array [value,label]
 */
function make_array($p_cn,$p_sql,$p_null=0) {
  echo_debug('ac_common',__LINE__,$p_sql);
  $a=ExecSql($p_cn,$p_sql);
  $max=pg_NumRows($a);
  if ( $max==0) return null;
  for ($i=0;$i<$max;$i++) {
    $row=pg_fetch_row($a);
    $r[$i]['value']=$row[0];
    $r[$i]['label']=$row[1];
  }
  // add a blank item ?
  if ( $p_null == 1 ) {
  for ($i=$max;$i!=0;$i--) {
    $r[$i]['value']=    $r[$i-1]['value'];
    $r[$i]['label']=    $r[$i-1]['label'];
  }
  $r[0]['value']=-1;
  $r[0]['label']=" ";
  } //   if ( $p_null == 1 ) 

  return $r;
}
/*! 
 **************************************************
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
  $ret=execSql($p_cn,"select to_char($pos,'Mon YYYY') as t from parm_periode where p_id=$p_id");
  if (pg_NumRows($ret) == 0) return null;
  $a=pg_fetch_array($ret,0);
  return $a['t'];
}
/*! 
 **************************************************
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
  $R=ExecSql($p_cn,"select p_id from parm_periode where
              to_char(p_start,'DD.MM.YYYY') = '01.$p_date'");
  if ( pg_NumRows($R) == 0 ) 
    return -1;
  $a=pg_fetch_array($R,0);

  return $a['p_id'];
}
/*! 
 **************************************************
 * \brief Return the period corresponding to the 
 *           date
 *        
 *
 * \param  	p_cn database connection
 *  \param      p_date the date 'DD.MM.YYYY'
 * \return parm_periode.p_id
 *       
 */
function getPeriodeFromDate($p_cn,$p_date) {
  $R=ExecSql($p_cn,"select p_id from parm_periode where
              p_start <= to_date('$p_date','DD.MM.YYYY')
           and p_end  >= to_date('$p_date','DD.MM.YYYY')
           ");
  if ( pg_NumRows($R) == 0 ) 
    return -1;
  $a=pg_fetch_array($R,0);

  return $a['p_id'];
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
 * \param $p_from start date (date)
 * \param $p_to  end date (date)
 * \param $p_form if the p_from and p_to are date or p_id
 * \param $p_field column name 
 * \return a string containg the query
 */
function sql_filter_per($p_cn,$p_from,$p_to,$p_form='p_id',$p_field='jr_tech_per')
{
  echo_debug("sql_filter_per($p_cn,$p_from,$p_to,$p_form,$p_field)");
  if ( $p_form != 'p_id' && 
       $p_form != 'date' )
    {
      echo_error (__FILE__,__LINE__,'Mauvais parametres ');
      exit(-1);
    }
  if ( $p_form == 'p_id' )
    {
      // retrieve the date
      $a_start=GetPeriode($p_cn,$p_from);
      $a_end=GetPeriode($p_cn,$p_to);
      if ( $a_start == null or $a_end == null  )
	{
	  echo_debug(__FILE__,__LINE__,'Attention periode '.
		       ' non trouvee periode p_from='.$p_from.
		     'p_to_periode = '.$p_to);
	}

      $p_from=($a_start==null)?'01.01.1900':$a_start['p_start'];
      $p_to=($a_end==null)?'01.01.2100':$a_end['p_end'];
    }
  if ( $p_from == $p_to ) 
    $periode=" $p_field = to_date('$p_from','DD.MM.YYYY') ";
  else
    $periode = "$p_field in (select p_id from parm_periode ".
      " where p_start >= to_date('$p_from','DD.MM.YYYY') and p_end <= to_date('$p_to','DD.MM.YYYY')) ";
  
  return $periode;
}
/* !\brief return the label of the tva_id
 * \param $p_cn database connx
 * \param $p_tva_id tva_id
 */
function tva_get_label($p_cn,$p_tva_id)
{
  $a=getDbValue($p_cn,"select tva_label from tva_rate where tva_id='".$p_tva_id."'");
  return $a;
}

?>
