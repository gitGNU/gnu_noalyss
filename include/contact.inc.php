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
//!\brief File for adding contact, contact is a kind of fiche
require_once("class_icard.php");
require_once("class_ispan.php");
require_once("class_iselect.php");
require_once("class_ihidden.php");
require_once('class_contact.php');
/*! \file
 * \brief the contact class is derived from the class fiche
 *        the contact is in fact a card but more specific
 *        This file is included from the module "Gestion"
 */

$sub_action=(isset($_REQUEST['sa']))?$_REQUEST['sa']:"";

// if this page is called from another menu (customer, supplier,...)
// a button back is added
// TODO add function for generating url, hidden tags...
if ( isset ($_REQUEST['url'])) 
{
  $retour=widget::button_href('Retour',urldecode($_REQUEST['url']));

     $h_url=sprintf('<input type="hidden" name="url" value="%s">',urldecode($_REQUEST['url']));
}
else 
{ 
     $retour="";
     $h_url="";
}

// Menu
// Remove a card
if ( isset ($_POST['delete']) ) 
{
  $f_id=$_REQUEST['f_id'];

  $fiche=new contact($cn,$f_id);
  $fiche->remove();
  $sub_action="list"; 
}
//-----------------------------------------------------
// Add card
if ( $sub_action=="insert" )
{
  $contact=new Contact($cn);
  $contact->Save($_REQUEST['fd_id']);
  echo $retour;
  echo "<table>";
  echo $contact->Display(true);
  echo "</table>";
  echo $retour;

}

//-----------------------------------------------------
// Save modification
if ( isset ($_POST['mod'])) 
{
  // modification is asked
  $f_id=$_REQUEST['f_id'];

  $contact=new contact($cn,$f_id);
  $contact->Save();
  $sub_action="list";
}
// by default open liste
if ( $sub_action  == "" ) 
      $sub_action="list";
//-----------------------------------------------------
//Display a blank card 
if ( $sub_action=="blank") 
{
  $retour_action=widget::button_href('Retour', "commercial.php?p_action=contact&$str_dossier");

  echo '<div class="u_redcontent">';

  echo $retour_action;
  $c=new contact($cn);
  echo '<form method="post" action="commercial.php"';
  echo dossier::hidden();
  echo '<input type="hidden" name="p_action" value="client">';
  echo '<input type="hidden" name="sa" value="insert">';
  echo '<input type="hidden" name="fd_id" value="'.$_GET['fd_id'].'">';
  echo '<input type="hidden" name="url" value="'.$_GET['url'].'">';
  echo $c->blank($_GET['fd_id']);
  echo '<input type="Submit" value="Sauve">';
  echo '</form>';
  echo $retour_action;
  echo '</div>';
}
//-----------------------------------------------------
// list
if ( $sub_action == "list" )
{
?>
<div class="content">
<span>
<form method="get" action="commercial.php">
<?php
	echo dossier::hidden();  
   $a=(isset($_GET['query']))?$_GET['query']:"";
   printf ('<input type="text" name="query" value="%s">',
	   $a);
?>
<input type="submit" name="submit_query" value="recherche">
<input type="hidden" name="p_action" value="contact">
</form>


<form>
<?php
   echo dossier::hidden();  
   $qcode=(isset($_GET['qcode']))?$_GET['qcode']:"";
 echo JS_SEARCH_CARD;
 $w=new ICard();
 $w->name='qcode';
 $w->value=$qcode;
 $w->label='qcode';
 $w->table=0;
 $w->extra='4,8,9,14';
 echo $w->IOValue();


 $sp=new ISpan();
 echo $sp->IOValue("qcode_label",$qcode)."</TD></TR>";


?>
<input type="submit" name="submit_query" value="recherche">
<input type="hidden" name="p_action" value="contact">

</FORM>
</span>
<span>
<form method="get" action="commercial.php">
   <? echo dossier::hidden(); ?>
<input type="hidden" name="url" <?php        $url=urlencode($_SERVER['REQUEST_URI']);echo 'value="'.$url.'"'; ?>
<input type="hidden" name="p_action" value="contact">

<?php  
 $w=new ISelect();
 $w->name="fd_id";
 $w->value= make_array($cn,"select fd_id,fd_label from fiche_def where ".
	     " frd_id=".FICHE_TYPE_CONTACT);
 // if array is empty show an warning and stops
 if ( sizeof ($w->value) == 0 ) {
   echo '<p style="color:red">Aucune fiche de catégories contact</p>';
   echo '<p>allez dans fiche creation et choississez contact comme sorte</p>';
   exit();
 }
 echo $w->IOValue();

?>
<input type="hidden" name="sa" value="blank">
<input type="submit" name="submit_query" value="Ajout Contact">

</form>
</span>
<?php  
   $contact=new Contact($cn);
 $search=(isset($_GET['query']))?$_GET['query']:"";
 // check if a company is asked if yes, add a condition
 if ( $qcode != "" )
   {
     $contact->company=$qcode;
   }
 echo $retour;
 echo '<div class="u_redcontent">';
 echo $contact->Summary($search);
 echo '</div>';
 echo $retour;


}
//-----------------------------------------------------
// Show Detail
if ( $sub_action == 'detail' )
{
  $f_id=$_REQUEST['f_id'];
  echo '<div class="u_redcontent">';
  $contact=new contact($cn,$f_id);
  echo $retour;
  echo '<form action="'.$_SERVER['REQUEST_URI'].'" method="post">'; 
  echo dossier::hidden();
  echo $contact->Display(false);
  $w=new IHidden();
  $w->name="p_action";
  $w->value="contact";
  echo $w->IOValue();
  $w->name="f_id";
  $w->value=$f_id;
  echo $w->IOValue();

  echo widget::submit('mod','Sauver les modifications');
  echo '<A HREF="commercial.php?p_action=contact&'.$str_dossier.'"><INPUT TYPE="button" value="Retour"></A>';
  echo widget::submit('delete','Effacer cette fiche');
  echo '</form>';
  echo $retour;
  echo '<div>';
}
html_page_stop();


