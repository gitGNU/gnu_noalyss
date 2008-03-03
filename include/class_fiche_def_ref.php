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
/*! \file
 * \brief fiche_def_ref, a fiche is owned by fiche_def which is owned by 
 *        fiche_def_ref
 */
/*! 
 * \brief fiche_def_ref, a fiche is owned by fiche_def which is owned by 
 *        fiche_def_ref
 */

class fiche_def_ref 
{
  var $frd_id;           /*!< $frd_id fiche_def_ref.frd_id */
  var $frd_text;         /*!< $frd_text fiche_def_ref.frd_tex */
  var $frd_class_base;   /*!< fiche_def_ref.frd_class_base */
  var $attribut;         /*!< array which containing list of attr */
                         /* it is used with dynamic variables */

  function fiche_def_ref($p_cn,$p_frd_id=-1)
    {
      $this->db=$p_cn;
      $this->frd_id=$p_frd_id;
      $this->attribut=array('frd_id','frd_text','frd_class_base');
    }
/*! 
 **************************************************
 * \brief  Load all the fiche_def_ref data and
 *           return an array of  fiche_def_ref objects
 *        
 * \return array
 */
  function LoadAll()
    {
      $sql="select * from fiche_def_ref order by frd_id";
      $Res=ExecSql($this->db,$sql);
      $all=pg_fetch_all($Res);
      if ( $Res == false ) return array();
      $idx=0;
      $array=array();
      foreach ($all as $row) 
	{
	  $o=new fiche_def_ref($this->db);

	  foreach (  $this->attribut as  $value)
	    {
	      $o->$value=$row[$value];
	    }
	  $array[$idx]=$o;
	  $idx++;
	}
      return $array;
    }
/*! 
 **************************************************
 * \brief  Display data between <TD> tag
 *        
 * \return string
 */
  function Display() 
    {
      $r="";
      foreach ( $this->attribut as $value) 
	{
	  $r.="<TD>".$this->$value.'</TD>';
	}
      return $r;
    }
/*! 
 **************************************************
 * \brief  Input Data between <td> tag
 *        
 * \return string
 */
  function Input()
    {
      $r="";
      $h=new widget('hidden');
      $r.='<li>Id          :'.$h->IOValue('frd_id',$this->frd_id).$this->frd_id."</li>";
      $w=new widget("text");
      $r.='<li>Commentaire  :'.$w->IOValue('frd_text',$this->frd_text)."</li>";
      $t=new widget('text');
      $r.='<li>Poste de base:'.$t->IOValue('frd_class_base',$this->frd_class_base)."</li>";

      return $r;
    }
/*! 
 **************************************************
 * \brief  Store data into the database: update the 
 *           record
 *        
 *	-
 * \return none
 */
  function Save()
    {
      $sql="update fiche_def_ref set frd_text='".
	$this->frd_text."' ,  frd_class_base='".$this->frd_class_base."'".
	" where frd_id=".$this->frd_id;
      $Res=ExecSql($this->db,$sql);
    }
/*! 
 **************************************************
 * \brief  Get the data with the p_code and complete
 *           the current object
 *        
 * \return none
 */
  function Get()
    {
      $sql="select * from  fiche_def_ref ".
	" where frd_id=".$this->frd_id;
      $Res=ExecSql($this->db,$sql);
      if ( pg_NumRows($Res) == 0 ) return null;
      $r=pg_fetch_array($Res,0);
      $this->frd_text=$r['frd_text'];
      $this->frd_class_base=$r['frd_class_base'];
    }

}
?>
