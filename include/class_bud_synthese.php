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
 * \brief Manage the synthese for the budget module
 *  Mother class
 */
/*!
 * \brief Manage the synthese for the budget module
 *  Mother class
 */
require_once ('class_dossier.php');
require_once ('class_bud_hypo.php');

class Bud_Synthese {
  var $cn;			/*!< database connection */
  var $from;			/*!< from periode (p_id) */
  var $to;			/*!< to periode (p_id) */
  var $bh_id;			/*!< Hypothese id */
  function __construct ($p_cn) { 
    $this->cn=$p_cn;
  }

  function from_array($p_array) {
    foreach (array ('bh_id','from','to') as $l) {
      $this->$l=(isset ($p_array[$l]))?$p_array[$l]:"";
    }
  }
  static function test_me() {
    $cn=DbConnect(dossier::id());
    $a=array('from'=>'01.01.2008',
	     'to'=>'11.01.2008',
	     'bh_id'=>1);
    print_r($a);
    $obj=new Bud_Synthese($cn);
    $obj->from_array($a);
    print_r($obj);
  }
}
