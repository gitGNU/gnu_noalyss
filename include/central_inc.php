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

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/* function Centralise
 * Purpose :
 *        Met les donnees centralisees dans la table
 *        centralize 
 * 
 * parm : 
 *	- p_cn connection
 *      - periode a centraliser
 * gen :
 *	- none
 * return:
 *	- code error
 *
 */ 
function Centralise($p_cn,$p_periode) {

$sql="insert into centralized( c_j_id,
            c_date ,
            c_internal,
            c_montant,
            c_debit,
            c_poste,
            c_description,
            c_grp,
            c_jrn_def,
            c_comment,
            c_rapt,
            c_periode) select j_id,
                                 j_date,
                                 jr_internal,
                                 j_montant,
                                 j_debit ,
                                 j_poste ,
                                 j_text ,
                                 j_grpt ,
                                 j_jrn_def,
                                 jr_comment,
                                 j_rapt, 
                                 j_tech_per 
            from jrnx left join jrn on jr_grpt_id=j_grpt 
            where  
            j_tech_per =".$p_periode." 
            and jr_internal != 'ANNULE'
            and j_internal != 'ANNULE'
            order by j_date,j_grpt,j_debit desc ";
 $Res=StartSql($p_cn);
 $Res=ExecSql($p_cn,$sql);
 if ($Res==false) { rollback($p_cn); EndSql($p_cn); return ERROR;}
 $Res=ExecSql($p_cn,"update jrnx set j_centralized='t' where j_tech_per=".$p_periode);
 if ($Res==false) { rollback($p_cn); EndSql($p_cn); return ERROR;}
 Commit($p_cn);
 EndSql($p_cn);
 return NOERROR;
}
/* function isCentralize($p_cn,$p_user) 
 **************************************************
 * Purpose : test if e jrn_jr.id is centralize or not 
 *        
 * parm :  
 *	- p_cn 
 *      - p_jnr_id jrn.jr_id 
 * gen : 
 *	-  none 
 * return: 0 if not centralized otherwise > 0
 */
function isCentralize($p_cn,$p_jrn_id) {
  $Res=ExecSql($p_cn,"select c_id from centralized where c_j_id=$p_jrn_id");
  return pg_NumRows($Res);
}

?>
