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
header('Content-type: application/csv');
header('Content-Disposition: attachment;filename="fiche.csv"',FALSE);
include_once ("ac_common.php");
include_once('class_fiche.php');
include_once ("postgres.php");


$cn=DbConnect($g_dossier);


include ('class_user.php');
$User=new cl_user($cn);
$User->Check();
if ( $g_UserProperty['use_admin'] == 0 ) {
  if (CheckAction($g_dossier,$g_user,FICHE_READ) == 0 )
        {
	  /* Cannot Access */
	  NoAccess();
	}
}
if  ( isset ($_POST['fd_id'])) {
  $fiche_def=new fiche_def($cn,$_POST['fd_id']);
  $fiche=new fiche($cn);
  $e=$fiche->GetByType($fiche_def->id);
  //  Heading
  $fiche_def->GetAttribut();
  foreach ($fiche_def->attribut as $attribut) 
    printf("%s\t",$attribut->ad_text);
  printf("\n");
  
  foreach ($e as $detail) {
    foreach ( $detail->attribut as $dattribut ) 
      printf ("%s\t",$dattribut->av_text);
    printf("\n");
    }


 }
  exit;
?>
