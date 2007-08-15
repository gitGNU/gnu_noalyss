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

/* !\file concern the table quant_sold
 */

/*! \brief this object handles the table quant_purchase
 *
 */
require_once ('class_gestion_table.php');


class gestion_purchase extends gestion_table
{
 var $qp_id;					/*!< id */
 var $qp_internal;				/*!< internal code */
 var $qp_fiche;					/*!< card id (fiche.f_id) */
 var $qp_quantite;				/*!< quantity */
 var $qp_price;					/*!< quantity */
 var $qp_vat;					/*!< vat amount */
 var $qp_vat_code;				/*!< vat_code */
 var $qp_nd_amount;				/*!< no deductible */
 var $qp_nd_tva;				/*!< tva not deductible */
 var $qp_nd_tva_recup;			/*!< tva ded via taxe */
 var $qp_supplier;				/*!< supplier code (f_id) */
 var $qp_valid;
 var $j_id;						/*!< jrnx.j_id  */
  /*!\brief return an array of gestion_table, the object are
   * retrieved thanks the qs_internal
   */
  function get_list() {
	if ($this->qp_internal=="")
	  exit (__FILE__.__LINE__." qs_internal est vide");
	$sql="select  qp_id,
                  qp_internal,
                  qp_fiche,
                  qp_quantite,
                  qp_price,
                  qp_vat,
                  qp_vat_code,
                  qp_nd_amount,
                  qp_nd_tva,
                  qp_nd_tva_recup,
                  qp_supplier,
                  j_id
                 from quant_purchase
          where qp_internal='".$this->qp_internal."'";
	$ret=ExecSql($this->db,$sql);
	// $res contains all the line
	$res=pg_fetch_all($ret);

	if ( sizeof($res)==0) return null;
	$count=0;
	foreach ($res as $row) {
	  $t_gestion_purchase=new gestion_purchase($this->db);
	  foreach ($row as $idx=>$value)
		$t_gestion_purchase->$idx=$value;
	  $array[$count]=clone $t_gestion_purchase;
	  $count++;
	}
	return $array;
  }
}
