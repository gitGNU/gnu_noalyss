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
require_once('class_fiche.php');
require_once('class_user.php');
require_once ('class_widget.php');
require_once ('class_dossier.php');
require_once ('class_own.php');
require_once ('class_anc_operation.php');
require_once ('class_acc_operation.php');
require_once ('class_acc_account_ledger.php');
require_once ('class_pre_op_advanced.php');
require_once ('jrn.php');
require_once ('class_acexception.php');
require_once ('class_acc_reconciliation.php');
require_once ('class_periode.php');
/*!\file
* \brief Class for jrn,  class acc_ledger for manipulating the ledger
*/

/*!\brief Class for jrn,  class acc_ledger for manipulating the ledger
*
*/
class Acc_Ledger {
  var $id;			/*!< jrn_def.jrn_def_id */
  var $name;			/*!< jrn_def.jrn_def_name */
  var $db;			/*!< database connextion */
  var $row;			/*!< row of the ledger */
  var $type;			/*!< type of the ledger ACH ODS FIN
				  VEN or GL */
  var $nb;			/*!< default number of rows by
				  default 10 */

  function Acc_Ledger ($p_cn,$p_id){
    $this->id=$p_id;
    $this->db=$p_cn;
    $this->row=null;
    $this->nb=10;
  }
  /*! 
   * \brief Return the type of a ledger (ACH,VEN,ODS or FIN) or GL 
   * 
   */
  function get_type() {
    if ( $this->id==0 ) {
      $this->name=" Grand Livre ";
      $this->type="GL";
      return "GL";
    }

    $Res=ExecSql($this->db,"select jrn_def_type from ".
		 " jrn_def where jrn_def_id=".
		 $this->id);
    $Max=pg_NumRows($Res);
    if ($Max==0) return null;
    $ret=pg_fetch_array($Res,0);
    $this->type=$ret['jrn_def_type'];
    return $ret['jrn_def_type'];
  }
  /*! 
   * \brief Return the name of a ledger 
   * 
   */
  function get_name() {
    if ( $this->id==0 ) {
      $this->name=" Grand Livre ";
      return $this->name;
    }
  
    $Res=ExecSql($this->db,"select jrn_def_name from ".
		 " jrn_def where jrn_def_id=".
		 $this->id);
    $Max=pg_NumRows($Res);
    if ($Max==0) return null;
    $ret=pg_fetch_array($Res,0);
    $this->name=$ret['jrn_def_name'];
    return $ret['jrn_def_name'];
  }
 
 
  /*! \function  get_row
   * \brief  Get The data 
   * 
   *
   * \param p_from from periode
   * \param p_to to periode
   * \param $p_cent (on or off)
   * \param p_limit starting line
   * \param p_offset number of lines
   *
   * gen :
   *	- none
   * \return Array with the asked data
   *
   */ 
  function get_row($p_from,$p_to,$cent='off',$p_limit=-1,$p_offset=-1) {

    echo_debug('class_acc_ledger.php',__LINE__,"get_row ( $p_from,$p_to,$cent,$p_limit,$p_offset)");
  
    $periode=sql_filter_per($this->db,$p_from,$p_to,'p_id','jr_tech_per');
  
    $cond_limite=($p_limit!=-1)?" limit ".$p_limit." offset ".$p_offset:"";
    // retrieve the type
    $this->get_type();
    // Grand livre == 0
    if ( $this->id != 0 ) {

      if ( $cent=='off' ) {
	echo_debug('class_acc_ledger.php',__LINE__,"journaux non  centralisé");
	// Journaux non centralisés
	$Res=ExecSql($this->db,"select j_id,j_id as int_j_id,to_char(j_date,'DD.MM.YYYY') as j_date,
	      jr_internal,
	case j_debit when 't' then j_montant::text else '   ' end as deb_montant,
	case j_debit when 'f' then j_montant::text else '   ' end as cred_montant,
	j_debit as debit,j_poste as poste,jr_montant , ".
		     "pcm_lib as description,j_grpt as grp,
       jr_comment||' ('||jr_internal||')' as jr_comment ,
       j_qcode,
	jr_rapt as oc, j_tech_per as periode 
      from jrnx left join jrn on ".
		     "jr_grpt_id=j_grpt ".
		     " left join tmp_pcmn on pcm_val=j_poste ".
		     " where j_jrn_def=".$this->id.
		     " and ".$periode." order by j_date::date asc,jr_internal,j_debit desc ".
		     $cond_limite);
      }else {
	// Journaux centralisés
	//      echo'class_acc_ledger.php',__LINE__,"journaux centralisé";
	$Sql="select jr_opid as j_id,
	    c_order as int_j_id,
    to_char (c_date,'DD.MM.YYYY') as j_date ,
    c_internal as jr_internal,
    case c_debit when 't' then c_montant::text else '   ' end as deb_montant,
    case c_debit when 'f' then c_montant::text else '   ' end as cred_montant,
    c_debit as j_debit,
    c_poste as poste,
    pcm_lib as description,
    j_qcode,
    jr_comment||' ('||c_internal||')' as jr_comment,
    jr_montant,
    c_grp as grp,
    c_comment as comment,
    c_rapt as oc,
    c_periode as periode 
    from 
  
  centralized left join jrnx on j_grpt=c_grp 
 left join tmp_pcmn on pcm_val=c_poste 
 left join jrn on jr_grpt_id=c_grp
   where ".
	  " c_jrn_def=".$this->id." and ".
	  $periode." order by c_order ";
	$Res=ExecSql($this->db,$Sql.$cond_limite);

      }
    } else {
      // Grand Livre
      if ( $cent == 'off') {
	echo_debug('class_acc_ledger.php',__LINE__,"Grand livre non centralisé");
	// Non centralisé
	$Res=ExecSql($this->db,"select j_id,j_id as int_j_id,to_char(j_date,'DD.MM.YYYY') as j_date,
	      jr_internal,
	case j_debit when 't' then j_montant::text else '   ' end as deb_montant,
	case j_debit when 'f' then j_montant::text else '   ' end as cred_montant,
	j_debit as debit,j_poste as poste,".
		     "pcm_lib as description,j_grpt as grp,
	jr_comment||' ('||jr_internal||')' as jr_comment,
	jr_montant,
	j_qcode,
	jr_rapt as oc, j_tech_per as periode from jrnx left join jrn on ".
		     "jr_grpt_id=j_grpt left join tmp_pcmn on pcm_val=j_poste where ".
		     "  ".$periode." order by j_date::date,j_grpt,j_debit desc   ".
		     $cond_limite);

      } else {
	echo_debug('class_acc_ledger.php',__LINE__,"Grand livre  centralisé");
	// Centralisé
	$Sql="select jr_c_opid as j_id,
	   c_order as int_j_id,
    c_j_id,
    to_char (c_date,'DD.MM.YYYY') as j_date ,
    c_internal as jr_internal,
    case c_debit when 't' then c_montant::text else '   ' end as deb_montant,
    case c_debit when 'f' then c_montant::text else '   ' end as cred_montant,
    c_debit as j_debit,
    c_poste as poste,
    pcm_lib as description,
    jr_comment||' ('||c_internal||'/ PJ :'||jr_opid||')' as jr_comment,
    jr_montant,
    c_grp as grp,
    c_comment||' ('||c_internal||' '||jr_opid||')' as comment,
    c_rapt as oc,
    c_periode as periode 
    from centralized left join jrn on ".
	  "jr_grpt_id=c_grp left join tmp_pcmn ".
	  " on (pcm_val=c_poste)  ".
	  "            join jrnx on (j_id=c_j_id)".
	  " where ".
	  $periode." order by c_order ";
	$Res=ExecSql($this->db,$Sql.$cond_limite);
      } // Grand Livre
    }


    $array=array();
    $Max=pg_NumRows($Res);
    if ($Max==0) return null;
    $case="";
    $tot_deb=0;
    $tot_cred=0;
    $row=pg_fetch_all($Res);
    for ($i=0;$i<$Max;$i++) {
      $fiche=new fiche($this->db);
      $line=$row[$i];
      $mont_deb=($line['deb_montant']!=0)?sprintf("% 8.2f",$line['deb_montant']):"";
      $mont_cred=($line['cred_montant']!=0)?sprintf("% 8.2f",$line['cred_montant']):"";
      $jr_montant=($line['jr_montant']!=0)?sprintf("% 8.2f",$line['jr_montant']):"";
      $tot_deb+=$line['deb_montant'];
      $tot_cred+=$line['cred_montant'];
      $tot_op=$line['jr_montant'];
      echo_debug('class_acc_ledger.php',__LINE__," get_row : mont_Deb ".$mont_deb);
      echo_debug('class_acc_ledger.php',__LINE__," get_row : mont_cred ".$mont_cred);

      /* Check first if there is a quickcode */
      if ( strlen(trim($line['j_qcode'])) != 0 ) 
	{
	  if ( $fiche->get_by_qcode($line['j_qcode'],false) == 0 ) 
	    {
	      $line['description']=$fiche->strAttribut(ATTR_DEF_NAME);
	    }
	}
      if ( $case != $line['grp'] ) {
	$case=$line['grp'];
	// for financial, we show if the amount is or not in negative
	if ( $this->type=='FIN') {
	  echo_debug(__FILE__,__LINE__,"Journal FIN");
	  $eMax=(($i+20) < $Max)?$i+20:$Max;
	  // check in $row if the BQE is in deb or cred
	  for ($e=$i;$e<$Max;$e++) {
	    echo_debug(__FILE__,__LINE__,$row[$e]);
	    if ( $row[$e]['grp'] != $case ) continue;
	    if ( strlen(trim($row[$e]['j_qcode'])) == 0 ) continue;
	
	    $f=new fiche($this->db);
	    $f->get_by_qcode($row[$e]['j_qcode'],false);
	    echo_debug(__FILE__,__LINE__,$f);
	    if ( $f->get_fiche_def_ref_id() == FICHE_TYPE_FIN ) {
	      $tot_op=($row[$e]['debit'] == 't')?$jr_montant:" - ".$jr_montant;
	      break;
	    }
	  }
	}
	$array[]=array (
			'int_j_id' => $line['int_j_id'],
			'j_id'=>$line['j_id'],
			'j_date' => $line['j_date'],
			'internal'=>$line['jr_internal'],
			'deb_montant'=>'',
			'cred_montant'=>' ',
			'description'=>'<b><i>'.$line['jr_comment'].' ['.$tot_op.'] </i></b>',
			'poste' => $line['oc'],
			'qcode' => $line['j_qcode'],
			'periode' =>$line['periode'] );

	$array[]=array (
			'int_j_id' => $line['int_j_id'],
			'j_id'=>'', 
			'j_date' => '',
			'internal'=>'',
			'deb_montant'=>$mont_deb,
			'cred_montant'=>$mont_cred,
			'description'=>$line['description'],
			'poste' => $line['poste'],
			'qcode' => $line['j_qcode'],
			'periode' => $line['periode']
			);

      }else {
	$array[]=array (
			'int_j_id' => $line['int_j_id'],
			'j_id'=>'',
			'j_date' => '',
			'internal'=>'',
			'deb_montant'=>$mont_deb,
			'cred_montant'=>$mont_cred,
			'description'=>$line['description'],
			'poste' => $line['poste'],
			'qcode' => $line['j_qcode'],
			'periode' => $line['periode']);

      }


    }
    echo_debug('class_acc_ledger.php',__LINE__,"Total debit $tot_deb,credit $tot_cred");
    $this->row=$array;
    $a=array($array,$tot_deb,$tot_cred);
    return $a;
  }
  /* \brief  Get simplified row from ledger 
   *        
   * \param from periode 
   * \param to periode 
   * \param centralized (on or off) 
   * \param p_limit starting line 
   * \param p_offset number of lines
   * \param trunc if data must be truncated (pdf export)
   *
   * \return an Array with the asked data 
   */
  function get_rowSimple($p_from,$p_to,$cent='off',$trunc=0,$p_limit=-1,$p_offset=-1) 
  {
    // Grand-livre : id= 0
    //---
    $jrn=($this->id == 0 )?"":"and jrn_def_id = ".$this->id;
    // Non Centralise si cent=off
    //--
    if ($cent=='off') 
      {// Non centralisé

	$periode=sql_filter_per($this->db,$p_from,$p_to,'p_id','jr_tech_per');

	$cond_limite=($p_limit!=-1)?" limit ".$p_limit." offset ".$p_offset:"";
	//---
	$sql=" 
	  SELECT jrn.jr_id as jr_id ,
	      jrn.jr_id as num , 
	      jrn.jr_def_id as jr_def_id, 
	      jrn.jr_montant as montant, 
	      substr(jrn.jr_comment,1,30) as comment, 
	      to_char(jrn.jr_date,'DD-MM-YYYY') as date, 
	      jr_internal,
	      jrn.jr_grpt_id as grpt_id, 
	      jrn.jr_pj_name as pj,
	      jrn_def_type,
	       jrn.jr_tech_per
	  FROM jrn join jrn_def on (jrn_def_id=jr_def_id)
	  WHERE $periode $jrn order by jr_date $cond_limite";
      } 
    else 
      {

	$periode=sql_filter_per($this->db,$p_from,$p_to,'p_id','jr_tech_per');

	$cond_limite=($p_limit!=-1)?" limit ".$p_limit." offset ".$p_offset:"";
	//Centralisé
	//---
	$id=($this->id == 0 ) ?"jr_c_opid as num":"jr_opid as num";

	$sql="
    SELECT jrn.jr_id as jr_id ,
	   $id , 
	   jrn.jr_def_id as jr_def_id, 
	   jrn.jr_montant as montant, 
	   substr(jrn.jr_comment,1,30) as comment, 
	   to_char(jrn.jr_date,'DD-MM-YYYY') as date, 
	   jr_internal,
	   jrn.jr_grpt_id as grpt_id, 
	   jrn.jr_pj_name as pj,
	   jrn_def_type,
	   jrn.jr_tech_per 
   FROM jrn join jrn_def on (jrn_def_id=jr_def_id) 
       where 
	 $periode $jrn and 
	 jr_opid is not null
	 order by jr_date  $cond_limite";
      }// end else $cent=='off'
    //load all data into an array
    //---

    $Res=ExecSql($this->db,$sql);
    $Max=pg_NumRows($Res);
    if ( $Max == 0 ) 
      {
	return null;
      } 
    $type=$this->get_type();
    // for type ACH and Ven we take more info
    if (  $type == 'ACH' ||  	  $type == 'VEN') 
      {
	$a_ParmCode=get_array($this->db,'select p_code,p_value from parm_code');
	$a_TVA=get_array($this->db,'select tva_id,tva_label,tva_poste 
			 from tva_rate where tva_rate != 0 order by tva_id');
	for ( $i=0;$i<$Max;$i++) 
	  {
	    $array[$i]=pg_fetch_array($Res); 
	    $p=$this->get_detail($array[$i],$type,$trunc,$a_TVA,$a_ParmCode);
 
	  }
 
      }
    else 
      {
	$array=pg_fetch_all($Res);
 
      }

    return $array;  
  }// end function get_rowSimple

  /*! 
   * \brief get_detail gives the detail of row 
   * this array must contains at least the field
   *       <ul>
   *       <li> montant</li>
   *       <li> grpt_id
   *       </ul>
   * the following field will be added
   *       <ul>
   *       <li> HTVA  
   *       <li> TVAC
   *       <li> TVA array with
   *          <ul>
   *          <li> field 0 idx
   *          <li> array containing tva_id,tva_label and tva_amount
   *          </ul>
   *       </ul> 
   *
   * \param p_array the structure is set in get_rowSimple, this array is 
   *        modified,  
   * \param $trunc if the data must be truncated, usefull for pdf export
   * \param p_jrn_type is the type of the ledger (ACH or VEN)
   * \param $a_TVA TVA Array (default null)
   * \param $a_ParmCode Array (default null)
   * \return p_array 
   */
  function get_detail(&$p_array,$p_jrn_type,$trunc=0,$a_TVA=null,$a_ParmCode=null)
  {
    if ( $a_TVA == null ) 
      {
	//Load TVA array
	$a_TVA=get_array($this->db,'select tva_id,tva_label,tva_poste 
			 from tva_rate where tva_rate != 0 order by tva_id');
      }
    if ( $a_ParmCode == null )
      {
	//Load Parm_code
	$a_ParmCode=get_array($this->db,'select p_code,p_value from parm_code');
      }
    // init
    $p_array['client']="";
    $p_array['TVAC']=0;
    $p_array['TVA']=array();
    $p_array['AMOUNT_TVA']=0.0;
    //
    // Retrieve data from jrnx
    $sql="select j_poste,j_montant, j_debit,j_qcode from jrnx where ".
      " j_grpt=".$p_array['grpt_id'];
    $Res2=ExecSql($this->db,$sql);
    $data_jrnx=pg_fetch_all($Res2);
    $c=0;

    // Parse data from jrnx and fill diff. field
    foreach ( $data_jrnx as $code ) {
      $idx_tva=0;
      echo_debug('class_acc_ledger',__LINE__,'Code is');
      echo_debug('class_acc_ledger',__LINE__,$code);
      $poste=new Acc_Account_Ledger($this->db,$code['j_poste']);

      // if card retrieve name if the account is not a VAT account
      if ( strlen(trim($code['j_qcode'] )) != 0 && $poste->isTva() == 0 )
	{
	  echo_debug('class_acc_ledger',__LINE__,'fiche_def = '.$code['j_qcode']);
	  $fiche=new fiche($this->db);
	  $fiche->get_by_qcode(trim($code['j_qcode']),false);
	  $fiche_def_id=$fiche->get_fiche_def_ref_id();
	  // Customer or supplier
	  if ( $fiche_def_id == FICHE_TYPE_CLIENT ||
	       $fiche_def_id == FICHE_TYPE_FOURNISSEUR ) 
	    {
	      echo_debug('class_acc_ledger',__LINE__,$code['j_qcode'].'est F ou C');
	      $p_array['TVAC']=$code['j_montant'];

	      $p_array['client']=($trunc==0)?$fiche->get_name():substr($fiche->get_name(),0,20);
	      $p_array['reversed']=false;
	      if (	$fiche_def_id == FICHE_TYPE_CLIENT && $code['j_debit']=='f')
		{
		  $p_array['reversed']=true;
		  $p_array['TVAC']*=-1;
	      
		}
	      if (	$fiche_def_id == FICHE_TYPE_FOURNISSEUR && $code['j_debit']=='t')
		{
		  $p_array['reversed']=true;
		  $p_array['TVAC']*=-1;
		}
	  
	  
	    } else {
	    // if we use the ledger ven / ach for others card than supplier and customer
	    if ( $fiche_def_id != FICHE_TYPE_VENTE &&
		 $fiche_def_id != FICHE_TYPE_ACH_MAR && 
		 $fiche_def_id != FICHE_TYPE_ACH_SER ) {
	      echo_debug('class_acc_ledger',__LINE__,$code['j_qcode']."n 'est PAS F ou C");
	      $p_array['TVAC']=$code['j_montant'];
	    
	      $p_array['client']=	($trunc==0)?$fiche->get_name():substr($fiche->get_name(),0,20);
	      $p_array['reversed']=false;
	      if ($p_jrn_type == 'ACH' && $code['j_debit']=='t')
		{
		  $p_array['reversed']=true;
		  $p_array['TVAC']*=-1;
		
		}
	      if ($p_jrn_type == 'VEN'  && $code['j_debit']=='f')
		{
		  $p_array['reversed']=true;
		  $p_array['TVAC']*=-1;
		}
	    
	    
	    
	    
	    }
	  }
	}
      echo_debug('class_acc_ledger',__LINE__,$a_TVA);
      // if TVA, load amount, tva id and rate in array 
      foreach ( $a_TVA as $line_tva) 
	{	      
	  echo_debug('class_acc_ledger',__LINE__,'ICI');
	  echo_debug('class_acc_ledger',__LINE__,'Montant TVA = '.$p_array['AMOUNT_TVA']);
	  list($tva_deb,$tva_cred)=split(',',$line_tva['tva_poste']);
	  if ( $code['j_poste'] == $tva_deb ||
	       $code['j_poste'] == $tva_cred )
	    {
		  
	      // For the reversed operation
	      if ( $p_jrn_type == 'ACH' && $code['j_debit'] == 'f')
		{
		  $code['j_montant']=-1*$code['j_montant'];
		}
	      if ( $p_jrn_type == 'VEN' && $code['j_debit'] == 't')
		{
		  $code['j_montant']=-1*$code['j_montant'];
		}
		  
	      $p_array['AMOUNT_TVA']+=$code['j_montant'];
		  
	      $p_array['TVA'][$c]=array($idx_tva,array($line_tva['tva_id'],$line_tva['tva_label'],$code['j_montant']));
	      echo_debug('class_acc_ledger',__LINE__,'Montant TVA = '.$p_array['AMOUNT_TVA']);
	      $c++;
		  
	      $idx_tva++;
	    }
	}
  
      // isDNA
      // If operation is reversed then  amount are negatif

    }
    $p_array['TVAC']=sprintf('% 10.2f',$p_array['TVAC']);
    $p_array['HTVA']=sprintf('% 10.2f',$p_array['TVAC']-$p_array['AMOUNT_TVA']);
    $r="";
    $a_tva_amount=array();
    // inline TVA (used for the PDF)
    foreach ($p_array['TVA'] as $linetva) 
      {
	foreach ($a_TVA as $tva)
	  {
	    if ( $tva['tva_id'] == $linetva[1][0] )
	      {
		$a=$tva['tva_id'];
		$a_tva_amount[$a]=$linetva[1][2];
	      }
	  }
      }
    foreach ($a_TVA as $line_tva)
      {
	$a=$line_tva['tva_id'];
	if ( isset($a_tva_amount[$a])) 
	  {
	    $tmp=sprintf("% 10.2f",$a_tva_amount[$a]);     
	    //		$r.=str_repeat("_",10-strlen($tmp)).$tmp." ";
	    // $r.=str_repeat(" ",10-strlen($tmp)).$tmp." ";
	    $r.="$tmp";
	  }
	else
	  $r.=sprintf("% 10.2f",0);
	//         $r.=str_repeat(" ",6)."0.00 ";
	//	            $r.=str_repeat("_",6)."0.00 ";
	//$r.="     0.00";
      }
    $p_array['TVA_INLINE']=$r;

    return $p_array;
  }  // retrieve data from jrnx
  /*!
   * \brief  Get the properties of a journal
   *
   * parm :
   *      - p_dossier the folder id
   *      - p_jrn the jrn id
   * gen :
   *      - none
   * return:
   *      - an array containing properties
   *
   */
  function get_propertie()
  { 
    if ( $this->id == 0 ) return;

    $Res=ExecSql($this->db,"select jrn_Def_id,jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_def_type,
	   jrn_deb_max_line,jrn_cred_max_line,jrn_def_ech,jrn_def_ech_lib,jrn_def_code,
	   jrn_def_fiche_deb,jrn_def_fiche_deb
	   from jrn_Def
	      where jrn_def_id=".$this->id);
    $Count=pg_NumRows($Res);
    if ( $Count == 0 ) {
      echo '<DIV="redcontent"><H2 class="error"> Param�tres journaux non trouv�s</H2> </DIV>';
      return null;
    }
    return pg_fetch_array($Res,0);
  }

  /*! \function GetDefLine
   * \brief Get the number of lines of a journal
   * \param $p_cred deb or cred
   *
   * \return an integer
   */
  function GetDefLine() 
  {
    $sql_cred='jrn_deb_max_line';
    $sql="select jrn_deb_max_line as value from jrn_def where jrn_def_id=".$this->id;
    $r=ExecSql($this->db,$sql);
    $Res=pg_fetch_all($r);
    echo_debug('class_acc_ledger',__LINE__,$Res);
    if ( sizeof($Res) == 0 ) return 1;
    return $Res[0]['value'];
  }
  /*!\brief get the saldo of a ledger for a specific period
   * \param $p_from start period
   * \param $p_to end period
   * \param $p_cent 1 for a centralized period otherwise 0
   */
  function get_solde($p_from,$p_to,$p_cent) {
    $ledger=""; 
    if ( $this->id != 0 && $p_cent=='off') {
      $ledger=" and j_jrn_def = ".$this->id;
    }
 
    if ( $this->id != 0 && $p_cent=='on') {
      $ledger=" and c_jrn_def = ".$this->id;
    }
 
    // we ask for a specific ledger
    if ( $p_cent == 'off') {
      $periode=sql_filter_per($this->db,$p_from,$p_to,'p_id','j_tech_per');
      $sql='select j_montant as montant,j_debit as deb from jrnx where '
	.$periode.$ledger;
    }else {
      $periode=sql_filter_per($this->db,$p_from,$p_to,'p_id','c_periode');
      $sql='select c_montant as montant,c_debit as deb from centralized where '
	.$periode.$ledger;
    }
    $ret=ExecSql($this->db,$sql);
    $array=pg_fetch_all($ret);
    $deb=0.0;
    $cred=0.0;
    foreach ($array as $line) {
 
      if ( $line['deb']=='t' )
	$deb+=$line['montant'];
      else
	$cred+=$line['montant'];
    }
    $response=array($deb,$cred);
    return $response;
  }
  /*! 
   * \brief Show a select list of the ledger you can access in
   * writing, the security is taken in care but show the readable AND
   * writable ledger 
   * \param
   * \param
   * \param
   * 
   *
   * \return object widget select
   */
  function select_ledger() {
    $user=new User($this->db);
    $array=$user->get_ledger();
    $idx=0;
    $ret=array();

    foreach ( $array as $value) {
      $ret[$idx]['value']=$value['jrn_def_id'];
      $ret[$idx]['label']=$value['jrn_def_name'];
      $idx++;
    }

    $select=new widget("select");
    $select->name='p_jrn';
    $select->value=$ret;
    $select->selected=$this->id;
    return $select;
  }
  /*! 
   * \brief retrieve the jrn_def_fiche and return them into a array
   *        index deb, cred
   * \param
   * \param
   * \param
   * 
   *
   * \return return an array ('deb'=> ,'cred'=>)
   */
  function get_fiche_def() {
    $sql="select jrn_def_fiche_deb as deb,jrn_def_fiche_cred as cred ".
      " from jrn_def where ".
      " jrn_def_id = ".$this->id;

    $r=ExecSql($this->db,$sql);

    $res=pg_fetch_all($r);
    if ( empty($res) ) return null;

    return $res[0];
  }
  /*! 
   * \brief retrieve the jrn_def_class_deb and return it
   *
   * \param
   * \param
   * \param
   * 
   *
   * \return return an string 
   */
  function get_class_def() {
    $sql="select jrn_def_class_deb  ".
      " from jrn_def where ".
      " jrn_def_id = ".$this->id;

    $r=ExecSql($this->db,$sql);

    $res=pg_fetch_all($r);

    if ( empty($res) ) return null;

    return $res[0];
  }

  /*! 
   * \brief show the result of the array
   * \param $p_array array from the form
   * \param
   * \param
   * 
   *
   * \return string
   */
  function show_summary($p_array) {
    $this->id=$p_array['p_jrn'];
    if ( empty($p_array)) return 'Aucun r&eacute;sultat';
    extract($p_array);
    $ret="";
    $ret.="<table>";
    $ret.="<tr><td>Date : </td><td>$date</td></tr>";
    $ret.="<tr><td>Description </td><td>$desc</td></tr>";
    $ret.='</table>';
    $ret.="<table>";
    $ret.="<tr>";
    $ret.="<th> Quick Code  ou ";
    $ret.="Poste </th>";
    $ret.="<th> Montant </th>";
    $ret.="<th> D&eacute;bit</th>";
    $ret.="</tr>";
    $own=new own($this->db);
    $hidden=new widget('hidden');
    $ret.=$hidden->IOValue('date',$date);
    $ret.=$hidden->IOValue('desc',$desc);
    // For predefined operation
    $ret.=$hidden->IOValue('e_comm',$desc);
    $ret.=$hidden->IOValue('jrn_type',$this->get_type());
    $ret.=$hidden->IOValue('p_jrn',$this->id);
    $ret.=$hidden->IOValue('nb_item',$this->nb);
    if ( $this->with_concerned==true) {
      $ret.=$hidden->IOValue('jrn_concerned',$jrn_concerned);
    }
    $ret.=dossier::hidden();
    $count=0;
    for ($i=0;$i<$this->nb;$i++) {
      $ret.="<tr>";
      if ( trim(${'qc_'.$i})!="") {
	$oqc=new fiche($this->db);
	$oqc->get_by_qcode(${'qc_'.$i},false);
	$strPoste=$oqc->strAttribut(ATTR_DEF_ACCOUNT);
	$ret.="<td>".${'qc_'.$i}.' - '.
				   $oqc->strAttribut(ATTR_DEF_NAME).$hidden->IOValue('qc_'.$i,${'qc_'.$i}).
				   '</td>';
      }

      if ( trim(${'qc_'.$i})=="" && trim(${'poste'.$i}) != "") {
	$oposte=new Acc_Account_Ledger($this->db,${'poste'.$i});
	$strPoste=$oposte->id;
	$ret.="<td>".${"poste".$i}." - ".
				     $oposte->get_name().$hidden->IOValue('poste'.$i,${'poste'.$i}).
				     '</td>';
      }

      if ( trim(${'qc_'.$i})=="" && trim(${'poste'.$i}) == "") 
	continue;

      $ret.="<td>".${"amount".$i}.$hidden->IOValue('amount'.$i,${'amount'.$i})."</td>";
      $ret.="<td>";
      $ret.=(isset(${"ck$i"}))?"D":"C";
      $ret.=(isset(${"ck$i"}))?$hidden->IOValue('ck'.$i,${'ck'.$i}):"";
      $ret.="</td>";
      // CA 

      if (  $own->MY_ANALYTIC!='nu') // use of AA
	{
	  if ( ereg("^[6,7]+",$strPoste)) {
	    // show form
	    $op=new Anc_Operation($this->db);
	    $null=($own->MY_ANALYTIC=='op')?1:0;
	    $ret.='<td>';
	    $ret.=$op->display_form_plan(null,$null,1,$count,round(${'amount'.$i},2));
	    $ret.='</td>';
	    $count++;
	  }
	   
	}



      $ret.="</tr>";
    }
    $ret.="</table>";
    return $ret;
  }

  /*! 
   * \brief Show the form to encode your operation
   * \param $p_array if you correct or use a predef operation
   * \param $p_readonly 1 for readonly 0 for writable
   * \param
   * 
   *
   * \return a string containing the form
   */
  function show_form($p_array=null,$p_readonly=0)
  {
    if ( $p_readonly == 1 )
      return $this->show_summary($p_array);
     
    if ( $p_array != null )
      extract($p_array);

    $ret="";
    // Load the javascript
    $ret.=JS_SEARCH_CARD;
    $ret.=JS_SEARCH_POSTE;
    $ret.=JS_AJAX_FICHE;
    // 
    $ret.="<table>";
    $ret.= '<tr><td>';
    $wDate=new widget('js_date','Date','date');
    $wDate->table=1;
    $wDate->readonly=$p_readonly;
    $wDate->value=(isset($date))?$date:'';

    $ret.=$wDate->IOValue();
    $ret.= '</td></tr>';

    $ret.= '<tr><td>';
    $wDescription=new widget('text',"Description",'desc');
    $wDescription->readonly=$p_readonly;
    $wDescription->table=1;
    $wDescription->value=(isset($desc))?$desc:'';
    $ret.=$wDescription->IOValue();
    $ret.= '</td></tr>';

    $ret.= '</table>';

    //     $nb_row=(isset($nb))?$nb:$this->GetDefLine();
    $nb_row=$this->nb;
    $hidden=new widget('hidden','nb','nb');
    $hidden->value=$nb_row;
    $ret.=$hidden->IOValue();
    $ret.=dossier::hidden();
    $ret.=$hidden->IOValue('p_jrn',$this->id);
    $ret.=$hidden->IOValue('jrn_type',$this->get_type());
    $ret.='<table border="2">';
    $ret.='<tr>'.
      '<th colspan="4">Quickcode</th>'.
      '<th colspan="2">Poste</th>'.
      '<th> Montant</th>'.
      '<th>D&eacute;bit</th>'.
      '</tr>';

    for ($i = 0 ;$i<$nb_row;$i++){
      // Quick Code
      $quick_code=new widget('js_search');
      $quick_code->name='qc_'.$i;
      $quick_code->value=(isset(${'qc_'.$i}))?${'qc_'.$i}:"";
      $quick_code->readonly=$p_readonly;
      $quick_code->extra2=$this->id;
      $quick_code->extra='filter';
      $qc_span=new widget('span','','qc_'.$i.'_label');

      // Account 
      $poste=new widget('js_search_poste');
      $poste->name='poste'.$i;
      $poste->value=(isset(${'poste'.$i}))?${"poste".$i}:'';
      $poste->readonly=$p_readonly;
      $poste->extra=$this->id;
      $poste->extra2=$this->get_class_def();


      $poste_span=new widget('span','','poste'.$i.'_label');

      // Amount
      $amount=new widget('text');
      $amount->name='amount'.$i;
      $amount->value=(isset(${'amount'.$i}))?${"amount".$i}:'';
      $amount->readonly=$p_readonly;
      $amount->javascript=' onChange="checkTotalDirect()"';
      // D/C
      $deb=new widget('checkbox');
      $deb->name='ck'.$i;
      $deb->selected=(isset(${'ck'.$i}))?true:false;
      $deb->readonly=$p_readonly;
      $deb->javascript=' onChange="checkTotalDirect()"';

      $ret.='<tr>';
      $ret.='<td>'.$quick_code->IOValue().'</td>';
      $ret.='<td>'.$qc_span->IOValue().'</td>';
      $ret.='<td>'.$poste->IOValue().'</td>';
      $ret.='<td>'.$poste_span->IOValue().'</td>';
      $ret.='<td>'.$amount->IOValue().'</td>';
      $ret.='<td>'.$deb->IOValue().'</td>';
      $ret.='</tr>';
      // If readonly == 1 then show CA
    }     
    $ret.='</table>';
    if ( isset ($this->with_concerned) && $this->with_concerned==true) {
      $oRapt=new Acc_Reconciliation($this->db);
      $w=$oRapt->widget();
      $w->name='jrn_concerned';
      $w->value=(isset($jrn_concerned))?$jrn_concerned:"";
      $ret.="R&eacute;conciliation/rapprochements : ".$w->IOValue();
    }
    return $ret;
  }

  /*!\brief 
   * check if the current ledger is closed
   *\return 1 for yes, otherwise 0
   *\see Periode::is_closed
   */
  function is_closed($p_periode) {
    $per=new Periode($this->db);
    $per->set_jrn($this->id);
    $per->set_periode($p_periode);
    $ret=$per->is_closed();
    echo_debug(__FILE__.':'.__LINE__.'- is_closed','return',$ret);
    return $ret;

  }
  /*! 
   * \brief verify that the operation can be saved
   * \param $p_array array of data same layout that the $_POST from show_form
   * 
   *
   * \return the return value are  0 ok,1 incorrect balance,  2 date
   * invalid, 3 invalid amount,  4 the card is not in the range of
   * permitted card, 5 not in the user's period, 6 closed period
   * 
   */
  function verify($p_array)
  {
    extract ($p_array);
    $user=new User($this->db);
    $tot_cred=0;$tot_deb=0;
    // Check the periode and the date
    if ( isDate($date) == null ) { 
      throw new AcException('Date invalide', 2);
    }
    list ($l_date_start,$l_date_end)=get_periode($this->db,$user->get_periode());
  
    // Date dans la periode active
    if ( cmpDate($date,$l_date_start)<0 || 
	 cmpDate($date,$l_date_end)>0 )
      {
	throw new AcException('Pas dans la periode active',5);
      }
    // Periode ferme 
    if ( $this->is_closed($user->get_periode())==1 )
      {
	echo_debug(__FILE__.':'.__LINE__.'- verify',' the periode is closed ');
	throw new AcException('Periode fermee',6);
      }

    for ($i=0;$i<$this->nb;$i++) 
      {
	$err=0;

	// Check the balance
	if ( ! isset (${'amount'.$i}))
	  continue;

	if ( isNumber(${'amount'.$i} ) == 0 )
	  throw new AcException('Montant invalide',3);

	$amount=round(${'amount'.$i},2);
	$tot_deb+=(isset(${'ck'.$i}))?$amount:0;
	$tot_cred+=(! isset(${'ck'.$i}))?$amount:0;

	// Check if the card is permitted
	if ( isset (${'qc_'.$i}) && trim(${'qc_'.$i}) !="") {
	  $f=new fiche($this->db);
	  $f->quick_code=${'qc_'.$i};

	  if ( $f->belong_ledger($p_jrn) < 0 )
	    throw new AcException("La fiche quick_code = ".
				  $f->quick_code." n\'est pas dans ce journal",4);
	}

	// Check if the account is permitted
	if ( isset (${'poste'.$i})) {
	  $p=new Acc_Account_Ledger($this->db,${'poste'.$i});
	  if ( $p->belong_ledger ($p_jrn) < 0 )
	    throw new AcException("Le poste ".$p->id." n\'est pas dans ce journal",5);
	}


      }
    if ( $tot_deb != $tot_cred )
      throw new AcException("Balance incorrecte debit = $tot_deb credit=$tot_cred ",1);
       
  }
  /*!
   * \brief
   *
   * \param $p_grpt id in jr_grpt_
   *
   * \return string internal_code
   *      -
   *
   */
  function compute_internal_code($p_grpt)
  {
    if ( $this->id==0) return;
    $num = NextSequence($this->db,'s_internal');
    $num=strtoupper(hexdec($num));
    $atype=$this->get_propertie();
    $type=$atype['jrn_def_code'];
    $internal_code=sprintf("%d%s-%s",dossier::id(),$type,$num);
    echo_debug (__FILE__,__LINE__,"internal_code = $internal_code");
    return $internal_code;
  }

  /*! 
   * \brief save the operation into the jrnx,jrn, ,
   *  CA and pre_def
   * \param $p_array 
   *
   * \return array with [0] = false if failed otherwise true, [1] error
   * code
   */
  function save ($p_array) {
    extract ($p_array);
    try {
      $this->verify($p_array);
     
      StartSql($this->db) ;
       
      $seq=NextSequence($this->db,'s_grpt');
      $internal=$this->compute_internal_code($seq);
       
      $group=NextSequence($this->db,"s_oa_group");       
      $own=new own($this->db);
      $tot_amount=0;

      $count=0;
      for ($i=0;$i<$this->nb;$i++) 
	{
	  if ( ! isset (${'qc_'.$i}) && ! isset(${'poste'.$i}))
	    continue;
	  $acc_op=new Acc_Operation($this->db);
	  $quick_code="";
	  // First we save the jrnx
	  if ( isset(${'qc_'.$i})) {
	    $qc=new fiche($this->db);
	    $qc->get_by_qcode(${'qc_'.$i},false);
	    $poste=$qc->strAttribut(ATTR_DEF_ACCOUNT);
	    $quick_code=${'qc_'.$i};
	  }
	  else {
	    $poste=${'poste'.$i};
	  }
	  $acc_op->date=$date;
	  $acc_op->desc=$desc;
	  $acc_op->amount=round(${'amount'.$i},2);
	  $acc_op->grpt=$seq;
	  $acc_op->poste=$poste;
	  $acc_op->jrn=$this->id;
	  $acc_op->type=(isset (${'ck'.$i}))?'d':'c';
	  $acc_op->qcode=$quick_code;
	  $j_id=$acc_op->insert_jrnx();
	  $tot_amount+=round($acc_op->amount,2);
	   
	  if ( $own->MY_ANALYTIC != "nu" )
	    {
	      if ( ereg("^[6,7]+",$poste)) {
	   
		// for each item, insert into operation_analytique */
		$op=new Anc_Operation($this->db); 
		$op->oa_group=$group;
		$op->j_id=$j_id;
		$op->oa_date=$date;
		$op->oa_debit=($acc_op->type=='d' )?'t':'f';
		$op->oa_description=$desc;
		$op->save_form_plan($p_array,$count);
		$count++;
	      }
	    }
	}
      $acc_end=new Acc_Operation($this->db);
      $acc_end->date=$date;
      $acc_end->desc=$desc;
      $acc_end->grpt=$seq;
      $acc_end->jrn=$this->id;
      $jr_id= $acc_end->insert_jrn();
      if ($jr_id == false )
	throw new Exception('Balance incorrecte');     

      ExecSql($this->db,"update jrn set jr_internal='".$internal."' where ".
	      " jr_grpt_id = ".$seq);

      // Save now the predef op 
      //------------------------
      if ( isset($save_opd)) {
	$opd=new Pre_Op_Advanced($this->db);
	$opd->name=(trim($desc)=='')?$internal:$desc;
	$opd->get_post();
	$opd->save();
      }

      if ( isset($this->with_concerned) && $this->with_concerned==true) {
	$orap=new acc_reconciliation($this->db);
	$orap->jr_id=$jr_id;
	$orap->insert($jrn_concerned);
      }
     
    }

    catch (AcException $a) {
      throw $a;
    } 
    catch (Exception $e) {
      Rollback($this->db);
      echo 'OPERATION ANNULEE ';
      echo '<hr>';
      echo __FILE__.__LINE__.$e->getMessage();
      exit();
    }
    Commit($this->db);
    return true;
  }

  /*! 
   * \brief get all the data from request and build the object 
   * \param
   * \param
   * \param
   * 
   *
   * \return
   */
  function get_request() 
  {
    $this->id=$_REQUEST['p_jrn'];

  }

  /*!
   * \brief retrieve the next number for this type of ledger
   * \param p_cn connx
   * \param p_type ledger type
   *
   * \return the number
   *      
   * \todo this function show be a part of the acc_ledger_type class
   *       as soon as this class is created
   *
   */
  static function next_number($p_cn,$p_type)
  {
  
    $Ret=CountSql($p_cn,"select * from jrn_def where jrn_def_type='".$p_type."'");
    return $Ret+1;
  }
  /*! 
   * \brief this function is intended to test this class
   * \param
   * \param
   * \param
   * 
   *
   * \return
   */
  static function test_me() 
  {
    echo Acc_Reconciliation::$javascript;
    html_page_start();
    $cn=DbConnect(dossier::id());
    $_SESSION['g_user']='phpcompta';
    $_SESSION['g_pass']='phpcompta';

    $id=(isset ($_REQUEST['p_jrn']))?$_REQUEST['p_jrn']:-1;
    $a=new Acc_Ledger($cn,$id);
    $a->with_concerned=true;
    // Vide
    echo '<FORM method="post">';
    echo $a->select_ledger()->IOValue();
    echo widget::submit_button('go','Test it');
    echo '</form>';
    if ( isset($_POST['go'])) {
      echo "Ok ";
      echo '<form method="post">';
      echo $a->show_form();
      echo widget::submit_button('post_id','Try me');
      echo '</form>';
      // Show the predef operation
      // Don't forget the p_jrn 
      echo '<form>';
      echo dossier::hidden();
      echo '<input type="hidden" value="'.$id.'" name="p_jrn">';
      $op=new Pre_operation($cn);
      $op->p_jrn=$id;
      $op->od_direct='t';
      if ($op->count() != 0 )
	echo widget::submit_button('use_opd','Utilisez une op.prédéfinie');
      echo $op->show_button();
      echo '</form>';
      exit();
    }
 
    if ( isset($_POST['post_id' ])) {
      echo '<form method="post">';
      echo $a->show_form($_POST,1);
      echo widget::submit_button('save_it',"Sauver");
      echo '</form>';
      exit();
    }
    if ( isset($_POST['save_it' ])) {
      print 'saving';
      $array=$_POST;
      $array['save_opd']=1;
      try {
	$a->save($array);
      } catch (AcException $e) {
	echo '<script>alert (\''.$e->getMessage()."'); </script>";
	echo '<form method="post">';

	echo $a->show_form($_POST);
	echo widget::submit_button('post_id','Try me');
	echo '</form>';
	 
      }
      exit();
    }
    // The GET at the end because automatically repost when you don't
    // specify the url in the METHOD field
    if ( isset ($_GET['use_opd'])) {
      $op=new Pre_op_advanced($cn);
      $op->set_od_id($_REQUEST['pre_def']);
      //$op->p_jrn=$id;

      $p_post=$op->compute_array();

      echo '<FORM method="post">';

      echo $a->show_form($p_post);
      echo widget::submit_button('post_id','Use predefined operation');
      echo '</form>';
      exit();

    }

  }


}
