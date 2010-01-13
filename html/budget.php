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
 * \brief Main page for budget
 */

/*! \brief module budget
 *
 */
require_once ('user_menu.php');
require_once("constant.php");
require_once('class_database.php');
require_once('class_dossier.php');
require_once ('class_bud_hypo.php');
html_page_start($_SESSION['g_theme']);

$gDossier=dossier::id();

$g_name=dossier::name();

$str_dossier=dossier::get();

require_once('class_database.php');
/* Admin. Dossier */
$rep=new Database($gDossier);
require_once ("class_user.php");
$User=new User($rep);
$User->Check();

echo '<div class="u_tmenu">';
echo menu_tool("budget.php");
echo '<div style="clear:both">';
/* security */

$p_action = ( isset($_REQUEST['p_action']))?$_REQUEST['p_action']:"";

$def=-1;

switch($p_action) {
 case 'hypo':
   $def=1;
   break;
 case 'fiche':
   $def=2;
   break;
 case 'detail':
   $def=3;
   break;
 case 'synthese':
   $def=4;
   break;
   

 }

echo ShowItem(array(
		    array('?p_action=hypo&'.$str_dossier,'Hypothese',"Hypothese de budget",1),
		    array('?p_action=fiche&'.$str_dossier,'Fiche',"Fiche des budget",2),
		    array('?p_action=detail&'.$str_dossier,'Detail',"Detail",3),
		    array('?p_action=synthese&'.$str_dossier,'Synthese',"Synthese",4)
		    ),
	      'H',"mtitle","mtitle",$def,' width="100%"');


		    //
echo '</div>';
echo '</div>';
echo '</div>';
$User->can_request(BUDLEC,1);

$cn=new Database($gDossier);
$obj=new Bud_Hypo($cn);

//-----------------------------------------------------
// p_action == hypo
//-----------------------------------------------------
if ( $p_action == "hypo" ) 
{
  $User->can_request(BUDHYP,1);
  require_once("bud_hypo.inc.php");
}

//-----------------------------------------------------
// p_action == fiche
//-----------------------------------------------------
if ( $p_action == "fiche" ) 
{
  $User->can_request(BUDFIC,1);
  if ( $obj->size()==0 ) {
    echo '<h2 class="info">Desole pas d\'hypothese definie</h2>';
    exit();
  }
  require_once("bud_card.inc.php");
}

//-----------------------------------------------------
// p_action == 
//-----------------------------------------------------
if ( $p_action == "detail" ) 
{
  if ( $obj->size()==0 ) {
    echo '<h2 class="info">Desole pas d\'hypothese definie</h2>';
    exit();
  }

  require_once("bud_detail.inc.php");
}


//-----------------------------------------------------
// p_action == synthese
//-----------------------------------------------------
if ( $p_action == "synthese" ) 
{
  $User->can_request(BUDIMP,1);
  if ( $obj->size()==0 ) {
    echo '<h2 class="info">Desole pas d\'hypothese definie</h2>';
    exit();
  }

  require_once("bud_synthese.inc.php");
}
