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
// Auteur Dany De Bontridder ddebontridder@yahoo.fr
/* $Revision$ */
include_once ("ac_common.php");
html_page_start();
if ( ! isset ( $g_dossier ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}
include_once ("postgres.php");
/* Admin. Dossier */
CheckUser();
include ("check_priv.php");
include_once("preference.php");


include_once ("top_menu_compta.php");
ShowMenuCompta($g_dossier);
ShowMenuComptaRight($g_dossier);
if ( CheckAdmin() == 0 ) {
  $r=CheckAction($g_dossier,$user,CENTRALIZE);
  if ($r == 0 ){
    /* Cannot Access */
    NoAccess();
  }
}
include_once("central_inc.php");

$l_Db=sprintf("dossier%d",$g_dossier);
$cn=DbConnect($l_Db);
echo '<DIV CLASS="ccontent">';
if ( isset ($_POST["central"] )) {

  //demande centralise
  if ( $_POST["periode"] != "" ) {
    $ret=Centralise($cn,$_POST["periode"]);
    if ($ret==NOERROR) {
      echo '<H2 class="info">La période '.$_POST["periode"].' est centralisée</H2>';
    } else {
      echo '<H2 class="error">La période '.$_POST["periode"].' n\' a pu être centralisée</H2>';
    }
  } 
}// if ( isset ($_POST["central"] ))

$ret=FormPeriode($cn,0,NOTCENTRALIZED);
if ( $ret != null) {
  echo '<FORM METHOD="POST" action="central.php">';
  echo $ret;
  echo '<INPUT TYPE="SUBMIT" name="central" VALUE="Centralise">';
  echo '</FORM>';
} else {
  echo '<H2 class="info"> Aucune période à centraliser</H2>';
}

echo "</DIV>";
html_page_stop();
?>
