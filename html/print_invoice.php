<?

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
/* received parameters
 e_date element 01.01.2003
 e_client element 3
 nb_item element 2
 e_march0 element 11
 e_quant0 element 1
 e_march1 element 6
 e_quant1 element 2
 e_comment  invoice number
*/
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
// $Revision$

    include_once("jrn.php");
    include_once("ac_common.php");
    include_once("postgres.php");
    include_once("class.ezpdf.php");
    include_once("impress_inc.php");
	include_once("fiche_inc.php");
	include_once("user_common.php");
    echo_debug("imp pdf journaux");
    $l_Db=sprintf("dossier%d",$g_dossier);
    $cn=DbConnect($l_Db);
foreach ($HTTP_POST_VARS as $key=>$element) {
  ${"$key"}=$element;
  echo_debug("key => $key element $element");
}

$amount=0.0;
for ($i=0;$i<$nb_item;$i++) {
	// store quantity & goods in array
		$a_good[$i]=${"e_march$i"};
		$a_quant[$i]=${"e_quant$i"};
		$a_amount[$i]=GetFicheAttribut($cn,$a_good[$i],ATTR_DEF_PRIX_VENTE)*$a_quant[$i];
		$amount+=$a_amount[$i];
		$tva=GetTvaRate($cn,GetFicheAttribut($cn,$a_good[$i],ATTR_DEF_TVA));
		// Prepare the array to print
		$a_good_detail[]=array('id'=>$i+1,
											'name'=>GetFicheAttribut($cn,$a_good[$i],ATTR_DEF_NAME),
										  'quantity'=>$a_quant[$i],
										  'Unit'        =>GetFicheAttribut($cn,$a_good[$i],ATTR_DEF_PRIX_VENTE),
										  'Total HTVA' => $a_amount[$i],
										  'tva'=>$tva['tva_label'],
										  'Total' => $a_amount[$i]+$a_amount[$i]*$a_quant[$i]*$tva['tva_rate']);
	}
	
	$a_vat=ComputeVat($cn,	$a_good,$a_quant,ATTR_DEF_PRIX_VENTE);
	$sum_vat=0.0;
	foreach ( $a_vat as $element => $t) {
	echo_debug(" a_vat element $element t $t");
	$sum_vat+=$t;
	echo_debug("sum_vat = $sum_vat");
	}

    $ret="";
    $pdf=& new Cezpdf('a4');
    $pdf->selectFont('./addon/fonts/Helvetica.afm');
	$pdf->ezSetCmMargins(2,2,2,2);
	// Write the date
	 $pdf->ezText("Date : ".$e_date,10,array('justification'=>'right'));
	 
	 // write the customer data
     $info[0]=array('info'=>GetFicheAttribut($cn,$e_client,ATTR_DEF_NAME));
	 $info[1]=array('info'=>GetFicheAttribut($cn,$e_client,ATTR_DEF_ADRESS));
	 $info[2]=array('info'=>GetFicheAttribut($cn,$e_client,ATTR_DEF_CP));
 	 $info[3]=array('info'=>GetFicheAttribut($cn,$e_client,ATTR_DEF_PAYS));
	$pdf->ezSetDy(-50);
	$pdf->ezTable($info, null, "Informations"
							, array('width'=>200,'fontSize'=>12,'shaded'=>0,'xOrientation'=>'left','xPos'=>'right','showHeadings'=>0));	
	
	// Write the invoice number
	$pdf->ezSetDy(-50);
	$pdf->ezText ($e_comment,14,array('justification'=>'center'));
	
	// Write the goods details						
	$pdf->ezSetDy(-50);
	$pdf->ezTable($a_good_detail,null,'Detail invoice',
		array('width'=>400,'cols'=>array(
		'quantity'=>array('justification'=>'right'),
		'Unit'=>array('justification'=>'right'),
		'Total HTVA'=>array('justification'=>'right'),
		'tva'=>array('justification'=>'right'),
		'Total'=>array('justification'=>'right')
		)));
	
	
	// Write the sum detail 
	$sum_detail[0] = array ('label'=>'Total HTVA ','amount'=>$amount);
	$index=1;
	foreach ( $a_vat as $element => $t) {
		echo_debug(" a_vat element $element t $t");
		$a_tva_sum=GetTvaRate($cn,$element);
		$sum_detail[++$index]=array('label'=>"tva ". $a_tva_sum['tva_label'],'amount'=>$t);
	}
	$total=$amount+$sum_vat;
	$sum_detail[++$index]=array('label'=>"Total", 'amount'=>$total);
	$pdf->ezSetDy(-50);
	$pdf->ezTable($sum_detail,null,'',
		array('width'=>200,'showHeadings'=>0,'shaded'=>0,'cols'=>array('amount'=>array('justification'=>'right'))));


// wRITE THE DATE LIMIT
	$pdf->ezSetDy(-50);
$pdf->ezText(" Limit date : ".$e_ech);



// // Make a file of it
// //$pdfcode=" I don't know whether the prob comes from the ezPdf or from Apache or from Mysql";
// $pdfcode=$pdf->ezOutput();
// $fp=fopen("invoice.pdf","wb+");
// fwrite($fp,$pdfcode);
// fclose ($fp);

$pdf->ezStream();


?>
