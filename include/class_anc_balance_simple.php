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
  \brief manage the simple balance for CA, inherit from balance_ca
 */

require_once ('class_anc_print.php');
require_once ('class_anc_plan.php');
require_once ('ac_common.php');
include_once("class.ezpdf.php");
require_once ('header_print.php');
/*! \brief manage the simple balance for CA, inherit from balance_ca
 *
 */

class Anc_Balance_Simple extends Anc_Print {

/*! 
 * \brief load the data from the database 
 *
 * \return array
 */
  function load()
  {
	$filter=$this->set_sql_filter();
	// sum debit
	
	$sql="select m.po_id,sum(deb) as sum_deb,sum(cred) as sum_cred,";
	$sql.=" po_name||'  '||po_description as po_name";
	$sql.=" from ";
	$sql.=" (select po_id,case when oa_debit='t' then oa_amount else 0 end as deb,";
	$sql.="case when oa_debit='f' then oa_amount else 0 end as cred ";
	$sql.=" from operation_analytique join poste_analytique using(po_id)";
	$sql.=(empty($filter) == false)?" where ".$filter:"";
	$sql.=" ) as m join poste_analytique using (po_id)";
	$sql.=" where pa_id=".$this->pa_id;
	$sql.=" group by po_id,po_name,po_description";
	$sql.=" order by po_id";

	$res=ExecSql($this->db,$sql);

	if ( pg_NumRows($res) == 0 )
	  return null;
	$a=array();
	$count=0;
	$array=pg_fetch_all($res);
	foreach ($array as $row) {
	  $a[$count]['po_id']=$row['po_id'];
	  $a[$count]['sum_deb']=$row['sum_deb'];
	  $a[$count]['sum_cred']=$row['sum_cred'];
	  $a[$count]['po_name']=$row['po_name'];
	  $a[$count]['solde']=abs($row['sum_deb']-$row['sum_cred']);
	  $a[$count]['debit']=($row['sum_deb']>$row['sum_cred'])?"debit":"credit";
	  $count++;
	}
	return $a;

 
  }
/*! 
 * \brief Set the filter (account_date) 
 *
 * \return return the string to add to load
 */


  function set_sql_filter() {
	$sql="";
	$and="";
	if ( $this->from != "" ){
	  $sql.=" oa_date >= to_date('".$this->from."','DD.MM.YYYY')";
	  $and=" and ";
	}
	if ( $this->to != "" ){
	  $sql.=" $and oa_date <= to_date('".$this->to."','DD.MM.YYYY')";
	  $and=" and ";
	}
	if ( $this->from_poste != "" ){
	  $sql.=" $and upper(po_name)>= upper('".$this->from_poste."')";
	  $and=" and ";
	}
	if ( $this->to_poste != "" ){
	  $sql.=" $and upper(po_name)<= upper('".$this->to_poste."')";
	  $and=" and ";
	}
	return $sql;
	  
  }
/*! 
 * \brief compute the html display
 * 
 *
 * \return string
 */
  function display_html()
  {
	$r="<table class=\"mtitle\">";
	$r.="<tr>";
	$r.="<th>Poste comptable Analytique</th>";
	$r.="<th>D&eacute;bit</th>";
	$r.="<th>Cr&eacute;dit</th>";
	$r.="<th>Solde</th>";
	$r.="<th>D/C</th>";
	$r.="</tr>";

	$array=$this->load();
	$odd=0;
	if ( is_array($array) == false ){
	  return $array;

	}
	foreach ( $array as $row) {
	  $odd++;

	  $r.=($odd%2==0)?'<tr class="odd">':'</tr>';
	  // the name and po_id
	  //	  $r.=sprintf("<td>%s</td>",$row['po_id']);
	  $r.=sprintf("<td align=\"left\">%s</td>",$row['po_name']);
	  $r.=sprintf("<td>%12.2f</td>",$row['sum_deb']);
	  $r.=sprintf("<td>%12.2f</td>",$row['sum_cred']);
	  $r.=sprintf("<td>%12.2f</td>",$row['solde']);
	  $deb=($row['sum_deb'] > $row['sum_cred'])?"D":"C";
	  $deb=($row['solde'] == 0 )?'':$deb;
	  $r.=sprintf("<td>%s</td>",$deb);
	  $r.="</tr>";	  
	}	
	$r.="</table>";
	return $r;
  }
/*! 
 * \brief Compute  the form to display
 * \param $p_hidden hidden tag to be included (gDossier,...)
 *
 *
 * \return string containing the data
 */
  function display_form($p_string="") {
	$r=parent::display_form($p_string);

	$r.= '<input type="submit" value="Afficher">';

	return $r;
  }

/*! 
 * \brief Display the result in pdf
 *
 * \return none
 */
  function display_pdf()
  {
	$array=$this->load();
	$offset=0;
	$page=1;
	$pagesize=50;

	$count=ceil(count($array)/$pagesize);
	$pdf= new Cezpdf("A4");
	$pdf->selectFont('./addon/fonts/Helvetica.afm');
	$titre=sprintf("Balance simple poste %s %s date %s %s",
				   $this->from_poste,
				   $this->to_poste,
				   $this->from,
				   $this->to);
	for ($i_loop=0;$i_loop<=$count;$i_loop++) {	

	$view=$array;
	$view=array_splice($view,$offset,$pagesize);
	header_pdf($this->db,$pdf);
	$pdf->ezTable($view,
				  array("po_id"=>"id",
					"po_name"=>"Poste Comptable",
					"sum_deb"=>"Debit",
					"sum_cred"=>"Credit",
					"solde"=>"Solde",
					"debit"=>"Debit/Credit"),
				  $titre,
				    array('shaded'=>1,'showHeadings'=>1,'width'=>500,
					  'cols'=>array('sum_deb'=> array('justification'=>'right'),
							'solde'=> array('justification'=>'right'),
							'sum_cred'=> array('justification'=>'right'))));
	
	  $page++;
	  $pdf->ezNewPage();					


	  $offset+=$pagesize;
	}
	$pdf->ezStream();
  }
/*! 
 * \brief Compute the csv export
 * \return string with the csv
 */
  function display_csv()
  {
	$array=$this->load();	
	if ( is_array($array) == false ){
	  return $array;

	}
	$r="";
	foreach ( $array as $row) {
	  // the name and po_id
	  $solde=($row['sum_cred']>$row['sum_deb'])?'C':'D';
	  $solde=($row['sum_cred']==$row['sum_deb'])?'':$solde;
	  $r.=sprintf("'%s',",$row['po_id']);
	  $r.=sprintf("'%s',",$row['po_name']);
	  $r.=sprintf("%12.2f,",$row['sum_deb']);
	  $r.=sprintf("%12.2f,",$row['sum_cred']);
	  $r.=sprintf("%12.2f,",$row['solde']);
	  $r.=sprintf("'%s'",$row['debit']);
	  $r.="\r\n";	  
	}	
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
  function show_button($url_csv,$url_pdf,$p_string="") 
  {
	$r="";
	$submit=new widget();
	$submit->table=0;
	$hidden=new widget("hidden");
	$r.= '<form method="GET" action="'.$url_pdf.'" style="display:inline">';
	$r.= $p_string;
	$r.= dossier::hidden();
	$r.= $hidden->IOValue("to",$this->to);
	$r.= $hidden->IOValue("from",$this->from);
	$r.= $hidden->IOValue("pa_id",$this->pa_id);
	$r.= $hidden->IOValue("from_poste",$this->from_poste);
	$r.= $hidden->IOValue("to_poste",$this->to_poste);
	$r.= $submit->Submit('bt_pdf',"Export en PDF");
	$r.= '</form>';

	$r.= '<form method="GET" action="'.$url_csv.'"  style="display:inline">';
	$r.= $hidden->IOValue("to",$this->to);
	$r.= $hidden->IOValue("from",$this->from);
	$r.= $hidden->IOValue("pa_id",$this->pa_id);
	$r.= $hidden->IOValue("from_poste",$this->from_poste);
	$r.= $hidden->IOValue("to_poste",$this->to_poste);

	$r.= $p_string;
	$r.= dossier::hidden();
	$r.= $submit->Submit('bt_csv',"Export en CSV");
	$r.= '</form>';
	return $r;
}

/*! 
 * \brief for testing and debuggind the class
 *        it must never be called from production system, it is
 *        intended only for developpers
 * \param
 * \param
 * \param
 * 
 *
 * \return none
 */
static  function test_me () {
  // call the page with ?gDossier=14
  $a=DbConnect(dossier::id());
  
  $bal=new Anc_Balance_Simple($a);
  $bal->get_request();	

  echo '<form method="GET">';

  echo $bal->display_form();
  echo '</form>';
  if ( isset($_GET['result'])) {
	echo $bal->show_button("","");
	echo "<h1>HTML</h1>";
	echo $bal->display_html();
	echo "<h1>CSV</h1>";
	echo $bal->display_csv();
/* 	echo "<h1>pdf</h1>"; */
/* 	echo $bal->display_pdf(); */

  }

}
}
