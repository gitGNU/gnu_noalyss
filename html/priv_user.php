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
include_once("postgres.php");
include_once("debug.php");
include_once("user_menu.php");
html_page_start($_SESSION['g_theme']);

$rep=DbConnect();
include_once ("class_user.php");
$User=new User($rep);
$User->Check();
/* only the global admin can modify something here
 */
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
$UserChange=new User($rep,$uid);

echo_debug('priv_user.php',__LINE__,"UID IS DEFINED");

$r_UID=$UserChange->id;
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



<?php
/* Parse the changes */
if ( isset ( $_GET['reset_passwd']) ){
  $cn=DbConnect();
  $l_pass=md5('phpcompta');
  $Res=ExecSql($cn, "update ac_users set use_pass='$l_pass' where use_id=$uid");
  echo '<H2 class="info"> Password remis à phpcompta</H2>';
}
/*  Save the changes */
if ( isset ($_POST['SAVE']) ){
  $uid = $_POST['UID'];

  // Update User 
  $cn=DbConnect();
  $last_name=pg_escape_string($_POST['fname']);
  $first_name=pg_escape_string($_POST['lname']);

  $Sql="update ac_users set use_first_name='".$first_name."', use_name='".$last_name."'
        ,use_active=".$_POST['Actif'].",use_admin=".$_POST['Admin']." where
         use_id=".$uid;
  $Res=ExecSql($cn,$Sql);
  // Update Priv on Folder
  foreach ($_POST as $name=>$elem)
    { 
      echo_debug('priv_user.php',__LINE__,"_POST $name $elem");
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
  if ( isset ($_POST["DELETE"]) ) {
    $cn=DbConnect();
    $Res=ExecSql($cn,"delete from priv_user where priv_jnt in ( select jnt_id from jnt_use_dos where use_id=".$uid.")");
    $Res=ExecSql($cn,"delete from jnt_use_dos where use_id=".$uid);
    $Res=ExecSql($cn,"delete from ac_users where use_id=".$uid);

    echo "<center><H2 class=\"info\"> User ".$_POST['fname']." ".$_POST['lname']." (".
      $_POST['login'].") is deleted </H2></CENTER>";
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
	     array('value'=>'L','label'=>'Administrateur local(Tous les droits)')
	     );

$Dossier=ShowDossier('all',1,0);
if (  empty ( $Dossier )) {
	echo '* Aucun Dossier *';
	echo '</div>';
	exit();
}
$mod_user=new User(DbConnect(),$uid);
foreach ( $Dossier as $rDossier) {

  $priv=$mod_user->get_privilege($rDossier['dos_id']);
  printf("<TR><TD> Dossier : %s </TD>",$rDossier['dos_name']);
  
  $select=new widget('select');
  $select->table=1;
  $select->name=sprintf('PRIV%s',$rDossier['dos_id']);
  $select->value=$array;
  $select->selected=$priv;
  echo $select->IOValue();
  echo "</TD></TR>";
}

?>
</TABLE>

<?php echo widget::button_href('Reinitialiser le mot de passe',
			  sprintf('priv_user.php?reset_passwd&UID=%s',$uid)); 
?>



<input type="Submit" NAME="SAVE" VALUE="Sauver les changements changes">

<input type="Submit" NAME="DELETE" VALUE="Effacer" onclick="return confirm('Confirmer effacement ?');" >

</FORM>
<A href='admin_repo.php?action=user_mgt'>Retour</a>
</DIV>










<?php
html_page_stop();
?>


