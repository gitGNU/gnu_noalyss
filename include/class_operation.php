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

/*! \brief this class is used to show the form for entering an
 *   operation
 *   to save it, to display or to get a list from a certain period
 *
 */
class operation 
{
  var $db; 						/*!< database connection */
  var $row;						/*!< array of row for one operation*/
  var $list;					/*!< array of all operation */
  var $id;						/*!< = oa_id (one row) */
  var $po_id;					/*!< poste analytique */
  var $oa_amount;				/*!< amount for one row */
  var $oa_description;			/*!< comment for one row */
  var $oa_debit;				/*!< true for debit or false */
  var $j_id;					/*!< foreign key to a jrnx operation
                                   (or null if none */
  var $oa_group;				/*!< group of operation  */
  var $oa_date;					/*!< equal to j_date if j_id is not
								  null */
  var $pa_id;					/*!< the plan analytique id */

  /*!\brief constructor
   * 
   */
  function operation ($p_cn,$p_id=0)   {
	$this->db=$p_cn;
	$this->id=$p_id;
  }
  /*!\brief add a row  to the table operation_analytique
   * \note if $this->oa_group if 0 then a sequence id will be computed for
   * the oa_group, if $this->j_id=0 then it will be null
   *
   */
  function add($p_seq=0) {

	if ( $this->oa_group == 0) {
	  $this->oa_group=NextSequence($this->db,'s_oa_group');
	}

	if ( $this->j_id == 0 ) {
	  $this->j_id="null";
	}

	  
	// we don't save null operations
	if ( $this->oa_amount == 0 || $this->po_id==-1) 
	  return;
	$sql='insert into operation_analytique (
           po_id,
           pa_id,
           oa_amount,
           oa_description,
           oa_debit,
           oa_group,
           j_id,
           oa_date
           ) values ('.
	  $this->po_id.",".
	  $this->pa_id.",".
	  $this->oa_amount.",".
	  "' ".pg_escape_string($this->oa_description)."',".
	  "'".$this->oa_debit."',".
	  $this->oa_group.",".
	  $this->j_id.",".
	  "to_date('".$this->oa_date."','DD.MM.YYYY'))";


	  ExecSql($this->db,$sql);

  }
  /*!\brief delete a row from the table operation_analytique
   * \note be carefull : do not delete a row when we have a group
   */
  function delete() {
	$sql="delete from operation_analytique where oa_id=".$this->oa_id;

	ExecSql($this->db,$sql);
  }

  /*!\brief update a row in  the table operation_analytique
   * \todo update
   */
  function update() {
	if ( $this->po_id == -1) { $this->delete();return;}
	
	  $sql="update operation_analytique set po_id=".$this->po_id." where oa_id=".$this->oa_id;
	ExecSql($this->db,$sql);
  }

  /*!\brief get a list of row from a certain periode
   * \todo to be done
   */
  function get_list($p_from,$p_to) {
	$cond="";
	if ($p_from!="")
	  $cond="and oa_date >= to_date('$p_from','DD.MM.YYYY') and oa_date <=to_date('$p_to','DD.MM.YYYY')";

	$sql="select oa_id,po_name,oa_description,".
	  "oa_debit,to_char(oa_date,'DD.MM.YYYY') as oa_date,oa_amount,oa_group,j_id ".
	  " from operation_analytique as B".
	  " join poste_analytique using(po_id) ".
	  "where B.pa_id=".$this->pa_id." and oa_amount <> 0.0 $cond".
	  " order by oa_date ,oa_group,oa_debit,oa_id";
	$RetSql=ExecSql($this->db,$sql);


	$array=pg_fetch_all($RetSql);
	return $array;
  }

  /*\brief show the HTML table for the operation
   */
  function html_table($p_from){


	if ($p_from=="")
	  { $from="";$to="";}
	else
	  list($from,$to)=GetPeriode($this->db,$p_from);


	$array=$this->get_list($from,$to);
	if ( empty($array)  )
	  return "Pas d'enregistrement trouv&eacute;";
	
	  $gDossier=dossier::id();
	  $ret.="";
	  $ret.=JS_VIEW_JRN_MODIFY;
	  $count=0;
	  $group=0;
	  $oldgroup=0;
	  $oldjrid=0;
	  foreach ($array as $row) {
		$group=$row['oa_group'];
		if ( $group !=$oldgroup ) {
		  if ( $oldgroup!=0 ) 
			{

			  $efface=new widget('button');
			  $efface->js="op_remove('".$_REQUEST['PHPSESSID']."',".$gDossier.",".$oldgroup.")";
			  $efface->name="Efface";
			  $efface->label="Efface";
			  $ret.="<td>".$efface->IOValue()."</td>";
			  
			$this->oa_group=$oldgroup;
			$jr_id=$this->get_jrid();
			
			if ( $jr_id != 0) {
			  // get the old jr_id
			  $detail=new widget('button');
			  $detail->js="viewOperation($jr_id,'".$_REQUEST['PHPSESSID']."',$gDossier)";
			  $detail->name="Detail";
			  $detail->label="Detail";
			  $ret.="<td>".$detail->IOValue()."</td>";
			}
			$ret.='</table>';

		  }
		$ret.='<table id="'.$row['oa_group'].'" style="border: 2px outset blue; width: 70%;">';

		$ret.="<td>".
		  $row['oa_date'].
		  "</td>".
		  "<td>".
		  $row['oa_description'].
		  "</td>";

		$ret.="<td>".
		  "Groupe id : ".$row['oa_group'].
		  "</td>";


		$oldgroup=$group;
		
	  }

	  $class=($count%2==0)?"odd":"";
	  $count++;
	  $cred= ( $row['oa_debit'] == 'f')?"CREDIT":"DEBIT";
	  $ret.="<tr class=\"$class\">";
	  $ret.= "<td>".
		$row['po_name'].
		"</td>".
		"<td>".
		$row['oa_amount'].
		"</td>".
		"<td>".
		$cred.
		"</td>".

		"</tr>";
		}

	$efface=new widget('button');
	$efface->js="op_remove('".$_REQUEST['PHPSESSID']."',$gDossier,".$oldgroup.")";
	$efface->name="Efface";
	$efface->label="Efface";
	$ret.="<td>".$efface->IOValue()."</td>";
	// get the old jr_id
	$this->oa_group=$oldgroup;
	$jr_id=$this->get_jrid();
	if ( $jr_id != 0 ){
	  $detail=new widget('button');
	  $detail->js="viewOperation($jr_id,'".$_REQUEST['PHPSESSID']."',$gDossier)";
	  $detail->name="Detail";
	  $detail->label="Detail";
	  $ret.="<td>".$detail->IOValue()."</td>";
	}
	$ret.='</table>';
	return $ret;
  }
  /*!\brief retrieve an operation thanks a jrnx.j_id 
   * \param the jrnx.j_id
   * \return false if nothing is found other true
   */
  function get_operation_by_jid($p_jid) {
	$sql="select  oa_id,
                  po_id,
                  oa_amount,
                  oa_description,
                  oa_debit,
                  j_id,
                  oa_group,
                  oa_date,
                  pa_id
          from operation_analytique 
          where 
          j_id=$p_jid";
	$ret=ExecSql($this->db,$sql);
	$res=pg_fetch_all($ret);
	echo_debug(__FILE__.":".__LINE__."count res is ",count($res));
	echo_debug(__FILE__.":".__LINE__," res =",$res);
	if ( $res== false) return null;

	foreach ($res as $row) {
	  $a=new operation($this->db);
	  foreach ( $row as $attr=>$value ) 
		{
		  $a->$attr=$row[$attr];
		}
	  $array[]=clone $a;
	}
	return $array;
  }
  /*!\brief modify an op from modify_op.php
   *
   */
  function update_from_jrnx($p_po_id){
	$a=$this->get_operation_by_jid($this->j_id);
	if ( $a == null ) {
	  // retrieve data from jrnx
	  $sql="select jr_date,j_montant,j_debit from jrnx ".
		" join jrn on (jr_grpt_id = j_grpt) ".
		"where j_id=".$this->j_id;
	  $res=ExecSql($this->db,$sql);
	  if (pg_NumRows($res) == 0 ) return;
	  $row=pg_fetch_array($res,0);
	  $this->oa_amount=$row['j_amount'];
	  $this->oa_date=$row['jr_date'];
	  $this->oa_debit=$row['j_debit'];
	  $this->oa_description=$row['jr_comment'];
	  $this->add();
	} else {
	  foreach ($a as $row ) {
		if ( $row->pa_id == $this->pa_id ) {
		  $row->po_id=$p_po_id;
		  $row->update();
		}
	  }
	}
  }
  /*!\brief retrieve the jr_id thanks the oa_group */
  function get_jrid() {
	$sql="select distinct jr_id from jrn join jrnx on (j_grpt=jr_grpt_id) join operation_analytique using (j_id) where j_id is not null and oa_group=".$this->oa_group;
	$res=ExecSql($this->db,$sql);
	if ( pg_NumRows($res) == 0 ) return 0;
	$ret=pg_fetch_all($res);
	return $ret[0]['jr_id'];
  }
  /*\brief this function get the balance for a certain period
   *\param $p_from from date (accountancy period)
   *\param $p_to to dat  (accountancy period)
   *\param  $p_plan_id the plan id
   */
function get_balance($p_from,$p_to,$p_plan_id)
  {
	// for the operation connected to jrnx
	$cond=sql_filter_per($this->db,$p_from,$p_to,'p_id','j_date');
	$sql="select oa_id, po_id, oa_amount, oa_debit, j_date from jrnx join operation_analytique using (j_id) 
         join poste_analytique using (pa_id)
          where 
          $cond and j_id is not null and pa_id=$p_plan_id";

	// OD 
	$cond=sql_filter_per($this->db,$p_from,$p_to,'p_id','oa_date');
	$sql="union select oa_id, po_id, oa_amount, oa_debit,oa_date from 
               operation_analytique 
               join poste_analytique using (pa_id)
          where j_id is null and 
      $cond and pa_id=$p_plan_id ";
	try { 
	  $res=ExecSql($this->db,$sql);
	  $array=pg_fetch_all($res);
	  echo_debug(__FILE__.":".__LINE__," array =",$array);
	} catch (Exception $e) {
	  var_dump($e);
	}
  }
}
