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

include_once('postgres.php');
include_once('class_fiche.php');
include_once("class_widget.php");

$cn=DbConnect($_SESSION['g_dossier']);
$fiche_def=new fiche_def($cn);


$fiche_def->GetAll();

$i=0;
foreach ($fiche_def->all as $l_fiche) {
  $a[$i]=array("user_impress.php?type=fiche&fd_id=".$l_fiche->id,$l_fiche->label);
  $i++;
}
echo ShowItem($a,'V');
////////////////////////////////////////////////////////////////////////////////
if  ( isset ($_GET['fd_id'])) {
  echo '<div class="u_redcontent">';
  $submit=new widget();
  $hid=new widget("hidden");
  $fiche_id=new widget("hidden");
  
  echo '<form method="POST" ACTION="fiche_csv.php">'.
    $submit->Submit('bt_csv',"Export CSV").
    $hid->IOValue("type","fiche").
    $fiche_id->IOValue("fd_id",$_GET['fd_id']);

  echo "</form>";
  
  $fiche_def->id=$_GET['fd_id'];
  $fiche=new fiche($cn);
  $e=$fiche->GetByType($fiche_def->id);
  $l=var_export($e,true);
  echo_debug(__FILE__,__LINE__,$l);
  $old=-1;
  echo "<TABLE>";
  echo "<TR>";
  $fiche_def->GetAttribut();
  foreach ($fiche_def->attribut as $attribut) 
    echo "<TH>".$attribut->ad_text."</TH>";
  echo "<TR></TR>";
  if ( count($e) != 0 ) {
    foreach ($e as $detail) {
      echo "<TR>";
      foreach ( $detail->attribut as $dattribut ) {
	echo "<TD>".$dattribut->av_text."</TD>";
      }
      echo "</TR>";
    }
  }
  echo "</TABLE>";
  echo "</div>";
 }
?>