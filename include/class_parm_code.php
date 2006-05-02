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
class parm_code {
  var $db; // database connection
  var $p_code; // parm_code.p_code primary key
  var $p_value; // parm_code.p_value 
  var $p_comment; // parm_code.p_comment
 // constructor
  function parm_code($p_cn,$p_id=-1) 
    {
      $this->db=$p_cn;
      $this->p_code=$p_id;
      if ( $p_id != -1 )
	$this->Get();
    }
/* function LoadAll
 **************************************************
 * Purpose : 
 *  Load all parmCode
 *  return an array of parm_code object
 *        
 * parm : 
 *	- none
 * gen :
 *	-
 * return: array
 */

  function LoadAll() {
    $sql="select * from parm_code order by p_code";
    $Res=ExecSql($this->db,$sql);
    $r= pg_fetch_all($Res);
    $idx=0;
    $array=array();

    if ( $r === false ) return null;
    foreach ($r as $row )
      {
	$o=new parm_code($this->db,$row['p_code']);
	$array[$idx]=$o;
	$idx++;
      }
    
    return $array;
  }
  /* function Save
 **************************************************
 * Purpose : update a parm_object into the database
 *        p_code is _not_ updatable
 * parm : 
 *	- none
 * gen :
 *	- none
 * return:
 *     nothing
 */
  function Save() 
    {
      // if p_code=="" nothing to save
      if ( $this->p_code== -1) return;
      $this->p_comment=FormatString($this->p_comment);
      $this->p_value=FormatString($this->p_value);
      $this->p_code=FormatString($this->p_code);
      $sql="update parm_code set ".
	"p_comment='".$this->p_comment."'  ".
	",p_value='".$this->p_value."'  ".
	"where p_code='".$this->p_code."'";
      $Res=ExecSql($this->db,$sql);
    }
/* function Display
 **************************************************
 * Purpose : Display an object, with the <TD> tag
 *        
 * parm : 
 *	- none
 * gen :
 *	- none
 * return:
 *     string
 */
  function Display() 
    {
      $r="";
      $r.= '<TD>'.$this->p_code.'</TD>';
      $r.= '<TD>'.$this->p_comment.'</TD>';
      $r.= '<TD>'.$this->p_value.'</TD>';

      return $r;
    }
/* function Input
 **************************************************
 * Purpose : Display a form to enter info about
 *        a parm_code object with the <TD> tag
 *    
 * parm : 
 *	- none
 * gen :
 *	- none
 * return: string
 */
  function Input() 
    {
      $comment=new widget("text");
      $comment->name='p_comment';
      $comment->value=$this->p_comment;
      $value=new widget("text");
      $value->name='p_value';
      $value->value=$this->p_value;
      $poste=new widget("text");
      $poste->SetReadOnly(true);
      $poste->name='p_code';
      $poste->value=$this->p_code;
      $r="";
      $r.= '<TD>'.$poste->IOValue().'</TD>';
      $r.= '<TD>'.$comment->IOValue().'</TD>';
      $r.= '<TD>'.$value->IOValue().'</TD>';

      return $r;
      
    }

/* function Get
 **************************************************
 * Purpose : 
 * Complete a parm_code object thanks the p_code 

 *        
 * parm : 
 *	- none
 * gen :
 *	-
 * return: array
 */

  function Get() {
    if ( $this->p_code == -1 ) return "p_code non initialisé";
    $sql=sprintf("select * from parm_code where p_code='%s' ",
		 $this->p_code);
    $Res=ExecSql($this->db,$sql);

    if ( pg_NumRows($Res) == 0 ) return 'INCONNU';
    $row= pg_fetch_array($Res,0);
    $this->p_value=$row['p_value'];
    $this->p_comment=$row['p_comment'];

  }

}
