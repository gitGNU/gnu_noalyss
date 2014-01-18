<?php
/*
 *   This file is part of NOALYSS.
 *
 *   NOALYSS is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   NOALYSS is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with NOALYSS; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Copyright Author Dany De Bontridder danydb@aevalys.eu

/*!\file
 * \brief show the Grand Livre for analytic
 */
require_once('class_anc_print.php');
require_once 'class_impress.php';

class Anc_GrandLivre extends Anc_Print
{
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
      /*!
     * \brief load the data from the database
     *
     * \return array
     */
    function load()
    {
      $filter_date=$this->set_sql_filter();
      $cond_poste='';
      if ($this->from_poste != "" )
            $cond_poste=" and upper(po_name) >= upper('".$this->from_poste."')";
        if ($this->to_poste != "" )
            $cond_poste.=" and upper(po_name) <= upper('".$this->to_poste."')";
        $pa_id_cond="";
        if ( isset ( $this->pa_id) && $this->pa_id !='')
            $pa_id_cond= "pa_id=".$this->pa_id." and";
        $array=$this->db->get_array("	select oa_id,
	po_name,
	oa_description,
	po_description,
	oa_debit,
	to_char(oa_date,'DD.MM.YYYY') as oa_date,
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
             where $pa_id_cond oa_amount <> 0.0  $cond_poste  $filter_date
	order by po_name,oa_date::date,qcode,j_poste");

        return $array;
    }

	function load_csv()
    {
      $filter_date=$this->set_sql_filter();
      $cond_poste='';
      if ($this->from_poste != "" )
            $cond_poste=" and upper(po_name) >= upper('".$this->from_poste."')";
        if ($this->to_poste != "" )
            $cond_poste.=" and upper(po_name) <= upper('".$this->to_poste."')";
        $pa_id_cond="";
        if ( isset ( $this->pa_id) && $this->pa_id !='')
            $pa_id_cond= "pa_id=".$this->pa_id." and";
        $array=$this->db->get_array("	select
	po_name,
	to_char(oa_date,'DD.MM.YYYY') as oa_date,
	j_poste,
	( select ad_value from fiche_Detail where f_id=jrnx.f_id and ad_id=23) as qcode,
	jr_comment,
	jr_internal,
	case when oa_debit='t' then 'D' else 'C' end,
	oa_amount
	from operation_analytique as B join poste_analytique using(po_id)
	left join jrnx using (j_id)
	left join jrn on  (j_grpt=jr_grpt_id)
             where $pa_id_cond oa_amount <> 0.0  $cond_poste $filter_date
	order by po_name,oa_date::date,qcode,j_poste");


        return $array;
    }

   /*!
     * \brief compute the html display
     *
     *
     * \return string
     */

    function display_html()
    {
        $r="";
        //---Html
        $array=$this->load();
        if ( is_array($array) == false )
        {
            return $array;

        }

        if ( empty($array) )
        {
            $r.= _("aucune donnée");
            return $r;
        }
        $r.= '<table class="result" style="width=100%">';
	$ix=0;$prev='xx';$idx=0;
	$tot_deb=$tot_cred=0;
        
        foreach ( $array as $row )
        {
	  if ($prev != $row['po_name'])
	    {
	      if ( $ix>0)
		{
		  $r.='<tr>';
		  $tot_solde=bcsub($tot_cred,$tot_deb);
		  $sign=($tot_solde>0)?'C':'D';
		  $r.=td('').td('').td('').td('').td('').td(nbm($tot_deb),' class="num"').td(nbm($tot_cred),' class="num"').td(nbm($tot_solde).$sign,' class="num"');
		}
	      $r.='<tr>'.'<td colspan="7" style="width:auto">'.'<h2>'.h($row['po_name'].' '.$row['po_description']).'</td></tr>';
	      $r.= '<tr>'.
		'<th>'._('Date').'</th>'.
		'<th>'._('Poste').'</th>'.
		'<th>'._('Quick_code').'</th>'.
		'<th>'._('libelle').'</th>'.
		'<th>'._('Num.interne').'</th>'.
		'<th style="text-align:right">'._('Debit').'</th>'.
		'<th style="text-align:right">'._('Credit').'</th>'.
		'</tr>';

	      $tot_deb=$tot_cred=0;
	      $prev=$row['po_name'];
	      $ix++;
	    }
            $class=($idx%2==0)?'even':'odd'; $idx++;
            $r.='<tr class="'.$class.'">';
	    $detail=($row['jr_id'] != null)?HtmlInput::detail_op($row['jr_id'],$row['jr_internal']):'';
	    $post_detail=($row['j_poste'] != null)?HtmlInput::history_account($row['j_poste'],$row['j_poste']):'';
	    $card_detail=($row['f_id'] != null)?HtmlInput::history_card($row['f_id'],$row['qcode']):'';
	    $amount_deb=($row['oa_debit']=='t')?$row['oa_amount']:0;
	    $amount_cred=($row['oa_debit']=='f')?$row['oa_amount']:0;
	    $tot_deb=bcadd($tot_deb,$amount_deb);
	    $tot_cred=bcadd($tot_cred,$amount_cred);

            $r.=
                '<td>'.$row['oa_date'].'</td>'.
	      td($post_detail).
	      td($card_detail).
	      //	      '<td>'.h($row['oa_description']).'</td>'.
	      td($row['jr_comment']).
	      '<td>'.$detail.'</td>'.
	      '<td class="num">'.nbm($amount_deb).'</td>'.
	      '<td class="num">'.nbm($amount_cred)
	      .'</td>';
            $r.= '</tr>';
        }
	$r.='<tr>';
	$tot_solde=bcsub($tot_cred,$tot_deb);
	$sign=($tot_solde>0)?'C':'D';
	$r.=td('').td('').td('').td('').td('').td(nbm($tot_deb),' class="num"').td(nbm($tot_cred),' class="num"').td(nbm($tot_solde).$sign,' class="num"');

        $r.= '</table>';
        return $r;
    }
      /*!
     * \brief Show the button to export in PDF or CSV
     * \param $url_csv url of the csv
     * \param $url_pdf url of the pdf
     * \param $p_string hidden data to include in the form
     *
     *
     * \return string with the button
     */
    function show_button($p_string="")
    {
        $r="";
   /*     $r.= '<form method="GET" action="export.php" style="display:inline">';
        $r.= $p_string;
        $r.= dossier::hidden();
        $r.= HtmlInput::hidden("to",$this->to);
        $r.= HtmlInput::hidden("act","PDF:AncGrandLivre");

        $r.= HtmlInput::hidden("from",$this->from);
        $r.= HtmlInput::hidden("pa_id",$this->pa_id);
        $r.= HtmlInput::hidden("from_poste",$this->from_poste);
        $r.= HtmlInput::hidden("to_poste",$this->to_poste);
        $r.=HtmlInput::submit('bt_pdf',"Export en PDF");
        $r.= '</form>';
*/
        $r.= '<form method="GET" action="export.php"  style="display:inline">';
        $r.= HtmlInput::hidden("act","CSV:AncGrandLivre");
        $r.= HtmlInput::hidden("to",$this->to);
        $r.= HtmlInput::hidden("from",$this->from);
        $r.= HtmlInput::hidden("pa_id",$this->pa_id);
        $r.= HtmlInput::hidden("from_poste",$this->from_poste);
        $r.= HtmlInput::hidden("to_poste",$this->to_poste);
        $r.= $p_string;
        $r.= dossier::hidden();
        $r.=HtmlInput::submit('bt_csv',"Export en CSV");
        $r.= '</form>';
        return $r;
    }
    function display_csv()
    {
        $r="";
        //---Html
        $array=$this->load_csv();
        if ( is_array($array) == false )
        {
            return $array;

        }

        if ( empty($array) )
        {
            $r.= _("aucune donnée");
            return $r;
        }

        $ix=0;$prev='xx';
	$tot_deb=$tot_cred=0;
        $aheader=array();
        $aheader[]=array("title"=>'Imp. Analytique','type'=>'string');
        $aheader[]=array("title"=>'Date','type'=>'string');
        $aheader[]=array("title"=>'Poste','type'=>'string');
        $aheader[]=array("title"=>'Quick_Code','type'=>'string');
        $aheader[]=array("title"=>'libelle','type'=>'string');
        $aheader[]=array("title"=>'Num.interne','type'=>'string');
        $aheader[]=array("title"=>'Debit','type'=>'string');
        $aheader[]=array("title"=>'Credit','type'=>'num');
        Impress::array_to_csv($array, $aheader);
    }
}