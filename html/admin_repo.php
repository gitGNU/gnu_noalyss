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

include_once("ac_common.php");
include_once("postgres.php");
include_once("debug.php");
html_page_start();
echo_debug("entering admin_repo");
echo_debug("user $user");

CheckUser();
if (CheckAdmin() != 1) {
  html_page_stop();
  return;
}
include ("top_menu_compta.php");
//ShowMenu(0,"<A class=\"mtitle\" HREF=\"login.php\"> Dossier </A>");
if ( ! isset ($g_dossier) ) $g_dossier=0;
ShowMenuAdminGlobalRight($g_dossier);
?>
<TABLE>
<TR>
 <TD> User Management </TD> 
</TR>
<TR> <table border=2>
<?
// check and add an user (see form below)
if ( isset ($_POST["LOGIN"]) ) {
  $cn=DbConnect();
  $pass5=md5($PASS);
  $Res=ExecSql($cn,"insert into ac_users(use_first_name,use_name,use_login,use_active,use_pass)
                    values ('".$_POST["FNAME"]."','".$_POST["LNAME"]."','".$_POST["LOGIN"]."',1,'$pass5')");
}
// check and add an new folder
if ( isset ($_POST["DATABASE"]) ) {
  $cn=DbConnect();
  $dos=trim($_POST["DATABASE"]);
  $desc=FormatString($_POST["DESCRIPTION"]);
  $Res=ExecSql($cn,"insert into ac_dossier(dos_name,dos_description)
                    values ('".$dos."','$desc')");
  // If the id is not null, name successfully inserted
  // Database created
  $l_id=GetDbId($dos);
  if ( $l_id != 0) {
    $Sql=sprintf("CREATE DATABASE DOSSIER%d encoding='ISO8859-1' TEMPLATE templ_account",$l_id);
    echo_debug($Sql);
    ExecSql($cn,$Sql);
  }
}

// Show all the existing user on 7 columns
$cn=GetAllUser();
echo_debug("Array = $cn");
$compteur=0;
if ( $cn != null ) {
  foreach ( $cn as $rUser) {
    $compteur++;
    if ( $compteur==0 ) echo "<TR>";
    if ( $compteur%3 == 0)     echo "</TR><TR>";
    if ( $rUser['use_active'] == 0 ) {
      $Active="not actif";
    } else {
      $Active="";
    }
    printf('<TD><A HREF=priv_user.php?UID=%s> %s %s ( %s )</A> %s </TD>',
	   $rUser['use_id'],
	   $rUser['use_first_name'],
	   $rUser['use_name'],
	   $rUser['use_login'],
	   $Active);
  }
  echo "</TR>";
}
?>
<TR COLSPAN=7> <TABLE> <TR> 
<form action="admin_repo.php" method="POST">
<TD>Add User</TD></TR>
<?
echo '<TR><TD> First Name </TD><TD><INPUT TYPE="TEXT" NAME="FNAME"></TD>';
echo '<TD> Last Name </TD><TD><INPUT TYPE="TEXT" NAME="LNAME"></TD></TR>';
echo '<TR><TD> login </TD><TD><INPUT TYPE="TEXT" NAME="LOGIN"></TD>';
echo '<TD> password </TD><TD> <INPUT TYPE="TEXT" NAME="PASS"></TD></TR>';
echo '<TD> <INPUT TYPE="SUBMIT" Value="Create user" NAME="ADD"></TD>';


?>
</FORM>
</TR></TABLE></TR>

<TABLE>
<TR><TD>
 Dossier Management</TD>
</TR>
<TR>
<TABLE BORDER=1>
<?
$cn=DbConnect();
$Res=ShowDossier('all');
$compteur=1;
if ( $Res != null ) {
  echo "<TR>";
  foreach ( $Res as $Dossier) {
    
    if ( $compteur%5 == 0 ) echo "</TR><TR>";
    echo "<TD VALIGN=\"TOP\"> <B>$Dossier[dos_name]</B> <BR><I>  $Dossier[dos_description]</I></TD>";
    $compteur++;
    
  }

  echo "</TR>";
}
// Add a new folder
?>
<TR COLSPAN=2>
<TABLE>
 <FORM ACTION="admin_repo.php" METHOD="POST">
 <TD> Name  <INPUT TYPE="TEXT" NAME="DATABASE"> </TD>
 <TD> Description <INPUT TYPE="TEXT" NAME="DESCRIPTION" SIZE="30"> </TD>
 <TD> <INPUT TYPE=SUBMIT VALUE="Create Folder"></TD>
 </FORM>
 </TR>
</TABLE>
</TABLE>
</TD></TR>
</TABLE>

<?

html_page_stop();
?>
