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

html_page_start($g_UserProperty['use_theme']);
if ( ! isset ( $g_dossier ) ) {
  echo "You must choose a Dossier ";
  phpinfo();
  exit -2;
}
include_once ("postgres.php");
/* Admin. Dossier */
CheckUser();

include_once ("postgres.php");
echo_debug(__FILE__,__LINE__,"user is $g_user");
/* CheckUser */
CheckUser();

// We don't check permissions here in fact, permission are tested in the
// functions 

// Show the top menus
include_once ("user_menu.php");
include_once ("top_menu_compta.php");
ShowMenuCompta($g_dossier,$g_UserProperty);

// Show the right menus
ShowMenuComptaRight($g_dossier,$g_UserProperty); 

$left_menu=ShowMenuAdvanced();
echo '<div class="lmenu">';
echo $left_menu;
echo '</DIV>';


html_page_stop();
?>
