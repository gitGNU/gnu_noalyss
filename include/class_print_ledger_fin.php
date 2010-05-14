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
 * \brief print a listing of financial
 */
require_once('class_pdf.php');
class Print_Ledger_Financial extends PDF
{
  function __construct($p_cn,$p_jrn) {
    parent::__construct($p_cn,'L','mm','A4');
    $this->ledger=$p_jrn;
    $this->jrn_type=$p_jrn->get_type();
  }
  function Header() {
        //Arial bold 12
        $this->SetFont('DejaVu', 'B', 12);
        //Title
        $this->Cell(0,10,$this->dossier, 'B', 0, 'C');
        //Line break
        $this->Ln(20);
        $this->SetFont('DejaVu', '', 6);
	$this->Cell(30,'Piece');
	$this->Cell(10,'Date');
	$this->Cell(20,'Interne');
	$this->Cell(60,'Banque');
	$this->Cell(60,'Dest/Orig');
	$this->Cell(65,'Commentaire');
	$this->Cell(15,'Montant');
	$this->Ln(6);

    }
    function Footer() {
        //Position at 2 cm from bottom
        $this->SetY(-20);
        //Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        //Page number
        $this->Cell(0,8,'Date '.$this->date." - Page ".$this->PageNo().'/{nb}',0,0,'C');
	$this->Ln(3);
	// Created by PhpCompta
        $this->Cell(0,8,'Created by Phpcompta, the most professional opensource accounting software http://www.phpcompta.eu',0,0,'C',false,'http://www.phpcompta.eu');
    }
    /**
     *@brief print the pdf
     *@param
     *@param
     *@return
     *@see
     */
    function export() {
      $a_jrn=$this->ledger->get_operation($_GET['from_periode'],
					$_GET['to_periode']);
     $this->SetFont('DejaVu', '', 6);
      if ( $a_jrn == null ) return;
      for ( $i=0;$i<count($a_jrn);$i++)
	{
	  $row=$a_jrn[$i];
	  $this->Cell(30,5,$row['pj']);
	  $this->Cell(10,5,shrink_date($row['date_fmt']));
	  $this->Cell(20,5,$row['internal']);

	  list($qc,$name,$bank_qc,$bank_name)=$this->get_tiers($row['id'],$this->jrn_type);
	  $this->Cell(20,5,$bank_qc,0,0);
	  $this->Cell(40,5,$bank_name,0,0);
	  $this->Cell(20,5,$qc,0,0);
	  $this->Cell(40,5,$name,0,0);


	  $this->Cell(65,5,$row['comment'],0,0);
	  $amount=$this->cn->get_value('select qf_amount from quant_fin where jr_id=$1',array( $row['id']));
	  $this->Cell(15,5,sprintf('%.2f',$amount),0,0,'R');
	  $this->Ln(5);
	  
	}
    }
}
