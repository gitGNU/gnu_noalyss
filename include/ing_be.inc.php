<?
/////////////////////////////////////////////////////////////////////////////////////////////////////
// Bank ING
/////////////////////////////////////////////////////////////////////////////////////////////////////
// first line is skipped
if ( $row > 1 ) {
	
	$code=""; $date_exec=""; $date_valeur=""; $montant=""; $devise=""; $compte_ordre=""; $detail=""; $num_compte=""; $iduser="";
	list($num_compte, $code, $date_exec, $date_valeur, $montant, $devise, $montant2, $devise2, $rubriques, $detail, $zzz, $zzz, $date_comptable) = split(";", $data[$c]);
	
	# Bug CSV ING : "424 au lieu de 424
	$code = str_replace("\"", "", $code);	
	
	# Bug CSV ING : espace après la date
	$date_exec = str_replace(" ", "", $date_exec);
	$date_valeur = str_replace(" ", "", $date_valeur);
	
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
	$Res=ExecSql($p_cn,$sql);
	$Num=pg_NumRows($Res);
	
	if($Num > 0) {
		echo "Op&eacute;ration FORTIS ".$code." d&eacute;j&eagrave; import&eacute;e.<br/>";
	} else {
		//echo $code." ".$date_exec." ".$date_valeur." ".$montant." ".$devise." ".$compte_ordre." detail ".$num_compte." <b>".$poste_comptable."</b><br/>";
		//$Sql="insert into import_tmp values ('$code','$date_exec','$date_valeur','$montant','$devise','".addslashes($compte_ordre)."','".addslashes($detail)."','$num_compte','$poste_comptable')";
		$Sql="insert into import_tmp (code 
			date_exec ,
			date_valeur,
			montant,
			devise,
			compte_ordre,
			detail,
			num_compte,
			poste_comptable,
			ok)
		values ('$code',
			'$date_exec',
			'$date_exec',
			'$montant',
			'$devise',
			'".addslashes($compte_ordre)."',	
			'".addslashes($detail)."',
			'$p_bq_account',
			false)";
		$Res=ExecSql($p_cn,$Sql);
	}
}
?>