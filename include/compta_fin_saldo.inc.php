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

/**\file
 *
 *
 * \brief show bank saldo
 *
 */
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
require_once ('class_acc_parm_code.php');
    echo '<div class="content">';
    $fiche=new Fiche($cn);

    $array=$fiche->get_bk_account();

    echo '<div class="content">';
    echo dossier::hidden();
    echo '<table style="margin-left:10%;width:60%" class="result">';
    echo tr(th('Quick Code').th('Compte en banque',' style="text-align:left"').th('solde opération',' style="text-align:right"')
	    .th('solde extrait/relevé',' style="text-align:right"')
	    .th('différence',' style="text-align:right"'));
    // Filter the saldo
    //  on the current year
    $filter_year="  j_tech_per in (select p_id from parm_periode where  p_exercice='".$g_user->get_exercice()."')";
    // for highligting tje line
    $idx=0;
    bcscale(2);
    // for each account
    for ( $i = 0; $i < count($array);$i++)
    {
		if ( $array[$i]->id==0) {
			echo '<tr >';
			echo td(h2("Journal mal configuré",' class="error" '),' colspan="5" style="width:auto" ');
			echo '</tr>';
			continue;
		}
        // get the saldo
        $m=$array[$i]->get_solde_detail($filter_year);

        $solde=$m['debit']-$m['credit'];

        // print the result if the saldo is not equal to 0
        if ( $m['debit'] != 0.0 || $m['credit'] != 0.0)
        {
            /*  get saldo for not reconcilied operations  */
            $saldo_not_reconcilied=$array[$i]->get_bk_balance($filter_year." and (trim(jr_pj_number) ='' or jr_pj_number is null)" );

            /*  get saldo for reconcilied operation  */

	    $saldo_reconcilied=$array[$i]->get_bk_balance($filter_year." and ( trim(jr_pj_number) != '' and jr_pj_number is not null)" );

            if ( $idx%2 != 0 )
                $odd="odd";
            else
                $odd="even";

            $idx++;
            echo "<tr class=\"$odd\">";
            echo "<TD >".
            IButton::history_card($array[$i]->id,$array[$i]->strAttribut(ATTR_DEF_QUICKCODE)).
            "</TD>";

	    $saldo_rec=bcsub($saldo_reconcilied['debit'],$saldo_reconcilied['credit']);
	    $diff=bcsub($saldo_not_reconcilied['debit'],$saldo_not_reconcilied['credit']);
            echo "<TD >".
            $array[$i]->strAttribut(ATTR_DEF_NAME).
            "</TD>".
            "<TD align=\"right\">".
	      nbm($solde).
            "</TD>".
            "<TD align=\"right\">".
	      nbm($saldo_rec).
            "</TD>".
            "<TD align=\"right\">".
	      nbm($diff).
            "</TD>".
            "</TR>";
        }
    }// for
    echo "</table>";
    echo "</div>";
    exit();
?>
