<?
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
class formulaire {
  var $db;
  var $id;
  var $name;
  function formulaire($p_cn,$p_id) {
    $this->db=$p_cn;
    $this->id=$p_id;
    $this->name='UNDEF';
  }
  function GetName() {
    $ret=execSql($this->db,"select fr_label from formdef where fr_id=".$this->id);
    if (pg_NumRows($ret) == 0) return $this->name;
    $a=pg_fetch_array($ret,0);
    $this->name=$a['fr_label'];
    return $this->name;
  }
  function GetRow($p_start,$p_end) {
    if ( $p_start == $p_end ) 
      $cond=" j_tech_per = $p_start ";
    else
      $cond = "(j_tech_per >= $p_start and j_tech_per <= $p_end) ";

   $Res=ExecSql($this->db,"select fo_id ,
                     fo_fr_id,
                     fo_pos,
                     fo_label,
                     fo_formula,
                     fr_label from form
                      inner join formdef on fr_id=fo_fr_id
                     where fr_id =".$this->id.
                     "order by fo_pos");
    $Max=pg_NumRows($Res);
    if ($Max==0) {      $this->row=0;return null;}
    $col=array();
    for ($i=0;$i<$Max;$i++) {
      $l_line=pg_fetch_array($Res,$i);
      $col[]=ParseFormula($this->db,
                   $l_line['fo_label'],
                   $l_line['fo_formula'],$cond);
     
    } //for ($i
    $this->row=$col;
    return $col;
  }
}
?>