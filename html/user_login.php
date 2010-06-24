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
require_once('class_database.php');
require_once('class_itext.php');
$rep=new Database();
include_once ("class_user.php");
$User=new User($rep);

$User->Check();
/*  Check Browser version if < IE6 then unsupported */
$browser=$_SERVER['HTTP_USER_AGENT'];
if ( strpos($browser,'MSIE 6')!=false ||
     strpos($browser,'MSIE 5')!=false ) {
  $nav=_('Vous utilisez un navigateur dépassé depuis près de 8 ans!');
  $nav2=_("Pour une meilleure expérience web, prenez le temps de mettre votre navigateur à jour");
echo <<<EOF
    <!--[if lt IE 7]>
  <div style='border: 1px solid #F7941D; background: #FEEFDA; text-align: center; clear: both; height: 75px; position: relative;'>
    <div style='position: absolute; right: 3px; top: 3px; font-family: courier new; font-weight: bold;'><a href='#' onclick='javascript:this.parentNode.parentNode.style.display="none"; return false;'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-cornerx.jpg' style='border: none;' alt='Close this notice'/></a></div>
    <div style='width: 640px; margin: 0 auto; text-align: left; padding: 0; overflow: hidden; color: black;'>
      <div style='width: 75px; float: left;'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-warning.jpg' alt='Warning!'/></div>
      <div style='width: 275px; float: left; font-family: Arial, sans-serif;'>
        <div style='font-size: 14px; font-weight: bold; margin-top: 12px;'>$nav</div>
  <div style='font-size: 12px; margin-top: 6px; line-height: 12px;'>$nav2.</div>
      </div>
      <div style='width: 75px; float: left;'><a href='http://fr.www.mozilla.com/fr/' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-firefox.jpg' style='border: none;' alt='Get Firefox 3.5'/></a></div>
      <div style='width: 73px; float: left;'><a href='http://www.apple.com/fr/safari/download/' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-safari.jpg' style='border: none;' alt='Get Safari 4'/></a></div>
      <div style='float: left;'><a href='http://www.google.com/chrome?hl=fr' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-chrome.jpg' style='border: none;' alt='Get Google Chrome'/></a></div>
    </div>
  </div>
  <![endif]-->
EOF;
exit();
}
$ac=new Database();

/* check if repo valid */
if ( $ac->exist_table('version') == false) {
  echo '<h2 class="error" style="font-size:12px">'._("Base de donnée invalide").'</h2>';
  $base=dirname($_SERVER['REQUEST_URI']);
  exit();
}

/* check repo version */
$version = $ac->get_value('select val from version');
if ( $version < DBVERSIONREPO ) {
  echo '<h2 class="error" style="font-size:12px">'._("Votre base de données n'est pas à jour").'   ';
  $a=_("cliquez ici pour appliquer le patch");
  $base=dirname($_SERVER['REQUEST_URI']).'/admin/setup.php';
  echo '<a hreF="'.$base.'">'.$a.'</a></h2>';

}

html_page_start($_SESSION['g_theme']);
include_once("user_menu.php");

$priv=($User->admin==1)?"Administrateur":"Utilisateur";
echo '<div class="welcome"> ';
/**
 *
 * If the user is NOT admin and can access only ONE folder,
 * so it will be directly redirected to this folder or to the plugins of this
 * folder if he's an "plugin user"
 */
if ( $User->admin == 0 ) {
  // how many folder ?
  $folder=GetAvailableFolder($_SESSION['g_user'],0);
  if ( count($folder) == 1 ) {
    if ( $User->check_dossier($folder[0]['dos_id']) == 'P')  {
      redirect('extension.php?gDossier='.$folder[0]['dos_id']);
      exit();
    }
    else 
      {
	redirect('access.php?gDossier='.$folder[0]['dos_id']);
	exit();
      }
  }

}
$result="<table border=\"0\">";
$result.='<TR>';
if ( $User->Admin()  == 1 ) {
  $result.="<TD  class=\"tool\" ><A class=\"cell\" HREF=\"admin_repo.php\"> Administration  </A></TD>";
}
$result.='<TD class="tool"><A class="cell" HREF="user_pref.php">'._('Préférence').'</a></TD>';
$result.='<TD  class="tool" ><A class="cell" HREF="logout.php" >'._('Deconnexion').'</a></TD>';
$result.="</TR>";
$result.="</table>";

echo '<h2 class="info">'._('Bienvenue').$User->first_name.'  '.$User->name.' '._("dans PhpCompta")."</h2>";
echo '<br>'._('Choississez votre dossier');
echo '<span style="position:absolute;right:10px;top:30px">'.$result.'</span>';
echo '</div>';
?>
<form method="get" action="?">
<input type="submit" class="button" value="<?php echo _('Rechercher');?>">
<?php
$w=new IText();
$p_nom=isset($_GET ['p_nom'])?$_GET['p_nom']:"";
echo $w->input('p_nom',$p_nom);

?>
<span class="notice">
<?php
  echo _('Donnez une partie du nom du dossier à rechercher');
?>
</span>
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
