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
require_once('class_database.php');
require_once("class_acc_account_ledger.php");
require_once ('class_acc_operation.php');

header('Pragma: public');
header('Content-type: application/csv');
header('Content-Disposition: attachment;filename="poste.csv"',FALSE);
require_once('class_dossier.php');
$gDossier=dossier::id();

/* Admin. Dossier */
$cn=new Database($gDossier);

if ( isset ( $_REQUEST['poste_fille']) )
{ //choisit de voir tous les postes
  $a_poste=$cn->get_array("select pcm_val from tmp_pcmn where pcm_val::text like $1||'%'",array($_REQUEST["poste_id"]));
}
else
{
  $a_poste=$cn->get_array("select pcm_val from tmp_pcmn where pcm_val = $1",array($_REQUEST['poste_id']));
}

if ( ! isset ($_REQUEST['oper_detail']))
{
    if ( count($a_poste) == 0 )
        exit;

    foreach ($a_poste as $pos)
    {
        $Poste=new Acc_Account_Ledger($cn,$pos['pcm_val']);
        $name=$Poste->get_name();
        list($array,$tot_deb,$tot_cred)=$Poste->get_row_date( $_REQUEST['from_periode'],
							      $_REQUEST['to_periode'],
							      $_GET['ople']
							      );
        if ( count($Poste->row ) == 0 )
            continue;

        echo '"Poste";'.
	  '"n° pièce";'.
	  '"Lib.";'.
        "\"Code interne\";".
        "\"Date\";".
        "\"Description\";".
        "\"Débit\";".
        "\"Crédit\";".
        "\"Prog.\"";
        printf("\n");

        $prog=0;
        foreach ( $Poste->row as $op )
        {
	  $diff=bcsub($op['deb_montant'],$op['cred_montant']);
	  $prog=bcadd($prog,$diff);
	  echo '"'.$pos['pcm_val'].'";'.
	    '"'.$op['jr_pj_number'].'"'.";".
            '"'.$name.'";'.
            '"'.$op['jr_internal'].'"'.";".
            '"'.$op['j_date_fmt'].'"'.";".
            '"'.$op['description'].'";'.
            nb($op['deb_montant']).";".
            nb($op['cred_montant']).";".
            nb(abs($prog));
            printf("\n");


        }
        $solde_type=($tot_deb>$tot_cred)?"solde débiteur":"solde créditeur";
        $diff=abs($tot_deb-$tot_cred);
        printf(
            '"'."$solde_type".'"'.";".
            nb($diff).";".
            nb($tot_deb).";".
            nb($tot_cred)."\n");
    }
}
else
{
    /* detail of all operation */
    if ( count($a_poste) == 0 )
        exit;

    foreach ($a_poste as $pos)
    {
        $Poste=new Acc_Account_Ledger($cn,$pos['pcm_val']);
        $Poste->get_name();
        list($array,$tot_deb,$tot_cred)=$Poste->get_row_date( $_REQUEST['from_periode'],
                                        $_REQUEST['to_periode']
                                                            );
        if ( count($Poste->row ) == 0 )
            continue;

        echo '"Poste";'.
        '"Lib.";'.
        '"QuickCode";'.
        "\"Code interne\";".
        "\"Date\";".
        "\"Description\";".
        "\"Montant\";".
        "\"D/C\"";
        printf("\n");


        foreach ( $Poste->row as $a )
        {
            $op=new Acc_Operation($cn);
            $op->jr_id=$a['jr_id'];
            $result=$op->get_jrnx_detail();
            foreach ( $result as $r)
            {
                printf('"%s";"%s";"%s";"%s";"%s";"%s";"%s";%12.2f;"%s"',
                       $r['j_poste'],
                       $r['pcm_lib'],
                       $r['j_qcode'],
                       $r['jr_internal'],
                       $r['jr_date'],
                       $a['description'],
                       $a['jr_pj_number'],
                       nb($r['j_montant']),
                       $r['debit']);
                printf("\r\n");

            }



        }
    }
    exit;
}
?>
