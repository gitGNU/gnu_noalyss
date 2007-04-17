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
 * \brief File to include : parse the CSV file from fortis Bank Belgium
 */

//-----------------------------------------------------
// Fortis Bank
//-----------------------------------------------------
$row=1;
while (($data = fgetcsv($handle, 2000,'!@')) !== FALSE) {
	$num = count($data);
	for ($c=0; $c < $num; $c++) {

// first line is skipped
		if ( $row>1) {
			
			$code=""; $date_exec=""; $date_valeur=""; $montant=""; $devise=""; $compte_ordre=""; $detail=""; $num_compte=""; $iduser="";
			
			list($code, $date_exec, $date_valeur, $montant, $devise, $compte_ordre, $detail, $num_compte) = split(";", $data[$c]);
			echo "line : $row > ".$data[$c]."<hr>";
		
			//corrige un bug de date
			$date_exec = str_replace(chr(34),"", $date_exec);
			
			// Si LTXXXXX ou LT XXXXX dans le détail
			if ((ereg ("LT+([0-9]{5})", $detail, $regs)) || (ereg ("LT+[ ]+([0-9]{5})", $detail, $regs))) {
				$iduser = $regs[1];
			}
			
			// Si 00000XXXXXXX
			if (ereg ("[0-9]{12}", $detail, $regs)){
				if($regs[0] != "000000000000") {
					$id = substr($regs[0], 5, 5);
					$check = substr($regs[0], 10, 2);
					if (($id % 97) == $check) $iduser = $id;
				}
			}
			
			if($iduser != "" ) $poste_comptable = "400".$iduser;
			else $poste_comptable = "";
			
			list($jour,$mois,$annee) = explode("/", $date_exec);  $date_exec = $annee."-".$mois."-".$jour;
			list($jour,$mois,$annee) = explode("/", $date_valeur);  $date_valeur = $annee."-".$mois."-".$jour;
			
			$montant = str_replace(",", ".", $montant);
			$montant = str_replace("+", "", $montant);
			$montant = str_replace("\"", "", $montant);
			
			$detail=str_replace("\"","",$detail);
		
			$sql = "select * from import_tmp 
					where 
					code='".$code."' and 
					num_compte='".$num_compte."'";
			$Res=ExecSql($p_cn,$sql);
			$Num=pg_NumRows($Res);
			
			if($Num > 0) {
				echo "Op&eacute;ration FORTIS ".$code." d&eacute;j&eagrave; import&eacute;e.<br/>";
			} else {
			//	$Sql="insert into import_tmp values ('$code','$date_exec','$date_valeur','$montant','$devise','".addslashes($compte_ordre)."','".addslashes($detail)."','$num_compte','$poste_comptable')";
				$Sql="insert into import_tmp (code ,
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
				values ('$code',
					'$date_exec',
					'$date_exec',
					'$montant',
					'$devise',
					'".addslashes($compte_ordre)."',	
					'".addslashes($detail)."',
					'$num_compte',
					$p_bq_account,
					$p_jrn,
					'n')";
			
				$Res=ExecSql($p_cn,$Sql);
			}
		}

		} // for ($c=0;$c<$num;$c++)
		$row++;
} // file is read
fclose($handle);
?>
