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

/* !\file
 */
require_once ('class_user.php');
/*! \brief  this file match the tables jrn & jrnx the purpose is to
    remove or save accountant writing to these table. 
 *
 */
class Acc_Operation 
{
  var $db; 				/*!< database connx */
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
  function Acc_operation($p_cn) {
    $this->db=$p_cn;
    $this->qcode="";
    $this->user=$_SESSION['g_user'];
    $user=new cl_user($this->db);
    $this->periode=$user->GetPeriode();
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

    $debit=($this->type=='c')?'false':'true';
    
    $sql=sprintf("select insert_jrnx
		 ('%s',abs(%.2f),%d,%d,%d,%s,'%s',%d,upper('%s'))",
		 $this->date,round($this->amount,2),
		 $this->poste,$this->grpt,$this->jrn,
		 $debit,$this->user,$this->periode,$this->qcode);
    
    $Res=ExecSql($this->db,$sql);
    if ( $Res==false) return $Res;
    $this->jrnx_id=GetSequence($this->db,'s_jrn_op');
    return $this->jrnx_id;
    
}
/*!
 **************************************************
 *\brief  Insert into the table Jrn, the amount is computed from jrnx thanks the 
 *        group id ($p_grpt)
 *        
 * \return  nothing
 *  
 */

  function insert_jrn()
{
	$p_comment=FormatString($this->desc);


	// retrieve the value from jrnx
	// 
	$montant_deb=getDBValue($this->db,"select sum(j_montant) from jrnx where j_debit='t' and j_grpt=".$this->grpt);
	$montant_cred=getDBValue($this->db,"select sum(j_montant) from jrnx where j_debit='f' and j_grpt=".$this->grpt);
	echo_debug('InsertJrn',__LINE__,"debit = $montant_deb credit  = $montant_cred ");

	$amount=-1.0000;
	if ( $montant_deb == $montant_cred ) {
	  $amount=$montant_deb;
	} else {
	  echo "Erreur : balance incorrecte : d&eacute;bit = $montant_deb cr&eacute;dit = $montant_cred";
	  return false;
	}
	// if amount == -1then the triggers will throw an error
	// 
	$sql=sprintf("insert into jrn (jr_def_id,jr_montant,jr_comment,jr_date,jr_ech,jr_grpt_id,jr_tech_per)
	         values ( %d,abs(%.2f),'%s',to_date('%s','DD.MM.YYYY'),null,%d,%d)",
		     $this->jrn, $amount,$p_comment,$this->date,$this->grpt,$this->periode);


	$Res=ExecSql($this->db,$sql);				 
	if ( $Res == false)  return false;
	return GetSequence($this->db,'s_jrn');
}

}
