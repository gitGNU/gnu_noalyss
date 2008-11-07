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
 * \brief respond ajax request, the get contains
 *  the value :
 * - c for qcode
 * - t for tva_id
 * - p for price
 * - q for quantity
 * - n for number of the ctrl
 * - gDossier
 * - PHPSESSID
 * Must return at least tva, htva and tvac
 * \todo must add the security
 */

require_once ('constant.php');
require_once ('postgres.php');
require_once ('debug.php');
require_once ('class_acc_compute.php');
require_once('class_dossier.php');
require_once ('class_acc_tva.php');

// Check if the needed field does exist
extract ($_GET);
foreach (array('t','c','p','q','n','gDossier') as $a) {
  if ( ! isset (${$a}) )   { echo "error $a is not set "; exit();} 
}
$cn=DbConnect(dossier::id());

// Retrieve the rate of vat

$tva_rate=new Acc_Tva($cn);
$tva_rate->set_parameter('id',$t);
$tva_rate->load();

$total=new Acc_Compute();
bcscale(4);
$amount=round(bcmul($p,$q),2);
$total->set_parameter('amount',$amount);
$total->set_parameter('amount_vat_rate',$tva_rate->get_parameter('rate'));
$total->compute_vat();
$tvac=bcadd($total->get_parameter('amount_vat'),$amount);
header("Content-type: text/html; charset: utf8",true);
echo '{"ctl":"'.$n.'","htva":"'.$amount.'","tva":"'.$total->get_parameter('amount_vat').'","tvac":"'.$tvac.'"}';
?>

