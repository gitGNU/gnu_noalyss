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
require_once('class_dossier.php');
include_once("ac_common.php");
require_once("user_menu.php");
include_once ("constant.php");
include_once("jrn.php");
include_once("user_common.php");
include_once("class_widget.php");
/*! \file
 * \brief Search module
 */

html_page_start($_SESSION['g_theme']);
$gDossier=dossier::id();

include_once ("postgres.php");
include_once ("check_priv.php");
/* Admin. Dossier */

$cn=DbConnect($gDossier);
include_once ('class_user.php');
$User=new cl_user($cn);
$User->Check();

   // PhpSessid
$sessid=$_REQUEST['PHPSESSID'];

// display a search box
$search_box=u_ShowMenuRecherche($cn,0,$sessid,$_GET);
echo '<DIV class="lextmenu">'; // class="recherche_form">';
echo $search_box;
echo "</div>";
//-----------------------------------------------------
// Display search result
//-----------------------------------------------------
if ( isset ($_GET['viewsearch'])) {

  // Navigation bar
  $step=$_SESSION['g_pagesize'];
  $page=(isset($_GET['offset']))?$_GET['page']:1;
  $offset=(isset($_GET['offset']))?$_GET['offset']:0;
  if (count ($_GET) == 0) 
    $array=null;
  else
     $array=$_GET;

  list($max_line,$a)=ListJrn($cn,0,"",$array,$offset,2);

  $bar=jrn_navigation_bar($offset,$max_line,$step,$page);


  echo '<div class="u_redcontent">';


  echo $bar;
  echo $a;
  echo $bar;
  echo '</div>';
}
echo '</DIV>'; 
 
?>
