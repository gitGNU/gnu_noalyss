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

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
// $Revision$
/*!\file
 * \brief contains function for the printing
 * \todo the functions of impress_inc.php should be replaced in a OO way
*/
require_once('class_periode.php');

/*! 
 * \brief
 * \param $p_cn database connection
 * \param $p_jr_id jrn.jr_id not centralized 
 *                 or jr_op_id normal ledger centralized or jr_c_op_id if GL and centralized
 * \param $p_jrn_id id of the ledger (0 for GL)
 * \param $p_exercice
 * \param $p_which LAST or FIRST
 * \param $p_type CENT for centralized otherwise empty
 * 
 *
 * \return array sum(HTVA) sum(TVAC) sum (each TVA)
 */
function get_rappel_simple ($p_cn,$p_jrn_id,$p_jrn_type,$p_from,&$arap) 
{
  echo_debug("impress_inc",__LINE__,"function get_rappel_simple ($p_cn,$p_jrn_id,$p_jrn_type,$p_from,$arap) ");
  if ( $p_jrn_type !='VEN' && $p_jrn_type != "ACH")
    {
      echo "ERREUR Journal invalide $p_jrn_type __FILE__ __LINE__";
      exit;
    }
  // find the last operation of the previous periode
  $min=$p_cn->get_value("select max (c_id) from centralized where c_jrn_def=$p_jrn_id ".
		  " and c_date < (select p_start from parm_periode where p_id = $p_from)");
  if ($min == "" ) return 0;
  // Find Exercice
  $periode=new Periode($p_cn,$p_from);
  $Exercice=$periode->get_exercice();

  $a_Tva=$p_cn->get_array("select tva_id,tva_label,tva_poste from tva_rate where tva_rate != 0.0000 order by tva_id");
  
  // Compute VAT
  foreach ($a_Tva as $line_tva)
    {
      list ($deb,$cred)=explode(',',$line_tva['tva_poste']);
      if ( $p_jrn_type == 'ACH' )
	$ctva=$deb;
      else
	$ctva=$cred;
      $sum_deb=$p_cn->get_value("select sum(j_montant) from (select distinct c_internal,j_montant ".
			    " from jrnx join centralized on (j_grpt=c_grp) ".
			   " where c_id < $min and j_poste = '$ctva' and j_debit='t' and ".
			  " c_jrn_def=$p_jrn_id and j_tech_per in ".
			  "    (select p_id from parm_periode where p_exercice='$Exercice') ) as w");

      $sum_cred=$p_cn->get_value("select sum(j_montant) from (select distinct c_internal,j_montant ".
			    " from jrnx join centralized on (j_grpt=c_grp) ".
			   " where c_id < $min and j_poste = '$ctva' and j_debit='f' and ".
			  " c_jrn_def=$p_jrn_id and j_tech_per in ".
			  "    (select p_id from parm_periode where p_exercice='$Exercice') ) as w");



      $ix=$line_tva['tva_label'];
      $arap[$ix]=($p_jrn_type=='ACH')?$sum_deb-$sum_cred:$sum_cred-$sum_deb;
    }
  // Previous period
  $previous=$p_cn->get_value("select max(p_id) from parm_periode where ".
		       "p_end < (select p_end from parm_periode where p_id=$p_from) ".
		       " and p_start <= (select p_start from parm_periode where p_id=$p_from)");

  $j=new Acc_Ledger($p_cn,$p_jrn_id);
  $a=$j->get_rowSimple($previous,$previous,$cent='on');
  $total_tvac=0.0;
  $total_htva=0.0;
  foreach ($a as $line) {
    $total_tvac+=$line['TVAC'];
    $total_htva+=$line['HTVA'];
  }
  return array($total_tvac,$total_htva);
}
/*!
 * \brief  Get the amount on each page
 *
 * \param $p_cn
 * \param $p_jrnx_id jrnx.j_id
 * \param $p_jrn_id jr_def_id
 * \param $which LAST or FIRST
 * \param  $p_type valeur JRN GL-CENTRAL GL-NOCENTRAL
 * \return array sum (deb) sum(cred)
 *
 */ 
function get_rappel($p_cn,$p_jrnx_id,$p_jrn_id,$p_exercice,$which,$p_type,$p_central) 
{
  if ( $which == LAST) 
    $cmp="<="; 
  else
    $cmp="<";

  if ( $p_type == 1 ) {
    // Vue filtree => Journaux
    if ( $p_central == 0 ) { // Vue non centralisée
	  return array(0,0);
    } // p_central == 0

    //     Vue filtree => Journaux & Jrn centralisé 
    if ( $p_central == 1 ) {
      $c_line=$p_cn->count_sql("select * from centralized left join parm_periode on c_periode=p_id ".
		       " where c_jrn_def=$p_jrn_id and  p_exercice='".$p_exercice."'".
		       " and c_order $cmp $p_jrnx_id ");
      
      if ($c_line == 0 ) { return array (0,0); }
      $sql="select sum(c_montant) as tot_amount ".
	" from centralized ".
	" left join parm_periode on c_periode=p_id ".
	" inner join jrn on jr_grpt_id=c_grp ".
	" where c_jrn_def=$p_jrn_id and ".
	" p_exercice='".$p_exercice."'".
	" and c_order $cmp $p_jrnx_id " ;
      $Res=$p_cn->exec_sql($sql." and c_debit='t' ");
      if ( Database::num_row($Res) == 0 ) 
	$deb=0;
      else {
	$line=Database::fetch_array($Res,0);
	$deb=$line['tot_amount'];
      }
      
      $Res=$p_cn->exec_sql($sql." and c_debit='f' ");
      if ( Database::num_row($Res) == 0 ) 
	$cred=0;
      else {
	
	$line=Database::fetch_array($Res,0);
	$cred=$line['tot_amount'];
      }
      echo_debug('impress_inc.php',__LINE__,"MONTANT $deb,$cred");
      $a=array($deb,$cred);
      return $a;

    }
  } // Type = jrn
  if ($p_type==0 ) { // Si Grand Livre, prendre donnée centralisée{
    if ( $p_central == 1) {
      $c_line=$p_cn->count_sql("select * from centralized left join parm_periode on c_periode=p_id ".
		       "where p_exercice='".$p_exercice."'".
		       " and c_id $cmp $p_jrnx_id ");
      
      if ($c_line == 0 ) { return array (0,0); }
      $sql="select sum(c_montant) as tot_amount ".
	" from centralized ".
	" left join parm_periode on c_periode=p_id ".
	" where ".
	" p_exercice='".$p_exercice."'".
	" and c_order $cmp $p_jrnx_id " ;
      $Res=$p_cn->exec_sql($sql." and c_debit='t' ");
      if ( Database::num_row($Res) == 0 ) 
	$deb=0;
      else {
	$line=Database::fetch_array($Res,0);
	$deb=$line['tot_amount'];
      }
      
      $Res=$p_cn->exec_sql($sql." and c_debit='f' ");
      if ( Database::num_row($Res) == 0 ) 
	$cred=0;
      else {
	
	$line=Database::fetch_array($Res,0);
	$cred=$line['tot_amount'];
      }
      echo_debug('impress_inc.php',__LINE__,"MONTANT $deb,$cred");
      $a=array($deb,$cred);
      return $a;
    } // central == 1
    else // Donnée non centralisée => pas de rappel
      {
	if ($p_central== 0) { // Si Grand Livre, prendre donnée non centralisée{
	  return array(0,0);
	}
      }//else
  } // if type==0
  
}

/*! \brief   Purpose Parse a formula
 * 
 * \param $p_cn connexion
 * \param $p_label
 * \param $p_formula
 * \param $p_eval  true if we eval here otherwise the function returns
 *                 a string which must be evaluated
 * \param $p_type_date : type of the date 0 for accountant period or 1
 * for calendar
 * \return array
 *
 *
 */ 
function ParseFormula($p_cn,$p_label,$p_formula,$p_start,$p_end,$p_eval=true,$p_type_date=0) 
{

  echo_debug('impress_inc',__LINE__,'ParseFormula');
  if ( CheckFormula($p_formula) == false) {
    if ( $p_eval == true)
      return array('desc'=>$p_label.'  Erreur Formule!',
		'montant'=>0);
    else
      return $p_formula;
    
  }
  if ( $p_type_date == 0 )
	$cond=sql_filter_per($p_cn,$p_start,$p_end,'p_id','j_tech_per');
  else
	$cond="( j_date >= to_date('$p_start','DD.MM.YYYY') and j_date <= to_date('$p_end','DD.MM.YYYY'))";
echo_debug(__FILE__,__LINE__,"receiving $p_formula");
  include_once("class_acc_account_ledger.php");  
  while (myereg("(\[[0-9]*%*D*C*S*\])",$p_formula,$e) == true) {

    // remove the [ ] 
    $x=$e;
    $compute='all';
    if ( strpos($e[0],'D') != 0 )
      $compute='deb';
    if ( strpos($e[0],'C') != 0 )
      $compute='cred';
    if ( strpos($e[0],'S') != 0 )
      $compute='signed';
    echo_debug(__FILE__,__LINE__,' $e = '.$e[0]);
    echo_debug(__FILE__,__LINE__,' $e = '.$e[0]);
    $e[0]=str_replace ("[","",$e[0]);
    $e[0]=str_replace ("]","",$e[0]);
    $e[0]=str_replace ("D","",$e[0]);
    $e[0]=str_replace ("C","",$e[0]);
    $e[0]=str_replace ("S","",$e[0]);
    echo_debug('impress_inc',__LINE__,"p_formula is $p_formula");
    // If there is a FROM clause we must recompute 
    // the time cond

    if ($p_type_date == 0 && myereg ("FROM=[0-9]+\.[0-9]+", $p_formula,$afrom) == true ){
      // There is a FROM clause 
      // then we must modify the cond for the periode
      $from=str_replace("FROM=","",$afrom[0]);

      // Get the periode 
      /*! \note special value for the clause FROM=00.0000
       */
      if ( $from == '00.0000' ) {

		// retrieve the first month of this periode
		$User=new User($p_cn);
		$user_periode=$User->get_periode();
		$oPeriode=new Periode($p_cn);
		$periode=$oPeriode->get_exercice($user_periode);
		list($first,$last)=$oPeriode->get_limit($periode);
		$ret=$first->get_date_limit();
		$end_date=$oPeriode->get_date_limit($p_end);
		if ($ret == null ) throw new Exception ('Pas de limite à cette période',1);
		$cond=sql_filter_per($p_cn,$ret['p_start'],$end_date['p_end'],'date','j_tech_per');	


      }  else {
	$oPeriode=new Periode($p_cn);
	try {
	  $from=$oPeriode->find_periode('01'.$from);
	} catch (Exception $exp)  {
	  /* if none periode is found
	     then we take the first periode of the year
	     */
	  $User=new User($p_cn);
	  $user_periode=$User->get_periode();

	  $year=$oPeriode->get_exercice($user_periode);
	  list($first,$last)=$oPeriode->get_limit($year);
	  $ret=$first->get_date_limit();
	  $end_date=$oPeriode->get_date_limit($p_end);
	  if ($ret == null ) throw new Exception ('Pas de limite à cette période',1);
	  $cond=sql_filter_per($p_cn,$ret['p_start'],$end_date['p_end'],'date','j_tech_per');	
	}
      }
    } 

    if ( strpos($p_formula,"FROM") != 0) {
      // We remove FROM out of the p_formula
      $p_formula=substr_replace($p_formula,"",strpos($p_formula,"FROM"));
    }

      // Get sum of account
    $P=new Acc_Account_Ledger($p_cn,$e[0]);
    $detail=$P->get_solde_detail($cond);


    if ( $compute=='all')
      $i=$detail['solde'];
    if ( $compute=='deb')
      $i=$detail['debit'];
    if ( $compute=='cred')
      $i=$detail['credit'];
    if ( $compute=='signed')
      $i=$detail['debit']-$detail['credit'];
echo_debug(__FILE__,__LINE__,"Resultat = $i ");
    $p_formula=str_replace($x[0],$i,$p_formula);
echo_debug(__FILE__,__LINE__,"p_formula = $p_formula ");

  }

  // $p_eval is true then we eval and returns result
  if ( $p_eval == true) {
    $p_formula="\$result=".$p_formula.";";
    echo_debug('impress_inc.php',__LINE__, $p_formula);

    eval("$p_formula");

    while (myereg("\[([0-9]+)([Tt]*)\]",trim($p_label),$e) == true) {
        $nom = "!!".$e[1]."!!";
        if (CheckFormula($e[0])) {
	  $nom = $p_cn->get_value ( "SELECT pcm_lib AS acct_name FROM tmp_pcmn WHERE pcm_val::text LIKE $1||'%' ORDER BY pcm_val ASC LIMIT 1",array($e[1]));
            if($nom) {
              if($e[2] == 'T') $nom = strtoupper($nom);
              if($e[2] == 't') $nom = strtolower($nom);
            }
        }
        $p_label = str_replace($e[0], $nom, $p_label);
    }

    $aret=array('desc'=>$p_label,
		'montant'=>$result);
    return $aret;
  } else {
    // $p_eval is false we returns only the string
    return $p_formula;
  }
}
/*!
 * \brief  Check if formula doesn't contain
 *           php injection
 * \param string
 *
 * \return true if the formula is good otherwise false
 */
function CheckFormula($p_string) {
  // the myereg gets too complex if we want to add a test
  // for parenthesis, math function...
  // So I prefer remove them before testing
  $p_string=str_replace("round","",$p_string);
  $p_string=str_replace("abs","",$p_string);
  $p_string=str_replace("(","",$p_string);
  $p_string=str_replace(")","",$p_string);
  // for  the inline test like $a=(cond)?value:other;
  $p_string=str_replace("?","+",$p_string);
  $p_string=str_replace(":","+",$p_string);
  $p_string=str_replace(">=","+",$p_string);
  $p_string=str_replace("<=","+",$p_string);
  $p_string=str_replace(">","+",$p_string);
  $p_string=str_replace("<","+",$p_string);
  // eat Space 
  $p_string=str_replace(" ","",$p_string);
  // Remove D/C/S
  $p_string=str_replace("C","",$p_string);
  $p_string=str_replace("D","",$p_string);
  $p_string=str_replace("S","",$p_string);
  // Remove T,t
  $p_string=str_replace("T","",$p_string);
  $p_string=str_replace("t","",$p_string);

  if ( myereg ("^(\\$[a-zA-Z]*[0-9]*=){0,1}((\[{0,1}[0-9]+\.*[0-9]*%{0,1}\]{0,1})+ *([+-\*/])* *(\[{0,1}[0-9]+\.*[0-9]*%{0,1}\]{0,1})*)*(([+-\*/])*\\$([a-zA-Z])+[0-9]*([+-\*/])*)* *( *FROM=[0-9][0-0].20[0-9][0-9]){0,1}$",$p_string) == false)
    {
      return false;
    } else {
      return true;
  }
}

?>
