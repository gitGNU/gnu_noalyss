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
/* $Revision$ */
include_once ("ac_common.php");
include_once ("postgres.php");
/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();

// Met a jour le theme utilisateur (style)
if ( isset ( $_POST['style_user']) ) {
  $CnRepo=DbConnect();
      $Res=ExecSql($CnRepo,
		   "update ac_users set use_theme='".$_POST['style_user'].
		   "'  where use_login='".$_SESSION['g_user']."'");
 //     echo '<H2 class="info"> Theme utilisateur changé </H1>';
      $_SESSION['use_theme']=$_POST['style_user'];

}

html_page_start($_SESSION['use_theme']);

// show the top menu depending of the use_style
// comta style

include_once ("user_menu.php");
if ( isset ($_SESSION['g_dossier']) ) {
  if ( $_SESSION['g_dossier'] != 0 )  
    ShowMenuCompta($_SESSION['g_dossier']);
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
      $Res=ExecSql($Cn,"update ac_users set use_pass='$l_pass' where use_login='".$_SESSION['g_user']."'");
      $pass=$pass_1;
      $_SESSION['g_pass']=$pass_1;
      $g_pass=$pass_1;
    }
  }
// Met a jour le theme utilisateur (style)
if ( isset ( $_POST['style_user']) ) {
  $CnRepo=DbConnect();
      $Res=ExecSql($CnRepo,
		   "update ac_users set use_theme='".$_POST['style_user'].
		   "'  where use_login='".$_SESSION['g_user']."'");
 //     echo '<H2 class="info"> Theme utilisateur changé </H1>';
      $_SESSION['use_theme']=$_POST['style_user'];

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
// charge tous les styles
$cnRepo=DbConnect();
$res=ExecSql($cnRepo,"select the_name from theme
                      order by the_name");
for ($i=0;$i < pg_NumRows($res);$i++){
  $st=pg_fetch_array($res,$i);
  $style[]=$st['the_name'];
}
// Formatte le display
$disp_style="<SELECT NAME=\"style_user\" >";
foreach ($style as $st){
  if ( $st == $_SESSION['use_theme'] ) {
    $disp_style.='<OPTION VALUE="'.$st.'" SELECTED>'.$st;
  } else {
    $disp_style.='<OPTION VALUE="'.$st.'">'.$st;
  }
}
$disp_style.="</SELECT>";
?>
<H2 class="info">Thème</H2>
<FORM ACTION="user_pref.php" METHOD="post">
<TABLE ALIGN="center">
<TR>
   <TD> Style </TD>
   <TD> <? print $disp_style;?> </TD>
</TR>
<TR>
   <td colspan=2> <INPUT TYPE="submit" Value="Sauve"></TD>
</TR>
</TABLE>
</FORM>

<?

// Si utilise un dossier alors propose de changer
// la periode par defaut
if ( isset ($_SESSION['g_dossier']) ) {

  include_once("preference.php");
  $cn=DbConnect($_SESSION['g_dossier']);

  if ( isset ($_POST["sub_periode"] ) ) {
    $periode=$_POST["periode"];
    SetUserPeriode($cn,$periode,$_SESSION['g_user']); 
  }

  $l_user_per=GetUserPeriode($cn,$_SESSION['g_user']);
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
