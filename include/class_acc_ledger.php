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
/*!\brief Class for jrn
 *
 */
class jrn {
  var $id;
  var $name;
  var $db;
  var $row;
  var $type;
  function jrn ($p_cn,$p_id){
    $this->id=$p_id;
    $this->db=$p_cn;
    $this->row=null;
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
  function GetName() {
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


/*! \function  GetRow
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
  function GetRow($p_from,$p_to,$cent='off',$p_limit=-1,$p_offset=-1) {

  echo_debug('class_acc_ledger.php',__LINE__,"GetRow ( $p_from,$p_to,$cent,$p_limit,$p_offset)");

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
                jr_rapt as oc, j_tech_per as periode from jrnx left join jrn on ".
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
            from centralized left join jrn on 
	jr_grpt_id=c_grp 
	 left join tmp_pcmn on pcm_val=c_poste 
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
    echo_debug('class_acc_ledger.php',__LINE__," GetRow : mont_Deb ".$mont_deb);
    echo_debug('class_acc_ledger.php',__LINE__," GetRow : mont_cred ".$mont_cred);

    /* Check first if there is a quickcode */
    if ( strlen(trim($line['j_qcode'])) != 0 ) 
      {
	if ( $fiche->GetByQCode($line['j_qcode'],false) == 0 ) 
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
		  $f->GetByQCode($row[$e]['j_qcode'],false);
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
  function GetRowSimple($p_from,$p_to,$cent='off',$trunc=0,$p_limit=-1,$p_offset=-1) 
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
        $a_ParmCode=GetArray($this->db,'select p_code,p_value from parm_code');
       $a_TVA=GetArray($this->db,'select tva_id,tva_label,tva_poste 
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
    }// end function GetRowSimple

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
 * \param p_array the structure is set in GetRowSimple, this array is 
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
       $a_TVA=GetArray($this->db,'select tva_id,tva_label,tva_poste 
                                 from tva_rate where tva_rate != 0 order by tva_id');
	}
      if ( $a_ParmCode == null )
	{
      //Load Parm_code
        $a_ParmCode=GetArray($this->db,'select p_code,p_value from parm_code');
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
	  $poste=new poste($this->db,$code['j_poste']);

	  // if card retrieve name if the account is not a VAT account
	  if ( strlen(trim($code['j_qcode'] )) != 0 && $poste->isTva() == 0 )
	    {
	      echo_debug('class_acc_ledger',__LINE__,'fiche_def = '.$code['j_qcode']);
	      $fiche=new fiche($this->db);
	      $fiche->GetByQCode(trim($code['j_qcode']),false);
	      $fiche_def_id=$fiche->get_fiche_def_ref_id();
	      // Customer or supplier
	      if ( $fiche_def_id == FICHE_TYPE_CLIENT ||
		   $fiche_def_id == FICHE_TYPE_FOURNISSEUR ) 
		{
		  echo_debug('class_acc_ledger',__LINE__,$code['j_qcode'].'est F ou C');
		  $p_array['TVAC']=$code['j_montant'];

		  $p_array['client']=($trunc==0)?$fiche->GetName():substr($fiche->GetName(),0,20);
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
		    
		    $p_array['client']=	($trunc==0)?$fiche->GetName():substr($fiche->GetName(),0,20);
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

}
