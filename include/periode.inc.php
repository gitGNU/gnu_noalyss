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
/*! \file
 * \brief Still used ?
 * \todo check if obsolete
 */

require_once("preference.php");
$cn=DbConnect($_SESSION['g_dossier']);
//-----------------------------------------------------
// Periode
//-----------------------------------------------------
$action="";
if ( isset($_REQUEST['action'])) 
     $action=$_REQUEST['action'];

if ( $action=="change_per") {
  foreach($HTTP_GET_VARS as $key=>$element) 
    ${"$key"}=$element;
  echo "<TABLE>";
  echo '<TR> <FORM ACTION="user_advanced.php?p_action=periode" METHOD="POST">';
  echo ' <INPUT TYPE="HIDDEN" NAME="p_per" VALUE="'.$p_per.'">';
  echo '<TD> <INPUT TYPE="text" NAME="p_date_start" VALUE="'.$p_date_start.'"></TD>';
  echo '<TD> <INPUT TYPE="text" NAME="p_date_end" VALUE="'.$p_date_end.'"></TD>';
  echo '<TD> <INPUT TYPE="text" NAME="p_exercice" VALUE="'.$p_exercice.'"></TD>';
  echo '<TD> <INPUT TYPE="SUBMIT" NAME="conf_chg_per" Value="Change"</TD>';
  echo '</FORM></TR>';
  echo "</TABLE>";

}
if ( isset ($_POST["conf_chg_per"] ) ) {
  foreach($HTTP_POST_VARS as $key=>$element) 
    ${"$key"}=$element;
  if (isDate($p_date_start) == null ||
      isDate($p_date_end) == null ||
      strlen (trim($p_exercice)) == 0 ||
     (string) $p_exercice != (string)(int) $p_exercice)
    { 
      echo "<H2 class=\"error\"> Valeurs invalides</H2>";
      ShowPeriode($cn);
      return;
    }
  $Res=ExecSql($cn," update parm_periode ".
	       "set p_start=to_date('". $p_date_start."','DD.MM.YYYY'),".
	       " p_end=to_date('". $p_date_end."','DD.MM.YYYY'),".
	       " p_exercice='".$p_exercice."'".
	       " where p_id=".$p_per);



}
if ( isset ($_POST["add_per"] )) {
  foreach($HTTP_POST_VARS as $key=>$element) 
    ${"$key"}=$element;
  if (isDate($p_date_start) == null ||
      isDate($p_date_end) == null ||
      strlen (trim($p_exercice)) == 0 ||
     (string) $p_exercice != (string)(int) $p_exercice)
    { 
      echo "<H2 class=\"error\"> Valeurs invalides</H2>";
      ShowPeriode($cn);
      return;
    }
  $Res=ExecSql($cn,sprintf(" insert into parm_periode(p_start,p_end,p_closed,p_exercice)".
			   "values (to_date('%s','DD.MM.YYYY'),to_date('%s','DD.MM.YYYY')".
			   ",'f','%s')",
			   $p_date_start,
			   $p_date_end,
			   $p_exercice));


}

echo_debug('periode.inc',__LINE__,"Action $p_action");
if ( $action=="closed") {
  $p_per=$_GET['p_per'];
  $Res=ExecSql($cn,"update parm_periode set p_closed=true where p_id=$p_per");
}

if ( $action== "delete_per" ) {
  $p_per=$_GET["p_per"];
// Check if the periode is not used
  if ( CountSql($cn,"select * from jrnx where j_tech_per=$p_per") != 0 ) {
  echo '<h2 class="error"> Désolé mais cette période est utilisée</h2>';
  } else
  {
  $Res=ExecSql($cn,"delete from parm_periode where p_id=$p_per");
  }
}


ShowPeriode($cn);

?>