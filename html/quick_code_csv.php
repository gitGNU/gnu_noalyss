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
/*! \file
 * \brief Send the poste list in csv
 */
include_once("ac_common.php");
include_once ("postgres.php");
include ('class_user.php');
include("class_fiche.php");
header('Content-type: application/csv');
header('Content-Disposition: attachment;filename="poste.csv"',FALSE);
require_once('class_dossier.php');
$gDossier=dossier::id();

/* Admin. Dossier */
$cn=DbConnect($gDossier);


$User=new cl_user($cn);
$User->Check();

$Fiche=new fiche($cn,$_REQUEST['f_id']);
$Fiche->getName();
list($array,$tot_deb,$tot_cred)=$Fiche->GetRow( 
					       $_POST['from_periode'],
					       $_POST['to_periode']
					       );
if ( count($Fiche->row ) == 0 ) 
{
  echo "Aucune donnée";
  return;
}
echo '"Qcode";'.
"\"Code interne\";".
"\"Date\";".
"\"Description\";".
"\"Débit\";".
"\"Crédit\"";
printf("\n");


foreach ( $Fiche->row as $op ) { 
  echo '"'.$op['j_qcode'].'";'.
    '"'.$op['jr_internal'].'"'.";".
    '"'.$op['j_date'].'"'.";".
    '"'.$op['description'].'"'.";".
    sprintf("%8.4f",$op['deb_montant']).";".
    sprintf("%8.4f",$op['cred_montant']);
  printf("\n");
  
  
}
$solde_type=($tot_deb>$tot_cred)?"solde débiteur":"solde créditeur";
$diff=abs($tot_deb-$tot_cred);
printf(
       '"'."$solde_type".'"'.";".
       sprintf("%8.4f",$diff).";".
       sprintf("%8.4f",$tot_deb).";".
       sprintf("%8.4f",$tot_cred)."\n");

exit;
?>
