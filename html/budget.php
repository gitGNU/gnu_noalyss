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

/*! \brief module budget
 *
 */

require_once("constant.php");
include_once ("postgres.php");
require_once('class_dossier.php');
html_page_start();

$gDossier=dossier::id();

$g_name=dossier::name();

$str_dossier=dossier::get();

include_once ("postgres.php");
/* Admin. Dossier */
$rep=DbConnect($gDossier);
require_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();

echo '<div class="u_tmenu">';
echo '<div style="float:left">';
echo "<H2 class=\"info\">Budget : ".dossier::name()."<h2> ";
echo '</div>';
echo '<div style="clear:both">';


$p_action = ( isset($_REQUEST['p_action']))?$_REQUEST['p_action']:"";

$def=-1;

switch($p_action) {
 case 'hypo':
   $def=1;
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

$cn=DbConnect($gDossier);
/*! \todo module budget add the security
 *$User->AccessRequest($cn,BUDGET);
*/ 
//-----------------------------------------------------
// p_action == hypo
//-----------------------------------------------------
if ( $p_action == "hypo" ) 
{
  require_once("hypo.inc.php");
}
//-----------------------------------------------------
// p_action == 
//-----------------------------------------------------
if ( $p_action == "xxx" ) 
{
  require_once(".inc.php");
}
