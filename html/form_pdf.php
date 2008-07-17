<?php

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
/*! \file
 * \brief Send a report in PDF
 */

include_once("class_acc_report.php");
include_once("ac_common.php");
include_once("postgres.php");
include_once("class.ezpdf.php");
include_once("impress_inc.php");
require_once('class_user.php');
require_once ('header_print.php');
require_once('class_dossier.php');
require_once('class_acc_report.php');

$gDossier=dossier::id();

$cn=DbConnect($gDossier);

foreach ($_GET as $key=>$element) {
  ${"$key"}=$element;
}

$ret="";
$pdf=new Cezpdf();
$pdf->selectFont('./addon/fonts/Helvetica.afm');
header_pdf($cn,$pdf);

$Form=new Acc_Report($cn,$form_id);
// Step ??
//--
if ( $_GET['p_step'] == 0 ) 
  {
    // No step asked
    //--
	if ( $_GET ['type_periode'] == 0 )
	  $array=$Form->get_row( $_GET['from_periode'],$_GET['to_periode'], $_GET['type_periode']);
	else 
	  $array=$Form->get_row( $_GET['from_date'],$_GET['to_date'], $_GET['type_periode']);

  }
 else 
   {
     // yes with step
     //--
     for ($e=$_GET['from_periode'];$e<=$_GET['to_periode'];$e+=$_GET['p_step'])
       {
		 $periode=getPeriodeName($cn,$e);
		 if ( $periode == null ) continue;
		 $array[]=$Form->get_row($e,$e);
		 $periode_name[]=$periode;
       }

   }

$Libelle=utf8_decode(sprintf("(%s) %s ",$Form->id,$Form->get_name()));
    
$pdf->ezText($Libelle,30);
// without step 
if ( $_GET['p_step'] == 0 ) 
  {
	if ( $_GET['type_periode'] == 0 ) {
	  $q=getPeriodeName($cn,$from_periode);
	  if ( $from_periode != $to_periode){
		$periode=sprintf("Période %s à %s",$q,getPeriodeName($cn,$to_periode));
	  } else {
		$periode=sprintf("Période %s",$q);
	  }
    } else {
	  $periode=sprintf("Date %s jusque %s",$_GET['from_date'],$_GET['to_date']);
  }
	$periode=utf8_decode($periode);
    $pdf->ezText($periode,25);
    $pdf->ezTable($array,
		  array ('desc'=>'Description',
			 'montant' => 'Montant'
		       ),$Libelle,
		  array('shaded'=>0,'showHeadings'=>1,'width'=>500,
			'cols'=>array('montant'=> array('justification'=>'right'),
				      )),true);
    //New page
    //$pdf->ezNewPage();
    //}    
  } 
 else 
   { // With Step 
     $a=0;
     foreach ($array as $e) 
       {
		 $pdf->ezText($periode_name[$a],25);
		 $a++;
		 $pdf->ezTable($e,
					   array ('desc'=>'Description',
							  'montant' => 'Montant'
							  ),$Libelle,
					   array('shaded'=>0,'showHeadings'=>1,'width'=>500,
							 'cols'=>array('montant'=> array('justification'=>'right'),
								       )),true);
       }
   }
$pdf->ezStream();

?>
