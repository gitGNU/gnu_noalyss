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
 * \brief Page who manage the different actions (meeting, letter)
 */
$User->can_request(GECOUR);
echo JS_PROTOTYPE;
$retour=HtmlInput::button_href('Retour','?p_action=suivi_courrier&'.dossier::get());
//-----------------------------------------------------
// Action
//-----------------------------------------------------
require_once("class_icard.php");
require_once("class_ispan.php");
require_once("class_ifile.php");
require_once("class_itext.php");
require_once("class_action.php");
require_once('class_iaction.php');
/*!\brief Show the list of action, this code should be common
 *        to several webpage. But for the moment we keep like that
 *        because it is used only by this file.
 *\param $cn database connection
 * \param $retour button for going back
 * \param $h_url calling url
 */
function ShowActionList($cn)
{
  // show the search menu
  ?>
<div>
<span style="position:float;float:left">
<form method="get" action="commercial.php">
<?php  
	echo dossier::hidden();
   $a=(isset($_GET['query']))?$_GET['query']:"";
   $qcode=(isset($_GET['qcode']))?$_GET['qcode']:"";

   echo JS_SEARCH_CARD;
   $w=new ICard();
   $w->name='qcode';
   $w->value=$qcode;
   $w->label='Quick Code1';
   $w->extra='4,9,14,16,8';
   $w->extra2='Fiche';
   $w->jrn=0;
   $w->table=0;
   echo '<span>';
   echo $w->input();
   printf ('Titre ou référence: <input type="text" name="query" value="%s"></span>',
	   $a);
   $see_all=new ICheckBox('see_all');
   $see_all->selected= (isset($_REQUEST['see_all']))?true:false;
   echo 'les actions fermées aussi:'.$see_all->input();
   $my_action=new ICheckBox('my_action');
   $my_action->selected= (isset($_REQUEST['my_action']))?true:false;
   echo 'uniquement mes actions:'.$my_action->input();

   $sp=new ISpan("qcode_label",$qcode);
   echo $sp->input();


?>

<input type="submit" name="submit_query" value="recherche">
<input type="hidden" name="sa" value="list">
<input type="hidden" name="p_action" value="suivi_courrier">
</form>
</span>

<span style="float:right">
<form  method="get" action="commercial.php">
   <?php echo dossier::hidden(); ?>
<input type="submit" name="submit_query" value="Ajout Action">
<input type="hidden" name="p_action" value="suivi_courrier">
<input type="hidden" name="sa" value="add_action">
</form>
</span>
</div>
<div class="content">

<?php  
    // show the  action in 
    $act=new Action($cn);
   /*! \brief
    *  \note The field 'recherche' is   about a part of the title or a ref. number
    */
   $query="";

   if ( isset($_REQUEST['query']) )
   {
     // if a query is request build the sql stmt
     $query="and (ag_title ~* '".FormatString($_REQUEST['query'])."' ".
       "or ag_ref ='".trim(FormatString($_REQUEST['query'])).
       "' or ag_comment ~* '".trim(FormatString($_REQUEST['query']))."'".
       ")"; 
   }
 
   $str="";
   if ( isset($_GET['qcode'] )) 
     {

        // verify that qcode is not empty
        if ( strlen(trim($_REQUEST['qcode'] )) != 0 )
	 { 

	   $fiche=new Fiche($cn);
	   $fiche->get_by_qcode($_REQUEST['qcode']);
	   // if quick code not found then nothing
	   if ( $fiche->id == 0 ) 
	     $str=' and false ';
	   else
	     $str=" and (f_id_dest= ".$fiche->id." ) ";
	 }
     }
   if ( ! isset($_REQUEST['see_all']))      $query = ' and ag_state in (2,3) ';
   if ( isset($_REQUEST['my_action']))      $query = " and (ag_owner='".$_SESSION['g_user']."' or ag_dest='".$_SESSION['g_user']."')";
   $r=$act->myList(ACTION,$query.$str);
   echo $r;
 }

// We need a sub action (3rd level)
  // show a list of already taken action 
  // propose to add one 
  // permit also a search
  // show detail
$sub_action=(isset($_REQUEST['sa']))?$_REQUEST['sa']:"";
/* if ag_id is set then we give it otherwise we have problem 
 * with the generation of document
 */
$ag_id=(isset($_REQUEST['ag_id']))?$_REQUEST['ag_id']:0;
/*--------------------------------------------------------------------------- */
/* We ask to generate the document */
/*--------------------------------------------------------------------------- */
if ( isset($_POST['generate'])){
  $act=new Action($cn);
  $act->fromArray($_POST);
  if ($act->ag_id == 0 ) { 
    $act->save();
    $ag_id=$act->ag_id;
  } else {
    $act->Update();
  }
  $act->generate_document($_POST['doc_mod']);
  $sub_action='detail';
}
/* for delete  */
if ( isset($_POST['delete'] )) $sub_action='delete';
if ( $sub_action == "" ) $sub_action="list";

// if correction is asked go to directly to add_action
if (isset($_POST['corr'] )) 
{
  $ag_comment=urldecode($_POST['ag_comment']);
  $sub_action="add_action";
}
// if this page is called from another menu (customer, supplier,...)
// a button back is added


//----------------------------------------------------------------------
// Update the detail
// Add a new action related to this one or update 
//----------------------------------------------------------------------
if ( $sub_action=="update" )
{
  // Update the modification
  if ( isset($_POST['save']))
    {
      $act2=new Action($cn);
      $act2->fromArray($_POST );

      if ( $act2->Update() == false ) {
	$sub_action="detail";
      } 
      else 
	{
	  ShowActionList($cn);
	  $url="?p_action=suivi_courrier&sa=detail&ag_id=".$act2->ag_id.'&'.dossier::get();
	  echo '<p><a class="mtitle" href="'.$url.'">'.hb('Action Sauvée  : '.$act2->ag_ref).'</a></p>';
	}
    }
  //----------------------------------------------------------------------
  // Add a related action 
  //----------------------------------------------------------------------
  if ( isset ($_POST['add_action_here']) )
    {
      $act=new Action($cn);

      
      //----------------------------------------
      // puis comme ajout normal (copier / coller )
      $act->fromArray($_POST);
      $act->ag_id=0;
      $act->d_id=0;
      $act->ag_ref_ag_id=$_POST['ag_id'];

      echo '<div class="content">';

      echo JS_SEARCH_CARD;
      // Add hidden tag
      echo '<form  enctype="multipart/form-data" action="commercial.php" method="post"">';
      
      echo dossier::hidden();
      $act->ag_comment="";
      echo_debug("action.inc",__LINE__,"call display");
      echo $act->Display('NEW',false,$retour);
      
      echo '<input type="hidden" name="p_action" value="suivi_courrier">';
      echo '<input type="hidden" name="sa" value="save_action_st2">';
      echo '<input type="submit" name="save_action_st2" value="Enregistrer">';
      echo '<input type="submit" name="generate" value="Genere le document"></p>';
      echo HtmlInput::submit("generate","Genere le document");
      echo '</form>';
      echo '</div>';
      
    }
  
  
}
//--------------------------------------------------------------------------------
// Show the detail of an action
// permit the update
if ( $sub_action=='detail' )
{
  echo '<div class="content">';
  $act=new Action($cn);
  $act->ag_id=$ag_id;
  echo $act->get();
  $act->ag_comment=Decode($act->ag_comment);
  echo '<form  enctype="multipart/form-data"  action="commercial.php"  method="post"   >';
  echo HtmlInput::hidden('p_action',$_REQUEST['p_action']);
  echo dossier::hidden();
  echo JS_SEARCH_CARD;
  echo $act->Display('UPD',false,$retour);
  echo '<input type="hidden" name="p_action" value="suivi_courrier">';
  echo '<input type="hidden" name="sa" value="update">';
  echo HtmlInput::submit("save","Sauve");
  echo HtmlInput::submit("generate","Genere le document");
  echo HtmlInput::submit("add_action_here","Ajoute une action à celle-ci");
  echo HtmlInput::submit("delete","Efface cette action"," onclick=\"return confirm('Vous confirmez l\'effacement')\" )");
  echo '</form>';
  echo '</div>';

}
//-------------------------------------------------------------------------------
// Delete an action
if ( $sub_action == 'delete' ) 
{
      // confirmed 
  $cn->start();
  $act=new Action($cn);
  $act->ag_id=$_REQUEST['ag_id'];
  $act->get();
  $act->remove();      
  $sub_action="list";
  $cn->commit();
  ShowActionList($cn);
  if ( isset( $act->ag_ref) )
    echo hb('Action '.$act->ag_ref.' effacée');
  exit();
}

//--------------------------------------------------------------------------------
// Show a list of the action
if ( $sub_action == "list" )
     ShowActionList($cn);
       
//--------------------------------------------------------------------------------
// Add an action
if ( $sub_action == "add_action" ) 
{
  echo_debug('action',__LINE__,var_export($_POST,true));
  $act=new Action($cn);
  $act->fromArray($_POST );
  $act->ag_id=0;
  $act->d_id=0;
  echo '<div class="content">';
  echo JS_SEARCH_CARD;
  // Add hidden tag
  echo '<form method="post" action="commercial.php" name="form_add" id="form_add" enctype="multipart/form-data" >';
  echo dossier::hidden();


  $act->ag_comment=(isset($_POST['ag_comment']))?Decode($_POST['ag_comment']):"";
  echo_debug("action.inc",__LINE__,"call display");
  echo $act->Display('NEW',false,$retour);

  echo '<input type="hidden" name="p_action" value="suivi_courrier">';
  echo '<input type="hidden" name="sa" value="save_action_st2">';
  echo '<input type="hidden" name="save_action_st2" value="save_action_st2">';
  echo '<input type="submit" name="save_action_st2" value="Enregistrer">';
  echo HtmlInput::submit("generate","Genere le document");
  echo '</form>';
  
  echo   '</div>';
}
//--------------------------------------------------------------------------------
// Save Action
// Stage 2 : Save the action + Files and generate eventually a document
//--------------------------------------------------------------------------------
if  ( $sub_action == "save_action_st2" ) 
{
  $act=new Action($cn);
  $act->fromArray($_POST);
  $act->d_id=0;
  $act->ag_ref_ag_id=(isset($_POST['ag_ref_ag_id']))?$_POST['ag_ref_ag_id']:0;
  $act->md_id=(isset($_POST['gen_doc']))?$_POST['gen_doc']:0;

  // insert into action_gestion
  echo $act->save();
  ShowActionList($cn);
  $url="?p_action=suivi_courrier&sa=detail&ag_id=".$act->ag_id.'&'.dossier::get();
  echo '<p><a class="mtitle" href="'.$url.'">'.hb('Action Sauvée  : '.$act->ag_ref).'</a></p>';
}
//---------------------------------------------------------------------

echo "</div>";
?>
