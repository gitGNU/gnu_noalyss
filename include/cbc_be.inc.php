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
// Bank CBC 
//-----------------------------------------------------
$row=1;
StartSql($p_cn);
while (($data = fgetcsv($handle, 2000,'!@')) !== FALSE) {
	$num = count($data);
	echo_debug('cbc_be',__LINE__,$num);
	echo_debug('cbc_be',__LINE__,var_export($data,true));
	for ($c=0; $c < $num; $c++) {
//-----------------------------------------------------
// Parsing CSV comes here
//-----------------------------------------------------
	  $row=split(';',$data[$c]);
	  echo_debug('cbc_be',__LINE__,'$row = '.var_export($row,true));
	  echo_debug('cbc_be',__LINE__,'sizeof($row)'.sizeof($row));
	  if ( sizeof ($row) < 13 )
	    continue;

	  $num_compte=$row[0];
	  $date_exec=$row[3];
	  $date_val=$row[4];
	  // remove first the thousand sep.
	  $montant=str_replace('.','',$row[6]);
	  // replace the coma by a period
	  $montant=str_replace(',','.',$montant);
	  // remove the sign
	  $montant=str_replace('+','',$montant);
	  $devise=$row[1];
	  $compte_ordre=$row[0];
	  $detail=trim($row[7]).' '.trim($row[8]).' '.trim($row[9]).' '.trim($row[10]);
	  if (  ereg('[0-9]{3}-[0-9]{7}-[0-9]{2}',$row[7],$r) )
	    {
	      $compte_ordre=$r[0];
	    }
//-----------------------------------------------------
// insert into import_tmp
//-----------------------------------------------------
			$Sql="insert into import_tmp (code,
				date_exec ,
				date_valeur,
				montant,
				devise,
				compte_ordre,
				detail,
				bq_account ,
				jrn,
				status)
			values (nextval('s_cbc'),
				to_date('$date_exec','YYYYMMDD'),
				to_date('$date_exec','YYYYMMDD'),
				$montant,
				'$devise',
				'".addslashes($compte_ordre)."',	
				'".addslashes($detail).$num_compte."	',
				'$p_bq_account',
				$p_jrn,
				'n')";
			try 
			  {
			    ExecSql($p_cn,$Sql) ;
			  }
			catch(Exception $e)
			  {
			    echo_debug(__FILE__.":".__LINE__." Erreur : ".$e->getCode." msg ".$e->getMessage);
			    Rollback($p_cn);
			    break;
			  }
		} // for ($c=0;$c<$num;$c++)
		$row++;
} // file is read
fclose($handle);
echo "Encore rien d�sol�";
?>
