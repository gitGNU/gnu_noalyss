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
/* $Revision$ */
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/*!\brief this file is always included and then executed
 *        it permits to change the user preferences
 */

echo '<DIV class="u_content">';

if ( isset ($_POST['spass']) ) {
  if ( $_POST['pass_1'] != $_POST['pass_2'] ) {
?>
<script>
   alert("Les mots de passe ne correspondent pas. Mot de passe inchang&eacute;");
</script>
<?php  
    }
    else {
      $l_pass=md5($_POST['pass_1']);
      $Rep=DbConnect();
      $Res=ExecSql($Rep,"update ac_users set use_pass='$l_pass' where use_login='".$_SESSION['g_user']."'");
      $pass=$_POST['pass_1'];
      $_SESSION['g_pass']=$_POST['pass_1'];
      $g_pass=$_POST['pass_1'];
      echo "<span><i>Mot de passe est modifi&eacute;</i></span>";
    }
  }
$url=$_SERVER['REQUEST_URI'];
if ( ! isset ($_SESSION['g_dossier']) ) 
  {
    echo '<A class="mtitle" href="user_login.php"><input type="button" value="Retour"></a>';
  }

?>
<H2 CLASS="info"> Mot de passe</H2>

<FORM ACTION="<?php  echo $url;?>" METHOD="POST">
<TABLE ALIGN="CENTER">
<TR><TD><input type="password" name="pass_1"></TD></TR>
<TR><TD><input type="password" name="pass_2"></TD></TR>
<TR><TD><input type="submit" name="spass" value="Change mot de passe"></TD></TR>
</TABLE>
</FORM>
<?php  
$Rep=DbConnect();

// charge tous les styles
$res=ExecSql($Rep,"select the_name from theme
                      order by the_name");
for ($i=0;$i < pg_NumRows($res);$i++){
  $st=pg_fetch_array($res,$i);
  $style[]=$st['the_name'];
}
// Formatte le display
$disp_style="<SELECT NAME=\"style_user\" >";
foreach ($style as $st){
  if ( $st == $_SESSION['g_theme'] ) {
    $disp_style.='<OPTION VALUE="'.$st.'" SELECTED>'.$st;
  } else {
    $disp_style.='<OPTION VALUE="'.$st.'">'.$st;
  }
}
$disp_style.="</SELECT>";
?>
<H2 class="info">Th&egrave;me</H2>
<FORM ACTION="<?php   echo $url; ?>" METHOD="post">
<TABLE ALIGN="center">
<TR>
   <TD> Style </TD>
   <TD> <?php   print $disp_style;?> </TD>
</TR>
<TR>
   <td colspan=2> <INPUT TYPE="submit" Value="Sauve"></TD>
</TR>
</TABLE>
</FORM>

<?php  

// Si utilise un dossier alors propose de changer
// la periode par defaut
if ( ! isset ($_SESSION['g_dossier']) ) 
  {
    echo '<A class="mtitle" href="user_login.php"><input type="button" value="Retour"></a>';
  }
else
  {

    include_once("preference.php");
    $msg=""; 
    $cn=DbConnect($_SESSION['g_dossier']);

    if ( isset ($_POST["sub_periode"] ) ) 
      {
	$periode=$_POST["periode"];
	$User->SetPeriode($periode);
	echo_debug('pref.inc',__LINE__,"Periode returns ".PeriodeClosed($cn,$periode));
      }

    $l_user_per=$User->GetPeriode();
    // if periode is closed then warns the users
    if ( PeriodeClosed($cn,$l_user_per)=='t')
      {
	$msg= '<h2 class="error">Attention cette p&eacute;riode est ferm&eacute;e, vous ne pourrez rien modifier dans le module comptable</h2>';
      }
    

    $l_form_per=FormPeriode($cn,$l_user_per,ALL);
    
    ?>
      <H2 CLASS="info"> P&eacute;riode</H2>
  <?php   echo $msg; ?>
<FORM ACTION="<?php   echo $url;?>" METHOD="POST">
<TABLE ALIGN="CENTER">
<TR><TD>PERIODE</TD>
<?php      printf('<TD> %s </TD></TR>',$l_form_per); ?>
<TR><TD><input type="submit" name="sub_periode" value="Sauve"></TD></TR>
</TABLE>
</FORM>

<H2 CLASS="info"> Taille des pages</H2>
<FORM ACTION="<?php   echo $url;?>" METHOD="POST">
<TABLE ALIGN="CENTER">
<TR><TD>Taille des pages</TD>
<TD>
<SELECT NAME="p_size">
<option value="15">15
<option value="25">25
<option value="50">50
<option value="100">100
<option value="150">150
<option value="200">200
<option value="-1">Illimit&eacute;
<?php  
	$label=($_SESSION['g_pagesize'] == -1)?'Illimit&eacute;':$_SESSION['g_pagesize'];
	echo '<option value="'.$_SESSION['g_pagesize'].'" selected>'.$label;
?>
</SELECT>
</TD></TR>
<TR><TD><input type="submit" name="sub_taille" value="Sauve"></TD></TR>
</TABLE>
</FORM>


<?php  
}
     
echo "</DIV>";
?>
