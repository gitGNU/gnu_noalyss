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
//		 Author DEXIA Adaptation Guy Moins
/*! \file
 * \brief This file must be included to parse the CVS from the DEXIA Bank
 */



//-----------------------------------------------------
// Bank DEXIA
//-----------------------------------------------------
$LinesSkipped=0;
$LinesImported=0;
$LinesDup=0;
$row=1;
$p_cn->start();
while (($data = fgetcsv($handle, 2000,'@')) !== FALSE) {
	$num = count($data);
	echo_debug('dexia_be',__LINE__,$num);
	echo_debug('dexia_be',__LINE__,var_export($data,true));

//-----------------------------------------------------
// Parsing CSV comes here
//-----------------------------------------------------

	echo_debug('dexia_be',__LINE__,'$row = '.var_export($row,true));
	echo_debug('dexia_be',__LINE__,'sizeof($row)'.sizeof($row));
	$row=explode(';',$data[0]);
	//to avoid a level of if
	if (!(isset($row[2]))) $row[2]='';


	// Skipping all the lines whith a blank operation reference ('numéro extrait')
	if (  $row[2] == '' || !(myereg('[0-9]{3}-[0-9]{7}-[0-9]{2}',$row[0],$r)))
			{
		 	$LinesSkipped++;
			continue;
			}
	// Alternative filter : import all the operations even without a reference
	// !! Disable check of doubles as all the unreferenced operations have hte smae 'NULL' reference
	// Just use the following test : if ( !(myereg('[0-9]{3}-[0-9]{7}-[0-9]{2}',$row[0],$r)))

	// Parsing the remaining lines
	$num_compte=$row[3];		// Third party bank account
	$date_exec=$row[1];		// Execution date of the operation
	$date_val=$row[8];		// Effective date of the operation
	$montant=str_replace(',','.',$row[9]);		// Amount of the operation
	$ref_extrait=$row[2];			        // Operation reference
	$devise=$row[10];				// Curency used
	$compte_ordre=$row[0];				// Your own bank account
	$detail=trim($row[4]).' '.trim($row[5]).' '.trim($row[6]).' '.trim($row[7]);
										//Details-Communicaion

//----------------------------------------------------
// Skip dubbel
//----------------------------------------------------
	$Sql="select * from import_tmp where code='$ref_extrait' and compte_ordre='$compte_ordre' limit 2";
	if ( $p_cn->count_sql(utf8_encode($Sql)) > 0)
	{
		/* Skip it it already encoded */
		echo "Double skipped : $ref_extrait $detail <BR>";
		$LinesDup++;
		continue;
	}


//--------------------------------------------------------------------
// SQL request to insert into import_tmp
// Adapt the format of the import's date in the ** to_date ** function
//--------------------------------------------------------------------
			$Sql="insert into import_tmp (code,
				date_exec ,
				date_valeur,
				montant,
				devise,
				compte_ordre,
				detail,
				num_compte,
				bq_account ,
				jrn,
				status)
			values ( '$ref_extrait',
				to_date('$date_exec','DD/MM/YYYY'),
				to_date('$date_val' ,'DD/MM/YYYY'),
				$montant,
				'$devise',
				'" . addslashes($compte_ordre). "',
				'".addslashes($detail)."','" .$num_compte."	',
				'$p_bq_account',
				$p_jrn,
				'n')";

//-----------------------------------------------------
// Check if no need to rollback when executing the SQL
//-----------------------------------------------------
			try {
			  $p_cn->exec_sql($Sql,'latin1') ;
			} catch (Exception $e) {
			  $p_cn->rollback();
			  echo "Rollbacking    : $ref_extrait $detail <BR>";
			  $LinesSkipped++;
			  break;
			}
//-----------------------------------------------------
// The import is OK
//-----------------------------------------------------
			$LinesImported++;
			$row++;
			echo "Record imported: $ref_extrait $detail <BR>";


}
//-----------------------------------------------------
// Close and summary
//-----------------------------------------------------

fclose($handle);
echo "<BR><B>$LinesImported operation(s) imported, $LinesSkipped text or invalid line(s) skipped, $LinesDup operation(s) skipped as already imported<BR>";
?>
