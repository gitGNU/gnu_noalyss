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
/* $Revision$ */
include_once ("ac_common.php");
html_page_start($g_UserProperty['use_theme']);
if ( ! isset ( $g_dossier ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}
include_once ("postgres.php");
/* Admin. Dossier */
CheckUser();
include ("check_priv.php");
include_once ("top_menu_compta.php");
ShowMenuCompta($g_dossier,$g_UserProperty);

// show sub menu
$result=ShowJrn("user_impress.php");
echo "<DIV class=\"u_subtmenu\">";
echo $result;

$p_array=array(array ("user_impress.php?type=jrn","Journaux"),
	       array("user_impress.php?type=poste","Poste"),
	       array("user_impress.php?type=form","Formulaire")
	       );
$default=( isset ($_GET['type']))?"user_impress.php?type=".$_GET['type']:"";
$result=ShowItem($p_array,'H',"cell","mtitle",$default);
echo $result;

echo "</DIV>";


include_once("impress_inc.php");

if ( $g_UserProperty['use_admin'] == 0 ) {
  if (CheckAction($g_dossier,$g_user,IMP) == 0 ){
    /* Cannot Access */
    NoAccess();
  }
}

$l_Db=sprintf("dossier%d",$g_dossier);
$cn=DbConnect($l_Db);
// something is choosen
$default=( isset ($_GET['type']))?$_GET['type']:"";
  switch ($default) {
  case "jrn":
    include ("impress_jrn.php");
    break;
  case "poste":
    include ("impress_poste.php");
    break;
  case "form":
    include ("impress_form.php");
    break;
  }

html_page_stop();
?>
