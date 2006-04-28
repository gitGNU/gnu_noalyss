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
/* $Revision$ */
include ("ac_common.php");
include ("check_priv.php");
include_once ("class_user.php");
include_once ("postgres.php");
include ("user_menu.php");
include_once("class_invoice.php");
if ( isset ($_REQUEST['dos'])) 
{
  $_SESSION["g_dossier"]=$_REQUEST['dos'];
}
$g_dossier=(isset ($_SESSION['g_dossier']))?$_SESSION['g_dossier']:-1;

// List of customer select * from fiche_def where frd_id=9;
if (  $g_dossier==-1  ) {
  echo "You must choose a Dossier ";
  exit -2;
}
// Check user
$rep=DbConnect($g_dossier);
$User=new cl_user($rep);
$User->Check();

// Save Folder's name
$g_name=GetDossierName($g_dossier);
$_SESSION["g_name"]=$g_name;


if ( $User->CheckAction($g_dossier,FACT) == 0 ){
    /* Cannot Access */
     NoAccess();
     exit -1;
}
////////////////////////////////////////////////////////////////////////////////
// update preference
////////////////////////////////////////////////////////////////////////////////
if ( isset ( $_POST['style_user']) ) {
  $User->update_global_pref('THEME',$_POST['style_user']);
  $_SESSION['g_theme']=$_POST['style_user'];
  $User->theme=$_POST['style_user'];
}
// Met a jour le pagesize
if ( isset ( $_POST['p_size']) ) {
  $User->update_global_pref('PAGESIZE',$_POST['p_size']);
  $_SESSION['g_pagesize']=$_POST['p_size'];
  
}

///
html_page_start($User->theme);

$cn=DbConnect($g_dossier);

echo "<H2 class=\"info\"> Facturation ".$_SESSION['g_name']." </H2>";

// Show Menu
echo ShowItem(array(			  
	       array('?p_action=create_invoice&dos='.$g_dossier,'Nouvelle'),
	       array('?p_action=view_invoice&dos='.$g_dossier,'Voir'),
	       array('?p_action=pref','Préférence'),
	       array('parametre.php?p_action=invoice&dos='.$g_dossier,'Paramètre'),
	       array('login.php','Accueil',"Accueil"),
	       array('logout.php','logout',"Sortie")
	       ),
	 'H',"mtitle","mtitle");
////////////////////////////////////////////////////////////////////////////////
// Action requested
//
////////////////////////////////////////////////////////////////////////////////
if ( isset ($_REQUEST["p_action"] ))  {
  $action=$_REQUEST["p_action"];
  ////////////////////////////////////////////////////////////////////////////////
  // p_action == pref
  ////////////////////////////////////////////////////////////////////////////////
  if ( $action == "pref" ) 
    {
      require_once("pref.inc.php");
    }

  ////////////////////////////////////////////////////
  // See All invoices
  if ($action == "create_invoice" ) {
  }
  //////////////////////////////////////////////////
  // See only unpaid invoices
  if ($action=="view_invoice") {
  }
  //////////////////////////////////////////////////
  // Create an invoice

}//get action

html_page_stop();

?>
