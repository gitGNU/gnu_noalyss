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
 *\brief definition of Anc_Operation
 */
require_once("class_ibutton.php");
require_once("class_ihidden.php");
require_once("class_iselect.php");
require_once("class_itext.php");
require_once("class_anc_plan.php");
require_once ("user_common.php");

/*! \brief this class is used to show the form for entering an
 *   operation only FOR analytic operation
 *   to save it, to display or to get a list from a certain period
 *
 */
class Anc_Operation
{
    var $db; 	/*!< database connection */
    var $row;	/*!< array of row for one operation*/
    var $list;	/*!< array of all operation */
    var $id;	/*!< = oa_id (one row) */
    var $po_id;	/*!< poste analytique */
    var $oa_amount;	/*!< amount for one row */
    var $oa_description;	/*!< comment for one row */
    var $oa_debit;	/*!< true for debit or false */
    var $j_id;		/*!< foreign key to a jrnx operation
                                      (or null if none */
    var $oa_group;   /*!< group of operation  */
    var $oa_date;	   /*!< equal to j_date if j_id is not	  null */
    var $pa_id;	/*!< the plan analytique id */

    /*!\brief constructor
     *
     */
    function Anc_Operation ($p_cn,$p_id=0)
    {
        $this->db=$p_cn;
        $this->id=$p_id;
    }
    /*!\brief add a row  to the table operation_analytique
     * \note if $this->oa_group if 0 then a sequence id will be computed for
     * the oa_group, if $this->j_id=0 then it will be null
     *
     */
    function add($p_seq=0)
    {

        if ( $this->oa_group == 0)
        {
            $this->oa_group=$this->db->get_next_seq('s_oa_group');
        }

        if ( $this->j_id == 0 )
        {
            $this->j_id="null";
        }


        // we don't save null operations
        if ( $this->oa_amount == 0 || $this->po_id==-1)
            return;

        $oa_row=(isset($this->oa_row))?$this->oa_row:"NULL";
        $sql='insert into operation_analytique (
             po_id,
             oa_amount,
             oa_description,
             oa_debit,
             oa_group,
             j_id,
             oa_date,
             oa_row
             ) values ('.
             $this->po_id.",".
             $this->oa_amount.",".
             "' ".Database::escape_string($this->oa_description)."',".
             "'".$this->oa_debit."',".
             $this->oa_group.",".
             $this->j_id.",".
             "to_date('".$this->oa_date."','DD.MM.YYYY'),".$oa_row.")";


        $this->db->exec_sql($sql);

    }
    /*!\brief delete a row from the table operation_analytique
     * \note be carefull : do not delete a row when we have a group
     */
    function delete()
    {
        $sql="delete from operation_analytique where oa_id=$1";

        $this->db->exec_sql($sql,array($this->oa_id));
    }

    /*!\brief get a list of row from a certain periode
     */
    function get_list($p_from,$p_to,$p_from_poste="",$p_to_poste="")
    {
        $cond="";
        $cond_poste="";

        if ($p_from!="")
            $cond="and jr_date >= to_date('$p_from','DD.MM.YYYY') ";
        if ( $p_to!="" )
            $cond.="and jr_date <=to_date('$p_to','DD.MM.YYYY')";

        if ($p_from_poste != "" )
            $cond_poste=" and upper(po_name) >= upper('".$p_from_poste."')";
        if ($p_to_poste != "" )
            $cond_poste.=" and upper(po_name) <= upper('".$p_to_poste."')";
        $pa_id_cond="";
        if ( isset ( $this->pa_id) && $this->pa_id !='')
            $pa_id_cond= "pa_id=".$this->pa_id." and";
	$sql="
	select oa_id,
	po_name,
	oa_description,
	po_description,
	oa_debit,
	to_char(jr_date,'DD.MM.YYYY') as oa_date,
	oa_amount,
	oa_group,
	j_id ,
	jr_internal,
	jr_id,
	jr_comment,
	j_poste,
	jrnx.f_id,
	( select ad_value from fiche_Detail where f_id=jrnx.f_id and ad_id=23) as qcode
	from operation_analytique as B join poste_analytique using(po_id)
	left join jrnx using (j_id)
	left join jrn on  (j_grpt=jr_grpt_id)
             where $pa_id_cond oa_amount <> 0.0 $cond $cond_poste
	order by oa_date ,oa_group,oa_debit desc,oa_id";

        $RetSql=$this->db->exec_sql($sql);

        $array=Database::fetch_all($RetSql);
        return $array;
    }

    /*\brief show the HTML table for the operation
     */
    function html_table($p_from)
    {
        if ($p_from=="")
        {
            $from="";
            $to="";
        }
        else
        {
            $p=new Periode($this->db);
            list($from,$to)=$p->get_date_limit($p_from);
        }

        $array=$this->get_list($from,$to);
        if ( empty($array)  )
            return "Pas d'enregistrement trouv&eacute;";

        // jrn_navigation_bar
        $step=$_SESSION['g_pagesize'];
        $page=(isset($_GET['offset']))?$_GET['page']:1;
        $offset=(isset($_GET['offset']))?$_GET['offset']:0;
        $bar=jrn_navigation_bar($offset+1,count($array),$step,$page);

        if ( $step !=-1)
            $view=array_splice($array,$offset,$step);
        else
            $view=$array;

        $gDossier=dossier::id();
        $ret="";
        $ret.=$bar;

        $count=0;
        $group=0;
        $oldgroup=0;
        $oldjrid=0;

        foreach ($view as $row)
        {
            $group=$row['oa_group'];
            if ( $group !=$oldgroup )
            {
                if ( $oldgroup!=0 )
                {

                    $efface=new IButton();
                    $efface->javascript="anc_remove_operation(".$gDossier.",".$oldgroup.")";
                    $efface->name="Efface";
                    $efface->label="Efface";
                    $ret.="<td>".$efface->input()."</td>";

                    $this->oa_group=$oldgroup;
                    $jr_id=$this->get_jrid();

                    if ( $jr_id != 0)
                    {
                        // get the old jr_id
                        $detail=new IButton();
                        $detail->javascript="viewOperation($jr_id,$gDossier)";
                        $detail->name="Detail";
                        $detail->label="Detail";
                        $ret.="<td>".$detail->input()."</td>";
                    }
                    $ret.='</table>';

                }
                $ret.='<table id="'.$row['oa_group'].'" style="margin-bottom:2px;border: 1px solid blue; width: 90%;">';

                $ret.="<th>".
                      $row['oa_date'].
                      "</th>".
                      "<th>".
                      h($row['oa_description']).
                      "</th>";

                $ret.="<th>".
                      "Groupe id : ".$row['oa_group'].
                      "</th>";

                $oldgroup=$group;

            }

            $class=($count%2==0)?"odd":"";
            $count++;
            $cred= ( $row['oa_debit'] == 'f')?"CREDIT":"DEBIT";
            $ret.="<tr class=\"$class\">";
            $ret.= "<td>".
                   h($row['po_name']).
                   "</td>";

	    $ret.=td(h($row['po_description']));

            $ret.='<td class="num">'.	nbm($row['oa_amount']).
                  "</td>".
                  "<td>".
                  $cred.
                  "</td>".

                  "</tr>";
        }


        $efface=new IButton();
        $efface->javascript="anc_remove_operation("."$gDossier,".$oldgroup.")";
        $efface->name="Efface";
        $efface->label="Efface";
        $ret.="<td>".$efface->input()."</td>";
        // get the old jr_id
        $this->oa_group=$oldgroup;
        $jr_id=$this->get_jrid();
        if ( $jr_id != 0 )
        {
            $detail=new IButton();
            $detail->javascript="modifyOperation($jr_id,'".$gDossier."')";
            $detail->name="Detail";
            $detail->label="Detail";
            $ret.="<td>".$detail->input()."</td>";
        }
        $ret.='</table>';
        $ret.=$bar;
        return $ret;
    }
    /*!\brief retrieve an operation thanks a jrnx.j_id
     * \param the jrnx.j_id
     * \return false if nothing is found other true
     */
    function get_by_jid($p_jid)
    {
        $sql="select distinct oa_id,
             po_id,
             oa_amount,
             oa_description,
             oa_debit,
             j_id,
             oa_group,
             oa_date,
             pa_id,
             oa_row
             from operation_analytique join poste_analytique using (po_id)
             where
             j_id=$p_jid order by j_id,oa_row,pa_id";
        $ret=$this->db->exec_sql($sql);
        $res=Database::fetch_all($ret);
        if ( $res== false) return null;

        foreach ($res as $row)
        {
            $a=new Anc_Operation($this->db);
            foreach ( $row as $attr=>$value )
            {
                $a->$attr=$row[$attr];
            }
            $array[]=clone $a;
        }
        return $array;
    }
    /*!\brief modify an op from modify_op.php
     *
     */
    function update_from_jrnx($p_po_id)
    {
        $a=$this->get_by_jid($this->j_id);
        if ( $a == null )
        {
            // retrieve data from jrnx
            $sql="select jr_date,j_montant,j_debit from jrnx ".
                 " join jrn on (jr_grpt_id = j_grpt) ".
                 "where j_id=".$this->j_id;
            $res=$this->db->exec_sql($sql);
            if (Database::num_row($res) == 0 ) return;
            $row=Database::fetch_array($res,0);
            $this->oa_amount=$row['j_amount'];
            $this->oa_date=$row['jr_date'];
            $this->oa_debit=$row['j_debit'];
            $this->oa_description=$row['jr_comment'];
            $this->add();
        }
        else
        {
            foreach ($a as $row )
            {
                if ( $row->pa_id == $this->pa_id )
                {
                    $row->po_id=$p_po_id;
                    $row->update();
                }
            }
        }
    }
    /*!\brief retrieve the jr_id thanks the oa_group */
    function get_jrid()
    {
        $sql="select distinct jr_id from jrn join jrnx on (j_grpt=jr_grpt_id) join operation_analytique using (j_id) where j_id is not null and oa_group=".$this->oa_group;
        $res=$this->db->exec_sql($sql);
        if ( Database::num_row($res) == 0 ) return 0;
        $ret=Database::fetch_all($res);
        return $ret[0]['jr_id'];
    }
    /*\brief this function get the balance for a certain period
     *\param $p_from from date (accountancy period)
     *\param $p_to to dat  (accountancy period)
     *\param  $p_plan_id the plan id
     */
    function get_balance($p_from,$p_to,$p_plan_id)
    {
        // for the operation connected to jrnx
        $cond=sql_filter_per($this->db,$p_from,$p_to,'p_id','j_date');
        $sql="select oa_id, po_id, oa_amount, oa_debit, j_date from jrnx join operation_analytique using (j_id)
             join poste_analytique using (po_id)
             where
             $cond and j_id is not null and pa_id=$p_plan_id";

        // OD
        $cond=sql_filter_per($this->db,$p_from,$p_to,'p_id','oa_date');
        $sql="union select oa_id, po_id, oa_amount, oa_debit,oa_date from
             operation_analytique
             join poste_analytique using (po_id)
             where j_id is null and
             $cond and pa_id=$p_plan_id ";
        try
        {
            $res=$this->db->exec_sql($sql);
            $array=Database::fetch_all($res);
        }
        catch (Exception $e)
        {
            var_dump($e);
        }
    }
    /*!\brief display the form for PA
     *\param $p_array contains POST (or GET) data (val[] hplan[][] op[])
     * \param $p_null = 1 if PA optional otherwise 0 mandatory
     * \param $p_mode == form 1 ==> read/write otherwise 0==>readonly
     * \param $p_seq number of the row
     * \param $p_amount amount
     * \param $p_id operation is detailled in a HTML popup, if several
     *  are opened, the tableid MUST be different. So we need to use a new parameter
     * \see save_form_plan
    @note
    - op is an array containing the line number
    - pa_id is an array of the existing array
    - hplan is an array of the POSTE ANALYTIQUE id used, the size of hplan from 0 to x,
    x can be bigger than the number of plan id
    - val contains the amount by row inside the table. One operation (j_id) you can have several rows
    @code
    0 =>
      array
        'op' => int 200
    'pa_id' =>
      array
        0 => string '14' (length=2)
        1 => string '15' (length=2)
    'hplan' =>
      array
        1 =>
          array
            0 => string '25' (length=2)
            1 => string '26' (length=2)
    'val' =>
      array
        1 =>
          array
            0 => string '100.0000' (length=8)

    @endcode
     */
    function display_form_plan($p_array,$p_null,$p_mode,$p_seq,$p_amount,$p_id='')
    {
        if ( $p_array != null)
            extract ($p_array);
        $result="";
        $plan=new Anc_Plan($this->db);
        $a_plan=$plan->get_list(" order by pa_id ");
        if ( empty ($a_plan) ) return "";
        $table_id="t".$p_seq;
        $hidden=new IHidden();

		$readonly=($p_mode==1)?false:true;

        $result.=$hidden->input('amount_'.$table_id,$p_amount);
        if ( $p_mode==1 )
            $result.='<table id="'.$p_id.$table_id.'">';
        else
            $result.='<table>';
        $result.="<tr>".$plan->header()."<th>montant</th></tr>";

        /* compute the number of rows */
        $nb_row=(isset($val[$p_seq]))?count($val[$p_seq]):1;
        $count=0;

		$remain=$p_amount;
		$ctrl_remain="remain".$table_id;

        for ( $i=0; $i < $nb_row;$i++)
        {
            $result.='<tr>';

            foreach ($a_plan as $r_plan)
            {

                $array=$this->db->make_array(
                           "select po_id as value,".
                           " html_quote(po_name) as label from poste_analytique ".
                           " where pa_id = ".$r_plan['id'].
                           " order by po_name",$p_null);
                $select =new ISelect("hplan[".$p_seq."][]",$array);
                $select->table=0;
                // view only or editables
                if (  $p_mode==1 )
                {
                    // editable
                    $select->readonly=false;
                    if ( isset($hplan) && isset($hplan[$p_seq][$count]) ){
                        $select->selected=$hplan[$p_seq][$count];

					}
                }
                else
                {
					if ( isset($hplan) && isset($hplan[$p_seq][$count]) ){
                        $select->selected=$hplan[$p_seq][$count];
					}
                    // view only
                    $select->readOnly=true;
                }
                if ($p_mode==1)
                    $result.='<td>'.$select->input().'</td>';
                else
                    $result.='<td>'.$select->display().'</td>';
                $count++;


            }
            $value=new INum();
			$value->javascript='onchange="format_number(this);anc_refresh_remain(\''.$table_id.'\',\''.$p_seq.'\')"';
            $value->name="val[".$p_seq."][]";
            $value->size=6;
            $value->value=abs((isset($val[$p_seq][$i]))?$val[$p_seq][$i]:$p_amount);
            $value->readOnly=($p_mode==1)?false:true;
			$remain=bcsub($remain,$value->value);
            $result.='<td>'.$value->input().'</td>';

            $result.="</tr>";
        }

        $result.="</table>";
		$style_remain=($remain==0)?'style="color:green"':' style="color:red"';
		$result.=" Reste à imputer =  ".
				'<span class="remain" '.$style_remain.' id="'.$ctrl_remain.'">'.
				$remain.'</span>';

        // add a button to add a row
        $button=new IButton();
        $button->javascript="add_row('".$p_id."$table_id',$p_seq);";
        $button->name="js".$p_id.$p_seq;
        $button->label="Nouvelle ligne";
        if ( $p_mode == 1 )
            $result.="<br>".$button->input();

        return $result;
    }
    /*!\brief it called for each item, the data are taken from $p_array
     *  data and set before in this.
     * \param $p_item if the item nb for each item (purchase or selling
     *  merchandise)
     * \param $p_array structure
     * \verbatim
      nb_tA A is the number of the item it contains the number of
              rows of CA for this card
      valAlR amount for the CA (item A row R)
      ta_AoCrow_R contains the value of the pa_id and po_id for this
                  row with the form pa_id_po_id %d_%d
     *\endverbatim
     * \attention The idea is one j_id matches several oa_id,
     *  serveral data are set before the call :
     *   -j_id
     *   -oa_debit
     *   -oa_group
     *   -oa_date
     *   -oa_description
     *
     */
    function save_form_plan($p_array,$p_item,$j_id)
    {
        extract($p_array);
	if (! isset ($hplan) ) return;
        /* variable for in array
           pa_id array of existing pa_id
           hplan double array with the pa_id (column)
           val double array by row with amount
           op contains sequence
           p_item is used to identify what op is concerned
        */
        /* echo "j_id = $j_id p_item = $p_item hplan=".var_export($hplan[$p_item],true)." val = ".var_export($val[$p_item],true).'<br>'; */
        /* for each row */
        //   for ($i=0;$i<count($val[$p_item]);$i++) {
        $idx_pa_id=0;
        $row=0;
        // foreach col PA
        for ($e=0;$e<count($hplan[$p_item]);$e++)
        {
            if ( $idx_pa_id == count($pa_id))
            {
                $idx_pa_id=0;
                $row++;
            }
            if ($hplan[$p_item][$e] != -1 && $val[$p_item][$row] != '')
            {
                $op=new Anc_Operation($this->db);
                $op->po_id=$hplan[$p_item][$e];
                $op->oa_group=$this->oa_group;
                $op->j_id=$j_id;
                $op->oa_amount=abs($val[$p_item][$row]);
                $op->oa_debit=$this->oa_debit;
                $op->oa_date=$this->oa_date;

                $op->oa_description=$this->oa_description;
                $op->oa_row=$row;
                $op->add();
            }
            $idx_pa_id++;
        }
        // }
    }

    /**
    *@brief save a whole form from a update box
    *@param $p_array  for ALL j_id
    *@return
    *@note
    *@see save_form_plan to_request
    @code

    @endcode
    */
    function save_update_form($p_array)
    {
        extract ($p_array);
        for ($i=0;$i < count($op);$i++)
        {
            /* clean jrnx */
            $this->db->exec_sql('delete from operation_analytique where j_id=$1',array($op[$i]));
            /* get missing data for adding */
            $a_missing=$this->db->get_array("select to_char(jr_date,'DD.MM.YYYY') as mdate,j_montant,j_debit,jr_comment from jrnx join jrn on (j_grpt=jr_grpt_id) where j_id=$1",
                                            array($op[$i]));
            $missing=$a_missing[0];
            $this->oa_debit=$missing['j_debit'];
            $this->oa_description=$missing['jr_comment'];
            $this->j_id=$op[$i];
            $group=$this->db->get_next_seq("s_oa_group"); /* for analytic */
            $this->oa_group=$group;
            $this->oa_date=$missing['mdate'];
            $this->save_form_plan($p_array,$i,$op[$i]);
        }
    }
    /*\brief transform a array of operation into a array usage by
     *display_form_plan & save_form_plan
     *\param $p_array array of operation
     *\param $p_line line
     *\return an array complying with \see save_form_plan
     */
    function to_request ($p_array,$p_line)
    {
        $result=array();
        $result[]=array('op'=>$this->j_id);
        $pa_id=array();

        /* First add the pa_id */
        for ($i=0;$i < count($p_array);$i++)
        {
            if ( in_array($p_array[$i]->pa_id,$pa_id)==false)
                $pa_id[]=$p_array[$i]->pa_id;
        }
        $result['pa_id']=$pa_id;

        /* add the hplan */
        $seq=0;
        for ($i=0;$i < count($p_array);$i++)
        {
            $hplan[$p_line][$i]=$p_array[$i]->po_id;
        }
        $result['hplan']=$hplan;
        /* Add the amount */
        $idx_pa=0;
        for ($i=0;$i < count($p_array);$i++)
        {
            $val[$p_line][$p_array[$i]->oa_row]=$p_array[$i]->oa_amount;
        }
        $result['val']=$val;
        return $result;
    }
    /*!
     * \brief delete from operation_analytique
     * \param $p_jid the operation_analytique.j_id field
     *
     * \return none
     */
    function delete_by_jid($p_jid)
    {
        $sql="delete from operation_analytique where j_id=$p_jid";
        $this->db->exec_sql($sql);
    }
    /*\brief Display a table with analytic accounting in
     *       detail of operation
     *@note $this->j_id must be set
     *\param $p_mode 0 = readonly or 1=writable
     *\param $p_amount amount
     *\param $p_id unique id
     *@see display_form_plan
     *\return string to display
     */
    function display_table($p_mode,$p_amount,$p_id)
    {
        static $seq=-1;		/* first seq == 0 */
        $seq++;

        $array=$this->get_by_jid($this->j_id) ;
        if ( $array != null )
        {
            $request=$this->to_request($array,$seq);
            return "<td>".$this->display_form_plan($request,1,$p_mode,$seq,$p_amount,$p_id)."</td>";
        }
        else
        {
            return '<td>'.$this->display_form_plan(null,1,$p_mode,$seq,$p_amount,$p_id)."</TD>";
        }
        return "";

    }
///////////////////////////////////////////////////////////////////////////
// TEST
///////////////////////////////////////////////////////////////////////////
    /*\brief test the class
     *\param
     *\param
     *\return
     */
    function test_me()
    {
        $cn=new Database(dossier::id());
        $anco=new Anc_Operation($cn);
        $j_id=200;
        $anco->j_id=$j_id;
        $array=$anco->get_by_jid($j_id);
        $a=$anco->to_request($array,1);
        echo '<form>';
        echo dossier::hidden();
        echo HtmlInput::hidden('j_id',$j_id);
        echo HtmlInput::hidden('test_select',$_REQUEST['test_select']);
        echo $anco->display_table(1,15002,0);
        echo '<input type="submit" name="save">';
        echo '</form>';
        if ( isset($_REQUEST['save']))
        {
            echo "to_save";
            var_dump($_REQUEST);
        }
        var_dump($a);

    }

}
