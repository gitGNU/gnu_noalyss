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
include_once("postgres.php");
include_once("class.ezpdf.php");
include_once("impress_inc.php");
require_once("class_fiche.php");
require_once ('header_print.php');
require_once('class_dossier.php');
$gDossier=dossier::id();

$cn=DbConnect($gDossier);
foreach ($_POST as $key=>$element) {
  ${"$key"}=$element;
}

$ret="";
$pdf= new Cezpdf();
$pdf->selectFont('./addon/fonts/Helvetica.afm');


echo_debug("quick_code_pdf",__LINE__,$f_id);
$Fiche=new fiche($cn,$f_id);


list($array,$tot_deb,$tot_cred)=$Fiche->get_row($from_periode,$to_periode);
// don't print empty account
if ( count($array) == 0 ) {
  continue;
}
$Libelle=sprintf("(%s) %s ",$Fiche->id,$Fiche->getName());
header_pdf($cn,$pdf);
//  $pdf->ezText($Libelle,30);
$pdf->ezTable($array,
	      array ('jr_internal'=>'Opération',
		     'j_date' => 'Date',
		     'jrn_name'=>'Journal',
		     'description'=>'Description',
		     'deb_montant'=> 'Montant',
		     'cred_montant'=> 'Montant'
		     ),$Libelle,
	      array('shaded'=>0,'showHeadings'=>1,'width'=>500,
		    'cols'=>array('montant'=> array('justification'=>'right'),
				  )));
$str_debit=sprintf("Débit  % 12.2f",$tot_deb);
$str_cred=sprintf("Crédit % 12.2f",$tot_cred);
$diff_solde=$tot_deb-$tot_cred;
if ( $diff_solde < 0 ) {
  $solde=" C ";
  $diff_solde*=-1;
} else 
	{
	  $solde=" D ";
	}
$str_solde=sprintf(" Solde %s %12.2f",$solde,$diff_solde);

$pdf->ezText($str_debit,10,array('justification'=>'right'));
$pdf->ezText($str_cred,10,array('justification'=>'right'));
$pdf->ezText($str_solde,14,array('justification'=>'right'));

//New page
$pdf->ezNewPage();
header_pdf($cn,$pdf);
  

$pdf->ezStream();

?>
