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
 * \brief Create, view, modify and parse report
 */

require_once('class_rapport_row.php');
require_once('class_widget.php');

/*! 
 * \brief Class rapport  Create, view, modify and parse report
 */

class Rapport {

  var $db;    /*!< $db database connx */
  var $id;    /*!< $id formdef.fr_id */
  var $name;  /*!< $name report's name */
  var $aRapport_row;		/*!< array of rapport_row */
  var $nb;
  /*!\brief  Constructor */
  function __construct($p_cn,$p_id=0) {
    $this->db=$p_cn;
    $this->id=$p_id;
    $this->name='Nouveau';
    $this->aRapport_row=null;
  }
  /*!\brief Return the report's name
   */
  function get_name() {
    $ret=execSql($this->db,"select fr_label from formdef where fr_id=".$this->id);
    if (pg_NumRows($ret) == 0) return $this->name;
    $a=pg_fetch_array($ret,0);
    $this->name=$a['fr_label'];
    return $this->name;
  }
  /*!\brief return all the row and parse formula
   *        from a report
   * \param $p_start start periode
   * \param $p_end end periode
   * \param $p_type_date type of the date : periode or calendar
   */
 
  function get_row($p_start,$p_end,$p_type_date) {

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
			      $l_line['fo_formula'],
			      $p_start,
			      $p_end,
			      true,
			      $p_type_date
			      );
     
    } //for ($i
    $this->row=$col;
    return $col;
  }
/*! 
 * \brief  Display a form for encoding a new report or update one
 * 
 * \param $p_line number of line
 *
 */ 
function form($p_line=0) {
  $search_poste=new widget('JS_SEARCH_POSTE');
  $search_poste->extra='not';
  $r="";
  if ($p_line == 0 ) $p_line=count($this->aRapport_row);
  $r.= dossier::hidden();
  $r.= widget::hidden('line',$p_line);
  $r.= widget::hidden('fr_id',$this->id);
  $wForm=new widget("text");
  $r.="Nom du rapport : ";
  $r.=$wForm->IOValue('form_nom',$this->name);

  $r.= '<TABLE id="rap1">';
  $r.= "<TR>";
  $r.= "<TH> Position </TH>";
  $r.= "<TH> Texte </TH>";
  $r.= "<TH> Formule</TH>";

  $r.= '</TR>';
  $wName=new widget("text");
  $wName->size=50;
  $wPos=new widget("text");
  $wPos->size=3;
  $wForm=new widget("text");
  $wForm->size=35;
  for ( $i =0 ; $i < $p_line;$i++) {

    $r.= "<TR>";
    
    $r.= "<TD>";
    $wPos->value=( isset($this->aRapport_row[$i]->fo_pos))?$this->aRapport_row[$i]->fo_pos:$i+1;
    $r.=$wPos->IOValue("pos".$i);
    $r.= '</TD>';
    

    $r.= "<TD>";
    $wName->value=( isset($this->aRapport_row[$i]->fo_label))?$this->aRapport_row[$i]->fo_label:"";
    $r.=$wName->IOValue("text".$i);
    $r.= '</TD>';

    $r.= "<TD>";
    $wForm->value=( isset($this->aRapport_row[$i]->fo_formula))?$this->aRapport_row[$i]->fo_formula:"";
    $r.=$wForm->IOValue("form".$i);

    $r.= '</TD>';

    $r.= "</TR>";
    }

  $r.= "</TABLE>";
  $wButton=new widget("button");
  $wButton->javascript=' rapport_add_row();';
  $wButton->label="Ajout d'une ligne";
  $r.=$wButton->IOValue();
  return $r;

}
  /*!\brief save into form and form_def
   */
  function save() {

    if ( strlen(trim($this->name)) == 0 )
      return;
    if ( $this->id == 0 )
      $this->insert();
    else 
      $this->update();

  }
  function insert() {
    try {
      startSql($this->db);
      $ret_sql=ExecSqlParam($this->db,
			 "insert into formdef (fr_label) values($1) returning fr_id",
			 array($this->name)
			 );
      $this->id=pg_fetch_result($ret_sql,0,0);
      $ix=1;
      foreach ( $this->aRapport_row as $row) {
	if ( strlen(trim($row->get_parameter("name"))) != 0 && 
	     strlen(trim($row->get_parameter("formula"))) != 0 ) 
	  {
	    $ix=($row->get_parameter("position")!="")?$row->get_parameter("position"):$ix;
	    $row->set_parameter("position",$ix);
	    $ret_sql=ExecSqlParam($this->db,
				  "insert into form (fo_fr_id,fo_pos,fo_label,fo_formula)".
				  " values($1,$2,$3,$4)",
				  array($this->id,
					$row->fo_pos,
					$row->fo_label,
					$row->fo_formula)
				  );
	  }
      }
			      
    } catch (Exception $e) {
      Rollback($this->db);
      echo $e->getMessage();
    }
    Commit($this->db);

  }
  function update() {
    try {
      startSql($this->db);
      $ret_sql=ExecSqlParam($this->db,
			    "update formdef set fr_label=$1 where fr_id=$2",
			    array($this->name,$this->id));
      $ret_sql=ExecSqlParam($this->db,
			    "delete from form where fo_fr_id=$1",
			    array($this->id));
      foreach ( $this->aRapport_row as $row) {
	if ( strlen(trim($row->get_parameter("name"))) != 0 && 
	     strlen(trim($row->get_parameter("formula"))) != 0 ) 
	{
	  $ix=($row->get_parameter("position")!="")?$row->get_parameter("position"):$ix;
	  $row->set_parameter("position",$ix);
	  $ret_sql=ExecSqlParam($this->db,
				"insert into form (fo_fr_id,fo_pos,fo_label,fo_formula)".
				" values($1,$2,$3,$4)",
				array($this->id,
				      $row->fo_pos,
				      $row->fo_label,
				      $row->fo_formula)
				);
	  }
      }
			    

    }catch (Exception $e) {
      Rollback($this->db);
      echo $e->getMessage();
    }
    Commit($this->db);
  }
  /*!\brief fill a form thanks an array, usually it is $_POST
   *\param $array
   */
  function from_array($p_array) {
    $this->id=(isset($p_array['fr_id']))?$p_array['fr_id']:0;
    $this->name=(isset($p_array['form_nom']))?$p_array['form_nom']:"";
    $ix=0;

    $rr=new Rapport_Row();
    $rr->set_parameter("form_id",$this->id);
    $rr->set_parameter('database',$this->db);

    $this->aRapport_row=$rr->from_array($p_array);


  }
  /*!\brief the fr_id MUST be before called
   */


  function load() {
    $sql=ExecSqlParam($this->db,
		      "select fr_label from formdef where fr_id=$1",
		      array($this->id));
    if ( pg_NumRows($sql) == 0 ) return;
    $this->name=pg_fetch_result($sql,0,0);
    $sql=ExecSqlParam($this->db,
		      "select fo_id,fo_pos,fo_label,fo_formula ".
		      " from form ".
		      " where fo_fr_id=$1 order by fo_pos",
		      array($this->id));
    $f=pg_fetch_all($sql);
    $array=array();
    if ( ! empty($f) ) {
      foreach ($f as $r) {
	$obj=new Rapport_Row();
	$obj->set_parameter("name",$r['fo_label']);
	$obj->set_parameter("id",$r['fo_id']);
	$obj->set_parameter("position",$r['fo_pos']);
	$obj->set_parameter("formula",$r['fo_formula']);
	$obj->set_parameter('database',$this->db);
	$obj->set_parameter('form_id',$this->id);
	$array[]=clone $obj;
      }
    }
    $this->aRapport_row=$array;

  }
  function delete() {
    $ret=ExecSqlParam($this->db,
		      "delete from formdef where fr_id=$1",
		      array($this->id)
		      );
  }
  /*!\brief get a list from formdef of all defined form
   *
   *\return array of object rapport
   *
   */
  function get_list()
  {
    $sql="select fr_id,fr_label from formdef order by fr_label";
    $ret=ExecSql($this->db,$sql);
    if ( pg_NumRows($ret) == 0 ) return array();
    $array=pg_fetch_all($ret);
    $obj=array();
    foreach ($array as $row) {
      $tmp=new Rapport($this->db);
      $tmp->id=$row['fr_id'];
      $tmp->name=$row['fr_label'];
      $obj[]=clone $tmp;
    }
    return $obj;
  }
  function test_me() {
    $cn=DbConnect(dossier::id());
    $a=new Rapport($cn);
    print_r($a->get_list());
    $array=array("text0"=>"test1",
		 "form0"=>"7%",
		 "text1"=>"test2",
		 "form1"=>"6%",
		 "fr_id"=>110,
		 "form_nom"=>"Tableau"
		 );
    $a->from_array($array);
    print_r($a);
    echo '<form method="post">';
    echo $a->form(10);
    echo '<INPUT TYPE="submit" value="Enregistre" name="update">';
    /* Add a line should be a javascript see comptanalytic */
    //  $r.= '<INPUT TYPE="submit" value="Ajoute une ligne" name="add_line">';
    echo '<INPUT TYPE="submit" value="Efface ce rapport" name="del_form">';

    echo "</FORM>";
    if ( isset ($_POST['update'])) {
      $b=new Rapport($cn);
      $b->from_array($_POST);
      echo '<hr>';
      print_r($b);
    }
  }
}

?>
