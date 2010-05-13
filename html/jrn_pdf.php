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
require_once('class_pdf.php');
include_once('class_user.php');
include_once("jrn.php");
include_once("ac_common.php");
require_once('class_database.php');
include_once("impress_inc.php");
include_once("class_acc_ledger.php");
require_once('class_own.php');
require_once('class_periode.php');
$cn=new Database($gDossier);
$periode=new Periode($cn);

$l_type="JRN";
$own=new Own($cn);

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

//----------------------------------------------------------------------
// Detailled Printing
//---------------------------------------------------------------------
if ( $Jrn->id==0  || $jrn_type=='FIN' || $jrn_type=='ODS' || $_REQUEST['p_simple']== 0 ) 
  {
    $pdf=new PDF($cn);
    $pdf->setDossierInfo($Jrn->name);
    $pdf->AliasNbPages();
    $pdf->AddPage();

    // detailled printing
    $rap_deb=0;$rap_cred=0;
    // take all operations from jrn 
    $array=$Jrn->get_operation($_GET['from_periode'],$_GET['to_periode']);

    $pdf->SetFont('DejaVu','BI',7);
    $pdf->Cell(160,7,'report Débit',0,0,'R');
    $pdf->Cell(30,7,sprintf('%10.2f',$rap_deb),0,0,'R');$pdf->Ln(4);
    $pdf->Cell(160,7,'report Crédit',0,0,'R');
    $pdf->Cell(30,7,sprintf('%10.2f',$rap_cred),0,0,'R');$pdf->Ln(4);

    // print all operation
    for ($i=0;$i< count($array);$i++) {
      $pdf->SetFont('DejaVuCond','B',7);
      $row=$array[$i];

      $pdf->Cell(15,7,$row['id']);
      $pdf->Cell(20,7,$row['internal']);
      $pdf->Cell(15,7,$row['date_fmt']);
      $pdf->Cell(100,7,$row['comment']);
      $pdf->Cell(20,7,$row['pj']);
      $pdf->Cell(20,7,$row['montant'],0,0,'R');

      $pdf->Ln(4);
      // get the entries
      $aEntry=$cn->get_array("select j_id,j_poste,j_qcode,j_montant,j_debit, ".
			     " pcm_lib ".
			     " from jrnx join tmp_pcmn on (j_poste=pcm_val) where j_grpt = $1".
			     " order by j_debit,j_id",
			     array($row['jr_grpt_id']));
      for ($j=0;$j<count($aEntry);$j++) {
	$pdf->SetFont('DejaVuCond','',7);
	$entry=$aEntry[$j];
	// $pdf->Cell(15,6,$entry['j_id'],0,0,'R');
	$pdf->Cell(32,6,$entry['j_qcode'],0,0,'R');
	$pdf->Cell(23,6,$entry['j_poste'],0,0,'R');

	// if j_qcode is not empty retrieve name
	if ( $entry['j_qcode'] != '') { 
	  $f_id=$cn->get_value('select f_id from vw_poste_qcode where j_qcode=$1',array($entry['j_qcode']));
	  $name=$cn->get_value('select av_text from attr_value join jnt_fic_att_value using(jft_id) where f_id=$1 and ad_id=1',
			       array($f_id));
	} else
	  $name=$entry['pcm_lib'];
	$pdf->Cell(80,6,$name,0,0,'L');

	// print amount
	$str_amount=sprintf('%10.2f',$entry['j_montant']);
	if ( $entry['j_debit']=='t') {
	  $pdf->Cell(20,6,$str_amount,0,0,'R');
	  $pdf->Cell(20,6,'',0,0,'R');
	} else {
	  $pdf->Cell(20,6,'',0,0,'R');
	  $pdf->Cell(20,6,$str_amount,0,0,'R');
	}
	$pdf->Ln(4); 
      }
    }
    $fDate=date('dmy-Hi');
    $pdf->Output('journal-'.$fDate.'.pdf','I');
    exit(0);
  } // impression detaillé
//----------------------------------------------------------------------
// Simple Printing Purchase Ledger
//---------------------------------------------------------------------
if   ( ($jrn_type=='VEN' || $jrn_type=='ACH')  && $_REQUEST['p_simple']== 1 ) 
  {
    $pdf= new PDFLand($cn,'L');
    $pdf->setDossierInfo($Jrn->name);
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $offset=0;$limit=30;$step=30;

    /* simple printing with vat */
    if ( $own->MY_TVA_USE=='Y') {
      $pdf->SetFont('DejaVu','BI',7);
  

      // Show column header, if $flag_tva is false then display vat as column
      $a_Tva=$Jrn->existing_vat();
      foreach($a_Tva as $line_tva)
	{
	  //initialize Amount TVA
	  $tmp1=$line_tva['tva_id'];
	  $rap_tva[$tmp1]=0;
	} 
      $flag_tva=(count($a_Tva) > 4)?true:false;
      $pdf->Cell(15,6,'PJ');
      $pdf->Cell(15,6,'Date');
      if ( $jrn_type=='ACH')
	$pdf->Cell(20,6,'Client');
      else 
	$pdf->Cell(20,6,'Fournisseur');
      if ( !$flag_tva )      $pdf->Cell(65,6,'Description');

      $pdf->Cell(15,6,'HTVA',0,0,'R');
      if ( $jrn_type=='ACH') 
	{
	  $pdf->Cell(15,6,'Privé',0,0,'R');
	  $pdf->Cell(15,6,'TVA ND',0,0,'R');
	}
      foreach($a_Tva as $line_tva) {
	$pdf->Cell(15,6,$line_tva['tva_label'],0,0,'R');
      }
      $pdf->Cell(15,6,'TVAC',0,0,'R');
      $pdf->Ln(5);

      $a_jrn=$Jrn->get_operation($_GET['from_periode'],
				 $_GET['to_periode']);

      if ( $a_jrn == null ) exit();
      /*
       * get rappel to initialize amount rap_xx
       *the easiest way is to compute sum from quant_
       */
      $previous=$Jrn->previous_amount($_GET['from_periode']);
      
      /* initialize the amount to report */
      foreach($previous['tva'] as $line_tva)
	{
	  //initialize Amount TVA
	  $tmp1=$line_tva['tva_id'];
	  $rap_tva[$tmp1]=$line_tva['sum_vat'];
	} 
      $rap_htva=$previous['price'];$rap_tvac=$previous['price']+$previous['vat'];$rap_priv=$previous['priv'];$rap_nd=$previous['tva_nd_recup'];

      $pdf->SetFont('DejaVu','',6);
      // page Header 
      $pdf->Cell(130,6,sprintf('%.2f',$previous['price']),0,0,'R'); /* HTVA */
      if ( $jrn_type != 'VEN') {
	$pdf->Cell(15,6,sprintf('%.2f',$previous['priv']),0,0,'R');  /* prive */
	$pdf->Cell(15,6,sprintf('%.2f',$previous['tva_nd_recup']),0,0,'R');  /* Tva ND */
      }
      foreach($previous['tva'] as $line_tva)
	$pdf->Cell(15,6,sprintf('%.2f',$line_tva['sum_vat']),0,0,'R');
      $pdf->Cell(15,6,sprintf('%.2f',$previous['price']+$previous['vat']),0,0,'R'); /* Tvac */

      $pdf->Ln(6);


      $offset+=$step;
      $new_page=false;

      //total page
      $tp_htva=0.0;$tp_tvac=0.0;$tp_priv=0;$tp_nd=0;
      foreach($a_Tva as $line_tva)
	{
	  //initialize Amount TVA
	  $tmp1=$line_tva['tva_id'];
	  $tp_tva[$tmp1]=0.0;
	}

      for ( $i=0;$i<count($a_jrn);$i++){

	if ( $new_page ) {
	  $new_page=false;
	  /* reset total page */
	  foreach($a_Tva as $line_tva)
	    {
	      //initialize Amount TVA
	      $tmp1=$line_tva['tva_id'];
	      $tp_tva[$tmp1]=0.0;
	    }
	  //total page
	  $tp_htva=0.0;$tp_tvac=0.0;$tp_priv=0;$tp_nd=0;
	  $pdf->SetFont('DejaVu','BI',7);
	  $pdf->AddPage();
	  // page Header 
	  $pdf->Cell(15,6,'PJ');
	  $pdf->Cell(15,6,'Date');
	  if ( $jrn_type=='ACH')
	    $pdf->Cell(20,6,'Client');
	  else 
	    $pdf->Cell(20,6,'Fournisseur');
	  if ( ! $flag_tva ) $pdf->Cell(65,6,'Description');

	  $pdf->Cell(15,6,'HTVA',0,0,'R');
	  if ($jrn_type !='VEN') {
	    $pdf->Cell(15,6,'Privé',0,0,'R');
	    $pdf->Cell(15,6,'TVA ND',0,0,'R');
	  }
	  if ( ! $flag_tva ) {
	    foreach($a_Tva as $line_tva) {
	      $pdf->Cell(15,6,$line_tva['tva_label'],0,0,'R');
	    }
	  }
	  $pdf->Cell(15,6,'TVAC',0,0,'R');
	  $pdf->Ln(5);
	  $pdf->SetFont('DejaVu','',6);
	  $pdf->Cell(130,6,sprintf('%.2f',$rap_htva),0,0,'R'); /* HTVA */
	  if($jrn_type !='VEN') {
	    $pdf->Cell(15,6,sprintf('%.2f',$rap_priv),0,0,'R');  /* prive */
	    $pdf->Cell(15,6,sprintf('%.2f',$rap_nd),0,0,'R');  /* Tva ND */
	  }
	  foreach($a_Tva as $line_tva) {
	    $l=$line_tva['tva_id'];
	    $pdf->Cell(15,6,sprintf('%.2f',$rap_tva[$l]),0,0,'R');
	  }
	  $pdf->Cell(15,6,sprintf('%.2f',$rap_tvac),0,0,'R'); /* Tvac */
	  
	  $pdf->Ln(6);

	}  

	/* initialize tva */
	for ($f=0;$f<count($a_Tva);$f++) {
	  $l=$a_Tva[$f]['tva_id'];
	  $atva_amount[$l]=0;
	}
	  
	// retrieve info from ledger
	$aAmountVat=$Jrn->vat_operation($a_jrn[$i]['jr_grpt_id']);

	// put vat into array
	for ($f=0;$f<count($aAmountVat);$f++) {
	  $l=$aAmountVat[$f]['tva_id'];
	  $atva_amount[$l]=$aAmountVat[$f]['sum_vat'];
	  $tp_tva[$l]+=$aAmountVat[$f]['sum_vat'];
	  $rap_tva[$l]+=$aAmountVat[$f]['sum_vat'];
	}

	$row=$a_jrn[$i];
	$pdf->Cell(15,6,($row['pj']));
	$pdf->Cell(15,6,shrink_date($row['date_fmt']));
	$pdf->Cell(20,6,$row['internal']);
	$pdf->Cell(65,6,$row['comment']);
	/* get other amount (without vat, total vat included, private, ND */
       	$other=$Jrn->get_other_amount($a_jrn[$i]['jr_grpt_id']);
	$tp_htva+=$other['price'];
	$tp_tvac+=$other['price']+$other['vat'];
	$tp_priv+=$other['priv'];
	$tp_nd+=$other['priv'];
	$rap_htva+=$other['price'];
	$rap_tvac+=$other['price']+$other['vat'];
	$rap_priv+=$other['priv'];
	$rap_nd+=$other['priv'];


	$pdf->Cell(15,6,sprintf("%.2f",$other['price']),0,0,'R');
	if ( $jrn_type !='VEN') {
	  $pdf->Cell(15,6,sprintf("%.2f",$other['priv']),0,0,'R');
	  $pdf->Cell(15,6,sprintf("%.2f",$other['tva_nd_recup']),0,0,'R');
	}
	foreach ($atva_amount  as $row_atva_amount) {
	  $pdf->Cell(15,6,sprintf("%.2f",$row_atva_amount),0,0,'R');
	}
	$pdf->Cell(15,6,sprintf("%.2f",($other['price']+$other['vat'])),0,0,'R');
	$pdf->Ln(5);
	/* footer */
	/* every  lines reset total page and print table footer and set flag new_page to true */
	if ($i>0 && $i % 26 == 0) {
	  $pdf->Cell(115,6,'total page',0,0,'R'); /* HTVA */
	  $pdf->Cell(15,6,sprintf('%.2f',$tp_htva),0,0,'R'); /* HTVA */
	  if ( $jrn_type !='VEN') {
	    $pdf->Cell(15,6,sprintf('%.2f',$tp_priv),0,0,'R');  /* prive */
	    $pdf->Cell(15,6,sprintf('%.2f',$tp_nd),0,0,'R');  /* Tva ND */
	  }
	  foreach($a_Tva as $line_tva) {
	    $l=$line_tva['tva_id'];
	    $pdf->Cell(15,6,sprintf('%.2f',$tp_tva[$l]),0,0,'R');
	  }
	  $pdf->Cell(15,6,sprintf('%.2f',$tp_tvac),0,0,'R'); /* Tvac */
	  $pdf->Ln(2);

	  $pdf->Cell(115,6,'report',0,0,'R'); /* HTVA */
	  $pdf->Cell(15,6,sprintf('%.2f',$rap_htva),0,0,'R'); /* HTVA */
	  if ( $jrn_type != 'VEN') {
	    $pdf->Cell(15,6,sprintf('%.2f',$rap_priv),0,0,'R');  /* prive */
	    $pdf->Cell(15,6,sprintf('%.2f',$rap_nd),0,0,'R');  /* Tva ND */
	  }
	  foreach($a_Tva as $line_tva) {
	    $l=$line_tva['tva_id'];
	    $pdf->Cell(15,6,sprintf('%.2f',$rap_tva[$l]),0,0,'R');
	  }
	  $pdf->Cell(15,6,sprintf('%.2f',$rap_tvac),0,0,'R'); /* Tvac */
	  $pdf->Ln(2);
	  $new_page=true;
	}
      }
      /* last footer */
      if ( ! $new_page) {
	  $pdf->Cell(115,6,'Total page ',0,'T','R'); /* HTVA */
	  $pdf->Cell(15,6,sprintf('%.2f',$tp_htva),0,'T','R'); /* HTVA */
	  if ( $jrn_type !='VEN') {
	    $pdf->Cell(15,6,sprintf('%.2f',$tp_priv),0,'T','R');  /* prive */
	    $pdf->Cell(15,6,sprintf('%.2f',$tp_nd),0,'T','R');  /* Tva ND */
	  }
	  foreach($a_Tva as $line_tva) {
	    $l=$line_tva['tva_id'];
	    $pdf->Cell(15,6,sprintf('%.2f',$tp_tva[$l]),0,'T','R');
	  }
	  $pdf->Cell(15,6,sprintf('%.2f',$tp_tvac),0,'T','R'); /* Tvac */
	  $pdf->Ln(2);

	  $pdf->Cell(115,6,'report',0,0,'R'); /* HTVA */
	  $pdf->Cell(15,6,sprintf('%.2f',$rap_htva),0,0,'R'); /* HTVA */
	  if ( $jrn_type !='VEN') {
	    $pdf->Cell(15,6,sprintf('%.2f',$rap_priv),0,0,'R');  /* prive */
	    $pdf->Cell(15,6,sprintf('%.2f',$rap_nd),0,0,'R');  /* Tva ND */
	  }
	  foreach($a_Tva as $line_tva) {
	    $l=$line_tva['tva_id'];
	    $pdf->Cell(15,6,sprintf('%.2f',$rap_tva[$l]),0,0,'R');
	  }
	  $pdf->Cell(15,6,sprintf('%.2f',$rap_tvac),0,0,'R'); /* Tvac */
	  $pdf->Ln(2);
	  $new_page=true;
      }
    
    } else { 		
      /* ---------------------------------------------------------------------- */
      /* we do not use any vat                                                  */
      /* ---------------------------------------------------------------------- */
      $pdf->SetFont('DejaVu','BI',7);
      $pdf->Cell(15,6,'PJ');
      $pdf->Cell(15,6,'Date');
      if ( $jrn_type=='ACH')
	$pdf->Cell(20,6,'Client');
      else 
	$pdf->Cell(20,6,'Fournisseur');
      $pdf->Cell(65,6,'Description');

      if ( $jrn_type=='ACH') 
	{
	  $pdf->Cell(15,6,'Privé',0,0,'R');
	}
      $pdf->Cell(15,6,'TVAC',0,0,'R');
      $pdf->Ln(5);

      $a_jrn=$Jrn->get_operation($_GET['from_periode'],
				 $_GET['to_periode']);
      if ( $a_jrn == null ) exit();
      /*
       * get rappel to initialize amount rap_xx
       *the easiest way is to compute sum from quant_
       */
      $previous=$Jrn->previous_amount($_GET['from_periode']);
      
      $rap_htva=$previous['price'];$rap_tvac=$previous['price']+$previous['vat'];$rap_priv=$previous['priv'];$rap_nd=$previous['tva_nd_recup'];

      $pdf->SetFont('DejaVu','',6);
      // page Header 
      $pdf->Cell(130,6,sprintf('%.2f',$previous['price']),0,0,'R'); /* HTVA */
      if ( $jrn_type !='VEN') {
	$pdf->Cell(15,6,sprintf('%.2f',$previous['priv']),0,0,'R');  /* prive */
      }
      $pdf->Ln(6);


      $offset+=$step;
      $new_page=false;

      //total page
      $tp_htva=0.0;$tp_tvac=0.0;$tp_priv=0;$tp_nd=0;
      for ( $i=0;$i<count($a_jrn);$i++){

	if ( $new_page ) {
	  $new_page=false;
	  /* reset total page */
	  //total page
	  $tp_htva=0.0;$tp_tvac=0.0;$tp_priv=0;$tp_nd=0;
	  $pdf->SetFont('DejaVu','BI',7);
	  $pdf->AddPage();
	  // page Header 
	  $pdf->Cell(15,6,'PJ');
	  $pdf->Cell(15,6,'Date');
	  if ( $jrn_type=='ACH')
	    $pdf->Cell(20,6,'Client');
	  else 
	    $pdf->Cell(20,6,'Fournisseur');
	  $pdf->Cell(65,6,'Description');

	  if ($jrn_type !='VEN') {
	    $pdf->Cell(15,6,'Privé',0,0,'R');
	  }
	  $pdf->Cell(15,6,'TVAC',0,0,'R');
	  $pdf->Ln(5);
	  $pdf->SetFont('DejaVu','',6);
	  if($jrn_type !='VEN') {
	    $pdf->Cell(15,6,sprintf('%.2f',$rap_priv),0,0,'R');  /* prive */
	  }
	  $pdf->Cell(15,6,sprintf('%.2f',$rap_tvac),0,0,'R'); /* Tvac */
	  
	  $pdf->Ln(6);

	}  
	$row=$a_jrn[$i];
	$pdf->Cell(15,6,($row['pj']));
	$pdf->Cell(15,6,shrink_date($row['date_fmt']));
	$pdf->Cell(20,6,$row['internal']);
	$pdf->Cell(65,6,$row['comment']);
	/* get other amount (without vat, total vat included, private, ND */
       	$other=$Jrn->get_other_amount($a_jrn[$i]['jr_grpt_id']);
	$tp_htva+=$other['price'];
	$tp_tvac+=$other['price']+$other['vat'];
	$tp_priv+=$other['priv'];
	$tp_nd+=$other['priv'];
	$rap_htva+=$other['price'];
	$rap_tvac+=$other['price']+$other['vat'];
	$rap_priv+=$other['priv'];
	$rap_nd+=$other['priv'];

	if ( $jrn_type !='VEN') {
	  $pdf->Cell(15,6,sprintf("%.2f",$other['priv']),0,0,'R');
	}

	$pdf->Cell(15,6,sprintf("%.2f",($other['price']+$other['vat'])),0,0,'R');
	$pdf->Ln(5);
	/* footer */
	/* every  lines reset total page and print table footer and set flag new_page to true */
	if ($i>0 && $i % 26 == 0) {
	  $pdf->Cell(115,6,'total page',0,0,'R'); /* HTVA */
	  if ( $jrn_type=='ACH') {
	    $pdf->Cell(15,6,sprintf('%.2f',$tp_priv),0,0,'R');  /* prive */
	  }
	  $pdf->Cell(15,6,sprintf('%.2f',$tp_tvac),0,0,'R'); /* Tvac */
	  $pdf->Ln(2);

	  $pdf->Cell(115,6,'report',0,0,'R'); /* HTVA */
	  if ( $jrn_type=='ACH') {
	    $pdf->Cell(15,6,sprintf('%.2f',$rap_priv),0,0,'R');  /* prive */
	  }
	  $pdf->Cell(15,6,sprintf('%.2f',$rap_tvac),0,0,'R'); /* Tvac */
	  $pdf->Ln(2);
	  $new_page=true;
	}
      }
      /* last footer */
      if ( ! $new_page) {
	  $pdf->Cell(115,6,'Total page ',0,'T','R'); /* HTVA */
	  if ( $jrn_type=='ACH') {
	    $pdf->Cell(15,6,sprintf('%.2f',$tp_priv),0,'T','R');  /* prive */
	  }
	  $pdf->Cell(15,6,sprintf('%.2f',$tp_tvac),0,'T','R'); /* Tvac */
	  $pdf->Ln(2);

	  $pdf->Cell(115,6,'report',0,0,'R'); /* HTVA */
	  if ( $jrn_type=='ACH') {
	    $pdf->Cell(15,6,sprintf('%.2f',$rap_priv),0,0,'R');  /* prive */
	  }
	  $pdf->Cell(15,6,sprintf('%.2f',$rap_tvac),0,0,'R'); /* Tvac */
	  $pdf->Ln(2);
	  $new_page=true;
      }

    } /* else  */
    $fDate=date('dmy-Hi');
    $pdf->Output('journal-'.$fDate.'.pdf','I');

    exit(0);

  }
?>
