<?


/*
 *   This file is part of WCOMPTA.
 *
 *   WCOMPTA is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   WCOMPTA is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with WCOMPTA; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Auteur Dany De Bontridder ddebontridder@yahoo.fr

/* function
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-
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
                                 jr_comment,
                                 j_rapt, 
                                 j_tech_per 
            from jrnx left join jrn on jr_grpt_id=j_grpt 
            where  j_tech_per =".$p_periode." and j_tech_user not in ( select c_j_id from centralized) 
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

?>
