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
 * \brief This file must be included to parse the CVS from the CBC Bank
 */



//-----------------------------------------------------
// Bank Argenta 
//-----------------------------------------------------
$line=0;
$p_cn->set_encoding('latin1');

while (($data = fgetcsv($handle, 2000,"!")) !== FALSE) {
	$num = count($data);
	for ($c=0; $c < $num; $c++) {
	  if ( $line==1) {
	    $row=split(';',$data[$c]);
	    $num_compte=$row[1];
	  }
	  if ( $line < 2 )
	    continue;
//-----------------------------------------------------
// Parsing CSV comes here
//-----------------------------------------------------
	  $row=split(';',$data[$c]);
	  echo 'ici sizeof $row = '.sizeof($row);
	  echo_debug('argenta',__LINE__,'$row = '.var_export($row,true));
	  echo_debug('argenta',__LINE__,'sizeof($row)'.sizeof($row));
	  if ( sizeof ($row) < 9 )
	    continue;
	  

	  $date_exec=$row[5];
	  $date_val=$row[5];
	  $code=$row[1]."/".$row[0];
	  // remove first the thousand sep.
	  $montant=str_replace('.','',$row[3]);
	  // replace the coma by a period
	  $montant=str_replace(',','.',$montant);
	  // remove the sign
	  $montant=str_replace('+','',$montant);
	  $devise=$row[4];
	  $compte_ordre=$row[6];
	  $detail='virement du compte:'.$compte_ordre.trim($row[2]).' '.trim($row[7]).' '.trim($row[8]).' '.trim($row[9]);

//----------------------------------------------------
// Skip dubbel
//----------------------------------------------------
	  $code=FormatString($code);
	  $num_compte=FormatString($num_compte);
	  if ( count_sql($p_cn,utf8_encode("select * from import_tmp where code='$code' and num_compte='$num_compte' limit 2")) != 0 )
{
	/* Skip it it already encoded */
	echo "Doublon éliminé ".$detail;
	echo "<br>";
	continue;
}
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
				compte_ordre,
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
				'".addslashes($compte_ordre)."',	
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
$p_cn->commit();
?>
