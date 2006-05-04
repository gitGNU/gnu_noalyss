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
/* $Revision$ */
include_once ("ac_common.php");
html_page_start($_SESSION['g_theme']);
include_once ("postgres.php");
require_once("class_widget.php");

if ( isset ($_REQUEST['dos'] ) ) {
  $_SESSION['g_dossier']=$_REQUEST['dos'];
  $g_name=GetDossierName($_SESSION['g_dossier']);
  $_SESSION["g_name"]=$g_name;

}

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

include("preference.php");
include_once("user_menu.php");

echo "<H2 class=\"info\"> Param&egrave;tre ".$_SESSION['g_name']." </H2>";

include_once ("check_priv.php");

$cn=DbConnect($_SESSION['g_dossier']);

if ( $User->CheckAction($cn,PARM) == 0 ) {
    /* Cannot Access */
    NoAccess();
  exit -1;			

}

// First action
$p_action="";
if ( isset($_REQUEST["p_action"]) ) {
  $p_action=$_REQUEST["p_action"];
}
echo ShowMenuParam("parametre.php?p_action=".$p_action);


// sub action
$action="";
if ( isset ($_POST['action']) ) {
  $action=$_POST['action'];
}
echo_debug("parametre",__LINE__,$_POST);
////////////////////////////////////////////////////////////////////////////////
// TVA RATE
////////////////////////////////////////////////////////////////////////////////
if ( $p_action == "tva" ) 
{
  require_once("tva.inc.php");
  // 
}
////////////////////////////////////////////////////////////////////////////////
// Account 
////////////////////////////////////////////////////////////////////////////////
if ( $p_action == "poste" ) 
{
  require_once('poste.inc.php');

}
////////////////////////////////////////////////////////////////////////////////
// fiche 
////////////////////////////////////////////////////////////////////////////////
if ( $p_action == "fiche" ) 
{
  require_once('fiche_def.inc.php');
  return;
}
echo '<DIV CLASS="ccontent">';
////////////////////////////////////////////////////////////////////////////////
// Currency
////////////////////////////////////////////////////////////////////////////////
if ( $p_action == "change" ) {
  $p_mid=$_GET['p_mid'];
  $p_rate=$_GET['p_rate'];
  $p_code=$_GET['p_code'];

  echo '<TR> <FORM ACTION="parametre.php" METHOD="POST">';
  echo '<INPUT TYPE="HIDDEN" VALUE="'.$p_mid.'" NAME="p_id">';
  echo '<TD> <INPUT TYPE="text" NAME="p_devise" VALUE="'.$p_code.'"></TD>';
  echo '<TD> <INPUT TYPE="text" NAME="p_rate" VALUE="'.$p_rate.'"></TD>';
  echo '<TD> <INPUT TYPE="SUBMIT" NAME="action" Value="Change"</TD>';
  echo '</FORM></TR>';
}
if ( $action == "Change") {
  $p_devise=$_POST['p_devise'];
  $p_id=$_POST['p_id'];
  $p_rate=$_POST['p_rate'];
  $Res=ExecSql($cn,"update parm_money set pm_code='$p_devise',pm_rate=$p_rate where pm_id=$p_id");
  ShowDevise($cn);

}
if ( $action == "Ajout") {
  $p_devise=$_POST['p_devise'];
  $p_rate=$_POST['p_rate'];
  $Res=ExecSql($cn,"insert into parm_money ( pm_code,pm_rate) values ('$p_devise',$p_rate) ");
  ShowDevise($cn);

}

if ( $p_action == "delete") {
  $p_id=$_GET['p_mid'];
  $Res=ExecSql($cn,"delete from parm_money  where pm_id=$p_id");
  ShowDevise($cn);
}


if ( $p_action=="devise") {
  ShowDevise($cn);
}

////////////////////////////////////////////////////////////////////////////////
// Coord societe
////////////////////////////////////////////////////////////////////////////////
if ( $p_action=='company') { 
  require_once("class_own.php");
  require_once("class_widget.php");
  if ( isset ($_POST['record_company'] )) {
    $m=new Own($cn);
    extract($_POST);
    $m->MY_NAME=$p_name;
    $m->MY_TVA=$p_tva;
    $m->MY_STREET=$p_street;
    $m->MY_NUMBER=$p_no;
    $m->MY_CP=$p_cp;
    $m->MY_COMMUNE=$p_Commune;
    $m->Save();
  }

  $my=new Own($cn);
  $all=new widget("text");
  $all->table=1;
  echo '<form method="post" action="?p_action=company">';
  echo "<table class=\"result\">";
  echo "<tr>".$all->IOValue("p_name",$my->MY_NAME,"Nom soci�t�")."</tr>";

  echo "<tr>".$all->IOValue("p_tva",$my->MY_TVA,"Num�ro de Tva")."</tr>";
  echo "<tr>".$all->IOValue("p_street",$my->MY_STREET,"Rue ")."</tr>";
  echo "<tr>".$all->IOValue("p_no",$my->MY_NUMBER,"Num�ro")."</tr>";
  echo "<tr>".$all->IOValue("p_cp",$my->MY_CP,"Code Postal")."</tr>";
  echo "<tr>".$all->IOValue("p_Commune",$my->MY_COMMUNE,"Commune")."</tr>";
  echo "</table>";
  $submit=new widget("submit");
  echo $submit->Submit("record_company","Enregistre");
  echo "</form>";
 }
///////////////////////////////////////////////////////////////////
// Document 
//////////////////////////////////////////////////////////////////

if ( $p_action == 'document' ) {
  	require_once("class_invoice.php");
	echo ShowMenuInvoice();
	$inv=new Invoice($cn);
	$inv->myList();
	$inv->FormAdd();
	
} 

///////////////////////////////////////////////////////////////////
// Invoice 	add a template
//////////////////////////////////////////////////////////////////
if ( $p_action=='add_invoice') {
	require_once("class_invoice.php");
	$inv=new Invoice($cn);
	//$inv->List();
}
///////////////////////////////////////////////////////////////////
// Invoice remove a template
//////////////////////////////////////////////////////////////////
if ( $p_action=='rm_template') {
require_once("class_invoice.php");
	$inv=new Invoice($cn);
	$inv->Delete();
}
  
  

echo "</DIV>";
html_page_stop();
?>
