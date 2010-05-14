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
require_once('class_print_ledger_detail.php');
require_once('class_print_ledger_simple.php');
require_once('class_print_ledger_simple_without_vat.php');
require_once('class_print_ledger_fin.php');
require_once('class_print_ledger_misc.php');


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
if ( $_REQUEST['p_simple']== 0 ) 
  {
    $pdf=new Print_Ledger_Detail($cn);
    $pdf->setDossierInfo($Jrn->name);
    $pdf->AliasNbPages();
    $pdf->AddPage();

    $pdf->export($Jrn);

    $fDate=date('dmy-Hi');
    $pdf->Output('journal-'.$fDate.'.pdf','I');
    exit(0);

  } // impression detaillé
//----------------------------------------------------------------------
// Simple Printing Purchase Ledger
//---------------------------------------------------------------------
if   (  $_REQUEST['p_simple']== 1 ) 
  {
    if ( $jrn_type=='ACH' || $jrn_type=='VEN')
      {
	if ( $own->MY_TVA_USE=='Y') 
	  {
	    $pdf= new Print_Ledger_Simple($cn,$Jrn);
	    $pdf->setDossierInfo($Jrn->name);
	    $pdf->AliasNbPages();
	    $pdf->AddPage();
	    $pdf->export();
	    $fDate=date('dmy-Hi');
	    $pdf->Output('journal-'.$fDate.'.pdf','I');
	    exit(0);
	  }
	if ( $own->MY_TVA_USE=='N') 
	  {
	    $pdf= new Print_Ledger_Simple_without_vat($cn,$Jrn);
	    $pdf->setDossierInfo($Jrn->name);
	    $pdf->AliasNbPages();
	    $pdf->AddPage();
	    $pdf->export($Jrn);
	    $fDate=date('dmy-Hi');
	    $pdf->Output('journal-'.$fDate.'.pdf','I');
	    exit(0);
	  }

      }
    
    if ($jrn_type=='FIN') {
      $pdf= new Print_Ledger_Financial($cn,$Jrn);
      $pdf->setDossierInfo($Jrn->name);
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->export();
      $fDate=date('dmy-Hi');
      $pdf->Output('journal-'.$fDate.'.pdf','I');
      exit(0);
    }
    if ( $jrn_type=='ODS' || $Jrn->id==0) {
      $pdf= new Print_Ledger_Misc($cn,$Jrn);
      $pdf->setDossierInfo($Jrn->name);
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->export();
      $fDate=date('dmy-Hi');
      $pdf->Output('journal-'.$fDate.'.pdf','I');
      exit(0);
    }
  }
?>
