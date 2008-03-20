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
 * \brief this class is used for the table tva_rate
 */
require_once('class_dossier.php');
require_once('postgres.php');

  /*!\brief Acc_Tva is used for to map the table tva_rate */
class Acc_Tva
{
  /* example private $variable=array("val1"=>1,"val2"=>"Seconde valeur","val3"=>0); */
  private static $cn;		/*!< $cn database connection */
  private static $variable=array("id"=>"tva_id",
				 "label"=>"tva_label",
				 "rate"=>"tva_rate",
				 "comment"=>"tva_comment",
				 "account"=>"tva_poste");

  function __construct ($p_init) {
    $this->cn=$p_init;
    $this->tva_id=0;
  }
  public function get_parameter($p_string) {
    if ( array_key_exists($p_string,self::$variable) ) {
      $idx=self::$variable[$p_string];
      return $this->$idx;
    }

      echo  (__FILE__.":".__LINE__.'Erreur attribut inexistant');
  }
  public function set_parameter($p_string,$p_value) {
    if ( array_key_exists($p_string,self::$variable) ) {
      $idx=self::$variable[$p_string];
      $this->$idx=$p_value;
    }
    else 
      exit (__FILE__.":".__LINE__.'Erreur attribut inexistant');
    
    
  }
  public function get_info() {    return var_export(self::$variable,true);  }

  public function verify() {
    // Verify that the elt we want to add is correct
  }
  public function save() {

    if (  $this->tva_id == 0 ) 
      $this->insert();
    else
      $this->update();
  }

  public function insert() {
    if ( $this->verify() != 0 ) return;
    $sql="insert into tva_rate (tva_label,tva_rate,tva_comment,tva_poste) ".
      " values ($1,$2,$3,$4)  returning tva_id";
    $res=ExecSqlParam($this->cn,
		 $sql,
		 array($this->tva_label,
		       $this->tva_rate,
		       $this->tva_comment,
		       $this->tva_poste)
		 );
    $this->tva_id=pg_fetch_result($res,0,0);
  }

  public function update() {
    if ( $this->verify() != 0 ) return;
    $sql="update tva_rate set tva_label=$1,tva_rate=$2,tva_comment=$3,tva_poste=$4 ".
      " where tva_id = $5";
    $res=ExecSqlParam($this->cn,
		 $sql,
		 array($this->tva_label,
		       $this->tva_rate,
		       $this->tva_comment,
		       $this->tva_poste,
		       $this->tva_id)
		 );

  }

  public function load() {
    $sql="select tva_label,tva_rate, tva_comment,tva_poste from tva_rate where tva_id=$1";
    $res=ExecSqlParam($this->cn,
		 $sql,
		 array($this->tva_id)
		 );
    if ( pg_NumRows($res) == 0 ) return;
    $row=pg_fetch_array($res,0);
    foreach ($row as $idx=>$value) { $this->$idx=$value; }
  }
  public function delete() {
    $sql="delete from tva_rate where tva_id=$1";
    $res=ExecSqlParam($this->cn,$sql,array($this->tva_id));
  }
  /*!\brief
   *\param
   *\return
   *\note
   *\see
   *\todo
   */	
  static function test_me() {
    $cn=DbConnect(dossier::id());
    $a=new Acc_Tva($cn);
    echo $a->get_info();
    $a->set_parameter("id",1);
    $a->load();
    $a->set_parameter("id",0);
    $a->set_parameter("rate","0.2222");
    $a->set_parameter("label","test");
    $a->save();
    $a->load();
    print_r($a);

    $a->set_parameter("comment","un cht'it test");
    $a->save();
    $a->load();
    print_r($a);

    $a->delete();
  }
  
}

/* test::test_me(); */
