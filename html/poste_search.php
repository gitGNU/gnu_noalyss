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
include_once ("postgres.php");
/* Admin. Dossier */
CheckUser();
echo '<SCRIPT LANGUAGE="javascript" SRC="win_search_poste.js"></SCRIPT>';

if ( ! isset ( $g_dossier ) ) {
  echo "You must choose a Dossier ";
  phpinfo();
  exit -2;
}

// $opt='<OPTION VALUE="<="> commence par';
// $opt.='<OPTION VALUE=">"> >';
// $opt.='<OPTION VALUE=">="> >=';

$c_comment="";
$c_class="";

$l_Db=sprintf("dossier%d",$g_dossier);
$condition="";
$cn=DbConnect($l_Db);
if ( isset($_POST['search']) ) {
  $c1=0;
  foreach( $HTTP_POST_VARS as $key=>$element){
    ${"$key"}=$element;
  }
  if ( strlen(trim($p_comment)) != 0 ) {
    $c_comment=" where upper(pcm_lib) like upper('%$p_comment%')";
    $c1=1;
  }
  if ( strlen(trim($p_class)) != 0 &&
       (string) $p_class == (int)(string) $p_class) {
    if ($c1==1) 
      $clause=" and ";
    else
      $clause = " where ";
    $c_class=sprintf(" %s pcm_val::text like '%s%%'",$clause,$p_class);

    }
  

  $condition=$c_comment.$c_class;
}
echo_debug("condition = $condition");
echo '<FORM ACTION="poste_search.php" METHOD="POST">';
echo '<TABLE>';
echo '<TR>';

echo '<TD>Poste Comptable Commence par  </TD>';

if ( ! isset ($p_class) ) $p_class="";
$opt=" <INPUT TYPE=\"text\" value=\"$p_class\" name=\"st_with\">";
echo '<TD> <INPUT TYPE="text" name="p_class" VALUE="'.$p_class.'"></TD>';

echo '<TD> Libellé </TD>';
echo '<TD> contient </TD>';
if ( ! isset ($p_comment) ) $p_comment="";
echo '<TD> <INPUT TYPE="text" name="p_comment" VALUE="'.$p_comment.'"></TD>';
echo '</TABLE>';
echo '<INPUT TYPE="submit" name="search" value="cherche">';
echo '</FORM>';

$Res=ExecSql($cn,"select * from tmp_pcmn $condition order by pcm_val::text");

$MaxLine=pg_NumRows($Res);
if ( $MaxLine==0) { 
  html_page_stop();
  return;
}
echo '<TABLE ALIGN="center" BORDER="0">';
$l_id="";

for ( $i=0; $i < $MaxLine; $i++) {
  $l_line=pg_fetch_array($Res,$i);
  echo "<TR>";
    echo '<TD>';
    echo $l_line['pcm_val'];
    echo '</TD>';

    echo '<TD>';
    echo $l_line['pcm_lib'];
    echo '</TD>';
    echo "</TR>";

}
  
  echo '</TABLE>';
echo '<INPUT TYPE="BUTTON" Value="Close" onClick=\'GetIt()\'>';
html_page_stop();
?>
