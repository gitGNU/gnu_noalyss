<?
/*
 *   This file is part of WCOMPTA.
 *
 *   WCOMPTA is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   WCOMPTA is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with WCOMPTA; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Auteur Dany De Bontridder ddebontridder@yahoo.fr
/* $Revision$ */
include_once ("ac_common.php");
html_page_start();

include_once ("postgres.php");
/* Admin. Dossier */
CheckUser();

include_once ("top_menu_compta.php");
if ( isset ($g_dossier) ) {
  if ( $g_dossier != 0 )  ShowMenuCompta($g_dossier);
  ShowMenuComptaRight($g_dossier);
} else {
 ShowMenuComptaRight();
}
echo '<DIV class="ccontent">';

if ( isset ($spass) ) {
  if ( $pass_1 != $pass_2 ) {
?>
<script>
   alert("Les mots de passe ne correspondent pas. Mot de passe inchangé");
</script>
<?
    }
    else {
      $Cn=DbConnect();
      $l_pass=md5($pass_1);
      $Res=ExecSql($Cn,"update ac_users set use_pass='$l_pass' where use_login='$user'");
      $pass=$pass_1;
      echo '<H2 class="info"> Mot de passe changé </H1>';
    }
  }

?>
<H2 CLASS="info"> Password</H2>
<FORM ACTION="user_pref.php" METHOD="POST">
<TABLE ALIGN="CENTER">
<TR><TD><input type="password" name="pass_1"></TD></TR>
<TR><TD><input type="password" name="pass_2"></TD></TR>
<TR><TD><input type="submit" name="spass" value="Change mot de passe"></TD></TR>
</TABLE>
</FORM>
<?
if ( isset ($g_dossier) ) {

  include_once("preference.php");
  $l_dossier=sprintf("dossier%d",$g_dossier);
  $cn=DbConnect($l_dossier);

  if ( isset ($_POST["sub_periode"] ) ) {
    $periode=$_POST["periode"];
    SetUserPeriode($cn,$periode,$user); 
  }

  $l_user_per=GetUserPeriode($cn,$user);
  $l_form_per=FormPeriode($cn,$l_user_per);

?>
<H2 CLASS="info"> Période</H2>
<FORM ACTION="user_pref.php" METHOD="POST">
<TABLE ALIGN="CENTER">
<TR><TD>PERIODE</TD>
<?    printf('<TD> %s </TD></TR>',$l_form_per); ?>
<TR><TD><input type="submit" name="sub_periode" value="Sauve"></TD></TR>
</TABLE>
</FORM>

<?
}
     
echo "</DIV>";
html_page_stop();
?>
