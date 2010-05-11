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
 * \param $p_cn connection
 * \param $p_periode a centraliser
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
 try
   {
     $Res=$p_cn->start();
     $Res=$p_cn->exec_sql($sql);
     $Res=$p_cn->exec_sql("update jrnx set j_centralized='t' where j_tech_per=".$p_periode);
     
     // Set correctly the number of operation id (jr_opid) for each journal
     // get the existing jrn_def_id 
     //--
     $Res = $p_cn->exec_sql("select jrn_def_id from jrn_def");
     $MaxJrn=Database::num_row($Res);
     // for each jrn_def_id
     for ( $i=0; $i < $MaxJrn;$i++) {
       $row=Database::fetch_array($Res,$i);
       // get the op related to that jrn_def_id
       $sql=sprintf("select jr_id from jrn 
         where
         jr_tech_per=%d
         and jr_def_id = %d
         order by jr_date,jr_grpt_id desc",
		$p_periode,
		$row['jrn_def_id']
		);

       $Res2=$p_cn->exec_sql($sql);
       $MaxLine=Database::num_row($Res2);
       $jrn_def_id=$row['jrn_def_id'];
	 /* if seq doesn't exist create it */
       if ( $p_cn->exist_sequence('s_jrn_'.$jrn_def_id) == false ) {
	 $p_cn->create_sequence('s_jrn_'.$jrn_def_id);
       }
		 
       for ($e=0;$e < $MaxLine;$e++) {
		 // each line is updated with a sequence
		 $line=Database::fetch_array($Res2,$e);
		 $jr_id=$line['jr_id'];
		 $sql=sprintf ("update jrn set 
                 jr_opid = (select nextval('s_jrn_%d'))
                 where jr_id =%d",
					   $jrn_def_id,
					   $jr_id); 
		 $Ret=$p_cn->exec_sql($sql);
     
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
     
     $Res2=$p_cn->exec_sql($sql);
     $MaxLine=Database::num_row($Res2);
     for ($e=0;$e < $MaxLine;$e++) {
       // each line is updated with a sequence
       $line=Database::fetch_array($Res2,$e);
       $jr_id=$line['jr_id'];
       $sql=sprintf ("update jrn set 
                 jr_c_opid = (select nextval('s_central'))
                 where jr_id =%d",
		     $jr_id); 
       $Ret=$p_cn->exec_sql($sql);
     }
     // Set the order of the jrn
     $Res=$p_cn->exec_sql("select c_id from centralized 
                 inner join jrn on c_grp = jr_grpt_id
                 order by jr_c_opid, c_debit desc");
     for ( $e=0;$e < Database::num_row($Res);$e++) {
       $row=Database::fetch_array($Res,$e);
       $sql=sprintf ("update centralized set  
                 c_order = (select nextval('s_central_order'))
                 where c_id = %d",$row['c_id']);
       $Res2=$p_cn->exec_sql($sql); 

     }
     $p_cn->exec_sql("update parm_periode set p_central=true where p_id=$p_periode");
   }
 catch(Exception $e)
   { 
     rollback($p_cn); 
     return ERROR;
   }
 
 
 
 
 $p_cn->commit();
 return NOERROR;
}

?>
