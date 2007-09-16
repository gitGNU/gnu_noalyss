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
 *  Print the crossed balance between 2 plan 
 *
 */
require_once ('class_print_ca.php');
require_once ('class_plananalytic.php');

class balance_ca_bc extends print_ca 
{
/*! 
 * \brief compute the html display
 * 
 *
 * \return string
 */

  function display_html ()
  {
	$r="";

	$array=$this->get_data();
	$odd=0;
	if ( is_array($array) == false ){
	  return $array;

	}
	$old="";
	$tot_deb=0;
	$tot_cred=0;

	foreach ( $array as $row) {
	  $odd++;

	  $r.=($odd%2==0)?'<tr class="odd">':'</tr>';
	  // the name and po_id
	  //	  $r.=sprintf("<td>%s</td>",$row['po_id']);
	  if ( $old == $row['a_po_name'] ) {
		$r.='<td></td>';
	  } else {

		if ( $tot_deb != 0 && $tot_cred !=0 ) {
		  $r.="<tr>";
		  $r.="<td>Total </td> <td></td><td> $tot_deb </td> <td>$tot_cred</td>";
		  $s=abs($tot_deb-$tot_cred);
		  $d=($tot_deb>$tot_cred)?'debit':'credit';
		  $r.="<td>$s</td><td>$d</td>";
		  $r.="</tr>";
		}
		$tot_deb=0;
		$tot_cred=0;

		// new 	
		$r.="</table>";
		$r.="<table class=\"mtitle\">";
		$r.="<tr>";
		$r.="<th>Poste comptable Analytique</th>";
		$r.="<th>Poste comptable Analytique</th>";
		$r.="<th>D&eacute;bit</th>";
		$r.="<th>Cr&eacute;dit</th>";
		$r.="<th>Solde</th>";
		$r.="<th>D/C</th>";
		$r.="</tr>";

		$r.=sprintf("<td>%s</td>",$row['a_po_name']);
		$old=$row['a_po_name'];
	  }
	  $tot_deb+=$row['a_d'];
	  $tot_cred+=$row['a_c'];

	  $r.=sprintf("<td>%s</td>",$row['b_po_name']);
	  $r.=sprintf("<td>%12.2f</td>",$row['a_d']);
	  $r.=sprintf("<td>%12.2f</td>",$row['a_c']);
	  $r.=sprintf("<td>%12.2f</td>",$row['a_solde']);
	  $r.=sprintf("<td>%s</td>",$row['a_debit']);
	  $r.="</tr>";	  
	}	
	if ( $tot_deb != 0 || $tot_cred !=0 ) {
	  $r.="<tr>";
	  $r.="<td>Total </td> <td></td><td> $tot_deb </td> <td>$tot_cred</td>";
	  $s=abs($tot_deb-$tot_cred);
	  $d=($tot_deb>$tot_cred)?'debit':'credit';
	  $r.="<td>$s</td><td>$d</td>";
	  $r.="</tr>";
	}

	$r.="</table>";
	return $r;
  }
/*! 
 * \brief Display the result in pdf
 *
 * \return none
 */
  function display_pdf()
  {
	$array=$this->get_data();
	$offset=0;
	$page=1;
	$pagesize=50;

	$count=ceil(count($array)/$pagesize);
	$pdf= new Cezpdf("A4");
	$pdf->selectFont('./addon/fonts/Helvetica.afm');
	$pa=new PlanAnalytic($this->db,$this->pa_id);
	$pa->get();
	$pb=new PlanAnalytic($this->db,$this->pa_id2);
	$pb->get();

	$titre=sprintf("Balance croise plan %s %s ",
				   $pa->name,
				   $pb->name);
	$filtre_date="";
	$filtre_pa="";
	$filtre_pb="";

	if ( $this->from !="" ||$this->to !="")
	  $filtre_date=sprintf("Filtre date  %s %s",
					  $this->from,
					  $this->to);
	if ( $this->from_poste !="" ||$this->to_poste !="")
	  $filtre_pa=sprintf("Filtre poste plan1  %s %s",
						 ($this->from_poste!="")?"de ".$this->from_poste:" ",
						 ($this->to_poste!="")?"jusque ".$this->to_poste:"");

	if ( $this->from_poste2 !="" ||$this->to_poste2 !="")
	  $filtre_pb=sprintf("Filtre poste plan2   %s  %s",
						 ($this->from_poste2!="")?"de ".$this->from_poste2:" ",
						 ($this->to_poste2!="")?"jusque ".$this->to_poste2:"");


	for ($i_loop=0;$i_loop<=$count;$i_loop++) {	

	  $view=$array;
	  $view=array_splice($view,$offset,$pagesize);
	  header_pdf($this->db,$pdf);
	  $pdf->ezText($filtre_date,'8');
	  $pdf->ezText($filtre_pa,'8');
	  $pdf->ezText($filtre_pb,'8');

	  $pdf->ezTable($view,
					array("a_po_name"=>"id",
						  "b_po_name"=>"Poste Comptable",
						  "a_d"=>"Debit",
						  "a_c"=>"Credit",
						  "a_solde"=>"Solde",
						  "a_debit"=>"Debit/Credit"),
				  $titre,
				    array('shaded'=>1,'showHeadings'=>1,'width'=>500,
						  'cols'=>array('a_d'=> array('justification'=>'right'),
										'a_solde'=> array('justification'=>'right'),
										'a_c'=> array('justification'=>'right'))));
	  
	  $page++;
	  $pdf->ezNewPage();					
	  
	  $offset+=$pagesize;
	}
	$this->show_pdf_sum($pdf,$array);
	$pdf->ezStream();
  }


/*! 
 * \brief Compute the csv export
 * \return string with the csv
 */
  function display_csv()
  {
	$r="";

	$r.='"Poste comptable Analytique",';
	$r.='"Poste comptable Analytique",';
	$r.='"Debit",';
	$r.='"Credit",';
	$r.='"Solde",';
	$r.='"D/C",';

	$r.="\r\n";

	$array=$this->get_data();
	if ( is_array($array) == false ){
	  return $array;

	}
	foreach ( $array as $row) {

	  $r.=sprintf('"%s",',$row['a_po_name']);
	  $r.=sprintf('"%s",',$row['b_po_name']);
	  $r.=sprintf("%12.2f,",$row['a_d']);
	  $r.=sprintf("%12.2f,",$row['a_c']);
	  $r.=sprintf("%12.2f,",$row['a_solde']);
	  $r.=sprintf('"%s"',$row['a_debit']);
	  $r.="\r\n";	  
	}	

	return $r;

  }
/*! 
 * \brief Compute  the form to display
 * \param $p_hidden hidden tag to be included (gDossier,...)
 *
 *
 * \return string containing the data
 */
  function display_form($p_string='')
  {
	$r=parent::display_form($p_string);
	// show the second plan
	$r.='<span style="padding:5px;margin:5px;border:2px double  blue;display:block;">';
	$plan=new PlanAnalytic($this->db);
	$plan_id=new widget("select","","pa_id2");
 	$plan_id->value=make_array($this->db,"select pa_id, pa_name from plan_analytique order by pa_name");
	$plan_id->selected=$this->pa_id2;
	$r.= "Plan Analytique :".$plan_id->IOValue();

	$poste=new widget("text");
	$poste->size=10;
	$r.="Entre le poste ".$poste->IOValue("from_poste2",$this->from_poste2);
	$choose=new widget("button");
	$choose->name="Choix Poste";
	$choose->label="Recherche";
	$choose->javascript="onClick=search_ca('".$_REQUEST['PHPSESSID']."',".dossier::id().",'from_poste2','pa_id')";
	$r.=$choose->IOValue();

	$r.=" et le poste ".$poste->IOValue("to_poste2",$this->to_poste2);
	$choose->javascript="onClick=search_ca('".$_REQUEST['PHPSESSID']."',".dossier::id().",'to_poste2','pa_id2')";
	$r.=$choose->IOValue();
	$r.='<span class="notice" style="display:block">Selectionnez le plan qui vous int&eacute;resse avant de cliquer sur Recherche</span>';

	$r.='</span>';
	$r.='<input type="SUBMIT" value="Afficher">';
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
	$r.= $hidden->IOValue("pa_id2",$this->pa_id2);
	$r.= $hidden->IOValue("from_poste2",$this->from_poste2);
	$r.= $hidden->IOValue("to_poste2",$this->to_poste2);
	$r.= $submit->Submit('bt_pdf',"Export en PDF");
	$r.= '</form>';

	$r.= '<form method="GET" action="'.$url_csv.'"  style="display:inline">';
	$r.= $hidden->IOValue("to",$this->to);
	$r.= $hidden->IOValue("from",$this->from);
	$r.= $hidden->IOValue("pa_id",$this->pa_id);
	$r.= $hidden->IOValue("from_poste",$this->from_poste);
	$r.= $hidden->IOValue("to_poste",$this->to_poste);
	$r.= $hidden->IOValue("pa_id2",$this->pa_id2);
	$r.= $hidden->IOValue("from_poste2",$this->from_poste2);
	$r.= $hidden->IOValue("to_poste2",$this->to_poste2);
	$r.= $p_string;
	$r.= dossier::hidden();
	$r.= $submit->Submit('bt_csv',"Export en CSV");
	$r.= '</form>';
	return $r;

}
/*! 
 * \brief complete the object with the data in $_REQUEST 
 */
  function get_request()
  {
	parent::get_request();
	$this->from_poste2=(isset($_REQUEST['from_poste2']))?$_REQUEST['from_poste2']:"";
	$this->to_poste2=(isset($_REQUEST['to_poste2']))?$_REQUEST['to_poste2']:"";
	$this->pa_id2=(isset($_REQUEST['pa_id2']))?$_REQUEST['pa_id2']:"";

  }
/*! 
 * \brief load the data from the database 
 *
 * \return array
 */
  function get_data()
  {
	$filter_poste="";
	$and="";
	if ( $this->from_poste != "" ){
	  $filter_poste.=" $and upper(pa.po_name)>= upper('".pg_escape_string($this->from_poste)."')";
	  $and=" and ";

	}
	if ( $this->to_poste != "" ){
	  $filter_poste.=" $and upper(pa.po_name)<= upper('".pg_escape_string($this->to_poste)."')";
	  $and=" and ";
	}

	if ( $this->from_poste2 != "" ){
	  $filter_poste.=" $and upper(pb.po_name)>= upper('".pg_escape_string($this->from_poste2)."')";
	  $and=" and ";
	}
	if ( $this->to_poste2 != "" ){
	  $filter_poste.=" $and upper(pb.po_name)<= upper('".pg_escape_string($this->to_poste2)."')";
	  $and=" and ";
	}
	if ( $filter_poste != "")
	  $filter_poste.=" where ".$filter_poste;

	$sql="
select  a_po_id ,
pa.po_name as a_po_name,
b_po_id,
pb.po_name as b_po_name,
sum(a_oa_amount_c) as a_c,
sum(a_oa_amount_d) as a_d
from (select 
a.po_id as a_po_id,
b.po_id as b_po_id,
case when a.oa_debit='t' then a.oa_amount else 0 end as a_oa_amount_d,
case when a.oa_debit='f' then a.oa_amount else 0 end as a_oa_amount_c
from 
operation_analytique as a join operation_analytique as b using (oa_group) 
where a.pa_id=".
$this->pa_id."
 and b.pa_id=".$this->pa_id2."  ".$this->set_sql_filter()."
) as m join poste_analytique as pa on ( a_po_id=pa.po_id) 
join poste_analytique as pb on (b_po_id=pb.po_id)

$filter_poste

 group by a_po_id,b_po_id,pa.po_name,pb.po_name 
order by 1;
";


	$res=ExecSql($this->db,$sql);

	if ( pg_NumRows($res) == 0 )
	  return null;
	$a=array();
	$count=0;
	$array=pg_fetch_all($res);
	foreach ($array as $row) {
	  $a[$count]['a_po_id']=$row['a_po_id'];
	  $a[$count]['a_d']=$row['a_d'];
	  $a[$count]['a_c']=$row['a_c'];
	  $a[$count]['b_po_id']=$row['b_po_id'];

	  $a[$count]['a_po_name']=$row['a_po_name'];
	  $a[$count]['b_po_name']=$row['b_po_name'];
	  $a[$count]['a_solde']=abs($row['a_d']-$row['a_c']);
	  $a[$count]['a_debit']=($row['a_d']>$row['a_c'])?"debit":"credit";

	  $count++;
	}
	return $a;


  }
/*! 
 * \brief Set the filter (account_date) 
 *
 * \return return the string to add to get_data
 */

  function set_sql_filter()
  {
	$sql="";
	$and=" and ";
	if ( $this->from != "" ){
	  $sql.="$and a.oa_date >= to_date('".$this->from."','DD.MM.YYYY')";
	}
	if ( $this->to != "" ){
	  $sql.=" $and a.oa_date <= to_date('".$this->to."','DD.MM.YYYY')";
	}

	return $sql;
	  
  }
  
/*! 
 * \brief add extra lines to PDF with sum of each account
 * \param $pdf pdf object
 * \param $p_array array returned by get_data()
 */
  function show_pdf_sum (&$pdf,$p_array)
{
  $tot_deb=0;
  $tot_cred=0;
  $old="";
  $first=true;
	foreach ( $p_array as $row) {

	  if ( $old != $row['a_po_name'] && $first==false ) 

		{
		  $s=abs($tot_deb-$tot_cred);
		  $d=($tot_deb>$tot_cred)?'debit':'credit';
		  $r=sprintf(" $old Debit %12.2f Credit 12.2f solde %12.2f %s",
					 $tot_deb,
					 $tot_cred,
					 $s,
					 $d);
		  $pdf->ezText($r,9);
		  $tot_deb=0;
		  $tot_cred=0;
		  
		  $old=$row['a_po_name'];
	  }

	  if ( $first ) {
		$first=false;
		$old=$row['a_po_name'];
	  }

	  $tot_deb+=$row['a_d'];
	  $tot_cred+=$row['a_c'];


	}
	$s=abs($tot_deb-$tot_cred);
	$d=($tot_deb>$tot_cred)?'debit':'credit';
	$r=sprintf(" %s Debit %12.2f Credit %12.2f solde %12.2f %s",
			   $old,
			   $tot_deb,
			   $tot_cred,
			   $s,
			   $d);
	$pdf->ezText($r,9);

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
 static function test_me()
  {
  $a=DbConnect(dossier::id());
  
  $bal=new balance_ca_bc($a);
  $bal->get_request();	

  echo '<form method="GET">';

  echo $bal->display_form();
  echo '</form>';
  if ( isset($_GET['result'])) {
	print_r($bal);
	echo $bal->show_button("","");
	echo "<h1>HTML</h1>";
	echo $bal->display_html();
	echo "<h1>CSV</h1>";
	echo $bal->display_csv();

  }
  }
}
