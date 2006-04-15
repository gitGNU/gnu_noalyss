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
include_once("ac_common.php");
include_once("postgres.php");
include_once("debug.php");
include_once("user_menu.php");
html_page_start($_SESSION['g_theme']);
echo_debug('priv_user.php',__LINE__,"entering priv_users");


$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();

if ($User->admin != 1) {
  html_page_stop();
  return;
}

if (! isset ($_GET['UID']) && ! isset($_POST['UID']) ) {
  //Message d'erreur si UID non positionné
  echo_debug('priv_user.php',__LINE__,"UID NOT DEFINED");
  html_page_stop();
  return;
}
$uid=( isset ($_GET['UID']))? $_GET['UID']: $_POST['UID'];
echo_debug('priv_user.php',__LINE__,"UID IS DEFINED");

$r_UID=GetUid($uid);
if ( $r_UID == false ) {
  echo_debug('priv_user.php',__LINE__,"UID NOT VALID");
  // Message d'erreur
  html_page_stop();
  return;
}
echo_debug('priv_user.php',__LINE__,"UID IS VALID");
echo '<H2 class="info"> Administration Globale</H2>';

echo "<div>".MenuAdmin()."</div>";

echo '<DIV>';

echo '<h2>Gestion Utilisateurs</h2>';

// User is valid and you're an admin 


?>



<?
/* Parse the changes */
if ( isset ( $_GET['reset_passwd']) ){
  $cn=DbConnect();
  $l_pass=md5('phpcompta');
  $Res=ExecSql($cn, "update ac_users set use_pass='$l_pass' where use_id=$uid");
  echo '<H2 class="info"> Password remis à phpcompta</H2>';
}
if ( isset ($_POST['SAVE']) ){
  $uid = $_POST['UID'];
  echo_debug('priv_user.php',__LINE__,"SAVE is set");
  // Update User 
  $cn=DbConnect();
  $Sql="update ac_users set use_first_name='".$_POST['fname']."', use_name='".$_POST['lname']."'
        ,use_login='".$_POST['login']."',use_active=".$_POST['Actif'].",use_admin=".$_POST['Admin']." where
         use_id=".$uid;
  $Res=ExecSql($cn,$Sql);
  // Update Priv on Folder
  foreach ($HTTP_POST_VARS as $name=>$elem)
    { 
      echo_debug('priv_user.php',__LINE__,"HTTP_POST_VARS $name $elem");
      if ( substr_count($name,'PRIV')!=0 )
      {
	echo_debug('priv_user.php',__LINE__,"Found a priv");
	$db_id=substr($name,4);
	$cn=DbConnect();
	if ( ExisteJnt($db_id,$uid) != 1 ) 
	  {
	  $Res=ExecSql($cn,"insert into jnt_use_dos(dos_id,use_id) values(".$db_id.",".$uid.")"); 
	  }
	$jnt=GetJnt($db_id,$uid);
	if (ExistePriv($jnt) > 0) 
	  {
	  $Res=ExecSql($cn,"update priv_user set priv_priv='".$elem."' where priv_jnt=".$jnt);
	  } else {
	   $Res=ExecSql($cn,"insert into  priv_user(priv_jnt,priv_priv) values (".$jnt.",'".$elem."')");
	  }
	
      }

    }
} else {
  if ( isset ($DELETE) ) {
    $cn=DbConnect();
    $Res=ExecSql($cn,"delete from priv_user where priv_jnt in ( select jnt_id from jnt_use_dos where use_id=".$uid.")");
    $Res=ExecSql($cn,"delete from jnt_use_dos where use_id=".$uid);
    $Res=ExecSql($cn,"delete from ac_users where use_id=".$uid);

    echo "<center><H2 class=\"info\"> User $fname $lname ($login) is deleted </H2></CENTER>";
    html_page_stop();
    return;
  }
}
$r_UID=GetUid($uid);
?>
<FORM ACTION="priv_user.php" METHOD="POST">

<? printf('<INPUT TYPE=HIDDEN NAME=UID VALUE="%s">',$uid); ?>
<TABLE BORDER=0>
<TR><TD>
<?printf('First name <INPUT type="text" NAME="fname" value="%s"> ',$r_UID[0]['use_first_name']); ?>
</TD><TD>
<?printf('Name <INPUT type="text" NAME="lname" value="%s"> ',$r_UID[0]['use_name']); ?>
</TD>
</TR><TR>
<TD>
<?printf('login <INPUT type="text" NAME="login" value="%s">',$r_UID[0]['use_login']); ?>
</TD>
<TD class="mtitle"> 
<?printf('<A class="mtitle" HREF="priv_user.php?reset_passwd&UID=%s">Reset Password</A>',$uid); ?>
</TD>
</TR>
<TR><TD>
<TABLE>
<?
if ( $r_UID[0]['use_active'] == 1 ) {
  $ACT="CHECKED";$NACT="UNCHECKED";
} else {
  $ACT="UNCHECKED";$NACT="CHECKED";
}
echo "<TR><TD>";
printf('<INPUT type="RADIO" NAME="Actif" VALUE="1" %s> Actif',$ACT); 
echo "</TD><TD>";
printf('<INPUT type="RADIO" NAME="Actif" VALUE="0" %s> Non Actif',$NACT); 
echo "</TD></TR>";
?>
</TABLE>
</TD>
<TD>
<TABLE>
<?
if ( $r_UID[0]['use_admin'] == 1 ) {
  $ACT="CHECKED";$NACT="UNCHECKED";
} else {
  $ACT="UNCHECKED";$NACT="CHECKED";
}
echo "<TR><TD>";
printf('<INPUT type="RADIO" NAME="Admin" VALUE="1" %s> Administrator',$ACT); 
echo "</TD><TD>";
printf('<INPUT type="RADIO" NAME="Admin" VALUE="0" %s> Normal user',$NACT); 
echo "</TD></TR>";
?>
</TABLE>
</TD>
</TR>
<TR>
<TD>
<!-- Show all database and rights -->
<H2 class="info"> Droit par défaut </H2>
<TABLE>
<? 
$Dossier=ShowDossier('all',1,0);
foreach ( $Dossier as $rDossier) {
  $NORIGHT="";$Write="";$Read="";
  echo_debug('priv_user.php',__LINE__,"Dossier : ".$rDossier['dos_id']);
  $login_name=GetLogin($uid);
  $priv=GetPriv($rDossier['dos_id'],$login_name);
  printf("<TR><TD> Dossier : %s </TD>",$rDossier['dos_name']);
  if ( $priv==0 ) 
    { $NORIGHT="CHECKED";} 
  else { 
    $A=$priv[0]['priv_priv'];
    echo_debug('priv_user.php',__LINE__,"Priv = $A");
    if ( $priv[0]['priv_priv']=='W' ) 
      {$Write="CHECKED";}
    else {
      if ( $priv[0]['priv_priv']=='R' ) 
	{ $Read="CHECKED";}
      else {
	if ($priv[0]['priv_priv']=='N') {
	$NORIGHT="CHECKED";
      }
    }
    }

  }

  printf('</TD><TD>No Right<INPUT TYPE="RADIO" NAME="PRIV%s" VALUE="NO" %s>',$rDossier['dos_id'],$NORIGHT);
  printf('</TD><TD>Read/Write <INPUT TYPE="RADIO" NAME="PRIV%s" VALUE="W" %s>',$rDossier['dos_id'],$Write);
  printf('</TD><TD>Read<INPUT TYPE="RADIO" NAME="PRIV%s" VALUE="R" %s>',$rDossier['dos_id'],$Read);
  echo "</TD></TR>";
}

?>
</TABLE>
</TD>
</TR>



<TR><TD><input type="Submit" NAME="SAVE" VALUE="Save changes"></TD>
<TD><input type="RESET" NAME="Reset" VALUE="Cancel Change"></TD>
<TD><input type="Submit" NAME="DELETE" VALUE="Delete User"></TD>
</TR>
</FORM>
</TABLE>
</DIV>










<?
html_page_stop();
?>


