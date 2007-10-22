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
require_once('class_dossier.php');
$gDossier=dossier::id();
$str_dossier=dossier::get();
$cn=DbConnect($gDossier);
require_once ("class_user.php");
$User=new cl_user($cn);
$User->Check();



html_page_start($_SESSION['g_theme']);

//-----------------------------------------------------------------
//Header
echo '<div class="u_tmenu">';
echo "<H2 class=\"info\">Analytique ".dossier::name()." ";
echo '<div align="right" title="Recherche">
<input type="IMAGE" src="image/search.png" width="36" onclick="openRecherche(\''.$_REQUEST['PHPSESSID'].'\','.dossier::id().');">
<A HREF="?p_action=pref&'.$str_dossier.'" title="Pr&eacute;f&eacute;rence"><IMG SRC="image/preference.png" width="36" border="0" ></A>
<A HREF="user_compta.php?'.$str_dossier.'" title="Comptabilit&eacute;"><IMG SRC="image/compta.png" width="36"  border="0" ></A>
<A HREF="parametre.php?'.$str_dossier.'" title="Param&egrave;tre"><IMG SRC="image/param.png" width="36" border="0" ></A>
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
		$User->AccessRequest($cn,CA_ACCESS);
		$def=0;
		break;
	  case 'ca_od':
		$User->AccessRequest($cn,CA_ODS);
		$def=1;
		break;
	  case 'ca_imp':
		$User->AccessRequest($cn,CA_IMPRESSION);
		$def=2;
		break;
	  }
  }
echo ShowItem(array(
					array('?p_action=ca_pa&'.$str_dossier,'Plan Analytique',"Plan Analytique",0),
					array('?p_action=ca_od&'.$str_dossier,'Op&eacute;rations Diverses',"Permet d'enregistrer des opérations sur la compta analytique",1),
					array('?p_action=ca_imp&'.$str_dossier,'Impression',"impression de rapport",2)
		    ),
	      'H',"mtitle","mtitle",$def,' width="100%"');
echo '</div>';

if ( !isset($_REQUEST['p_action']))
  exit();

//-----------------------------------------------------
// p_action == pref
//-----------------------------------------------------
if ( $_REQUEST['p_action'] == "pref" ) 
{
  require_once("pref.inc.php");
  exit();
}

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
