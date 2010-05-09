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
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/* $Revision$ */

/*! \file
 * \brief Main page for the printing
 */
$str_dossier=dossier::get();
// show sub menu

echo "<DIV class=\"lmenu\">";
echo JS_AJAX_FICHE;

$p_array=array(array ("?p_action=impress&type=jrn&".$str_dossier,_("Journaux")),
	       array("?p_action=impress&type=gl_comptes&".$str_dossier,_("Grand Livre")), /* AG */
	       array("?p_action=impress&type=poste&".$str_dossier,_("Poste")),
	       array("?p_action=impress&type=fiche&".$str_dossier,_("Catégorie")),
	       array("?p_action=impress&type=rapport&".$str_dossier,_("Rapport")),
               array('?p_action=impress&type=bal&'.$str_dossier,_('Balance des comptes')),
	       array("?p_action=impress&type=bilan&".$str_dossier,_("Bilan"))
	       );
$default=( isset ($_GET['type']))?"?p_action=impress&type=".$_GET['type']."&$str_dossier":"";


$result=ShowItem($p_array,'H',"mtitle","mtitle",$default,' ');
echo $result;

echo "</DIV>";
$cn=new Database($gDossier);


include_once("impress_inc.php");


// something is choosen
$default=( isset ($_REQUEST['type']))?$_REQUEST['type']:"";
  switch ($default) {
  case "jrn":
    $User->can_request(IMPJRN,1);
    require_once ("impress_jrn.inc.php");
    break;
  case "poste":
    $User->can_request(IMPPOSTE,1);
    require_once ("impress_poste.inc.php");
    break;
  case "rapport":
    $User->can_request(IMPRAP,1);
    require_once ("impress_rapport.inc.php");
    break;
  case "bilan":
    $User->can_request(IMPBIL,1);
    require_once ("impress_bilan.inc.php");
    break;
  case "gl_comptes": /* AG */
    $User->can_request(IMPBIL,1);
    require_once ("impress_gl_comptes.inc.php");
    break;

  case "bal":
    $User->can_request(IMPBAL,1);
    require_once ("balance.inc.php");
    break;
  case "fiche":
    $User->can_request(IMPFIC,1);
    require_once ("impress_fiche.inc.php");
    break;

  }

html_page_stop();
?>
