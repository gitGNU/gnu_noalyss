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
 * \brief Manage the hypothese for the budget module
 *  synthese
 */
/*!
 * \brief Manage the hypothese for the budget module
 *  synthese
 */
require_once("class_iselect.php");
require_once ('class_bud_synthese.php');
require_once ('class_acc_account_ledger.php');
require_once ('class_bud_hypo.php');

class Bud_Synthese_Hypo extends Bud_Synthese {
/*   function __construct($p_cn) { */
/*     echo "constructor ".__FILE__; */
/*   } */
  static function make_array($p_cn) {
    $a=make_array($p_cn,'select bh_id, bh_name from bud_hypothese '.
		  ' order by bh_name');
    return $a;
  }

  function form() {
    $wSelect =new ISelect();
    $wSelect->name='bh_id';
    $wSelect->value=Bud_Synthese_Hypo::make_array($this->cn);
    $wSelect->selected=$this->bh_id;
    $wSelect->javascript='onChange=this.form.submit()';

    $r="Choississez l'hypoth&egrave;se :".$wSelect->input();

    $per=make_array($this->cn,"select p_id,to_char(p_start,'MM.YYYY') ".
		    " from parm_periode order by p_start,p_end");

    $wFrom=new ISelect();
    $wFrom->name='from';
    $wFrom->value=$per;
    $wFrom->selected=$this->from;
    $wFrom->selected=$this->from;

    $wto=new ISelect();
    $wto->name='to';
    $wto->selected=$this->to;
    $wto->value=$per;
    $wto->selected=$this->to;

    $r.="Periode de ".$wFrom->input()." &agrave; ".$wto->input();
    $r.=dossier::hidden();
    return $r;
  }
  /*!\brief load the data from the database and return the result an
     array
     \return 
Array
(
    [6510] => Array
        (
            [GROUPE1] => 0
            [GROUPE3] => 67
            [total_row] => 67
            [acc_name] => Dotations
            [acc_amount] => 0
        )

    [6040001] => Array
        (
            [GROUPE1] => 81.7
            [GROUPE3] => 28
            [total_row] => 109.7
            [acc_name] => Electricite
            [acc_amount] => 0
        )

    [6040002] => Array
        (
            [GROUPE1] => 6
            [GROUPE3] => 0
            [total_row] => 6
            [acc_name] => Loyer
            [acc_amount] => 0
        )

)
*/
  function load() {
    $per=sql_filter_per($this->cn,$this->from,$this->to,'p_id','p_id');
    $per_acc=sql_filter_per($this->cn,$this->from,$this->to,'p_id','j_tech_per');
    $sql_poste="select distinct pcm_val from bud_detail where bh_id=".$this->bh_id;
    $aPoste=get_array($this->cn,$sql_poste);
    $hypo=new Bud_Hypo($this->cn,$this->bh_id);
    $local_con=DbConnect(dossier::id());
    if ( $hypo->has_plan()  == 1 ) {
      $sql_prepare=pg_prepare($local_con,"get_group","select sum(bdp_amount) as amount,ga_id,".
			      "bc_price_unit".
			      " from ".
			      " bud_detail join bud_detail_periode using (bd_id) ".
			      " join bud_card using (bc_id) ".
			      " join poste_analytique using (po_id) ".
			      " where $per ".
			      " and pcm_val=$1 and ".
			      " bud_detail.bh_id=$2 ".
			      " group by ga_id,bc_price_unit ".
			      " order by ga_id ");
    } else {
      $sql_prepare=pg_prepare($local_con,"get_group","select sum(bdp_amount) as amount,'Aucun groupe' as ga_id,".
			      "bc_price_unit".
			      " from ".
			      " bud_detail join bud_detail_periode using (bd_id) ".
			      " join bud_card using (bc_id) ".
			      " where $per ".
			      " and pcm_val=$1 and ".
			      " bud_detail.bh_id=$2 ".
			      " group by ga_id,bc_price_unit ".
			      " order by ga_id ");

    }
    $array=array();
    // Now we put 0 if there is nothing for a group
    $aGroup=get_array($this->cn,"select distinct ga_id from bud_detail join poste_analytique ".
		      " using (po_id) where bh_id=".$this->bh_id." order by ga_id ");

     if ( empty ($aPoste)) return array();
    // foreach poste get all the value of the group
    foreach ($aPoste as $rPoste) {
      $pcm_val=$rPoste['pcm_val'];
      $line=array();
      $res=pg_execute("get_group",array($pcm_val,$this->bh_id));
      $row=pg_fetch_all($res);
      if ( empty ($row) ) continue;
      // initialize all groupe to 0
      if ( ! empty($aGroup) ) {
	foreach ($aGroup as $rGroup) {
	  $sGroup=$rGroup['ga_id'];
	  $line[$sGroup]=0;
	}
      }
      $line['total_row']=0;
      foreach ($row as $col ) {
	$groupe=$col['ga_id'];
	$line[$groupe]=$col['amount']*$col['bc_price_unit'];
	$line['total_row']+=$line[$groupe];
      }
      // total CE
      $acc_account=new Acc_Account_Ledger($this->cn,$pcm_val);
      $acc_account->load();
      $line['acc_name']=$acc_account->label;
      $line['acc_amount']=$acc_account->get_solde($per_acc);
      $array[$pcm_val]=$line;
    }
    /*!\bug it is a bug ? When I close a connection created and used
     *   locally, it closed also the main connection so I reopened it
     */
    pg_close($local_con);
    $this->cn=DbConnect(dossier::id());
    return $array;
  }
  /*!\brief compute the summary
   * \param $p_array is an array generated by load()
   * \return array
Array
(
    [65] => Array
        (
            [GROUPE1] => 0
            [total] => 74
            [GROUPE3] => 74
        )

    [60] => Array
        (
            [GROUPE1] => 83.7
            [total] => 161.7
            [GROUPE3] => 78
        )

)
   */
  function summary($p_array) {

    if ( empty ($p_array)) return ;
    // Now we put 0 if there is nothing for a group
    $aGroup=get_array($this->cn,"select distinct ga_id from bud_detail join poste_analytique ".
		      " using (po_id) where bh_id=".$this->bh_id." order by ga_id ");


    $array=array();
    foreach ($p_array as $key=>$row) {
      $pcm_val=substr($key,0,2);
      $sub=array();
      if ( ! empty ($aGroup ) ) {
	foreach ($aGroup as $rGroup ) {
	  $group_id=$rGroup['ga_id'];
	  $sub[$group_id]=(isset($sub[$group_id]))?$sub[$group_id]:0;
	  $sub[$group_id]+=$row[$group_id];
	  //	print_r("$key pcm_val :".$pcm_val."gr.".$group_id." ".$row[$group_id]."sub ".$sub[$group_id]);
	  $sub['total']=(isset($sub['total']))?$sub['total']:0;
	  $sub['total']+=$row[$group_id];
	  //print_r('sub_total = '.$sub['total'].'<br>');
	}
      }
      if (isset( $array[$pcm_val])) {
	$new_array=array();
	foreach ($array[$pcm_val] as $key=>$value) {
	  $new_array[$key]=$value+$sub[$key];
	}
	$array[$pcm_val]=$new_array;
      }
      else 
	$array[$pcm_val]=$sub;
    }
    return $array;
  }
  /*!\brief show the last line with the total of column 
   * \param $p_array returned value from load()
   * \return Array
   */
  function total_column($p_array) {
    $array=array();
    if ( empty ($p_array)) return $array ;
    // Now we put 0 if there is nothing for a group
    $aGroup=get_array($this->cn,"select distinct ga_id from bud_detail join poste_analytique ".
		      " using (po_id) where bh_id=".$this->bh_id." order by ga_id ");



    foreach ($p_array as $col) {
      if ( ! empty ($aGroup ) ) {
	foreach ($aGroup as $rGroup) {
	  $ga_id=$rGroup['ga_id'];
	  $array[$ga_id]=(isset($array[$ga_id]))?$array[$ga_id]:0;
	  $array[$ga_id]+=$col[$ga_id];
	}
      }
    }

    // total for total_row and acc_amount
    $tot_row=0;
    $acc_amount=0;
    foreach($p_array as $k=>$v) {
      $tot_row+=$v['total_row'];
      $acc_amount+=$v['acc_amount'];
    }
    $array['total_row']=$tot_row;
    $array['acc_amount']=$acc_amount;
    return $array;
  }
  function display_html($p_array){
    if ( empty ($p_array)) return;
    $heading="";
    $r="";
    $aGroup=get_array($this->cn,"select distinct ga_id from bud_detail join poste_analytique ".
		      " using (po_id) where bh_id=".$this->bh_id." order by ga_id ");
    $heading.='<tr>';
    $heading.='<th>CE  </th>';
    if ( ! empty ($aGroup ) ) {
    foreach ($aGroup as $rGroup ) 
      $heading.='<th>'.$rGroup['ga_id'].'</td>';
    }
    $heading.='<th>Total Ligne</th><th>Result. CE</th>';
    $heading.='</tr>';
    $r.='<table>';
    $r.=$heading;
    foreach ( $p_array as $key=>$v) {
      $r.='<tr>';
      $r.='<td>'.$key.' - '.$v['acc_name'].'</td>';
      if ( ! empty ($aGroup ) ) {
	foreach ($aGroup as $rGroup ) {
	  $gr=$rGroup['ga_id'];
	  $r.=sprintf('<td align="right">% 10.2f</td>',$v[$gr]);
	}
      }
      $r.=sprintf('<td align="right">% 10.2f</td>',$v['total_row']);
      $r.=sprintf('<td align="right">% 10.2f</td>',$v['acc_amount']);
      $r.='</tr>';
    }
    $r.="<tr>";
    // Show total by col
    $array=$this->total_column($p_array);
    $r.="<td> Total </td>";
    if ( ! empty ($aGroup ) ) {
      foreach ($aGroup as $rGroup ) {
	$gr=$rGroup['ga_id'];
	$r.=sprintf('<td align="right">% 10.2f</td>',$array[$gr]);
      }
    }
    $r.=sprintf('<td align="right">% 10.2f</td>',$array['total_row']);
    $r.=sprintf('<td align="right">% 10.2f</td>',$array['acc_amount']);

    $r.='</tr>';

    $r.='</table>';
    return $r;
  }
  function display_csv($p_array) {
    if ( empty ($p_array)) return;
    $heading="";
    $r="";
    $aGroup=get_array($this->cn,"select distinct ga_id from bud_detail join poste_analytique ".
		      " using (po_id) where bh_id=".$this->bh_id." order by ga_id ");
    $heading.='"CE",';
    foreach ($aGroup as $rGroup ) 
      $heading.='"'.$rGroup['ga_id'].'",';
    $heading.='"Total Ligne","Result. CE",';
    $heading.="\r\n";

    $r.=$heading;
    foreach ( $p_array as $key=>$v) {
      $r.='"'.$key.' - '.$v['acc_name'].'",';
      foreach ($aGroup as $rGroup ) {
	$gr=$rGroup['ga_id'];
	$r.=sprintf('% 10.2f,',$v[$gr]);
      }
      $r.=sprintf('% 10.2f,',$v['total_row']);
      $r.=sprintf('% 10.2f',$v['acc_amount']);
      $r.="\r\n";
    }
    // Show total by col
    $array=$this->total_column($p_array);
    $r.='" Total",';
    foreach ($aGroup as $rGroup ) {
      $gr=$rGroup['ga_id'];
      $r.=sprintf('% 10.2f,',$array[$gr]);
    }
    $r.=sprintf('% 10.2f,',$array['total_row']);
    $r.=sprintf('% 10.2f',$array['acc_amount']);
    $r.="\r\n";

    return $r;

    
  }
  function hidden() {
    $r="";
    foreach (array('bh_id','from','to') as $e)
      $r.=HtmlInput::hidden($e,$this->$e);
    return $r;
  }
  /*!\brief the same as summary but show it in html */
  function summary_html($p_array) {
    if ( empty ($p_array))return '';    
    $summary_array=$this->summary($p_array);
    arsort($summary_array);
    $aGroup=get_array($this->cn,"select distinct ga_id from bud_detail join poste_analytique ".
		      " using (po_id) where bh_id=".$this->bh_id." order by ga_id ");
    $per_acc=sql_filter_per($this->cn,$this->from,$this->to,'p_id','j_tech_per');

    $heading='<tr>';
    $heading.='<th>CE  </th>';
    if ( ! empty ($aGroup ) ) {
      foreach ($aGroup as $rGroup ) 
	$heading.='<th>'.$rGroup['ga_id'].'</td>';
    }
    $heading.='<th>Total Ligne</th><th>Result. CE</th>';
    $heading.='</tr>';
    $r='<table>';
    $r.=$heading;



    foreach ($summary_array as $key=>$aRow) {
      $amount_row=0;
      $r.="<tr><td>";

      $acc_account=new Acc_Account_Ledger($this->cn,$key);
      $acc_account->load();
      $r.=$key." - ".$acc_account->label.'</td>';

      if ( ! empty ($aGroup ) ) {
	foreach ($aGroup as $gr) {
	  $idx=$gr['ga_id'];
	  $r.='<td>'.$aRow[$idx].'</td>';
	  $amount_row+=$aRow[$idx];
	}
      }
      $r.="<td>".$amount_row.'</td>';
      $acc_account->id=$acc_account->id."%";
      $r.="<td>".$acc_account->get_solde($per_acc).'</td>';
      $r.="</tr>";
    }
    $r.="</table>";
    return $r;
  }
  static function test_me() {
    $cn=DbConnect(dossier::id());
    $obj=new Bud_Synthese_Hypo($cn);
    echo '<form method="GET">';
	echo HtmlInput::hidden('test_select',$_REQUEST['test_select']);
    echo $obj->form();
    echo HtmlInput::submit('recherche','Recherche');
    echo '</form>';
    print_r($_GET);
    if ( isset ($_GET['recherche'])) {
      $obj->from_array($_GET);
      $res=$obj->load();
      print_r($res);
      $summary=$obj->summary($res);
      print_r($summary);
      print_r($obj->total_column($res));
    }
  }
}
