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

// Met a jour le theme utilisateur (style)
if ( isset ( $_POST['style_user']) ) {
  $CnRepo=DbConnect();
      $Res=ExecSql($CnRepo,
		   "update ac_users set use_theme='".$_POST['style_user'].
		   "'  where use_login='$g_user'");
 //     echo '<H2 class="info"> Theme utilisateur changé </H1>';
      $g_UserProperty['use_theme']=$_POST['style_user'];

}
// // Met à jour le profil
// if ( isset ( $_POST['profile_user']) ) {
//       $g_UserProperty['use_usertype']=$_POST['profile_user'];

// }
html_page_start($g_UserProperty['use_theme']);

// clean session variable from user interface (make prob. with compta  interfac)
//
if ( isset ($g_jrn) ) {
  session_unregister("g_jrn");
}

/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();

// show the top menu depending of the use_style
// comta style

include_once ("top_menu_compta.php");
if ( isset ($g_dossier) ) {
  if ( $g_dossier != 0 )  ShowMenuCompta($g_dossier,$g_UserProperty);
  //  ShowMenuComptaRight($g_dossier,$g_UserProperty);
  } else {
  // ShowMenuComptaRight(0,$g_UserProperty);
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
      $Res=ExecSql($Cn,"update ac_users set use_pass='$l_pass' where use_login='$g_user'");
      $pass=$pass_1;
      $g_UserProperty['g_pass']=$pass_1;
      $g_pass=$pass_1;
    }
  }
// Met a jour le theme utilisateur (style)
if ( isset ( $_POST['style_user']) ) {
  $CnRepo=DbConnect();
      $Res=ExecSql($CnRepo,
		   "update ac_users set use_theme='".$_POST['style_user'].
		   "'  where use_login='$g_user'");
 //     echo '<H2 class="info"> Theme utilisateur changé </H1>';
      $g_UserProperty['use_theme']=$_POST['style_user'];

}
// Met à jour le profil
// if ( isset ( $_POST['profile_user']) ) {
//   $CnRepo=DbConnect();
//       $Res=ExecSql($CnRepo,
// 		   "update ac_users set use_usertype='".$_POST['profile_user'].
// 		   "'  where use_login='$g_user'");
//       $g_UserProperty['use_usertype']=$_POST['profile_user'];

// }

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
  if ( $st == $g_UserProperty['use_theme'] ) {
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
// // charge tous les profiles
//    $aprofile=array("compta","user");
// $disp_pro="<SELECT NAME=\"profile_user\" >";
// foreach ($aprofile as $st){
//   if ( $st == $g_UserProperty['use_usertype'] ) {
//     $disp_pro.='<OPTION VALUE="'.$st.'" SELECTED>'.$st;
//   } else {
//     $disp_pro.='<OPTION VALUE="'.$st.'">'.$st;
//   }
// }
// $disp_pro.="</SELECT>";
?>
<!-- <H2 class="info">Profile</H2>
<FORM ACTION="user_pref.php" METHOD="post">
<TABLE ALIGN="center">
<TR>
   <TD> Style </TD>
   <TD> <? print $disp_pro;?> </TD>
</TR>
<TR>
   <td colspan=2> <INPUT TYPE="submit" Value="Sauve"></TD>
</TR>
</TABLE>
</FORM>
-->
<?

// Si utilise un dossier alors propose de changer
// la periode par defaut
if ( isset ($g_dossier) ) {

  include_once("preference.php");
  $cn=DbConnect($g_dossier);

  if ( isset ($_POST["sub_periode"] ) ) {
    $periode=$_POST["periode"];
    SetUserPeriode($cn,$periode,$g_user); 
  }

  $l_user_per=GetUserPeriode($cn,$g_user);
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
