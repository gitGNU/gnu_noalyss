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
/*! \file
 * \brief Print the balance in pdf format
 * \param received parameters
 * \param e_date element 01.01.2003
 * \param e_client element 3
 * \param nb_item element 2
 * \param e_march0 element 11
 * \param e_quant0 element 1
 * \param e_march1 element 6
 * \param e_quant1 element 2
 * \param e_comment  invoice number
 */
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
// $Revision$
 
include_once("ac_common.php");
require_once('class_database.php');
include_once("class_acc_balance.php");
require_once ('header_print.php');
require_once('class_dossier.php');
require_once('class_pdf.php');
$gDossier=dossier::id();

$cn=new Database($gDossier);
$rep=new Database();
include ('class_user.php');
$User=new User($rep);
$User->Check();

$bal=new Acc_Balance($cn);
$User->can_request(IMPBAL,1);

extract ($_GET);

$bal->jrn=(isset($_GET['p_jrn']))?$_GET['p_jrn']:null;
$bal->from_poste=$_GET['from_poste'];
$bal->to_poste=$_GET['to_poste'];

$array=$bal->get_row($from_periode,$to_periode);

if ( sizeof($array)  == 0 ) {
  exit();
  
 }

$pPeriode=new Periode($cn);
$a=$pPeriode->get_date_limit($from_periode);
$b=$pPeriode->get_date_limit($to_periode);
$per_text="  du ".$a['p_start']." au ".$b['p_end'];
$pdf= new PDF($cn);
$pdf->setDossierInfo(" Balance  ".$per_text);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('DejaVuCond','',7);
$pdf->Cell(30,6,'poste');
$pdf->Cell(80,6,'Libellé');
$pdf->Cell(20,6,'Total Débit',0,0,'R');
$pdf->Cell(20,6,'Total Crédit',0,0,'R');
$pdf->Cell(20,6,'Solde Débiteur',0,0,'R');
$pdf->Cell(20,6,'Solde Créditeur',0,0,'R');
$pdf->Ln();

$pdf->SetFont('DejaVuCond','',8);
$tp_deb=0;$tp_cred=0;$tp_sold=0;$tp_solc=0;
for ($i=0;$i<count($array);$i++){
  if ( $i % 2 == 0 ) {
    $pdf->SetFillColor(220,221,255);
    $fill=1;
  } else {
    $pdf->SetFillColor(0,0,0);
    $fill=0;
  }
  if ( ! isset($array[$i]))continue;
  $pdf->Cell(30,6,$array[$i]['poste'],0,0,'L',$fill);
  $pdf->Cell(80,6,$array[$i]['label'],0,0,'L',$fill);
  $pdf->Cell(20,6,$array[$i]['sum_deb'],0,0,'R',$fill);
  $pdf->Cell(20,6,$array[$i]['sum_cred'],0,0,'R',$fill);
  $pdf->Cell(20,6,$array[$i]['solde_deb'],0,0,'R',$fill);
  $pdf->Cell(20,6,$array[$i]['solde_cred'],0,0,'R',$fill);
  $pdf->Ln();
  $tp_deb+=$array[$i]['sum_deb'];
  $tp_cred+=$array[$i]['sum_cred'];
  $tp_sold+=$array[$i]['solde_deb'];
  $tp_solc+=$array[$i]['solde_cred'];

}
// Totaux
$pdf->SetFont('DejaVuCond','B',8);
$pdf->Cell(110,6,'Totaux');
$pdf->Cell(20,6,$tp_deb,'T',0,'R',0);
$pdf->Cell(20,6,$tp_cred,'T',0,'R',0);
$pdf->Cell(20,6,$tp_sold,'T',0,'R',0);
$pdf->Cell(20,6,$tp_solc,'T',0,'R',0);
$pdf->Ln();


$fDate=date('dmy-Hi');
$pdf->Output('balance-'.$fDate.'.pdf','I');



?>
