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
/* $Revision$ */
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
include_once("ac_common.php");
include_once ("postgres.php");
include ('class_user.php');
include("class_rapport.php");
include("impress_inc.php");
header('Content-type: application/csv');
header('Content-Disposition: attachment;filename="rapport.csv"',FALSE);

/* Admin. Dossier */
$cn=DbConnect($_SESSION['g_dossier']);


$User=new cl_user($cn);
$User->Check();


$Form=new rapport($cn,$_POST['form_id']);
$Form->GetName();
// Step ?
//--
if ( $_POST['p_step'] == 0 )
  {
    $array=$Form->GetRow( $_POST['from_periode'],
			  $_POST['to_periode']
			  );
    if ( count($Form->row ) == 0 ) 
      exit;
    
    echo       "\"Description\"\t,".
      "\"Montant\"\t";



    foreach ( $Form->row as $op ) { 
      echo '"'.$op['desc'].'"'."\t,".
	sprintf("%8.2f",$op['montant'])."\t".
	"\n";
      
    }
  } 
 else 
   {
     // Gather all the data
     //---
     for ($e=$_POST['from_periode'];$e<=$_POST['to_periode'];$e+=$_POST['p_step'])
       {
	 $array[]=$Form->GetRow($e,$e);
	 $periode_name[]=getPeriodeName($cn,$e);
       }
     // Display column heading
     //--
     $x="";
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
