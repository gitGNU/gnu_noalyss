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

// Copyright Author Olivier Dzwoniarkiewicz
// Modified Dany De Bontridder ddebontridder@yahoo.fr
// $Revision$
include_once("jrn.php");
include_once("preference.php");
include_once("user_common.php");
require_once('class_user.php');
require_once('class_widget.php');
require_once('class_fiche.php');
/*! 
 **************************************************
 * \brief  Parse the file and insert the record
 *          into the table import_tmp. Insert in a temporary table, if
 *          no confirmation is given then the data are removed otherwise
 *          records are inserted into import_tmp. Following
 *          the choosen bank a different file is included to 
 *          to parse the CSV, take the cbc_be.inc.php as template
 *        
 * \param $p_cn database connection
 * \param $file  the uploaded file
 * \param $p_bq_account the bank account (target)
 * \param $p_format_csv file to include (depending of the bank)
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

}
/*!\brief Update import_tmp with the bank account
 * 
 */
function UpdateCSV($p_cn){
  $code=$_POST['code'];
  $count=$_POST['count'];
  $poste=$_POST['poste'.$count];
  $sql = "update import_tmp set poste_comptable='".$poste."' where code='".$code."'";
  $Res=ExecSql($p_cn,$sql);
}




/*!\brief This function show a record from the table import_tmp, the tag for the form
 *        are not included in the function and must set in the calling proc.
 * \param $p_val array (row from import_type)
 * \param $counter a counter used in the form
 * \param $p_form indicates if the button for the form is enable
 */
function ShowBox($p_val,$counter,$p_form='form'){

  $w=new widget('js_search_only');
  $w->name='poste'.$counter;
  $w->extra='cred';
  $w->extra2=$p_val['jrn'];
  $w->label='';
  $w->table=0;

  $s=new widget('span');
  
  if ( isset($p_val['poste_comptable']))
    {
      $w->value=$p_val['poste_comptable'];
      $cn=DbConnect($_SESSION['g_dossier']);
      $f=new fiche($cn);
      $f->GetByQCode($p_val['poste_comptable']);
      $s->value=$f->strAttribut(ATTR_DEF_NAME);
  }
  echo '<input type="hidden" name="code" value="'.$p_val['code'].'">';
  echo '<input type="hidden" name="count" value="'.$counter.'">';
  echo '<table border="1" width="500">';
  echo '<tr><td width="200">'.$p_val['code'].'</td><td width="200">'.$p_val['date_exec'].'</td><td width="100">'.$p_val['montant'].' EUR</td><tr/>';
  echo '<tr><td height="50" colspan="3">'.$p_val['detail'].'</td><tr/>';
  echo '<tr><td>'.$w->IOValue().' '.$s->IOValue('poste'.$counter.'_label').'</td>';
  echo "<td>n° compte : ".$p_val['num_compte']."</td>";
  if ( $p_form == 'form')
    echo '<td><input type="submit" value="modifier"></td><tr/>';
  echo '</table>';
  
}

/*!\brief Verify the import
 */

function VerifImport($p_cn){
	$sql = "select * from import_tmp where poste_comptable='' or poste_comptable is null";
	$Res=ExecSql($p_cn,$sql);
	$Num=pg_NumRows($Res);
	echo $Num." opérations à complèter.<br/><br/>";
	$i=1;
	// include javascript for popup 
	echo JS_SEARCH_CARD;
	while($val = pg_fetch_array($Res)){
	  echo '<form METHOD="POST" action="import.php?action=verif">'; 
	  ShowBox($val,$i);
	  echo '</form>';
	  $i++;
	}

}
/*!\brief ConfirmCSV shows the operation which are going to be transfered
 *  
 * \param $p_cn database conx       
 * \param $periode user's periode
 */
function ConfirmTransfert($p_cn,$periode){
  $sql = "select to_char(p_start,'DD-MM-YYYY') as p_start,to_char(p_end,'DD-MM-YYYY') as p_end".
    " from parm_periode where p_id = '".$periode."'";
  $Res=ExecSql($p_cn,$sql);
  $val = pg_fetch_array($Res);
  if ( $val == false )
    {
      echo "<script>".
	"alert ('Vous devez selectionner votre période dans vos préférences');".
	"</script>";
      exit();
    }
  $start ="to_date('".$val['p_start']."','DD-MM-YYYY')";   
  $end = "to_date('".$val['p_end']."','DD-MM-YYYY')";

  $sql = "select code,to_char(date_exec,'DD.MM.YYYY') as date_exec, ".
    " montant,num_compte,poste_comptable,bq_account,jrn,detail ".
    " from import_tmp where poste_comptable is not null and  poste_comptable <> '' 
          and   ok <> TRUE AND date_exec BETWEEN ".$start." and ".$end;
  

	
  $Res=ExecSql($p_cn,$sql);
  $Num=pg_NumRows($Res);
  echo $Num." opérations à complèter.<br/><br/>";
  echo '<form method="post" action="import.php">';
  $i=1;
  while($val = pg_fetch_array($Res)){
    ShowBox($val,$i,'disabled');
    $i++;
  }
  echo '<input type="hidden" name="action" value="transfer">';
  echo '<input type="submit" name="sub" value="Commencer le transfert">';
  echo '</form>';

}

/*!\brief Transfert data into the ledger
 * \param $p_cn connx
 * \param $periode periode 
 */

function TransferCSV($p_cn, $periode){
	//on obtient la période courante
  $User=new cl_user($p_cn);
  $periode = $User->GetPeriode();
  // on trouve les dates frontières de cette période
  $sql = "select to_char(p_start,'DD-MM-YYYY') as p_start,to_char(p_end,'DD-MM-YYYY') as p_end".
    " from parm_periode where p_id = '".$periode."'";
  $Res=ExecSql($p_cn,$sql);
  $val = pg_fetch_array($Res);
  if ( $val == false )
    {
      echo "<script>".
	"alert ('Vous devez selectionner votre période dans vos préférences');".
	"</script>";
      exit();
    }
  $start ="to_date('".$val['p_start']."','DD-MM-YYYY')";   
  $end = "to_date('".$val['p_end']."','DD-MM-YYYY')";
  // var_dump($val);
  $sql = "select code,to_char(date_exec,'DD.MM.YYYY') as date_exec, ".
    " montant,num_compte,poste_comptable,bq_account,jrn,detail ".
    " from import_tmp where poste_comptable is not null and  poste_comptable <> '' 
          and   ok <> TRUE AND date_exec BETWEEN ".$start." and ".$end;
  $Res=ExecSql($p_cn,$sql);
  //echo "boucle: ".sizeof($Res)."<br/>";
  //while($val = pg_fetch_array($Res)){
  $Max=pg_NumRows($Res);
  echo $Max." opérations à transférer.<br/>";
  StartSql($p_cn);

  for ($i = 0;$i < $Max;$i++) {
    $val=pg_fetch_array($Res,$i);
    
    $code=$val['code']; $date_exec=$val['date_exec']; $montant=$val['montant']; $num_compte=$val['num_compte']; 
    $poste_comptable=$val['poste_comptable'];$bq_account=$val['bq_account'];
    $jrn=$val['jrn'];
    
// Retrieve the account thx the quick code    
    $f=new fiche($p_cn);
    $f->GetByQCode($poste_comptable,false);
    $poste_comptable=$f->strAttribut(ATTR_DEF_ACCOUNT);

    // Vérification que le poste comptable trouvé existe
    if ( $poste_comptable == '- ERROR -')
      $test=0;
      else
	{
	  $sqltest = "select * from tmp_pcmn WHERE pcm_val='".$poste_comptable."'";
	  
	  $Restest=ExecSql($p_cn,$sqltest);
	  $test=pg_NumRows($Restest);
	}

    // Test it
    if($test == 0) {
      $sqlupdate = "update import_tmp set poste_comptable=null,  ok='f' WHERE code='".$code."' AND num_compte='".$num_compte."' or num_compte is null";
      $Resupdate=ExecSql($p_cn,$sqlupdate);
      echo "Poste comptable erronné pour l'opération ".$num_compte."-".$code.", réinitialisation du poste comptable<br/>";
      continue;
    }
	 
      
      // Finances
      
      $seq=NextSequence($p_cn,'s_grpt');
      $p_user = $_SESSION['g_user'];

      $r=InsertJrnx($p_cn,"d",$p_user,$jrn,$bq_account,$date_exec,$montant,$seq,$periode);
      if ( $r == false) { $Rollback($p_cn);exit("error 'import_inc.php' __LINE__");}
      
      $r=InsertJrnx($p_cn,"c",$p_user,$jrn,$poste_comptable,$date_exec,$montant,$seq,$periode);
      if ( $r == false) { $Rollback($p_cn);exit("error 'import_inc.php' __LINE__");}
      
      //remove annoying double-quote
      $num_compte=str_replace('"','',$num_compte);
      $code=str_replace('\"','',$code);
      if ( strlen(trim($num_compte)) == 0 )
	$num_compte=$val['detail'];

      $r=InsertJrn($p_cn,$date_exec,NULL,$jrn,$num_compte." ".$code,$montant,$seq,$periode);
      if ( $r == false ) { Rollback($p_cn); exit(" Error 'import_inc.php' __LINE__");}
      
      SetInternalCode($p_cn,$seq,$jrn);
      
      
      echo "Tranfer de l'opération ".$code." effectué<br/>";
      $sql2 = "update import_tmp set ok=TRUE where code='".$code."'";
      $Res2=ExecSql($p_cn,$sql2);
      
    	
  }
  Commit($p_cn);

}
/*! 
 **************************************************
 * \brief  ShowForm for getting data about 
 *           the bank transfert in cvs
 *        
 * \param  $p_cn  database connection
 *	
 * \return none
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
