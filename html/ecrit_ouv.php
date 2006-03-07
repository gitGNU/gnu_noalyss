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
/* $Revision$ */
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
include_once ("ac_common.php");
require_once('class_widget.php');
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

include_once ("check_priv.php");

include_once ("user_menu.php");
ShowMenuCompta($_SESSION['g_dossier']);
$cn=DbConnect($_SESSION['g_dossier']);
// TODO : add a check for permission
if ( $User->CheckAction($cn,GJRN) == 0 ) {
  /* Cannot Access */
  NoAccess();
  exit -1;			
 }

echo '<div class="u_subtmenu">';
echo ShowMenuAdvanced("ecrit_ouv.php");
/////////////////////////// EXPORT ////////////////////////////////////////////
if ( isset ($_GET['export'])) {
// if the year is not set, ask it
	// ask the exercice and do the export
	$periode=make_array($cn,"select distinct p_exercice,p_exercice from parm_periode order by p_exercice");
	echo '<form method="GET" ACTION="export_ouv.php">';
	$w=new widget('select');
	echo '<table>';
	$w->table=1;
	$w->label='Periode';
	$w->readonly=false;
	$w->value=$periode;
	$w->name="p_periode";
	echo $w->IOValue();
	echo '<TD>'.$w->Submit('export','Export').'</TD>';
	echo '</table>';
	echo "</form>";
	exit(0);
}
/////////////////////////// IMPORT ////////////////////////////////////////////
if ( isset ($_GET['import'])) {
	// show a form to upload the file
	// that form will parse the file, create an ods operation
	// and ask you to validate it
	exit(0);
}
// IF import and export are not set then the choice is proposed
echo '<div>';
echo ShowItem ( array (
			array ("ecrit_ouv.php?export","Export"),
			array ("ecrit_ouv.php?import","Import")
			),'V');
echo '</div>';
html_page_stop(); 
?>