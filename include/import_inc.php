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

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
// $Revision$
include_once("jrn.php");
include_once("preference.php");
include_once("user_common.php");
/* function ImportCSV
 **************************************************
 * Purpose : Parse the file and insert the record
 *          into the table import_tmp. Insert in a temporary table, if
 *          no confirmation is given then the data are removed otherwise
 *          records are inserted into import_tmp
 *        
 * parm : 
 *	- p_cn database connection
 *  - file the uploaded file
 *  - $p_bq_account 
 *  - p_format_csv file to include (depending of the bank)
 * gen :
 *	-
 * return:
 */
function ImportCSV($p_cn,$file,$p_bq_account,$p_format_csv,$p_jrn)
{
        if(!$handle = fopen($file, "r")) {
                print 'could not open file. quitting';
                die;
        }

StartSql($p_cn);

        
// include the right format for CSV --> given by the <form
		include($p_format_csv);

        
	echo "Importation terminée.";

// if importation succeeds then we can commit the change
Commit($p_cn);
/* Done by trigger
// clean all the double quote
$sql="update import_tmp set 
		compte_ordre=replace(compte_ordre,'\"',''),
		detail=replace(detail,'\"',''),
		num_compte=replace(num_compte,'\"','');";
ExecSql($p_cn,$sql);*/

}

function UpdateCSV($p_cn, $code, $poste){
	$sql = "update import_tmp set poste_comptable='".$poste."' where code='".$code."'";
	$Res=ExecSql($p_cn,$sql);
}

function VerifImport($p_cn){
	$sql = "select * from import_tmp where poste_comptable='' or poste_comptable is null";
	$Res=ExecSql($p_cn,$sql);
	$Num=pg_NumRows($Res);
	echo $Num." opérations à complèter.<br/><br/>";
	$i=1;
	while($val = pg_fetch_array($Res)){
		echo '<form METHOD="POST" action="import.php?action=verif">';
		echo '<input type="hidden" name="code" value="'.$val['code'].'">';
		echo '<table border="1" width="500">';
		echo '<tr><td width="200">'.$val['code'].'</td><td width="200">'.$val['date_exec'].'</td><td width="100">'.$val['montant'].' EUR</td><tr/>';
		echo '<tr><td height="50" colspan="3">'.$val['detail'].'</td><tr/>';
		echo '<tr><td>Poste Comptable : <input type="text" size="10" name="poste"></td>';
		echo "<td>n° compte : ".$val['num_compte']."</td>";
		echo '<td><input type="submit" value="modifier"></td><tr/>';
		echo '</table>';
		echo '</FORM>';
		$i++;
	}
}

function TransferCSV($p_cn, $periode){
	//on obtient la période courante
	//$p_user = $_SESSION['g_user'];
	//$periode = GetUserPeriode($p_cn,$p_user);
	//$periode = $p_user->GetPeriode();
	// on trouve les dates frontières de cette période
	$sql = "select p_start,p_end from parm_periode where p_id = '".$periode."'";
	$Res=ExecSql($p_cn,$sql);
	$val = pg_fetch_array($Res);
	$start = $val['p_start'];   $end = $val['p_end'];
	
	$sql = "select * from import_tmp where poste_comptable is not null and  poste_comptable <> '' 
            AND ok <> TRUE AND date_exec BETWEEN '".$start."' and '".$end."'";
	$Res=ExecSql($p_cn,$sql);
	//echo "boucle: ".sizeof($Res)."<br/>";
	//while($val = pg_fetch_array($Res)){
	$Max=pg_NumRows($Res);
	echo $Max." opérations à transférer.<br/>";
	for ($i = 0;$i < $Max;$i++) {
		$val=pg_fetch_array($Res,$i);
		
		$code=$val['code']; $date_exec=$val['date_exec']; $montant=$val['montant']; $num_compte=$val['num_compte']; 
		$poste_comptable=$val['poste_comptable'];$bq_account=$val['bq_account'];
		$jrn=$val['jrn'];

		list($annee, $mois, $jour) = explode("-", $date_exec);
		$date_exec = $jour.".".$mois.".".$annee;

		// Vérification que le poste comptable trouvé existe
		$sqltest = "select * from tmp_pcmn WHERE pcm_val='".$poste_comptable."'";
		$Restest=ExecSql($p_cn,$sqltest);
		$test=pg_NumRows($Restest);
		if($test == 0) {
			$sqlupdate = "update import_tmp set poste_comptable='' WHERE code='".$code."' AND num_compte='".$num_compte."'";
			$Resupdate=ExecSql($p_cn,$sqlupdate);
			echo "Poste comptable erronné pour l'opération ".$num_compte."-".$code.", réinitialisation du poste comptable<br/>";
		} else {

		// Finances
	
		//$seq = GetNextId($p_cn,'j_grpt')+1;
		$seq=NextSequence($p_cn,'s_grpt');
		$p_user = $_SESSION['g_user'];
		//$periode = GetUserPeriode($p_cn,$p_user);
		//$periode = $User->GetPeriode();
		StartSql($p_cn);

		$r=InsertJrnx($p_cn,"d",$p_user,$jrn,$bq_account,$date_exec,$montant,$seq,$periode);
		if ( $r == false) { $Rollback($p_cn);exit("error 'import_inc.php' __LINE__");}
	
		$r=InsertJrnx($p_cn,"c",$p_user,$jrn,$poste_comptable,$date_exec,$montant,$seq,$periode);
		if ( $r == false) { $Rollback($p_cn);exit("error 'import_inc.php' __LINE__");}

		//remove annoying double-quote
		$num_compte=str_replace('"','',$num_compte);
		$code=str_replace('\"','',$code);
	
		$r=InsertJrn($p_cn,$date_exec,NULL,$jrn,$num_compte." ".$code,$montant,$seq,$periode);
		if ( $r == false ) { Rollback($p_cn); exit(" Error 'import_inc.php' __LINE__");}
		  
		//$sql = "insert into jrn (jr_def_id,jr_montant,jr_comment,jr_date,jr_grpt_id,jr_tech_per) values ( ".$p_jrn.", abs(".round($montant,2)."), '".$num_compte." ".$code."','".$date_valeur."','".$seq."','".$periode."')";
		SetInternalCode($p_cn,$seq,$jrn);


		echo "Tranfer de l'opération ".$code." effectué<br/>";
		$sql2 = "update import_tmp set ok=TRUE where code='".$code."'";
		$Res2=ExecSql($p_cn,$sql2);

		Commit($p_cn);
		}
	}
}
/* function ShowForm
 **************************************************
 * Purpose : ShowForm for getting data about 
 *           the bank transfert in cvs
 *        
 * parm :  database connection
 *	-
 * gen :
 *	-
 * return: none
 */

function ShowFormTransfert($p_cn){
$w=new widget("select");
  echo '<FORM METHOD="POST" action="import.php?action=import" enctype="multipart/form-data">';
  echo '<INPUT TYPE="file" name="fupload" size="20"><br>';
  // ask for the journal target 
  $jrn=make_array ($p_cn,"select jrn_def_id,jrn_def_name from jrn_def where jrn_def_type='FIN';");
  $w->label='Journal';
  echo $w->label." :".$w->IOValue('import_jrn',$jrn)."<br>";
  // choose the bank account
  $bq=make_array($p_cn,"select pcm_val,pcm_lib from tmp_pcmn where pcm_val like '550%'");
  $w->label='Banque';
  echo "Compte en banque :".$w->IOValue('import_bq',$bq)."<br>";
  $format_csv=make_array($p_cn,"select include_file,name from format_csv_banque;");
  $w->label="Format import";
  echo $w->label.$w->IOValue('format_csv',$format_csv).'<br>';
  echo '<INPUT TYPE="SUBMIT" Value="Import fiche">';
  echo '</FORM>';
}
?>
