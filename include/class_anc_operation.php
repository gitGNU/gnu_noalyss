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
 *\brief definition of Anc_Operation
 */
require_once("class_ibutton.php");
require_once("class_ihidden.php");
require_once("class_iselect.php");
require_once("class_itext.php");
require_once("class_anc_plan.php");
require_once ("user_common.php");

/*! \brief this class is used to show the form for entering an
 *   operation only FOR analytic operation 
 *   to save it, to display or to get a list from a certain period
 *
 */
class Anc_Operation
{
  var $db; 	/*!< database connection */
  var $row;	/*!< array of row for one operation*/
  var $list;	/*!< array of all operation */
  var $id;	/*!< = oa_id (one row) */
  var $po_id;	/*!< poste analytique */
  var $oa_amount;	/*!< amount for one row */
  var $oa_description;	/*!< comment for one row */
  var $oa_debit;	/*!< true for debit or false */
  var $j_id;		/*!< foreign key to a jrnx operation
                              (or null if none */
  var $oa_group;   /*!< group of operation  */
  var $oa_date;	   /*!< equal to j_date if j_id is not	  null */
  var $pa_id;	/*!< the plan analytique id */

  /*!\brief constructor
   * 
   */
  function Anc_Operation ($p_cn,$p_id=0)   {
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
	  $this->oa_group=$this->db->get_next_seq('s_oa_group');
	}

	if ( $this->j_id == 0 ) {
	  $this->j_id="null";
	}

	  
	// we don't save null operations
	if ( $this->oa_amount == 0 || $this->po_id==-1) 
	  return;

	$oa_row=(isset($this->oa_row))?$this->oa_row:"NULL";
	$sql='insert into operation_analytique (
           po_id,
           pa_id,
           oa_amount,
           oa_description,
           oa_debit,
           oa_group,
           j_id,
           oa_date,
           oa_row
           ) values ('.
	  $this->po_id.",".
	  $this->pa_id.",".
	  $this->oa_amount.",".
	  "' ".pg_escape_string($this->oa_description)."',".
	  "'".$this->oa_debit."',".
	  $this->oa_group.",".
	  $this->j_id.",".
	  "to_date('".$this->oa_date."','DD.MM.YYYY'),".$oa_row.")";


	  $this->db->exec_sql($sql);

  }
  /*!\brief delete a row from the table operation_analytique
   * \note be carefull : do not delete a row when we have a group
   */
  function delete() {
	$sql="delete from operation_analytique where oa_id=".$this->oa_id;

	$this->db->exec_sql($sql);
  }

  /*!\brief update a row in  the table operation_analytique
   */
  function update() {
	if ( $this->po_id == -1) { $this->delete();return;}
	
	  $sql="update operation_analytique set po_id=".$this->po_id." where oa_id=".$this->oa_id;
	$this->db->exec_sql($sql);
  }

  /*!\brief get a list of row from a certain periode
   */
  function get_list($p_from,$p_to,$p_from_poste="",$p_to_poste="") {
	$cond="";
	$cond_poste="";

	if ($p_from!="")
	  $cond="and oa_date >= to_date('$p_from','DD.MM.YYYY') ";
	if ( $p_to!="" )
	  $cond.="and oa_date <=to_date('$p_to','DD.MM.YYYY')";

	if ($p_from_poste != "" )
	  $cond_poste=" and upper(po_name) >= upper('".$p_from_poste."')";
	if ($p_to_poste != "" )
	  $cond_poste.=" and upper(po_name) <= upper('".$p_to_poste."')";
	$pa_id_cond="";
	if ( isset ( $this->pa_id) && $this->pa_id !='')
	  $pa_id_cond= "B.pa_id=".$this->pa_id." and";

	$sql="select oa_id,po_name,oa_description,".
	  "oa_debit,to_char(oa_date,'DD.MM.YYYY') as oa_date,oa_amount,oa_group,j_id ".
	  " from operation_analytique as B".
	  " join poste_analytique using(po_id) ".
	  "where $pa_id_cond oa_amount <> 0.0 $cond $cond_poste".
	  " order by oa_date ,oa_group,oa_debit desc,oa_id";
	$RetSql=$this->db->exec_sql($sql);


	$array=pg_fetch_all($RetSql);
	return $array;
  }

  /*\brief show the HTML table for the operation
   */
  function html_table($p_from){


	if ($p_from=="")
	  { $from="";$to="";}
	else
	  list($from,$to)=get_periode($this->db,$p_from);


	$array=$this->get_list($from,$to);
	if ( empty($array)  )
	  return "Pas d'enregistrement trouv&eacute;";

	// jrn_navigation_bar
	$step=$_SESSION['g_pagesize'];
	$page=(isset($_GET['offset']))?$_GET['page']:1;
	$offset=(isset($_GET['offset']))?$_GET['offset']:0;	
	$bar=jrn_navigation_bar($offset+1,count($array),$step,$page);

	$view=array_splice($array,$offset,$step);
	$gDossier=dossier::id();
	$ret="";
	$ret.=$bar;
	$ret.=JS_VIEW_JRN_MODIFY;
	$count=0;
	$group=0;
	$oldgroup=0;
	$oldjrid=0;
	foreach ($view as $row) {
	  $group=$row['oa_group'];
	  if ( $group !=$oldgroup ) {
		if ( $oldgroup!=0 ) 
		  {
			
			  $efface=new IButton();
			  $efface->javascript="op_remove('".$_REQUEST['PHPSESSID']."',".$gDossier.",".$oldgroup.")";
			  $efface->name="Efface";
			  $efface->label="Efface";
			  $ret.="<td>".$efface->input()."</td>";
			  
			$this->oa_group=$oldgroup;
			$jr_id=$this->get_jrid();
			
			if ( $jr_id != 0) {
			  // get the old jr_id
			  $detail=new IButton();
			  $detail->javascript="viewOperation($jr_id,'".$_REQUEST['PHPSESSID']."',$gDossier)";
			  $detail->name="Detail";
			  $detail->label="Detail";
			  $ret.="<td>".$detail->input()."</td>";
			}
			$ret.='</table>';

		  }
		$ret.='<table id="'.$row['oa_group'].'" style="border: 2px outset blue; width: 70%;">';

		$ret.="<td>".
		  $row['oa_date'].
		  "</td>".
		  "<td>".
		  h($row['oa_description']).
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
	  if ( $cred=='CREDIT')
	    $ret.='<td></td>';
	  $ret.= "<td>".
		h($row['po_name']).
	    "</td>";
	  if ( $cred=='DEBIT')
	    $ret.='<td></td>';
	  
	  $ret.='<td>'.	$row['oa_amount'].
		"</td>".
		"<td>".
		$cred.
		"</td>".

		"</tr>";
		}


	$efface=new IButton();
	$efface->javascript="op_remove('".$_REQUEST['PHPSESSID']."',$gDossier,".$oldgroup.")";
	$efface->name="Efface";
	$efface->label="Efface";
	$ret.="<td>".$efface->input()."</td>";
	// get the old jr_id
	$this->oa_group=$oldgroup;
	$jr_id=$this->get_jrid();
	if ( $jr_id != 0 ){
	  $detail=new IButton();
	  $detail->javascript="viewOperation($jr_id,'".$_REQUEST['PHPSESSID']."',$gDossier)";
	  $detail->name="Detail";
	  $detail->label="Detail";
	  $ret.="<td>".$detail->input()."</td>";
	}
	$ret.='</table>';
	$ret.=$bar;
	return $ret;
  }
  /*!\brief retrieve an operation thanks a jrnx.j_id 
   * \param the jrnx.j_id
   * \return false if nothing is found other true
   */
  function get_by_jid($p_jid) {
	$sql="select distinct oa_id,
                  po_id,
                  oa_amount,
                  oa_description,
                  oa_debit,
                  j_id,
                  oa_group,
                  oa_date,
                  pa_id,
                  oa_row
          from operation_analytique 
          where 
          j_id=$p_jid order by j_id,oa_row";
	$ret=$this->db->exec_sql($sql);
	$res=pg_fetch_all($ret);
	echo_debug(__FILE__.":".__LINE__."count res is ",count($res));
	echo_debug(__FILE__.":".__LINE__," res =",$res);
	if ( $res== false) return null;

	foreach ($res as $row) {
	  $a=new Anc_Operation($this->db);
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
	$a=$this->get_by_jid($this->j_id);
	if ( $a == null ) {
	  // retrieve data from jrnx
	  $sql="select jr_date,j_montant,j_debit from jrnx ".
		" join jrn on (jr_grpt_id = j_grpt) ".
		"where j_id=".$this->j_id;
	  $res=$this->db->exec_sql($sql);
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
	$res=$this->db->exec_sql($sql);
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
	  $res=$this->db->exec_sql($sql);
	  $array=pg_fetch_all($res);
	  echo_debug(__FILE__.":".__LINE__," array =",$array);
	} catch (Exception $e) {
	  var_dump($e);
	}
  }
  /*!\brief display the form for PA
   * \param $p_null = 1 if PA optional otherwise 0 mandatory 
   * \param $p_mode == form 1 ==> read/write otherwise 0==>readonly
   * \param $p_seq number of the row
   * \param $p_amount amount 
   * \see save_form_plan
   */
 function display_form_plan($p_array,$p_null,$p_mode,$p_seq,$p_amount) {
   echo_debug(__FILE__.':'.__LINE__,' display_form_plan($p_array,$p_null,$p_doc,$p_seq,$p_amount) '," $p_array,$p_null,$p_mode,$p_seq,$p_amount) ");
   if ( $p_array != null)
     extract ($p_array);
   $result="";
   $plan=new Anc_Plan($this->db);
   $a_plan=$plan->get_list();
   if ( empty ($a_plan) ) return "";
   $table_id="t".$p_seq;

   $hidden=new IHidden();


   $result.=$hidden->input('amount_'.$table_id,$p_amount);
   if ( $p_mode==1 )
     $result.='<table id="'.$table_id.'">';
   else 
        $result.='<table>';
   $result.="<tr>".$plan->header()."<th>montant</th></tr>";



   $nb_row=(!isset(${'nb_'.$table_id}))?1:${'nb_'.$table_id};
   $result.=$hidden->input('nb_'.$table_id,$nb_row);

   for ( $i=1; $i <= $nb_row;$i++) {
	 $result.='<tr>';
	 $count=0;

	 foreach ($a_plan as $r_plan)
	   {
		 $count++;
	 
		 $array=$this->db->make_array(
						   "select pa_id||'_'||po_id as value,".
						   " html_quote(po_name) as label from poste_analytique ".
						   " where pa_id = ".$r_plan['id'].
						   " order by po_name",$p_null);
		 $select =new ISelect("","ta_".$p_seq."o".$count."row_".$i,$array);
		 $select->table=0;
		 // view only or editable
		 if (  $p_mode==1 ) {
		   // editable
		   $select->readonly=false;
	           $select->selected=(isset(${"ta_".$p_seq."o".$count."row_".$i}))?${"ta_".$p_seq."o".$count."row_".$i}:0;
		 } else {
		   // view only
		   $select->readonly=true;
		   $select->selected=(isset(${"ta_".$p_seq."o".$count."row_".$i}))?${"ta_".$p_seq."o".$count."row_".$i}:0;
		 }
		 if ($p_mode==1)
		   $result.='<td id="'.$table_id.'td'.$count.'c'.$i.'">'.$select->input().'</td>';
		 else
		   $result.='<td>'.$select->input().'</td>';
	   

	   }
	 $value=new IText();
	 $value->name="val".$p_seq."l$i";
	 $value->size=6;
	 $value->value=(isset(${"val".$p_seq."l$i"}))?round(${"val".$p_seq."l$i"},2):$p_amount;
	 //	 $value->value=($p_doc=="form")?$p_amount:round(${"val".$p_seq."l$i"},2);
	 $value->readonly=($p_mode==1)?false:true;
	 
	 $result.='<td>'.$value->input().'</td>';

	 $result.="</tr>";
   }

   $result.="</table>";
   // add a button to add a row
   $button=new IButton();
   $button->javascript="onChange=add_row('$table_id',$p_seq,$count);";
   $button->name="js".$p_seq;
   $button->label="Nouvelle ligne";
   if ( $p_mode == 1 )
	 $result.=$button->input();
   return $result;
 }
 /*!\brief it called for each item, the data are taken from $p_array
  *  data and set before in this. 
  * \param $p_item if the item nb for each item (purchase or selling
  *  merchandise)
  * \param $p_array structure 
  * \verbatim
   nb_tA A is the number of the item contains the number of
           rows of CA for this card 
   valAlR amount for the CA (item A row R)
   ta_AoCrow_R contains the value of the pa_id and po_id for this
               row with the form pa_id_po_id %d_%d
  *\endverbatim
  * \attention The idea is one j_id matches several oa_id, 
  *  serveral data are set before the call :
  *   -j_id
  *   -oa_debit
  *   -oa_group
  *   -oa_date      
  *   -oa_description
  *       
  */
 function save_form_plan($p_array,$p_item) {
   echo_debug(__FILE__.':'.__LINE__,"p_array is ",$p_array);
   extract($p_array);
   if ( !isset (${"nb_t".$p_item}) ) {
     //	 echo __FILE__.':'.__LINE."nb_t".$p_item." n'est pas defini !!!";
     return;
   }
   $max=${"nb_t".$p_item};
   echo_debug(__FILE__.':'.__LINE__.'- ', "max of row for CA = ".$max);
   // get all the PA
   $plan=new Anc_Plan($this->db);
   $cplan=$plan->count(); 
   echo_debug(__FILE__.':'.__LINE__," nb of PA $cplan");
   // foreach row 
   for ($i=1;$i<=$max;$i++) {
	 echo_debug(__FILE__.':'.__LINE__." loop i $i");

	 // foreach col PA			
	 for ($e=1;$e<=$cplan+1;$e++)
	   { 
		 echo_debug(__FILE__.':'.__LINE__."loop e $e");
		 echo_debug(__FILE__.':'.__LINE__," Checking ta_".$p_item."o".$e."row_".$i);
		 if ( isset(${"ta_".$p_item."o".$e."row_".$i}) && ${"ta_".$p_item."o".$e."row_".$i}!=-1)
		   { 
		     echo_debug(__FILE__.':'.__LINE__,"Value is ".${"ta_".$p_item."o".$e."row_".$i});			 
		     $op=new Anc_Operation($this->db); 
		     $val=${"ta_".$p_item."o".$e."row_".$i};
		     list($op->pa_id,$op->po_id)=sscanf($val,"%d_%d");
		     $op->oa_group=$this->oa_group;
		     $op->j_id=$this->j_id; 
		     $op->oa_amount=${"val".$p_item."l".$i};
		     $op->oa_debit=$this->oa_debit;
		     $op->oa_date=$this->oa_date;
		     
		     $op->oa_description=$this->oa_description;
		     $op->oa_row=$i;
		     $op->add(); 
		   } 	 
	   }
   }
   
 }
 /*\brief transform a array of operation into a array usage by
  *display_form_plan & save_form_plan
  *\param $p_array array of operation
  *\param $p_line line 
  *\return an array complying with \see save_form_plan
  */
 function to_request ($p_array,$p_line){
   if ( count($p_array) == 0 ) {
	 echo_debug(__FILE__.':'.__LINE__,"p_array est vide",$p_array);
	 return null;
   }
   $result=array();
   $table="nb_t".$p_line;

   $col=1;
   $line=1;
   $first=true;
   $last_pa_id=0;
   foreach ( $p_array as $row) {
     $val="val".$p_line."l".$row->oa_row;
     $result[$val]=$row->oa_amount;
     if ( $first ) {
       $first_pa_id=$row->pa_id;
       $first=false;
     }

     if ( $first_pa_id != $row->pa_id )
       $col++;
     else {
       $col=1;
       $line++;
     }
     $po="ta_".$p_line."o".$col."row_".$row->oa_row;
     $result[$po]=$row->pa_id."_".$row->po_id;
     //     $last_pa_id=$row->pa_id;
   }
   $result[$table]=$line-1;
   return $result;
 }
/*! 
 * \brief delete from operation_analytique
 * \param $p_jid the operation_analytique.j_id field
 *
 * \return none
 */
 function delete_by_jid($p_jid) {
   $sql="delete from operation_analytique where j_id=$p_jid";
   $this->db->exec_sql($sql);
 }

 /*\brief test the class
  *\param
  *\param
  *\return
  */
 function test_me() {
   $array=$this->get_by_jid(442);
   echo_debug(__FILE__.':'.__LINE__,"resultat get_by_jid",$array);

   $a=$this->to_request($array,1);   
   echo_debug(__FILE__.':'.__LINE__,"resultat to_request ligne 1",$a);


 }
}
