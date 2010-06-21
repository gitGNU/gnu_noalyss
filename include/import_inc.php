
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
/*!\file 
 * \brief import operation into the accountancy
 */
// Copyright Author Olivier Dzwoniarkiewicz
// Modified Dany De Bontridder ddebontridder@yahoo.fr
// $Revision$
include_once("jrn.php");
include_once("user_common.php");
require_once("class_icard.php");
require_once("class_iconcerned.php");
require_once("class_ispan.php");
require_once("class_iselect.php");
require_once('class_user.php');
require_once('class_fiche.php');
require_once('class_acc_ledger.php');
require_once("class_acc_parm_code.php");
require_once('class_acc_operation.php');
require_once('class_ipopup.php');
echo js_include('prototype.js');
echo js_include('scriptaculous.js');
echo js_include('effects.js');
echo js_include('controls.js');
echo js_include('dragdrop.js');
echo js_include('bq_import.js');
echo js_include('acc_ledger.js');

echo JS_CARD;
echo ICard::ipopup('ipopcard');
$search_card=new IPopup('ipop_card');
$search_card->title=_('Recherche de fiche');
$search_card->value='';
echo $search_card->input();

/*! 
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
 *@todo this function doesn't update the table quant_fin, it must be done
 * Run first the fill_quant_fin 
 *@see upgrade76.sql
 */
function ImportCSV($p_cn,$file,$p_bq_account,$p_format_csv,$p_jrn)
{
  if(!$handle = fopen($file, "r")) {
    print 'could not open file. quitting';
    die;
  }
  
$p_cn->start();

        
// include the right format for CSV --> given by the <form
require_once($p_format_csv);


echo _("Importation terminée");

// if importation succeeds then we can commit the change
$p_cn->commit();

}

/*!\brief This function show a record from the table import_tmp, the tag for the form
 *        are not included in the function and must set in the calling proc.
 * \param $p_val array (row from import_type)
 * \param $counter a counter used in the form
 * \param $p_cn database connection
 * \param $p_form indicates if the button for the form is enable,
 *        modify the Quick Code or remove record poss.value are form, remove
 */
function ShowBox($p_val,$counter,$p_cn,$p_form='form'){
   $w=new ICard();
  $w->jrn=$p_val['jrn'];
  $w->name='poste'.$counter;
  $w->extra='filter';
  $w->typecard='cred';
  $w->set_dblclick("fill_ipopcard(this);");
  $w->set_attribute('ipopup','ipopcard');
  $w->set_callback('filter_card');
  $w->set_function('fill_data');

  $w->table=0;
  if ( $p_form == 'remove' )
    $w->readOnly=true;

  $oJrn=new Acc_Ledger($p_cn,$p_val['jrn']);
  // widget concerned
  $wConcerned=new IConcerned();
  $wConcerned->name="e_concerned"+$counter;
  $wConcerned->extra=abs($p_val['montant']);
  $wConcerned->extra2='paid';
  $wConcerned->label=_('op. concernée');
  $wConcerned->table=0;
  $wConcerned->value=$p_val['jr_rapt'];

  $s=new ISpan();

  // if in readonly retrieve the conc. ope
  if ( $p_form== 'remove') {
    $wConcerned->readOnly=true;
  }

  if ( isset($p_val['poste_comptable']))
    {
      $w->value=$p_val['poste_comptable'];
      $cn=new Database(dossier::id());
      $f=new fiche($p_cn);
      $f->get_by_qcode($p_val['poste_comptable']);
      $s->value=$f->strAttribut(ATTR_DEF_NAME);
  }
  echo '<input type="hidden" id="code'.$counter.'" value="'.$p_val['code'].'">';
  echo '<input type="hidden" name="count" value="'.$counter.'">';
  echo HtmlInput::hidden('p_jrn',$p_val['jrn']);
  echo '<table border="1" width="500">';
  echo '<tr><td width="200">'.$p_val['code'].'</td><td width="200">'.$p_val['date_exec'].'</td><td width="100">'.$p_val['montant'].' EUR</td><tr/>';
  echo "<tr><td>"._('Journal')." : ".$oJrn->get_name()."</TD><TD>"._('poste comptable Destination')." : ".$p_val['bq_account']."</td><tr>";
  echo '<tr colspan="3"><td height="50" colspan="3">'.$p_val['detail'].'</td></tr>';
  echo '<tr><td  colspan="3"> '.$wConcerned->input("e_concerned".$counter).'</td></tr>';
  echo '<tr><td>'.$w->search().$w->input().' '.$s->input('poste'.$counter.'_label').
   "</TD>";

  echo "<td>n° compte : ".$p_val['num_compte']."</td>";
  if ( $p_form == 'form') {
    $str_update=sprintf("import_update('%s','%s');",
		 dossier::id(),
		 $counter);
    $str_remove=sprintf("import_remove('%s','%s');",
		 dossier::id(),
		 $counter);
		 
    echo '<td><input type="button" value="Modifier" onClick="'.$str_update.'">';
    echo '<input type="button" name="trashit" value="Effacer.."'.
      ' onClick="'.$str_remove.'" >'.
      '</td><tr/>';
  }
  if ($p_form == 'remove' ) {
    $str_notconfi=sprintf("import_not_confirmed('%s','%s');",
		 dossier::id(),
		 $counter);

    echo '<td><input type="button" value='._("Enlever").' onClick="'.$str_notconfi.'"'.
      '></td><tr/>';
  }

  echo '</table>';
  
}
/*!\brief Verify the import
 */

function VerifImport($p_cn){
	$sql = "select * from import_tmp where status='n' ".
	  " order by date_exec,code";
	$Res=$p_cn->exec_sql($sql);
	$Num=Database::num_row($Res);
	echo $Num._(" opérations à complèter")."<br/><br/>";
	// include javascript for popup 
	echo JS_CARD;
	echo JS_LEDGER;
	echo JS_AJAX_FICHE;
	for ( $i=0;$i< $Num;$i++){
	  $val = Database::fetch_array($Res,$i);
	  echo '<form METHOD="POST" id="form_'.$i.'"action="import.php?action=verif">'; 
	  echo dossier::hidden();
	  ShowBox($val,$i,$p_cn,'form');
	  echo '</form>';
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
  $Res=$p_cn->exec_sql($sql);
  $val = Database::fetch_array($Res);
  if ( $val == false )
    {
      alert (_('Vous devez selectionner votre période dans vos préférences'));
      exit();
    }
  $start ="to_date('".$val['p_start']."','DD-MM-YYYY')";   
  $end = "to_date('".$val['p_end']."','DD-MM-YYYY')";

  $sql = "select code,to_char(date_exec,'DD.MM.YYYY') as date_exec, ".
    " montant,num_compte,poste_comptable,bq_account,jrn,detail,jr_rapt ".
    " from import_tmp where 
          status = 'w' AND date_exec BETWEEN ".$start." and ".$end;
    

	
  $Res=$p_cn->exec_sql($sql);
  $Num=Database::num_row($Res);
  echo $Num." "._("opérations à transfèrer")."<br/><br/>";
  if ( $Num == 0 ) return;

  for ( $i=0;$i<$Num;$i++){
    $val = Database::fetch_array($Res,$i);
    echo '<form method="post" id="form_'.$i.'" action="import.php">';
	echo dossier::hidden();
    echo '<input type="hidden" name="action" value="remove">';
    ShowBox($val,$i,$p_cn,'remove');
    echo '</form>';
  }
  echo '<form method="post" id="form_'.$i.'" action="import.php">';
  echo dossier::hidden();
  /*  to avoid double post */
  $mt=microtime(true);
  echo HtmlInput::hidden('mt',$mt);
  echo HtmlInput::hidden("action" ,"transfer");
  echo HtmlInput::hidden("period" ,$periode);
  echo HtmlInput::submit("sub",_("Commencer le transfert"));
  echo '</form>';

}

/*!\brief Transfert data into the ledger
 *        set the column import_tmp.status to w (wait) if the account is not correct
 *        otherwise transfert it to the ledger and set the column import_tmp.status
 *        to t (transfert)
 * \param $p_cn connx
 * \param $periode periode 
 */

function TransferCSV($p_cn, $periode){
  //on obtient la période courante
  $User=new User($p_cn);
  $periode = $User->get_periode();
	
  // on trouve les dates frontières de cette période
  $sql = "select to_char(p_start,'DD-MM-YYYY') as p_start,to_char(p_end,'DD-MM-YYYY') as p_end".
    " from parm_periode where p_id = '".$periode."'";

  $Res=$p_cn->exec_sql($sql);
  $val = Database::fetch_array($Res);
  if ( $val == false )
    {
      alert (_('Vous devez selectionner votre période dans vos préférences'));
      exit();
    }

  $start ="to_date('".$val['p_start']."','DD-MM-YYYY')";   
  $end = "to_date('".$val['p_end']."','DD-MM-YYYY')";
  $sql = "select code,to_char(date_exec,'DD.MM.YYYY') as date_exec, ".
    " montant,num_compte,poste_comptable,bq_account,jrn,detail,jr_rapt,it_pj ".
    " from import_tmp where ".
         " status= 'w' AND date_exec BETWEEN ".$start." and ".$end;

  try 
    {
      $p_cn->start();
      $ResAll=$p_cn->exec_sql($sql);
      $Max=Database::num_row($ResAll);
      echo $Max." opérations à transférer.<br/>";
      for ($i = 0;$i < $Max;$i++) {
	$val=Database::fetch_array($ResAll,$i);

	$code=$val['code']; 
	$date_exec=$val['date_exec']; 
	$montant=$val['montant']; 
	$num_compte=$val['num_compte']; 
	$poste_comptable=$val['poste_comptable'];
	$bq_account=$val['bq_account'];
	$jrn=$val['jrn']; 
  	$oJrn=new Acc_Ledger($p_cn,$jrn);
	$detail=$val['detail'];
	$jr_rapt=$val['jr_rapt'];
    
	// Retrieve the account thx the quick code    
	$f=new fiche($p_cn);
	$quick_code=$poste_comptable;
	$f->get_by_qcode($poste_comptable,false);
	$poste_comptable=$f->strAttribut(ATTR_DEF_ACCOUNT);
	$f->get_by_qcode($bq_account);
	$bq_poste=$f->strAttribut(ATTR_DEF_ACCOUNT);
	// Vérification que le poste comptable trouvé existe
	if ( $poste_comptable == '- ERROR -' || strlen(trim($poste_comptable))==0)
	  $test=0;
	else
	  {
	    $sqltest = "select * from tmp_pcmn WHERE pcm_val=$1";
	    $Restest=$p_cn->exec_sql($sqltest,array($poste_comptable));
	    $test=Database::num_row($Restest);
	  }

	// Test it
	if($test == 0) {
	  $sqlupdate = "update import_tmp set status='n' WHERE code=$1  or num_compte is null";
	  $Resupdate=$p_cn->exec_sql($sqlupdate,array($code));
	  echo _("Poste comptable erronné pour l'opération ").$num_compte."-".$code.", ".("réinitialisation du poste comptable")."<br/>";
	  continue;
	}
	 
      
	// Finances
      
	$seq=$p_cn->get_next_seq('s_grpt');
	$p_user = $_SESSION['g_user'];

	$acc_op=new Acc_Operation($p_cn);
	$acc_op->amount=$montant;
	$acc_op->desc=$detail;
	$acc_op->type="d";
	$acc_op->date=$date_exec;
	$acc_op->user=$p_user;
	$acc_op->poste=$bq_poste;
	$acc_op->grpt=$seq;
	$acc_op->jrn=$jrn;
	$acc_op->periode=$periode;
	$acc_op->qcode=$bq_account;
	$acc_op->mt=$_REQUEST['mt'];
	$r=$acc_op->insert_jrnx();

      
	$acc_op->type="c";
	$acc_op->poste=$poste_comptable;
	$acc_op->amount=$montant;
	$acc_op->qcode=$quick_code;
	$r=$acc_op->insert_jrnx();


      
	//remove annoying double-quote
	$num_compte=str_replace('"','',$num_compte);
	$code=str_replace('\"','',$code);
	$acc_op->comment=$detail.$num_compte." ".$code;

	$jr_id=$acc_op->insert_jrn();
	$sql="update jrn set jr_pj_number=$1 where jr_id=$2";
	$p_cn->exec_sql($sql,array($val['it_pj'],$jr_id));

      	$internal=$oJrn->compute_internal_code($seq);

	$Res=$p_cn->exec_sql("update jrn set jr_internal=$1 where jr_id = $2",array($internal,$jr_id));

      // insert rapt

	$acc_reconc=new Acc_Reconciliation($p_cn);
	$acc_reconc->set_jr_id=$jr_id;
	$acc_reconc->insert($jr_rapt);
	
	echo _("Tranfert de l'opération ").$code._(" effectué")."<br/>";
	$sql2 = "update import_tmp set status='t' where code='".$code."'";
	$Res2=$p_cn->exec_sql($sql2);
      } 
    }	catch (Exception $e) {
      $p_cn->rollback();
      echo '<span class="error">'.
	'Erreur dans '.__FILE__.':'.__LINE__.
	' Message = '.$e->getMessage().
	'</span>';
    }
  
  $p_cn->commit();
  
}
/*! 
* \brief  ShowForm for getting data about 
*           the bank transfert in cvs
*        
* \param  $p_cn  database connection
*/

function ShowFormTransfert($p_cn){
$w=new ISelect();
  echo '<FORM METHOD="POST" action="import.php?action=import" enctype="multipart/form-data">';
  echo dossier::hidden();
  echo '<INPUT TYPE="file" name="fupload" size="20"><br>';
  // ask for the journal target 
  $jrn=$p_cn->make_array ("select jrn_def_id,jrn_def_name from jrn_def where jrn_def_type='FIN';");
  $w->label=_('Journal');
  echo $w->label." :".$w->input('import_jrn',$jrn)."<br>";
  // choose the bank account
  $banque=new Acc_Parm_Code($p_cn,'BANQUE');
  $caisse=new Acc_Parm_Code($p_cn,'CAISSE');
  $sql="select j_qcode,vw_name from vw_poste_qcode join vw_fiche_attr on (j_qcode=quick_code) where j_poste::text like ".$caisse->p_value."||'%'
  or j_poste::text like ".$banque->p_value."::text||'%'";
  $bq=$p_cn->make_array($sql);
  $w->label='Banque';
  echo "Compte en banque :".$w->input('import_bq',$bq)."<br>";
  $format_csv=$p_cn->make_array("select include_file,name from format_csv_banque;");
  $w->label="Format import";
  echo $w->label.$w->input('format_csv',$format_csv).'<br>';
  echo HtmlInput::submit("Import fiche",_("Import fiche"));
  echo '</FORM>';
}

?>
