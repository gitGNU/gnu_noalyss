
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
 */
/*!
 * \brief Manage the hypothese for the budget module
 *  synthese
 */
require_once ('class_widget.php');
require_once ('class_bud_synthese.php');
require_once ('class_acc_account.php');
require_once ('class_bud_hypo.php');

class Bud_Synthese_Group extends Bud_Synthese {
  function from_array($p_array) {
    parent::from_array($p_array);
    foreach (array('ga_id') as $r) {
      $this->$r=(isset($p_array[$r]))?$p_array[$r]:"";
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
    $anc_value=make_array($this->cn,"select distinct ga_id, ".
			  " ga_id||'-'||substr(ga_description,10) as ga_description ".
			  "from groupe_analytique ".
			  " join poste_analytique using (ga_id) ".
			  " join bud_detail using (po_id) ".
			  " where bh_id=".$this->bh_id);

    $wGa_id=new widget("select");
    $wGa_id->name="ga_id";
    $wGa_id->value=$anc_value;

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
    $r.="Groupe  ".$wGa_id->IOValue();
    $r.=dossier::hidden();
    return $r;
  }
  /*!\brief get all the value and return and array
   * \return Array, the prefix BD : amount taken from the budget
   * module, and acc from the accountancy
(
    [6510] => Array
        (
            [total_bud] => 74
            [total_acc] => 0
            [BD_79] => 2
            [AC_79] => 0
            [BD_80] => 43
            [AC_80] => 0
            [BD_81] => 4
            [AC_81] => 0
            [BD_82] => 5
            [AC_82] => 0
            [BD_83] => 0
            [AC_83] => 0
            [BD_84] => 6
            [AC_84] => 0
            [BD_85] => 7
            [AC_85] => 0
            [BD_86] => 7
            [AC_86] => 0
            [BD_87] => 0
            [AC_87] => 0
            [BD_88] => 0
            [AC_88] => 0
            [BD_89] => 0
            [AC_89] => 0
            [BD_90] => 0
            [AC_90] => 0
            [BD_91] => 0
            [AC_91] => 0
        )

    [6040001] => Array
        (
            [total_bud] => 78
            [total_acc] => 0
            [79] => 1
            [AC_79] => 0
            [BD_80] => 2
            [AC_80] => 0
            [BD_81] => 3
            [AC_81] => 0
            [BD_82] => 4
            [AC_82] => 0
            [BD_83] => 5
            [AC_83] => 0
            [BD_84] => 6
            [AC_84] => 0
            [BD_85] => 7
            [AC_85] => 0
            [BD_86] => 8
            [AC_86] => 0
            [BD_87] => 9
            [AC_87] => 0
            [BD_88] => 10
            [AC_88] => 0
            [BD_89] => 11
            [AC_89] => 0
            [BD_90] => 12
            [AC_90] => 0
            [BD_91] => 0
            [AC_91] => 0
        )

)
 */
  function load() {
    $per=sql_filter_per($this->cn,$this->from,$this->to,'p_id','p_id');
    $sql="select bc_price_unit,pcm_val,sum(bdp_amount) as amount,p_id ".
      " from bud_detail join bud_detail_periode using (bd_id) ".
      " join poste_analytique using (po_id) ".
      " join bud_card using (bc_id) ".
      " where ".
      " ga_id ='".$this->ga_id."'".
      " and bud_detail.bh_id=".$this->bh_id.
      " and $per ".
      " group by pcm_val,p_id,bc_price_unit order by pcm_val,p_id";
    $res=get_array($this->cn,$sql);
    $pcm_val="";
    $old="XX";
    $sub=array();
    foreach ($res as $row) {
      $pcm_val=$row['pcm_val'];

      // first loop
      if ( $old=="XX") {
	$old=$pcm_val;
      }
      if ( $pcm_val == $old ) {
	$per='BD_'.$row['p_id'];
	$sub[$per]=$row['amount']*$row['bc_price_unit'];
      } else {
	// save the array
	$array[$old]=$sub;
	$old=$pcm_val;
	$per=$row['p_id'];
	// reinitialize array
	$sub=array();
	$sub[$per]=$row['amount']*$row['bc_price_unit'];

      }
    }
    // save the last value
    $array[$old]=$sub;

    // Add amount from Accountancy
    $tmp=$array;
    $acc=new Acc_Account($this->cn,0);
    // $key is the pcm_val and the amount is an array of amount
    // (index = periode)
    foreach ($array as $key=>$amount) {
      $sub=array();
      $sub['total_bud']=0;
      $sub['total_acc']=0;
      foreach ($amount as $periode=>$value){
	$sub[$periode]=$value;
	$sub['total_bud']+=$value;
	$tp=str_replace('BD_','',$periode);
	// ACC
	$idx_acc="AC_".$tp;
	$acc->id=$key;

	$sub[$idx_acc]=$acc->get_solde("j_tech_per =".$tp);
	$sub['total_acc']+=$sub[$idx_acc];
      }
      $tmp[$key]=$sub;
    }
    $array=$tmp;



    return $array;
  }
  /*!\brief compute the sum for the heading and footing row of the
     table 
     \param the array is what load return
\return Array
(
    [BD_79] => 0
    [BD_80] => 0
    [BD_81] => 45
    [BD_82] => 7
    [BD_83] => 9
    [BD_84] => 5
    [BD_85] => 12
    [BD_86] => 14
    [BD_87] => 15
    [BD_88] => 9
    [BD_89] => 10
    [BD_90] => 11
    [BD_91] => 12
    [AC_79] => 0
    [AC_80] => 0
    [AC_81] => 0
    [AC_82] => 0
    [AC_83] => 0
    [AC_84] => 0
    [AC_85] => 0
    [AC_86] => 0
    [AC_87] => 0
    [AC_88] => 0
    [AC_89] => 0
    [AC_90] => 0
    [AC_91] => 0
)
Array
(
    [6510] => 6510
    [BD_79] => 2
    [BD_80] => 45
    [BD_81] => 7
    [BD_82] => 9
    [BD_83] => 5
    [BD_84] => 12
    [BD_85] => 14
    [BD_86] => 15
    [BD_87] => 9
    [BD_88] => 10
    [BD_89] => 11
    [BD_90] => 12
    [BD_91] => 0
    [AC_79] => 0
    [AC_80] => 0
    [AC_81] => 0
    [AC_82] => 0
    [AC_83] => 0
    [AC_84] => 0
    [AC_85] => 0
    [AC_86] => 0
    [AC_87] => 0
    [AC_88] => 0
    [AC_89] => 0
    [AC_90] => 0
    [AC_91] => 0
    [6040001] => 6040001
) */
  function head_foot($p_array) {
    if ( empty ($p_array ) ) return;
    $foot=array();
    $head=array();
    // get the initial value from anc_hypo
    $hypo=new Bud_Hypo($this->cn);
    $hypo->load();
    $initial=$hypo->bh_saldo;
    // the key is the pcm_val and the amount is an array
    foreach ($p_array as $key=>$amount) {
      $foot[$key]=$key;
      $previous=$initial;
      foreach ($amount as $periode=>$value) {
	if ( strpos($periode,'BD_') !== 0 ) continue;
	$head[$periode]=$previous;
	$foot[$periode]=(isset($foot[$periode]))?$foot[$periode]:0;
	$foot[$periode]+=$value;
	$previous=$foot[$periode];
      }
      $previous=0;
      foreach ($amount as $periode=>$value) {
	if ( strpos($periode,'AC_') !== 0 ) continue;
	$head[$periode]=$previous;
	$foot[$periode]=(isset($foot[$periode]))?$foot[$periode]:0;
	$foot[$periode]+=$value;
	$previous=$foot[$periode];
      }
  
    }

    return array($head,$foot);
  }
  static function test_me() {
    $cn=DbConnect(dossier::id());
    $obj=new Bud_Synthese_Group($cn);
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
      $res=$obj->load();
      print_r($res);
      list($head_table,$foot_table)=$obj->head_foot($res);
      print_r($head_table);
      print_r($foot_table);
    }
  }


}
