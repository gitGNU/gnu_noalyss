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
 * \brief definition of Pre_operation
 */

/*! \brief manage the predefined operation, link to the table op_def
 * and op_def_detail
 *
 */
require_once("class_iselect.php");
require_once("class_ihidden.php");
class Pre_operation
{
    var $db;						/*!< $db database connection */
    var $nb_item;					/*!< $nb_item nb of item */
    var $p_jrn;					/*!< $p_jrn jrn_def_id */
    var $jrn_type;					/*!< $jrn_type */
    var $name;						/*!< $name name of the predef. operation */

    function Pre_operation($cn)
    {
        $this->db=$cn;
        $this->od_direct='false';
    }
    /*!\brief fill the object with the $_POST variable */
    function get_post()
    {
        $this->nb_item=$_POST['nb_item'];
        $this->p_jrn=$_REQUEST['p_jrn'];
        $this->jrn_type=$_POST['jrn_type'];

	$this->name=$_POST['opd_name'];

        $this->name=(trim($this->name)=='')?$_POST['e_comm']:$this->name;

        if ( $this->name=="")
        {
            $n=$this->db->get_next_seq('op_def_op_seq');
            $this->name=$this->jrn_type.$n;
            // common value
        }
    }
    function delete ()
    {
        $sql="delete from op_predef where od_id=".$this->od_id;
        $this->db->exec_sql($sql);
    }
    /*!\brief save the predef check first is the name is unique
     * \return true op.success otherwise false
     */
    function save()
    {

        if (	$this->db->count_sql("select * from op_predef ".
                                  "where upper(od_name)=upper('".Database::escape_string($this->name)."')".
                                  "and jrn_def_id=".$this->p_jrn)
                != 0 )
        {
            echo "<span class=\"notice\"> Ce modèle d' op&eacute;ration a d&eacute;j&agrave; &eacute;t&eacute; sauv&eacute;</span>";
            return false;
        }
        if ( $this->count()  > MAX_PREDEFINED_OPERATION )
        {
            echo '<span class="notice">Vous avez atteint le max. d\'op&eacute;ration pr&eacute;d&eacute;finie, d&eacute;sol&eacute;</span>';
            return false;
        }
        $sql=sprintf('insert into op_predef (jrn_def_id,od_name,od_item,od_jrn_type,od_direct)'.
                     'values'.
                     "(%d,'%s',%d,'%s','%s')",
                     $this->p_jrn,
                     Database::escape_string($this->name),
                     $this->nb_item,
                     $this->jrn_type,
                     $this->od_direct);
        $this->db->exec_sql($sql);
        $this->od_id=$this->db->get_current_seq('op_def_op_seq');
        return true;
    }
    /*!\brief load the data from the database and return an array
     * \return an array
     */
    function load()
    {
        $sql="select od_id,jrn_def_id,od_name,od_item,od_jrn_type".
             " from op_predef where od_id=".$this->od_id.
             " and od_direct='".$this->od_direct."'".
             " order by od_name";
        $res=$this->db->exec_sql($sql);
        $array=Database::fetch_all($res);

        return $array;
    }
    function compute_array()
    {
        $p_array=$this->load();
        $array=array(
                   "e_comm"=>$p_array[0]["od_name"],
                   "nb_item"=>(($p_array[0]["od_item"]<10?10:$p_array[0]["od_item"]))   ,
                   "p_jrn"=>$p_array[0]["jrn_def_id"],
                   "jrn_type"=>$p_array[0]["od_jrn_type"]
               );
        return $array;

    }

    /*!\brief show the button for selecting a predefined operation */
    function show_button()
    {

        $select=new ISelect();
        $value=$this->db->make_array("select od_id,od_name from op_predef ".
                                     " where jrn_def_id=".$this->p_jrn.
                                     " and od_direct ='".$this->od_direct."'".
                                     " order by od_name");

        if ( empty($value)==true) return "";
        $select->value=$value;
        $r=$select->input("pre_def");

        return $r;
    }
    /*!\brief count the number of pred operation for a ledger */
    function count()
    {
        $a=$this->db->count_sql("select od_id,od_name from op_predef ".
                                " where jrn_def_id=".$this->p_jrn.
                                " and od_direct ='".$this->od_direct."'".
                                " order by od_name");
        return $a;
    }
    /*!\brief get the list of the predef. operation of a ledger
     * \return string
     */
    function get_list_ledger()
    {
        $sql="select od_id,od_name from op_predef ".
             " where jrn_def_id=".$this->p_jrn.
             " and od_direct ='".$this->od_direct."'".
             " order by od_name";
        $res=$this->db->exec_sql($sql);
        $all=Database::fetch_all($res);
        return $all;
    }
    /*!\brief set the ledger
     * \param $p_jrn is the ledger (jrn_id)
     */
    function set_jrn($p_jrn)
    {
        $this->p_jrn=$p_jrn;
    }
}

/*!\brief mother of the pre_op_XXX, it contains only one data : an
 * object Pre_Operation. The child class contains an array of
 * Pre_Operation object
 */
class Pre_operation_detail
{
    var $operation;
    function __construct($p_cn)
    {
        $this->db=$p_cn;
        $this->operation=new Pre_operation($this->db);
        $this->valid=array('ledger'=>'jrn_def_id','ledger_type'=>'jrn_type','direct'=>'od_direct');
		$this->jrn_def_id=-1;
    }


    /*!\brief show a form to use pre_op
     */
    function form_get ()
    {

        $hid=new IHidden();
        $r=$hid->input("action","use_opd");
        $r.=$hid->input("jrn_type",$this->get("ledger_type"));
        $r.= HtmlInput::submit('use_opd','Utilisez une op&eacute;ration pr&eacute;d&eacute;finie');
        $r.= $this->show_button();
        return $r;

    }
    /*!\brief count the number of pred operation for a ledger */
    function count()
    {
        $a=$this->db->count_sql("select od_id,od_name from op_predef ".
                                " where jrn_def_id=".$this->jrn_def_id.
                                " and od_direct ='".$this->od_direct."'".
                                " order by od_name");
        return $a;
    }
    /*!\brief show the button for selecting a predefined operation */
    function show_button()
    {

        $select=new ISelect();

        $value=$this->get_operation();
        //	if ( empty($value)==true) return "";
        $select->value=$value;
        $r=$select->input("pre_def");
        return $r;
    }
    public function   get_operation()
    {
		if ( $this->jrn_def_id=='') return array();
        $value=$this->db->make_array("select od_id,od_name from op_predef ".
                                     " where jrn_def_id=".sql_string($this->jrn_def_id).
                                     " and od_direct ='".sql_string($this->od_direct)."'".
                                     " order by od_name",1);
        return $value;
    }
    function set($p_param,$value)
    {
        if ( ! isset ($this->valid[$p_param] ) )
        {
            echo(" le parametre $p_param n'existe pas ".__FILE__.':'.__LINE__);
            exit();
        }
        $attr=$this->valid[$p_param];
        $this->$attr=$value;
    }
    function get($p_param)
    {

        if ( ! isset ($this->valid[$p_param] ) )
        {
            echo(" le parametre $p_param n'existe pas ".__FILE__.':'.__LINE__);
            exit();
        }
        $attr=$this->valid[$p_param];
        return $this->$attr;
    }

    function get_post()
    {
        $this->operation->get_post();
    }

}
