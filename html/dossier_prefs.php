<?
/*
 *   This file is part of WCOMPTA.
 *
 *   WCOMPTA is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   WCOMPTA is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with WCOMPTA; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
/* $Revision$ */
// Auteur Dany De Bontridder ddebontridder@yahoo.fr
/* $Revision$ */
include_once ("ac_common.php");
html_page_start();
if ( ! isset ( $g_dossier ) ) {
  echo "You must choose a Dossier ";
  phpinfo();
  exit -2;
}
include_once ("postgres.php");
/* Admin. Dossier */
CheckUser();

include("preference.php");

include_once ("top_menu_compta.php");
ShowMenuCompta($g_dossier);
ShowMenuComptaRight($g_dossier);
include_once ("check_priv.php");

if ( $g_UserProperty['use_admin'] == 0 ) {
  $r=CheckAction($g_dossier,$g_user,PARM);
  if ($r == 0 ){
    /* Cannot Access */
    NoAccess();
  exit -1;			

  }
}
ShowMenuComptaLeft($g_dossier,MENU_PARAM);
$l_Db=sprintf("dossier%d",$g_dossier);
$p_action="";
$cn=DbConnect($l_Db);
if ( isset($_GET["p_action"]) ) {
  $p_action=$_GET["p_action"];
}


echo '<DIV CLASS="ccontent">';
// Devise 

$action="";
if ( isset ($_POST['action']) ) {
  $action=$_POST['action'];
}

if ( $p_action == "change" ) {
  echo '<TR> <FORM ACTION="dossier_prefs.php" METHOD="POST">';
  echo '<INPUT TYPE="HIDDEN" VALUE="'.$p_mid.'" NAME="p_id">';
  echo '<TD> <INPUT TYPE="text" NAME="p_devise" VALUE="'.$p_code.'"></TD>';
  echo '<TD> <INPUT TYPE="text" NAME="p_rate" VALUE="'.$p_rate.'"></TD>';
  echo '<TD> <INPUT TYPE="SUBMIT" NAME="action" Value="Change"</TD>';
  echo '</FORM></TR>';
}
if ( $p_action=="change_per") {
  foreach($HTTP_GET_VARS as $key=>$element) 
    ${"$key"}=$element;
  echo "<TABLE>";
  echo '<TR> <FORM ACTION="dossier_prefs.php" METHOD="POST">';
  echo ' <INPUT TYPE="HIDDEN" NAME="p_per" VALUE="'.$p_per.'">';
  echo '<TD> <INPUT TYPE="text" NAME="p_date_start" VALUE="'.$p_date_start.'"></TD>';
  echo '<TD> <INPUT TYPE="text" NAME="p_date_end" VALUE="'.$p_date_end.'"></TD>';
  echo '<TD> <INPUT TYPE="text" NAME="p_exercice" VALUE="'.$p_exercice.'"></TD>';
  echo '<TD> <INPUT TYPE="SUBMIT" NAME="chg_per" Value="Change"</TD>';
  echo '</FORM></TR>';
  echo "</TABLE>";

}
if ( isset ($_POST["chg_per"] ) ) {
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

  ShowPeriode($cn);

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

  ShowPeriode($cn);

}

echo_debug ("Action $action");
if ( $action == "Change") {
  $Res=ExecSql($cn,"update parm_money set pm_code='$p_devise',pm_rate=$p_rate where pm_id=$p_id");
  ShowDevise($cn);

}
if ( $action == "Ajout") {
  $Res=ExecSql($cn,"insert into parm_money ( pm_code,pm_rate) values ('$p_devise',$p_rate) ");
  ShowDevise($cn);

}



if ( $p_action=="devise") {
  ShowDevise($cn);
}
if ( $p_action=="closed") {
  $Res=ExecSql($cn,"update parm_periode set p_closed=true where p_id=$p_per");
  ShowPeriode($cn);
}

if ( $p_action== "delete_per" ) {
  $p_per=$_GET["p_per"];
  $Res=ExecSql($cn,"delete from parm_periode where p_id=$p_per");
  ShowPeriode($cn);
}

if ( $p_action == "periode" ) {
  ShowPeriode($cn);
}



echo "</DIV>";
html_page_stop();
?>
