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
 * \brief object to show a table: link between accountancy and analytic
 */
require_once('class_anc_print.php');

class Anc_Table extends Anc_Print
{
  function __contruct($p_cn)
  {
    $this->cn=$p_cn;
  }
  function get_request()
  {
    parent::get_request();
    $this->card_poste=HtmlInput::default_value('card_poste',1,$_GET);
  }
  function display_form($p_hidden='')
  {
    $r=parent::display_form($p_hidden);
    $icard=new ISelect('card_poste');
    $icard->value=array(
			array('value'=>1,'label'=>'Par fiche'),
			array('value'=>2,'label'=>'Par poste comptable')
			);
    
    $icard->selected=$this->card_poste;
    $r.=$icard->input();
    return $r;
  }
  /**
   * load the data
   * does not return anything but give a value to this->aheader and this->arow
   */
  function load_card()
  {
    $date=$this->set_sql_filter();
    $date=($date != '')?"  $date":'';
    $sql_from_poste=($this->from_poste!='')?" and  po.po_name >= upper('".Database::escape_string($this->from_poste)."')":'';
    $sql_to_poste=($this->to_poste!='')?" and  po.po_name <= upper('".Database::escape_string($this->to_poste)."')":'';

    $header="select distinct po_id,po_name from v_table_analytic_card  where
		pa_id=$1 ".$date.$sql_from_poste.$sql_to_poste." order by po_name";
    $this->aheader=$this->db->get_array($header,array($this->pa_id));
    
    $this->arow=$this->db->get_array("select distinct f_id,j_qcode,name from v_table_analytic_card  where
		pa_id=$1 ".$date.$sql_from_poste.$sql_to_poste." order by name",array($this->pa_id));
    $this->sql='select sum_amount from v_table_analytic_card where f_id=$1 and po_id=$2 and pa_id='.$this->pa_id.' '.$date.$sql_from_poste.$sql_to_poste;
  }
    function set_sql_filter()
    {
        $sql="";
        $and=" and ";
        if ( $this->from != "" )
        {
            $sql.="$and oa_date >= to_date('".$this->from."','DD.MM.YYYY')";
        }
        if ( $this->to != "" )
        {
            $sql.=" $and oa_date <= to_date('".$this->to."','DD.MM.YYYY')";
        }

        return $sql;

    }

  /**
   * load the data
   * does not return anything but give a value to this->aheader and this->arow
   */
  function load_poste()
  {
    $date=$this->set_sql_filter();
    $date=($date != '')?"  $date":'';
    $sql_from_poste=($this->from_poste!='')?" and  po.po_name >= upper('".Database::escape_string($this->from_poste)."')":'';
    $sql_to_poste=($this->to_poste!='')?" and  po.po_name <= upper('".Database::escape_string($this->to_poste)."')":'';

    $header="select distinct po_id,po_name from v_table_analytic_account where
		pa_id=$1 ".$date.$sql_from_poste.$sql_to_poste." order by po_name";
    $this->aheader=$this->db->get_array($header,array($this->pa_id));
    
    $this->arow=$this->db->get_array("select distinct j_poste,name from v_table_analytic_account  where
		pa_id=$1 ".$date.$sql_from_poste.$sql_to_poste." order by j_poste",array($this->pa_id));
    $this->sql='select sum_amount from v_table_analytic_account where j_poste=$1 and po_id=$2 and pa_id='.$this->pa_id.' '.$date.$sql_from_poste.$sql_to_poste;
  }
  /**
   *@brief display the button export CSV
   *@param $p_hidden is a string containing hidden items
   *@return html string
   */  
  function show_button($p_hidden)
  {
    $r="";
    $r.= '<form method="GET" action="export.php"  style="display:inline">';
    $r.= HtmlInput::hidden("act","CSV/AncTable");
    $r.= HtmlInput::hidden("to",$this->to);
    $r.= HtmlInput::hidden("from",$this->from);
    $r.= HtmlInput::hidden("pa_id",$this->pa_id);
    $r.= HtmlInput::hidden("from_poste",$this->from_poste);
    $r.= HtmlInput::hidden("to_poste",$this->to_poste);
    $r.= $p_hidden;
    $r.= dossier::hidden();
    $r.=HtmlInput::submit('bt_csv',"Export en CSV");
    $r.= '</form>';
    return $r;
  }
  function display_html()
  {
    bcscale(2);

    if ( $this->card_poste=='1')
      {
	$this->load_card();

	echo '<table class="result">';
	echo '<tr>';
	echo th('Fiche');
	foreach ($this->aheader as $h)
	  {
	    echo '<th style="text-align:right">'.h($h['po_name']).'</th>';
	  }
	echo th('Total',' style="text-align:right"');
	echo '</tr>';
	/*
	 * Show all the result
	 */
	$tot_global=0;
	for ($i=0;$i<count($this->arow);$i++)
	  {
	    echo '<tr>';
	    echo td(HtmlInput::history_card($this->arow[$i]['f_id'],$this->arow[$i]['j_qcode'].' '.$this->arow[$i]['name']));
	    $tot_row=0;
	    for ($x=0;$x<count($this->aheader);$x++)
	      {
		$amount=$this->db->get_value($this->sql,array($this->arow[$i]['f_id'],$this->aheader[$x]['po_id']));
		if ($amount==null)$amount=0;
		if ( isset($tot_col[$x]))
		  {
		    $tot_col[$x]=bcadd($tot_col[$x],$amount);
		  }
		else
		  {
		    $tot_col[$x]=$amount;
		  }
		echo td(nbm($amount),' class="num" ');
		$tot_row=bcadd($tot_row,$amount);
	      }
	    echo td(nbm($tot_row),' class="num"');
	    $tot_global=bcadd($tot_global,$tot_row);
	    echo '</tr>';
		    

	  }
	echo '<tr>';
	echo td('Totaux');
	for ($i=0;$i<count($this->aheader);$i++)
	  {
	    echo td(nbm($tot_col[$i]),' class="num"');
	  }
	echo td(nbm($tot_global),' class="num" style="font-size:130%;text-weight:bold;border:solid 1px blue"');
	echo '</tr>';
	echo '</table>';
      }
    if ( $this->card_poste=='2')
      {
	$this->load_poste();

	echo '<table class="result">';
	echo '<tr>';
	echo th('poste comptable ');
	foreach ($this->aheader as $h)
	  {
	    echo '<th style="text-align:right">'.h($h['po_name']).'</th>';
	  }
	echo th('Total',' style="text-align:right"');
	echo '</tr>';
	/*
	 * Show all the result
	 */
	$tot_global=0;
	for ($i=0;$i<count($this->arow);$i++)
	  {
	    echo '<tr>';
	    echo td(HtmlInput::history_account($this->arow[$i]['j_poste'],$this->arow[$i]['j_poste'].' '.$this->arow[$i]['name']));
	    $tot_row=0;
	    for ($x=0;$x<count($this->aheader);$x++)
	      {
		$amount=$this->db->get_value($this->sql,array($this->arow[$i]['j_poste'],$this->aheader[$x]['po_id']));
		if ($amount==null)$amount=0;
		if ( isset($tot_col[$x]))
		  {
		    $tot_col[$x]=bcadd($tot_col[$x],$amount);
		  }
		else
		  {
		    $tot_col[$x]=$amount;
		  }
		echo td(nbm($amount),' class="num" ');
		$tot_row=bcadd($tot_row,$amount);
	      }
	    echo td(nbm($tot_row),' class="num"');
	    $tot_global=bcadd($tot_global,$tot_row);
	    echo '</tr>';
		    

	  }
	echo '<tr>';

	echo td('Totaux');
	for ($i=0;$i<count($this->aheader);$i++)
	  {
	    echo td(nbm($tot_col[$i]),' class="num"');
	  }
	echo td(nbm($tot_global),' class="num" style="font-size:130%;text-weight:bold;border:solid 1px blue"');
	echo '</tr>';
	echo '</table>';

      }

  }
}