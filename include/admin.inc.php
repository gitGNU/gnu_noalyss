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
require_once("class_admin.php");
$sub_action=(isset($_REQUEST['sa']))?$_REQUEST['sa']:"";
var_dump($_REQUEST);
/*! \file
 * \brief Called from the module "Gestion" to manage the customer
 */

?>

<?
// Menu
// Remove a card
if ( isset ($_POST['delete']) ) 
{
  echo 'delete';
  $f_id=$_REQUEST['f_id'];

  $fiche=new Admin($cn,$f_id);
  $fiche->remove();
  $sub_action="list";
}
////////////////////////////////////////////////////////////////////////////////
// Add card
if ( $sub_action=="insert" )
{
  $retour=sprintf('<A class="two" HREF="%s"><input type="button" value="Retour"></A>',
		  urldecode($_REQUEST['url']));

  $admin=new Admin($cn);
  $admin->Save($_REQUEST['fd_id']);
  echo $retour;
  echo "<table>";
  echo $admin->Display(true);
  echo "</table>";
  echo $retour;

}
////////////////////////////////////////////////////////////////////////////////
// Save modification
if ( isset ($_POST['mod'])) 
{
  // modification is asked
  $f_id=$_REQUEST['f_id'];

  $sup=new Admin($cn,$f_id);
  $sup->Save();
}
// by default open liste
if ( $sub_action  == "" ) 
      $sub_action="list";
////////////////////////////////////////////////////////////////////////////////
//Display a blank card 
if ( $sub_action=="blank") 
{
  $retour=sprintf('<A class="two" HREF="%s"><input type="button" value="Retour"></A>',
		  "commercial.php?p_action=admin");
  echo '<div class="u_redcontent">';

  echo $retour;
  $c=new Admin($cn);
  echo '<form method="post" action="commercial.php"';
  echo '<input type="hidden" name="p_action" value="admin">';
  echo '<input type="hidden" name="sa" value="insert">';
  echo '<input type="hidden" name="fd_id" value="'.$_GET['fd_id'].'">';
  echo '<input type="hidden" name="url" value="'.$_GET['url'].'">';
  echo $c->blank($_GET['fd_id']);
  echo '<input type="Submit" value="Sauve">';
  echo '</form>';
  echo $retour;
  echo '</div>';
}
////////////////////////////////////////////////////////////////////////////////
// list
if ( $sub_action == "list" )
{
?>
<div class="u_content">
<span>
<form method="get" action="commercial.php">
<?
   $a=(isset($_GET['query']))?$_GET['query']:"";
   printf ('<input type="text" name="query" value="%s">',
	   $a);
?>
<input type="submit" name="submit_query" value="recherche">
<input type="hidden" name="p_action" value="admin">
</form>
</span>
<span>
<form method="get" action="commercial.php">
<input type="hidden" name="url" <?      $url=urlencode($_SERVER['REQUEST_URI']);echo 'value="'.$url.'"'; ?>
<input type="hidden" name="p_action" value="admin">

<?
 $w=new widget("select");
 $w->name="fd_id";
 $w->value= make_array($cn,"select fd_id,fd_label from fiche_def where ".
	     " frd_id=".FICHE_TYPE_ADM_TAX);
 echo $w->IOValue();
?>
<input type="hidden" name="sa" value="blank">
<input type="submit" name="submit_query" value="Ajout Sup">

</form>
</span>
<?
   $sup=new Admin($cn);
 $search=(isset($_GET['query']))?$_GET['query']:"";
 // echo '<div style="position:absolute;left:15%;width:67%;margin-top:20px;">';
 echo '<div class="u_redcontent">';
 echo $sup->Summary($search);
 echo '</div>';
 echo '<div style="position:absolute;right:1%;width:10%;color:blue;background-color:lightgrey;margin-top:10px;">';
 echo "<strong>Action :</strong> Ajout, mise à jour ou effacement";
 echo " <ul>";
 echo "<li>C  - Contact</li>";
 echo "<li>FC - Courrier</li>";
 echo "</ul>";

 echo '</div>';
 echo '</div>';

}
////////////////////////////////////////////////////////////////////////////////
// Show Detail
if ( $sub_action == 'detail' )
{
  $f_id=$_REQUEST['f_id'];
  echo '<div class="u_redcontent">';
  $sup=new Admin($cn,$f_id);
  $retour=sprintf('<A class="two" HREF="%s"><input type="button" value="Retour"></A>',
		  urldecode($_REQUEST['url']));
  echo $retour;
  echo '<form action="'.$_REQUEST['url'].'" method="post">'; 
  echo $sup->Display(false);
  $w=new widget("hidden");
  $w->name="p_action";
  $w->value="admin";
  echo $w->IOValue();
  $w->name="f_id";
  $w->value=$f_id;
  echo $w->IOValue();

  echo $w->Submit('mod','Sauver les modifications');
  echo $w->Reset("Annuler");
  echo $w->Submit('delete','Effacer cette fiche');
  echo '</form>';
  echo $retour;
  echo '<div>';
}
html_page_stop();
?>
