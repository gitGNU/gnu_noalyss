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
include_once("poste.php");
/*! \file
 * \brief Class for manipulating data to print the balance of account
 */
/*!
 * \brief Class for manipulating data to print the balance of account
 */

class Balance {
  var $db;       /*! \enum database connection */
  var $central; /*! \enum from centralized ledger if equal to Y */
  var $row;     /*! \enum row for ledger*/
  function Balance($p_cn) {
    $this->db=$p_cn;
    $this->central='N';
  }


/*! 
 * \brief retrieve all the row from the ledger in the range of a periode
 * \param $p_from_periode start periode
 * \param $p_to_periode end periode
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
  function GetRow($p_from_periode,$p_to_periode) {
    // compute periode
    if ( $p_from_periode==$p_to_periode ) {
      $per_sql=" j_tech_per = $p_from_periode ";
    } else {
      $per_sql = "j_tech_per >=  $p_from_periode and j_tech_per <= $p_to_periode ";
    }


    // if centralized
    $cent="";

    if ( $this->central=='Y' ) { $cent="j_centralized = true and "; }

    // build query
    $sql="select j_poste,sum(deb) as sum_deb, sum(cred) as sum_cred from 
          ( select j_poste,
             case when j_debit='t' then j_montant else 0 end as deb,
             case when j_debit='f' then j_montant else 0 end as cred
          from jrnx join tmp_pcmn on j_poste=pcm_val
              where 
             $cent
            $per_sql ) as m group by j_poste order by j_poste::text";

    $Res=ExecSql($this->db,$sql);

    $tot_cred=  0.0;
    $tot_deb=  0.0;
    $tot_deb_saldo=0.0;
    $tot_cred_saldo=0.0;
    $M=pg_NumRows($Res);
    // Load the array
    for ($i=0; $i <$M;$i++) {
      $r=pg_fetch_array($Res,$i);
      $a['poste']=$r['j_poste'];
      $a['label']=substr(GetPosteLibelle($this->db,$r['j_poste'],1),0,40);
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
    $a['label']="<b> Totaux </b>";
    $a['sum_deb']=$tot_deb;
    $a['sum_cred']=$tot_cred;
    $a['solde_deb']=$tot_deb_saldo;
    $a['solde_cred']=$tot_cred_saldo;
    $array[$i]=$a;
    $this->row=$array;
    return $array;

  }
}
