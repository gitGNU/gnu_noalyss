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
// Author Olivier Dzwoniarkiewicz
///////////////////////////////////////////////////////////////////////////////////////////////////////
// Bank type = eub
/////////////////////////////////////////////////////////////////////////////////////////////////////
$row=1;
while (($data = fgetcsv($handle, 2000,'!@')) !== FALSE) {
	$num = count($data);
	for ($c=0; $c < $num; $c++) {

		// first line is skipped
		if ( $row > 1) {
		$code=""; $date_exec=""; $detail=""; $montant=""; 
		list($code, $date_exec, $detail, $montant) = split(";", $data[$c]);
		
		$date_exec = str_replace("\t", "", $date_exec);
		$date_exec = str_replace(" ", "", $date_exec);
		
		list($annee,$mois,$jour) = explode("-", $date_exec);
		
		$montant = str_replace(".", "", $montant);
		$montant = str_replace(",", ".", $montant);
		$montant = str_replace("+", "", $montant);
		
		if($code < 10) $code = "000".$code;
		if($code >= 10 and $code < 100) $code = "00".$code;
		if($code >= 100) $code = "0".$code;
		
		$code = $annee."-".$code;
		
		
		$Sql="insert into import_tmp (code, 
			date_exec ,
			date_valeur,
			montant,
			devise,
			detail,
			num_compte,
			bq_account 	,
			jrn,
			ok)
		values ('$code',
			'$date_exec',
			'$date_exec',
			'$montant',
			'EUR',
			'".addslashes($detail)."',
			'$p_bq_account',
			$p_jrn,
			false)";
		$Res=ExecSql($p_cn,$Sql);
		}
	} // for ($c=0;$c<$num;$c++)
		$row++;
} // file is read
fclose($handle);
?>