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
 * \brief manage the database bud_hypo concerning the hypothese for
 *   different hyp.
 */

/*!
 * \brief manage the database bud_hypo concerning the hypothese for
 *   different hyp.
 *
 */
require_once("class_itext.php");
require_once("class_ihidden.php");
require_once("class_iselect.php");
require_once ('class_dossier.php');
require_once ('class_database.php');

 
class Bud_Hypo {

  var $db; 			/*!< database connx */
  var $bh_id;			/*!< Primary key bud_hypo */
  var $bh_saldo;		/*!< saldo */
  var $bh_description;		/*!< Description */
  var $pa_id;			/*!< Foreign key to Anc_Plan */


  function __construct($p_cn,$p_id=0)
  {
    $this->db=$p_cn;
    $this->bh_id=$p_id;
    $this->pa_id=null;
    $this->bh_saldo=0;
    $this->bh_name="";$this->bh_description="";
  }

  function load()
  {
    if ( $this->bh_id == 0 ) return ;
    $sql="select bh_name,bh_saldo,bh_description, pa_id ".
      " from bud_hypothese ".
      " where  ".
      " bh_id =".$this->bh_id;
    $res=$this->db->exec_sql($sql);

    if ( Database::num_row($res) == 0 ) return;

    $a=Database::fetch_array($res,0);
    $this->bh_name=$a['bh_name'];
    $this->bh_saldo=$a['bh_saldo'];
    $this->bh_description=$a['bh_description'];
    $this->pa_id=$a['pa_id'];
  
  }
  
  function delete () {
    $sql="delete from bud_hypothese where bh_id=".$this->bh_id;
    $this->db->exec_sql($sql);
  }

  function add() {
    $bh_saldo=(isNumber($this->bh_saldo) == 1 ) ?$this->bh_saldo:0;
    $pa_id=($this->pa_id <= 0 )?null:$this->pa_id;
    if ( strlen(trim ($this->bh_name)) == 0 ) return; 
    $sql="insert into bud_hypothese( bh_name,bh_saldo,bh_description,pa_id)  ".
      " values ($1,$2,$3,$4) returning bh_id";
    $array=array(
		 $this->bh_name,
		 $bh_saldo,
		 $this->bh_description,
		 $pa_id
	      );
    $a=$this->db->exec_sql($sql,$array);
    $this->bh_id=Database::fetch_result($a,0,0);
  }
  function update() {
    if ( strlen(trim ($this->bh_name)) == "" ) return; 
    $bh_name=Database::escape_string($this->bh_name);
    $bh_saldo=(isNumber($this->bh_saldo) == 1 ) ?$this->bh_saldo:0;
    $bh_description=Database::escape_string($this->bh_description);
    $pa_id=($this->pa_id == null || $this->pa_id < 0 )?"NULL":$this->pa_id;

    $sql=sprintf(
      "update  bud_hypothese set bh_name='%s',".
      " bh_saldo = %f ,bh_description='%s'  ".
      " where bh_id= %d",
      $bh_name,
      $bh_saldo,
      $bh_description,
      $this->bh_id
       	 );
    $this->db->exec_sql($sql);
  }

  function get_from_array($p_array) {
    $this->bh_id=(isset($p_array['bh_id']))?$p_array['bh_id']:0;
    $this->bh_saldo=(isset($p_array['bh_saldo']))?$p_array['bh_saldo']:0;
    $this->bh_description=(isset($p_array['bh_description']))?$p_array['bh_description']:0;
    $this->bh_name=(isset($p_array['bh_name']))?$p_array['bh_name']:0;
    $this->pa_id=(isset($p_array['pa_id']))?$p_array['pa_id']:0;

  }
/*! 
 * \brief retrieve a array of hypothese, 
 * \param $p_cn, database connextion
 *
 * \return array of Bud_Hypo objects or null
 */
  static function get_list($p_cn) {
    $sql="select * from bud_hypothese order by bh_name ";
    $r=$p_cn->exec_sql($sql);
    if ( Database::num_row($r)==0 ) return null;
    $a=Database::fetch_all($r);
    foreach($a as $row) {
      $tmp=new Bud_Hypo($p_cn);
      $tmp->bh_id=$row['bh_id'];
      $tmp->bh_name=$row['bh_name'];
      $tmp->pa_id=$row['pa_id'];
      $tmp->bh_saldo=$row['bh_saldo'];
      $tmp->bh_description=$row['bh_description'];
      $result[]=clone $tmp;
    }
    return $result;
  }

  function form($p_update=0) {

    $wName=new IText("Nom","bh_name",$this->bh_name);

    $wDescription=new IText("Description","bh_description",$this->bh_description);
    $wSaldo=new IText("Solde","bh_saldo",$this->bh_saldo);
    $wBh_id=new IHidden("","bh_id",$this->bh_id);
    $array=$this->db->make_array("select pa_id,pa_name from plan_analytique",1);
    $wPa_id=new ISelect("Plan Analytique","pa_id",$array);
    $wPa_id->selected=$this->pa_id;
    if ( $p_update != 0 ) $wPa_id->readonly=true;
    $wName->table=1;
    $wDescription->table=1;
    $wSaldo->table=1;
    $wPa_id->table=1;

    $r="<table>";
    $r.='<tr>'.$wName->input().'</tr>';
    $r.='<tr>'.$wDescription->input().'</tr>';
    $r.='<tr>'.$wSaldo->input().'</tr>';
    $r.='<tr>'.$wPa_id->input().'</tr>';
    $r.=$wBh_id->input();
    $r.="</table>";
    return $r;


  }
  function has_plan() {
    $this->load();
    if ( isNumber($this->pa_id)==1) return 1;
    else return 0;
  }
  function size() {
    $count=$this->db->get_value("select count(*) from bud_hypothese");
    return $count;
  }
  function size_analytic() {
    $count=$this->db->get_value("select count(*) from bud_hypothese where pa_id is not null");
    return $count;
  }

  static function test_me() {
    $cn=new Database (dossier::id());
    $cn->exec_sql("delete from bud_hypothese");
    $a=new Bud_Hypo($cn);
    $a->bh_name="test me function";
    $a->bh_saldo=2.123456;
    $a->bh_description=" Test depuis la test_me";
    echo "<h2> ajout d'une hypothese</h2>";
    $a->add();

    $a->bh_name="2";
    $a->add();

    $a->bh_name="3";
    $a->add();

    print_r($a);

    echo "<h2> mise a jour</h2>";

    $a->bh_name="c'est ici";
    $a->bh_saldo="t";
    $a->update();

    print_r($a);
    //    $a->delete();

    echo "<h2> chargement de b</h2>";
    $b=new Bud_Hypo($cn,$a->bh_id);
    echo "Avant load";
    print_r($b);
    echo "<hr>";
    $b->load();
    echo "Apres load";
    print_r($b);
    echo '<hr>';
    echo '<h2> Liste complete </h2>';
    print_r( Bud_Hypo::get_list($cn));
  }

}
