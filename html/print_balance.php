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
/* received parameters
 e_date element 01.01.2003
 e_client element 3
 nb_item element 2
 e_march0 element 11
 e_quant0 element 1
 e_march1 element 6
 e_quant1 element 2
 e_comment  invoice number
*/
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
// $Revision$
 
include_once("ac_common.php");
include_once("postgres.php");
include_once("class.ezpdf.php");
include_once("poste.php");
echo_debug("imp pdf journaux");
$l_Db=sprintf("dossier%d",$g_dossier);
$cn=DbConnect($l_Db);
foreach ($HTTP_POST_VARS as $key=>$element) {
  ${"$key"}=$element;
  echo_debug("key => $key element $element");
}
$per=join(',',$periode);
// if centralized
$cent="";
$t_cent="";
if ( isset($central) ) { $cent="j_centralized = true and "; $t_cent=" centralisé";}

// build query
$sql="select j_poste,sum(deb) as sum_deb, sum(cred) as sum_cred from 
          ( select j_poste,
             case when j_debit='t' then j_montant else 0 end as deb,
             case when j_debit='f' then j_montant else 0 end as cred
          from jrnx join tmp_pcmn on j_poste=pcm_val
              where 
             $cent
            j_tech_per in ( $per)) as m group by j_poste order by j_poste";

$Res=ExecSql($cn,$sql);
if ( ( $M=pg_NumRows($Res)) == 0 ) {
 $pdf=& new Cezpdf('a4');
 $pdf->selectFont('./addon/fonts/Helvetica.afm');
 $pdf->ezSetCmMargins(2,2,2,2);
 $pdf->ezText("Balance compte -- vide");
 $pdf->ezStream();
 exit();

}
// Load the array
for ($i=0; $i <$M;$i++) {
  $r=pg_fetch_array($Res,$i);
  $a['poste']=$r['j_poste'];
  $a['label']=GetPosteLibelle($cn,$r['j_poste'],1);
  $a['sum_deb']=$r['sum_deb'];
  $a['sum_cred']=$r['sum_cred'];
  $a['solde_deb']=( $a['sum_deb']  >=  $a['sum_cred'] )? $a['sum_deb']- $a['sum_cred']:0;
  $a['solde_cred']=( $a['sum_deb'] <=  $a['sum_cred'] )?$a['sum_cred']-$a['sum_deb']:0;
  $array[$i]=$a;
  

}//for i
$pdf=& new Cezpdf('a4');
$pdf->selectFont('./addon/fonts/Helvetica.afm');
$pdf->ezSetCmMargins(2,2,2,2);
$pdf->ezTable($array,array('poste'=>'Poste','label'=>'Libellé','sum_deb'=>'Total Débit',
			   'sum_cred'=>'Total crédit','solde_deb'=>'Solde débiteur',
			   'solde_cred'=>'Solde créditeur'),'Balance des comptes '.$t_cent);
$pdf->ezStream();


?>
