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


// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
// $Revision$
/*! \file
 * \brief Send a ledger in a pdf format
 *
*/

require_once('class_dossier.php');
$gDossier=dossier::id();

include_once('class_user.php');
include_once("jrn.php");
include_once("ac_common.php");
include_once("postgres.php");
include_once("class.ezpdf.php");
include_once("impress_inc.php");
include_once("class_acc_ledger.php");
require_once ('header_print.php');
require_once('class_own.php');
require_once('class_periode.php');
$cn=DbConnect($gDossier);
$periode=new Periode($cn);


echo_debug('jrn_pdf.php',__LINE__,"imp pdf journaux");

$l_type="JRN";
$centr=" Non centralisé";
$l_centr=0;
$own=new Own($cn);

if ($_GET['central'] == 'on' ) {
  $centr=utf8_decode(" centralisé ");
  $l_centr=1;
}
$Jrn=new Acc_Ledger($cn,$_GET['jrn_id']);

$Jrn->get_name();
$User=new User($cn);
$User->Check();
$User->check_dossier($gDossier);
$User->can_request(IMPJRN,0);

// Security
if ( $_GET['jrn_id']!=0 &&  $User->check_jrn($_GET['jrn_id']) == 'X' ){
    /* Cannot Access */
    NoAccess();
 }

$ret="";

// filter : 0 for Grand Livre otherwise 1
$filter=( $Jrn->id == 0)?0:1;
$jrn_type=$Jrn->get_type();
echo_debug('jrn_pdf',__LINE__,'Jrn type '.$jrn_type);
echo_debug('jrn_pdf',__LINE__,'p_simple = '.$_REQUEST['p_simple']);
//----------------------------------------------------------------------
// Detailled Printing
//---------------------------------------------------------------------
if ( $Jrn->id==0  || $jrn_type=='FIN' || $jrn_type=='ODS' || $_REQUEST['p_simple']== 0 ) 
{
  $pdf= new Cezpdf("A4");
  $pdf->selectFont('./addon/fonts/Helvetica.afm');
  header_pdf($cn,$pdf);
  // detailled printing
  $offset=0;$limit=22;$step=22;
  $rap_deb=0;$rap_cred=0;
  while (1) {
    $a=0;
    list ($a_jrn,$tot_deb,$tot_cred)=$Jrn->get_row($_GET['from_periode'],
						  $_GET['to_periode'],
						  $_GET['central'],
						  $limit,$offset);
    echo_debug('jrn_pdf.php',__LINE__,"Total debit $tot_deb,credit $tot_cred");
    
    if ( $a_jrn==null) break;
    $offset+=$step; 
    $first_id=$a_jrn[0]['int_j_id'];
    $Exercice=$periode->get_exercice($a_jrn[0]['periode']);
    

    list($rap_deb,$rap_cred)=get_rappel($cn,$first_id,$Jrn->id,$Exercice,FIRST,
				     $filter,
				     $l_centr
				     );
    echo_debug('jrn_pdf.php',__LINE__,"MONTANT $rap_deb,$rap_cred");
    echo_debug('jrn_pdf.php',__LINE__,"  list($rap_deb,$rap_cred)=get_rappel($cn,$first_id,".$Jrn->id.",$Exercice,FIRST)");
    $pdf->ezText(utf8_decode($Jrn->name),30);
    
    if (  $l_centr == 1 ) {
    // si centralisé montre les montants de rappel
      $str_debit=utf8_decode(sprintf( "report Débit  % 10.2f",$rap_deb));
      $str_credit=utf8_decode(sprintf("report Crédit % 10.2f",$rap_cred));
      $pdf->ezText($str_debit,12,array('justification'=>'right'));
      $pdf->ezText($str_credit,12,array('justification'=>'right'));
    }
    $pdf->ezTable($a_jrn,
		  array ('j_id'=>utf8_decode(' Numéro'),
			 'j_date' => 'Date',
			 'poste'=>'Poste',
			 'description' => 'Description',
			 'deb_montant'=>utf8_decode( 'Débit'),
			 'cred_montant'=>utf8_decode('Crédit')),
		  " ",
		  array('shaded'=>0,'showHeadings'=>1,'width'=>500,
			'cols'=>array('deb_montant'=> array('justification'=>'right'),
				      'cred_montant'=> array('justification'=>'right'))),
		  true);
    $a=1;
    // Total Page
    $apage=array(array('deb'=>sprintf("%8.2f",$tot_deb),'cred'=>$tot_cred));
    foreach ($apage as $key=>$element) echo_debug('jrn_pdf.php',__LINE__,"apage $key => $element");
    $pdf->ezTable($apage,
		array (
		       'deb'=>utf8_decode( 'Total Débit'),
		       'cred'=>utf8_decode('Total Crédit'))," ",
		array('shaded'=>0,'showHeadings'=>1,'width'=>200,
		      'xPos'=>'right','xOrientation'=>'left',
		      'cols'=>array('deb'=> array('justification'=>'right'),
				    'cred'=> array('justification'=>'right'))),true);

    $count=count($a_jrn)-1;
    $last_id=$a_jrn[$count]['int_j_id'];
    $Exercice=$periode->get_exercice($a_jrn[$count]['periode']);
    if ( $l_centr == 1) {
      // Montant de rappel si centralisé
      list($rap_deb,$rap_cred)=get_rappel($cn,$last_id,$Jrn->id,$Exercice,LAST,$filter,$l_centr);
      $str_debit=utf8_decode(sprintf( "à reporter Débit  % 10.2f",$rap_deb));
      $str_credit=utf8_decode(sprintf("à reporter Crédit % 10.2f",$rap_cred));
      $pdf->ezText($str_debit,12,array('justification'=>'right'));
      $pdf->ezText($str_credit,12,array('justification'=>'right'));
    }
    //New page
    $pdf->ezNewPage();
	header_pdf($cn,$pdf);

  }    
  if ( $a == 1 )   {
    $apage=array('deb'=>$tot_deb,'cred'=>$tot_cred);
    $pdf->ezTable($apage,
		  array (
			 'deb'=>utf8_decode( 'Total Débit'),
			 'cred'=>utf8_decode('Total Crédit'))," ",
		  array('shaded'=>0,'showHeadings'=>1,'width'=>500,
			'cols'=>array('deb'=> array('justification'=>'right'),
				      'cred'=> array('justification'=>'right'))),true);
    $count=count($a_jrn)-1;
    $last_id=$a_jrn[$count]['int_j_id'];
    $Exercice=$periode->get_exercice($a_jrn[$count]['periode']);
    
    list($rap_deb,$rap_cred)=get_rappel($cn,$last_id,$Jrn->id,$Exercice,LAST,$filter,$l_centr);
    $str_debit=utf8_decode(sprintf( "à reporter  Débit % 10.2f",$rap_deb));
    $str_credit=utf8_deccode(sprintf("à reporter Crédit % 10.2f",$rap_cred));
    $pdf->ezText($str_debit,12,array('justification'=>'right'));
    $pdf->ezText($str_credit,12,array('justification'=>'right'));
    
  }
  $pdf->ezStream();
  exit(0);
} // impression detaillé
//----------------------------------------------------------------------
// Simple Printing
//---------------------------------------------------------------------
if  ( ($jrn_type=='ACH' || $jrn_type=='VEN' ) && $_REQUEST['p_simple']== 1 ) 
{
  /* simple printing with vat */
  if ( $own->MY_TVA_USE=='Y') {
    echo_debug ('jrn_pdf',__LINE__,'here');
    echo_debug('jrn_pdf',__LINE__,$Jrn);
    $pdf= new Cezpdf("A4",'landscape');
    //  $pdf->selectFont('./addon/fonts/Helvetica.afm');
    $pdf->selectFont('./addon/fonts/Courier.afm');
    header_pdf($cn,$pdf);

    $offset=0;$limit=30;$step=30;
    $a_Tva=get_array($cn,"select tva_id,tva_label,tva_poste from tva_rate where tva_rate != 0.0000 order by tva_id");
    $col_tva="TVA ";
    $space=0;
    $total_HTVA=0.0;
    $total_TVAC=0.0;
    foreach($a_Tva as $line_tva)
      {
	//initialize Amount TVA
	$tmp1=$line_tva['tva_label'];
	$rap_tva[$tmp1]=0.0;
	if ( $space == 0 )
	  $col_tva=str_repeat(" ",6).utf8_decode($line_tva['tva_label']);
	else {
	  $ecart=$space-strlen($line_tva['tva_label']);
	  $ecart=($ecart<0)?0:$ecart;
	  $col_tva.=str_repeat(" ",$ecart).utf8_decode($line_tva['tva_label']);
	}
	$space=9;
      } 

    // if the period is centralized get the first amounts
    if ( $l_centr==1) 
      list($total_TVAC,$total_HTVA)=get_rappel_simple($cn,$Jrn->id,$jrn_type,$_GET['from_periode'],$rap_tva);



    while (1) {

      $a=0;
      $a_jrn=$Jrn->get_rowSimple($_GET['from_periode'],
				 $_GET['to_periode'],
				 $_GET['central'],
				 1,
				 $limit,
				 $offset);
      if ( $a_jrn == null ) break;

      // page Header 
      $t=sprintf("Rappel TVAC = %.2f HTVA= %.2f",$total_TVAC,$total_HTVA);
      foreach($rap_tva as $idx=>$am) {
	$t.=utf8_decode(sprintf('[ %s = % .2f]',$idx,$am));
      }
      $pdf->ezText($t,9,array('justification'=>'left'));

      $offset+=$step;
      //total page
      $total_htva_page=0.0;$total_tvac_page=0.0;
      foreach($a_Tva as $line_tva)
	{
	  //initialize Amount TVA
	  $tmp1=$line_tva['tva_label'];
	  $page_tva[$tmp1]=0.0;
	}
      $str_tva="";


      $pdf->ezTable($a_jrn,
		    array('num'=>utf8_decode('Numéro'),
			  'date'=>'Date',
			  'jr_internal'=>'Int.',
			  'client'=>'Client',
			  'comment'=>'Description', 
			  'HTVA'=>'HTVA',
			  'TVA_INLINE'=>$col_tva,
			  'TVAC'=>'TVAC'),
		    utf8_decode($Jrn->name),
		    array('shaded'=>0,'showHeadings'=>1,'fontSize'=>8,'width'=>750,'Maxwidth'=>750),
		    true

		       
		  
		    );
      //--------------------------------------------------------------------------------
      // Foot
      // Set total foot page
      // amount VAT
      foreach ($a_jrn as $row) 
	{
	  $total_TVAC+=$row['TVAC'];
	  $total_HTVA+=$row['HTVA'];
	  $total_tvac_page+=$row['TVAC'];
	  $total_htva_page+=$row['HTVA'];

	  foreach ($row['TVA'] as $line)
	    {
	      $tva_id=$line[1][1];
	      $rap_tva[$tva_id]+=$line[1][2];
	      $page_tva[$tva_id]+=$line[1][2];
	    }
	}
      //total page
      $t=sprintf("total page TVAC = %.2f HTVA= %.2f",$total_tvac_page,$total_htva_page);
      foreach($page_tva as $idx=>$am) {
	$t.=utf8_decode(sprintf('[ %s = % .2f ]',$idx,$am));
      }
      $pdf->ezText($t,9,array('justification'=>'left'));

      $t=utf8_decode(sprintf("total à reporter TVAC = %.2f HTVA= %.2f",$total_TVAC,$total_HTVA));
      foreach($rap_tva as $idx=>$am) {
	$t.=utf8_decode(sprintf('[ %s = % .2f ]',$idx,$am));
      }
      $pdf->ezText($t,9,array('justification'=>'left'));

      // New Page
      $pdf->ezNewPage();
      header_pdf($cn,$pdf);
      echo_debug('jrn_pdf',__LINE__,$a_jrn);
    }
  } else { 			/* we do not use any vat */

    $pdf= new Cezpdf("A4",'landscape');
    //  $pdf->selectFont('./addon/fonts/Helvetica.afm');
    $pdf->selectFont('./addon/fonts/Courier.afm');
    header_pdf($cn,$pdf);

    $offset=0;$limit=30;$step=30;
    $space=0;
    $total_HTVA=0.0;
    $total_TVAC=0.0;

    if ( $l_centr==1) 
      list($total_TVAC,$total_HTVA)=get_rappel_simple($cn,$Jrn->id,$jrn_type,$_GET['from_periode'],$rap_tva);


    while (1) {

      $a=0;
      $a_jrn=$Jrn->get_rowSimple($_GET['from_periode'],
				 $_GET['to_periode'],
				 $_GET['central'],
				 1,
				 $limit,
				 $offset);
      if ( $a_jrn == null ) break;
      // page Header 
      $t=sprintf("Rappel Total = %.2f ",$total_TVAC);

      $pdf->ezText($t,9,array('justification'=>'left'));

      $offset+=$step;
      //total page
      $total_htva_page=0.0;$total_tvac_page=0.0;


      $pdf->ezTable($a_jrn,
		    array('num'=>utf8_decode('Numéro'),
			  'date'=>'Date',
			  'client'=>'Client',
			  'jr_internal'=>'Int.',
			  'comment'=>'Description', 
			  'TVAC'=>'Total'),
		    utf8_decode($Jrn->name),
		    array('shaded'=>0,'showHeadings'=>1,'fontSize'=>8,'width'=>750,'Maxwidth'=>750),
		    true

		       
		  
		    );
      //--------------------------------------------------------------------------------
      // Foot
      // Set total foot page
      // amount VAT
      foreach ($a_jrn as $row) 
	{
	  $total_TVAC+=$row['TVAC'];
	  $total_tvac_page+=$row['TVAC'];
	  
	}
      //total page
      $t=sprintf("total page  = %.2f ",$total_tvac_page);

      $pdf->ezText($t,9,array('justification'=>'left'));

      $t=utf8_decode(sprintf("total à reporter  = %.2f ",$total_TVAC));

      $pdf->ezText($t,9,array('justification'=>'left'));

      // New Page
      $pdf->ezNewPage();
      header_pdf($cn,$pdf);
      echo_debug('jrn_pdf',__LINE__,$a_jrn);
    }

  } /* else  */
  
  $pdf->ezStream();
  exit(0);

}
?>
