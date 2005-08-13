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

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

include_once("debug.php");
include_once("constant.php");

function echo_error      ($p_log) {
	$fdebug=fopen("/tmp/phpcompta_error.log","a+");
	fwrite($fdebug,date("Ymd H:i:s").$p_log."\n");
	fclose($fdebug);
}

/* function cmpDate
 * Purpose : 
 *         Compare 2 dates
 * parm : 
 *	- date1 date2
 * gen :
 *	- rien
 * return:
 *	- == 0 les dates sont identiques
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
/* function IsNumber
 * purpose : check if the argument is a number
 *
 * parm : $p_int
 *
 * gen nothing
 * 
 * return :
 *        1 it's a number
 *        0 it is not
 */
function isNumber($p_int) {
  if ( strlen (trim($p_int)) == 0 ) return 0;
  if (! ereg ("^-{0,1}[0-9]+.{0,1}[0-9]*$",$p_int) ) {
    return 0;
  } else {
    return 1;
  }// !ereg
  return $p_date;
}

/* function isDate
 * Purpose : Verifie qu'une date est bien formaté
 *           en d.m.y et est valable
 * parm : 
 *	- $p_date
 * gen :
 *	- rien
 * return:
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
    if ( $l_date[2] > 2020 ) {
      return null;
    }
    if ( checkdate ($l_date[1],$l_date[0],$l_date[2]) == false) {
      return null;
    }

  }// !ereg
  return $p_date;
}
/* function  formatDate($p_date) 
 * Purpose : 
 *        
 * parm : 
 *	-
 * gen :
 *	-
 * return:
 */
function formatDate($p_date) {
  if ( isDate($p_date)== null) return "null";
  return "'".$p_date."'";
}
/* function html_page_start
 * Purpose : Default page header for each page
 *        
 * parm : 
 *      - default theme
 *	- $p_script
 * gen :
 *	- none
 * return:
 *        none
 */
function html_page_start($p_theme="",$p_script="")
{	
  include_once ("postgres.php");
 ini_set('magic_quotes_gpc','Off');
 ini_set('session.use_trans_sid',1);
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
 echo "<HEAD> 
      <TITLE> Gnu Accountancy</TITLE>
      <META http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\">
      <LINK REL=\"stylesheet\" type=\"text/css\" href=\"$style\">
	</HEAD><script src=\"scripts.js\" type=\"text/javascript\"></script>";
 echo "<BODY $p_script>";
}
/* function html_page_stop()
 * Purpose : 
 *        
 * parm : 
 *	-
 * gen :
 *	-
 * return:
 */
function html_page_stop()
{
	echo "</BODY>";
	echo "</HTML>";
}
/* function html_button_logout 
 * Purpose : 
 *        
 * parm : 
 *	-
 * gen :
 *	-
 * return:
 */

function html_button_logout() {
	echo "<A class=\"mtitle\" HREF=logout.php> Logout </A>";
}
/* function  NoAccess 
 * Purpose : 
 *        
 * parm : 
 *	-
 * gen :
 *	-
 * return:
 */


function NoAccess() {
  echo "<BR><BR><BR><BR><BR><BR>";
  echo "<P ALIGN=center><BLINK>
	<FONT size=+12 COLOR=RED>
	You haven't access
	</FONT></BLINK></P></BODY></HTML>";
		
  exit -1;
}
/* function FormatString($p_string) 
 * Purpose : Fix the problem with the ' char
 *        
 * parm : 
 *	-
 * gen :
 *	-
 * return:
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
/* function GetUserType
 * Purpose : get the type of an user (compta,developper or user)
 *        
 * parm : 
 *	- param $p_user user_login
 * gen :
 *	- none
 * return: the type of the user
 */
function GetUserType($p_user)
{
  $cn=DbConnect();
  $Res=ExecSql($cn,"select use_usertype from ac_users
                    where use_login='".$p_user."'");
  if ( pg_NumRows($Res) == 0 ) return null;
  $Ret=pg_fetch_row($Res,0);
  return $Ret[0];
}

/* function ShowItem($p_array) */
/* purpose : store the string which print */
/*           the content of p_array in a table */
/*           used to display the menu */
/* parameter : array */
/* return : string */
function ShowItem($p_array,$p_dir='V',$class="mtitle",$class_ref="mtitle",$default="")
{
  $ret="<TABLE>";
  // direction Vertical
  if ( $p_dir == 'V') {
    foreach ($p_array as $all=>$href){
      $ret.='<TR><TD CLASS="'.$class.'"><A class="'.$class_ref.'" HREF="'.$href[0].'">'.$href[1].'</A></TD></TR>';
    }
  }
      //direction Horizontal
  else if ( $p_dir == 'H' ) {
    $ret.="<TR>";
    foreach ($p_array as $all=>$href){
      if ( $default== $href[0]) {
      $ret.='<TD CLASS="selectedcell">'.$href[1].'</TD>';

      } else {
      $ret.='<TD CLASS="'.$class.'"><A class="'.$class_ref.'" HREF="'.$href[0].'">'.$href[1].'</A></TD>';
      }
    }
    $ret.="</TR>";
  }
    $ret.="</TABLE>";
  return $ret;
}
/* function echo_warning
 * Purpose : warns
 *        
 * parm : 
 *	- string
 * gen :
 *	- none
 * return:
 *      - none
 */
function echo_warning($p_string) 
{
  echo '<H2 class="info">'.$p_string."</H2>";
}
/* function make_array($cn,$sql)
 **************************************************
 * Purpose : make a array with the sql
 *        
 * parm : 
 *	- $cn dbatabase connection
 *      - $sql related sql 
 * gen :
 *	- none
 * return: a double array [value,label]
 */
function make_array($p_cn,$p_sql,$p_null=0) {

  $a=pg_exec($p_cn,$p_sql);
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
/* function getPeriodeName
 **************************************************
 * Purpose : Show the periode which found thanks its
 *           id
 *        
 * parm : 
 *	- $p_cn database connection 
 *      - p_id
 *      - Start or end
 * gen :
 *	-
 * return: string
 */
function getPeriodeName($p_cn,$p_id,$pos='p_start') {
  if ( $pos != 'p_start' and 
       $pos != 'p_end')
    echo_error(__FILE__."-".__LINE__.'  UNDEFINED PERIODE');
  $ret=execSql($p_cn,"select to_char($pos,'Mon YYYY') as t from parm_periode where p_id=$p_id");
  if (pg_NumRows($ret) == 0) return __FILE__.":".__LINE__." ERROR UNKNOW PERIODE";
  $a=pg_fetch_array($ret,0);
  return $a['t'];
}
/* function getPeriodeFromDate
 **************************************************
 * Purpose : Return the period corresponding to the 
 *           date
 *        
 * parm : 
 *	- p_cn database connection
 *      - the date 'MM.YYYY'
 * gen :
 *	- none
 *
 * return:
 *       parm_periode.p_id
 */
function getPeriodeFromDate($p_cn,$p_date) {
  $R=ExecSql($p_cn,"select p_id from parm_periode where
              to_char(p_start,'DD.MM.YYYY') = '01.$p_date'");
  if ( pg_NumRows($R) == 0 ) 
    return -1;
  $a=pg_fetch_array($R,0);

  return $a['p_id'];
}

?>
