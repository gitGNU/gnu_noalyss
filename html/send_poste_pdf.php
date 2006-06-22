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
/*! \file
 * \brief Print the account in pdf
 */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
// $Revision$

    include_once("jrn.php");
    include_once("ac_common.php");
    include_once("postgres.php");
    include_once("class.ezpdf.php");
    include_once("impress_inc.php");
include("poste.php");
    echo_debug('send_poste_pdf.php',__LINE__,"imp pdf journaux");
    $cn=DbConnect($g_dossier);
foreach ($HTTP_POST_VARS as $key=>$element) {
  ${"$key"}=$element;
}
if ( isset ( $all_poste) ){ //choisit de voir tous les postes
  $r_poste=ExecSql($cn,"select pcm_val from tmp_pcmn where pcm_val = any ".
		   " (select j_poste from jrnx) order by pcm_val::text");
  $nPoste=pg_numRows($r_poste);
  for ( $i=0;$i<$nPoste;$i++) {
    $t_poste=pg_fetch_array($r_poste,$i);
    $poste[]=$t_poste['pcm_val'];
  } 
}      


    $ret="";
    $pdf=& new Cezpdf();
    $pdf->selectFont('./addon/fonts/Helvetica.afm');
$cond=CreatePeriodeCond($periode);
//$rap_deb=0;$rap_cred=0;
for ( $i =0;$i<count($poste);$i++) {
  
    list($array,$tot_deb,$tot_cred)=GetDataPoste($cn,$poste[$i],$cond);
    // don't print empty account
    if ( count($array) == 0 ) {
    continue;
    }
    $Libelle=sprintf("(%s) %s ",$poste[$i],GetPosteLibelle($cn,$poste[$i],1));
    
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
  //$pdf->ezNewPage();
}    

$pdf->ezStream();

?>
