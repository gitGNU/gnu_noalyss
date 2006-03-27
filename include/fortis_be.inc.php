<?
/////////////////////////////////////////////////////////////////////////////////////////////////////
// Fortis Bank
/////////////////////////////////////////////////////////////////////////////////////////////////////
// first line is skipped
if ( $row>1) {
	
	$code=""; $date_exec=""; $date_valeur=""; $montant=""; $devise=""; $compte_ordre=""; $detail=""; $num_compte=""; $iduser="";
	
	list($code, $date_exec, $date_valeur, $montant, $devise, $compte_ordre, $detail, $num_compte) = split(";", $data[$c]);
	
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
	
	$sql = "select * from import_tmp 
			where 
			code='".$code."' and 
			num_compte='".$p_bq_account."'";
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
			jrn
			ok)
		values ('$code',
			'$date_exec',
			'$date_exec',
			'$montant',
			'$devise',
			'".addslashes($compte_ordre)."',	
			'".addslashes($detail)."',
			'$p_bq_account',
			$p_jrn,
			false)";
	
		$Res=ExecSql($p_cn,$Sql);
	}
}
?>