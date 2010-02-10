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
// Author Olivier Dzwoniarkiewicz
/*! \file
 * \brief File to be included to parse CSV from ING Belgium
 */

//-----------------------------------------------------
// Bank ING
//-----------------------------------------------------
$row=1;
$p_cn->set_encoding('latin1');
while (($data = fgetcsv($handle, 2000,'@')) !== FALSE) {
	$num = count($data);
	for ($c=0; $c < $num; $c++) {
	
	// first line is skipped
	if ( $row > 1 ) {
		
		$code=""; $date_exec=""; $date_valeur=""; $montant=""; $devise=""; $compte_ordre=""; $detail=""; $num_compte=""; $iduser="";
		list($num_compte, $code, $date_exec, $date_valeur, $montant, $devise, $montant2, $devise2, $rubriques, $detail, $zzz, $zzz, $date_comptable) = explode(";", $data[$c]);
		
		# Bug CSV ING : "424 au lieu de 424
#"
		$code = str_replace("\"", "", $code);	
		
		# Bug CSV ING : espace apres la date
		$date_exec = str_replace(" ", "", $date_exec);
		$date_valeur = str_replace(" ", "", $date_valeur);
		$date_exec = str_replace("\"", "", $date_exec);
		$date_valeur = str_replace("\"", "", $date_valeur);
		$montant = str_replace("\"", "", $montant);
		if ((ereg ("([0-9]{3})-([0-9]{7})-([0-9]{2})", $num_compte, $regs))){
			$num_compte = $regs[0];
		}
		
		// Si LTXXXXX ou LT XXXXX dans le détail
		if ((ereg ("LT+([0-9]{5})", $detail, $regs)) || (ereg ("LT+[ ]+([0-9]{5})", $detail, $regs))) {
			$iduser = $regs[1];
		}
		
		// Si XXXXXXXXXXXX
		if (ereg ("[0-9]{12}", $detail, $regs)){
			if($regs[0] != "000000000000") {
				$id = substr($regs[0], 2, 5);
				$longchiffre = substr($regs[0], 0, 10);
				$check = substr($regs[0], 10, 2);
				if (($longchiffre % 97) == $check) $iduser = $id;
			}
		}
		
		if($iduser != "" ) $poste_comptable = "400".$iduser;
		else $poste_comptable = "";
		
		list($jour,$mois,$annee) = explode("/", $date_valeur);  $date_valeur = $annee."-".$mois."-".$jour;
		list($jour,$mois,$annee) = explode("/", $date_exec);  $date_exec = $annee."-".$mois."-".$jour;
		
		$montant = str_replace(",", ".", $montant);
		$montant = str_replace("+", "", $montant);
		
		if($code < 10) $code = "000".$code;
		if($code >= 10 and $code < 100) $code = "00".$code;
		if($code >= 100) $code = "0".$code;
		
		$code = $annee."-".$code;
		
		$sql = "select * from import_tmp where code='".$code."' and num_compte='".$num_compte."'";
		$Res=$p_cn->exec_sql($sql);
		$Num=Database::num_row($Res);
		
		if($Num > 0) {
			echo "Op&eacute;ration FORTIS ".$code." d&eacute;j&eagrave; import&eacute;e.<br/>";
		} else {
			//echo $code." ".$date_exec." ".$date_valeur." ".$montant." ".$devise." ".$compte_ordre." detail ".$num_compte." <b>".$poste_comptable."</b><br/>";
			//$Sql="insert into import_tmp values ('$code','$date_exec','$date_valeur','$montant','$devise','".addslashes($compte_ordre)."','".addslashes($detail)."','$num_compte','$poste_comptable')";
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
			values ('$code',
				'$date_exec',
				'$date_exec',
				$montant,
				'$devise',
				'".addslashes($compte_ordre)."',	
				'".addslashes($detail).$num_compte."	',
				'$p_bq_account',
				$p_jrn,
				'n')";
	echo $sql;
	$Res=$p_cn->exec_sql($Sql);
		}
	}

		} // for ($c=0;$c<$num;$c++)
		$row++;
} // file is read
fclose($handle);
?>
