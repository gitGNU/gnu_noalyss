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
html_min_page_start($_SESSION['g_theme']);
include_once("user_menu.php");

$priv=($User->admin==1)?"Administrateur":"Utilisateur";
echo '<div class="info"> ';

$result="<table border=\"0\">";
$result.='<TR>';
if ( $User->Admin()  == 1 ) {
  $result.="<TD  class=\"cell\" ><A class=\"cell\" HREF=\"admin_repo.php\"> Administration  </A></TD>";
}
$result.='<TD  class="cell" ><A class="cell" HREF="manuel-fr.pdf" > Aide </a></TD>';
$result.='<TD class="cell"><A class="cell" HREF="user_pref.php">Pr&eacute;f&eacute;rence</a></TD>';
$result.='<TD  class="cell" ><A class="cell" HREF="logout.php" >Deconnexion</a></TD>';
$result.="</TR>";
$result.="</table>";

echo '<h2 class="info">Bienvenue  '.$User->first_name.'  '.$User->name." dans PhpCompta </h2>";
echo '<br>Choississez votre dossier';
echo '<span style="position:absolute;right:10px;top:30px">'.$result.'</span>';
echo '</div>';
?>
<form method="get" action="?">
<input type="submit" value="Rechercher">
<?php
$w=new IText();
$p_nom=isset($_GET ['p_nom'])?$_GET['p_nom']:"";
echo $w->IOValue('p_nom',$p_nom);

?>
<span class="notice">Donnez une partie du nom du dossier &agrave; rechercher</span>
</form>
<?php
$filtre="";
if ( isset ($_GET ['p_nom'])) {
     $filtre=FormatString($_GET['p_nom']);
}

// If admin show everything otherwise only the available dossier
$res=u_ShowDossier($_SESSION['g_user'],$User->Admin(),$filtre);
echo $res;
?>
<P>

</P>
<?php
html_page_stop();
?>
