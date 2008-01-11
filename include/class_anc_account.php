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

/*!\brief contains the object for the poste_analytique (table poste_analytique)
 *
 */
require_once("class_widget.php");
require_once("postgres.php");
require_once("class_anc_plan.php");

class Anc_Account
{
  var $id; /*!<  $id is po_id */
  var $name;		/*!< po_name */
  var $pa_id;		/*!< pa_id fk to the plan_analytique(pa_id) */
  var $amount;		/*!< po_amount just an amount  */
  var $description;       /*!< po_description description of the post */
  var $db;	/*!< database	  connection*/
  var $ga_id;		/*!< FK to the table groupe analytique */
  function Anc_Account($p_db,$p_id=0)
  {
	$this->db=$p_db;
	$this->id=$p_id;
	$this->ga_id=null;
  }
  /*! \brief retrieve data from the database and
   *        fill the object 
   * \param $p_where the where clause 
   */
  private function fetch_from_db($p_where)
  {
	$sql="select po_id,
                 po_name ,
                 pa_id,
                 po_amount,
                 po_description,
                 ga_id
         from poste_analytique
          where ".
	  $p_where;

	$ret=ExecSql($this->db,$sql);
	if ( pg_NumRows($ret) == 0 )return null;
	$line=pg_fetch_array($ret);

	$this->id=$line['po_id'];
	$this->name=$line['po_name'];
	$this->pa_id=$line['pa_id'];
	$this->amount=$line['po_amount'];
	$this->description=$line['po_description'];
	$this->ga_id=$line['ga_id'];


  }
  function get_by_id()
  {
	$this->fetch_from_db("po_id=".$this->id);
  }
  /*!
   * \brief retrieve data thanks the name
   * \param $p_name name of the analytic account 
   *
   */
  function get_by_name($p_name)
  {
	$p_name=FormatString($p_name);
	if ( $p_name == null )
	  $p_name=$this->name;

	$this->fetch_from_db("po_name='".$p_name."'");
	echo "id = ".$this->id;
  }
  function add()
  {
	$this->format_data();
	if ( strlen($this->name) == 0)
	  return;
	if ( $this->ga_id == null || strlen(trim($this->ga_id)) == 0 ) 
	  $ga_id="NULL"; 
	else 
	  $ga_id="'".$this->ga_id."'";
	$sql="insert into poste_analytique (
                 po_name ,
                 pa_id,
                 po_amount,
                 po_description,
                 ga_id
                 ) values (".
	  "'".$this->name."',".
	  $this->pa_id.",".
	  $this->amount.",".
	  "'".$this->description."',".
	  $ga_id.")";
	try {
	  ExecSql($this->db,$sql);

	} catch (Exception $e) {
	  echo_debug(__FILE__,__LINE__,$e);
	  if ( DEBUG ) print_r($e);
	  echo "<p class=\"notice\">Doublon : l'enregistrement n'est pas sauve</p>";
	}
            
  }
  static function make_array_name($cn,$pa_id) {
    $a=make_array($cn,"select  po_name,po_name from poste_analytique ".
		  " where ".
		  " pa_id =".$pa_id." order by po_name ");
    return $a;
  }
  function update()
  {
	$this->format_data();
	if ( strlen($this->name) == 0)
	  return;
	$sql="update poste_analytique ".
	  " set po_name='".$this->name."',".
	  " pa_id=".$this->pa_id.",".
	  " po_amount=".$this->amount.",".
	  " po_description='".$this->description."',".
	  " ga_id='".$this->ga_id."'".
	  " where po_id=".$this->id;
	try { 
	  ExecSql($this->db,$sql);
	} catch (Exception $e) {
	  echo_debug(__FILE__,__LINE__,$e);
	  echo "<p class=\"notice\">Doublon : l'enregistrement n'est pas sauve</p>";
	}
	  
  }
  private function format_data()
  {

	$this->name=FormatString($this->name);
	$this->pa_id=FormatString($this->pa_id);
	$this->amount=FormatString($this->amount);
	if (strlen($this->amount) == 0 )
	  $this->amount=0.0;

	$this->description=FormatString($this->description);
  }
  function delete()
  {
	$sql="delete from poste_analytique where po_id=".$this->id;
	ExecSql($this->db,$sql);
  }
  /*! \brief return an array of object Poste_Analytique
   *
   */
  function get_list()
  {
	$sql="select po_id,
                 po_name ,
                 pa_id,
                 po_amount,
                 po_description,
                 ga_id
         from poste_analytique ".
	  "   order by po_name";

	$ex=ExecSql($this->db,$sql);
	$ret=pg_fetch_all($ex);
	if ( $ret  == null )
	  return null;

	$array=array();
	foreach ($ret as $line)
	  {
		$objet=new Poste_Analytique($this->db);

		$object->id=$line['po_id'];
		$object->name=$line['po_name'];
		$object->pa_id=$line['pa_id'];
		$object->amount=$line['po_amount'];
		$object->description=$line['po_description'];
		$object->ga_id=$line['ga_id'];
		$array[]=clone $object;
	  }

	return $array;
  }
  function display_list()
  {
	$array=$this->get_list();
	if ( empty($array) ) { echo "Vide"; return;}
	foreach ($array as $line)
	  {
		echo $line->id." / ".$line->name." / ".$line->description."/".
		  $line->amount." / ".$line->pa_id."/".$line->ga_id."<br>";
	  }
  }
  function debug()
  {
	echo "id ".$this->id."<br>";
	echo "name ".$this->name."<br>";
	echo "pa_id ".$this->pa_id."<br>";
	echo "amount ".$this->amount."<br>";
	echo "description ".$this->description."<br>";
	echo "ga_id ".$this->ga_id."<br>";
  }
  function form()
  {
	$wId=new widget("hidden","po_id","po_id",$this->id);
	$wName=new widget("text","Nom","po_name",$this->name);
	$wPa_id=new widget("hidden","pa_id","pa_id",$this->pa_id);
	$wAmount=new widget("text","Montant","po_amount",$this->amount);
	$wDescription=new widget("text","Description","po_description",$this->description);
	$wGa_id=new widget("select","Groupe","ga_id");
	$wGa_id->value=make_array($this->db,"select ga_id,ga_id from groupe_analytique",1);
	$wGa_id->selected=$this->ga_id;
	$wGa_id->table=1;
	$pa=new Anc_Plan($this->db,$this->pa_id);
	$pa->get();
	$wPaName=new widget("text","Plan A.","",$pa->name);
	$wPaName->table=1;
	$wPaName->readonly=true;

	$wName->table=1;
	$wPa_id->table=1;
	$wAmount->table=1;
	$wDescription->table=1;

	$r=$wId->IOValue();
	$r.=$wPa_id->IOValue();

	$r.="<table>";

	$r.="<tr>";
	$r.=$wName->IOValue();
	$r.="</tr>";

	$r.="<tr>";
	$r.=$wAmount->IOValue();
	$r.="</tr>";


	$r.="<tr>";
	$r.=$wDescription->IOValue();
	$r.="</tr>";

	$r.="<tr>";
	$r.=$wPaName->IOValue();
	$r.="</tr>";

	$r.="<tr>";
	$r.=$wGa_id->IOValue();
	$r.="</tr>";

	$r.="</table>";
	return $r;

  }
  function get_from_array($p_array)
  {
    $this->name=(isset ($p_array['po_name']))?$p_array['po_name']:"";
    $this->description=(isset ($p_array['po_description']))?$p_array['po_description']:"";
    $this->pa_id=(isset ($p_array['pa_id']))?$p_array['pa_id']:"";
    $this->amount=(isset ($p_array['po_amount']))?$p_array['po_amount']:0;
    $this->id=(isset ($p_array['po_id']))?$p_array['po_id']:-1;
    //    $this->ga_id=(isset($p_array['ga_id']) && $p_array['ga_id'] == "-1" )?null:2;
    $this->ga_id=(isset($p_array['ga_id']) && $p_array['ga_id'] != "-1" )?$p_array['ga_id']:null;
  }
  static function test_me() {
    $cn=DbConnect(dossier::id());
    $pa_id=getDBValue($cn,"select max(pa_id) from plan_analytique");
    $o=new Poste_Analytique($cn);
    echo "<h1>Poste_Analytique</h1>";
    echo "<h2>get_list</h2>";
    $ee=$o->get_list();
    print_r($ee);
    //var_dump($ee);

    echo "<h2>Add some </h2>";
    $o->pa_id=$pa_id;
    $o->name="test1";
    $o->add();


    $o->name="test2";
    $o->add();

    $o->name="test3";
    $o->add();

    $o->name="test4";
    $o->add();

    $o->name="test5";
    $o->add();

    echo "<h2> remove test1</h2>";
    $o->get_by_name("test1");
    $o->delete();
    $o->display_list();

    $o->get_by_name("test4");
    echo "<hr>".$o->id."<hr>";
    $o->name="Test Four";
    $o->update();
    $o->display_list();
    $o->delete();
    $o->display_list();
  }
}
?>
