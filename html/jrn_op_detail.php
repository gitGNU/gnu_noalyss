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
include_once ("poste.php");
html_page_start($g_UserProperty['use_theme']);
if ( ! isset ( $g_dossier ) ) {
  echo "You must choose a Dossier ";
  phpinfo();
  exit -2;
}
include_once ("postgres.php");
include_once("jrn.php");
/* Admin. Dossier */
CheckUser();

if ( isset( $p_jrn )) {
  session_register("g_jrn");
  $g_jrn=$p_jrn;
}
$l_Db=sprintf("dossier%d",$g_dossier);
$cn=DbConnect($l_Db);

list ($l_array,$max_deb,$max_cred)=GetData($cn,$jrn_op);
foreach ($l_array as $key=>$element) {
  ${"e_$key"}=$element;
  echo_debug("e_$key =$element");
}

echo '<div> Op�ration '.$l_array['jr_internal']."</div>";

  echo 'Date : '.$e_op_date;
if ( isset ($e_ech) ) {
  echo "<DIV> Echeance $e_ech </DIV>";
}
for ( $i = 0; $i < $max_deb;$i++) {
  $lib=GetPosteLibelle($g_dossier,${"e_class_deb$i"}); 
  echo '<div style="background-color:lightblue;">';
  echo ${"e_class_deb$i"}." $lib    "."<B>".${"e_mont_deb$i"}."</B>";
  echo "</div>";
}
for ( $i = 0; $i < $max_cred;$i++) {
  $lib=GetPosteLibelle($g_dossier,${"e_class_cred$i"});
  echo '<div style="background-color:lightgreen;">';
  echo ${"e_class_cred$i"}."  $lib   "."<B>".${"e_mont_cred$i"}."</B>";
  echo '</div>';
}
echo "operation concern�e $e_rapt";
echo '<div style="border-style:solid;border-width:1pt;">';
echo $e_comment;
echo '</DIV>';
?>
<input type="button" onClick="window.close()" value="Fermer">
<?
html_page_stop();
?>
