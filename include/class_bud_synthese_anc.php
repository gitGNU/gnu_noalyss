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
 * 
 */
/*! 
\brief Manage the hypothese for the budget module
*/
require_once ('class_bud_synthese.php');
require_once ('class_anc_account.php');

class Bud_Synthese_Anc extends Bud_Synthese {
  var $po_from;
  var $po_to;
  function from_array($p_array) {
    parent::from_array($p_array);
    foreach (array('po_from','po_to') as $r) {
      $this->$r=(isset($p_array[$r]))?$p_array[$r]:"";
    }
    // swap po_from and po_to if po_from is > to
    if  ( $this->from > $this->po_to ){ 
      $swap=$this->po_to;
      $this->po_to=$this->from;
      $this->from=$swap;
    }
  }

  function select_hypo() {
    $hypo=make_array($this->cn,'select bh_id, bh_name from bud_hypothese '.
		  ' where pa_id is not null order by bh_name');
    $wSelect = new widget('select');
    $wSelect->name='bh_id';
    $wSelect->value=$hypo;

    $r="Hypoth&egrave;se :".$wSelect->IOValue();
    $r.=dossier::hidden();
    return $r;
  }
  function form() {
    /*    2nd Step
    */
    if  ( $this->bh_id == 0 ) 
      throw new Exception ("bh_id n'est pas sélectionné");
    $hypo=new Bud_Hypo($this->cn);
    $hypo->bh_id=$this->bh_id;
    $hypo->load();
    $po_value=Anc_Account::make_array_name($this->cn,$hypo->pa_id);
    $wPo_from=new widget("select");
    $wPo_from->name="po_from";
    $wPo_from->value=$po_value;

    $wPo_to=new widget("select");
    $wPo_to->name="po_to";
    $wPo_to->value=$po_value;

    $per=make_array($this->cn,"select p_id,to_char(p_start,'MM.YYYY') ".
		    " from parm_periode order by p_start,p_end");

    $wFrom=new widget('select');
    $wFrom->name='from';
    $wFrom->value=$per;

    $wto=new widget('select');
    $wto->name='to';
    $wto->value=$per;
    $r="";
    $r.="Periode de ".$wFrom->IOValue()." &agrave; ".$wto->IOValue();
    $r.="Poste analytique de ".$wPo_from->IOValue()." &agrave; ".$wPo_to->IOValue();
    $r.=dossier::hidden();
    return $r;
  }
  /*!\brief return all the data (raw format) in a array
\return  Array structure (
    [0] => Array
        (
            [bc_id] => 4
            [pcm_val] => 6040002
            [amount] => Array
                (
                    [79] => 1.0000
                    [91] => 0.0000
                    [90] => 12.0000
                    [81] => 3.0000
                    [89] => 11.0000
                    [80] => 2.0000
                    [88] => 10.0000
                    [87] => 9.0000
                    [86] => 0.0000
                    [85] => 0.0000
                    [84] => 0.0000
                    [83] => 0.0000
                    [82] => 0.0000
                )

        )

    [1] => Array
        (
            [bc_id] => 7
            [pcm_val] => 6510
            [amount] => Array
                (
                    [79] => 2.0000
                    [91] => 0.0000
                    [90] => 0.0000
                    [81] => 4.0000
                    [89] => 0.0000
                    [80] => 43.0000
                    [88] => 0.0000
                    [87] => 0.0000
                    [86] => 7.0000
                    [85] => 7.0000
                    [84] => 6.0000
                    [83] => 0.0000
                    [82] => 5.0000
                )

        )

) */
  function load() {
    $per=sql_filter_per($this->cn,$this->from,$this->to,'p_id','p_id');

    // get all the bud_card.bc_id
    $sql="select distinct bc_id from bud_card join bud_detail using (bc_id) ".
      "join poste_analytique using(po_id) where po_name >= $1 and ".
      "po_name <=$2 and bud_card.bh_id=$3";
    $res=ExecSqlParam($this->cn,$sql,array($this->po_from,$this->po_to,$this->bh_id));
    $aBudCard=pg_fetch_all($res);
    $array=array();
    $cn=DbConnect(dossier::id());
    pg_prepare($cn,"sql_detail","select distinct pcm_val from bud_detail ".
	       " where bc_id=$1");
    pg_prepare($cn,"sql_detail_periode","select sum(bdp_amount) as amount,".
	       "p_id from bud_card join bud_detail using (bc_id)".
	       " join bud_detail_periode using (bd_id) ".
	       " join parm_periode using (p_id) ".
	       " where bc_id=$1 and $per and pcm_val=$2 group by p_id,p_start order  by p_start");

    // foreach card get the detail per pcm_val and periode
    foreach ($aBudCard as $rBudCard) {
      $line=array();
      $line['bc_id']=$rBudCard['bc_id'];
      $res=pg_execute("sql_detail",array($line['bc_id']));
      $row=pg_fetch_all($res);
      foreach ($row as $col) {
	$pcm_val=$col['pcm_val'];
	$line['pcm_val']=$pcm_val;
	$periode=array();
	$res2=pg_execute("sql_detail_periode",array($rBudCard['bc_id'],$pcm_val));
	$col_per=pg_fetch_all($res2);

	foreach ($col_per as $cPer) {
	  $p_id=$cPer['p_id'];
	  $periode[$p_id]=$cPer['amount'];
	}
	$line['amount']=$periode;
      }
      $array[]=$line;
    }
    pg_close($cn);
    return $array;
  }
  static function test_me() {

    $cn=DbConnect(dossier::id());
    $obj=new Bud_Synthese_Anc($cn);
    echo '<form method="GET">';
    echo $obj->select_hypo();
    echo widget::submit_button('recherche','recherche');
    echo '</form>';
    if ( isset($_GET['recherche'])) {
      $obj->from_array($_GET);
      echo '<form method="GET">';
      echo $obj->form();
      echo widget::hidden('bh_id',$obj->bh_id);
      echo widget::submit_button('recherche2','recherche');
      echo '</form>';
    }
    if ( isset($_GET['recherche2'])){
      print_r($_GET);
      $obj->from_array($_GET);

      echo 'ICI';
      print_r($obj->load());
    }
  }



}
