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
/*! \file
 * \brief Send the poste list in csv
 */
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
include_once("ac_common.php");
require_once('class_database.php');
require_once("class_fiche.php");
header('Content-type: application/csv');

header('Pragma: public');
header('Content-Disposition: attachment;filename="poste.csv"',FALSE);
require_once('class_dossier.php');
$gDossier=dossier::id();

/* Admin. Dossier */
$cn=new Database($gDossier);


$Fiche=new Fiche($cn,$_REQUEST['f_id']);
$Fiche->getName();
list($array,$tot_deb,$tot_cred)=$Fiche->get_row_date(
                                    $_GET['from_periode'],
                                    $_GET['to_periode'],
                                    $_GET['ople']
                                );
if ( count($Fiche->row ) == 0 )
{
    echo "Aucune donnée";
    return;
}


if ( ! isset ($_REQUEST['oper_detail']))
{
    echo '"Qcode";'.
    "\"Date\";".
      "\"n° pièce\";".
    "\"Code interne\";".
    "\"Description\";".
    "\"Débit\";".
    "\"Crédit\";".
    "\"Prog.\";".
    "\"Let.\""     ;
    printf("\n");
    $progress=0;
    foreach ( $Fiche->row as $op )
    {
        $progress+=$op['deb_montant']-$op['cred_montant'];

        echo '"'.$op['j_qcode'].'";'.
	  '"'.$op['j_date_fmt'].'"'.";".
	  '"'.$op['jr_pj_number'].'"'.";".
	  '"'.$op['jr_internal'].'"'.";".
	  '"'.$op['description'].'"'.";".
	  nb($op['deb_montant']).";".
	  nb($op['cred_montant']).";".
	  nb(abs($progress)).';'.
	  '"'.(($op['letter']==-1)?'':strtoupper(base_convert($op['letter'],10,36))).'"';
        printf("\n");

    }
}
else
{
    echo '"Poste";"Qcode";"date";"ref";"internal";';
    echo    "\"Description\";".
    "\"Montant\";".
    "\"D/C\"";

    printf("\r\n");

    foreach ( $Fiche->row as $op )
    {
        $acc=new Acc_Operation($cn);
        $acc->jr_id=$op['jr_id'];
        $result= $acc->get_jrnx_detail();

        foreach ( $result as $r)
        {
            printf('"%s";"%s";"%s";"%s";"%s";%s;%s;"%s"',
                   $r['j_poste'],
                   $r['j_qcode'],
                   $r['jr_date'],
		   $op['jr_pj_number'],
                   $r['jr_internal'],
                   $r['description'],
                   nb($r['j_montant']),
                   $r['debit']);
            printf("\r\n");

        }



    }
}
$solde_type=($tot_deb>$tot_cred)?"solde débiteur":"solde créditeur";
$diff=abs($tot_deb-$tot_cred);
printf(
    '"'."$solde_type".'"'.";".
    nb($diff).";".
    nb($tot_deb).";".
    nb($tot_cred)."\n");

exit;
?>
