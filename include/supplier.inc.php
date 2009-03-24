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
require_once("class_supplier.php");
$sub_action=(isset($_REQUEST['sa']))?$_REQUEST['sa']:"";

/*! \file
 * \brief Called from the module "Gestion" to manage the customer
 */

$User->can_request(GESUPPL);
$script=basename($_SERVER['PHP_SELF']);
?>

<?php  
// Menu
// Remove a card
if ( isset ($_POST['delete']) ) 
{

  $f_id=$_REQUEST['f_id'];

  $fiche=new Supplier($cn,$f_id);
  $fiche->remove();
  $sub_action="list";
}
//-----------------------------------------------------
// Add card
if ( $sub_action=="insert" )
{
  echo '<div class="content">';

  $retour=HtmlInput::button_href("Retour", urldecode($_REQUEST['url']));

  $supplier=new Supplier($cn);
  $supplier->Save($_REQUEST['fd_id']);
  echo $retour;
  echo "<table>";
  echo $supplier->Display(true);
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

  $sup=new Supplier($cn,$f_id);
  $sup->Save();
}
// by default open liste
if ( $sub_action  == "" ) 
      $sub_action="list";
//-----------------------------------------------------
//Display a blank card 
if ( $sub_action=="blank") 
{
  $retour=HtmlInput::button_href('Retour','?p_action=fournisseur&'.dossier::get());
  echo '<div class="content">';

  echo $retour;
  $c=new Supplier($cn);
  echo '<form method="post" ';
  echo '<input type="hidden" name="p_action" value="fournisseur">';
  echo '<input type="hidden" name="sa" value="insert">';
  echo '<input type="hidden" name="fd_id" value="'.$_GET['fd_id'].'">';
  echo dossier::hidden();
  echo $c->blank($_GET['fd_id']);
  echo HtmlInput::submit("", "Sauve");
  echo '<input type="hidden" name="url" value="'.$_GET['url'].'">';

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
<span style="position:float;float:left">
<form method="get" action="">
<?php 
    echo dossier::hidden();
 
   $a=(isset($_GET['query']))?$_GET['query']:"";
   printf ('Recherche <input type="text" name="query" value="%s">',
	   $a);
?>

<input type="hidden" name="p_action" value="fournisseur">
<input type="submit" name="submit_query" value="recherche">
</form>
</span>
<?php  
   $sup=new Supplier($cn);
 $search=(isset($_GET['query']))?$_GET['query']:"";
 // echo '<div style="position:absolute;left:15%;width:67%;margin-top:20px;">';
 echo '<div class="content">';
 echo $sup->Summary($search);
 echo '</div>';
 echo '</div>';

}
//-----------------------------------------------------
// Show Detail
if ( $sub_action == 'detail' )
{
  $f_id=$_REQUEST['f_id'];
  echo '<div class="content">';
  $sup=new Supplier($cn,$f_id);
  $retour=HtmlInput::button_href("Retour", urldecode($_REQUEST['url']));

  echo $retour;
  echo '<form action="'.$_REQUEST['url'].'" method="post">'; 
  echo $sup->Display(false);
  $w=new IHidden();
  $w->name="p_action";
  $w->value="fournisseur";
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
