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
 * \brief Send a report in CSV format
 */
include_once("ac_common.php");
require_once('class_database.php');
include ('class_user.php');
require_once("class_acc_report.php");
require_once("impress_inc.php");
header('Content-type: application/csv');
header('Content-Disposition: attachment;filename="rapport.csv"',FALSE);
require_once('class_dossier.php');
$gDossier=dossier::id();

/* Admin. Dossier */
$cn=new Database($gDossier);


$User=new User($cn);
$User->Check();


$Form=new Acc_Report($cn,$_GET['form_id']);
$Form->get_name();
// Step ?
//--
if ( $_GET['p_step'] == 0 )
  {
	if ( $_GET ['type_periode'] == 0 )
	  $array=$Form->get_row( $_GET['from_periode'],$_GET['to_periode'], $_GET['type_periode']);
	else 
	  $array=$Form->get_row( $_GET['from_date'],$_GET['to_date'], $_GET['type_periode']);


    if ( count($Form->row ) == 0 ) 
      exit;
    
    echo       "\"Description\";".
      "\"Montant\"\n";



    foreach ( $Form->row as $op ) { 
      echo '"'.$op['desc'].'"'.";".
	sprintf("%8.2f",$op['montant']).
	"\n";
      
    }
  } 
 else 
   {
     // Gather all the data
     //---
     for ($e=$_GET['from_periode'];$e<=$_GET['to_periode'];$e+=$_GET['p_step'])
       {
		 $periode=getPeriodeName($cn,$e);
		 if ( $periode == null ) continue;
		 $array[]=$Form->get_row($e,$e);
		 $periode_name[]=$periode;
       }
     // Display column heading
     //--
     $x="";
     printf ("Mois;");
     foreach ($array[0] as $e) 
       {
	 printf("%s%s",$x,$e['desc']);
	 $x=";";

       }
     printf("\n");
     // Display value for each line 
     //--
     $a=0;
     foreach ($array as $e ) 
       {
	 print $periode_name[$a];
	 $a++;
	 foreach ($e as $elt) 
	   {
	     printf(";%s",$elt['montant']);
	   }
	 printf("\n");
       }
   }
  exit;
?>
