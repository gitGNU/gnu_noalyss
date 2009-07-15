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
/*! \file
 * \brief Class for manipulating data to print the balance of account
 */
/*!
 * \brief Class for manipulating data to print the balance of account
 */
require_once('class_acc_account.php');

class Acc_Balance {
  var $db;       /*! < database connection */
  var $central; /*! < from centralized ledger if equal to Y */
  var $row;     /*! < row for ledger*/
  var $jrn;						/*!< jrn_def.jr_id or -1 for all of
								  them */
  var $from_poste;				/*!< from_poste  filter on the post */
  var $to_poste;				/*!< to_poste filter on the post*/
  function Acc_Balance($p_cn) {
    $this->db=$p_cn;
    $this->central='N';
	$this->jrn=-1;
	$from_poste="";
	$to_poste="";
  }


/*! 
 * \brief retrieve all the row from the ledger in the range of a periode
 * \param $p_from_periode start periode (p_id)
 * \param $p_to_periode end periode (p_id)
 *
 * \return a double array
 *     array of
 *         - $a['poste']
 *         - $a['label']
 *         - $a['sum_deb']
 *         - $a['sum_cred']
 *         - $a['solde_deb']
 *         - $a['solde_cred']
 */
  function get_row($p_from_periode,$p_to_periode) {

    // filter on requested periode
    $per_sql=sql_filter_per($this->db,$p_from_periode,$p_to_periode,'p_id','j_tech_per');
    // if centralized
    $cent="";	$and=""; $jrn="";
	$from_poste="";$to_poste="";

    if ( $this->central=='Y' ) { $cent="j_centralized = true";$and=" and "; }
	if ($this->jrn!= -1){	  $jrn=" $and  j_jrn_def=".$this->jrn;$and=" and ";}
	if ( strlen(trim($this->from_poste)) != 0 ) {
	  $from_poste=" $and j_poste::text >= '".$this->from_poste."'"; $and=" and ";
	}
	if ( strlen(trim($this->to_poste)) != 0 ) {
	  $to_poste=" $and j_poste::text <= '".$this->to_poste."'"; $and=" and ";
	}

    // build query
    $sql="select j_poste,sum(deb) as sum_deb, sum(cred) as sum_cred from 
          ( select j_poste,
             case when j_debit='t' then j_montant else 0 end as deb,
             case when j_debit='f' then j_montant else 0 end as cred
             from jrnx join tmp_pcmn on j_poste=pcm_val
                  left join parm_periode on j_tech_per = p_id
              where 
             $cent  $jrn $from_poste $to_poste
             $and
            $per_sql ) as m group by j_poste order by j_poste::text";

    $Res=$this->db->exec_sql($sql);

    $tot_cred=  0.0;
    $tot_deb=  0.0;
    $tot_deb_saldo=0.0;
    $tot_cred_saldo=0.0;
    $M=pg_NumRows($Res);
    // Load the array
    for ($i=0; $i <$M;$i++) {
      $r=pg_fetch_array($Res,$i);
      $poste=new Acc_Account($this->db,$r['j_poste']);

      $a['poste']=$r['j_poste'];
      $a['label']=substr($poste->get_lib(),0,40);
      $a['sum_deb']=round($r['sum_deb'],2);
      $a['sum_cred']=round($r['sum_cred'],2);
      $a['solde_deb']=round(( $a['sum_deb']  >=  $a['sum_cred'] )? $a['sum_deb']- $a['sum_cred']:0,2);
      $a['solde_cred']=round(( $a['sum_deb'] <=  $a['sum_cred'] )?$a['sum_cred']-$a['sum_deb']:0,2);
      $array[$i]=$a;
      $tot_cred+=  $a['sum_cred'];
      $tot_deb+= $a['sum_deb']; 
      $tot_deb_saldo+= $a['solde_deb'];
      $tot_cred_saldo+= $a['solde_cred'];
      
      
    }//for i
    // Add the saldo
    $i+=1;
    $a['poste']="";
    $a['label']="Totaux ";
    $a['sum_deb']=$tot_deb;
    $a['sum_cred']=$tot_cred;
    $a['solde_deb']=$tot_deb_saldo;
    $a['solde_cred']=$tot_cred_saldo;
    $array[$i]=$a;
    $this->row=$array;
    return $array;

  }
}
