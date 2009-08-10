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
 * \brief it is a object including the class bud_detail bud_card,and
 * bud_detail_periode, 
 * the purpose is to insert or save into bud_detail_periode
 */

/*! \brief it is a object including the class bud_detail bud_card,and
 * bud_detail_periode, 
 * the purpose is to insert or save into bud_detail_periode
 */
require_once("class_itext.php");
require_once("class_iselect.php");
require_once("class_ibutton.php");
require_once ('class_database.php');
require_once ('constant.php');
require_once ('debug.php');
require_once ('ac_common.php');
require_once ('class_bud_detail.php');
require_once ('class_bud_detail_periode.php');



class Bud_Data {
  var $bh_id;
  var $bc_id;
  var $bd_id;
  var $pcm_val;

  var $amount;			/*!< array of amount, the key is the
                                   p_id and the content is the amount */
  var $cn;
  var $bud_detail_periode;

  function __construct($p_cn,$p_bh_id=0,$p_po_id=0) {
    $this->cn=$p_cn;
    $this->bh_id=$p_bh_id;
    $this->po_id=$p_po_id;
   

   
   
  }
  /*!\brief Load Data from Database 
   *
   * \return an array of Bud_Data objects
   */
  function load() {
    if ( $this->po_id == -1) 
      $sql_po_id="po_id is null";
    else 
      $sql_po_id="po_id = $this->po_id";
    $sql=" select bd_id,bc_id,bc_code,bd.bh_id,bd.bh_name,".
      " tmp_pcmn.pcm_val as pcm_val,pcm_lib ".
      " from bud_detail ".
      " join bud_hypothese as bd using (bh_id) ".
      "  join bud_card using (bc_id) ".
      " join tmp_pcmn using (pcm_val) ".
      " where bd.bh_id=".$this->bh_id.
      " and $sql_po_id ;";
    echo_debug(__FILE__.':'.__LINE__.'- load',' SQL ',$sql);

    $res1=$this->cn->exec_sql($sql);
    $array=Database::fetch_all($res1);

    
    $sql_periode="select coalesce(bdp_amount,0) as bdp_amount,a.p_id as p_id ".
      " from bud_detail_periode left join parm_periode as a using (p_id)  ".
      " where bd_id=$1 order by p_start,p_end ";
       

    $ret=array();


    if ( ! empty($array)){
      foreach ($array as $row) {
	$obj=new Bud_Data($this->cn,$this->bh_id,$this->po_id);
	$obj->load_from_array($row);
	echo_debug(__FILE__,__LINE__," load ");
	echo_debug(__FILE__,__LINE__,$obj);
	try {
	  // get the the bud_detail_periode for this bud_detail
	  $arg=array($obj->bd_id);
	  $res2=$this->cn->exec_sql($sql_periode,$arg);
	  $per=Database::fetch_all($res2);
	} catch (Exception $e) {
	  echo __FILE__.__LINE__."Erreur lors du chargement ";
	  echo_debug(__FILE__,__LINE__,$arg);
	  echo_debug(__FILE__,__LINE__,$e);
	  echo $e->getMessage();
	  exit();
	}
	
	foreach ($per as $row2) {
	  echo_debug(__FILE__.':'.__LINE__.'- row','',$row2);
	  $p_id=$row2['p_id'];
	  $obj->amount[$p_id]=$row2['bdp_amount'];
	}
	$ret[]=clone $obj;
      }
    }
    echo_debug(__FILE__.':'.__LINE__,'Return load',$ret);
    return $ret;
  }
  /*!\brief  create a empty row (subform)
   *
   * \return a Bud_Data object
   */

  private function create_empty_row() {
    $ret=new Bud_Data($this->cn,$this->bh_id,$this->po_id);
    $ret->bd_id=0;
    $ret->pcm_lib="";
    // populate the  periode with 0
    $res_empty=$this->cn->get_array(
			 "select 0,p_id from parm_periode order by p_start,p_end");
    foreach ( $res_empty as $r) {
      $p_id=$r['p_id'];
      $ret->amount[$p_id]=0;
    }

    return $ret;
  }
  /*!\brief convert the array loaded from the database into data member
   * \param array
   *
   */

  private function load_from_array($p_array) {
    foreach (array('bd_id','bh_id','bc_id','pcm_val','pcm_lib','bh_name','bc_code') as $key) {
      $this->{$key}=$p_array[$key];
    }
  }
  /*!\brief  create all the row of the form, MAX_BUD_DETAIL is the
   * maximum of sub form the form may contains (defined in constant.php)
   *
   * \return string with html code
   */

  function form() {
    $r="";
    $array=$this->load();
    echo_debug(__FILE__.':'.__LINE__,'form : $array',$array);
    $a=0;
    if ( ! empty ($array) ) {
      foreach ($array as $row) {
	$a++;
	$style=($a%2==0)?"even":"odd";
	echo_debug(__FILE__.':'.__LINE__,'function form :row ',$row);
	$r.=$row->create_row($style);
      }
    }
    for ($i=count($array);$i< MAX_BUD_DETAIL;$i++) {
	$a++;
	$style=($a%2==0)?"even":"odd";

	$obj=$this->create_empty_row($style);
	$r.=$obj->create_row();
    }
    

    return $r;
  }
  /*!\brief Create one form 
   * \param the style
   * \return string
   */

  private function create_row($p_style="odd") {
    static $p_number=0;
    echo_debug(__FILE__.':'.__LINE__.'- create_row','',$this);
    $p_number++;
    $tot=0;

    $wAmount=new IText();
    $wAmount->size=8;
    $wAmount->extra="disabled";

     $wAccount=new widget('js_bud_search_poste');
    $wAccount->table=0;
    $wAccount->disabled=true;
    $wAccount->value=$this->pcm_val.' - '.$this->pcm_lib;
    $wAccount->extra=$this->pcm_val;

    $wBudCard=new ISelect();
    $wBudCard->value=$this->load_bud_card();
    $wBudCard->selected=$this->bc_id;
    $wBudCard->disabled=true;

    foreach ($this->amount as $p_id=>$amount)
      $tot+=$amount;

    $r='<form id="form_'.$p_number.'" disabled>';
    $r.=dossier::hidden();
    $r.=HtmlInput::hidden('po_id',$this->po_id);
    $r.=HtmlInput::hidden('bh_id',$this->bh_id);
    $r.=HtmlInput::hidden('bd_id',$this->bd_id);
    $r.=HtmlInput::hidden('form_id',$p_number);
    
    $r.="Fiche Budget ".$wBudCard->input('bc_id'.$p_number);
    $r.="Compte d'exploitation ".$wAccount->input('account_'.$p_number);
    //    $r.=HtmlInput::hidden('account_'.$p_number.'_hidden',$this->pcm_val);

    $r.='Total : <span id="form_total_'.$p_number.'"> '.$tot.' </span>';
    $r.='<table WIDTH="100%">';
    $r.=$this->header_table();
    $r.="<tr> ";
    foreach ($this->amount as $p_id=>$amount){
      $wAmount->javascript="onChange='bud_compute_sum(".$p_number.");'";
      echo_debug(__FILE__.':'.__LINE__,' p_id '.$p_id.' - amount '.$amount);
      $tot+=$amount;
      $r.='<td >'.$wAmount->input('amount_'.$p_id,sprintf("% 8.2f",$amount))."</td>";
    }
    $r.="</tr>";
    $r.="</table>";
    $r.=HtmlInput::hidden('PHPSESSID',$_REQUEST['PHPSESSID']);

    $r.="</form>";
    $button_change=new IButton('Change');
    $button_change->javascript='bud_form_enable('.$p_number.')';
    $r.=$button_change->input('button_change'.$p_number);


    $button_save=new IButton('Sauve');
    $button_save->javascript='bud_form_save('.$p_number.')';
    $button_save->extra='style="display:none"';
    $r.=$button_save->input('button_save'.$p_number);

    $button_delete=new IButton('Efface');
    $button_delete->javascript='bud_form_delete('.$p_number.')';
    $button_delete->extra='style="display:none"';
    $r.=$button_delete->input('button_delete'.$p_number);

    $button_escape=new IButton('Echapper');
    $button_escape->javascript='bud_form_disable('.$p_number.')';
    $button_escape->extra='style="display:none"';
    $r.=$button_escape->input('button_escape'.$p_number);

    $r.='<span id="Result'.$p_number.'"></span>';
    $r.="<hr>";
    return $r;
  }
  /*!\brief Load all the card of a Hypothese
   *
   * \return double array [0][bc_id, bc_code]
   */

  private function load_bud_card() {
    if ( !isset ($this->array_bud_card) )
      $this->array_bud_card=$this->cn->make_array(
				       'select bc_id,bc_code from  bud_card '.
				       'where bh_id='.$this->bh_id);
    return $this->array_bud_card;
  }

  /*!\brief create the heading line for the table ( of a form)
   *
   * \return String
   */

  private function header_table() {
    if ( ! isset ($this->header ) ){
      $r='<table style="border: 2px outset blue; width: 100%;">';
      $r.="<tr>";
      $periode=$this->cn->get_array("select  to_char(p_start,'MM/YY')as d from parm_periode order by p_start,p_end ");
      foreach ($periode as $row)
	$r.='<th >'.$row['d'].'</th>';

      $r.="</tr>";
      $this->header=$r;
    }
    return $this->header;
  }
  /*!\brief convert an array into an object Bud_Detail_Periode the
   * array must contains the keys bd_id, po_id,bc_id,pcm_val, bh_id
   * and amount_xx where xx stands for the p_id (pk of parm_periode)
   * \param array
   *
   */

  function get_from_array($p_array) {
    foreach (array('bd_id','po_id','bc_id','pcm_val','bh_id') as $attr) {
      if ( isset ($p_array[$attr])) {
	$this->$attr=$p_array[$attr];
      }
    }

    $this->get_form_detail_periode($p_array);
    echo_debug(__FILE__.':'.__LINE__.'- get_from_array','',$this);
  }

  /*!\brief  Add in the table Bud_Detail and Bud_Detail_Periode
   *
   */

  function add() {
    $r=$this->extract_bud_detail();
    $r->add();
    $this->bd_id=$r->bd_id;

    foreach ( $this->bud_detail_periode as $r ) {
      $r->bd_id=$this->bd_id;
      $r->add();
    }
   
  }
  /*!\brief create an object Bud_Detail thanks the attr. of an object Bud_Data
   * \return an object Bud_Detail
   */

  function     extract_bud_detail() {
   foreach ( array('bd_id','po_id','bc_id','pcm_val','bh_id') as $r1) {
     $a[$r1]=$this->$r1;
   }
   $r=new Bud_Detail($this->cn);
   
   $r->get_from_array($a);
   return $r;
  }
  /*!\brief  update the table bud_detail and bud_detail_periode
   *
   */
 
  function update() {

    $r=$this->extract_bud_detail();
    $r->update();
    $this->cn->exec_sql("delete from bud_detail_periode where bd_id =".$this->bd_id);
    echo_debug(__FILE__.':'.__LINE__.'- update ',$this->bud_detail_periode);
    foreach ( $this->bud_detail_periode as $obj ) {
      $obj->add();
    }

  }
  /*!\brief verify that all the amount are numeric */
  function verify() {
    foreach ($this->bud_detail_periode as $obj) {
      if ( isNumber($obj->bdp_amount) == 0 ) {
	$obj->bdp_amount=0;
      }
    }
  }
  /*!\brief delete in bud_detail 
   */

  function delete_by_bd_id() {
    $this->cn->exec_sql('delete from bud_detail where bd_id='.$this->bd_id);
  }
  /*!\brief transform an array containing the word amount_xx where xx
   * stand for the p_id ( parm_periode primary key) into the data
   * member this->bud_detail_periode
   * \param the array
   */

  private function get_form_detail_periode($p_array){
    echo_debug(__FILE__.':'.__LINE__.'- get_form_detail_periode arg:','',$p_array);
    extract ($p_array);
    $periode=$this->cn->get_array("select p_id from parm_periode");

    foreach ($periode as $key=>$value ) {
      $row=$value['p_id'];

      if ( isset (${'amount_'.$row} )) {
	$obj=new Bud_Detail_Periode($this->cn);
	$obj->p_id=$row;
	$obj->bd_id=$this->bd_id;
	$obj->bdp_amount=${'amount_'.$row};
	$this->bud_detail_periode[]=clone $obj;
      }
      
    }
  }

  /*!\brief Test the class in debug mode
   */

  static function test_me() {
    echo JS_PROTOTYPE_JS;
    echo JS_BUD_SCRIPT;
    $cn=new Database(dossier::id());
    $sql="select bh_id||','||po_id,bh_name||' -- '||po_name ".
      " from bud_hypothese join poste_analytique using (pa_id)";
    $w=new ISelect();
    echo '<form>';
	echo HtmlInput::hidden('test_select',$_REQUEST['test_select']);
    echo dossier::hidden();
    $w->selected=(isset($_REQUEST['bh_po_id']))?$_REQUEST['bh_po_id']:"";
    echo $w->input('bh_po_id',$cn->make_array($sql));
    echo HtmlInput::submit('search','Recherche');

    echo '</form>';
    if ( isset($_REQUEST['search'])) {

      list($bh_id,$po_id)=split(',',$_REQUEST['bh_po_id']);
      $obj=new Bud_Data($cn,$bh_id,$po_id);
      $r=$obj->load();
      print_r($obj);
      echo '<hr>';
      print_r($r);
      print_r($obj->create_empty_row());
      echo $obj->form();
    }
  }
}
