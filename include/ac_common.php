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
if ( $fdebug != false ) {
	fwrite($fdebug,date("Ymd H:i:s").$p_log."\n");
	fclose($fdebug);
}	
 
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
/* function 
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
function html_page_start($p_theme,$p_script="")
{	
  include_once ("postgres.php");
  $cn=DbConnect();
$bg="";
  $Res=ExecSql($cn,"select the_filestyle from theme
                   where the_name='".$p_theme."'");
  if (pg_NumRows($Res)==0) $style="style.css";
  else {
    $s=pg_fetch_array($Res,0);
    $style=$s['the_filestyle'];
    // TO FIX
    // Ugly but I cannot change the background color thanks CSS !!!!
    $bg="bgcolor=white";
    if ($style=="style-aqua.css") $bg="bgcolor=\"#E3F0FF\"";
    if ($style=="style-elegant.css") $bg="bgcolor=\"#E3F0FF\"";
  }
 	echo '<!DOCTYPE HTML PUBLIC "-//W3C/DTD HTML 3.2 FINAL//EN">';
	echo "<HTML>";
	echo "<HEAD> 
              <TITLE> Gnu Accountancy</TITLE>
               <LINK REL=\"stylesheet\" type=\"text/css\" href=\"$style\">
	      </HEAD>";
	echo "<BODY $bg class=\"defaut\" $p_script>";
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
 * Purpose : 
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
/* function Redirect
 * Purpose : redirect following the user's profile
 *        
 * parm : 
 *	- p_profile (ac_users.use_usertype)
 *      - php_session PHPSESSID
 * gen :
 *	- none
 * return: none
 */
function Redirect($p_profile,$php_session) {
  include_once("debug.php");
  echo_debug("Session is $php_session");

  if ( $p_profile =="compta" ) {
    $URL="compta_login.php";
  } else if ( $p_profile == "user") {
    $URL="user_login.php";
  }
  else {
    echo_error ("Undefined profile");
    exit (-1);
  }
  echo "<META HTTP-EQUIV=\"REFRESH\" content=\"0;url=$URL?PHPSESSID=$php_session\">";
  
}
/* function ShowItem($p_array) */
/* purpose : store the string which print */
/*           the content of p_array in a table */
/*           used to display the menu */
/* parameter : array */
/* return : string */
function ShowItem($p_array,$p_dir='V')
{
  $ret="<TABLE>";
  // direction Vertical
  if ( $p_dir == 'V') {
    foreach ($p_array as $all=>$href){
      $ret.='<TR><TD CLASS="mtitle"><A class="mtitle" HREF="'.$href[0].'">'.$href[1].'</A></TD></TR>';
    }
  }
      //direction Horizontal
  else if ( $p_dir == 'H' ) {
    $ret.="<TR>";
    foreach ($p_array as $all=>$href){

      $ret.='<TD CLASS="mtitle"><A class="mtitle" HREF="'.$href[0].'">'.$href[1].'</A></TD>';
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

?>
