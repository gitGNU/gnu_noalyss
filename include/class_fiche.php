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

class fiche {
  var $cn;// database connection
  var $a_attribut; // array of attribut
  var $id; // fiche.f_id

  function fiche($p_cn,$p_id=0) {
    $this->cn=$p_cn;
    $this->id=$p_id;
    $this->ad_id[0]='undefined';
    $this->jft_id[0]='undefined';
    $this->fd_id[0]='undefined';
    $this->description[0]='undefined';
    $this->libelle[0]='undefined';

  }

  function getAttribut() {
    if ( $p_id == 0){
      return;
    }
    $sql="select * 
          from jnt_fic_att_value 
              natural join fiche 
              natural join attr_value natural 
              join attr_def where f_id=".$this->id;
    $Ret=ExecSql($this->cn,$sql);
    if ( ($Max=pg_NumRows($Ret)) == 0 )
      return ;
    for ($i=0;$i<$Max;$i++) {
      $row=pg_fetch_array($Ret,$i);
      $this->ad_id[$i]=$row['ad_id'];
      $this->jft_id[$i]=$row['jft_id'];
      $this->fd_id[$i]=$row['fd_id'];
      $this->description[$i]=$row['av_text'];
      $this->libelle[$i]=$row['ad_text'];
    }
  }

  function size() {
    if ( isset ($this->ad_id))
      return sizeof($this->ad_id);
    else
      return 0;
  }
    
}
?>