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
require_once ('class_pre_operation.php');

/*---------------------------------------------------------------------- */
/*!\brief concerns the operation for ACH ledger 
/*---------------------------------------------------------------------- */
class Pre_op_ach extends Pre_operation_detail {
  var $op;
  function __construct($cn) {
    parent::__construct($cn);

    $this->operation->od_direct='f';
  }

  function get_post() {
	echo_debug(__FILE__.':'.__LINE__.'- ','get_post');
	parent::get_post();
	$this->operation->od_direct='f';
	$this->e_client=$_POST['e_client'];
	for ($i=0;$i<$this->operation->nb_item;$i++) {
	  $march="e_march".$i;
	  $this->$march=$_POST['e_march'.$i];
	  $this->{"e_march".$i."_buy"}=$_POST['e_march'.$i."_buy"];
	  $this->{"e_march".$i."_tva_id"}=$_POST['e_march'.$i."_tva_id"];
	  $this->{"e_march".$i."_tva_amount"}=$_POST['e_march'.$i."_tva_amount"];
	  $this->{"e_quant".$i}=$_POST['e_quant'.$i];

	}
  }
/*! 
 * \brief save the detail and op in the database
 *
 */
  function save() {
	try {
	  StartSql($this->db);
	  if ($this->operation->save() == false )
		return;
	  // save the client
	  $sql=sprintf('insert into op_predef_detail (od_id,opd_poste,opd_debit)'.
					 ' values '.
					 "(%d,'%s','%s')",
				   $this->operation->od_id,
				   $this->e_client,
				   "f");
	  ExecSql($this->db,$sql);
	  // save the selling
	  for ($i=0;$i<$this->operation->nb_item;$i++) {
		$sql=sprintf('insert into op_predef_detail (opd_poste,opd_amount,opd_tva_id,opd_quantity,'.
					 'opd_debit,od_id,opd_tva_amount)'.
					 ' values '.
					 "('%s',%.2f,%d,%f,'%s',%d,%f)",
					 $this->{"e_march".$i},
					 $this->{"e_march".$i."_buy"},
					 $this->{"e_march".$i."_tva_id"},
					 $this->{"e_quant".$i},
					 't',
					 $this->operation->od_id,
					 $this->{"e_march".$i."_tva_amount"}
					 );
		ExecSql($this->db,$sql);
	  }
	} catch (Exception $e) {
	  echo ($e->getMessage());
	  Rollback($this->db);
	}

  }
  /*!\brief compute an array accordingly with the FormVenView function
   */
  function compute_array() {
	$count=0;
	$a_op=$this->operation->load();
	$array=$this->operation->compute_array($a_op);
	$p_array=$this->load();
	foreach ($p_array as $row) {
	  if ( $row['opd_debit']=='f') {
		$array+=array('e_client'=>$row['opd_poste']);
	  } else {
		$array+=array("e_march".$count=>$row['opd_poste'],
				  "e_march".$count."_buy"=>$row['opd_amount'],
				  "e_march".$count."_tva_id"=>$row['opd_tva_id'],
				  "e_march".$count."_tva_amount"=>$row['opd_tva_amount'],
				  "e_quant".$count=>$row['opd_quantity']
				  );
		$count++;
	  }
	}
	return $array;
  }
  /*!\brief load the data from the database and return an array
   * \return an array 
   */
  function load() {
	$sql="select opd_id,opd_poste,opd_amount,opd_tva_id,opd_debit,".
	  " opd_quantity,opd_tva_amount from op_predef_detail where od_id=".$this->operation->od_id.
	  " order by opd_id";
	$res=ExecSql($this->db,$sql);
	$array=pg_fetch_all($res);
	return $array;
  }
  function set_od_id($p_id) {
	$this->operation->od_id=$p_id;
  }
}
