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
/*! \file
 * \brief Send a ledger in CSV format
 */

header('Content-type: application/csv');
header('Content-Disposition: attachment;filename="jrn.csv"',FALSE);
include_once ("ac_common.php");
require_once('class_own.php');

require_once('class_dossier.php');
$gDossier=dossier::id();

include_once ("postgres.php");
require_once("class_acc_ledger.php");
$cn=DbConnect($gDossier);


require_once ('class_user.php');
$User=new User($cn);
$User->Check();
$User->can_request(IMPJRN,0);
$User->check_dossier($gDossier);

if ($_GET['jrn_id']!=0 &&  $User->check_jrn($_GET['jrn_id']) =='X') {
  NoAccess(); exit();}

$p_cent=( isset ( $_GET['central']) )?$_GET['central']:'off';

$Jrn=new Acc_Ledger($cn,$_GET['jrn_id']);

$Jrn->get_name();
$jrn_type=$Jrn->get_type();

// Detailled printing
//---
if  ( $_GET['p_simple'] == 0 ) 
  {
    $Jrn->get_row( $_GET['from_periode'],
		  $_GET['to_periode'],
		  $p_cent);

    if ( count($Jrn->row) == 0) 
      exit;
    foreach ( $Jrn->row as $op ) { 
      // should clean description : remove <b><i> tag and '; char
      $desc=$op['description'];
      $desc=str_replace("<b>","",$desc);
      $desc=str_replace("</b>","",$desc);
      $desc=str_replace("<i>","",$desc);
      $desc=str_replace("</i>","",$desc);
      $desc=str_replace('"',"'",$desc);
      $desc=str_replace(";",",",$desc);

      printf("\"%s\";\"%s\";\"%s\";\"%s\";\"%s\";%8.4f;%8.4f\n",
	     $op['j_id'],
	     $op['internal'],
	     $op['j_date'],
	     $op['poste'],
	     $desc,
	     $op['deb_montant'],
	     $op['cred_montant']
	     );
	
    }
    exit;
  }
 else 
   {
     $Row=$Jrn->get_rowSimple($_GET['from_periode'],
			     $_GET['to_periode'],
			     $p_cent,
			     0);
//-----------------------------------------------------
     if ( $jrn_type == 'ODS' || $jrn_type == 'FIN' || $jrn_type=='GL')
       {
	 printf ('" operation";'.
		 '"Date";'.
		 '"commentaire";'.
		 '"internal";'.
		 '"montant";'.
		 "\r\n");
	  // set a filter for the FIN
	  $a_parm_code=get_array($cn,"select p_value from parm_code where p_code in ('BANQUE','COMPTE_COURANT','CAISSE')");
	  $sql_fin="(";
	  $or="";
	  foreach ($a_parm_code as $code) {
	    $sql_fin.="$or j_poste::text like '".$code['p_value']."%'";
	    $or=" or ";
	  }
	  $sql_fin.=")";

	 foreach ($Row as $line)
	   {
	     
	     echo $line['num'].";";
	     echo $line['date'].";";
	     echo $line['comment'].";";
	     echo $line['jr_internal'].";";
	     //	  echo "<TD>".$line['pj'].";";
	     // If the ledger is financial :
	     // the credit must be negative and written in red
	     // Get the jrn type
	     if ( $line['jrn_def_type'] == 'FIN' ) {
	       $positive = CountSql($cn,"select * from jrn inner join jrnx on jr_grpt_id=j_grpt ".
				    " where jr_id=".$line['jr_id']." and $sql_fin ".
			       " and j_debit='f'");
	       

	       echo ( $positive != 0 )?sprintf("-%8.2f",$line['montant']):sprintf("%8.2f",$line['montant']);
	       echo ";";
	     }
	     else 
	       {
		 printf("% 8.2f",$line['montant']).";";
	       }
	     
	     printf("\r\n");
	   }
       }  
//-----------------------------------------------------
     if ( $jrn_type=='ACH' || $jrn_type=='VEN')
       {
	 $own=new Own($cn);
	 $col_tva="";

	 if ( $own->MY_TVA_USE=='Y') {
	   $a_Tva=get_array($cn,"select tva_id,tva_label from tva_rate where tva_rate != 0.0000 order by tva_rate");
	   foreach($a_Tva as $line_tva)
	     {
	       $col_tva.='"Tva '.$line_tva['tva_label'].'";';
	     } 
	 }
	 echo '"Date";"operation";"Client/Fourn.";"Commentaire";"inter.";"HTVA";'.$col_tva.'"TVAC"'."\n\r";
	 foreach ($Row as $line)
	   {
	     printf('"%s";"%s";"%s";"%s";"%s";% 10.2f;',
		    $line['date'],
		    $line['num'],
		    $line['client'],
		    $line['comment'],
		    $line['jr_internal'],
		    $line['HTVA']);
	     $a_tva_amount=array();
	     foreach ($line['TVA'] as $lineTVA)
	       {
		 foreach ($a_Tva as $idx=>$line_tva)
		   {
		     
		     if ($line_tva['tva_id'] == $lineTVA[1][0])
		       {
			 $a=$line_tva['tva_id'];
			 $a_tva_amount[$a]=$lineTVA[1][2];
		       }
		   } 
	       }
	     if ($own->MY_TVA_USE == 'Y' ) {  
	       foreach ($a_Tva as $line_tva)
		 {
		   $a=$line_tva['tva_id'];
		   if ( isset($a_tva_amount[$a]))
		     printf("% 8.2f;",$a_tva_amount[$a]);
		   else
		     printf("0;");
		 }
	     }
	     printf("% 9.2f\r\n",$line['TVAC']);
	   }
       }
   }
?>
