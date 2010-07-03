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
 * \brief send the account list in PDF
 */
include_once("class_acc_account_ledger.php");
include_once("ac_common.php");
require_once('class_database.php');
include_once("impress_inc.php");
require_once("class_fiche.php");
require_once ('header_print.php');
require_once('class_dossier.php');
require_once('class_pdf.php');
$gDossier=dossier::id();

$cn=new Database($gDossier);

extract($_GET);

$ret="";
$pdf= new PDF($cn);
$pdf->setDossierInfo("  Periode : ".$_GET['from_periode']." - ".$_GET['to_periode']);
$pdf->AliasNbPages();
$pdf->AddPage();


$Fiche=new Fiche($cn,$f_id);


list($array,$tot_deb,$tot_cred)=$Fiche->get_row_date($from_periode,$to_periode);
// don't print empty account
if ( count($array) == 0 ) {
  exit;
}
$size=array(13,25,20,80,12,20,20);
$align=array('L','C','C','L','R','R','R');

$Libelle=sprintf("(%s) %s ",$Fiche->id,$Fiche->getName());
$pdf->SetFont('DejaVu','',10);
$pdf->Cell(0,8,$Libelle,1,0,'C');  
$pdf->Ln();


  $pdf->SetFont('DejaVuCond','',8);
  $l=0;
  $pdf->Cell($size[$l],6,'Date',0,0,'L');$l++;
  $pdf->Cell($size[$l],6,'Ref',0,0,'C');$l++;
  $pdf->Cell($size[$l],6,'Journal',0,0,'C');$l++;
  $pdf->Cell($size[$l],6,'Libellé',0,0,'L');$l++;
  $pdf->Cell($size[$l],6,'Let',0,0,'R');$l++;
  $pdf->Cell($size[$l],6,'Debit',0,0,'R');$l++;
  $pdf->Cell($size[$l],6,'Credit',0,0,'R');$l++;
  $pdf->ln();
  $tot_deb=0;$tot_cred=0;
  for ($e=0;$e<count($array);$e++) {
    $l=0;
    $row=$array[$e];
    $date=shrink_date($row['j_date_fmt']);
    $pdf->Cell($size[$l],6,$date,0,0,$align[$l]);$l++;
    $pdf->Cell($size[$l],6,$row['jr_internal'],0,0,$align[$l]);$l++;
    $pdf->Cell($size[$l],6,substr($row['jrn_name'],0,14),0,0,$align[$l]);$l++;
    $pdf->Cell($size[$l],6,$row['description'],0,0,$align[$l]);$l++;
    $pdf->Cell($size[$l],6,(($row['letter']!=-1)?$row['letter']:''),0,0,$align[$l]);$l++;
    $pdf->Cell($size[$l],6,(sprintf('% 12.2f',$row['deb_montant'])),0,0,$align[$l]);$l++;
    $pdf->Cell($size[$l],6,(sprintf('% 12.2f',$row['cred_montant'])),0,0,$align[$l]);$l++;
    $pdf->ln();
    $tot_deb+=$row['deb_montant'];
    $tot_cred+=$row['cred_montant'];

  }
  $str_debit=sprintf("% 12.2f €",$tot_deb);
  $str_credit=sprintf("% 12.2f €",$tot_cred);
  $diff_solde=$tot_deb-$tot_cred;
  if ( $diff_solde < 0 ) {
    $solde=" créditeur ";
    $diff_solde*=-1;
  } else 
    {
      $solde=" débiteur ";
    }
 $str_diff_solde=sprintf("%12.2f €",$diff_solde);
 
 $pdf->SetFont('DejaVu','B',8);
 
 $pdf->Cell(160,5,'Débit',0,0,'R');
 $pdf->Cell(30,5,$str_debit,0,0,'R');$pdf->Ln();
 $pdf->Cell(160,5,'Crédit',0,0,'R');
 $pdf->Cell(30,5,$str_credit,0,0,'R');$pdf->Ln();
 $pdf->Cell(160,5,'Solde '.$solde,0,0,'R');
 $pdf->Cell(30,5,$str_diff_solde,0,0,'R');$pdf->Ln();
    
$fDate=date('dmy-Hi');
$pdf->Output('fiche-'.$fDate.'.pdf','I');


?>
