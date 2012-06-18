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
 * @brief show the result to stock state in list format (more detailled)
 *
 */
$a_repo=$cn->get_array("select distinct t.r_id,r_name,r_adress,r_country from stock_repository as s join tmp_stockgood_detail as t
	on (s.r_id=t.r_id)
	where
	s_id=$1
	order by 2
	",array($tmp_id));
?>
<? for ($r=0;$r<count($a_repo);$r++):?>
<h1><?=$a_repo[$r]['r_name']?></h1>
<p><?=$a_repo[$r]['r_adress']?></p>
<p><?=$a_repo[$r]['r_country']?></p>
<table class="result">
	<tr>
		<th>Code</th>
		<th>Détail</th>
		<th style="text-align: right">IN</th>
		<th style="text-align: right">OUT</th>
		<th style="text-align: right">En Stock</th>
	</tr>
	<?
		$a_stock=$cn->get_array(
				"
					select coalesce(sum(s_qin),0) as qin,coalesce(sum(s_qout),0) as qout,sg_code
						from tmp_stockgood_detail  where r_id=$1 and s_id=$2
						group by sg_code
						order by sg_code

					",array($a_repo[$r]['r_id'],$tmp_id));
		for ($s=0;$s<count($a_stock);$s++):
			$a_card=$cn->get_array(
					"
						select f_id,vw_name,quick_code
						from vw_fiche_attr
						where
					 f_id in (
						select distinct f_id from fiche_detail
							where
							ad_id=19 and
							ad_value=$1)
						order by vw_name,quick_code
					",array($a_stock[$s]['sg_code']));
	?>
	<tr>
		<td>
			<?=HtmlInput::card_detail($a_stock[$s]['sg_code'])?>
		</td>
		<td>
			<?
				$sep="";
				for ( $c=0;$c<count($a_card);$c++):
					echo $sep.HtmlInput::card_detail($a_card[$c]['quick_code'], $a_card[$c]['vw_name'], ' class="line" ');
					$sep="  ,";
				endfor;
				if ( count($a_card)== 0 ) echo '<span class="notice"> Erreur Code non utilisé </span>';
			?>
		</td>
		<td class="num">
			<?=nbm($a_stock[$s]['qin'])?>
		</td>
		<td class="num">
			<?=nbm($a_stock[$s]['qout'])?>

		</td>
		<td class="num">
			<?=nbm(bcsub($a_stock[$s]['qin'],$a_stock[$s]['qout']))?>
		</td>
	</tr>
<? endfor; ?>
</table>
<? endfor; ?>

