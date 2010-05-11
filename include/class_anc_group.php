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
 * \brief class for the group of the analytic account
 *
 */
require_once ('class_database.php');
require_once ('constant.php');
require_once ('class_dossier.php');

/*! \brief class for the group of the analytic account
 *
 */
class Anc_Group {
  var $db;
  var $ga_id;
  var $ga_description;
  var $pa_id;

  function __construct ( $p_cn ) {
    $this->db=$p_cn;
    $this->ga_id=null;
    $this->ga_description=null;
    $this->pa_id=null;
  }
  /*! 
   * \brief insert into the database  an object
   * \return message with error otherwise an empty string
   */
  
  function insert() {
    $sql=" insert into groupe_analytique (ga_id,ga_description,pa_id) values ('%s','%s',%d)";
    $sql=sprintf($sql,Database::escape_string($this->ga_id),
		 Database::escape_string($this->ga_description),
		 $this->pa_id);
    try {
      $this->db->exec_sql($sql);
    } catch (Exception $a) {
      return '<span class="notice">Doublon !!</span>';
    }
    return "";
  }
  /*! 
   * \brief remove from the database 
   */
  
  function remove() {
    $this->ga_id=str_replace(' ','',$this->ga_id);
    $this->ga_id=strtoupper($this->ga_id);
    $sql=" delete from groupe_analytique where ga_id='".Database::escape_string($this->ga_id)."'";

    $this->db->exec_sql($sql);
  }

  /*! 
   * \brief load from the database and make an object
   */
  function load() {
    $sql="select ga_id, ga_description,pa_id from groupe_analytique where".
      " ga_id = ".$this->ga_id;
    $res=$this->db->exec_sql($sql);
    $array=Database::fetch_all($res);
    if ( ! empty($array) ) {
      $this->ga_id=$array['ga_id'];
      $this->ga_description=$array['ga_description'];
      $this->pa_id=$array['pa_id'];
    }
  }

  /*! 
   * \brief fill the object thanks an array
   * \param array
   */
  function get_from_array($p_array) {
    $this->ga_id=$p_array['ga_id'];
    $this->pa_id=$p_array['pa_id'];
    $this->ga_description=$p_array['ga_description'];
  }
  function myList() {
    $sql=" select ga_id,groupe_analytique.pa_id,pa_name,ga_description ".
      " from groupe_analytique ".
      " join plan_analytique using (pa_id)";
    $r=$this->db->exec_sql($sql);
    $array=Database::fetch_all($r);
    $res=array();
    if ( ! empty($array)) {
      foreach ($array as $m ) {
	$obj= new Anc_Group($this->db);
	$obj->get_from_array($m);
	$obj->pa_name=$m['pa_name'];
	$res[]=clone $obj;
      }
    }
    return $res;
  }
  static function test_me() {

    $cn=new Database(dossier::id());
    print_r($cn);
    $o=new Anc_Group($cn);
    $r=$o->myList();
    print_r($r);
    echo '<hr>';
    print_r($o);
    $o->ga_id="DD' dd dDD";
    $o->ga_description="Test 1";
    $o->remove();
	//    $o->insert();
    $o->ga_id="DD";
    $o->ga_description="Test 1";
    $o->remove();

    $r=$o->myList();
    print_r($r);
  }
}
