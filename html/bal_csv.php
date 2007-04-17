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
 * \brief Return the balance in CSV format
 */
header('Content-type: application/csv');
header('Content-Disposition: attachment;filename="balance.csv"',FALSE);
include_once ("ac_common.php");
include_once("class_balance.php");
include_once ("postgres.php");

include("class_jrn.php");
$cn=DbConnect($_SESSION['g_dossier']);


include ('class_user.php');
$User=new cl_user(DbConnect());
$User->Check();
if ( $User->CheckAction($cn,BALANCE) == 0)
  {
    NoAccess();
    exit;
  }
$bal=new Balance($cn);
  
$t_cent="";
  //$per=join(',',$periode);
if ( isset($_POST['central']) ) {
    $bal->central='Y';
    $t_cent="centralisée";
  }
  else
  $bal->central='N';

  $row=$bal->GetRow($_POST['from_periode'],
		  $_POST['to_periode']);
   foreach ($row as $r) {
 
    echo $r['poste'].';'.
      $r['label'].';'.
      $r['sum_deb'].';'.
      $r['sum_cred'].';'.
      $r['solde_deb'].';'.
      $r['solde_cred'];
    printf("\n");
  }
 

?>
