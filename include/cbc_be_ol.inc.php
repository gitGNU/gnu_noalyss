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
/* $Revision: 3151 $ */
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/*! \file
 * \brief This file must be included to parse the CVS from the CBC Bank
 */

//-----------------------------------------------------
// Bank CBC
//-----------------------------------------------------
$line=0;
$p_cn->start();
$p_cn->set_encoding('latin1');
while (($row = fgetcsv($handle, 2000,';')) !== FALSE)
{

    $num = count($row);
    if ( $num != 11 ) continue;
    if (  $row[3] != 'EUR') continue;
    $line++;
    //-----------------------------------------------------
    // Parsing CSV comes here
    //-----------------------------------------------------
    $num_compte=$row[0];
    $date_exec=$row[5];
    $date_val=$row[7];
    // remove first the thousand sep.
    $montant=str_replace(' ','',$row[8]);
    // replace the coma by a period
    $montant=str_replace(',','.',$montant);
    // remove the sign
    $montant=str_replace('+','',$montant);
    $devise=$row[3];
    $ref_extrait=$row[4].'-'.$line;
    $detail=$line.'/'.$row[4].' '.$row[6];
    $pj=$row[10];
    //
    //-----------------------------------------------------
    // insert into import_tmp
    //-----------------------------------------------------
    $Sql="insert into import_tmp (code,
         date_exec ,
         date_valeur,
         montant,
         devise,
         detail,
         bq_account ,
         jrn,
         status,
         it_pj)
         values ('$ref_extrait',
         to_date('$date_exec','DD/MM/YYYY'),
         to_date('$date_exec','DD/MM/YYYY'),
         $montant,
         '$devise',
         '$detail',
         '$p_bq_account',
         $p_jrn,
         'n',$pj)";
    try
    {
        $p_cn->exec_sql($Sql) ;
    }
    catch(Exception $e)
    {
        $p_cn->rollback();
        break;
    }
} // file is read
fclose($handle);
if ($line ==0 )
    echo "Encore rien désolé";
else
    echo "$line lignes sont importées";
echo '<br>';
?>
