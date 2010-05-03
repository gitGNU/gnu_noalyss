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
/* $Revision$ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/*!\file
 * \brief Called by impress->category, export in PDF the history of a category
 * of card
 */

  // Security we check if user does exist and his privilege
require_once('class_user.php');
require_once('class_database.php');
require_once('class_pdf.php');
require_once('class_lettering.php');
require_once('class_dossier.php');

/* Security */
$gDossier=dossier::id();
$cn=new Database($gDossier);
$User=new User($cn);
$User->Check();
$User->check_dossier($gDossier);
$User->can_request(IMPFIC,0);

$pdf=new PDF($cn);
$pdf->setDossierInfo("  Periode : ".$_GET['start']." - ".$_GET['end']);
$pdf->AliasNbPages();
$pdf->AddPage();
$array=Fiche::get_fiche_def($cn,$_GET['cat']);
$name=$cn->get_value('select fd_label from fiche_def where fd_id=$1',array($_GET['cat']));
$pdf->SetFont('Arial','BI',14);
$pdf->Cell(0,8,$name,0,1,'C');
$pdf->SetTitle($name,1);
$pdf->SetAuthor('Phpcompta');
/*
 * You show now the result
 */
if ($array == null  ) {
  /**
   * @todo if no data send an empty PDF
   */
  //  echo '<h2 class="info2"> Aucune fiche trouvée</h2>';
  exit();
}
$tab=array(13,25,100,20,20,12);
$align=array('L','L','L','R','R','R');

foreach($array as $row) {
  $letter=new Lettering_Card($cn);
  $letter->set_parameter('quick_code',$row->strAttribut(ATTR_DEF_QUICKCODE));
  $letter->set_parameter('start',$_GET['start']);
  $letter->set_parameter('end',$_GET['end']);
  // all
  if ( $_GET['histo'] == 0 ) {
    $letter->get_all();
  }

   // lettered
  if ( $_GET['histo'] == 1 ) {
    $letter->get_letter();
  }
  // unlettered
  if ( $_GET['histo'] == 2 ) {
    $letter->get_unletter();
  }
  /* skip if nothing to display */
  if (count($letter->content) == 0 ) continue;
  $pdf->SetFont('Arial','',10);
  
  $pdf->Cell(0,7,$row->strAttribut(ATTR_DEF_NAME),1,1,'C');


  $pdf->Cell($tab[0],7,'Date');
  $pdf->Cell($tab[1],7,'ref');
  $pdf->Cell($tab[2],7,'Comm');
  $pdf->Cell(40,7,'Montant',0,0,'C');
  $pdf->Cell($tab[5],7,'Let.',0,0,'R');
  $pdf->ln();

  $amount_deb=0;$amount_cred=0;
  for ($i=0;$i<count($letter->content);$i++){
    if ( $i % 2 == 0 ) {
      $pdf->SetFillColor(220,221,255);
      $fill=1;
    } else {
      $pdf->SetFillColor(0,0,0);
      $fill=0;
    }
    $pdf->SetFont('Arial','',8);
    $row=$letter->content[$i];
    $date=str_replace('.','',$row['j_date_fmt']);
    $str_date=substr($date,0,4).substr($date,6,2);
    $pdf->Cell($tab[0],4,$str_date,0,0,$align[0],$fill);
    $pdf->Cell($tab[1],4,$row['jr_internal'],0,0,$align[1],$fill);
    $pdf->Cell($tab[2],4,$row['jr_comment'],0,0,$align[2],$fill);
    if ( $row['j_debit'] == 't') {
      $pdf->Cell($tab[3],4,sprintf('%10.2f',$row['j_montant']),0,0,$align[4],$fill);
      $amount_deb+=$row['j_montant'];
      $pdf->Cell($tab[4],4,"",0,0,'C',$fill);
    } else {
      $pdf->Cell($tab[3],4,"",0,0,'C',$fill);
      $pdf->Cell($tab[4],4,sprintf('%10.2f',$row['j_montant']),0,0,$align[4],$fill);
      $amount_cred+=$row['j_montant'];
    }
    if ($row['letter'] != -1 ) $pdf->Cell($tab[5],4,$row['letter'],0,0,$align[5],$fill); else $pdf->Cell($tab[5],4,"",0,0,'R',$fill);
    $pdf->Ln();
  }
  $pdf->SetFillColor(0,0,0);
  $pdf->SetFont('Arial','B',8);
  $debit =sprintf('Debit  : % 12.2f',$amount_deb);
  $credit=sprintf('Credit : % 12.2f',$amount_cred);
  if ( $amount_deb>$amount_cred) $s='solde débiteur'; else $s='solde crediteur';
  $solde =sprintf('%s  : % 12.2f',$s,(abs(round($amount_cred-$amount_deb,2))));

  $pdf->Cell(0,6,$debit,0,0,'R');$pdf->ln(4);
  $pdf->Cell(0,6,$credit,0,0,'R');$pdf->ln(4);
  $pdf->Cell(0,6,$solde,0,0,'R');$pdf->ln(4);

  $pdf->Ln();
}

//Save PDF to file
$pdf->Output("category.pdf", 'I');exit;