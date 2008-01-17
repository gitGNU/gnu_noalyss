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
require_once ('class_acc_account_ledger.php');


class Bud_Synthese_Anc extends Bud_Synthese {
  var $po_from;
  var $po_to;
  function from_array($p_array) {
    parent::from_array($p_array);
    foreach (array('po_from','po_to') as $r) {
      $this->$r=(isset($p_array[$r]))?$p_array[$r]:"";
    }
  }

  function select_hypo() {
    $hypo=make_array($this->cn,'select bh_id, bh_name from bud_hypothese '.
		  ' where pa_id is not null order by bh_name');
    $wSelect = new widget('select');
    $wSelect->name='bh_id';
    $wSelect->value=$hypo;
    $wSelect->javascript='onChange=this.form.submit()';
    $wSelect->selected=(isset($this->bh_id))?$this->bh_id:'';
    $r="Choississez l'hypoth&egrave;se :".$wSelect->IOValue();
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
    $wPo_from->selected=$this->po_from;
    $wPo_to=new widget("select");
    $wPo_to->name="po_to";
    $wPo_to->value=$po_value;
    $wPo_to->selected=$this->po_to;

    $per=make_array($this->cn,"select p_id,to_char(p_start,'MM.YYYY') ".
		    " from parm_periode order by p_start,p_end");

    $wFrom=new widget('select');
    $wFrom->name='from';
    $wFrom->value=$per;
    $wFrom->selected=$this->from;

    $wto=new widget('select');
    $wto->name='to';
    $wto->value=$per;
    $wto->selected=$this->to;

    $r="";
    $r.="Periode de ".$wFrom->IOValue()." &agrave; ".$wto->IOValue();
    $r.="Poste analytique de ".$wPo_from->IOValue()." &agrave; ".$wPo_to->IOValue();
    $r.=dossier::hidden();
    return $r;
  }
  /*!\brief return all the data (raw format) in a array
    \return  Array structure

    [0] => Array
        (
            [bc_id] => 4
            [price_unit] => 1.0000
            [bc_code] => FICHE_1
            [bc_description] => 
            [detail] => Array
                (
                    [0] => Array
                        (
                            [pcm_val] => 6040001
                            [unit] => 27
                            [amount] => 27
                            [amount_unit] => Array
                                (
                                    [79] => 13.0000
                                    [80] => 14.0000
                                )

                            [acc_name] => Electricité
                            [acc_amount] => 0
                        )

                    [1] => Array
                        (
                            [pcm_val] => 6040002
                            [unit] => 317
                            [amount] => 317
                            [amount_unit] => Array
                                (
                                    [79] => 108.0000
                                    [80] => 209.0000
                                )

                            [acc_name] => Loyer
                            [acc_amount] => 0
                        )

                )

        )

*/
  function load() {
    $per=sql_filter_per($this->cn,$this->from,$this->to,'p_id','p_id');
    $per_acc=sql_filter_per($this->cn,$this->from,$this->to,'p_id','j_tech_per');

    // get all the bud_card.bc_id
    $sql="select distinct bc_id,bc_price_unit,bc_code,bc_description,bc_unit ".
    " from bud_card join bud_detail using (bc_id) ".
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
      $line['price_unit']=$rBudCard['bc_price_unit'];
      $line['bc_code']=$rBudCard['bc_code'];
      $line['bc_description']=$rBudCard['bc_description'];
      $line['bc_unit']=$rBudCard['bc_unit'];

      $res=pg_execute("sql_detail",array($line['bc_id']));
      $row=pg_fetch_all($res);
      $idx=0;
      foreach ($row as $col) {
	$sub=array();
	$pcm_val=$col['pcm_val'];
	$sub['pcm_val']=$pcm_val;
	$periode=array();
	$res2=pg_execute("sql_detail_periode",array($rBudCard['bc_id'],$pcm_val));
	$col_per=pg_fetch_all($res2);
	$sub['unit']=0;
	foreach ($col_per as $cPer) {
	  $p_id=$cPer['p_id'];
	  $periode[$p_id]=$cPer['amount'];
	  $sub['unit']+=$cPer['amount'];
	}
	$sub['amount']=$sub['unit']*$line['price_unit'];
	$sub['amount_unit']=$periode;
	$acc_account=new Acc_Account_Ledger($this->cn,$pcm_val);
	$acc_account->load();
	$sub['acc_name']=$acc_account->label;
	$sub['acc_amount']=$acc_account->get_solde($per_acc);
	$line['detail'][$idx]=$sub;
	$idx++;
      }
      $array[]=$line;
    }

    return $array;
  }

  function display_csv ($p_array){
    if (empty($p_array)) return;
    $r="";
    foreach ($p_array as $key=>$value) {
      echo_debug(__FILE__.':'.__LINE__.'- display_csv','$key',$key);
      echo_debug(__FILE__.':'.__LINE__.'- display_csv','$value',$value);
      $r.=sprintf('"%s","%s"',$value['bc_code'],$value['bc_description']);
      $r.=sprintf("\r\n");

      foreach ($value['detail'] as $v) {
	$r.=sprintf('"%s","%s",'
		    ,$v['pcm_val'],
		    $v['acc_name']);

	foreach ($v['amount_unit'] as $a=>$v2 ){
	  $r.=sprintf('%10.4f,',$v2);
	}
	$r.=sprintf('%10.4f,%10.4f,%10.4f',
		    $v['unit'],$v['amount'],$v['acc_amount']);
	$r.=sprintf("\r\n");
      }
    }
    return $r;
  }
  function display_html($p_array) {
    $r="";
    if (empty($p_array)) return;
    $per=get_array($this->cn,"select to_char(p_start,'MM.YYYY') as d".
		   " from parm_periode ".
		   " where p_id between ".$this->from.' and '.
		   $this->to." order by p_start" );
    $heading="<tr><th> CE </th>";
    foreach( $per as $c) { $heading.='<th>'.$c['d'].'</th>';}
    $heading.="<th>Total Unite</th>";
    $heading.="<th>Total Prix</th>";
    $heading.="<th>Total CE</th></tr>";

    foreach ($p_array as $key=>$value) {
      echo_debug(__FILE__.':'.__LINE__.'- display_html','$key',$key);
      echo_debug(__FILE__.':'.__LINE__.'- display_html','$value',$value);
      $r.='<span style="margin-left:5px;display:block">'.$value['bc_code'].'-'.$value['bc_description'].
	' PU: '.$value['price_unit'].$value['bc_unit'].'</span>';
      $r.='<span style="display:block;margin-left:100px">';
	$r.='<table style="border:solid 1px blue;">';
	$r.=$heading;
	foreach ($value['detail'] as $v) {
	  $r.='<tr>';
	  $r.='<td>'.$v['pcm_val'].' '.$v['acc_name'].'</td>';
	  foreach ($v['amount_unit'] as $a=>$v2) {
	    $r.=sprintf('<td align="right">% 10.2f</td>',$v2);
	  }
	  $r.=sprintf('<td align="right">% 10.2f</td>',$v['unit']);
	  $r.=sprintf('<td align="right">% 10.2f</td>',$v['amount']);
	  $r.=sprintf('<td align="right">% 10.2f</td>',$v['acc_amount']);
	  $r.='</tr>';
	}
      $r.="</table>";
      $r.='</span>';
    }    

    return $r;
  }
  function hidden() {
    $r="";
    foreach (array('bh_id','po_from','po_to','from','to') as $e)
      $r.=widget::hidden($e,$this->$e);
    return $r;
  }
  static function test_me() {

    $cn=DbConnect(dossier::id());
    $obj=new Bud_Synthese_Anc($cn);
    echo '<form method="GET">';
    echo $obj->select_hypo();
    echo widget::submit('recherche','recherche');
    echo '</form>';
    if ( isset($_GET['recherche'])) {
      $obj->from_array($_GET);
      echo '<form method="GET">';
      echo $obj->form();
      echo widget::hidden('bh_id',$obj->bh_id);
      echo widget::submit('recherche2','recherche');
      echo '</form>';
    }
    if ( isset($_GET['recherche2'])){
      print_r($_GET);
      $obj->from_array($_GET);

      echo 'ICI';
      $res=$obj->load();
      echo '<hr>';
      echo $obj->display_csv($res);
      echo '<hr>';

      echo $obj->display_html($res);
    }
  }



}
