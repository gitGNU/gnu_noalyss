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
\brief Manage the hypothese for the budget module
 * 
 */
/*! 
\brief Manage the hypothese for the budget module
*/
require_once("class_iselect.php");
require_once ('class_bud_synthese.php');
require_once ('class_anc_account.php');
require_once ('class_acc_account_ledger.php');
require_once ("class.ezpdf.php");
require_once ('header_print.php');


class Bud_Synthese_Acc extends Bud_Synthese {
  var $po_from;
  var $po_to;
  function from_array($p_array) {
    parent::from_array($p_array);
    foreach (array('acc_from','acc_to') as $r) {
      $this->$r=(isset($p_array[$r]))?$p_array[$r]:"";
    }
  }

  function select_hypo() {
    $hypo=$this->cn->make_array('select bh_id, bh_name from bud_hypothese ');

    $wSelect =new ISelect();
    $wSelect->name='bh_id';
    $wSelect->value=$hypo;
    $wSelect->javascript='onChange=this.form.submit()';
    $wSelect->selected=(isset($this->bh_id))?$this->bh_id:'';
    $r="Choississez l'hypoth&egrave;se :".$wSelect->input();
    $r.=dossier::hidden();
    return $r;
  }
  function form() {
    /*    2nd Step
    */
    if  ( $this->bh_id == 0 ) 
      throw new Exception ("bh_id n'est pas selectionne");
    $hypo=new Bud_Hypo($this->cn);
    $hypo->bh_id=$this->bh_id;
    $hypo->load();
    $acc_value=$this->cn->make_array('select  distinct pcm_val::text,pcm_val from bud_detail where bh_id='.$this->bh_id." order by pcm_val::text ");
    $wAcc_from=new ISelect();
    $wAcc_from->name="acc_from";
    $wAcc_from->value=$acc_value;
    $wAcc_from->selected=$this->acc_from;

    $wAcc_to=new ISelect();
    $wAcc_to->name="acc_to";
    $wAcc_to->value=$acc_value;
    $wAcc_to->selected=$this->acc_to;

    $per=$this->cn->make_array("select p_id,to_char(p_start,'MM.YYYY') ".
		    " from parm_periode order by p_start,p_end");

    $wFrom=new ISelect();
    $wFrom->name='from';
    $wFrom->value=$per;
    $wFrom->selected=$this->from;

    $wto=new ISelect();
    $wto->name='to';
    $wto->value=$per;
    $wto->selected=$this->to;

    $r="";
    $r.="Periode de ".$wFrom->input()." &agrave; ".$wto->input();
    $r.="Poste comptable de ".$wAcc_from->input()." &agrave; ".$wAcc_to->input();
    $r.=dossier::hidden();
    return $r;
  }
  /*!\brief return all the data (raw format) in a array
   * \return  Array key = Account
    [6040002] => Array
        (
            [acc_name] => Loyer
            [acc_amount] => 0
            [detail] => Array
                (
                    [0] => Array
                        (
                            [bc_id] => 4
                            [bc_code] => FICHE_1
                            [unit] => 6
                            [amount] => 6
                            [amount_unit] => Array
                                (
                                    [79] => 1.0000
                                    [80] => 2.0000
                                    [81] => 3.0000
                                    [82] => 0.0000
                                )

                        )

                    [1] => Array
                        (
                            [bc_id] => 5
                            [bc_code] => FICHE_2
                            [unit] => 10
                            [amount] => 10
                            [amount_unit] => Array
                                (
                                    [79] => 1.0000
                                    [80] => 2.0000
                                    [81] => 3.0000
                                    [82] => 4.0000
                                )

                        )

                )

        )

   *
   */
  function load() {
    $per=sql_filter_per($this->cn,$this->from,$this->to,'p_id','p_id');
    $per_acc=sql_filter_per($this->cn,$this->from,$this->to,'p_id','j_tech_per');

    // get all the bud_card.bc_id
    $sql="select distinct pcm_val ".
      " from bud_card join bud_detail using (bc_id) ".
      " where pcm_val::text >= $1 and ".
      "pcm_val::text <= $2 and bud_card.bh_id=$3";

    $res=$this->cn->exec_sql($sql,array($this->acc_from,$this->acc_to,$this->bh_id));
    $aBudCard=pg_fetch_all($res);
    echo_debug(__FILE__.':'.__LINE__.'- load','aBudCard',$aBudCard);
    $array=array();
    $cn=new Database(dossier::id());
    pg_prepare($cn,"sql_detail","select distinct bc_id,bc_code,bc_description,bc_price_unit ".
	       " from bud_detail join bud_card using (bc_id)".
	       " where pcm_val=$1");
    pg_prepare($cn,"sql_detail_periode","select sum(bdp_amount) as amount,".
	       "p_id from bud_card join bud_detail using (bc_id)".
	       " join bud_detail_periode using (bd_id) ".
	       " join parm_periode using (p_id) ".
	       " where bc_id=$1 and $per and pcm_val=$2 group by p_id,p_start order  by p_start");

    if ( empty ($aBudCard)) 
      return;
    // foreach card get the detail per pcm_val and periode
    foreach ($aBudCard as $rBudCard) {
      $line=array();
      echo_debug(__FILE__.':'.__LINE__.'- load ','bud_card',$rBudCard);
      $acc_account=new Acc_Account_Ledger($this->cn,$rBudCard['pcm_val']);
      $acc_account->load();
      $line['acc_name']=$acc_account->label;
      $line['acc_amount']=$acc_account->get_solde($per_acc);

      $res=pg_execute("sql_detail",array($rBudCard['pcm_val']));
      $row=pg_fetch_all($res);
      $idx=0;
      foreach ($row as $col) {
	$sub=array();
	echo_debug(__FILE__.':'.__LINE__.'- ','load : pcm_val',$rBudCard['pcm_val']);
	$bc_id=$col['bc_id'];
	$sub['bc_id']=$bc_id;
	$sub['bc_code']=$col['bc_code'];
	$sub['bc_description']=$col['bc_description'];
	$sub['bc_price_unit']=$col['bc_price_unit'];
	$periode=array();
	$sub['unit']=0;

	$res2=pg_execute("sql_detail_periode",array($bc_id,$rBudCard['pcm_val']));
	$col_per=pg_fetch_all($res2);
	if ( empty ($col_per) ) continue;
	// fill the periode array
	foreach ($col_per as $cPer) {
	  $p_id=$cPer['p_id'];
	  $periode[$p_id]=$cPer['amount'];
	  $sub['unit']+=$cPer['amount'];
	}
	$sub['amount']=$sub['unit']*$col['bc_price_unit'];
	$sub['amount_unit']=$periode;

	$line['detail'][$idx]=$sub;
	$idx++;
      }
      $array[$rBudCard['pcm_val']]=$line;
    }
    echo_debug(__FILE__.':'.__LINE__.'- load ','return ',$array);
    return $array;
  }

  function display_csv ($p_array){
    if (empty($p_array)) return;
    $r="";
    foreach ($p_array as $key=>$value) {
      echo_debug(__FILE__.':'.__LINE__.'- display_csv','$key',$key);
      echo_debug(__FILE__.':'.__LINE__.'- display_csv','$value',$value);
      $r.=sprintf('"%s","%s"',$key,$value['acc_name']);
      $r.=sprintf("\r\n");

      foreach ($value['detail'] as $v) {
	$r.=sprintf('"%s","%s","%s",'
		    ,$v['bc_code'],
		    $v['bc_description'],
		    $v['bc_price_unit']);
	echo_debug(__FILE__.':'.__LINE__.'- display_csv','$value[amount_unit]',$v['amount_unit']);
	foreach ($v['amount_unit'] as $a=>$v2 ){
	  $r.=sprintf('%10.4f,',$v2);
	}
	$r.=sprintf('%10.4f,%10.4f',$v['unit'],$v['amount']);
	$r.=sprintf("\r\n");
      }
    }
    return $r;
  }
  function display_html($p_array) {
    $r="";
    if (empty($p_array)) return;
    $persql=sql_filter_per($this->cn,$this->from,$this->to,'p_id','p_id');
    $per=$this->cn->get_array("select to_char(p_start,'MM.YYYY') as d".
		   " from parm_periode ".
		   " where $persql");

    $heading="<tr><th> CE </th>";
    foreach( $per as $c) { $heading.='<th>'.$c['d'].'</th>';}
    $heading.="<th>Total Unite</th>";
    $heading.="<th>Total Cout</th></tr>";

    foreach ($p_array as $key=>$value) {
      echo_debug(__FILE__.':'.__LINE__.'- display_html','$key',$key);
      echo_debug(__FILE__.':'.__LINE__.'- display_html','$value',$value);
	$r.=$key.'-'.$value['acc_name'];
	
	$r.="<table>";
	$r.=$heading;	
	foreach ($value['detail'] as $v) {
	  $r.='<tr>';
	  $r.='<td>'.$v['bc_code'].' '.$v['bc_description'].'</td>';
	  foreach ($v['amount_unit'] as $a=>$v2) {
	    $r.=sprintf('<td align="right">%10.2f</td>',$v2);
	  }
	  $r.=sprintf('<td align="right">% 10.2f</td>',$v['unit']);
	  $r.=sprintf('<td align="right">% 10.2f</td>',$v['amount']);
	  $r.='</tr>';
	}
      $r.="</table>";
    }    

    return $r;
  }
  function hidden() {
    $r="";
    foreach (array('bh_id','acc_from','acc_to','from','to') as $e)
      $r.=HtmlInput::hidden($e,$this->$e);
    return $r;
  }

  static function test_me() {

    $cn=new Database(dossier::id());
    $obj=new Bud_Synthese_Acc($cn);
    echo '<form method="GET">';
    echo $obj->select_hypo();
    echo HtmlInput::submit('recherche','recherche');
    echo '</form>';
    if ( isset($_GET['recherche'])) {
      $obj->from_array($_GET);
      echo '<form method="GET">';
	  HtmlInput::hidden('test_select',$_REQUEST['test_select']);
      echo $obj->form();
      echo HtmlInput::hidden('bh_id',$obj->bh_id);
      echo HtmlInput::submit('recherche2','recherche');
      echo '</form>';
    }
    if ( isset($_GET['recherche2'])){
      print_r($_GET);
      $obj->from_array($_GET);

      echo 'ICI';
     $res=$obj->load();
     print_r($res);
     echo '<hr>';
     echo $obj->display_csv($res);
     echo '<hr>';
     echo $obj->display_html($res);
    }
  }



}
