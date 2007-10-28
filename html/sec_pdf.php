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
// Copyright Stanislas Pinte stanpinte@sauvages.be

// $Revision$
/*! \file
 * \brief Print the user security in pdf
 */

require_once('class_dossier.php');
$gDossier=dossier::id();
include_once("ac_common.php");
include_once("postgres.php");
include_once("class.ezpdf.php");
require_once("check_priv.php");
echo_debug('sec_pdf.php',__LINE__,"imp pdf securité");
$cn=DbConnect($gDossier);
//-----------------------------------------------------
// Security 

// Check User
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();
// Check Priv
$User->AccessRequest($cn,SECU);

//-----------------------------------------------------
// Get User's info
if ( ! isset($_GET['user_id']) ) 
  return;

$SecUser=new cl_user($rep,$_GET['user_id']);


//-----------------------------------------------------
// Print result

$pdf=new Cezpdf("A4");
$pdf->selectFont('./addon/fonts/Helvetica.afm');
$str_user=sprintf("( %d ) %s %s [ %s ]",
		  $SecUser->id,
		  $SecUser->first_name,
		  $SecUser->name,
		  $SecUser->login);

$pdf->ezText($str_user,14,array('justification'=>'center'));

if ( $SecUser->active==0)
  $pdf->ezText('Bloqué',12,array('justification'=>'center'));

if ( $SecUser->admin==1)
  $pdf->ezText('Administrateur',12,array('justification'=>'center'));
//-----------------------------------------------------
// Journal
$Res=ExecSql($cn,"select jrn_def_id,jrn_def_name  from jrn_def ");
for ($e=0;$e < pg_NumRows($Res);$e++) {
  $row=pg_fetch_array($Res,$e);
  $a_jrn[$e]['jrn_name']=$row['jrn_def_name'];
  $priv=CheckJrn($gDossier,$SecUser->login,$row['jrn_def_id']);
  switch($priv) {
  case 0:
    $a_jrn[$e]['priv']="pas d'accès";
    break;
  case 1:
    $a_jrn[$e]['priv']="lecture";
    break;
  case 2:
  case 3:
    $a_jrn[$e]['priv']="Ecriture";
    break;
  }

 }
$pdf->ezTable($a_jrn,
		array ('jrn_name'=>' Journal',
		       'priv'=>'Privilège')," ",
		array('shaded'=>0,'showHeadings'=>1,'width'=>500));

//-----------------------------------------------------
// Action
$Res=ExecSql($cn,
	     "select ac_id, ac_description from action   order by ac_description ");

$Max=pg_NumRows($Res);

for ( $i =0 ; $i < $Max; $i++ ) {
   $l_line=pg_fetch_array($Res,$i);
   $action['lib']=$l_line['ac_description'];
   $right=CheckAction($gDossier,$SecUser->login,$l_line['ac_id']);
   switch ($right) {
   case 0:
     $action['priv']="Pas d'accès";
     break;
   case 1:
   case 2:
     $action['priv']="Accès";
     break;
   }
   $a_action[$i]=$action;
 }
$pdf->ezTable($a_action ,
		array ('lib'=>'Description',
		       'priv'=>'Privilège')," ",
		array('shaded'=>0,'showHeadings'=>1,'width'=>500));


$pdf->ezStream();

?>
