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
include_once("class_acc_balance.php");
include_once ("postgres.php");
require_once('class_dossier.php');
$gDossier=dossier::id();

require_once("class_acc_ledger.php");
$cn=DbConnect($gDossier);


require_once ('class_user.php');
$User=new User(DbConnect());
$User->Check();
if ( $User->check_action(IMPBAL) == 0)
  {
    NoAccess();
    exit;
  }
echo 'poste;libelle;deb;cred;solde deb;solde cred';
printf("\n");
$bal=new Acc_Balance($cn);
  
$t_cent="";
  //$per=join(',',$periode);
if ( isset($_POST['central']) ) {
    $bal->central='Y';
    $t_cent="centralisée";
  }
  else
  $bal->central='N';
  $bal->jrn=$_POST['p_jrn'];
  $bal->from_poste=$_POST['from_poste'];
  $bal->to_poste=$_POST['to_poste'];

  $row=$bal->get_row($_POST['from_periode'],
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
