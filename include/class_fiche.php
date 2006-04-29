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
include_once("class_attribut.php");

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
      $t = new Attribut($row['ad_id']);
      $t->ad_text=$row['ad_text'];
      $this->attribut[$i]=$t;
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
/* function HasAttribute
 **************************************************
 * Purpose : Check in vw_fiche_def if a fiche has 
 *           a attribut X
 *        
 * parm : 
 *	- attribut to check
 * gen :
 *	- none
 * return:  true or false
 */
 function HasAttribute($p_attr) {
   return (CountSql($this->cn,"select * from vw_fiche_def where ad_id=$p_attr and fd_id=".$this->id)>0)?true:false;

 }
/* function Display
 **************************************************
 * Purpose : Display a fiche_def object into a table
 *        
 * parm : 
 *	- none
 * gen :
 *	-
 * return: string
 */
 function Display() 
   {

     $r=sprintf("<TD>%s</TD>",$this->id);
     $r.=sprintf("<TD>%s</TD>",$this->label);
     $r.=sprintf("<TD>%s</TD>",$this->class_base);
     $r.=sprintf("<TD>%s</TD>",$this->fiche_def);
     return $r;
   }

}

////////////////////////////////////////////////////////////////////////////////
// class fiche
////////////////////////////////////////////////////////////////////////////////
class fiche {
  var $cn;// database connection
  var $id; // fiche.f_id
  var $fiche_def;		// fd_id
  var $attribut;		// array of attribut object
  function fiche($p_cn,$p_id=0) {
    $this->cn=$p_cn;
    $this->id=$p_id;
  }

  function getAttribut() {
    if ( $this->id == 0){
      return;
    }
     $sql="select * 
           from jnt_fic_att_value 
               natural join fiche 
               natural join attr_value
               left join attr_def using (ad_id) where f_id=".$this->id.
       " order by ad_id";

    $Ret=ExecSql($this->cn,$sql);
    if ( ($Max=pg_NumRows($Ret)) == 0 )
      return ;
    for ($i=0;$i<$Max;$i++) {
      $row=pg_fetch_array($Ret,$i);
      $this->fiche_def=$row['fd_id'];
      $t=new Attribut ($row['ad_id']);
      $t->ad_text=$row['ad_text'];
      $t->av_text=$row['av_text'];
      $this->attribut[$i]=$t;
    }
    $e=new Fiche_def($this->cn,$this->fiche_def);
    $e->GetAttribut();

    if ( sizeof($this->attribut) != sizeof($e->attribut ) ) {

      // !!! Missing attribute
      foreach ($e->attribut as $f ) {
	$flag=0;
	foreach ($this->attribut as $g ) {
	  if ( $g->ad_id == $f->ad_id ) 
	    $flag=1;
	}
	if ( $flag == 0 ) { 
	  // there's a missing one, we insert it
	  $t=new Attribut ($f->ad_id);
	  $t->av_text="";
	  $t->ad_text=$f->ad_text;
	  $this->attribut[$Max]=$t;
	  $Max++;
	} // if flag == 0

      }// foreach 
      $this->attribut=SortAttributeById($this->attribut);


    }//missing attribut
  }

  function size() {
    if ( isset ($this->ad_id))
      return sizeof($this->ad_id);
    else
      return 0;
  }
  function GetByType($p_fd_id) {
     $sql="select * 
           from
               fiche 
            where fd_id=".$p_fd_id;


    $Ret=ExecSql($this->cn,$sql);
    if ( ($Max=pg_NumRows($Ret)) == 0 )
      return ;
    $all[0]=new fiche($this->cn);

    for ($i=0;$i<$Max;$i++) {
      $row=pg_fetch_array($Ret,$i);
      $t=new fiche($this->cn,$row['f_id']);
      $t->getAttribut();
      $all[$i]=$t;

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