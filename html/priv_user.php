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
include_once("top_menu_compta.php");
html_page_start($g_UserProperty['use_theme']);
echo_debug("entering priv_users");


CheckUser();
if ($g_UserProperty['use_admin'] != 1) {
  html_page_stop();
  return;
}
if (! isset ($UID) ) {
  //Message d'erreur si UID non positionné
  echo_debug("UID NOT DEFINED");
  html_page_stop();
  return;
}
echo_debug("UID IS DEFINED");

$r_UID=GetUid($UID);
if ( $r_UID == false ) {
  echo_debug("UID NOT VALID");
  // Message d'erreur
  html_page_stop();
  return;
}
echo_debug("UID IS VALID");
echo '<H2 style="color:blue;text-align:center"> Administration Globale</H2>';
ShowMenuAdminGlobal();
ShowMenuAdminGlobalRight('<A class="mtitle" HREF="admin_repo.php">Retour</A>');
echo '<DIV CLASS="redcontent">';
echo '<H1 class="title"> User Management</H1>';
// User is valid and you're an admin 


?>



<?
/* Parse the changes */
if ( isset ( $reset_passwd) ){
  $cn=DbConnect();
  $l_pass=md5('phpcompta');
  $Res=ExecSql($cn, "update ac_users set use_pass='$l_pass' where use_id=$UID");
  echo '<H2 class="info"> Password remis à phpcompta</H2>';
}
if ( isset ($SAVE) ){
  echo_debug("SAVE is set");
  // Update User 
  $cn=DbConnect();
  $Sql="update ac_users set use_first_name='".$fname."', use_name='".$lname."'
        ,use_login='".$login."',use_active=".$Actif.",use_admin=".$Admin." where
         use_id=".$UID;
  $Res=ExecSql($cn,$Sql);
  // Update Priv on Folder
  foreach ($HTTP_POST_VARS as $name=>$elem)
    { 
      echo_debug("HTTP_POST_VARS $name $elem");
      if ( substr_count($name,'PRIV')!=0 )
      {
	echo_debug("Found a priv");
	$db_id=substr($name,4);
	$cn=DbConnect();
	if ( ExisteJnt($db_id,$UID) != 1 ) 
	  {
	  $Res=ExecSql($cn,"insert into jnt_use_dos(dos_id,use_id) values(".$db_id.",".$UID.")"); 
	  }
	$jnt=GetJnt($db_id,$UID);
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
    $Res=ExecSql($cn,"delete from priv_user where priv_jnt in ( select jnt_id from jnt_use_dos where use_id=".$UID.")");
    $Res=ExecSql($cn,"delete from jnt_use_dos where use_id=".$UID);
    $Res=ExecSql($cn,"delete from ac_users where use_id=".$UID);

    echo "<center><H2 class=\"info\"> User $fname $lname ($login) is deleted </H2></CENTER>";
    html_page_stop();
    return;
  }
}
$r_UID=GetUid($UID);
?>
<FORM ACTION="priv_user.php" METHOD="POST">

<? printf('<INPUT TYPE=HIDDEN NAME=UID VALUE="%s">',$UID); ?>
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
<?printf('<A class="mtitle" HREF="priv_user.php?reset_passwd&UID=%s">Reset Password</A>',$UID); ?>
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
  echo_debug("Dossier : ".$rDossier['dos_id']);
  $login_name=GetLogin($UID);
  $priv=GetPriv($rDossier['dos_id'],$login_name);
  printf("<TR><TD> Dossier : %s </TD>",$rDossier['dos_name']);
  if ( $priv==0 ) 
    { $NORIGHT="CHECKED";} 
  else { 
    $A=$priv[0]['priv_priv'];
    echo_debug("Priv = $A");
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
<TD><input type="Submit" NAME="DELETE" VALUE="Delete Users"></TD>
</TR>
</FORM>
</TABLE>
</DIV>










<?
html_page_stop();
?>


