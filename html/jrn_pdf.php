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


// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
// $Revision$

if ( ! isset($_SESSION['g_dossier']) ) {
  echo "INVALID G_DOSSIER UNKNOWN !!! ";
  exit();
}
include_once('class_user.php');
include_once("jrn.php");
include_once("ac_common.php");
include_once("postgres.php");
include_once("class.ezpdf.php");
include_once("impress_inc.php");
include_once("preference.php");
include_once("class_jrn.php");
include_once("check_priv.php");

echo_debug(__FILE__,__LINE__,"imp pdf journaux");
$cn=DbConnect($_SESSION['g_dossier']);
$l_type="JRN";
$centr=" Non centralisé";
$l_centr=0;
if ($_POST['central'] == 'on' ) {
  $centr=" centralisé ";
  $l_centr=1;
}
$Jrn=new jrn($cn,$_POST['jrn_id']);

$Jrn->GetName();
$User=new cl_user($cn);
$User->Check();
if ( $User->admin == 0 ) {
  if (CheckAction($_SESSION['g_dossier'],$_SESSION['g_user'],IMP) == 0 ||
        $User->AccessJrn($_POST['jrn_id']) == false){
    /* Cannot Access */
    NoAccess();
  }

}

$ret="";
$pdf=& new Cezpdf("A4");
$pdf->selectFont('./addon/fonts/Helvetica.afm');

// filter : 0 for Grand Livre otherwise 1
$filter=( $Jrn->id == 0)?0:1;

$offset=0;$limit=22;$step=22;
$rap_deb=0;$rap_cred=0;
while (1) {
  $a=0;
  list ($a_jrn,$tot_deb,$tot_cred)=$Jrn->GetRow($_POST['from_periode'],
					  $_POST['to_periode'],
					  $_POST['central'],
					  $limit,$offset);
  echo_debug(__FILE__,__LINE__,"Total debit $tot_deb,credit $tot_cred");

  if ( $a_jrn==null) break;
  $offset+=$step; 
  $first_id=$a_jrn[0]['int_j_id'];
  $Exercice=GetExercice($cn,$a_jrn[0]['periode']);


  list($rap_deb,$rap_cred)=GetRappel($cn,$first_id,$Jrn->id,$Exercice,FIRST,
				     $filter,
				     $l_centr
				     );
  echo_debug(__FILE__,__LINE__,"MONTANT $rap_deb,$rap_cred");
  echo_debug(__FILE__,__LINE__,"  list($rap_deb,$rap_cred)=GetRappel($cn,$first_id,".$Jrn->id.",$Exercice,FIRST)");
  $pdf->ezText($Jrn->name,30);

  if (  $l_centr == 1 ) {
    // si centralisé montre les montants de rappel
  $str_debit=sprintf( "report Débit  % 10.2f",$rap_deb);
  $str_credit=sprintf("report Crédit % 10.2f",$rap_cred);
  $pdf->ezText($str_debit,12,array('justification'=>'right'));
  $pdf->ezText($str_credit,12,array('justification'=>'right'));
  }

  $pdf->ezTable($a_jrn,
		array ('j_id'=>' Numéro',
		       'j_date' => 'Date',
		       'poste'=>'Poste',
		       'description' => 'Description',
		       'deb_montant'=> 'Débit',
		       'cred_montant'=>'Crédit')," ",
		array('shaded'=>0,'showHeadings'=>1,'width'=>500,
		      'cols'=>array('deb_montant'=> array('justification'=>'right'),
		      'cred_montant'=> array('justification'=>'right'))));
  $a=1;
  // Total Page
  $apage=array(array('deb'=>sprintf("%8.2f",$tot_deb),'cred'=>$tot_cred));
  foreach ($apage as $key=>$element) echo_debug(__FILE__,__LINE__,"apage $key => $element");
  $pdf->ezTable($apage,
		array (
		       'deb'=> 'Total Débit',
		       'cred'=>'Total Crédit')," ",
		array('shaded'=>0,'showHeadings'=>1,'width'=>200,
		      'xPos'=>'right','xOrientation'=>'left',
		      'cols'=>array('deb'=> array('justification'=>'right'),
		      'cred'=> array('justification'=>'right'))));

  $count=count($a_jrn)-1;
  $last_id=$a_jrn[$count]['int_j_id'];
  $Exercice=GetExercice($cn,$a_jrn[$count]['periode']);
  if ( $l_centr == 1) {
    // Montant de rappel si centralisé
    list($rap_deb,$rap_cred)=GetRappel($cn,$last_id,$Jrn->id,$Exercice,LAST,$filter,$l_centr);
    $str_debit=sprintf( "à reporter Débit  % 10.2f",$rap_deb);
    $str_credit=sprintf("à reporter Crédit % 10.2f",$rap_cred);
    $pdf->ezText($str_debit,12,array('justification'=>'right'));
    $pdf->ezText($str_credit,12,array('justification'=>'right'));
  }
  //New page
  $pdf->ezNewPage();
}    
if ( $a == 1 )   {
  $apage=array('deb'=>$tot_deb,'cred'=>$tot_cred);
$pdf->ezTable($apage,
		array (
		       'deb'=> 'Total Débit',
		       'cred'=>'Total Crédit')," ",
		array('shaded'=>0,'showHeadings'=>1,'width'=>500,
		      'cols'=>array('deb'=> array('justification'=>'right'),
		      'cred'=> array('justification'=>'right'))));
  $count=count($a_jrn)-1;
  $last_id=$a_jrn[$count]['int_j_id'];
  $Exercice=GetExercice($cn,$a_jrn[$count]['periode']);

  list($rap_deb,$rap_cred)=GetRappel($cn,$last_id,$Jrn->id,$Exercice,LAST,$filter,$l_centr);
  $str_debit=sprintf( "à reporter  Débit % 10.2f",$rap_deb);
  $str_credit=sprintf("à reporter Crédit % 10.2f",$rap_cred);
  $pdf->ezText($str_debit,12,array('justification'=>'right'));
  $pdf->ezText($str_credit,12,array('justification'=>'right'));

}
$pdf->ezStream();

?>
