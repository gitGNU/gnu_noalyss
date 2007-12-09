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

/* !\file 
 */

/*! \brief manage the database bud_hypo concerning the hypothese for
    different hyp.
 *
 */
require_once ('class_dossier.php');
require_once ('postgres.php');

 
class Bud_Hypo {

  var $db; 			/*!< database connx */
  var $bh_id;			/*!< Primary key bud_hypo */
  var $bh_saldo;		/*!< saldo */
  var $bh_description;		/*!< Description */
  var $pa_id;			/*!< Foreign key to PlanAnalytic */


  function __construct($p_cn,$p_id=0)
  {
    $this->db=$p_cn;
    $this->bh_id=$p_id;
  }

  function load()
  {
    if ( $p_id == 0 ) return ;
    $sql="select bh_name,bh_saldo,bh_description, pa_id ".
      " from bud_hypothese ".
      " where  ".
      " bh_id =".$this->bh_id;
    $res=ExecSql($this->db,$sql);

    if ( pg_NumRows($res) == 0 ) return;

    $a=pg_fetch_array($res,0);
    print_r ($a);
    foreach ($a as $col=>value) 
      $this->{col}=$value;
      
  }
  function delete () {
    $sql="delete from bud_hypothese where bh_id=".$this->bud_id;
    ExecSql($this->db,$sql);
  }

  function add() {
    $bh_name=pg_escape_string($this-bh_name);
    $bh_saldo=(isNumber($this->bh_saldo) == 1 ) ?$this->bh_saldo:0;
    $bh_description=pg_escape_string($this-bh_description);
    $pa_id=($this->pa_id < 0 )?"NULL":$this->pa_id;

    $sql=sprintf(
      "insert into bud_hypothese( bh_name,bh_saldo,bh_description,pa_id)  ".
      " ('%s',%f,'%s',%s) ",
      $bh_name,
      $bh_saldo,
      $bh_description,
      $pa_id
       	 );
    ExecSql($this->db,$sql);
  }
  function update() {
    $bh_name=pg_escape_string($this-bh_name);
    $bh_saldo=(isNumber($this->bh_saldo) == 1 ) ?$this->bh_saldo:0;
    $bh_description=pg_escape_string($this-bh_description);
    $pa_id=($this->pa_id < 0 )?"NULL":$this->pa_id;

    $sql=sprintf(
      "update  bud_hypothese set bh_name='%s',".
      " bh_saldo = %f ,bh_description='%s',pa_id=%s  ".
      " where bh_id= %d"
      $bh_name,
      $bh_saldo,
      $bh_description,
      $pa_id,
      $this->bh_id
       	 );
    ExecSql($this->db,$sql);
  }



  static function test_me() {
    $cn=DbConnect (dossier::id());

  }

}
