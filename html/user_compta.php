<?php
/*
 *   This file is part of PHPCOMPTA
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
 *   along with PHPCOMPTA; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
// Auteur Dany De Bontridder ddebontridder@yahoo.fr
require_once('class_dossier.php');
include_once ("ac_common.php");
/* $Revision$ */
include_once ("class_user.php");
require_once('class_database.php');
/*! \file
 * \brief main page of the accountancy module
 */

$User=new User(new Database());
$User->check_dossier(dossier::id());
$cn=new Database(dossier::id());
$User->cn=$cn;
$User->Check();

html_page_start($User->theme);
$gDossier=dossier::id();

echo_debug('user_compta.php',__LINE__,"user is ".$_SESSION['g_user']);

// Get The priv on the selected folder
if ( $User->admin == 0 && $User->is_local_admin(dossier::id()) == 0 ) {
  
  $r=$User->get_folder_access($gDossier);
  if ($r == 'X' ){
    /* Cannot Access */
    NoAccess(1);
  }
}




include_once ("user_menu.php");
echo '<div class="u_tmenu">';

echo ShowMenuCompta();
echo '</div>';
html_page_stop();

?>
