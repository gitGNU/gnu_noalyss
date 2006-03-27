<?
///////////////////////////////////////////////////////////////////////////////////////////////////////
// Bank type = eub
/////////////////////////////////////////////////////////////////////////////////////////////////////
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
?>