
<?
/*
 *   This file is part of PHPCOMPTA.
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
include_once("preference.php");

html_page_start($g_UserProperty['use_theme']);

if ( ! isset ( $g_dossier ) ) {
  echo "You must choose a Dossier ";
  phpinfo();
  exit -2;
}
include_once ("postgres.php");
/* Admin. Dossier */
CheckUser();

// Synchronize rights
SyncRight($g_dossier,$g_user);

// Get The priv on the selected folder
if ( $g_UserProperty['use_admin'] == 0 ) {
  
  $r=GetPriv($g_dossier,$g_user);
  if ($r == 0 ){
    /* Cannot Access */
    NoAccess();
  }

}
$l_dossier=sprintf("dossier%d",$g_dossier);
$cn=DbConnect($l_dossier);

//Show the top menu
include_once ("user_menu.php");
include_once ("top_menu_compta.php");
ShowMenuCompta($g_dossier,$g_UserProperty);

// Show the right menu
ShowMenuComptaRight($g_dossier,$g_UserProperty); 

// Show Menu Left
$left_menu=ShowMenuAdvanced();
echo '<div class="lmenu">';
echo $left_menu;
echo '</DIV>';

// Show the current stock
echo '<div class="u_redcontent">';
echo '<FORM action="stock.php" method="post">';


echo '<input type="submit" name="view" value="ok">';
echo '</div>';

?>