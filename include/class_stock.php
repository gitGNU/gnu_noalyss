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

/**
 * @file
 * @brief
 *
 *
 */
require 'class_stock_sql.php';

class Stock extends Stock_Sql
{

	function view_detail_stock($p_sg_code, $p_year)
	{
		$sql = "select sg_id,
              sg_code,
         j_montant,
         coalesce(j_date,sg_date) as j_date,
         sg_quantity,
         sg_type,
         jr_id,
         coalesce(jr_comment,sg_comment) as comment,
         coalesce(jr_internal,'Changement manuel') as jr_internal,
         jr_id,
         case when sg_date is not null then sg_date else j_date end as stock_date
         from stock_goods
         left outer join jrnx using (j_id)
         left outer join jrn on jr_grpt_id=j_grpt
         where
         sg_code=$1 and (
         sg_exercice = $2
         or j_tech_per in (select p_id from parm_periode where p_exercice=$2)
         )
         order by stock_date
         ";
		// name

		$in_quantity = 0;
		$out_quantity = 0;
		$in_amount = 0;
		$out_amount = 0;
		$r = "";
		$a_name = getFicheNameCode($p_cn, $p_sg_code);
		$name = "";
		if ($a_name != null)
		{
			foreach ($a_name as $key => $element)
			{
				$name.=$element['av_text'] . ",";
			}
		}// if ( $a_name
		// Add java script for detail


		$r.='<H2 class="info">' . $p_sg_code . "  Noms : " . $name . '</H2>';

		$Res = $this->cn->exec_sql($sql, array($p_sg_code, $p_year));
		if (($M = Database::num_row($Res)) == 0)
			return "no rows";
		$r.='<table class="result" >';
		$r.="<TR >";
		$r.="<th>Date </th>";
		$r.="<th>Entrée / Sortie </th>";
		$r.="<th>Description</th>";
		$r.="<th>Op&eacute;ration</th>";
		$r.="<th style=\"text-align:right\">Montant</th>";
		$r.="<th style=\"text-align:right\">Quantité</th>";
		$r.="<th style=\"text-align:right\">Prix/Cout Unitaire</th>";
		$r.="</TR>";
		$tot_quantity = 0;
		for ($i = 0; $i < $M; $i++)
		{
			$l = Database::fetch_array($Res, $i);
			$r.="<tR id=\"stock" . $l['sg_id'] . "\" >";

			// date
			$r.="<TD>";
			$r.=format_date($l['j_date']);
			$r.="</TD>";

			//type (deb = out cred=in)
			$r.="<TD>";
			$r.=($l['sg_type'] == 'c') ? 'OUT' : 'IN';
			$r.="</TD>";

			if ($l['sg_type'] == 'c')
			{
				$quantity = (-1) * $l['sg_quantity'];
				$out_quantity+=$l['sg_quantity'];
				$out_amount+=$l['j_montant'];
			}
			else
			{
				$quantity = $l['sg_quantity'];
				$in_quantity+=$l['sg_quantity'];
				$in_amount+=$l['j_montant'];
			}
			$tot_quantity+=$quantity;
			// comment
			$r.="<TD>";
			$r.=h($l['comment']);
			$r.="</TD>";

			// jr_internal
			$r.="<TD>";
			if ($l['jr_id'] != "")
				$r.= "<A class=\"detail\" style=\"text-decoration:underline\" HREF=\"javascript:modifyOperation('" . $l['jr_id'] . "'," . dossier::id() . ",0,'S')\" > " . $l['jr_internal'] . "</A>";
			else
				$r.=$l['jr_internal'];

			$r.="</TD>";



			//amount
			$r.='<TD align="right">';
			$r.=nbm($l['j_montant']);
			$r.="</TD>";
			//quantity
			$r.='<TD align="right">';
			$r.=abs($quantity);
			$r.="</TD>";

			// Unit Price
			$r.='<TD align="right">';
			$up = "";
			if ($l['sg_quantity'] != 0)
				$up = round($l['j_montant'] / $l['sg_quantity'], 4);
			$r.=nbm($up);
			$r.="</TD>";
			$r.=td(HtmlInput::remove_stock($l['sg_id'], 'Effacer'), 'id="href' . $l['sg_id'] . '"');
			$r.="</TR>";
		}// for ($i
		// write the total
		$msg = ($tot_quantity < 0) ? 'Sortie / Vente ' : 'Entrée / Achat ';
		$row = td('Total ' . $msg, ' colspan="4" style="width:auto;text-align:right"');
		$row.=td(abs($tot_quantity), 'style="text-align:right"');
		$row.=td();
		$r.=td($row);
		$r.="</table>";
		$r.='<div style="float:left">';
		$r.='<table>';
		$row = td('Quantité IN/Achetée') . td($in_quantity, 'style="text-align:right"');
		$r.=tr($row);
		$row = td('Quantité OUT/Vendue') . td($out_quantity, 'style="text-align:right"');
		$r.=tr($row);
		$row = td('Quantité ') . td(abs($tot_quantity), 'style="text-align:right"');
		$r.=tr($row);
		$r.='</table>';
		$r.='</div>';

		$r.='<div style="float:left;clear:right;margin-left:10%">';
		$r.='<table>';
		$row = td('Montant IN/Acheté') . td($in_amount, 'style="text-align:right"');
		$r.=tr($row);
		$row = td('Montant OUT/Vendu') . td($out_amount, 'style="text-align:right"');
		$r.=tr($row);
		$row = td('Montant ') . td($out_amount - $in_amount, 'style="text-align:right"');
		$r.=tr($row);
		$r.='</table>';
		$r.='</div>';

		return $r;
	}

	/**
	 * show history of all the stock movement
	 * @param $p_array usually contains $_GET
	 */
	function history($p_array)
	{

		$sql = $this->create_query_histo($p_array);
		require_once 'class_sort_table.php';
		$p_url = HtmlInput::get_to_string(array("gDossier", "ac", "wcard", "wdate_start", "wdate_end", "wrepo",
					"wamount_start", "wamount_end", "wcode_stock", "wdirection"));

		$tb = new Sort_Table();
		$tb->add("Date", $p_url, " order by real_date asc", "order by real_date desc", "da", "dd");
		$tb->add("Code Stock", $p_url, " order by sg_code asc", "order by sg_code desc", "sa", "sd");
		$tb->add("Dépôt", $p_url, " order by r_name asc", "order by r_name desc", "ra", "rd");
		$tb->add("Fiche", $p_url, " order by 2 asc", "order by 2 desc", "fa", "fd");
		$tb->add("Commentaire", $p_url, " order by coalesce(sg_comment,jr_comment)  asc", "order by coalesce(sg_comment,jr_comment)  desc", "ca", "cd");
		$tb->add("Montant", $p_url, " order by j_montant asc", "order by j_montant desc", "ja", "jd");
		$tb->add("Quantité", $p_url, " order by sg_quantity asc", "order by sg_quantity  desc", "qa", "qd");
		$tb->add("IN/OUT", $p_url, " order by (case when sg_type='c' then 'OUT' when sg_type='c' then 'IN' end ) asc", "order by (case when sg_type='c' then 'OUT' when sg_type='c' then 'IN' end ) desc", "ta", "td");
		$order = (isset($p_array['ord'])) ? $p_array['ord'] : 'da';

		$sql.=$tb->get_sql_order($order);
		$step = $_SESSION['g_pagesize'];
		$page = (isset($_GET['offset'])) ? $_GET['page'] : 1;
		$offset = (isset($_GET['offset'])) ? $_GET['offset'] : 0;

		$res = $this->cn->exec_sql($sql);

		$max_row = Database::num_row($res);

		$nav_bar = jrn_navigation_bar($offset, $max_row, 0, $page);

		if ($step != -1)
			$res = $this->cn->exec_sql($sql . " , sg_id asc limit " . $step . " offset " . $offset);
		$max_row = Database::num_row($res);

		$this->search_box_button();
		$this->search_box($p_array);
		require_once 'template/stock_histo.php';
		$this->export_stock_histo_form();
		echo HtmlInput::print_window();
	}

	function export_stock_histo_form()
	{
		echo '<form style="display:inline" method="GET" action="export.php">';
		 echo HtmlInput::get_to_hidden(array("gDossier", "wcard", "wdate_start", "wdate_end", "wrepo",
					"wamount_start", "wamount_end", "wcode_stock", "wdirection"));
		 echo HtmlInput::hidden('act','CSV:StockHisto');
		 echo HtmlInput::submit('stockhisto','Export CSV');
		 echo '</form>';
	}
	function export_stock_summary_list_form()
	{
		echo '<form style="display:inline"  method="GET" action="export.php">';
		 echo HtmlInput::get_to_hidden(array("gDossier", "state_exercice"));
		 echo HtmlInput::hidden('act','CSV:StockResmList');

		 echo HtmlInput::submit('stockresm','Export CSV');
		 echo '</form>';
	}
	function search_box_button()
	{
		$bt = HtmlInput::button("Recherche", "Recherche", ' onclick="$(\'histo_search_d\').show();"');
		echo $bt;
	}

	function search_box($p_array)
	{
		// Declaration
		global $g_user;
		$wrepo = HtmlInput::select_stock($this->cn, "wrepo", 'R');
		$wrepo->value[] = array('value' => -1, 'label' => 'Tous les dépôts');

		$wdate_start = new IDate('wdate_start');
		$wdate_end = new IDate('wdate_end');
		$wamount_start = new INum('wamount_start');
		$wamount_end = new INum('wamount_end');
		$wcard = new ICard('wcard');
		$wcode_stock = new ICard('wcode_stock');
		$wdirection = new ISelect("wdirection");

		// value
		$wrepo->selected = HtmlInput::default_value("wrepo", -1, $p_array);

		// Date start / end
		$exercice = $g_user->get_exercice();
		$periode = new Periode($this->cn);
		list($periode_start, $periode_end) = $periode->get_limit($exercice);

		$wdate_start->value = HtmlInput::default_value("wdate_start", $periode_start->first_day(), $p_array);
		$wdate_end->value = HtmlInput::default_value("wdate_end", $periode_end->last_day(), $p_array);
		//amounts
		$wamount_start->value = HtmlInput::default_value("wamount_start", 0, $p_array);
		$wamount_end->value = HtmlInput::default_value("wamount_end", 0, $p_array);

		//Card
		$wcard->extra = "all";
		$wcard->set_attribute("typecard", "all");
		$wcard->value = HtmlInput::default_value("wcard", "", $p_array);

		//Card stock
		$wcode_stock->extra = " [sql] fd_id=500000 ";
		$wcode_stock->set_attribute("typecard", "[sql] fd_id=500000");
		$wcode_stock->value = HtmlInput::default_value("wcard", "", $p_array);

		// Repository
		$wcode_stock->value = HtmlInput::default_value("wcode_stock", "", $p_array);

		//Direction
		$wdirection->value = array(
			array('value' => "-1", 'label' => "Tout"),
			array('value' => "c", 'label' => "OUT"),
			array('value' => "d", 'label' => "IN")
		);
		$wdirection->selected = HtmlInput::default_value("wdirection", "-1", $p_array);

		require_once 'template/stock_histo_search.php';
	}

	function create_query_histo($p_array)
	{
		global $cn,$g_user;
		$profile=$g_user->get_profile();
		$sql = "
			select sg_id,
				sg.f_id,
				(select ad_value from fiche_Detail as fd1 where ad_id=1 and fd1.f_id=jx.f_id) as fname,
				(select ad_value from fiche_Detail as fd1 where ad_id=23 and fd1.f_id=jx.f_id) as qcode,
				sg_code,
				coalesce(sg_comment,jr_comment) as ccomment,
				sg_exercice,
				r_name,
				sg.r_id,
				j_montant,
				jr_date,
				sg_quantity,
				case when sg_type='c' then 'OUT' when sg_type='d' then 'IN' end as direction,
				jr_internal,
				jr_id,
				coalesce(sg_date,jr_date) as real_date,
				to_char(coalesce(sg_date,jr_date),'DD.MM.YY') as cdate
			from stock_goods as sg
			join stock_repository as sr on (sg.r_id=sr.r_id)
			left join jrnx as jx on (sg.j_id=jx.j_id)
			left join jrn as j on (j.jr_grpt_id=jx.j_grpt)
			where
			sg.r_id in (select r_id from user_sec_repository where p_id = $profile)";
		$and = " and ";
		$clause = "";
		if (isset($p_array['wdate_start']) && $p_array['wdate_start'] != '')
		{
			$clause = $and." to_date('" . sql_string($p_array['wdate_start']) . "','DD.MM.YYYY')<=coalesce(sg_date,jr_date) ";
		}
		if (isset($p_array['wdate_end']) && $p_array['wdate_end'] != '')
		{
			$clause.=$and . " to_date('" . sql_string($p_array['wdate_end']) . "','DD.MM.YYYY')>=coalesce(sg_date,jr_date) ";
		}
		if (isset($p_array['wamount_start']) && $p_array['wamount_start'] != '' && isNumber($p_array['wamount_start']) == 1)
		{
			$clause.=$and . " j_montant >= " . sql_string($p_array['wamount_start']);
		}
		if (isset($p_array['wamount_end'])
				&& $p_array['wamount_end'] != ''
				&& $p_array['wamount_end'] != 0
				&& isNumber($p_array['wamount_end']) == 1)
		{
			$clause.=$and . " j_montant <= " . sql_string($p_array['wamount_end']);
		}
		if (isset($p_array['wcard']) && $p_array['wcard'] != '')
		{
			$f = new Fiche($this->cn);
			$f->get_by_qcode($p_array['wcard'], false);
			if ($f->id != 0)
			{
				$clause.=$and . " sg.f_id =  " . sql_string($f->id);
			}
		}
		if (isset($p_array['wcode_stock']) && $p_array['wcode_stock'] != "")
		{
			$clause.=$and . " upper(sg_code) =  upper('" . sql_string($p_array['wcode_stock']) . "')";
		}
		if (isset($p_array['wrepo']) && $p_array['wrepo'] != -1)
		{
			$clause.=$and . " sg.r_id = " . sql_string($p_array['wrepo']);
		}
		if (isset($p_array['wdirection']) && $p_array['wdirection'] != -1)
		{
			$clause.=$and . " sg.sg_type = '" . sql_string($p_array['wdirection']) . "'";
		}
		return $sql . $clause;
	}

	function summary($p_array)
	{
		global $cn, $g_user;
		$tmp_id=$this->build_tmp_table($p_array);
		// Build condition
		$a_repository = $g_user->get_available_repository('R');
		$a_code = $cn->get_array("select distinct sg_code from tmp_stockgood_detail where s_id=$1", array($tmp_id));
		if (isset($p_array['present']))
		{
			$present = $p_array['present'];
		}
		else
		{
			$present = 'T';
		}
		if ($present == 'T')
		{
			require_once 'template/stock_summary_table.php';
		}
		if ($present == 'L')
		{
			require_once 'template/stock_summary_list.php';
			$this->export_stock_summary_list_form();

		}
		echo HtmlInput::print_window();
	}
	function build_tmp_table($p_array)
	{
		global $cn,$g_user;
		$tmp_id = $cn->get_next_seq("public.tmp_stockgood_s_id_seq");
		$cn->exec_sql("delete from tmp_stockgood where s_date < now() - interval '2 days' ");
		$cn->exec_sql("insert into tmp_stockgood(s_id) values ($1)", array($tmp_id));

		// get all readable repository
		$a_repository = $g_user->get_available_repository('R');

		// All the stock card
		$sql_repo_detail = "
			insert into tmp_stockgood_detail(s_id,sg_code,s_qin,s_qout,r_id)

		with fiche_stock as (select distinct ad_value from fiche_detail where ad_id=19 and coalesce(ad_value,'') != '') ,
				stock_in as (select coalesce(sum(sg_quantity),0) as qin,r_id,sg_code from stock_goods where sg_type='c'
				and (
					(j_id is not null and j_id in (select j_id from jrnx where  j_date <= to_date($2,'DD.MM.YYYY'))
					or sg_tech_date <= to_date($2,'DD.MM.YYYY'))
				)
				group by r_id,sg_code) ,
				stock_out as	(select coalesce(sum(sg_quantity),0) as qout ,r_id,sg_code from stock_goods
				where sg_type='d'
				and (
					(j_id is not null and j_id in (select j_id from jrnx where  j_date <= to_date($2,'DD.MM.YYYY'))
					or sg_tech_date <= to_date($2,'DD.MM.YYYY'))
				)
				group by r_id,sg_code)
				select distinct
					$tmp_id,
					coalesce(si.sg_code,so.sg_code),
					coalesce(qin,0) as qin,
					coalesce(qout,0) as qout,
					sg.r_id
					from
					stock_goods as sg
					left join stock_in as si on ( sg.r_id=si.r_id)
					full join stock_out as so on (si.sg_code=so.sg_code and sg.r_id=so.r_id)
				where
				(si.sg_code is not null or so.sg_code is not null)
				 and sg.r_id  in (select r_id from user_sec_repository where p_id=$1)

			";
		$end_date = $cn->get_value("select to_char(max(p_end),'DD.MM.YYYY') from parm_periode");
		if (isset($p_array['state_exercice']))
		{
			if (isDate($p_array['state_exercice']) == $p_array['state_exercice'])
			{
				$end_date = $p_array['state_exercice'];
			}
		}
		$cn->exec_sql($sql_repo_detail, array($g_user->get_profile(), $end_date));

		return $tmp_id;
	}

}

?>
