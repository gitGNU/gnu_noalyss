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

/*!\file 
 *
 *
 * \brief user managemnt
 *
 */

// Add user
if ( isset ($_POST["LOGIN"]) ) {
  $cn=new Database();
  $pass5=md5($_POST['PASS']);

  $first_name=Database::escape_string($_POST['FNAME']);
  $last_name=Database::escape_string($_POST['LNAME']);
  $login=$_POST['LOGIN'];
  $login=str_replace("'","",$login);
  $login=str_replace('"',"",$login);
  $login=str_replace(" ","",$login);
  $login=strtolower($login);

  $Res=$cn->exec_sql("insert into ac_users(use_first_name,use_name,use_login,use_active,use_pass)
                    values ($1,$2,$3,1,$4)",
		     array($first_name,$last_name,$login,$pass5));
} //SET login

// Show all the existing user on 7 columns
$repo=new Dossier(0);
$cn=$repo->get_user();
$compteur=0;
?>
<h2>Gestion Utilisateurs</h2>
<TABLE><TR>
<?php  
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
      }// foreach
    } // $cn != null
?>
</TABLE>
<TABLE> <TR> 
<form action="admin_repo.php?action=user_mgt" method="POST">
<TD><H3>
<?php  
  echo _("Ajout d'utilisateur");
echo '<H3></TD></TR>';
echo '<TR><TD> First Name </TD><TD><INPUT TYPE="TEXT" NAME="FNAME"></TD>';
echo '<TD> Last Name </TD><TD><INPUT TYPE="TEXT" NAME="LNAME"></TD></TR>';
echo '<TR><TD> login </TD><TD><INPUT TYPE="TEXT" NAME="LOGIN"></TD>';
echo '<TD> password </TD><TD> <INPUT TYPE="TEXT" NAME="PASS"></TD></TR>';
echo '<TD> <INPUT TYPE="SUBMIT" class="button" Value="Create user" NAME="ADD"></TD>';
echo '</TABLE>';

?>
</FORM>

