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
require_once('user_common.php');

$gDossier=dossier::id();
include_once ("ac_common.php");
$action=(isset($_REQUEST['p_action']))?$_REQUEST['p_action']:'';
$use_html=1;
//----------------------------------------------------------------------
/*!\todo find a way to improve performance : the calendar must not
 *   always be loaded and takes at least 50KB
 */
if ( $action == 'ven' && ! isset ($_REQUEST['sa']) )
  $use_html=1;

if ( ! isset ($_SESSION['g_theme'])) {
	echo '<h2 class="error"> Vous êtes déconnecté</h2>';
	redirect('index.php',5);

	exit();
}
if ( $action == 'ven' && isset ($_REQUEST['sa']) && in_array($_REQUEST['sa'],array('n','p')  ))
  $use_html=1;
if ( $use_html == 1) 
  html_page_start($_SESSION['g_theme']);
else 
  html_min_page_start($_SESSION['g_theme']);
//----------------------------------------------------------------------

require_once('class_database.php');
/* Admin. Dossier */
$cn=new Database($gDossier);


require_once ('class_user.php');
$User=new User($cn);
$User->Check();

include_once ("user_menu.php");
echo '<div class="u_tmenu">';

echo ShowMenuCompta("user_advanced.php?".dossier::get());

echo '</div>';
// Get action

// call impress sub-menu
if ( $action == 'impress' ) {
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
if ( $action == 'ach' ||
     $action == 'fournisseur') {
  require_once ('compta_ach.inc.php');
 }
if ( $action == 'bank') {
  require_once ('compta_fin.inc.php');
 }

html_page_stop();
?>
