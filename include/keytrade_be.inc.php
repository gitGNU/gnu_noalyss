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
/* $Revision: ??2162?? $ */
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/*! \file
 * \brief This file must be included to parse the CVS from the Keytrade Bank
 */



//-----------------------------------------------------
// Bank Keytrade
//-----------------------------------------------------
$line=0;
$p_cn->set_encoding('latin1');

while (($data = fgetcsv($handle, 2000,"!")) !== FALSE) {
	$num = count($data);
	for ($c=0; $c < $num; $c++) {
	  // skip the first line
	  if ( $line < 1 )
	    continue;

//-----------------------------------------------------
// Parsing CSV comes here
//-----------------------------------------------------
	  $row=explode(';',$data[$c]);
	  if ( sizeof ($row) < 7 )
	    continue;

	  // Your own bank account
	  $compte_ordre=''; 

	  // Execution date of the operation
	  $date_exec=str_replace('/','-',$row[0]);
	  // Operation reference, it must be unique, so we take the datum
	  // the line number and a random number
	  $r=rand(1,1000);
	  $code=$date_exec.'-'.$line.$r;
	  // Effective date of the operation
	  $date_val=str_replace('/','-',$row[1]);
	  // Third party bank account
	  $num_compte=$row[2];
	  //
	  $detail=trim($row[3]);
	  //
	  $signe=trim($row[4]);
	  //
	  // remove first the thousand sep.
	  $montant=str_replace('.','',$row[5]);
	  // replace the coma by a period
	  $montant=str_replace(',','.',$montant);
	  // remove the sign
	  //$montant=str_replace('+','',$montant);
	  // use the sign
	  if ($signe == '-')
	      $montant='-'.$montant;
	  // Curency used
	  $devise=trim($row[6]);

//----------------------------------------------------
// Skip dubbel impossible
//----------------------------------------------------

echo "Ajout de $detail";
echo "<br>";

//-----------------------------------------------------
// insert into import_tmp
//-----------------------------------------------------
	  $Sql="insert into import_tmp (code,
			date_exec ,
			date_valeur,
			montant,
			devise,
                        num_compte,
			detail,
			bq_account ,
			jrn,
			status)
		values ('$code',
			to_date('$date_exec','DD-MM-YYYY'),
			to_date('$date_exec','DD-MM-YYYY'),
			$montant,
			'$devise',
                        '".$num_compte."',
			'".addslashes($detail)."	',
			'$p_bq_account',
			$p_jrn,
			'n')";
	  try 
		  {
		    $p_cn->exec_sql($Sql);
		  }


	  catch(Exception $e)
		  { 
		    echo(__FILE__.":".__LINE__." Erreur : ".$e->getCode()." msg ".$e->getMessage());
		    rollback($p_cn); 
		    break;
		  }

	} // for ($c=0;$c<$num;$c++)
	$line++;

} // file is read

fclose($handle);
commit($p_cn);
?>
