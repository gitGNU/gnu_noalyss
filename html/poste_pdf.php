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
require_once("poste.php");
require_once ('header_print.php');
require_once('class_dossier.php');
require_once('class_user.php');

$gDossier=dossier::id();

/* Security */
$cn=DbConnect($gDossier);
$User=new User($cn);
$User->Check();
$User->check_dossier($gDossier);
$User->can_request(IMPPOSTE,0);

extract($_POST);

 if ( isset ( $poste_fille) ){ //choisit de voir tous les postes
   $a_poste=get_array($cn,"select pcm_val from tmp_pcmn where pcm_val::text like '$poste_id%'");
 } else 
 $a_poste=get_array($cn,"select pcm_val from tmp_pcmn where pcm_val::text = '$poste_id'");
      

$ret="";
$pdf=new Cezpdf();
$pdf->selectFont('./addon/fonts/Helvetica.afm');
header_pdf($cn,$pdf);

if ( count($a_poste) == 0 ) {
  $pdf->ezStream();
     return;
}

foreach ($a_poste as $poste) 
{
  echo_debug("poste_pdf",__LINE__,$poste);
  $Poste=new Acc_Account_Ledger($cn,$poste['pcm_val']);
  list($array,$tot_deb,$tot_cred)=$Poste->get_row($from_periode,$to_periode);
  // don't print empty account
  if ( count($array) == 0 ) {
     continue;
   }
  $Libelle=sprintf("(%s) %s ",$Poste->id,$Poste->get_name());
    
    //  $pdf->ezText($Libelle,30);
  $pdf->ezTable($array,
		array ('jr_internal'=>'Operation',
		       'j_date' => 'Date',
		       'jrn_name'=>'Journal',
		       'description'=>'Description',
		       'deb_montant'=> 'Montant',
		       'cred_montant'=> 'Montant'
		       ),utf8_decode($Libelle),
		array('shaded'=>1,'showHeadings'=>1,'width'=>500,
		      'cols'=>array('montant'=> array('justification'=>'right'),
				    )),true);
  $str_debit=utf8_decode(sprintf("Débit  % 12.2f",$tot_deb));
  $str_cred=utf8_decode(sprintf("Crédit % 12.2f",$tot_cred));
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
}    

$pdf->ezStream();

?>
