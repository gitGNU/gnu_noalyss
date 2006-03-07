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
include_once ("ac_common.php");
require_once("class_poste.php");
header('Content-type: application/csv');
header('Content-Disposition: attachment;filename="export_ouv.csv"',FALSE);
if ( ! isset ( $_SESSION['g_dossier'] ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}
include_once ("postgres.php");
/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();

include_once ("check_priv.php");

include_once ("user_menu.php");

$cn=DbConnect($_SESSION['g_dossier']);
// TODO : add a check for permission
if ( $User->CheckAction($cn,GJRN) == 0 ) {
  /* Cannot Access */
  NoAccess();
  exit -1;			
 }
if ( !isset ($_GET['p_periode'])) {
	echo 'Erreur : aucune periode demandée';
	exit(0);
}
$sql_from=GetArray($cn,"select min(p_id) from parm_periode where p_exercice=".$_GET['p_periode']);

$sql_to=GetArray($cn,"select max(p_id) from parm_periode where p_exercice=".$_GET['p_periode']);

$ret=GetArray($cn,"select distinct j_poste::text 
		from jrnx inner join tmp_pcmn on (pcm_val=j_poste)
		where 
		j_tech_per >= ".$sql_from[0]['min']." and 
	 	j_tech_per <= ".$sql_to[0]['max']." and j_poste not like '7%'
		and j_poste not like '6%'
                 order by j_poste::text");
if ( $ret == null ) {echo 'Rien à exporter'; exit();}
printf ("OUVERTURE\n");
foreach ($ret as $poste_id) {
//var_dump($poste_id); echo "<br>";
	$Poste=new poste($cn,$poste_id['j_poste']);
	$sql="j_tech_per >=". $sql_from[0]['min']." and j_tech_per <=".$sql_to[0]['max'];
	$result=$Poste->GetSoldeDetail($sql );
	if ( $result['solde'] == 0 ) continue;
	if ( $result['debit'] > $result ['credit'] ) {
		printf ("debit;%d;%9.4f\n",$Poste->id,$result['solde']);
	} else {
		printf ("credit;%d;%9.4f\n",$Poste->id,$result['solde']);
	}
}

?>