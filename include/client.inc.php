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
require_once("class_iselect.php");
require_once("class_ihidden.php");
require_once("class_customer.php");
$sub_action=(isset($_REQUEST['sb']))?$_REQUEST['sb']:"list";

/*! \file
 * \brief Called from the module "Gestion" to manage the customer
 */
$User->can_request(GECUST);
$href=basename($_SERVER['PHP_SELF']);

// Menu
// Remove a card
if ( isset ($_POST['delete']) ) 
{

  $f_id=$_REQUEST['f_id'];

  $fiche=new Customer($cn,$f_id);
  $fiche->remove();
  $sub_action="list";
}
//-----------------------------------------------------
// Add card
if ( $sub_action=="insert" )
{

  $retour=HtmlInput::button_href("Retour", urldecode($_REQUEST['url']));

  $customer=new Customer($cn);
  $customer->Save($_REQUEST['fd_id']);

  echo '<div class="content">';
  echo $retour;
  echo "<table>";
  echo $customer->Display(true);
  echo "</table>";
  echo $retour;
  echo '</div>';

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

  $retour=HtmlInput::button_href('Retour',$href.'?p_action=client&'.dossier::get());

  echo '<div class="content">';

  echo $retour;
  $c=new Customer($cn);
  echo '<form method="post" action="'.$href.'"';
  echo dossier::hidden();
  echo '<input type="hidden" name="p_action" value="client">';
  echo '<input type="hidden" name="sb" value="insert">';
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
<div class="content">
<span  style="position:float;float:left">
<form method="get" action="<?php echo $href; ?>">
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
<form method="get" action="<?php echo $href;?>">
   <?php echo dossier::hidden(); ?>
<input type="hidden" name="p_action" value="client">

<?php  
 $w=new ISelect();
 $w->name="fd_id";
 $w->value= make_array($cn,"select fd_id,fd_label from fiche_def where ".
	     " frd_id=".FICHE_TYPE_CLIENT);
 echo $w->input();
?>
<input type="hidden" name="sb" value="blank">
<input type="submit" name="submit_query" value="Ajout Client">
<input type="hidden" name="url" <?php        $url=urlencode($_SERVER['REQUEST_URI']);echo 'value="'.$url.'"'; ?>

</form>
</span>
<?php  
   $client=new Customer($cn);
 $search=(isset($_GET['query']))?$_GET['query']:"";

 echo '<div class="content">';
 echo $client->Summary($search);
 echo '</div>';
 echo '</div>';

}
//-----------------------------------------------------
// Show Detail
if ( $sub_action == 'detail' )
{
  $f_id=$_REQUEST['f_id'];
  echo '<div class="content">';
  $client=new Customer($cn,$f_id);
  $retour=HtmlInput::button_href("Retour", urldecode($_REQUEST['url']));

  echo $retour;
  echo '<form action="'.$_REQUEST['url'].'" method="post">'; 
  echo dossier::hidden();
  echo $client->Display(false);
  $w=new IHidden();
  $w->name="p_action";
  $w->value="client";
  echo $w->input();
  $w->name="f_id";
  $w->value=$f_id;
  echo $w->input();

  
  echo HtmlInput::submit('mod','Sauver les modifications');
  echo HtmlInput::reset("Annuler");
  echo HtmlInput::submit('delete','Effacer cette fiche','onclick="return confirm(\'Confirmer effacement ?\');"');
  
  echo '</form>';
  echo $retour;
  echo '<div>';
}
html_page_stop();
?>
