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
require_once('class_database.php');
include_once("class.ezpdf.php");
echo_debug('sec_pdf.php',__LINE__,"imp pdf securité");
$cn=new Database($gDossier);
//-----------------------------------------------------
// Security 

// Check User
$rep=new Database();
include_once ("class_user.php");
$User=new User($rep);
$User->Check();
// Check Priv
$User->can_request(PARSEC,1);

//-----------------------------------------------------
// Get User's info
if ( ! isset($_GET['user_id']) ) 
  return;

$SecUser=new User($rep,$_GET['user_id']);


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
$Res=$cn->exec_sql("select jrn_def_id,jrn_def_name  from jrn_def ");
$SecUser->db=$cn;
for ($e=0;$e < Database::num_row($Res);$e++) {
  $row=Database::fetch_array($Res,$e);
  $a_jrn[$e]['jrn_name']=utf8_decode($row['jrn_def_name']);
  $priv=$SecUser->check_jrn($row['jrn_def_id']);
  switch($priv) {
  case 'X':
    $a_jrn[$e]['priv']=utf8_decode("pas d'accès");
    break;
  case 'R':
    $a_jrn[$e]['priv']=utf8_decode("lecture");
    break;
  case 'O':
    $a_jrn[$e]['priv']=utf8_decode("Operation prédéfinie uniquement");
    break;
  case 'W':
    $a_jrn[$e]['priv']="Ecriture";
    break;
  }

 }
$pdf->ezTable($a_jrn,
		array ('jrn_name'=>' Journal',
		       'priv'=>utf8_decode('Privilège'))," ",
		array('shaded'=>0,'showHeadings'=>1,'width'=>500));

//-----------------------------------------------------
// Action
$Res=$cn->exec_sql(
	     "select ac_id, ac_description from action   order by ac_description ");

$Max=Database::num_row($Res);

for ( $i =0 ; $i < $Max; $i++ ) {
   $l_line=Database::fetch_array($Res,$i);
   $action['lib']=utf8_decode($l_line['ac_description']);
   $right=$SecUser->check_action($l_line['ac_id']);
   switch ($right) {
   case 0:
     $action['priv']=utf8_decode("Pas d'accès");
     break;
   case 1:
   case 2:
     $action['priv']=utf8_decode("Accès");
     break;
   }
   $a_action[$i]=$action;
 }
$pdf->ezTable($a_action ,
		array ('lib'=>'Description',
		       'priv'=>utf8_decode('Privilège'))," ",
		array('shaded'=>0,'showHeadings'=>1,'width'=>500));


$pdf->ezStream();

?>
