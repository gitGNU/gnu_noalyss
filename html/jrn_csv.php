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
header('Content-Disposition: attachment;filename="jrn.csv"',FALSE);
include_once ("ac_common.php");

include_once ("postgres.php");

include("class_jrn.php");
$cn=DbConnect($g_dossier);


include ('class_user.php');
$User=new cl_user($cn);
$User->Check();
if ( $g_UserProperty['use_admin'] == 0 ) {
  if (CheckAction($g_dossier,$g_user,IMP) == 0 ||
        $User->AccessJrn($_POST['jrn_id']) == false){
    /* Cannot Access */
    NoAccess();
  }

}


 $p_cent=( isset ( $_POST['central']) )?'on':'off';

$Jrn=new jrn($cn,$_POST['jrn_id']);
// $Jrn->Access();
$Jrn->GetName();
$Jrn->GetRow( $_POST['from_periode'],
	      $_POST['to_periode'],
	      $p_cent);


  foreach ( $Jrn->row as $op ) { 
      // should clean description : remove <b><i> tag and '; char
    $desc=$op['description'];
    $desc=str_replace("<b>","",$desc);
    $desc=str_replace("</b>","",$desc);
    $desc=str_replace("<i>","",$desc);
    $desc=str_replace("</i>","",$desc);
    $desc=str_replace('"',"'",$desc);

      printf("\"%s\"\t\"%s\"\t\"%s\"\t\"%s\"\t\"%s\"\t%8.4f\t%8.4f\n",
	     $op['j_id'],
	     $op['internal'],
	     $op['j_date'],
	     $op['poste'],
	     $desc,
	     $op['cred_montant'],
	     $op['deb_montant']
	     );
	
  }
  exit;
?>
