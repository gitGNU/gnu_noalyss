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
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/*! \file
 * \brief Users Security
 */
include_once("ac_common.php");
require_once('class_database.php');
include_once("user_menu.php");
html_page_start($_SESSION['g_theme']);

$rep=new Database();
include_once ("class_user.php");
$User=new User($rep);
$User->Check();
/* only the global admin can modify something here
 */
if ($User->admin != 1) {
  html_page_stop();
  return;
}

if (! isset ($_REQUEST['UID'])  ) {
   html_page_stop();
  exit();
}
$uid=$_REQUEST['UID'];
$UserChange=new User($rep,$uid);

$r_UID=$UserChange->id;
if ( $r_UID == false ) {
  // Message d'erreur
  html_page_stop();
}
echo '<H2 class="info"> Administration Globale</H2>';

echo "<div>".MenuAdmin()."</div>";

echo '<DIV>';

echo '<h2>Gestion Utilisateurs</h2>';

// User is valid and you're an admin


?>



<?php
/* Parse the changes */
if ( isset ( $_GET['reset_passwd']) ){
  $cn=new Database();
  $l_pass=md5('phpcompta');
  $Res=$cn->exec_sql( "update ac_users set use_pass='$l_pass' where use_id=$uid");
  echo '<H2 class="info"> Password remis à phpcompta</H2>';
}
/*  Save the changes */
if ( isset ($_POST['SAVE']) ){
  $uid = $_POST['UID'];

  // Update User
  $cn=new Database();
  $last_name=$_POST['fname'];
  $first_name=$_POST['lname'];
  $UserChange=new User($cn,$uid);
  if ( $UserChange->load()==-1) {
	alert("Cet utilisateur n'existe pas");}
  else {
	  $UserChange->first_name=$first_name;
	  $UserChange->last_name=$last_name;
	  $UserChange->active=$_POST['Actif'];
	  $UserChange->admin=$_POST['Admin'];
	  $UserChange->save();

	  // Update Priv on Folder
	  foreach ($_POST as $name=>$elem)
	    {
	      if ( substr_count($name,'PRIV')!=0 )
	      {
			$db_id=substr($name,4);
			$cn=new Database();
			$UserChange->set_folder_access($db_id,$elem);

	     }

	    }
	}
} else {
  if ( isset ($_POST["DELETE"]) ) {
    $cn=new Database();
    $Res=$cn->exec_sql("delete from priv_user where priv_jnt in ( select jnt_id from jnt_use_dos where use_id=$1",array($uid));
    $Res=$cn->exec_sql("delete from jnt_use_dos where use_id=$1",array($uid));
    $Res=$cn->exec_sql("delete from ac_users where use_id=$1",array($uid));

    echo "<center><H2 class=\"info\"> User ".h($_POST['fname'])." ".h($_POST['lname'])." (".
      h($_POST['login']).") est effacé</H2></CENTER>";
require_once("class_iselect.php");
    require_once("user.inc.php");
    return;
  }
}
$UserChange->load();
?>
<FORM ACTION="priv_user.php" METHOD="POST">

<?php printf('<INPUT TYPE=HIDDEN NAME=UID VALUE="%s">',$uid); ?>
<TABLE BORDER=0>
<TR>

<?php printf('<td>login</td><td> %s</td>',$UserChange->login); ?>
</TD></tr>
<TR><TD>
<?php printf('Nom de famille </TD><td><INPUT type="text" NAME="fname" value="%s"> ',$UserChange->name); ?>
</TD></TR>
<?php printf('<td>prénom</td><td>
<INPUT type="text" NAME="lname" value="%s"> ',$UserChange->first_name); ?>
</TD>
</TR>
</table>

<TABLE>
<?php
if ( $UserChange->active == 1 ) {
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
<?php
if ( $UserChange->admin == 1 ) {
  $ACT="CHECKED";$NACT="UNCHECKED";
} else {
  $ACT="UNCHECKED";$NACT="CHECKED";
}
echo "<TR><TD>";
printf('<INPUT type="RADIO" NAME="Admin" VALUE="1" %s> Administrateur global',$ACT);
echo "</TD><TD>";
printf('<INPUT type="RADIO" NAME="Admin" VALUE="0" %s> Pas administrateur global ',$NACT);
echo "</TD></TR>";
?>
</TABLE>
</TD>
</TR>
<TR>
<TD>
<!-- Show all database and rights -->
<H2 class="info"> Droit sur les dossiers pour les utilisateurs normaux </H2>
<p class="notice">
Les autres droits doivent être réglés dans les dossiers (paramètre->sécurité)
 </p>
<TABLE>
<?php
$array=array(
	     array('value'=>'X','label'=>'Aucun Accès'),
	     array('value'=>'R','label'=>'Utilisateur normal'),
	      array('value'=>'P','label'=>'Utilisateur uniquement extension'),
	     array('value'=>'L','label'=>'Administrateur local(Tous les droits)')
	     );
$repo=new Dossier(0);

$Dossier=$repo->show_dossier('all',1,0);
if (  empty ( $Dossier )) {
	echo hb('* Aucun Dossier *');
	echo '</div>';
	exit();
}

$mod_user=new User(new Database(),$uid);
foreach ( $Dossier as $rDossier) {

  $priv=$mod_user->get_folder_access($rDossier['dos_id']);
  printf("<TR><TD> Dossier : %s </TD>",h($rDossier['dos_name']));

  $select=new ISelect();
  $select->table=1;
  $select->name=sprintf('PRIV%s',$rDossier['dos_id']);
  $select->value=$array;
  $select->selected=$priv;
  echo $select->input();
  echo "</TD></TR>";
}

?>
</TABLE>

<?php echo HtmlInput::button_anchor('Reinitialiser le mot de passe',
			  sprintf('priv_user.php?reset_passwd&UID=%s',$uid));
?>



<input type="Submit" class="button" NAME="SAVE" VALUE="Sauver les changements changes">

<input type="Submit"  class="button" NAME="DELETE" VALUE="Effacer" onclick="return confirm('Confirmer effacement ?');" >

</FORM>
<A href='admin_repo.php?action=user_mgt'>Retour</a>
</DIV>










<?php
html_page_stop();
?>


