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

include_once ("ac_common.php");

include_once ("postgres.php");

include("class_jrn.php");
$l_Db=sprintf("dossier%d",$g_dossier);
$cn=DbConnect($l_Db);


include ('class_user.php');
$User=new cl_user($cn);
$User->Check();
if ( $g_UserProperty['use_admin'] == 0 ) {
  if (CheckAction($g_dossier,$g_user,IMP) == 0 ){
    /* Cannot Access */
    NoAccess();
  }
}


 $p_cent=( isset ( $_POST['cent']) )?'on':'off';

$Jrn=new jrn($cn,$_POST['jrn_id']);
$Jrn->GetName();
$Jrn->GetRow( $_POST['from_periode'],
	      $_POST['to_periode'],
	      $p_cent);


  foreach ( $Jrn->row as $op ) { 
  $rep="";

    if ( $op['j_id'] != $rep) {
      $rep= $op['j_id'];
      // should clean description : remove <b><i> tag and '; char
      // should clean poste       : remove <b><i> tag and '; char

      echo 
	"'".$op['internal']."';".
	"'".$op['j_date']."';".
	"'".$op['poste']."';".
	"'".$op['description']."';".
	"'".$op['cred_montant']."';".
	"'".$op['deb_montant']."\n";
	
    } else {
      echo 
	"'".$op['internal']."';".
	"'".$op['j_date']."';".
	"'".$op['poste']."';".
	"'".$op['description']."';".
	"'".$op['cred_montant']."';".
	"'".$op['deb_montant']."\n";

    }
    
  }
  exit;
?>
