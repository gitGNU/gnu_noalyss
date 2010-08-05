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
 * \brief Send a CSV file with card
 */

header('Pragma: public');
header('Content-type: application/csv');
header('Content-Disposition: attachment;filename="fiche.csv"',FALSE);
include_once ("ac_common.php");
include_once('class_fiche.php');
require_once('class_database.php');
require_once('class_dossier.php');
$gDossier=dossier::id();

$cn=new Database($gDossier);

require_once ('class_user.php');
$User=new User($cn);
$User->Check();
$User->check_dossier($gDossier);
$User->can_request(IMPFIC,0);


if  ( isset ($_GET['fd_id'])) {
  $fiche_def=new Fiche_Def($cn,$_GET ['fd_id']);
  $fiche=new Fiche($cn);
  $e=$fiche_def->GetByType();
  $o=0;
  //  Heading
  $fiche_def->GetAttribut();
  foreach ($fiche_def->attribut as $attribut) {
	  if ( $o == 0 ) {
    		printf("%s",$attribut->ad_text);
		$o=1;
	  }else {
	    printf(";%s",$attribut->ad_text);
	  }
    }
  printf("\n");
  $o=0;
  // Details
  usort($e,'fiche::cmp_name');
  foreach ($e as $detail) {
    foreach ( $detail->attribut as $dattribut ) {
	  if ( $o == 0 ) {
    		printf("%s",$dattribut->av_text);
		$o=1;
	  } else {
	    printf (";%s",$dattribut->av_text);

	  }
      }
    printf("\n");
    $o=0;
    }


 }
  exit;
?>
