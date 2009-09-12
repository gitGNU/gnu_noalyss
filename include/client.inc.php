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
  /*!\brief include from client.inc.php and concerned only the customer card and
   * the customer category
   */
require_once("class_iselect.php");
require_once("class_ihidden.php");
require_once("class_customer.php");
require_once("class_ibutton.php");
require_once('class_iaction.php');
require_once('class_fiche_def.php');
require_once('class_iaction.php');
require_once('class_fiche_def.php');

$sub_action=(isset($_REQUEST['sb']))?$_REQUEST['sb']:"list";
echo JS_PROTOTYPE;
echo JS_AJAX_FICHE;
/*! \file
 * \brief Called from the module "Gestion" to manage the customer
 */
$User->can_request(GECUST);
$href=basename($_SERVER['PHP_SELF']);

// by default open liste
if ( $sub_action  == "" ) 
      $sub_action="list";
//---------------------------------------------------------------------------
// Add a category of customer
//---------------------------------------------------------------------------
if ( $sub_action == 'cat' ) {
 /* security */
  if ( $User->check_action(FICCAT) == 0 ) {
    alert('Vous  ne pouvez pas ajouter de catégorie de fiche');
    return;
  }
  if ( isset($_POST['insert'])) {
      if ( ! isset ($_POST ["nom_mod"]) || 
	   ! isset ($_POST['class_base']) )
	die(__FILE__.__LINE__.'Invalid call');
      /* insert the new cat into the database */
      if ( strlen(trim($_POST['nom_mod'])) != 0 &&
	   strlen(trim($_POST['class_base'])) != 0 ) {
	$array=array("FICHE_REF"=>FICHE_TYPE_CLIENT,
		   "nom_mod"=>$_POST['nom_mod'],
		     "class_base"=>$_POST['class_base']);
	if ( isset ($_POST['create'])) $array['create']=1;
	$catcard=new Fiche_def($cn);
	$catcard->Add($array);
      } else {
	alert("Le nom et la classe base ne peuvent être vide");
	$invalid=1;
      }
      
    }
    if ( ! isset($_POST['insert']) || isset($invalid) ) {
    echo "<h2>Ajout d'une catégorie</h2>";
    /*  show the form */
    echo JS_SEARCH_POSTE;
    echo '<div class="u_content">';
    echo '<form name="newcat" action="'.$href.'" method="post">'; 
    echo HtmlInput::hidden('p_action','client');
    echo HtmlInput::hidden('sb','cat');
    echo HtmlInput::hidden('insert','');
    echo dossier::hidden();
     $base=$cn->get_value("select p_value from parm_code where p_code='CUSTOMER'");
    $p_js=" SearchPosteFilter('".$_REQUEST['PHPSESSID']."','".dossier::id()."','class_base','all','','class_base_label')";
    require('template/category_of_card.php');
    $submit=new IAction('save',"");
    $submit->label='Sauve';
    $submit->javascript='document.newcat.submit();';
    $retour=new IAction('return',"?p_action=client&".dossier::get());
    $retour->label='Retour';
    echo '<p>';
    echo $submit->input();
    echo $retour->input();


    echo '</p>';
    echo '</form>';
    echo '</div>';
  }
}
//-----------------------------------------------------
//Display a blank card 
//-----------------------------------------------------
if ( $sub_action=="blank") 
{
  /* security */
  if ( $User->check_action(FICADD) == 0 ) {
    alert('Vous  ne pouvez pas ajouter de fiche');
    return;
  }
  if ( !isset ($_REQUEST['fd_id'])) {
    $type=new Fiche_Def($cn);
    if ( $type->count_category(FICHE_TYPE_CLIENT) == 1 ) {
      /* if only one kind we continue */
      $fd_id=$cn->get_value("select fd_id from fiche_def where frd_id=$1",
			    array(FICHE_TYPE_CLIENT));
    } else {
      echo '<div class="content">';
    /* if we have several kind of card for customer there
       is on more step to select the card so we propose a form to select one*/
      echo '<h2 > Choississez la catégorie de fiche </h2>';
      echo '<form id="newcard" name="newcard" method="post" action="'.$href.'">';
      echo HtmlInput::hidden('sb','blank');
      echo HtmlInput::hidden('p_action','client');
      echo dossier::hidden();
      $aFd_id=$cn->get_array("select fd_id from fiche_def where frd_id=$1",
			     array(FICHE_TYPE_CLIENT));
      foreach ($aFd_id as $id) {
	$select=new IRadio("fd_id");
	$cat=new Fiche_def($cn,$id['fd_id']);
	$cat->Get(); 
	$select->value=$id['fd_id'];
	echo $select->input();
	echo $cat->label;
	echo '<br>';
      }
      echo '<p>';
      $submit=new IAction('save',"");
      $submit->label='Sauve';
      $submit->javascript='document.newcard.submit();"';
      echo $submit->input();
      $retour=new IAction('return',"?p_action=client&".dossier::get());
      $retour->label='Retour';
      echo $retour->input();
      echo '</p>';
      echo '</form>';
      echo '</div>';
      exit();
    }
  } else {
    $fd_id=$_REQUEST['fd_id'];
  }
  /* if we have only want kind  */
  echo '<div class="content">';
  $c=new Customer($cn);
  echo '<form name="newcard" method="post" action="'.$href.'"';
  echo dossier::hidden();
  echo '<input type="hidden" name="p_action" value="client">';
  echo '<input type="hidden" name="sb" value="insert">';
  echo '<input type="hidden" name="fd_id" value="'.$fd_id.'">';

  echo $c->blank($fd_id);
  echo '<p>';
 
  $submit=new IAction('save',"");
  $submit->label='Sauve';
  $submit->javascript='document.newcard.submit();"';
  echo $submit->input();
  $retour=new IAction('return',"?p_action=client&".dossier::get());
  $retour->label='Retour';
  echo $retour->input();
  echo '</p>';
  echo '</form>';
  echo '</div>';

}
//-----------------------------------------------------
// Remove a card
//-----------------------------------------------------
if ( isset($_POST['delete'] ) )
{

  $f_id=$_REQUEST['f_id'];

  $fiche=new Customer($cn,$f_id);
  $fiche->remove();
  $sub_action="list";

}

//-----------------------------------------------------
//    list of customer
//-----------------------------------------------------
if ( $sub_action == "list" )
{

?>
<div class="content">
<span  style="position:float;float:left">
<form method="get" action="<?php echo $href; ?>">
<?php
	echo dossier::hidden();  
   $a=(isset($_GET['query']))?$_GET['query']:"";
   printf ('Recherche <input type="text" name="query" value="%s">',
	   $a);
?>
<input type="submit" name="submit_query" value="recherche">
<input type="hidden" name="p_action" value="client">
</form>
</span>
<?php  
   $client=new Customer($cn);
 $search=(isset($_GET['query']))?$_GET['query']:"";

 echo '<div class="content">';
 echo $client->Summary($search,$action);

 $w=new IAction();
 $w->name='blank';
 $w->label='Ajout de client';
 $w->value='?p_action=client&'.$str_dossier.'&sb=blank';
 $ajout_cat=new IAction();
 $ajout_cat->name='cat';
 $ajout_cat->label="Ajout d'une catégorie";
 $ajout_cat->value='?p_action=client&'.$str_dossier.'&sb=cat';
 echo '<br>';
 echo '<br>';
 echo '<br>';
 echo $w->input();
 echo $ajout_cat->input();
 echo '</div>';
 echo '</div>';


}

/* Detail for a card, Suivi, Contact, Operation,... */
/* cc stands for customer card */
if ( $sub_action == 'detail') {
  /* Menu */
  require_once('client_card.inc.php');
  exit();
}

if ( $sub_action=="insert" )
{

  $customer=new Customer($cn);
  $customer->Save($_REQUEST['fd_id']);

  echo '<div class="content">';
  echo "<table>";
  echo $customer->Display(true);
  echo "</table>";
  $retour=new IAction();
  $retour->label="Retour";
  $retour->value="?p_action=client&".dossier::get();
  echo $retour->input();
  echo '</div>';

}

html_page_stop();
?>
