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
 * \brief this class manage the table bud_detail
 *
 */

  /*!
   * \brief this class manage the table bud_detail
   *
   */

require_once ('constant.php');
require_once ('postgres.php');
require_once ('class_dossier.php');

class Bud_Detail {
  var $bd_id;			/*!< primary key */
  var $db;			/*!< database connx */
  var $po_id;			/*!< PosteAnalytic id */
  var $bc_id;			/*!< Budget Card id */
  var $pcm_val;			/*!< from the table tmp_pcmn */
  function __construct( $p_cn,$id = 0 ) {
    $this->db=$p_cn; $this->bd_id=$id;
    $this->po_id=null;$this->bc_id=null;$this->pcm_val=null;
  }
  function add () {
    $array=array($this->po_id,
		 $this->bc_id,
		 $this->pcm_val);
    $sql="insert into bud_detail (po_id,bc_id,pcm_val) ".
      " values ($1,$2,$3) returning bd_id ";

    $a=ExecSqlParam($this->db,$sql,$array);
    $x=pg_fetch_array($a,0);
    $this->bd_id=$x['bd_id'];
  }

  function update() {
    if ( $this->bd_id == 0) return;
    $sql="update bud_detail set po_id=$1,".
      "bc_id=$2,".
      "pcm_val=$3 ".
      " where bd_id=$4";

    $array=array($this->po_id,
		 $this->bc_id,
		 $this->pcm_val,
		 $this->bd_id
		 );
    ExecSqlParam($this->db,$sql,$array);

  }

  function delete() {
    ExecSql($this->db,"delete from bud_detail where bd_id=".$this->bd_id);
  }
  function load()
  {
    if ( $this->bd_id == 0 ) return ;
    $sql="select * ".
      " from bud_detail ".
      " where  ".
      " bd_id =".$this->bd_id;
    $res=ExecSql($this->db,$sql);

    if ( pg_NumRows($res) == 0 ) return;

    $a=pg_fetch_array($res,0);
    foreach ( array('bc_id','po_id','pcm_val') as $key) {
      $this->{"$key"}=$a[$key];
    }

  
  }

  static function testme() {
    $cn=DbConnect(dossier::id());
    $a=new Bud_Detail($cn);
    $a->po_id=4;
    echo "<h2> Ajout d'un bud_detail</h2>";
    $a->add();
    print_r($a);
    echo "<h2> Mise a jour d'un bud_detail</h2>";
    $a->pcm_val='55';
    $a->update();
    print_r($a);
    echo "<h2>Load </h2>";
    $b=new Bud_Detail($cn);
    $b->bd_id=$a->bd_id;
    $b->load();
    print_r($b);

    echo "<h2>Effacement</h2>";
    $a->delete();
  }




}
