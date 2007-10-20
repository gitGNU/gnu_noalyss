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
require_once("class_customer.php");
$sub_action=(isset($_REQUEST['sa']))?$_REQUEST['sa']:"";

/*! \file
 * \brief Called from the module "Gestion" to manage the customer
 */
$User->AccessRequest($cn,CLIENT);

?>

<?php  
// Menu
// Remove a card
if ( isset ($_POST['delete']) ) 
{

  echo 'delete';
  $f_id=$_REQUEST['f_id'];

  $fiche=new Customer($cn,$f_id);
  $fiche->remove();
  $sub_action="list";
}
//-----------------------------------------------------
// Add card
if ( $sub_action=="insert" )
{

  $retour=sprintf('<A class="mtitle" HREF="%s"><input type="button" value="Retour"></A>',
		  urldecode($_REQUEST['url']));

  $customer=new Customer($cn);
  $customer->Save($_REQUEST['fd_id']);
  echo $retour;
  echo "<table>";
  echo $customer->Display(true);
  echo "</table>";
  echo $retour;

}
//-----------------------------------------------------
// Save modification
if ( isset ($_POST['mod'])) 
{

  // modification is asked
  $f_id=$_REQUEST['f_id'];

  $client=new Customer($cn,$f_id);
  $client->Save();
}
// by default open liste
if ( $sub_action  == "" ) 
      $sub_action="list";
//-----------------------------------------------------
//Display a blank card 
if ( $sub_action=="blank") 
{

  $retour=sprintf('<A class="mtitle" HREF="%s"><input type="button" value="Retour"></A>',
		  "commercial.php?p_action=client&$str_dossier");
  echo '<div class="u_content">';

  echo $retour;
  $c=new Customer($cn);
  echo '<form method="post" action="commercial.php"';
  echo dossier::hidden();
  echo '<input type="hidden" name="p_action" value="client">';
  echo '<input type="hidden" name="sa" value="insert">';
  echo '<input type="hidden" name="fd_id" value="'.$_GET['fd_id'].'">';
  echo '<input type="hidden" name="url" value="'.$_GET['url'].'">';
  echo $c->blank($_GET['fd_id']);
  echo '<input type="Submit" value="Sauve">';
  echo '</form>';
  echo $retour;
  echo '</div>';
}
//-----------------------------------------------------
// list
if ( $sub_action == "list" )
{
?>
<div class="u_content">
<span  style="position:float;float:left">
<form method="get" action="commercial.php">
<?php
	echo dossier::hidden();  
   $a=(isset($_GET['query']))?$_GET['query']:"";
   printf ('<input type="text" name="query" value="%s">',
	   $a);
?>
<input type="submit" name="submit_query" value="recherche">
<input type="hidden" name="p_action" value="client">
</form>
</span>
<span  style="position:float;float:left">
<form method="get" action="commercial.php">
   <?php echo dossier::hidden(); ?>
<input type="hidden" name="url" <?php        $url=urlencode($_SERVER['REQUEST_URI']);echo 'value="'.$url.'"'; ?>
<input type="hidden" name="p_action" value="client">

<?php  
 $w=new widget("select");
 $w->name="fd_id";
 $w->value= make_array($cn,"select fd_id,fd_label from fiche_def where ".
	     " frd_id=".FICHE_TYPE_CLIENT);
 echo $w->IOValue();
?>
<input type="hidden" name="sa" value="blank">
<input type="submit" name="submit_query" value="Ajout Client">

</form>
</span>
<?php  
   $client=new Customer($cn);
 $search=(isset($_GET['query']))?$_GET['query']:"";

 echo '<div class="u_content">';
 echo $client->Summary($search);
 echo '</div>';
 echo '</div>';

}
//-----------------------------------------------------
// Show Detail
if ( $sub_action == 'detail' )
{
  $f_id=$_REQUEST['f_id'];
  echo '<div class="u_content">';
  $client=new Customer($cn,$f_id);
  $retour=sprintf('<A class="mtitle" HREF="%s"><input type="button" value="Retour"></A>',
		  urldecode($_REQUEST['url']));
  echo $retour;
  echo '<form action="'.$_REQUEST['url'].'" method="post">'; 
  echo dossier::hidden();
  echo $client->Display(false);
  $w=new widget("hidden");
  $w->name="p_action";
  $w->value="client";
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
