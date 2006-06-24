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
/*!\file
 * \brief for reporting the saldo of account to the next year.
 */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
include_once ("ac_common.php");
require_once('class_widget.php');
require_once('class_poste.php');
html_page_start($_SESSION['g_theme']);
if ( ! isset ( $_SESSION['g_dossier'] ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}
include_once ("postgres.php");
/* Admin. Dossier */
include_once ("class_user.php");
$cn=DbConnect($_SESSION['g_dossier']);
$User=new cl_user($cn);
$User->Check();

include_once ("check_priv.php");

include_once ("user_menu.php");
echo '<div class="u_tmenu">';
echo ShowMenuCompta($_SESSION['g_dossier'],"user_advanced.php");
echo '</div>';
// \todo add a check for permission
if ( $User->CheckAction($cn,EXP_IMP_ECR) == 0 ) {
  /* Cannot Access */
  NoAccess();
  exit -1;			
 }

echo ShowMenuAdvanced("ecrit_ouv.php");
echo '<div class="lmenu">';

echo ShowItem ( array (
			array ("ecrit_ouv.php?export","Export"),
			array ("ecrit_ouv.php?import","Import")
			),'V');
echo '</div>';
echo '<div class="redcontent">';
/////////////////////////// EXPORT ////////////////////////////////////////////
if ( isset ($_GET['export'])) {
  // if the year is not set, ask it
  // ask the exercice and do the export
  $periode=make_array($cn,"select distinct p_exercice,p_exercice from parm_periode order by p_exercice");
  echo '<form method="GET" ACTION="export_ouv.php">';
  $w=new widget('select');
  $w->table=0;
  $w->label='Periode';
  $w->readonly=false;
  $w->value=$periode;
  $w->name="p_periode";
  echo 'P&eacute;riode : '.$w->IOValue();
  echo $w->Submit('export','Export');
  echo "</form>";
  exit(0);
}
/////////////////////////// IMPORT ////////////////////////////////////////////
if ( isset ($_GET['import'])) {
	// show a form to upload the file
	// that form will parse the file, create an ods operation
	// and ask you to validate it
  // if no file is given
  if ( ! isset ($_REQUEST['p_submit']) ) {
?>
<FORM NAME="form_detail" enctype="multipart/form-data" ACTION="ecrit_ouv.php?import" METHOD="POST">
<?
  // TODO propose  ODS ledger 
  $ods=make_array($cn,"select jrn_def_id,jrn_def_name from jrn_def where jrn_def_type = 'OD'");
  $x=new widget("select");
  $x->name='p_jrn';
  $x->value=$ods;
  echo "Choississez votre journal ".$x->IOValue();

  	$w=new widget("file");
  $w->name='import_file';
  $w->label='p_file';
  echo $w->IOValue();
  echo $w->Submit('p_submit','Charger le fichier');
?>
</FORM>
<?
	exit(0);
  } else { 
    require_once("user_form_ods.php");
    require_once("jrn.php");
    // a file is given, so we upload it
    $new_name=tempnam('/tmp','import');
    if ( strlen ( $_FILES['import_file']['tmp_name']) != 0 ) {
      if ( move_uploaded_file($_FILES['import_file']['tmp_name'],$new_name) ) {
	// upload succeed
	$h_file=fopen($new_name,'r') ;
	// test if the file is opened
	if ( $h_file == false) { echo 'Je ne peux ouvrir pas ce fichier';exit(-1);}
	// Analyze the file and store result into array
	$valid=false;
	$idx=0;
	while ( !feof($h_file) ) {

	  $line=fgets($h_file);
	  // check if the first line contains the signature
	  if ( $valid  ) {
	    // skip blank line
	    if (strlen (trim($line)) == 0 ) continue;
	    // put the line into several array with the same index
	    list($sign,$poste,$label,$amount)=explode(";",$line);
	    $asign[$idx]=$sign; $aposte[$idx]=$poste;$aamount[$idx]=$amount;
	    $alabel[$idx]=$label;
	    $idx++;
	    }
	  $valid=(($line=="OUVERTURE\n" && $valid==false) || $valid)?true:false;
	  
	} // read the file
	// if valid is still false then there is nothing to do
	if ( ! $valid) { echo 'Aucun enregistrement valide'; return ;}
	// compose the array for the function FormODS
	$array_ods['e_comm']='Ecriture d\'ouverture';
	for ($i=0;$i<$idx;$i++) {
		$n="e_account$i";
		$array_ods[$n]=$aposte[$i];
		$n="e_account".$i."_type";
		$array_ods[$n]=$asign[$i];
		$n="e_account".$i."_amount";
		$array_ods[$n]=$aamount[$i];
	}
	// Check if all the poste exist
	// otherwise create it
	for ($i=0;$i<$idx;$i++)
	  {
	    
	    $p=new Poste($cn,$aposte[$i]);

	    // if the poste exists then check the next one
	    if ( $p->get() == true ) continue;
	    echo 'Attention creation de '.$p->id.' '.$alabel[$i].'<br>';
	    $sql=sprintf("select account_add(%d,'%s')",
			$p->id,$alabel[$i]);

	    ExecSql($cn,$sql);
	  }

	// submit button in the form	
	$submit='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout Poste">
                    <INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Sauver">';
	 $r=FormODS($cn,$_POST['p_jrn'],$User->GetPeriode(),$submit,
			$array_ods,false,$idx,$p_saved=false);
	
	  echo $r;
	  echo "<div><h4>On-line calculator</h4>".JS_CALC_LINE."<div>";
	
      } 
    }
  } // else -> a file is given
 }// if import
// IF import and export are not set then the choice is proposed
echo '</div>';
html_page_stop(); 
?>
