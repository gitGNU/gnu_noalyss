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
class fiche_def {
  var $cn;           // database connection
  var $id;			// id (fiche_def.fd_id
  var $label;			// fiche_def.fd_label
  var $class_base;		// fiche_def.fd_class_base
  var $fiche_def;		// fiche_def.frd_id = fiche_def_ref.frd_id
  var $create_account;		// fd_create_account: flag
  var $all;
  var $attribut;		// get from attr_xxx tables
  function fiche_def($p_cn,$p_id = 0) {
    $this->cn=$p_cn;
    $this->id=$p_id;
  }

/* function GetAttribut
 **************************************************
 * Purpose : Get attribut of a fiche_def
 *        
 * parm : 
 *	- none
 * gen :
 *	- none
 * return:
 * none
 */
  function GetAttribut() {
    $sql="select * from jnt_fic_attr ".
      " natural join attr_def where fd_id=".$this->id.
      " order by ad_id";

    $Ret=ExecSql($this->cn,$sql);

    if ( ($Max=pg_NumRows($Ret)) == 0 )
      return ;
    for ($i=0;$i < $Max;$i++) {
      $row=pg_fetch_array($Ret,$i);
      $this->attribut[$i]=$row['ad_text'];
    }
  }

 /* function Get
 **************************************************
 * Purpose : Get attribut of the fiche_def
 *        
 * parm : 
 *	- none
 * gen :
 *	- none
 * return:
 *       none
 */
  function Get() {
    if ( $this->id == 0 ) 
      return 0;
    $sql="select * from fiche_def ".
      " where fd_id=".$this->id;
    $Ret=ExecSql($this->cn,$sql);
    if ( ($Max=pg_NumRows($Ret)) == 0 )
      return ;
    $row=pg_fetch_array($Ret,0);
    $this->label=$row['fd_label'];
    $this->class_base=$row['fd_class_base'];
    $this->fiche_def=$row['frd_id'];
    $this->create_account=$row['fd_create_account'];
  }
/* function GetAll
 **************************************************
 * Purpose : Get all the fiche_def
 *        
 * parm : 
 *	- none
 * gen :
 *	- none
 * return: an array of fiche_def object 
 */
 function GetAll() {
   $sql="select * from fiche_def ";

    $Ret=ExecSql($this->cn,$sql);
    if ( ($Max=pg_NumRows($Ret)) == 0 )
      return ;

    for ( $i = 0; $i < $Max;$i++) {
      $row=pg_fetch_array($Ret,$i);
      $this->all[$i]=new fiche_def($this->cn,$row['fd_id']);
      $this->all[$i]->label=$row['fd_label'];
      $this->all[$i]->class_base=$row['fd_class_base'];
      $this->all[$i]->fiche_def=$row['frd_id'];
      $this->all[$i]->create_account=$row['fd_create_account'];
    }
  }

}

////////////////////////////////////////////////////////////////////////////////
// class fiche
////////////////////////////////////////////////////////////////////////////////
class fiche {
  var $cn;// database connection
  var $id; // fiche.f_id
  var $fiche_def;		// fd_id
  var $attribut_id;		// .ad_id
  var $attribut_def;		// ad_text
  var $attribut_value;		// av_text
  function fiche($p_cn,$p_id=0) {
    $this->cn=$p_cn;
    $this->id=$p_id;
  }

  function getAttribut() {
    if ( $this->id == 0){
      return;
    }
//     $sql="select * 
//           from jnt_fic_att_value 
//               natural join fiche 
//               natural join attr_value
//               left join attr_def on (attr_def.ad_id=attr_value) where f_id=".$this->id;

    $sql="select *
           from
           ( select * from fiche_def natural join jnt_fic_attr natural join attr_def where fd_id = (select fd_id from fiche where f_id=$this->id))
  as w
 full join ( select * from jnt_fic_att_value where f_id=$this->id ) as a on  (w.ad_id=a.ad_id)
order by f_id,w.ad_id"
;

    $Ret=ExecSql($this->cn,$sql);
    if ( ($Max=pg_NumRows($Ret)) == 0 )
      return ;
    for ($i=0;$i<$Max;$i++) {
      $row=pg_fetch_array($Ret,$i);
      $this->fiche_def=$row['fd_id'];
      $this->attribut_id=$row['ad_id'];
      $this->attribut_value=$row['av_text'];
      $this->attribut_def=$row['ad_text'];
    }
  }

  function size() {
    if ( isset ($this->ad_id))
      return sizeof($this->ad_id);
    else
      return 0;
  }
  function GetByType($p_fd_id) {
  //   $sql="select * 
//           from jnt_fic_att_value 
//               natural join fiche 
//               natural join attr_value natural 
//               join attr_def where fd_id=".$p_fd_id.
//       " order by f_id,ad_id";
    $sql="select *
           from
           ( select * from 
                       fiche_def 
                      natural join jnt_fic_attr 
                      natural join attr_def 
          where fd_id = $p_fd_id
           )  as w
           right join 
               ( select * 
                 from jnt_fic_att_value 
                       inner join attr_def using (ad_id) 
                       inner join attr_value using (jft_id) 
                       join fiche using (f_id) where fd_id=$p_fd_id  
               ) as a on  (w.ad_id=a.ad_id)
           order by f_id,w.ad_id"
;

    $Ret=ExecSql($this->cn,$sql);
    if ( ($Max=pg_NumRows($Ret)) == 0 )
      return ;
    $all[0]=new fiche($this->cn);

    for ($i=0;$i<$Max;$i++) {
      $row=pg_fetch_array($Ret,$i);
      $all[$i]=new fiche($this->cn,$row['f_id']);
      $all[$i]->fiche_def=$row['fd_id'];
      $all[$i]->attribut_id=$row['ad_id'];
      $all[$i]->attribut_value=$row['av_text'];
      $all[$i]->attribut_def=$row['ad_text'];

    }
    return $all;
  }
  function ShowTable() {
    echo "<TR><TD> ".
      $this->id."</TD>".
      "<TR> <TD>".
      $this->attribut_value."</TD>".
      "<TR> <TD>".
      $this->attribut_def."</TD></TR>";
  }
}
?>