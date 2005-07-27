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
/* $Revision$ */
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

class jrn {
  var $id;
  var $name;
  var $db;
  var $row;
  function jrn ($p_cn,$p_id){
    $this->id=$p_id;
    $this->db=$p_cn;
    $this->row=null;
  }
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

/* function GetRow
 * Purpose : Get The data 
 * 
 * parm : 
 *	- connection
 *      - array
 *      - p_limit starting line
 *      - p_offset number of lines
 *
 * gen :
 *	- none
 * return:
 *	- Array with the asked data
 *
 */ 
  function GetRow($p_from,$p_to,$cent='off',$p_limit=-1,$p_offset=-1) {

  echo_debug(__FILE__,__LINE__,"GetRow ( $p_from,$p_to,$cent,$p_limit,$p_offset)");

    if ( $p_from == $p_to ) 
      $periode=" jr_tech_per = $p_from ";
    else
      $periode = "(jr_tech_per >= $p_from and jr_tech_per <= $p_to) ";
    $cond_limite=($p_limit!=-1)?" limit ".$p_limit." offset ".$p_offset:"";

    // Grand livre == 0
    if ( $this->id != 0 ) {

      if ( $cent=='off' ) {
	echo_debug(__FILE__,__LINE__,"journaux non  centralisé");
	// Journaux non centralisés
	$Res=ExecSql($this->db,"select j_id,j_id as int_j_id,to_char(j_date,'DD.MM.YYYY') as j_date,
                      jr_internal,
                case j_debit when 't' then j_montant::text else '   ' end as deb_montant,
                case j_debit when 'f' then j_montant::text else '   ' end as cred_montant,
                j_debit as debit,j_poste as poste,jr_montant , ".
	       "pcm_lib as description,j_grpt as grp,jr_comment||' ('||jr_internal||')' as jr_comment ,
                jr_rapt as oc, j_tech_per as periode from jrnx left join jrn on ".
		 "jr_grpt_id=j_grpt ".
		 " left join tmp_pcmn on pcm_val=j_poste ".
		 " where j_jrn_def=".$this->id.
	       " and ".$periode." order by j_date::date asc,jr_internal,j_debit desc ".
		 $cond_limite);
    }else {
      // Journaux centralisés
      echo_debug(__FILE__,__LINE__,"journaux centralisé");
      $Sql="select jr_opid as j_id,
                    c_order as int_j_id,
            to_char (c_date,'DD.MM.YYYY') as j_date ,
            c_internal as jr_internal,
            case c_debit when 't' then c_montant::text else '   ' end as deb_montant,
            case c_debit when 'f' then c_montant::text else '   ' end as cred_montant,
            c_debit as j_debit,
            c_poste as poste,
            pcm_lib as description,
            jr_comment||' ('||c_internal||')' as jr_comment,
            jr_montant,
            c_grp as grp,
            c_comment as comment,
            c_rapt as oc,
            c_periode as periode 
            from centralized left join jrn on ".
		"jr_grpt_id=c_grp left join tmp_pcmn on pcm_val=c_poste where ".
	        " c_jrn_def=".$this->id." and ".
                $periode." order by c_order ";
      $Res=ExecSql($this->db,$Sql.$cond_limite);

    }
  } else {
    // Grand Livre
    if ( $cent == 'off') {
      echo_debug(__FILE__,__LINE__,"Grand livre non centralisé");
      // Non centralisé
      $Res=ExecSql($this->db,"select j_id,j_id as int_j_id,to_char(j_date,'DD.MM.YYYY') as j_date,
                      jr_internal,
                case j_debit when 't' then j_montant::text else '   ' end as deb_montant,
                case j_debit when 'f' then j_montant::text else '   ' end as cred_montant,
                j_debit as debit,j_poste as poste,".
	       "pcm_lib as description,j_grpt as grp,
                jr_comment||' ('||jr_internal||')' as jr_comment,
                jr_montant,
                jr_rapt as oc, j_tech_per as periode from jrnx left join jrn on ".
		 "jr_grpt_id=j_grpt left join tmp_pcmn on pcm_val=j_poste where ".
	       "  ".$periode." order by j_date::date,j_grpt,j_debit desc   ".
	       $cond_limite);

    } else {
      echo_debug(__FILE__,__LINE__,"Grand livre  centralisé");
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
            jr_comment||' ('||c_internal||')' as jr_comment,
            jr_montant,
            c_grp as grp,
            c_comment||' ('||c_internal||')' as comment,
            c_rapt as oc,
            c_periode as periode 
            from centralized left join jrn on ".
		"jr_grpt_id=c_grp left join tmp_pcmn on pcm_val=c_poste where ".
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
  for ($i=0;$i<$Max;$i++) {
    $line=pg_fetch_array($Res,$i);
    $mont_deb=($line['deb_montant']!=0)?sprintf("% 8.2f",$line['deb_montant']):"";
    $mont_cred=($line['cred_montant']!=0)?sprintf("% 8.2f",$line['cred_montant']):"";
    $jr_montant=($line['jr_montant']!=0)?sprintf("% 8.2f",$line['jr_montant']):"";
    $tot_deb+=$line['deb_montant'];
    $tot_cred+=$line['cred_montant'];
    echo_debug(__FILE__,__LINE__," GetRow : mont_Deb ".$mont_deb);
    echo_debug(__FILE__,__LINE__," GetRow : mont_cred ".$mont_cred);

    if ( $case != $line['grp'] ) {
      $case=$line['grp'];
      $array[]=array (
		      'int_j_id' => $line['int_j_id'],
		      'j_id'=>$line['j_id'],
		      'j_date' => $line['j_date'],
		      'internal'=>$line['jr_internal'],
		      'deb_montant'=>'',
		      'cred_montant'=>'<b><i>'.$jr_montant.'</i></b>',
		      'description'=>'<b><i>'.$line['jr_comment'].'</i></b>',
		      'poste' => $line['oc'],
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
		      'periode' => $line['periode']);

    }
      

  }
  echo_debug(__FILE__,__LINE__,"Total debit $tot_deb,credit $tot_cred");
  $this->row=$array;
  $a=array($array,$tot_deb,$tot_cred);
  return $a;
  }
 /* function GetRowSimple
 **************************************************
 * Purpose : Get simplified row
 *        
 * parm : 
 *	- connection
 *      - array
 *      - p_limit starting line
 *      - p_offset number of lines
 *
 * gen :
 *	- none
 * return:
 *	- Array with the asked data
 *
 */ 

  function GetRowSimple($p_from,$p_to,$cent='off',$p_limit=-1,$p_offset=-1) 
  {
    
    echo_debug(__FILE__,__LINE__,"GetRowSimple ( $p_from,$p_to,$cent,$p_limit,$p_offset)");
    // Periode check
    //---
    if ( $p_from == $p_to ) 
      $periode=" jr_tech_per = $p_from ";
    else
      $periode = "(jr_tech_per >= $p_from and jr_tech_per <= $p_to) ";
    $cond_limite=($p_limit!=-1)?" limit ".$p_limit." offset ".$p_offset:"";
    // Grand-livre : id= 0
    //---
    $jrn=($this->id == 0 )?"":"and jrn_def_id = ".$this->id;
    // Non Centralise si cent=off
    //--
    if ($cent=='off') 
      {// Non centralisé
	//---
	$sql=" 
    SELECT jrn.jr_id as jr_id ,
           jrn.jr_id as num , 
           jrn.jr_def_id as jr_def_id, 
           jrn.jr_montant as montant, 
           jrn.jr_comment as comment, 
           to_char(jrn.jr_date,'DD-MM-YYYY') as date, 
           jr_internal,
           jrn.jr_grpt_id as grpt_id, 
           jrn.jr_pj_name as pj,
            jrn_def_type
   FROM jrn join jrn_def on (jrn_def_id=jr_def_id)
where $periode $jrn order by jr_date";


	
      } 
    else 
      {
	//Centralisé
	//---
	$id=($this->id == 0 ) ?"jr_c_opid as num":"jr_opid as num";
	$sql="
    SELECT jrn.jr_id as jr_id ,
           $id , 
           jrn.jr_def_id as jr_def_id, 
           jrn.jr_montant as montant, 
           jrn.jr_comment as comment, 
           to_char(jrn.jr_date,'DD-MM-YYYY') as date, 
           jr_internal,
           jrn.jr_grpt_id as grpt_id, 
           jrn.jr_pj_name as pj,
            jrn_def_type
   FROM jrn join jrn_def on (jrn_def_id=jr_def_id)
       where 
         $periode $jrn and 
         jr_opid is not null
         order by jr_date";
      }// end else $cent=='off'
    //load all data into an array
    //---
    $Res=ExecSql($this->db,$sql);
    if ( pg_NumRows($Res) == 0 ) 
      {
	return null;
      } 


    $array=pg_fetch_all($Res); 

    return $array;  
  }// end function GetRowSimple
}