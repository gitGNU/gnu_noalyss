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
/* $Revision$ */
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
include_once("ac_common.php");
include_once ("postgres.php");
include ('class_user.php');
include("class_poste.php");
header('Content-type: application/csv');
header('Content-Disposition: attachment;filename="poste.csv"',FALSE);

/* Admin. Dossier */
$cn=DbConnect($g_dossier);


$User=new cl_user($cn);
$User->Check();


$Poste=new poste($cn,$_POST['poste_id']);
$Poste->GetName();
list($array,$tot_deb,$tot_cred)=$Poste->GetRow( $_POST['from_periode'],
						$_POST['to_periode']
						);
if ( count($Poste->row ) == 0 ) 
  exit;

echo "\"Code interne\"\t ".
     "\"Date\"\t".
      "\"Description\"\t".
      "\"Débit\"\t".
      "\"Crédit\n";


  foreach ( $Poste->row as $op ) { 
      echo '"'.$op['jr_internal'].'"'."\t".
	'"'.$op['j_date'].'"'."\t".
	'"'.$op['description'].'"'."\t".
	sprintf("%8.4f",$op['deb_montant'])."\t".
	sprintf("%8.4f",$op['cred_montant']).
	"\n";
    
  }
  $solde_type=($tot_deb>$tot_cred)?"solde débiteur":"solde créditeur";
  $diff=abs($tot_deb-$tot_cred);
  echo 
    '"'."$solde_type".'"'."\t".
    sprintf("%8.4f",$diff)."\t".
    sprintf("%8.4f",$tot_deb)."\t".
  sprintf("%8.4f",$tot_cred)."\n";

  exit;
?>
