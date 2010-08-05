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
 * \brief create GL comptes as PDF
 */

include_once('class_acc_account_ledger.php');
include_once('ac_common.php');
require_once('class_database.php');
include_once('impress_inc.php');
require_once('class_own.php');
require_once('class_dossier.php');
require_once('class_user.php');

header('Content-type: application/csv');
header('Content-Disposition: attachment;filename="gl_comptes.csv"',FALSE);
header('Pragma: public');


$gDossier=dossier::id();

/* Security */
$cn=new Database($gDossier);
$User=new User($cn);
$User->Check();
$User->check_dossier($gDossier);
$User->can_request(IMPBIL,0);

extract($_GET);

if ( isset($poste_id) && strlen(trim($poste_id)) != 0 && isNumber($poste_id) ) {
    if ( isset ($poste_fille) ) {
      $parent=$poste_id;
      $a_poste=$cn->get_array("select pcm_val from tmp_pcmn where pcm_val::text like '$parent%' order by pcm_val::text");
    } elseif ( $cn->count_sql('select * from tmp_pcmn where pcm_val='.FormatString($poste_id)) != 0 ) {
      $a_poste=array('pcm_val' => $poste_id);
    }
} else {
  $a_poste=$cn->get_array("select pcm_val from tmp_pcmn order by pcm_val::text");
}

if ( count($a_poste) == 0 ) {
   echo 'Rien à rapporter.';
   printf("\n");
   exit;
}

// Header
$header = array( "Date", "Référence", "Libellé", "Pièce", "Débit", "Crédit", "Solde" );

foreach ($a_poste as $poste) {

    $Poste=new Acc_Account_Ledger($cn,$poste['pcm_val']);
    list($array,$tot_deb,$tot_cred)=$Poste->get_row_date($from_periode,$to_periode);

    // don't print empty account
    if ( count($array) == 0 ) {
      continue;
    }

    echo sprintf("%s - %s ",$Poste->id,$Poste->get_name());
    printf("\n");

    for($i=0;$i<count($header);$i++)
      echo $header[$i].";";
    printf("\n");

    $solde = 0.0;
    $solde_d = 0.0;
    $solde_c = 0.0;

    foreach ($Poste->row as $detail) {

        /*
               [0] => 1 [jr_id] => 1
               [1] => 01.02.2009 [j_date_fmt] => 01.02.2009
               [2] => 2009-02-01 [j_date] => 2009-02-01
               [3] => 0 [deb_montant] => 0
               [4] => 12211.9100 [cred_montant] => 12211.9100
               [5] => Ecriture douverture [description] => Ecriture douverture
               [6] => Opération Diverses [jrn_name] => Opération Diverses
               [7] => f [j_debit] => f
               [8] => 17OD-01-1 [jr_internal] => 17OD-01-1
               [9] => ODS1 [jr_pj_number] => ODS1 ) 1
         */

          if ($detail['cred_montant'] > 0) {
            $solde   += $detail['cred_montant'];
            $solde_c += $detail['cred_montant'];
          }
          if ($detail['deb_montant'] > 0) {
            $solde   -= $detail['deb_montant'];
            $solde_d += $detail['deb_montant'];
          }

          echo $detail['j_date_fmt'].";";
          echo $detail['jr_internal'].";";
          echo $detail['description'].";";
          echo $detail['jr_pj_number'].";";
          echo ($detail['deb_montant']  > 0 ? sprintf("%.2f", $detail['deb_montant'])  : '').";";
          echo ($detail['cred_montant'] > 0 ? sprintf("%.2f", $detail['cred_montant']) : '').";";
          echo sprintf("%.2f", $solde).";";
          printf("\n");

        }


        echo ";";
        echo ";";
        echo ";";
        echo 'Total du compte '.$Poste->id.";";
        echo ($solde_d  > 0 ? sprintf("%.2f", $solde_d)  : '').";";
        echo ($solde_c  > 0 ? sprintf("%.2f", $solde_c)  : '').";";
        echo sprintf("%.2f", abs($solde_c-$solde_d)).";";
        echo ($solde_c > $solde_d ? 'C' : 'D').";";
        printf("\n");
        printf("\n");
}

exit;

?>
