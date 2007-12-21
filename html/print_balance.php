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
include_once("postgres.php");
include_once("class.ezpdf.php");
include_once("poste.php");
include_once("class_acc_balance.php");
include_once("preference.php");
require_once ('header_print.php');
require_once('class_dossier.php');
$gDossier=dossier::id();

$cn=DbConnect($gDossier);
$rep=DbConnect();
include ('class_user.php');
$User=new cl_user($rep);
$User->Check();

$bal=new Acc_Balance($cn);
$User->can_request($cn,BALANCE);

echo_debug('print_balance.php',__LINE__,"imp pdf journaux");
foreach ($_POST as $key=>$element) {
  ${"$key"}=$element;
  echo_debug('print_balance.php',__LINE__,"key => $key element $element");
}
// if centralized
$t_cent="";

if ( isset($central) ) {
    $bal->central='Y';
    $t_cent="centralisée";
 }
 else 
  $bal->central='N';

$bal->jrn=$_POST['p_jrn'];
$bal->from_poste=$_POST['from_poste'];
$bal->to_poste=$_POST['to_poste'];

$array=$bal->get_row($from_periode,$to_periode);

if ( sizeof($array)  == 0 ) {
  $pdf= new Cezpdf('a4');
  $pdf->selectFont('./addon/fonts/Helvetica.afm');
  $pdf->ezSetCmMargins(2,2,2,2);
  $pdf->ezText("Balance compte -- vide");
  $pdf->ezStream();
  exit();
  
 }
$a=get_periode($cn,$from_periode);
$b=get_periode($cn,$to_periode);
$per_text=" période du ".$a['p_start']." au ".$b['p_end'];
$pdf=new Cezpdf('a4');
$pdf->selectFont('./addon/fonts/Helvetica.afm');
$pdf->ezSetCmMargins(2,2,2,2);
header_pdf($cn,$pdf);
$pdf->ezTable($array,array('poste'=>'Poste','label'=>'Libellé','sum_deb'=>'Total Débit',
			   'sum_cred'=>'Total crédit','solde_deb'=>'Solde débiteur',
			   'solde_cred'=>'Solde créditeur'),'Balance des comptes '.$t_cent.$per_text);
$pdf->ezStream();


?>
