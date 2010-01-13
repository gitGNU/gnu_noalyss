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
 *   \brief class acc_reconciliation, this class is new and the code
 *   must use it
 *  
 */
require_once("class_iconcerned.php");
require_once ('class_database.php');
require_once ('class_dossier.php');

/*! \brief new class for managing the reconciliation it must be used
 * instead of the function InsertRapt, ...
 *
 */
class Acc_Reconciliation {
  var $db;			/*!< database connection */
  var $jr_id;			/*!< jr_id */
  public static  $javascript=JS_LEDGER;
  function   __construct($cn) {
    $this->db=$cn;
    $this->jr_id=0;
  }

  function set_jr_id($jr_id) {
    $this->jr_id=$jr_id;
  }
  /*! \brief return a widget of type js_concerned
   */
  function widget() {
    $wConcerned=new IConcerned();
    $wConcerned->extra=0; // with 0 javascript search from e_amount... field (see javascript)

    return $wConcerned;

  }
  /*!
   *\brief   Insert into jrn_rapt the concerned operations
   *        
   * \param $jr_id2 (jrn.jr_id) => jrn_rapt.jra_concerned or a string
   * like "jr_id2,jr_id3,jr_id4..."
   *
   * \return none
   *
   */
  function insert($jr_id2) {
    if ( trim($jr_id2) == "" ) 
      return;
      if ( strpos($jr_id2,',') !== 0 )
	{
	  $aRapt=split(',',$jr_id2);
	  foreach ($aRapt as $rRapt) {
	    if ( isNumber($rRapt) == 1 ) 
	      {
		$this->insert_rapt($rRapt);
	      }
	  }
	} else 
	if ( isNumber($jr_id2) == 1 ) 
	  {
	    $this->insert_rapt($jr_id2);
	  }
  }

  /*!
   *\brief   Insert into jrn_rapt the concerned operations 
   * should not  be called directly, use insert instead
   *        
   * \param $jr_id2 (jrn.jr_id) => jrn_rapt.jra_concerned
   *
   * \return none
   *
   */
  function insert_rapt($jr_id2) {
    if ( isNumber($this->jr_id)  == 0 ||  isNumber($jr_id2) == 0 )
      {
	return false;
      }
    if ( $this->jr_id==$jr_id2)
      return true;

    // verify if exists
    if ( $this->db->count_sql(
		  "select jra_id from jrn_rapt where jra_concerned=".$this->jr_id.
		  " and jr_id=$jr_id2
                   union
                 select jra_id from jrn_rapt where jra_id=".$this->jr_id.
		  " and jra_concerned=$jr_id2 ") 
	 ==0) 
      {
	// Ok we can insert 
	$Res=$this->db->exec_sql("insert into jrn_rapt(jr_id,jra_concerned) values ".
		     "(".$this->jr_id.",$jr_id2)");
      }
    return true;
  }
  /*!   
   *\brief   Insert into jrn_rapt the concerned operations
   *        
   * \param $this->jr_id (jrn.jr_id) => jrn_rapt.jr_id
   * \param $jr_id2 (jrn.jr_id) => jrn_rapt.jra_concerned
   * 
   * \return none
   */
  function remove($jr_id2) {
    if ( isNumber($this->jr_id)  == 0 or 
	 isNumber($jr_id2) == 0 )
      {
	return;
      }
    // verify if exists
    if ( $this->db->count_sql("select jra_id from jrn_rapt where ".
		  " jra_concerned=".$this->jr_id."  and jr_id=$jr_id2
                   union
                 select jra_id from jrn_rapt where jra_concerned=$jr_id2 ".
		  " and jr_id=".$this->jr_id) !=0) 
      {
	// Ok we can delete 
	$Res=$this->db->exec_sql("delete from jrn_rapt where ".
		     "(jra_concerned=$jr_id2 and jr_id=".$this->jr_id.") or 
                               (jra_concerned=".$this->jr_id." and jr_id=$jr_id2) ");
      }
  }

  /*!   
   *\brief   Return an array of the concerned operation
   *        
   *  
   *\param database connection
   * \return array if something is found or null
   */
  function get ( ) {
    $sql=" select jr_id as cn from jrn_rapt where jra_concerned=".$this->jr_id.
      " union ".
      " select jra_concerned as cn from jrn_rapt where jr_id=".$this->jr_id;
    $Res=$this->db->exec_sql($sql);

    // If nothing is found return null
    $n=Database::num_row($Res);

    if ($n ==0 ) return null;

    // put everything in an array
    for ($i=0;$i<$n;$i++) {
      $l=Database::fetch_array($Res,$i);
      $r[$i]=$l['cn'];
    }
    return $r;
  }
  /*!\brief return the javascript function (static method)
   *
  static function javascript()  {
    return JS_LEDGER;
  }
  */
  static function test_me() {
    $cn=new Database(dossier::id());
    $rap=new Acc_Reconciliation($cn);
    $rap->set_jr_id(38);
    $a=$rap->get();
    print_r($a);
    $rap->remove(4);
    $rap->insert("4,5,6");
    $a=$rap->get();
    print_r($a);
    echo Acc_Reconciliation::$javascript;
    $w=$rap->widget();
    $w->name="jr_concerned";

    $w->extra2="";
    echo $w->input();
  }

}
