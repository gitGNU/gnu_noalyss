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
 * @brief Manage the goods
 *
 */
require_once 'class_stock_goods_sql.php';

class Stock_Goods extends Stock_Goods_Sql
{

	function input($p_array = null, $p_readonly = false)
	{
		global $cn;

		if ($p_array != null)
		{
			extract($p_array);
		}
		else
		{
			$p_date = '';
			$p_motif = '';
			$p_depot = 0;
		}
		$date = new IDate('p_date', $p_date);
		$date->setReadOnly($p_readonly);
		$motif = new IText('p_motif', $p_motif);
		$motif->setReadOnly($p_readonly);
		$motif->size = 80;
		$idepo = HtmlInput::select_stock($cn, "p_depot", "W");
		$idepo->setReadOnly($p_readonly);
		if (count($idepo->value) == 0)
		{
			NoAccess();
			exit();
		}
		$idepo->selected = $p_depot;
		for ($e = 0; $e < MAX_ARTICLE; $e++)
		{
			$sg_code[$e] = new ICard('sg_code' . $e);
			$sg_code[$e]->extra = '[sql] fd_id = 500000';
			$sg_code[$e]->set_attribute("typecard", $sg_code[$e]->extra);
			$sg_code[$e]->set_attribute("label", "label" . $e);
			$sg_code[$e]->value = (isset(${'sg_code' . $e})) ? ${'sg_code' . $e} : '';
			$sg_quantity[$e] = new INum('sg_quantity' . $e);
			$sg_quantity[$e]->value = (isset(${'sg_quantity' . $e})) ? ${'sg_quantity' . $e} : '';
			$label[$e] = new ISpan("label$e");
			if (trim($sg_code[$e]->value) != '')
			{
				$label[$e]->value = $cn->get_value("select vw_name from vw_fiche_attr where quick_code=$1", array($sg_code[$e]->value));
			}
			$sg_code[$e]->setReadOnly($p_readonly);
			$sg_quantity[$e]->setReadOnly($p_readonly);
		}
		require_once 'template/stock_inv.php';
	}

	function save($p_array)
	{
		global $cn;
		try
		{
			if (isDate($p_array['p_date']) == null)
				throw new Exception('Date invalide');
			$cn->start();
			$ch = new Stock_Change_Sql();
			$ch->setp("c_comment", $p_array['p_motif']);
			$ch->setp("r_id", $p_array['p_depot']);
			$ch->setp("c_date", $p_array['p_date']);
			$ch->setp('tech_user', $_SESSION['g_user']);
			$ch->insert();
			$per = new Periode($cn);
			$periode = $per->find_periode($p_array['p_date']);
			$exercice = $per->get_exercice($periode);

			for ($i = 0; $i < MAX_ARTICLE; $i++)
			{
				$a = new Stock_Goods_Sql();
				if ($p_array['sg_quantity' . $i] != 0 &&
						trim($p_array['sg_code' . $i]) != '')
				{
					$a->sg_code = $p_array['sg_code' . $i];
					$a->sg_quantity = $p_array['sg_quantity' . $i];
					$a->sg_type = ($p_array['sg_quantity' . $i] > 0) ? 'd' : 'c';
					$a->sg_comment = $p_array['p_motif'];
					$a->tech_user = $_SESSION['g_user'];
					$a->r_id = $p_array['p_depot'];
					$a->sg_exercice = $exercice;
					$a->c_id = $ch->c_id;
					$a->insert();
				}
			}
			$cn->commit();
		}
		catch (Exception $exc)
		{
			echo $exc->getTraceAsString();
			throw $exc;
		}
	}

}

?>
