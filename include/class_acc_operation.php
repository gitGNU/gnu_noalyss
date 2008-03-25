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

/*!\file
 * \brief  this file match the tables jrn & jrnx the purpose is to
 *   remove or save accountant writing to these table. 
 */
require_once ('class_user.php');
/*! \brief  this file match the tables jrn & jrnx the purpose is to
 *   remove or save accountant writing to these table. 
 *
 */
class Acc_Operation 
{
  var $db; 				/*!< database connx */
var $jr_id;	/*!< pk of jrn */
  var $jrn_id;			/*!< jrn_def_id */
  var $debit;			/*!< debit or credit */
  var $user;			/*!< current user */
  var $jrn;			/*!< the ledger to use */
  var $poste;			/*!< account  */
  var $date;			/*!< the date */
  var $periode;			/*!< periode to use */
  var $amount;			/*!< amount of the operatoin */
  var $grpt;			/*!< the group id */
/*! 
 * \brief constructor set automatically the attributes user and periode
 * \param $p_cn the databse connection
 */
  function __construct($p_cn) {
    $this->db=$p_cn;
    $this->qcode="";
    $this->user=$_SESSION['g_user'];
    $user=new User($this->db);
    $this->periode=$user->get_periode();
    $this->jr_id=0;
  }
  /* **************************************************
   *\brief  Insert into the table Jrn
   *        
   *  
   * \return  nothing
   *
   */

  function insert_jrnx()
  {
    if ( $this->poste == "") return true;
    /* for negative amount the operation is reversed */
    if ( $this->amount < 0 ) {
      $this->type=($this->type=='d')?'c':'d';
    }

    $debit=($this->type=='c')?'false':'true';
    $Res=ExecSqlParam($this->db,"select insert_jrnx
		 ($1,abs($2),$3,$4,$5,$6,$7,$8,upper($9)",
		      array(
			    $this->date,
			    round($this->amount,2),
			    $this->poste,
			    $this->grpt,
			    $this->jrn,
			    $debit,
			    $this->user,
			    $this->periode,
			    $this->qcode));
    if ( $Res==false) return $Res;
    $this->jrnx_id=GetSequence($this->db,'s_jrn_op');
    return $this->jrnx_id;
    
}
/*!
 **************************************************
 *\brief  Insert into the table Jrn, the amount is computed from jrnx thanks the 
 *        group id ($p_grpt)
 *        
 * \return  sequence
 *  
 */

  function insert_jrn()
  {
    $p_comment=FormatString($this->desc);
    
    $diff=getDbValue($this->db,"select check_balance ($1)",$this->grpt);
    if ( $diff != 0 ) {
      
      echo "Erreur : balance incorrecte : d&eacute;bit = $montant_deb cr&eacute;dit = $montant_cred";
      return false;
    }

    $echeance=( isset( $this->echeance))?$this->echeance:"";

    // if amount == -1then the triggers will throw an error
    // 
    $Res=ExecSqlParam($this->db,"insert into jrn (jr_def_id,jr_montant,jr_comment,".
		      "jr_date,jr_ech,jr_grpt_id,jr_tech_per)   values (".
		      "$1,$2,$3,".
		      "to_date($4,'DD.MM.YYYY'),$5,$6,$7)",
		      array ($this->jrn, $amount,$p_comment,
			     $this->date,$echeance,$this->grpt,$this->periode)
			    );
    if ( $Res == false)  return false;
    $this->jr_id=GetSequence($this->db,'s_jrn');
    return $this->jr_id;
  }
/*!
 * \brief  Return the internal value, the property jr_id must be set before
 *
 * \return  null si aucune valeur de trouv
 *
 */
function get_internal() {
 if ( ! isset($this->jr_id) ) 
		throw new Exception('jr_id is not set',1);
  $Res=ExecSql($this->db,"select jr_internal from jrn where jr_id=".$this->jr_id);
  if ( pg_NumRows($Res) == 0 ) return null;
  $l_line=pg_fetch_array($Res);
  $this->jr_internal= $l_line['jr_internal'];
  return $this->jr_internal;
}
/*!\brief search an operation thankx it internal code
 * \param internal code
 * \return 0 ok -1 nok
 */
 function seek_internal($p_internal) {
   $res=ExecSqlParam($this->db,'select j_id from jrn where jr_internal=$1',
		     array($p_internal));
   if ( pg_NumRows($Res) == 0 ) return -1;
   $this->jr_id=pg_fetch_result($Res,0,0);
   return 0;
 }
 /*!\brief retrieve data from jrnx 
  * \return an array
  */
 function get_jrnx_detail() {
   if ( $this->jr_id==0 ) return;
   $sql=" select j_date,j_qcode,j_poste,j_montant,jr_internal,case when j_debit = 'f' then 'C' else 'D' end as debit,
                vw_name,pcm_lib from jrnx join jrn on (jr_grpt_id=j_grpt)
                join tmp_pcmn on (j_poste=pcm_val)
                left join vw_fiche_attr on (j_qcode=quick_code)
		where
		jr_id=$1";
   $res=ExecSqlParam($this->db,$sql,array($this->jr_id));
   if ( pg_NumRows ($res) == 0 ) return array();
   $all=pg_fetch_all($res);
   return $all;
 }
 /*!\brief display_jrnx_detail : get the data from get_jrnx_data and
    return a string with HTML code 
  * \param table(=0 no code for table,1 code for table,2 code for CSV)

 */
 function display_jrnx_detail($p_table) {
   $show=$this->get_jrnx_detail();

   $r='';
   $r_notable='';
   $csv="";
   foreach ($show as $l) {
     if ( $l['j_poste'] == $this->poste)
       $r.='<tr bgcolor="red">';
     else
       $r.='<tr>';

       $r.='<td>';
       $a=$l['j_qcode'];;
       $r_notable.=$a;
       $r.=$a;
       $csv.='"'.$a.'";';
       $r.='</td>';
       
       $r.='<td>';
       $a=$l['j_poste'];
       $r_notable.=$a;
       $r.=$a;
       $csv.='"'.$a.'";';
       $r.='</td>';

       $r.='<td>';
       $a=($l['vw_name']=="")?$l['j_qcode']:$l['pcm_lib'];
       $r_notable.=$a;
       $r.=$a;
       $csv.='"'.$a.'";';
       $r.='</td>';
       
       $r.='<td>';
       $a=$l['j_montant'];
       $r_notable.=$a;
       $r.=$a;
       $csv.=$a.';';
       $r.='</td>';
       
       $r.='<td>';
       $a=$l['debit'];
       $r_notable.=$a;
       $r.=$a;
       $csv.='"'.$a.'"';

       $csv.="\r\n";
       $r.='</td>';
     
       $r.='</tr>';
   }
   switch ($p_table) {
   case 1:
     return $r;
     break;
   case 0:
     return $r_notable;
     break;
   case 2:
     return $csv;
   }
   return "ERROR PARAMETRE";
 }
}
