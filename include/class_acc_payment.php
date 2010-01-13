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
 * \brief Handle the table mod_payment
 */
require_once("class_iselect.php");
require_once("class_icard.php");
require_once("class_ispan.php");
require_once('class_acc_ledger.php');
require_once('class_fiche.php');
require_once('class_fiche_def.php');
require_once('constant.php');
/*!\brief Handle the table mod_payment
 *\note the private data member are accessed via
  - mp_id  ==> id ( Primary key )
  - mp_lib ==> lib (label)
  - mp_type ==> type (type of the ledger ACH or VEN )
  - mp_jrn_def_id ==> ledger (Number of the ledger where to save)
  - mp_fd_id ==> fiche_def (fiche class to use)
  - mp_qcode ==> qcode (quick_code of the card)
 *
 */
class Acc_Payment
{

  private static $variable=array("id"=>"mp_id",
				 "lib"=>"mp_lib",
				 "qcode"=>"mp_qcode",
				 "type"=>"mp_type",
				 "ledger"=>"mp_jrn_def_id",
				 "fiche_def"=>"mp_fd_id");

 
  private  $mp_lib; 		
  private  $mp_qcode;  	
  private  $mp_type;		
  private  $mp_jrn_def_if;	
				
  private  $mp_fd_id;	

  function __construct ($p_cn,$p_init=0) {
    $this->cn=$p_cn;
    $this->mp_id=$p_init;
  }
  public function get_parameter($p_string) {
    if ( array_key_exists($p_string,self::$variable) ) {
      $idx=self::$variable[$p_string];
      return $this->$idx;
    }
    else 
      exit (__FILE__.":".__LINE__.'Erreur attribut inexistant');
  }
  public function set_parameter($p_string,$p_value) {
    if ( array_key_exists($p_string,self::$variable) ) {
      $idx=self::$variable[$p_string];
      $this->$idx=$p_value;
    }
    else 
      exit (__FILE__.":".__LINE__.'Erreur attribut inexistant');
    
    
  }
  public function get_info() {    return var_export(self::$variable,true);  }
  public function verify() {
    // Verify that the elt we want to add is correct
  }
  public function save() {
  /* please adapt */
    if (  $this->get_parameter("id") == 0 ) 
      $this->insert();
    else
      $this->update();
  }

  public function insert() {
    if ( $this->verify() != 0 ) return;
  }

  public function update() {
    if ( $this->verify() != 0 ) return;

    $sql="update mod_payment set mp_lib=$1,mp_qcode=$2,mp_type=$3,mp_jrn_def_id=$4,mp_fd_id=$5 ".
      " where mp_id = $6";
    $res=$this->cn->exec_sql(
		 $sql,
		 array($this->mp_lib,
		       $this->mp_qcode,
		       $this->mp_type,
		       $this->mp_jrn_def_id,
		       $this->mp_fd_id,
		       $this->mp_id)
		 );
    if ( strlen (trim($this->mp_jrn_def_id))==0)
      $this->cn->exec_sql(
		   'update mod_payment '.
		   'set mp_jrn_def_id = null where mp_id=$1',
		   array($this->mp_id));
    if ( strlen (trim($this->mp_qcode))==0)
      $this->cn->exec_sql(
		   'update mod_payment '.
		   'set mp_qcode = null where mp_id=$1',
		   array($this->mp_id));
    if ( strlen (trim($this->mp_fd_id))==0)
      $this->cn->exec_sql(
		   'update mod_payment '.
		   'set mp_fd_id = null where mp_id=$1',
		   array($this->mp_id));

  }

  public function load() {
    $sql='select mp_id,mp_lib,mp_fd_id,mp_jrn_def_id,mp_qcode,mp_type from mod_payment '.
      ' where mp_id = $1';
    $res=$this->cn->exec_sql(
		 $sql,
		 array($this->mp_id)
		 );

    if ( Database::num_row($res) == 0 ) return;
    $row=Database::fetch_array($res,0);
    foreach ($row as $idx=>$value) { $this->$idx=$value; }
  }
  public function delete() {

  }
  /*!\brief retrieve all the data for a certain type
   *\param non
   *\return an array of row
   */
  public function get_all() {
    $sql='select mp_id '.
      ' from mod_payment '.
      ' where mp_type=$1';
    $array=$this->cn->get_array($sql,array($this->mp_type));
    $ret=array();
    if ( !empty($array) ) {
      foreach ($array as $row) {
	$t=new Acc_Payment($this->cn,$row['mp_id']);
	$t->load();
	$ret[]=$t;
      }
    }
    return $ret;
  }
  /*!\brief retrieve all the data for a certain type but filter on the
   *valid record (jrn and fd not null
   *\param non
   *\return an array of row
   */
  public function get_valide() {
    $sql='select mp_id '.
      ' from mod_payment '.
      ' where mp_type=$1 and mp_jrn_def_id is not null and '.
      ' (mp_fd_id is not null or mp_qcode is not null)';
    $array=$this->cn->get_array($sql,array($this->mp_type));
    $ret=array();
    if ( !empty($array) ) {
      foreach ($array as $row) {
	$t=new Acc_Payment($this->cn,$row['mp_id']);
	$t->load();
	$ret[]=$t;
      }
    }
    return $ret;
  }

  public function row() {
    //---------------------------------------------------------------------------
    // Common variable
    $td='<TD>';$etd='</td>';$tr='<tr>';$etr='</tr>';$th='<th>';$eth='</th>';

    $r='';
    $r.=$td.$this->mp_lib.$etd;
    if ( $this->mp_fd_id != NULL && $this->mp_fd_id !=0) {
      $fiche=new Fiche_Def($this->cn,$this->mp_fd_id);
      $fiche->Get();
      $r.=$td.$fiche->label.$etd;
    }else
      $r.=$td.$etd;
    $jrn=new Acc_Ledger($this->cn,$this->mp_jrn_def_id);
    $r.=$td.$jrn->get_name().$etd;
    if ( strlen(trim($this->mp_qcode)) != 0 ) {
      $f=new Fiche($this->cn);
      $f->get_by_qcode($this->mp_qcode);
      $r.=$td.$f->strAttribut(ATTR_DEF_NAME).$etd;

    }else
      $r.=$td.$etd;
    return $r;
  }
  /*!\brief return a string with a form (into a table)
   *\param none
   *\return a html string 
   */
  public function form() {
    $td='<TD>';$etd='</td>';$tr='<tr>';$etr='</tr>';$th='<th>';$eth='</th>';
    $r='';
    $r.=HtmlInput::hidden('id',$this->mp_id);
    $r.='<table>';
    $r.=$tr.$td.'Libell&eacute;'.$etd;
    $r.=$td;
    $r.=$this->mp_lib;
    $r.=$etd.$etr;
    $r.=$tr.$td;
    $r.='Type de fiche '.$etd;
    $array=$this->cn->make_array('select fd_id,fd_label from fiche_def join fiche_def_ref '.
	' using (frd_id) where frd_id in (25,4) order by fd_label');
    $fd=new ISelect();
    $fd->name='mp_fd_id';
    $fd->value=$array;
    $fd->selected=$this->mp_fd_id;
    $r.=$td.$fd->input();
    $r.=$etd;
    $r.=$tr.$td.'Enregistre dans le journal '.$etd;
    $array=$this->cn->make_array('select jrn_def_id,jrn_def_name from '.
		      ' jrn_def where jrn_def_type = \'ODS\' or jrn_def_type=\'FIN\'');
    $jrn=new ISelect();
    $jrn->value=$array;
    $jrn->name='mp_jrn_def_id';
    $jrn->selected=(isset ($this->mp_jrn_def_id))?$this->mp_jrn_def_id:0;
    $r.=$td.$jrn->input().$etd;
    $r.=$etr.$tr;
    $r.=$td.'Avec la fiche'.$etd;
    $f=new ICard();
    $f->jrn=$jrn->selected;
    $f->noadd=true;
    $f->name='mp_qcode';
    $list=$this->cn->make_list('select fd_id from fiche_def where frd_id in (25,4)');
    $f->extra=$list;
    $f->extra2=_('Recherche');
    $f->value=(isset($this->mp_qcode))?$this->mp_qcode:'';
    $r.=$td.$f->input().$etd;
    $s=new ISpan();
    $r.=$td.$s->input('mp_qcode_label');
    $r.='</table>';
    return $r;

  }
  /*!\brief show several lines with radio button to select the payment
   *method we want to use, the $_POST['e_mp'] will be set
   *\param none
   *\return html string
   */
  public function select() {
    $r='';
    $array=$this->get_valide();
    $r.=HtmlInput::hidden('gDossier',dossier::id());
    $r.='<ol>';
    $r.='<li ><input type="radio" name="e_mp" value="0" checked>Paiement encod&eacute; plus tard';
    if ( empty($array ) == false ){
      foreach ($array as $row) {
	$f='';
	/* if the qcode is  null the propose a search button to select
	   the card */
	if ( $row->mp_qcode==NULL) { 
 	  $a=new ICard();
	  $a->jrn=$row->mp_jrn_def_id;
	  $a->extra=$row->mp_fd_id;
	  $a->name='e_mp_qcode_'.$row->mp_id;
	  $a->set_dblclick("fill_ipopcard(this);");
	  $a->set_callback('filter_card');
	  $a->set_function('fill_data');
	  $a->set_attribute('ipopup','ipopcard');
	  $a->set_attribute('label',$a->name.'_label');

	  $s=new ISpan();
	  $s->name=$a->name.'_label';
	  $f=$a->input().$s->input();
	}else {
	  /* if the qcode is not null then add a hidden variable with
	     the qcode */

	  $fiche=new fiche($this->cn);
	  $fiche->get_by_qcode($row->mp_qcode);
	  $f=HtmlInput::hidden('e_mp_qcode_'.$row->mp_id,$row->mp_qcode);

	  $f.=$fiche->strAttribut(ATTR_DEF_NAME);
	}
	$r.='<li><input type="radio" name="e_mp" value="'.$row->mp_id.'">';
	$r.=' payement par '.$row->mp_lib.' - fiche '.$f;

	/* Show in which ledger the operation will be saved */
	$jrn=new Acc_Ledger($this->cn,$row->mp_jrn_def_id);
	$r.=' dans le journal '.$jrn->get_name();
	  
      }
    }
    $r.='</ol>';
    return $r;
  }

  /*!\brief convert an array into an Acc_Payment object
   *\param array to convert
   */
  public function from_array($p_array) {
    $idx=array('mp_id','mp_lib','mp_fd_id','mp_jrn_def_id','mp_qcode','mp_type');
    foreach ($idx as $l) 
      if (isset($p_array[$l])) $this->$l=$p_array[$l];
  }
  /*!\brief test function
   */
  static function test_me() {
    echo JS_LEDGER;
    $cn=new Database(dossier::id());
    $ac=new Acc_Payment($cn);
    $ac->set_parameter('type','ACH');
    echo '<form method="post">';
	echo HtmlInput::hidden('test_select',$_REQUEST['test_select']);
    echo $ac->select();
    echo HtmlInput::submit('go','go');
    echo '</form>';
    if ( isset($_POST['go']))
      print_r($_POST);
  }
  
}


