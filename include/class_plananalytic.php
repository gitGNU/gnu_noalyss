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

/* !\file 
 */

/* \brief
 *  Concerns the Analytic class
 */
require_once("constant.php");
require_once("postgres.php");
require_once("class_widget.php");
require_once("class_poste_analytic.php");

class PlanAnalytic
{
  var $db; /*!<database connection */
  var $name; 					/*!< name plan_analytique.pa_name */
  var $description;				/*!< description of the PA plan_analytique.pa_description*/
  var $id;						/*!< id = plan_analytique.pa_id */

  function PlanAnalytic($p_cn,$p_id=0)
  {
	$this->db=$p_cn;
	$this->id=$p_id;
	$this->name="";
	$this->description="";
	$this->get();
  }
  /*!\brief get the list of all existing PA 
   * \return an array of PA (not object)
   *
   */
  function get_list()
  {
	$array=array();
	$sql="select pa_id as id,pa_name as name,".
	  "pa_description as description from plan_analytique order by pa_name";
	$ret=ExecSql($this->db,$sql);
	$array=pg_fetch_all($ret);
	return $array;
  }

  function get()
  {
	if ( $this->id==0) return;

	$sql="select pa_name,pa_description from plan_analytique where pa_id=".$this->id;
	$ret= ExecSql($this->db,$sql);
	if ( pg_NumRows($ret) == 0)
	  {
		return;
	  }
	$a=  pg_fetch_array($ret,0);
	$this->name=$a['pa_name'];
	$this->description=$a['pa_description'];

  }

  function delete()
  {
	if ( $this->id == 0 ) return;
	ExecSql($this->db,"delete from plan_analytique where pa_id=".$this->id);
  }

  function update()
  {
	if ( $this->id==0) return;
	$name=FormatString($this->name);
	if ( strlen($name) == 0)
	  return;

	$description=FormatString($this->description);
	ExecSql($this->db,"update plan_analytique set pa_name='".$name."'".
			", pa_description='".$description."'".
			" where pa_id=".$this->id);
  }

  function add()
  {
	$name=FormatString($this->name);
	if ( strlen($name) == 0)
	  return;
	if ( $this->isAppend() == false) return;
	$description=FormatString($this->description);
	ExecSql($this->db,"insert into plan_analytique(pa_name,pa_description)".
			" values (".
			"'".$name."',".
			"'".$description."')");
	$this->id=getSequence($this->db,'plan_analytique_pa_id_seq');

  }
  function form()
  {

	$wName=new widget('TEXT','Nom','pa_name',$this->name);
	$wName->table=1;
	$wDescription=new widget('TEXT','Description','pa_description',$this->description);
	$wDescription->table=1;
	$wId=new widget("HIDDEN","pa_id",$this->id);
	$ret="<TABLE>";
	$ret.='<tr>'.$wName->IOValue().'</tr>';
	$ret.="<tr>".$wDescription->IOValue()."</tr>";
	$ret.="</table>";
	$ret.=$wId->IOValue();
	return $ret;
  }
  function isAppend()
  {
	$count=getDbValue($this->db,"select count(pa_id) from plan_analytique");

	if ( $count > 4)
	  return false;
	else
	  return true;
  }
  /*!\brief get all the poste related to the current
   *        Analytic plan 
   * \return an array of Poste_analytic object
   */
  function get_poste_analytique()
  {
	$sql="select po_id from poste_analytique where pa_id=".$this->id;
	$r=ExecSql($this->db,$sql);
	$ret=array();
	if ( pg_NumRows($r) == 0 ) 
	  return $ret;
	
	$all=pg_fetch_all($r);
	foreach ($all as $line)
	  {
		$obj=new Poste_analytique($this->db,$line['po_id']);
		$obj->get_by_id();
		$ret[]=clone $obj;
	  }
	return $ret;
  }
}
?>
