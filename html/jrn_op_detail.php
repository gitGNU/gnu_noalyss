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
include_once ("user_common.php");
html_page_start($_SESSION['g_theme']);
if ( ! isset ( $_SESSION['g_dossier'] ) ) {
  echo "You must choose a Dossier ";
  //  phpinfo();
  exit -2;
}
include_once ("postgres.php");
include_once("jrn.php");
/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();

if ( isset( $_GET['$p_jrn'] )) {
  $p_jrn=$_GE['p_jrn'];
  $_SESSION[ "p_jrn"]=$p_jrn;

}
$cn=DbConnect($_SESSION['g_dossier']);
$jrn_op=$_GET['jrn_op'];
list ($l_array,$max_deb,$max_cred)=GetData($cn,$jrn_op);
foreach ($l_array as $key=>$element) {
  ${"e_$key"}=$element;
  echo_debug('jrn_op_detail.php',__LINE__,"jrn_op_detail.php e_$key =$element");
}

echo '<div align="center"> Opération </div>';

echo 'Date : '.$e_op_date;
echo '<div style="border-style:solid;border-width:1pt;">';
echo $e_comment;
echo '</DIV>';

if ( isset ($e_ech) ) {
  echo "<DIV> Echeance $e_ech </DIV>";
}
echo '<div style="background-color:#BFC2D5;">';

for ( $i = 0; $i < $max_deb;$i++) {
  $lib=GetPosteLibelle($_SESSION['g_dossier'],${"e_class_deb$i"}); 
  echo ${"e_class_deb$i"}." $lib    "."<B>".${"e_mont_deb$i"}."</B>.<br>";

}
  echo "</div>";
  echo '<div style="background-color:#E8F4FF;margin-left:50px;">';
for ( $i = 0; $i < $max_cred;$i++) {
  $lib=GetPosteLibelle($_SESSION['g_dossier'],${"e_class_cred$i"});

  echo ${"e_class_cred$i"}."  $lib   "."<B>".${"e_mont_cred$i"}."</B><br>";
}
  echo '</div>';

$a=GetConcerned($cn,$e_jr_id);

if ( $a != null ) {
    echo "operation concernée <br>";
    echo '<div style="margin-left:30px;">';
  foreach ($a as $key => $element) {

    echo "<A class=\"detail\" HREF=\"jrn_op_detail.php?jrn_op=".GetGrpt($cn,$element)."\"> ".GetInternal($cn,$element)."</A>";
  }//for
  echo "</div>";
}// if ( $a != null ) {
?>
<input type="button" onClick="window.close()" value="Fermer">
<?
html_page_stop();
?>
