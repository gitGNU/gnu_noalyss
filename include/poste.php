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
/* $Revision$ */
/*!\file
 * \brief contains several function for Account, will be removed
 * \todo remove these files and move those function to class_acc_account
*/
/*! 
 * \brief  Return the label of a poste
 * 
 *  
 * \param     $p_dossier
 * \param     $p_id tmp_pcmn (pcm_val)
 * \param     $is_cn conneciton
 *
 * \return string 
 *     
 *
 */ 
function GetPosteLibelle($p_dossier,$p_id,$is_cn=0)
{
  include_once("postgres.php");
  if ( ! isset($is_cn) ) $is_cn=0;
  if ( $is_cn == 0) {
    $cn=DbConnect($p_dossier);
  } else {
    $cn=$p_dossier;
  }
  $Res=ExecSql($cn,"select pcm_lib from tmp_pcmn where pcm_val=$p_id");
  if ( pg_NumRows($Res) == 0) { return "non existing poste";}
  $l_poste=pg_fetch_row($Res,0);
  return $l_poste[0];
}
/*! 
 * \brief  give the balance of an account
 * 
 * 
 * \param $p_cn connection
 * \param $p_account account
 * \param $p_cond sql condition
 *
 * \return balance of the account
 *
 */ 
function get_solde($p_cn,$p_account,$p_cond="") {
  $Res=ExecSql($p_cn,"select j_poste,sum(deb) as sum_deb, sum(cred) as sum_cred from 
          ( select j_poste, 
             case when j_debit='t' then j_montant else 0 end as deb, 
             case when j_debit='f' then j_montant else 0 end as cred 
          from jrnx join tmp_pcmn on j_poste=pcm_val 
              where  
            j_poste=$p_account
            $p_cond
          ) as m group by j_poste ");
  $Max=pg_NumRows($Res);
  if ($Max==0) return 0;
  $r=pg_fetch_array($Res,0);

  return $r['sum_deb']-$r['sum_cred'];
}
?>
