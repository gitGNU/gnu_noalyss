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
require_once("constant.php");
include_once ("postgres.php");

if ( isset ($_REQUEST['dos'] ) ) {
  $_SESSION['g_dossier']=$_REQUEST['dos'];
  $g_name=GetDossierName($_SESSION['g_dossier']);
  $_SESSION["g_name"]=$g_name;

}

html_page_start($_SESSION['g_theme']);
if ( ! isset ( $_SESSION['g_dossier'] ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}
include_once ("postgres.php");
/* Admin. Dossier */
$rep=DbConnect();
require_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();

include("preference.php");
include_once("user_menu.php");

echo "<H2 class=\"info\">Commercial ".$_SESSION['g_name']." </H2>";

$p_action=(isset ($_REQUEST['p_action']))?$_REQUEST['p_action']:"";
// TODO Menu with all the customer
echo ShowItem(array(
		    array('?p_action=client','Client'),
		    array('?p_action=fournisseur','Fournisseur'),
		    array('login.php','Accueil',"Accueil"),
		    array('logout.php','logout',"Sortie")
		    ),
	      'H',"mtitle","mtitle","?p_action=$p_action");


		    //


$cn=DbConnect($_SESSION['g_dossier']);
////////////////////////////////////////////////////////////////////////////////
// p_action == client
////////////////////////////////////////////////////////////////////////////////
if ( $p_action == "client" ) 
{
  require_once("client.inc.php");
}// $p_action == fournisseur
////////////////////////////////////////////////////////////////////////////////
// Fournisseur
if ( $p_action == 'fournisseur') 
{
  require_once("fournisseur.inc.php");
}