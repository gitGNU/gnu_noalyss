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
/* received parameters
 e_date element 01.01.2003
 e_client element 3
 nb_item element 2
 e_march0 element 11
 e_quant0 element 1
 e_march1 element 6
 e_quant1 element 2
 e_comment  invoice number
*/
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
// $Revision$
 
include_once("ac_common.php");
include_once("postgres.php");
include_once("class.ezpdf.php");
include_once("poste.php");
include_once("class_balance.php");
include_once("preference.php");

$cn=DbConnect($_SESSION['g_dossier']);

$bal=new Balance($cn);

echo_debug(__FILE__,__LINE__,"imp pdf journaux");
foreach ($HTTP_POST_VARS as $key=>$element) {
  ${"$key"}=$element;
  echo_debug(__FILE__,__LINE__,"key => $key element $element");
}
// if centralized
$t_cent="";

if ( isset($central) ) {
    $bal->central='Y';
    $t_cent="centralisée";
 }
 else 
  $bal->central='N';

$array=$bal->GetRow($from_periode,$to_periode);

if ( sizeof($array)  == 0 ) {
  $pdf=& new Cezpdf('a4');
  $pdf->selectFont('./addon/fonts/Helvetica.afm');
  $pdf->ezSetCmMargins(2,2,2,2);
  $pdf->ezText("Balance compte -- vide");
  $pdf->ezStream();
  exit();
  
 }
$a=GetPeriode($cn,$from_periode);
$b=GetPeriode($cn,$to_periode);
$per_text=" période du ".$a['p_start']." au ".$b['p_end'];
$pdf=& new Cezpdf('a4');
$pdf->selectFont('./addon/fonts/Helvetica.afm');
$pdf->ezSetCmMargins(2,2,2,2);
$pdf->ezTable($array,array('poste'=>'Poste','label'=>'Libellé','sum_deb'=>'Total Débit',
			   'sum_cred'=>'Total crédit','solde_deb'=>'Solde débiteur',
			   'solde_cred'=>'Solde créditeur'),'Balance des comptes '.$t_cent.$per_text);
$pdf->ezStream();


?>
