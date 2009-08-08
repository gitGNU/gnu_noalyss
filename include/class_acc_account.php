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
require_once("class_iselect.php");
require_once ('class_database.php');
require_once ('class_dossier.php');

class Acc_Account {
  var $db;          /*!< $db database connection */
  static private $variable = array("value"=>'pcm_val',
				'type'=>'pcm_type',
				'parent'=>'pcm_val_parent',
				'libelle'=>'pcm_lib');
  private  $pcm_val;
  private  $pcm_type;
  private  $pcm_parent;
  private  $pcm_lib;   
  static private $type=array(
		 array('label'=>'Actif','value'=>'ACT'),
		 array('label'=>'Passif','value'=>'PAS'),
		 array('label'=>'Actif c. inverse','value'=>'ACTINV'),
		 array('label'=>'Passif c.inverse','value'=>'PASINV'),
		 array('label'=>'Produit','value'=>'PRO'),
		 array('label'=>'Produit Inverse','value'=>'PROINV'),
		 array('label'=>'Charge','value'=>'CHA'),
		 array('label'=>'Charge Inverse','value'=>'CHAINV'),
		 array('label'=>'Non defini','value'=>'CON')
		 );

  function __construct ($p_cn,$p_id=0) {
    $this->db=$p_cn;
    $this->pcm_val=$p_id;
  }
  public function get_parameter($p_string) {
    if ( array_key_exists($p_string,self::$variable) ) {
      $idx=self::$variable[$p_string];
      return $this->$idx;
    }
    else 
      exit (__FILE__.":".__LINE__.'Erreur attribut inexistant');
  }
  
  function set_parameter($p_string,$p_value) {
  if ( array_key_exists($p_string,self::$variable) ) {
      $idx=self::$variable[$p_string];
      if ($this->check($idx,$p_value) == true )      $this->$idx=$p_value;
    }
    else 
      exit (__FILE__.":".__LINE__.'Erreur attribut inexistant');
    
    
  }
  /*!\brief Return the name of a account
   *        it doesn't change any data member
   * \return string with the pcm_lib
   */
  function get_lib() {
    $ret=$this->db->exec_sql(
		 "select pcm_lib from tmp_pcmn where
                  pcm_val=$1",array($this->pcm_val));
      if ( pg_NumRows($ret) != 0) {
	$r=pg_fetch_array($ret);
	$this->pcm_lib=$r['pcm_lib'];
      } else {
	$this->pcm_lib="Poste inconnu";
      }
    return $this->pcm_lib;
  }
  /*!\brief Check that the value are valid 
   *\return true if all value are valid otherwise false
   */
  function check ($p_member='',$p_value='')
  {
	// if there is no argument we check all the member
	if ($p_member == '' && $p_value== '' ) {
		foreach (self::$variable as $l=>$k) {
			$this->check($k,$this->$k);
		}
	} else {
	// otherwise we check only the value
		if ( strcmp ($p_member,'pcm_val') == 0 ) {
		  if (is_numeric($p_value) ==0 )
		    throw new Exception('Poste comptable incorrect '.$p_value);
		  else
		    return true;
		} else if ( strcmp ($p_member,'pcm_val_parent') == 0 ) {
		  if ( is_numeric($p_value) == 0 || ($this->count($p_value) == 0 && $p_value !=0))
		    throw new Exception('Poste comptable parent incorrect '.$p_value);
		  else 
		    return true;
		} else if ( strcmp ($p_member,'pcm_lib') == 0 ) {
			return true;
		} else if ( strcmp ($p_member,'pcm_type') == 0 ) {
			foreach (self::$type as $l=>$k) {
				if ( strcmp ($k['value'],$p_value) == 0 ) return true;

			}
			throw new Exception('type de compte incorrect '.$p_value);
		}	
		throw new Exception ('Donnee member inconnue '.$p_member);
	}
	
  }
  /*!\brief Get all the value for this object from the database
   *        the data member are set 
   * \return false if this account doesn't exist otherwise true
   */
  function load()
  {
    $ret=$this->db->exec_sql("select pcm_lib,pcm_val_parent,pcm_type from 
                              tmp_pcmn where pcm_val=".$this->pcm_val);
    $r=pg_fetch_all($ret);
    
    if ( ! $r ) return false;
    $this->pcm_lib=$r[0]['pcm_lib'];
    $this->pcm_val_parent=$r[0]['pcm_val_parent'];
    $this->pcm_type=$r[0]['pcm_type'];
    return true;
    
  }
  function form($p_table=true){
    $wType=new ISelect();
    $wType->name='p_type';
    $wType->value=self::$type;
    
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

      $ret.=$wType->input().'</TD>';
      return $ret;
    }
    else {
      $ret='<TABLE><TR>';
      $ret.=sprintf ('<TD>Numéro de classe </TD><TD><INPUT TYPE="TEXT" name="p_val" value="%s"></TD>',$this->pcm_val);
      $ret.="</TR><TR>";
      $ret.=sprintf('<TD>Libellé </TD><TD><INPUT TYPE="TEXT" size="70" NAME="p_lib" value="%s"></TD>',h($this->pcm_lib));
      $ret.= "</TR><TR>";
      $ret.=sprintf ('<TD>Classe Parent</TD><TD><INPUT TYPE="TEXT" name="p_parent" value="%s"></TD>',$this->pcm_val_parent);
      $ret.='</tr><tr>';
      $wType->selected=$this->pcm_type;
      $ret.="<td> Type de poste </td>";
      $ret.= '<td>'.$wType->input().'</td>';
      $ret.="</TR> </TABLE>";
      $ret.=dossier::hidden();

      return $ret;
    }
  }
  function count($p_value) {
    $sql="select count(*) from tmp_pcmn where pcm_val=$1";
    return $this->db->get_value($sql,array($p_value));
  }
  /*!\brief for developper only during test */
 static function test_me() {
     $cn=new Database(dossier::id());

 }
  function update($p_old) {
    $this->pcm_lib=substr($this->pcm_lib,0,150);
    $this->check();
    $sql="update tmp_pcmn set pcm_val=$1, pcm_lib=$2,pcm_val_parent=$3,pcm_type=$4 where pcm_val=$5";
    $Ret=$this->db->exec_sql($sql,array($this->pcm_val,
					   $this->pcm_lib,
					   $this->pcm_val_parent,
					   $this->pcm_type,
					   $p_old));
  }
}
