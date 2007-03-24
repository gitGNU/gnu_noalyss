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
/* $Revision$ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/* !\file 
 */

/* \brief confirm the removal of a template , folder and remove it if confirmed
 *
 */
include_once("postgres.php");
include_once("debug.php");
include_once("ac_common.php");

$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
html_page_start($User->theme,'onLoad="window.focus();"');
$User->Check();
if ($User->admin != 1) {

print "<H2 class=\"error\"> Désolé mais vous n' êtes pas administrateur</H2>";

  html_page_stop(); 
  return; 
} 
if ( ! isset ($_REQUEST['p_type'] ) ||
     ! isset ($_REQUEST['PHPSESSID']) ||
     ! isset ($_REQUEST['ob_id']))
{
  print '<H2 CLASS="error">';
  print "Désolé vous n'avez pas appelé cette fonction avec les bons paramètres";
  print '</H2>';
  print '<INPUT TYPE="BUTTON" VALUE="Fermez la fenêtre" onclick="window.close();">';
  return;
}
$cn=DbConnect();

switch($_REQUEST['p_type']) 
{
 case 'db':
   $msg="dossier";
   $name=getDbValue($cn,"select dos_name from ac_dossier where dos_id=".$_REQUEST['ob_id']);
   if ( strlen(trim($name)) == 0 )
     {
       echo "<h2 class=\"error\"> $msg inexistant</h2>";
       return;
     }

   break;
 case 'mod':
   $msg="modèle";
   $name=getDbValue($cn,"select mod_name from modeledef where mod_id=".$_REQUEST['ob_id']);
   if ( strlen(trim($name)) == 0 )
     {
       echo "<h2 class=\"error\"> $msg inexistant</h2>";
       return;
     }
   if ( $_REQUEST['ob_id'] < 3 ) {
     echo "<h2 class=\"error\">Désolé mais vous ne pouvez pas effacer les modèles de base</H2>";
     return;
   }
   break;
 default:
  print '<H2 CLASS="error">';
  print "Désolé mais que voulez-vous effacer ? ";
  print '</H2>';
  return;

}

if  ( isset($_POST['remove']) ) 
{
  // Check if the radio is checked
  //  if yes removed the template and show a confirmation message
  if ( isset($_POST['confirm']) ) 
    {
      switch( $_POST['p_type'] ) 
	{
	  // Drop Modele
	case 'mod':
	  $sql="drop database ".domaine."mod".$_POST['ob_id'];
	  ob_start();
	  if ( pg_query($cn,$sql)==false) {
		ob_clean();

		echo "<h2 class=\"error\"> 
                     Base de donnée ".domaine."mod".$_POST['ob_id']."  est accèdée, déconnectez-vous d'abord</h2>";
		exit;
	  }
	  ob_flush();
	  $sql="delete from modeledef where mod_id=".$_POST['ob_id'];
	  ExecSql($cn,$sql);
	  print '<h2 class="info">';
	  print "Voilà le modèle $name est effacé";
	  print "<h2>";
	  break;
	case 'db':
	  $sql="drop database ".domaine."dossier".$_POST['ob_id'];
	  ob_start();
	  if ( pg_query($cn,$sql)==false) {
		ob_clean();
		
		echo "<h2 class=\"error\"> 
                     Base de donnée ".domaine."mod".$_POST['ob_id']."  est accèdée, déconnectez-vous d'abord</h2>";
		exit;
	  }
	  ob_flush();
	  $sql="delete from priv_user where priv_id in (select jnt_id from jnt_use_dos where dos_id=".$_REQUEST['ob_id'].")";
	  ExecSql($cn,$sql);
	  $sql="delete from  jnt_use_dos where dos_id=".$_REQUEST['ob_id'];
	  ExecSql($cn,$sql);
	  $sql="delete from ac_dossier where dos_id=".$_REQUEST['ob_id'];
	  ExecSql($cn,$sql);
	  print '<h2 class="info">';
	  print "Voilà le modèle $name est effacé";
	  print "<h2>";

	}
    } 
  else 
    {
      print '<h2 class="info">';
      print "$msg $name n'est pas effacé";
      print '</h2>';
      print "<hr>";
      print "Vous n'avez pas coché la case";

    }
} 
else 
{
  print "<h2 class=\"info\"> Confirmer vous l'effacement du $msg $name ? <h2> ";
?>
<FORM METHOD="POST" ACTION="confirm_remove.php">
   <INPUT TYPE="HIDDEN" NAME="ob_id" value="<?echo $_REQUEST['ob_id'];?>"  >
   <INPUT TYPE="HIDDEN" NAME="p_type" value="<?echo $_REQUEST['p_type'];?>" >
<INPUT TYPE="CHECKBOX" NAME="confirm" VALUE="UNCHECKED"> Cochez cette case si vous voulez vraiment effacer <?echo $name;?>
   <INPUT TYPE="SUBMIT" NAME="remove" value="Oui">
      <input type="button" name="close" value="Annuler" onclick="window.close();">
</FORM>
<?
}