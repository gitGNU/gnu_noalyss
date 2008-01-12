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
 * \brief Manage the account 
 */
/*!
 * \brief Manage the account from the table tmp_pcmn
 */
require_once ('postgres.php');
require_once ('class_dossier.php');
require_once ('class_widget.php');

class Acc_Account {
  var $db;          /*! \enum $db database connection */
  var $pcm_val;
  var $pcm_type;
  var $pcm_parent;
  var $pcm_lib;   
  function __construct ($p_cn,$p_id=0) {
    $this->db=$p_cn;
    $this->pcm_val=$p_id;
  }
  /*!\brief Return the name of a account
   *        it doesn't change any data member
   * \return string with the pcm_lib
   */
  function get_lib() {
    $ret=pg_exec($this->db,
		 "select pcm_lib from tmp_pcmn where
                  pcm_val=".$this->id);
      if ( pg_NumRows($ret) != 0) {
	$r=pg_fetch_array($ret);
	$this->name=$r['pcm_lib'];
      } else {
	$this->name="Poste inconnu";
      }
    return $this->name;
  }
  /*!\brief Get all the value for this object from the database
   *        the data member are set 
   * \return false if this account doesn't exist otherwise true
   */
  function load()
  {
    $ret=ExecSql($this->db,"select pcm_lib,pcm_val_parent,pcm_type from 
                              tmp_pcmn where pcm_val=".$this->id);
    $r=pg_fetch_all($ret);
    
    if ( ! $r ) return false;
    $this->pcm_lib=$r[0]['pcm_lib'];
    $this->pcm_val_parent=$r[0]['pcm_val_parent'];
    $this->pcm_type=$r[0]['pcm_type'];
    return true;
    
  }
  function form($p_table=true){
    $array=array(
		 array('label'=>'Actif','value'=>'ACT'),
		 array('label'=>'Passif','value'=>'PAS'),
		 array('label'=>'Actif c. inverse','value'=>'ACTINV'),
		 array('label'=>'Passif c.inverse','value'=>'PASINV'),
		 array('label'=>'Produit','value'=>'PRO'),
		 array('label'=>'Charge','value'=>'CHA'),
		 array('label'=>'Non defini','value'=>'CON')
		 );
    $wType=new widget("select");
    $wType->name='p_type';
    $wType->value=$array;
    
    if ( ! $p_table ) {
      $ret='    <TR>
<TD>
<INPUT TYPE="TEXT" NAME="p_val" SIZE=7>
</TD>
<TD>
<INPUT TYPE="TEXT" NAME="p_lib" size=50>
</TD>
<TD>
<INPUT TYPE="TEXT" NAME="p_parent" size=5>
</TD>
<TD>';

      $ret.=$wType->IOValue().'</TD>';
      return $ret;
    }
    else {
      $ret='<TABLE><TR>';
      $ret.=sprintf ('<TD>Numéro de classe </TD><TD><INPUT TYPE="TEXT" name="p_val" value="%s"></TD>',$this->pcm_val);
      $ret.="</TR><TR>";
      $ret.=sprintf('<TD>Libellé </TD><TD><INPUT TYPE="TEXT" size="70" NAME="p_lib" value="%s"></TD>',urldecode($this->pcm_lib));
      $ret.= "</TR><TR>";
      $ret.=sprintf ('<TD>Classe Parent</TD><TD><INPUT TYPE="TEXT" name="p_parent" value="%s"></TD>',$this->pcm_val_parent);
      $ret.='</tr><tr>';
      $wType->selected=$this->pcm_type;
      $ret.="<td> Type de poste </td>";
      $ret.= '<td>'.$wType->IOValue().'</td>';
      $ret.="</TR> </TABLE>";
      $ret.=dossier::hidden();
      return $ret;
    }
  }
  /*!\brief for developper only during test */
 static function test_me() {
     $cn=DbConnect(dossier::id());

 }
}
