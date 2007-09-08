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

/*! \brief group of object operations, used for misc operation
 *
 */
require_once ("class_operation.php");
require_once ("postgres.php");
require_once ("class_widget.php");

class groupop
{
  var $db;						/*!< database connection */
  var  $id;						/*!< oa_group, a group contains
                                   several rows of
                                   operation_analytique linked by the
                                   group id */

  var $a_operation;						/*!< array of operations */
  var $date;							/*!< date of the operations */
  var $pa_id;							/*!< the concerned pa_id */

  /*!\brief constructor */
  function  groupop($p_cn,$p_id=0)
  {
	$this->db=$p_cn;
	$this->id=$p_id;
	$this->date=date("d.m.Y");
	$this->nMaxRow=10;
  }
  /*!\brief add several rows */
  function add() {
	/*!\todo add a control to check that the amount are balanced if
       not throw an exception
	*/
	foreach ($this->a_operation as $row) {
	  $row->add();
	}
  }
  /*!\brief show a form for the operation (several rows)
   * \return the string containing the form but without the form tag
   *
   */
  function form($p_readonly=0){
	$wDate=new widget("text","Date : ","pdate",$this->date);
	$wDate->table=1;
	$wDate->size=10;
	$wDate->readonly=$p_readonly;

	$wDescription=new widget("text","Description","pdesc");
	$wDescription->table=0;
	$wDescription->size=80;
	$wDescription->readonly=$p_readonly;
	// Show an existing operation
	//
	if ( isset ($this->a_operation[0])) {
		  $wDate->value=$this->a_operation[0]->oa_date;
		  $wDescription->value=$this->a_operation[0]->oa_description;
	}

	$wPa_id=new widget("hidden","","pa_id",$this->pa_id);
	$ret="";
	$ret.=$wPa_id->IOValue();
	$ret.='<table style="border: 2px outset blue; width: 100%;"	>';
	//	$ret.="<table border=1>";
	$ret.="<TR>".$wDate->IOValue()."</tr>";
	$ret.='<tr><td style="border:1px groove blue">Description</td>'.
	  '<td colspan="3">'.
	  $wDescription->IOValue()."</td></tr>";

	/* array of possible value for the select */
	$aPoste=make_array($this->db,"select po_id as value,po_name||':'||po_description as label".
					" from poste_analytique ".
					" where pa_id = ".$this->pa_id.
					" order by po_name ");

	/* show 10 rows */
	$ret.="<tr>".
	  "<th> Poste </th>".
	  "<th></th>".
	  "<th> Montant</th>".
	  "<th>D&eacute;bit</th>".
	  "</tr>";

	for ($i = 0;$i < $this->nMaxRow;$i++) 
	  {
		// init variable
		$wSelect=new widget("select","","pop".$i);
		$wSelect->value=$aPoste;
		$wSelect->table=1;
		$wSelect->size=12;
		$wSelect->readonly=$p_readonly;

		$wAmount=new widget("text","","pamount$i",0.0);
		$wAmount->size=12;
		$wAmount->table=1;
		$wAmount->readonly=$p_readonly;

		$wDebit=new widget("checkbox","","pdeb$i");
		$wDebit->table=1;
		$wDebit->readonly=$p_readonly;
		if ( isset ($this->a_operation[$i])) {
		  $wSelect->selected=$this->a_operation[$i]->po_id;
		  $wAmount->value=$this->a_operation[$i]->oa_amount;
		  $wDebit->value=$this->a_operation[$i]->oa_debit;
		  if ( $wDebit->value=='t') { $wDebit->selected=true;}
		}

		// build the table
		$ret.="<tr>";
		$ret.=$wSelect->IOValue();
		$ret.="<TD></TD>";
		$ret.=$wAmount->IOValue();
		$ret.=$wDebit->IOValue();
		$ret.="</tr>";
	  }
	$ret.="</table>";
	return $ret;
  }
  /*!\brief fill row from $_POST data
   *
   */
  function from_POST() {
	for ( $i = 0;$i <$this->nMaxRow;$i++) {
	  $p=new operation($this->db);
	  $p->po_id=$_POST["pop$i"];
	  $p->oa_amount=$_POST["pamount$i"];

	  $p->oa_description=$_POST["pdesc"];
	  $p->oa_date=$_POST['pdate'];
	  $p->j_id=0;
	  $p->oa_debit=(isset ($_POST["pdeb$i"]))?'t':'f';
	  $p->oa_group=0;
	  $p->pa_id=$_POST['pa_id'];
	  $this->a_operation[]=clone $p;
	}
  }
  /*!\brief save the group of operation but only if the amount is
     balanced  */
  function save() {
	StartSql($this->db);
	try  {
	  $oa_group=NextSequence($this->db,'s_oa_group');
	  for ($i=0;$i<$this->nMaxRow;$i++) {
		$this->a_operation[$i]->oa_group=$oa_group;
		$this->a_operation[$i]->add();
	  }
	} catch (Exception $ex) {
	      echo '<span class="error">'.
			'Erreur dans l\'enregistrement '.
			__FILE__.':'.__LINE__.' '.
			$ex->getMessage();
		  Rollback($p_cn);
		  exit();

	}
	Commit($this->db);
  }
  /*!\brief show the form */
  function show() {
	return $this->form(1);
  }

}
