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
require_once ('class_widget.php');
require_once ('postgres.php');
require_once ('constant.php');
require_once ('debug.php');
require_once ('ac_common.php');


class Bud_Data {
  var $bh_id;
  var $bc_id;
  var $bd_id;
  var $pcm_val;
  var $bdp_id;
  var $amount;
  var $cn;
  function __construct($p_cn,$p_bh_id,$p_po_id) {
    echo "constructor";
    $this->cn=$p_cn;
    $this->bh_id=$p_bh_id;
    $this->po_id=$p_po_id;
    /*

    pg_prepare($this->cn,"sql_periode",$sql_periode);
    */
  }
  function load() {
    $sql=" select bd_id,bc_id,bc_code,bd.bh_id,bd.bh_name,".
      " tmp_pcmn.pcm_val,pcm_lib,bud_detail_periode.bdp_id ".
      " from bud_detail join bud_detail_periode  using (bd_id)".
      " join bud_hypothese as bd using (bh_id) ".
      "  join bud_card using (bc_id) ".
      " join tmp_pcmn using (pcm_val) ".
      " where bd.bh_id=".$this->bh_id.
      " and po_id=".$this->po_id;


    $res1=ExecSql($this->cn,$sql);
    $array=pg_fetch_all($res1);

    
    $sql_periode="select coalesce(bdp_amount,0) as bdp_amount,a.p_id as p_id ".
      " from bud_detail_periode left join parm_periode as a using (p_id)  ".
      " where bdp_id=$1 order by p_start,p_end ";
       
    print_r($sql_periode);


    $ret=array();


    if ( ! empty($array)){
      foreach ($array as $row) {
	$obj=new Bud_Data($this->cn,$this->bh_id,$this->po_id);
	$obj->load_from_array($row);
	
	$res2=ExecSqlParam($this->db,"sql_periode",array($row['bdp_id']));
	$per=pg_fetch_all($r);
	
	foreach ($per as $row2) {
	  $p_id=$row['p_id'];
	  $obj->amount[$p_id]=$row['bdp_amount'];
	}
      }
      $ret=clone $obj;
    }
    return $ret;
  }

  function create_empty_row() {
    $ret=new Bud_Data($this->cn,$this->bh_id,$this->po_id);
    $ret->bd_id=0;
    $ret->bdp_id=0;
    // populate the  periode with 0
    $res_empty=get_array($this->cn,
			 "select 0,p_id from parm_periode order by p_start,p_end");
    foreach ( $res_empty as $r) {
      $p_id=$r['p_id'];
      $ret->amount[$p_id]=0;
    }
    return $ret;
  }

  private function load_from_array($p_array) {
    foreach (array('bd_id','bh_id','bc_id','pcm_val','bdp_id','pcm_lib','bh_name','bc_code') as $key) {
      $this->{$key}=$p_array[$key];
    }
  }
  function form() {
    $r="";
    $array=$this->load();
    $a=0;
    if ( ! empty ($array) ) {
      foreach ($array as $row) {
	$a++;
	$style=($a%2==0)?"even":"odd";
	$r.=$this->create_row($style);
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

  private function create_row($p_style="odd") {
    static $p_number=0;
    $p_number++;
    $tot=0;

    $wAmount=new widget('text');
    $wAmount->size=8;
    $wAmount->extra="disabled";

    $wAccount=new widget('js_bud_search_poste');
    $wAccount->table=0;
    $wAccount->disabled=true;

    $wBudCard=new widget('select');
    $wBudCard->value=$this->load_bud_card();
    $wBudCard->selected=$this->bc_id;
    $wBudCard->disabled=true;

    foreach ($this->amount as $p_id=>$amount)
      $tot+=$amount;

    $r='<form id="form_'.$p_number.'" disabled>';
    $r.=dossier::hidden();
    $r.=widget::hidden('po_id',$this->po_id);
    $r.=widget::hidden('bh_id',$this->bh_id);
    $r.=widget::hidden('bd_id',$this->bd_id);
    $r.=widget::hidden('bdp_id',$this->bdp_id);
    $r.=widget::hidden('form_id',$p_number);
    

    $r.="Compte d'exploitation ".$wAccount->IOValue('account_'.$p_number);
    
    $r.="Fiche Budget ".$wBudCard->IOValue('bc_id'.$p_number);
    $r.='Total : <span id="form_'.$p_number.'"> '.$tot.' </span>';
    $r.='<table WIDTH="100%">';
    $r.=$this->header_table();
    $r.="<tr> ";
    foreach ($this->amount as $p_id=>$amount){
      $tot+=$amount;
      $r.='<td >'.$wAmount->IOValue('amount_'.$p_id,sprintf("%08.2f",$amount))."</td>";
    }
    $r.="</tr>";
    $r.="</table>";
    $r.=widget::hidden('PHPSESSID',$_REQUEST['PHPSESSID']);

    $r.="</form>";
    $button_change=new widget('button','Change');
    $button_change->javascript='bud_form_enable('.$p_number.')';
    $r.=$button_change->IOValue('button_change'.$p_number);

    $button_save=new widget('button','Sauve');
    $button_save->javascript='bud_form_save('.$p_number.')';
    $button_save->extra='style="display:none"';
    $r.=$button_save->IOValue('button_save'.$p_number);

    $button_delete=new widget('button','Efface');
    $button_delete->javascript='bud_form_delete('.$p_number.')';
    $button_delete->extra='style="display:none"';
    $r.=$button_delete->IOValue('button_delete'.$p_number);
    $r.='<span id="Result'.$p_number.'"></span>';
    $r.="<hr>";
    return $r;
  }
  private function load_bud_card() {
    if ( !isset ($this->array_bud_card) )
      $this->array_bud_card=make_array($this->cn,
				       'select bc_id,bc_code from  bud_card '.
				       'where bh_id='.$this->bh_id);
    return $this->array_bud_card;
  }

  private function header_table() {
    if ( ! isset ($this->header ) ){
      $r='<table style="border: 2px outset blue; width: 100%;">';
      $r.="<tr>";
      $periode=get_array($this->cn,"select  to_char(p_start,'MM/YY')as d from parm_periode ");
      foreach ($periode as $row)
	$r.='<th >'.$row['d'].'</th>';

      $r.="</tr>";
      $this->header=$r;
    }
    return $this->header;
  }
  static function test_me() {
    echo JS_PROTOTYPE_JS;
    echo JS_BUD_SCRIPT;
    $cn=DbConnect(dossier::id());
    $sql="select bh_id||','||po_id,bh_name||' -- '||po_name ".
      " from bud_hypothese join poste_analytique using (pa_id)";
    $w=new widget("select");
    echo '<form>';
    echo dossier::hidden();
    $w->selected=(isset($_REQUEST['bh_po_id']))?$_REQUEST['bh_po_id']:"";
    echo $w->IOValue('bh_po_id',make_array($cn,$sql));
    echo widget::submit_button('search','Recherche');

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
