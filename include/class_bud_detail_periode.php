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
* \brief definition of class Bud_Detail_Periode
 */

/*! \brief manage the table bud_detail_periode
 *
 */

require_once ('class_dossier.php');



class Bud_Detail_Periode{
  var $cn;
  var $bpd_id;
  var $p_id;
  var $bdp_amount;

  function __construct ($p_cn,$bdp_id=0) {
    $this->cn=$p_cn;
    $this->bdp_id=0;
    $this->p_id=0;
    $this->bdp_amount=0;
  }
  /*!\brief delete
   */
  function delete () {
    $sql="delete from bud_detail_periode where bdp_id=".$this->bdp_id;
    ExecSql($this->cn,$sql);
  }
  /*!\brief add a row in the table bud_detail_periode and set the
     this->bdp_id with the value in the row
   *
   */
  function add(){
    $sql="insert into bud_detail_periode(bdp_amount,p_id,bd_id) values ($1,$2,$3)".
      " returning bdp_id";
    $value=array($this->bdp_amount,$this->p_id,$this->bd_id);
    try {
      $r=ExecSqlParam($this->cn,$sql,$value);
      $this->bdp_id=pg_fetch_result($r,0,0);
    } catch (Exception $e) {
      echo "Erreur : ".$e->getMessage();
    }
  }
  /*!\brief for developper
   *
   */
  static function  test_me() {
    $cn=DbConnect(dossier::id());

    print_r($cn);

    $obj=new Bud_Detail_Periode($cn);

    $obj->bdp_amount = 12;
    $obj->p_id=54;
    $obj->bd_id=10;
    $obj->add();
    print_r($obj);
    echo '<hr>';
  }
}


