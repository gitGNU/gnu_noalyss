<?
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
include_once ("ac_common.php");
/* $Revision$ */
/*! \file
 * \brief Obsolete
 * \todo Obsolete ?
 */

html_page_start($_SESSION['g_theme']);
if ( ! isset ( $_SESSION['g_dossier'] ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}
include_once ("postgres.php");
/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();

include_once ("postgres.php");
echo_debug('user_advanced.php',__LINE__,"user is ".$_SESSION['g_user']);
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();

// We don't check permissions here in fact, permission are tested in the
// functions 

// Show the top menus
include_once ("user_menu.php");
echo '<div class="u_tmenu">';

echo ShowMenuCompta($_SESSION['g_dossier']);
echo '</div>';

echo ShowMenuAdvanced();


if ( isset($_REQUEST['p_action']) && $_REQUEST['p_action'] == "periode" ) {
  $p_action=$_REQUEST['p_action'];
  include_once("periode.inc.php");
}

html_page_stop();
?>
