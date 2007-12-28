
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

class Bud_Synthese_Hypo extends Bud_Synthese {
/*   function __construct($p_cn) { */
/*     echo "constructor ".__FILE__; */
/*   } */
  static function make_array($p_cn) {
    $a=make_array($p_cn,'select bh_id, bh_name from bud_hypothese '.
		  ' where pa_id is not null order by bh_name');
    return $a;
  }

  function form() {
    $wSelect = new widget('select');
    $wSelect->name='bh_id';
    $wSelect->value=Bud_Synthese_Hypo::make_array($this->cn);

    $r="Hypoth&egrave;se :".$wSelect->IOValue();

    $per=make_array($this->cn,"select p_id,to_char(p_start,'MM.YYYY') ".
		    " from parm_periode order by p_start,p_end");

    $wFrom=new widget('select');
    $wFrom->name='from';
    $wFrom->value=$per;

    $wto=new widget('select');
    $wto->name='to';
    $wto->value=$per;
    $r.="Periode de ".$wFrom->IOValue()." &agrave; ".$wto->IOValue();
    $r.=dossier::hidden();
    return $r;
  }
  function load() {
    $per=sql_filter_per($this->cn,$this->from,$this->to,'p_id','p_id');
    /*        $sql="select sum(bdp_amount),ga_id,pcm_val,pcm_lib ".
      " from bud_detail_periode join bud_detail using (bd_id) ".
      " join bud_hypothese using (bh_id) ".
      " join tmp_pcmn using (pcm_val) ".
      " join plan_analytique using (pa_id) ".
      " join groupe_analytique using(pa_id) ".
      " where bh_id= ".$this->bh_id.' and '.$per.
      " group by ga_id,pcm_lib,pcm_val ";
    */
    $sql="select bd_id, pcm_val,pcm_lib,po_id,ga_id ".
      " from bud_detail join tmp_pcmn using (pcm_val) join poste_analytique using (po_id)".
      " where bh_id = ".$this->bh_id.' order by pcm_val ';
    $array=get_array($this->cn,$sql);



    return $array;
  }
  static function test_me() {
    $cn=DbConnect(dossier::id());
    $obj=new Bud_Synthese_Hypo($cn);
    echo '<form method="GET">';
    echo $obj->form();
    echo widget::submit_button('recherche','Recherche');
    echo '</form>';
    print_r($_GET);
    if ( isset ($_GET['recherche'])) {
      $obj->from_array($_GET);
      print_r( $obj->load());
    }
  }
}
