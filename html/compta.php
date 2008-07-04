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
 * \brief Main page for accountancy
 */
require_once('class_dossier.php');
$gDossier=dossier::id();
include_once ("ac_common.php");
html_page_start($_SESSION['g_theme']);


include_once ("postgres.php");
/* Admin. Dossier */
$cn=DbConnect($gDossier);


require_once ('class_user.php');
$User=new User($cn);
$User->Check();

require_once ("check_priv.php");
include_once ("user_menu.php");
echo '<div class="u_tmenu">';
$action=$_REQUEST['p_action'];
echo ShowMenuCompta("user_advanced.php?".dossier::get());

echo '</div>';
// Get action



// call impress sub-menu
if ( $action == 'impress' ) {

  $User->can_request($cn,IMP) ;

  require_once('impress.inc.php');
}

if ( $action == 'fiche') {

  require_once('fiche.inc.php');
}

if ( $action == 'stock') {
  require_once('stock.inc.php');
}
if ( $action=='quick_writing') {
  require_once ('quick_writing.inc.php');
 }
if ( $action == 'gl' ) {
  require_once ('user_action_gl.php');
 }
if ( $action == 'ven' ||
     $action == 'client') {
  require_once ('compta_ven.inc.php');
 }
if ( $action == 'ach') {
  require_once ('compta_ach.inc.php');
 }
if ( $action == 'bank') {
  require_once ('compta_fin.inc.php');
 }

html_page_stop();
?>
