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
/*! \file
 * \brief module to manage the card (removing, listing, creating, modify attribut)
 */
include_once ("ac_common.php");
require_once('class_fiche.php');
include_once ("postgres.php");
include_once ("user_menu.php");
require_once ("check_priv.php");
require_once('class_dossier.php');
$gDossier=dossier::id();
$str_dossier=dossier::get();
echo JS_SEARCH_POSTE;
echo JS_AJAX_FICHE;


if ( !isset($sessid)) 
{
  $sessid=$_REQUEST["PHPSESSID"];
} 
$search='<INPUT TYPE="BUTTON" VALUE="Cherche" OnClick="SearchPoste(\''.$sessid."',".dossier::id().",'class_base','')\">";


include_once("fiche_inc.php");

$cn=DbConnect($gDossier);
echo_debug(__FILE__,__LINE__,"Connected");
// Security check
$read=$User->check_action($cn,FICHE_READ);
$write=$User->check_action($cn,FICHE_WRITE);
if ($read+$write == 0 ){
  /* Cannot Access */
  NoAccess();
}

function ShowRecherche() {
  echo '<DIV class="u_redcontent">';
  echo '<form method="GET" action="?">';
  echo dossier::hidden();
  $w=new widget('text');
  $search_text=(isset($_REQUEST['search_text']))?$_REQUEST['search_text']:"";
  $h=new widget('hidden');
  echo $h->IOValue('p_action','fiche');
  echo $h->IOValue('action','search');
  echo "Recherche :".$w->IOValue('search_text',$search_text);
  echo widget::submit('submit','Rechercher');
  echo '</form>';
  echo '</div>';
}
function ShowFicheDefInput($p_fiche_def)
{
  $r="";
  // Save the label
  
  $p_fiche_def->Get();
  $p_fiche_def->GetAttribut();
  if (isset ($_REQUEST['label']) )
        $p_fiche_def->SaveLabel($_REQUEST['label']);
  $r.= '<H2 class="info">'.$p_fiche_def->label.'</H2>';
  
  $r.= '<FORM action="?p_action=fiche" method="POST">';
  $r.=dossier::hidden();
  $r.= '<INPUT TYPE="HIDDEN" NAME="fd_id" VALUE="'.$p_fiche_def->id.'">';
  
  $r.= $p_fiche_def->DisplayAttribut("remove");
  $r.= ' <INPUT TYPE="SUBMIT" Value="Ajoute cet &eacute;l&eacute;ment" NAME="add_line">';
  $r.= ' <INPUT TYPE="SUBMIT" Value="Sauver" NAME="save_line">';
  // if there is nothing to remove then hide the button
  if ( strpos ($r,"chk_remove") != 0 ) {
    $r.=' <INPUT TYPE="SUBMIT" Value="Enleve les &eacute;l&eacute;ments coch&eacute;s" NAME="remove_line">';
  }
  $r.= "</form>";
  $r.=" <p class=\"notice\"> Attention : il n'y aura pas de demande de confirmation pour enlèver les 
attributs sélectionnés. Il ne sera pas possible de revenir en arrière</p>";
  return $r;
}

$recherche=true;
// Creation of a new model of card
// in the database
if ( isset($_POST['add_modele'])  and $write != 0) {
  // insert the model of card in database
  $fiche_def=new fiche_def($cn);
  $fiche_def->Add($_POST);
}
$r="";
// Add a line in the card model
if ( isset ($_POST["add_line"])  ) {
  $r= '<DIV class="u_redcontent">';
    if ( $write ==0)  
      $r.= "<h2 class=\"error\"> Pas d'accès </h2>";
    else
      {
		$fiche_def=new fiche_def($cn,$_REQUEST['fd_id']);
		// Insert Line
		$fiche_def->InsertAttribut($_REQUEST['ad_id']);
		
		$r.=ShowFicheDefInput($fiche_def);

      }
  $r.= '</DIV>';
  $recherche=false;
}
/* ------------------------------------------------- */
/* SAVE ORDER */
/* ------------------------------------------------- */
if ( isset($_POST['save_line'])) {
  $fiche_def=new fiche_def($cn,$_REQUEST['fd_id']);
  $fiche_def->save_order($_POST);
  $r= '<DIV class="u_redcontent">';
  if ( $write ==0)  
      $r.= "<h2 class=\"error\"> Pas d'accès </h2>";
  else
    {
      $fiche_def=new fiche_def($cn,$_REQUEST['fd_id']);
      // Insert Line
      $r.=ShowFicheDefInput($fiche_def);

      }
  $r.= '</DIV>';
  $recherche=false;

}
// Remove lines from a card model
if ( isset ($_POST['remove_line'])) 
{
  $r= '<DIV class="u_redcontent">';
    if ( $write ==0)  
      $r.= "<h2 class=\"error\"> Pas d'accès </h2>";
    else
      {
	$fiche_def=new fiche_def($cn,$_REQUEST['fd_id']);
	// Insert Line
	// demander confirmation 

	$fiche_def->RemoveAttribut($_REQUEST['chk_remove']);
	$r.=ShowFicheDefInput($fiche_def);

      }
  $r.= '</DIV>';
  $recherche=false;
}
// Change the name of the card  model
if ( isset ($_POST["change_name"] )  ) {
  $r= '<DIV class="u_redcontent">';
  if ( $write ==0)  
	$r.= "<h2 class=\"error\"> Pas d'accès </h2>";
  else
	{
	  $fiche_def=new fiche_def($cn,$_REQUEST['fd_id']);
	  $r.=ShowFicheDefInput($fiche_def);
	}
  $r.= '</DIV>';
  $recherche=false;
}

//var_dump($_GET);
ShowMenuFiche($gDossier);
echo $r;

//------------------------------------------------------------------------------
// Get action
if ( isset ( $_GET["action"]) ) {
  $action=$_GET["action"];
  // View the details of the selected cat. of cards
  if ( isset ($_GET["fiche"]) && $action=="vue" 
       && ! isset ($_POST['add_fiche']) 
       && ! isset ($_POST['update_fiche'])
       && ! isset ($_POST['delete'])) {

    echo '<DIV class="u_redcontent">';
    $fiche_def=new fiche_def($cn,$_GET['fiche']);
    $fiche_def->myList();

    echo '</DIV>';

  $recherche=false;
  }
  //_________________________________________________________________________
// Display the detail of a card
  if ($action== "detail" ) {
    echo '<DIV class="u_redcontent">';
    $t=false;
    if ( $write == 0) 
      { 
	echo '<H2 class="info"> Vos changements ne seront pas sauvés</h2>';
	$t=true;
      }
    $str="&".dossier::get();
    $fiche=new fiche($cn,$_GET["fiche_id"]);
    $fiche->get_categorie();
    $fiche_def=new fiche_def($cn,$fiche->fd_id);
    $fiche_def->Get();
    echo '<h2 class="info">'.$fiche_def->label.'</h2>';

    if ( $_SESSION['g_pagesize'] != -1 ){
      // retrieve value
      // with offet &offset=15&step=15&page=2&size=15
	if ( isset($_GET['offset']) && $_SESSION['g_pagesize'] != -1) {
	  $str=sprintf("&offset=%s&step=%s&page=%s&size=%s",
		       $_GET['offset'],
		       $_GET['step'],
		       $_GET['page'],
		       $_GET['size']);
	}
		       

    }
    if ( $write != 0 )
      echo '<form method="post" action="?p_action=fiche&action=vue&fiche='.$_GET['fiche'].$str.'">';
	echo dossier::hidden();
    echo $fiche->Display($t);
    echo '<input type="hidden" name="f_id" value="'.$_GET['fiche_id'].'">';
    if ( $write != 0 ) {
      echo '<input type="submit" name="update_fiche" value="Mise &agrave; jour">';
      echo '<input type="submit" name="delete" value="Effacer cette fiche">';
    }
    $str="";

    echo '<a class="mtitle" href="?p_action=fiche&action=vue&'.$str_dossier.'&fiche='.$fiche->fiche_def.$str.
      '"><input type="button" value="Annuler"></A>';
    if ( $write != 0 ) echo '</form>';
    echo '</DIV>';
  $recherche=false;
  }
  //_________________________________________________________________________
  // Display the form where you can enter
  // the property of the card model
  if ($action == "add_modele" and $write !=0) {
    echo '<DIV class="u_redcontent">';
    CreateCategory($cn,$search);
    echo '</DIV>';
  $recherche=false;
  }
  //_________________________________________________________________________
  // Modify a card Model
  if ($action == "modifier" ) {
    echo '<DIV class="u_redcontent">';
    if ( $write ==0)  
      echo "<h2 class=\"error\"> Pas d'accès </h2>";
    else
      {


		$fiche_def=new fiche_def($cn,$_GET['fiche']);
		
		echo ShowFicheDefInput($fiche_def);
      }
    echo '</DIV>';
  $recherche=false;
  }
  //_________________________________________________________________________
  // Search a card
  if ( $action == "search" ) 
    {
	  ShowRecherche();
      $sql="select distinct f_id,fd_id,av_text from fiche join jnt_fic_att_value using (f_id) 
            join attr_value using (jft_id) where
            upper(av_text) like upper('%".FormatString($_GET["search_text"])."%') order by av_text,f_id";
      $all=get_array($cn,$sql);
      // test on the size
      //
      if ( sizeof($all) != 0 )
		{
		  echo '<DIV class="u_redcontent">';
		  echo "Nombre de résultat : ".sizeof($all).'<br>';
		  foreach ($all as $f_id){
			$fiche=new fiche($cn,$f_id['f_id']);
			echo '<A class="mtitle" href="?p_action=fiche&'.$str_dossier.'&action=detail&fiche_id='.$f_id['f_id'].
			  '&fiche='.$f_id['fd_id'].'">'.
			  $fiche->getName().'</A><br>';
		  }
		  echo '</div>';
		}
      else 
		{
		  echo '<DIV class="u_redcontent">';
		  echo "Aucun résultat trouvé";
		  
		  echo '</div>';
		  
	}
    }
    $recherche=false;
}
// Display a blank  card from the selected category
if ( isset ($_POST["fiche"]) && isset ($_POST["add"] ) ) {
  echo '<DIV class="u_redcontent">';
    if ( $write ==0)  
      echo "<h2 class=\"error\"> Pas d'accès </h2>";
    else
      {
		$fiche_def=new fiche_def($cn,$_POST['fiche']);
		$fiche_def->Get();
		echo '<h2 class="info">'.$fiche_def->label.'</h2>';
		$url=$_SERVER['REQUEST_URI'];
		$fiche=new fiche($cn,0);
		
		echo '<form method="post" action="'.$url.'&fiche='.$_POST['fiche'].'">';
		echo dossier::hidden();
		echo $fiche->blank($_POST['fiche']);
		echo '<input type="submit" name="add_fiche" value="Ajout">';
		echo '<a class="mtitle" href="'.$url.'&fiche='.$_POST['fiche'].'&'.$str_dossier.'">'.
		  '<input type="button" value="Annuler"></A>';
		

		echo '</form>';
      }
  echo '</DIV>';
  $recherche=false;
  exit();
}
//------------------------------------------------------------------------------
// delete a card
if (isset($_POST['delete']) ) {
	ShowRecherche();
    echo '<DIV class="u_redcontent">';
    if ( $write ==0)  
      echo "<h2 class=\"error\"> Pas d'accès </h2>";
    else
      {
		$fiche=new fiche($cn,$_POST["f_id"]);
		$fiche->remove();
      }
    $fiche_def=new fiche_def($cn,$_GET['fiche']);
    $fiche_def->myList();

    echo "</DIV>";
    exit();
  }  
//------------------------------------------------------------------------------
// Add the data (attribute) of the card
if ( isset ($_POST["add_fiche"]) ) {
  
  if ( $write ==0)  {  
	  echo '<DIV class="u_redcontent">';
    echo "<h2 class=\"error\"> Pas d'accès </h2>";
    }
  else
    {
      ShowRecherche();
      echo '<DIV class="u_redcontent">';
      $fiche=new fiche($cn);
      $fiche->Save($_REQUEST['fiche']);
      $fiche_def=new fiche_def($cn,$_GET['fiche']);
      $fiche_def->myList();

	
    }
  echo '</DIV>';
  $recherche=false;
}
//------------------------------------------------------------------------------
// Update a card
if ( isset ($_POST["update_fiche"])  ) {

  echo '<DIV class="u_redcontent">';
      if ( $write ==0)  
      echo "<h2 class=\"error\"> Pas d'accès </h2>";
    else
      {
		$fiche=new fiche($cn,$_POST['f_id']);
		$fiche->Save();

      }
	  $fiche_def=new fiche_def($cn,$_GET['fiche']);
	  $fiche_def->myList();





  echo '</DIV>';
  $recherche=false;
}
//--Search menu
if ( $recherche==true) {
	ShowRecherche();
}
 html_page_stop();
?>
