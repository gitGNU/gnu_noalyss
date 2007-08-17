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
 * \brief Send the final balance in CSV this balance is used to open an exercice
 */

include_once ("ac_common.php");
require_once("class_poste.php");
header('Content-type: application/csv');
header('Content-Disposition: attachment;filename="export_ouv.csv"',FALSE);
require_once('class_dossier.php');
$gDossier=dossier::id();

include_once ("postgres.php");
/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();

include_once ("check_priv.php");

include_once ("user_menu.php");

$cn=DbConnect($gDossier);

$User->AccessRequest($cn,EXP_IMP_ECR);

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
// check if the account are balanced 
$sum=0;
foreach ($ret as $poste_id) {

	$Poste=new poste($cn,$poste_id['j_poste']);
	// fill the object
	$Poste->get();
	// build sql stmt
	$sql="j_tech_per >=". $sql_from[0]['min']." and j_tech_per <=".$sql_to[0]['max'];
	$result=$Poste->GetSoldeDetail($sql );
	$Poste->label=str_replace(';','',$Poste->label);
	
	if ( $result['solde'] == 0 ) continue;
	if ( $result['debit'] > $result ['credit'] ) {
		printf ("d;%d;%s;%12.4f\n",$Poste->id,$Poste->label,$result['solde']);
		$sum+=$result['solde'];
	} else {
		printf ("c;%d;%s;%12.4f\n",$Poste->id,$Poste->label,$result['solde']);
		$sum-=$result['solde'];
	}
}
// $sum must be equal to 0 
// $sum > 0 then deb is too big
// $sum < 0 then cred is too big
// rounded problem 
if ( round($sum,4) != 0.0 ) 
{
  printf("ATTENTION : COMPTE NON EQUILIBRE\n ");
  $msg = ($sum > 0)?" Debit plus grand de $sum":"Credit plus grand de $sum";
  printf ("DIFFERENCE = $msg \n");
 
}
?>
