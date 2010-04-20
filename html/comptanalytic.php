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

/*!\file
 * \brief Main page for the analytic module
 */

/*! \brief Analytic accountancy
 *
 */
require_once("constant.php");
require_once('class_database.php');
require_once("ac_common.php");
require_once('class_dossier.php');
$gDossier=dossier::id();
$str_dossier=dossier::get();
$cn=new Database($gDossier);
require_once ("class_user.php");
require_once ('user_menu.php');
$User=new User($cn);
$User->Check();
if ($User->check_dossier($gDossier) == 'P') exit();


html_page_start($_SESSION['g_theme']);
echo js_include('prototype.js');
echo js_include('anc_script.js');
//-----------------------------------------------------------------
//Header
echo '<div class="u_tmenu">';
//-----------------------------------------------------------------
echo menu_tool('comptanalytic.php');
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
      case 'ca_groupe':
	$def=3;
	break;
      }
  }
echo '<div style="float:left;background-color:#879ED4;width:100%;">';
echo ShowItem(array(
	array('?p_action=ca_pa&'.$str_dossier,'Plan Analytique',"Plan Analytique",0),
	array('?p_action=ca_od&'.$str_dossier,'Op&eacute;rations Diverses',"Permet d'enregistrer des operations sur la compta analytique",1),
	array('?p_action=ca_groupe&'.$str_dossier,'Groupe','Regroupe les postes analytiques',3),
	array('?p_action=ca_imp&'.$str_dossier,'Impression',"impression de rapport",2)
		    ),
	      'H',"mtitle","mtitle",$def,' style="width:75%;margin-left:12%"');
echo '</div>';
echo '</div>';
echo '</div>';
if ( isset ($_REQUEST['p_action']))
  {
    switch ($_REQUEST['p_action'])
      {
      case 'ca_pa':
	$User->can_request(CAPA,1);
	$def=0;
	break;
      case 'ca_od':
	$User->can_request(CAOD,1);
	$def=1;
	break;
      case 'ca_imp':
	$User->can_request(CAIMP,1);
	$def=2;
	break;
      case 'ca_groupe':
	$User->can_request(CAGA,1);
	$def=3;
	break;
      }
  }

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
    require_once('anc_pa.inc.php');
    exit();
  }

// Operations Diverses
if ($_REQUEST['p_action'] == 'ca_od' )
  {
	require_once('anc_od.inc.php');
	exit();
  }
// Impression
if ($_REQUEST['p_action'] == 'ca_groupe' )
  {
	require_once('anc_group.inc.php');
	exit();
  }

// Impression
if ($_REQUEST['p_action'] == 'ca_imp' )
  {
    echo '<div class="content">';

    require_once('anc_imp.inc.php');
    echo '</div>';

    exit();
  }
