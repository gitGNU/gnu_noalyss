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
 * \brief Manage the synthese module : budget
 */

class Bud_Synthese_Simple extends Bud_Synthese {

  var $from_po;
  var $to_po;

  function from_array($p_array) {
    parent::from_array($p_array);
    foreach (array ('po_from','po_to') as $l) {
      $this->$l=isset($p_array[$l])?$p_array[$l]:'';
    }
  }

  function form() {
    $r="";
    $obj=new Bud_Hypo($this->cn);
    $obj->bh_id=$this->bh_id;
    if ( $obj->has_plan () == 1 ) {
      /* show the analytic account of this plan */
      $po=new widget('select');
      $po->name='from_po';
      $value=make_array($this->cn,'select po_id,po_name from poste_analytique '.
			' where pa_id = '.$this->bh_id.' order by po_name');
      $po->value=$value;
      $po_to=new widget('select');
      $po_to->name='to';
      $po_to->value=$value;
      $r.='Plage de poste analytique '.$po->IOValue().' &agrave; '.$po_to->IOValue();
    }
    $per=make_array($this->cn,"select p_id,to_char(p_start,'MM.YYYY') ".
		    " from periode order by p_start,p_end");

    $wFrom=new widget('select');
    $wFrom->name='from';
    $wFrom->value=$per;

    $wto=new widget('select');
    $wto->name='to';
    $wto->value=$per;
    $r.="Periode de ".$wFrom->IOValue()." &agrave; ".$wto->IOValue();
  }

  function load_all() {


  }
  static function test_me() {

  }
}
