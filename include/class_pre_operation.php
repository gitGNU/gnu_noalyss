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
 * \brief definition of Pre_operation
 */

/*! \brief manage the predefined operation, link to the table op_def
 * and op_def_detail
 *
 */
require_once ('class_widget.php');
class Pre_operation 
{
  var $db;						/*!< $db database connection */
  var $nb_item;					/*!< $nb_item nb of item */
  var $p_jrn;					/*!< $p_jrn jrn_def_id */
  var $jrn_type;					/*!< $jrn_type */
  var $name;						/*!< $name name of the predef. operation */

  function Pre_operation($cn) {
	$this->db=$cn;
	$this->od_direct='false';
  }
  /*!\brief fill the object with the $_POST variable */
  function get_post() {
	echo_debug(__FILE__.':'.__LINE__.'-',' $_POST',$_POST);
	$this->nb_item=$_POST['nb_item'];
	$this->p_jrn=$_REQUEST['p_jrn'];
	$this->jrn_type=$_POST['jrn_type'];
	$this->name=(isset($_POST['e_comm']))?$_POST['e_comm']:"";
	if ( $this->name=="") {
	  $n=NextSequence($this->db,'op_def_op_seq');
	  $this->name=$this->jrn_type.$n;
	// common value
	}
  }
  function delete () {
	$sql="delete from op_predef where od_id=".$this->od_id.
	  			  " and od_direct ='".$this->od_direct."'";
	ExecSql($this->db,$sql);
  }
  /*!\brief save the predef check first is the name is unique
   * \return true op.success otherwise false
   */
  function save() {
	if (	CountSql($this->db,"select * from op_predef ".
			 "where upper(od_name)=upper('".pg_escape_string($this->name)."')".
					 "and jrn_def_id=".$this->p_jrn)
			!= 0 )
	  {
		echo "Cette op&eacute;ration a d&eacute;j&agrave; &eacute;t&eacute; sauv&eacute;e";
		return false;
	  }
	if ( $this->count()  > 20 ){
	  echo '<h2 class="info">Vous avez atteint le max. d\'op&eacute;ration pr&eacute;d&eacute;finie, d&eacute;sol&eacute;</h2>';
	  return false;
	}
	$sql=sprintf('insert into op_predef (jrn_def_id,od_name,od_item,od_jrn_type,od_direct)'.
				 'values'.
				 "(%d,'%s',%d,'%s','%s')",
				 $this->p_jrn,
				 pg_escape_string($this->name),
				 $this->nb_item,
		     $this->jrn_type,
		     $this->od_direct);
	ExecSql($this->db,$sql);
	$this->od_id=GetSequence($this->db,'op_def_op_seq');
	return true;
  }
  /*!\brief load the data from the database and return an array
   * \return an array 
   */
  function load() {
	$sql="select od_id,jrn_def_id,od_name,od_item,od_jrn_type".
	  " from op_predef where od_id=".$this->od_id.
	  " and od_direct='".$this->od_direct."'".
	  " order by od_name";
	$res=ExecSql($this->db,$sql);
	$array=pg_fetch_all($res);
	echo_debug(__FILE__.':'.__LINE__.'- ','load pre_op',$array);

	return $array;
  }
  function compute_array() {
	$p_array=$this->load();
	$array=array(
				 "e_comm"=>$p_array[0]["od_name"],
				 "nb_item"=>$p_array[0]["od_item"],
				 "p_jrn"=>$p_array[0]["jrn_def_id"],
				 "jrn_type"=>$p_array[0]["od_jrn_type"]
				 );
	return $array;
				 
  }
  
  /*!\brief show the button for selecting a predefined operation */
  function show_button() {

	$select=new widget("select");
	$value=make_array($this->db,"select od_id,od_name from op_predef ".
			  " where jrn_def_id=".$this->p_jrn.
			  " and od_direct ='".$this->od_direct."'".
			  " order by od_name");

	if ( empty($value)==true) return "";
	$select->value=$value;
	$r=$select->IOValue("pre_def");
	return $r;
  }
  /*!\brief count the number of pred operation for a ledger */
  function count() {
	$a=CountSql($this->db,"select od_id,od_name from op_predef ".
		    " where jrn_def_id=".$this->p_jrn.
		    " and od_direct ='".$this->od_direct."'".
		    " order by od_name");
	return $a;
  }
  /*!\brief get the list of the predef. operation of a ledger
   * \return string
   */
  function get_list_ledger() {
    $sql="select od_id,od_name from op_predef ".
      " where jrn_def_id=".$this->p_jrn.
      " and od_direct ='".$this->od_direct."'".
      " order by od_name";
  $res=ExecSql($this->db,$sql);
  $all=pg_fetch_all($res);
  return $all;
  }
  /*!\brief set the ledger
   * \param $p_jrn is the ledger (jrn_id)
   */
  function set_jrn($p_jrn) {
	$this->p_jrn=$p_jrn;
  }
}

/*!\brief mother of the pre_op_XXX, it contains only one data : an
 * object Pre_Operation. The child class contains an array of
 * Pre_Operation object
 */
class Pre_operation_detail {
  var $operation;
  function __construct($p_cn) {
	$this->db=$p_cn;
	$this->operation=new Pre_operation($this->db);
	$this->valid=array('ledger'=>'jrn_def_id','ledger_type'=>'jrn_type','direct'=>'od_direct');

  }


  /*!\brief show a form to use pre_op
   */
  function form_get () {
  
     $hid=new widget("hidden");
     $r=$hid->IOValue("action","use_opd");
     $r.=dossier::hidden();
     $r.=$hid->IOValue("p_jrn",$this->get("ledger"));
     $r.=$hid->IOValue("jrn_type",$this->get("ledger_type"));

     if ($this->count() != 0 )
       $r.= widget::submit('use_opd','Utilisez une op.pr&eacute;d&eacute;finie');
     $r.= $this->show_button();
     return $r;

  }
  /*!\brief count the number of pred operation for a ledger */
  function count() {
    $a=CountSql($this->db,"select od_id,od_name from op_predef ".
		    " where jrn_def_id=".$this->jrn_def_id.
		    " and od_direct ='".$this->od_direct."'".
		    " order by od_name");
    return $a;
  }
  /*!\brief show the button for selecting a predefined operation */
  function show_button() {

	$select=new widget("select");
	$value=make_array($this->db,"select od_id,od_name from op_predef ".
			  " where jrn_def_id=".$this->jrn_def_id.
			  " and od_direct ='".$this->od_direct."'".
			  " order by od_name");

	if ( empty($value)==true) return "";
	$select->value=$value;
	$r=$select->IOValue("pre_def");
	return $r;
  }

  function set($p_param,$value) {
    if ( ! isset ($this->valid[$p_param] ) ) {
      echo(" le parametre $p_param n'existe pas ".__FILE__.':'.__LINE__);
      exit();
    }
    $attr=$this->valid[$p_param];
    $this->$attr=$value;
  }
  function get($p_param) {

    if ( ! isset ($this->valid[$p_param] ) ) {
      echo(" le parametre $p_param n'existe pas ".__FILE__.':'.__LINE__);
      exit();
    }
    $attr=$this->valid[$p_param];
    return $this->$attr;
  }

  function get_post() {
	$this->operation->get_post();
  }

}
