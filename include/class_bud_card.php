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

/*! \brief class to manage the table bud_detail
 *
 */

require_once ('constant.php');
require_once ('postgres.php');
require_once ('class_dossier.php');

class Bud_Card {
  var $bc_id;			/*!< Primary key */
  var $db;			/*!< Database connx */
  var $bc_code;			/*!< code of the card */
  var $bc_description;		/*!< Description */
  var $bc_price_unit;		/*!< price per unit */
  var $bc_unit;			/*!< what unit to use (euro, square
				  meter...) */
  var $bh_id;			/*!< Bud_Hypo id */

  function __construct( $p_cn,$id = 0 ) {
    $this->bc_id=0;
    $this->db=$p_cn;
  }
  function add () {
    $array=array(
		 $this->bc_code,
		 $this->bc_description,
		 $this->bc_price_unit,
		 $this->bc_unit
		 );
    $sql="insert into bud_card (bc_code,bc_description,bc_price_unit,bc_unit) ".
      " values (substr($1,1,10),$2,$3,substr($4,1,20)) returning bc_id ";

    $a=ExecSqlParam($this->db,$sql,$array);
    $x=pg_fetch_array($a,0);
    $this->bc_id=$x['bc_id'];
  }

  function update() {
    if ( $this->bd_id == 0) return;
    $sql="update bud_card set bc_code=substr($1,1,10),".
      "bc_description=$2,".
      "bc_price_unit=$3, ".
      "bc_unit=substr($4,1,20) ".
      " where bd_id=$5";
    $array=array(
		 $this->bc_code,
		 $this->bc_description,
		 $this->bc_price_unit,
		 $this->bc_unit,
		 $this->bc_id
		 );

    ExecSqlParam($this->db,$sql,$array);

  }

  function delete() {
    ExecSql($this->db,"delete from bud_card where bc_id=".$this->bc_id);
  }

  function load()
  {
    if ( $this->bc_id == 0 ) return ;
    $sql="select bc_code,bc_description, bc_price_unit,bc_unit ".
      " from bud_card ".
      " where  ".
      " bh_id =".$this->bc_id;
    $res=ExecSql($this->db,$sql);

    if ( pg_NumRows($res) == 0 ) return;

    $a=pg_fetch_array($res,0);
    foreach ( array('bc_code','bc_description','bc_price','bc_price_unit') as $key) {
      $this->{"$key"}=$a[$key];
    }
  
  }

  
  static function testme() {
    $cn=DbConnect(dossier::id());
    $a=new Bud_Card($cn);

    echo "<h2> Ajout d'un bud_card</h2>";
    $a->bc_code='test une fois';
    $a->bc_description="Juste une description";
    $a->bc_unit="Euro";
    $a->bc_price_unit=0.55;
    $a->add();
    print_r($a);

    echo "<h2> Mise a jour d'un bud_card</h2>";
    $a->bc_code=' --- test une fois';
    $a->bc_description="--- -Juste une description";
    $a->bc_unit="M2";
    $a->bc_price_unit=0.50;
    print_r($a);

    echo "<h2>Load </h2>";
    $b=new Bud_Card($cn);
    $b->bc_id=$a->bc_id;
    $b->load();
    print_r($b);

    echo "<h2>Effacement</h2>";
    $a->delete();
  }




}
