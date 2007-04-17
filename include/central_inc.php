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
/*! \file
 * \brief Concerns the centralization operations
 */

/*! 
 * \brief 
 *        Met les donnees centralisees dans la table
 *        centralize 
 * 
 * \param p_cn connection
 * \param periode a centraliser
 *
 * \return  code error
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
            order by j_date,j_grpt,j_debit desc ";
 $Res=StartSql($p_cn);
 $Res=ExecSql($p_cn,$sql);
 if ($Res==false) { rollback($p_cn); EndSql($p_cn); return ERROR;}
 $Res=ExecSql($p_cn,"update jrnx set j_centralized='t' where j_tech_per=".$p_periode);
 if ($Res==false) { rollback($p_cn); EndSql($p_cn); return ERROR;}
// Set correctly the number of operation id (jr_opid) for each journal
// get the existing jrn_def_id 
//--
 $Res = ExecSql($p_cn,"select jrn_def_id from jrn_def");
 $MaxJrn=pg_NumRows($Res);
 // for each jrn_def_id
 for ( $i=0; $i < $MaxJrn;$i++) {
   $row=pg_fetch_array($Res,$i);
   // get the op related to that jrn_def_id
   $sql=sprintf("select jr_id from jrn 
         where
         jr_tech_per=%d
         and jr_def_id = %d
         order by jr_date,jr_grpt_id desc",
		$p_periode,
		$row['jrn_def_id']
		);

   $Res2=ExecSql($p_cn,$sql);
   $MaxLine=pg_NumRows($Res2);
   for ($e=0;$e < $MaxLine;$e++) {
     // each line is updated with a sequence
     $line=pg_fetch_array($Res2,$e);
     $jr_id=$line['jr_id'];
     $sql=sprintf ("update jrn set 
                 jr_opid = (select nextval('s_jrn_%d'))
                 where jr_id =%d",
		   $row['jrn_def_id'],
		   $jr_id); 
     $Ret=ExecSql($p_cn,$sql);
     if ($Res==false) { rollback($p_cn); EndSql($p_cn); return ERROR;}
   }
 }
   // Put jr_c_opid in centralized                 
   // for each jrn_def_id
   // get the op related to that jrn_def_id
   $sql=sprintf("select jr_id from jrn 
         where
         jr_tech_per=%d
            order by jr_date,jr_grpt_id desc",
		$p_periode
		);

   $Res2=ExecSql($p_cn,$sql);
   $MaxLine=pg_NumRows($Res2);
   for ($e=0;$e < $MaxLine;$e++) {
     // each line is updated with a sequence
     $line=pg_fetch_array($Res2,$e);
     $jr_id=$line['jr_id'];
     $sql=sprintf ("update jrn set 
                 jr_c_opid = (select nextval('s_central'))
                 where jr_id =%d",
		   $jr_id); 
     $Ret=ExecSql($p_cn,$sql);
     if ($Ret==false) { rollback($p_cn); EndSql($p_cn); return ERROR;}
   }
   // Set the order of the jrn
   $Res=ExecSql($p_cn,"select c_id from centralized 
                 inner join jrn on c_grp = jr_grpt_id
                 order by jr_c_opid, c_debit desc");
   for ( $e=0;$e < pg_NumRows($Res);$e++) {
     $row=pg_fetch_array($Res,$e);
     $sql=sprintf ("update centralized set  
                 c_order = (select nextval('s_central_order'))
                 where c_id = %d",$row['c_id']);
     $Res2=ExecSql($p_cn,$sql); 
      if ($Res2==false) { rollback($p_cn); EndSql($p_cn); return ERROR;}

   }
   if ( ExecSql($p_cn,"update parm_periode set p_central=true where p_id=$p_periode") == false)
     { rollback($p_cn); EndSql($p_cn); return ERROR;}

 
 
 
 Commit($p_cn);
 EndSql($p_cn);
 return NOERROR;
}
/*! 
 **************************************************
 * \brief  test if e jrn_jr.id is centralize or not 
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
