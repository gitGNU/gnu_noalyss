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
include_once ("ac_common.php");
html_page_start($_SESSION['use_theme']);
if ( !isset ($_SESSION['g_dossier'])) {
    echo "You must choose a Dossier ";
  phpinfo();
  exit -2;
}
include_once ("postgres.php");
include_once("check_priv.php");

/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();

if ( $User->admin == 0 ) {
  $r=CheckAction($_SESSION['g_dossier'],$_SESSION['g_user'],SECU);
  if ($r == 0 ){
    /* Cannot Access */
    NoAccess();
  exit -1;			

  }

}

include ("user_menu.php");
ShowMenuComptaRight($_SESSION['g_dossier'],$User->admin);
$l_Db=sprintf("dossier%d",$_SESSION['g_dossier']);
echo '<DIV CLASS="ccontent">';

?>
<FORM ACTION="pcmn_update.php" METHOD="POST">
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
<TR>
<TD><INPUT TYPE="Submit" VALUE="Save">
<INPUT TYPE="HIDDEN" name="update">
<? printf ('<INPUT TYPE="HIDDEN" name="p_old" value="%s">',$l); ?>
</TD></TR>
<TR> <TD class="mtitle"><A CLASS="mtitle" HREF="pcmn_update.php">Retour sans sauver</A></TD></TR>
</TABLE>
</FORM>
</DIV>

<?
html_page_stop();
?>
