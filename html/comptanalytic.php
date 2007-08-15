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

/* !\file 
 */

/* \brief Analytic accountancy
 *
 */
require_once("constant.php");
require_once("postgres.php");
require_once("ac_common.php");

if ( isset ($_GET['dos']) )
	 $_SESSION['g_dossier']=$_GET['dos'];

if ( ! isset ( $_SESSION['g_dossier'] ) ) {
  echo "Vous devez choisir un dossier ";
  exit -2;
}
$cn=DbConnect($_SESSION['g_dossier']);
require_once ("class_user.php");
$User=new cl_user($cn);
$User->Check();



$g_name=GetDossierName($_SESSION['g_dossier']);
$_SESSION["g_name"]=$g_name;
html_page_start($_SESSION['g_theme']);

//-----------------------------------------------------------------
//Header
echo '<div class="u_tmenu">';
echo "<H2 class=\"info\">Analytique ".$_SESSION['g_name']." ";
echo '<div align="right" title="Recherche">
<input type="IMAGE" src="image/search.png" width="36" onclick="openRecherche(\''.$_REQUEST['PHPSESSID'].'\','.$_SESSION['g_dossier'].');">
<A HREF="?p_action=pref" title="Pr&eacute;f&eacute;rence"><IMG SRC="image/preference.png" width="36" border="0" ></A>
<A HREF="user_compta.php?dos='.$_SESSION['g_dossier'].'" title="Comptabilit&eacute;"><IMG SRC="image/compta.png" width="36"  border="0" ></A>
<A HREF="parametre.php?dos='.$_SESSION['g_dossier'].'" title="Param&egrave;tre"><IMG SRC="image/param.png" width="36" border="0" ></A>
<A HREF="login.php" title="Accueil"><IMG src="image/home.png" width="36" title="Accueil"  border="0"  ></A>
<A HREF="logout.php" title="Sortie"><IMG src="image/logout.png" title="Logout"  width="36"  border="0"></A>
</div> </h2>';
//-----------------------------------------------------------------

$def=-1;
if ( isset ($_REQUEST['p_action']))
  {
	switch ($_REQUEST['p_action'])
	  {
	  case 'ca_pa':
		$def=0;
		break;
	  case 'ca_od':
		$def=1;
		break;
	  case 'ca_imp':
		$def=2;
		break;
	  }
  }
echo ShowItem(array(
					array('?p_action=ca_pa','Plan Analytique',"Plan Analytique",0),
					array('?p_action=ca_od','Op&eacute;rations Diverses',"Permet d'enregistrer des opérations sur la compta analytique",1),
					array('?p_action=ca_imp','Impression',"impression de rapport",2)
		    ),
	      'H',"mtitle","mtitle",$def,' width="100%"');
echo '</div>';

if ( !isset($_REQUEST['p_action']))
  exit();

//---------------------------------------------------------------------------
// p_action
//---------------------------------------------------------------------------
// Plan Analytique
if ($_REQUEST['p_action'] == 'ca_pa' )
  {
	require_once('ca_pa.inc.php');
	exit();
  }

// Operations Diverses
if ($_REQUEST['p_action'] == 'ca_od' )
  {
	require_once('ca_od.inc.php');
	exit();
  }

// Impression
if ($_REQUEST['p_action'] == 'ca_imp' )
  {
	require_once('ca_imp.inc.php');
	exit();
  }
