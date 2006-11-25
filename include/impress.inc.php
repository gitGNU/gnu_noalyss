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
/*! \file
 * \brief Main page for the printing
 */

// show sub menu

echo "<DIV class=\"u_subtmenu\">";


$p_array=array(array ("?p_action=impress&type=jrn","Journaux"),
	       array("?p_action=impress&type=poste","Poste"),
	       array("?p_action=impress&type=fiche","Fiche"),
	       array("?p_action=impress&type=rapport","Rapport"),
               array('?p_action=impress&type=bal','Balance des comptes'),
	       array("?p_action=impress&type=bilan","Bilan"),
	       array("?p_action=impress&type=list_client","Liste Clients Assujettis")
	       );
$default=( isset ($_GET['type']))?"?p_action=impress&type=".$_GET['type']:"";


$result=ShowItem($p_array,'H',"cell","mtitle",$default);
echo $result;

echo "</DIV>";


if ( $User->admin == 0 ) {
  if (CheckAction($_SESSION['g_dossier'],$_SESSION['g_user'],IMP) == 0 ){
    /* Cannot Access */
    NoAccess();
  }
}

include_once("impress_inc.php");


// something is choosen
$default=( isset ($_REQUEST['type']))?$_REQUEST['type']:"";
  switch ($default) {
  case "jrn":
    require_once ("impress_jrn.php");
    break;
  case "poste":
    require_once ("impress_poste.php");
    break;
  case "rapport":
    require_once ("impress_rapport.php");
    break;
  case "bilan":
    require_once ("impress_bilan.php");
    break;

  case "bal":
    require_once ("balance.php");
    break;
  case "fiche":
    require_once ("impress_fiche.php");
    break;
  case "list_client":
    require_once ("impress_listing_client.php");
    break;

  }

html_page_stop();
?>
