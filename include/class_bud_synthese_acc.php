
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
 */
/*!
 * \brief Manage the hypothese for the budget module
 *  synthese
 */
require_once ('class_bud_synthese.php');

class Bud_Synthese_Acc extents Bud_Synthese {

  function form() {
    $obj=new Bud_Hypo($this->cn);
    $obj->bh_id=$this->bh_id;
    $obj->load();
    $r.='<h2 class="info">'.$obj->bh_name.'</h2>';
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
  function load() {


  }
  function test_me() {

  }
}
