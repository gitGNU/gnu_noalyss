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
/*! \file
 * \brief Welcome page where the folder and module are choosen
 */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
// $Revision$
include_once ("ac_common.php");
include_once("postgres.php");
$rep=DbConnect();
include_once ("class_user.php");
$User=new User($rep);

$User->Check();
html_page_start($_SESSION['g_theme']);
include_once("user_menu.php");

$priv=($User->admin==1)?"Administrateur":"Utilisateur";

echo '<div class="info"> Bienvenue  '.$User->first_name.'  '.
     $User->name.',<h3 class="info"> Vous &ecirc;tes un  '. $priv.' <br>Selectionnez votre dossier</h3></div>';
// Show default menu (preference,...)

// If admin show everything otherwise only the available dossier
$res=u_ShowDossier($_SESSION['g_user'],$User->admin);
echo $res;
?>
<P>

</P>
<?php
html_page_stop();
?>
