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
// $Revision$

include_once("class_rapport.php");
include_once("ac_common.php");
include_once("postgres.php");
include_once("class.ezpdf.php");
include_once("impress_inc.php");

    $cn=DbConnect($_SESSION['g_dossier']);
foreach ($HTTP_POST_VARS as $key=>$element) {
  ${"$key"}=$element;
}

$ret="";
$pdf=& new Cezpdf();
$pdf->selectFont('./addon/fonts/Helvetica.afm');

$Form=new rapport($cn,$form_id);
$array=$Form->GetRow($from_periode,$to_periode);

$Libelle=sprintf("(%s) %s ",$Form->id,$Form->GetName());
    
    $pdf->ezText($Libelle,30);
$q=getPeriodeName($cn,$from_periode);
if ( $from_periode != $to_periode){
  $periode=sprintf("Période %s à %s",$q,getPeriodeName($cn,$to_periode));
 } else {
  $periode=sprintf("Période %s",$q);
 }
$pdf->ezText($periode,25);
    $pdf->ezTable($array,
		  array ('desc'=>'Description',
		       'montant' => 'Montant'
		       ),$Libelle,
		array('shaded'=>0,'showHeadings'=>1,'width'=>500,
		      'cols'=>array('montant'=> array('justification'=>'right'),
				    )));
  //New page
  //$pdf->ezNewPage();
//}    

$pdf->ezStream();

?>
