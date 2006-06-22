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
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
// $Revision$
/*! \file
 * \brief Modify the "Plan Comptable"
 */
include_once ("ac_common.php");

if ( !isset ($_SESSION['g_dossier'])) {
    echo "You must choose a Dossier ";
   exit -2;
}
include_once ("postgres.php");
include_once("check_priv.php");

/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();
html_page_start($User->theme,"onLoad='window.focus();'");

if ( $User->CheckAction(DbConnect($_SESSION['g_dossier']),MPCMN) == 0 ) {
  /* Cannot Access */
  NoAccess();
  exit -1;			
 }

include ("user_menu.php");

/* Modif d'une ligne */
if ( isset ($_POST["update"] ) ) {
  foreach ($HTTP_POST_VARS as $name => $element) {
    echo_debug('line_update.php',__LINE__,"name $name $element");
  }
  echo JS_UPDATE_PCMN;
  $cn=DbConnect($_SESSION['g_dossier']);
    $p_val=trim($_POST["p_val"]);
    $p_lib=FormatString($_POST["p_name"]);
    $p_parent=trim($_POST["p_val_parent"]);
    $old_line=trim($_POST["p_old"]);
    echo_debug('line_update.php',__LINE__,"Update old : $old_line News = $p_val $p_lib");
    if ( strlen ($p_val) != 0 && strlen ($p_lib) != 0 && strlen($old_line)!=0 ) {
      if (strlen ($p_val) == 1 ) {
	$p_parent=0;
      } else {
	if ( strlen($p_parent)==0 ) {
	  $p_parent=substr($p_val,0,strlen($p_val)-1);
	  echo_debug('line_update.php',__LINE__,"Modif valeur = $p_val parent = $p_parent");
	}
      }
      /* Parent existe */
      $Ret=ExecSql($cn,"select pcm_val from tmp_pcmn where pcm_val=$p_parent");
      if ( pg_NumRows($Ret) == 0 || $p_parent==$old_line ) {
	echo '<SCRIPT> alert(" Ne peut pas modifier; aucune poste parent"); </SCRIPT>';
      } else {
	
	$Ret=ExecSql($cn,"update tmp_pcmn set pcm_val=$p_val, pcm_lib='$p_lib',pcm_val_parent=$p_parent where pcm_val=$old_line");
      }
    } else {
      echo '<script> alert(\'Update Valeurs invalides\'); </script>';
    }

  echo "<script> 
        window.close();
         self.opener.RefreshMe();

        </script>";

}

?>
<FORM ACTION="line_update.php" METHOD="POST">
<TABLE>
<TR>
<?
$l=$_GET['l'];
$p=$_GET['p'];
$n=$_GET['n'];

printf ('<TD>Numéro de classe </TD><TD><INPUT TYPE="TEXT" name="p_val" value="%s"></TD>',$l);
echo "</TR><TR>";
printf('<TD>Libellé </TD><TD><INPUT TYPE="TEXT" size="70" NAME="p_name" value="%s"></TD>',urldecode($n));
echo "</TR><TR>";
printf ('<TD>Classe Parent</TD><TD><INPUT TYPE="TEXT" name="p_val_parent" value="%s"></TD>',$p);
?>
</TR>
</TABLE>
<TABLE>
<TR>
<TD><INPUT TYPE="Submit" VALUE="Sauve">
<INPUT TYPE="HIDDEN" name="update">
<? printf ('<INPUT TYPE="HIDDEN" name="p_old" value="%s">',$l); ?>
</TD><TD><input type="button"  Value="Retour sans sauver" onClick='window.close();'></TD></TR>
</TABLE>
</FORM>


<?
html_page_stop();
?>
