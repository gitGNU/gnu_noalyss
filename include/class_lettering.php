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
   * \brief letter the accounting entry (row level)
   */
require_once ('class_user.php');

  /**
   *@brief mother class for the lettering by account and by card
   * use the tables jnt_letter, letter_deb and letter_cred
   *
    @note by default start and end are the 1.1.exercice to 31.12.exercice
   Example
   @code

   @endcode
  */
class Lettering
{
  /* example private $variable=array("easy_name"=>column_name,"email"=>"column_name_email","val3"=>0); */
  protected $variable=array("account"=>"account",
			    "quick_code"=>"quick_code",
			    "start"=>"start",
			    "end"=>"end",
			    "periode"=>"periode",
			    "sql_ledger"=>"sql_ledger"
			    )
			    ;
  function __construct ($p_init) {
    $this->db=$p_init;
    $a=new User($p_init);
    $exercice=$a->get_exercice();
    $this->start='01.01.'.$exercice;
    $this->end='31.12.'.$exercice;
    // available ledgers
    $this->sql_ledger=str_replace('jrn_def_id','jr_def_id',$a->get_ledger_sql('ALL',3));

  }
  public function get_parameter($p_string) {
    if ( array_key_exists($p_string,$this->variable) ) {
      $idx=$this->variable[$p_string];
      return $this->$idx;
    }
    else 
      throw new Exception (__FILE__.":".__LINE__.$p_string.'Erreur attribut inexistant');
  }
  public function set_parameter($p_string,$p_value) {
    if ( array_key_exists($p_string,$this->variable) ) {
      $idx=$this->variable[$p_string];
      $this->$idx=$p_value;
    }
    else 
      throw new Exception (__FILE__.":".__LINE__.$p_string.'Erreur attribut inexistant');
  }
  public function get_info() {    return var_export(self::$variable,true);  }
  public function verify() {
    // Verify that the elt we want to add is correct
  }
  /**
   *@brief save from array
   *@param $p_array
@code
  'phpsessid' => string 'a2c261a2f48b0045a8c28276afd31f5c' (length=32)
  'gDossier' => string '13' (length=2)
  'letter_j_id' => 
    array
      0 => string '5' (length=1)
      1 => string '23' (length=2)
      2 => string '67' (length=2)
      3 => string '136' (length=3)
      4 => string '139' (length=3)
      5 => string '145' (length=3)
      6 => string '374' (length=3)
      7 => string '148' (length=3)
      8 => string '156' (length=3)
      9 => string '254' (length=3)
      10 => string '277' (length=3)
  'ck0' => string 'on' (length=2)
  'ck1' => string 'on' (length=2)
  'ck10' => string 'on' (length=2)
  'j_id' => string '142' (length=3)
  'jnt_id' => string '-2' (length=2)
  'record' => string 'Sauver' (length=6)
@endcode
@todo if only one row, we delete completely the lettering
  */
  public function save($p_array) {
    $this->db->start();
    $this->db->exec_sql('delete from jnt_letter where jl_id=$1',array($p_array['jnt_id']));
    $jl_id=$this->db->get_next_seq("jnt_letter_jl_id_seq");
    $this->db->exec_sql('insert into jnt_letter(jl_id) values($1)',
			array($jl_id));
  
    // save the source 
    $deb=$this->db->get_value('select j_debit,j_montant from jrnx where j_id=$1',array($p_array['j_id']));
    if ( $deb == 't') {
      // save into letter_deb
      $ld_id=$this->db->get_value('insert into letter_deb(j_id,jl_id) values($1,$2) returning ld_id',array($p_array['j_id'],$jl_id));
    }else {
      $lc_id=$this->db->get_value('insert into letter_cred(j_id,jl_id)  values($1,$2) returning lc_id',array($p_array['j_id'],$jl_id));
    }

    // save dest
    for($i=0;$i<count($p_array['letter_j_id']);$i++){
      if (isset($p_array['ck'.$i])) { //if 1
	// save the dest 
	$deb=$this->db->get_value('select j_debit,j_montant from jrnx where j_id=$1',array($p_array['letter_j_id'][$i]));
	if ( $deb == 't') {
	  // save into letter_deb
	  $ld_id=$this->db->get_value('insert into letter_deb(j_id,jl_id) values($1,$2) returning ld_id',array($p_array['letter_j_id'][$i],$jl_id));
	}else {
	  $lc_id=$this->db->get_value('insert into letter_cred(j_id,jl_id)  values($1,$2) returning lc_id',array($p_array['letter_j_id'][$i],$jl_id));
	}
      } //end if 1
    } //end for
    // save into jnt_letter

    $this->db->commit();
  }
  /**
   *@brief retrieve * row thanks a condition
   */
  public function seek($cond,$p_array=null) 
  {
    /*
      $sql="select * from * where $cond";
      return $this->cn->get_array($cond,$p_array)
    */
  }
  public function insert() {
    if ( $this->verify() != 0 ) return;
    /*  please adapt
	$sql="insert into tva_rate (tva_label,tva_rate,tva_comment,tva_poste) ".
	" values ($1,$2,$3,$4)  returning tva_id";
	$this->tva_id=$this->cn->get_value(
	$sql,
	array($this->tva_label,
	$this->tva_rate,
	$this->tva_comment,
	$this->tva_poste)
	);
    */
  }
  protected function show_not_lettered() {
  }
  protected function show_lettered() {
  }
  protected function show_all() {
    $this->get_all();
    $r="";
    ob_start();
    require_once('template/letter_all.php');
    $r=ob_get_contents();
    ob_clean();
    return $r;
  }

  public function show_list($p_type) {
    switch($p_type) {
    case 'all':
      return $this->show_all();
      break;
    case 'notletter':
      return $this->show_not_lettered();
      break;
    case 'letter':
      return $this->show_lettered();
      break;
    }
    throw new Exception ("[$p_type] is no unknown");
  }

  public function show_letter($p_jid) {
    $j_debit=$this->db->get_value('select j_Debit from jrnx where j_id=$1',array($p_jid));
    $amount_init=$this->db->get_value('select j_montant from jrnx where j_id=$1',array($p_jid));
    $this->get_other_side($j_debit);
    // retrieve jnt_letter.id
    $sql="select distinct(jl_id) from jnt_letter  join letter_deb using (jl_id) join letter_cred using (jl_id)
        where letter_deb.j_id = $1 or letter_cred.j_id=$2";
    $jnt_id=$this->db->get_value($sql,array($p_jid,$p_jid));

    if ($this->db->count()==0 ) $jnt_id=-2;

    ob_start();
    require_once('template/letter_prop.php');
    $r=ob_get_contents();
    ob_clean();
    $r.=HtmlInput::hidden('j_id',$p_jid);
    $r.=HtmlInput::hidden('jnt_id',$jnt_id);

    return $r;
  }
  public function update() {
    if ( $this->verify() != 0 ) return;
  }

  public function load() {

    $sql="select tva_label,tva_rate, tva_comment,tva_poste from tva_rate where tva_id=$1"; 
    if ( Database::num_row($res) == 0 ) return;
    foreach ($res as $idx=>$value) { $this->$idx=$value; }
  }

  public function delete() {
    /*    $sql="delete from tva_rate where tva_id=$1"; 
	  $res=$this->cn->exec_sql($sql,array($this->tva_id));
    */
  }
  /**
   * Unit test for the class
   */	
  static function test_me() {
  }
  
}

class Lettering_Account extends Lettering{
  function __construct($p_init,$p_account=null) {
    parent::__construct($p_init);
    $this->account=$p_account;
    $this->object_type='account';
  }
  /**
   *@brief get other side 
   *@param $j_debit f for cred or t for debit
   */
  public function get_other_side($j_debit) {
    $sql="
select j_id,j_date,to_char(j_date,'DD.MM.YYYY') as j_date_fmt,
j_montant,j_debit,jr_comment,jr_internal,
coalesce(comptaproc.get_letter_jnt(j_id),-1) as letter
 from jrnx join jrn on (j_grpt = jr_grpt_id)
where j_poste = $1 and j_date >= to_date($2,'DD.MM.YYYY') and j_date <= to_date ($3,'DD.MM.YYYY') 
and $this->sql_ledger -- and j_debit != $4

order by j_date,j_id";
    $this->content=$this->db->get_array($sql,array($this->account,$this->start,$this->end));
  }


  public function get_all() {
    $sql="
select j_id,j_date,to_char(j_date,'DD.MM.YYYY') as j_date_fmt,
j_montant,j_debit,jr_comment,jr_internal,
coalesce(comptaproc.get_letter_jnt(j_id),-1) as letter
 from jrnx join jrn on (j_grpt = jr_grpt_id)
where j_poste = $1 and j_date >= to_date($2,'DD.MM.YYYY') and j_date <= to_date ($3,'DD.MM.YYYY') 
and $this->sql_ledger

order by j_date,j_id";
    $this->content=$this->db->get_array($sql,array($this->account,$this->start,$this->end));
  }

}

class Lettering_Card extends Lettering{
  function __construct($p_init,$p_account=null) {
    parent::__construct($p_init);
    $this->qcode=$p_qcode;
    $this->object_type='card';
  }


}
