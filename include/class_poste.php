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
/*! \file
 * \brief Manage the account 
 */
/*!
 * \brief Manage the account 
 */

class poste {
  var $db;
  var $id;
  var $row;
  function poste($p_cn,$p_id) {
    $this->db=$p_cn;
    $this->id=$p_id;
  }

  /*! 
   * \brief  Get dat for poste 
   * 
   * \param  $p_from periode from
   * \param  $p_to   end periode
   * \return double array (j_date,deb_montant,cred_montant,description,jrn_name,j_debit,jr_internal)
   *         (tot_deb,tot_credit
   *
   */ 
  function GetRow($p_from,$p_to)
{
    if ( $p_from == $p_to ) 
      $periode=" jr_tech_per = $p_from ";
    else
      $periode = "(jr_tech_per >= $p_from and jr_tech_per <= $p_to) ";

  $Res=ExecSql($this->db,"select to_char(j_date,'DD.MM.YYYY') as j_date,".
	       "case when j_debit='t' then j_montant else 0 end as deb_montant,".
	       "case when j_debit='f' then j_montant else 0 end as cred_montant,".
	       " jr_comment as description,jrn_def_name as jrn_name,".
	       "j_debit, jr_internal ".
	       " from jrnx left join jrn_def on jrn_def_id=j_jrn_def ".
	       " left join jrn on jr_grpt_id=j_grpt".
	       " where j_poste=".$this->id." and ".$periode.
	       " order by j_date::date");
  $array=array();
  $tot_cred=0.0;
  $tot_deb=0.0;
  $Max=pg_NumRows($Res);
  if ( $Max == 0 ) return null;
  for ($i=0;$i<$Max;$i++) {
    $array[]=pg_fetch_array($Res,$i);
    if ($array[$i]['j_debit']=='t') {
      $tot_deb+=$array[$i]['deb_montant'] ;
    } else {
      $tot_cred+=$array[$i]['cred_montant'] ;
    }
  }
  $this->row=$array;
  return array($array,$tot_deb,$tot_cred);
}
  /*!\brief Return the name of a account
   */
  function GetName() {
    $ret=pg_exec($this->db,
		 "select pcm_lib from tmp_pcmn where
                  pcm_val=".$this->id);
      if ( pg_NumRows($ret) != 0) {
	$r=pg_fetch_array($ret);
	$this->name=$r['pcm_lib'];
      } else {
	$this->name="Poste inconnu";
      }
    return $this->name;
  }
  /*! 
   * \brief  give the balance of an account
   * 
   * \return
   *      balance of the account
   *
   */ 
function GetSolde($p_cond="") {
  $Res=ExecSql($this->db,"select sum(deb) as sum_deb, sum(cred) as sum_cred from 
          ( select j_poste, 
             case when j_debit='t' then j_montant else 0 end as deb, 
             case when j_debit='f' then j_montant else 0 end as cred 
          from jrnx join tmp_pcmn on j_poste=pcm_val 
              where  
            j_poste like ('$this->id'::text) and
            $p_cond
          ) as m  ");
  $Max=pg_NumRows($Res);
  if ($Max==0) return 0;
  $r=pg_fetch_array($Res,0);
  
  return abs($r['sum_deb']-$r['sum_cred']);
}
  /*! 
   * \brief   give the balance of an account
   * \return
   *      balance of the account
   *
   */ 
function GetSoldeDetail($p_cond="") {
	if ( $p_cond != "") $p_cond=" and ".$p_cond;
  $Res=ExecSql($this->db,"select sum(deb) as sum_deb, sum(cred) as sum_cred from 
          ( select j_poste, 
             case when j_debit='t' then j_montant else 0 end as deb, 
             case when j_debit='f' then j_montant else 0 end as cred 
          from jrnx join tmp_pcmn on j_poste=pcm_val 
              where  
            j_poste like ('$this->id'::text)
            $p_cond
          ) as m  ");
  $Max=pg_NumRows($Res);
  if ($Max==0) return 0;
  $r=pg_fetch_array($Res,0);
  
  return array('debit'=>$r['sum_deb'],
	       'credit'=>$r['sum_cred'],
	       'solde'=>abs($r['sum_deb']-$r['sum_cred']));
}
}
