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
/*! \file
 * \brief Page who manage the different action (meeting, letter)
 */
var_dump($_POST);
//-----------------------------------------------------
// Action
//-----------------------------------------------------
require_once("class_widget.php");
require_once("class_action.php");

/*!\brief Show the list of action, this code should be common
 *        to several webpage. But for the moment we keep like that
 *        because it is used only by this file.
 *\param $cn database connection
 * \param $retour button for going back
 * \param $h_url calling url
 */
function ShowActionList($cn,$retour,$h_url)
{
  // show the search menu
  ?>
<div>
<span style="position:float;float:left">
<form method="get" action="commercial.php">
<?
   $a=(isset($_GET['query']))?$_GET['query']:"";
   printf ('<span>Titre ou référence: <input type="text" name="query" value="%s"></span>',
	   $a);
   $qcode=(isset($_GET['qcode_query']))?$_GET['qcode_query']:"";
   echo JS_SEARCH_CARD;
   $w=new widget('js_search_only');
   $w->name='qcode_query';
   $w->value=$qcode;
   $w->label='Quick Code';
   $w->extra='4,9,14,16,8';
   $w->table=0;
   $sp= new widget("span");

   echo $sp->IOValue("qcode_query_label","",$qcode);
   echo $w->IOValue();
?>
<input type="submit" name="submit_query" value="recherche">
<input type="hidden" name="sa" value="list">
<input type="hidden" name="p_action" value="suivi_courrier">
</form>
</span>

<?
?>
<form  style="position:float;float:left" method="get" action="commercial.php">
<input type="submit" name="submit_query" value="Ajout Action">
<input type="hidden" name="p_action" value="suivi_courrier">
<input type="hidden" name="sa" value="add_action">
<!--    <input type="hidden" name="qcode_dest" value=<? echo '"'.$qcode_dest.'"';?> -->
   <? // if called from another menu, url is set
   echo $h_url;
   echo $retour; ?>
</form>
</div>
<div class="u_content">

<?
    // show the  action in 
    $act=new action($cn);
   /*! \brief
    *  \note The field 'recherche' is   about a part of the title or a ref. number
    */
   $query="";

   if ( isset($_REQUEST['query']) )
   {

     // if a query is request build the sql stmt
     $query="and (ag_title ~* '".FormatString($_REQUEST['query'])."' ".
       "or ag_ref ='".trim(FormatString($_REQUEST['query']))."'".
       ")"; 
   }
 
   $str="";
   if ( isset($_GET['qcode_query'] )) 
     {

        // verify that qcode is not empty
        if ( strlen(trim($_REQUEST['qcode_query'] )) != 0 )
	 { 

	   $fiche=new Fiche($cn);
	   $fiche->GetByQCode($_REQUEST['qcode_query']);
	   $str=" and (f_id_exp= ".$fiche->id." or ".
	     "f_id_dest=".$fiche->id.")";

	 }
     }

   $r=$act->myList(ACTION,$query.$str);
   echo $r;
 }

// We need a sub action (3rd level)
  // show a list of already taken action 
  // propose to add one 
  // permit also a search
  // show detail
$sub_action=(isset($_REQUEST['sa']))?$_REQUEST['sa']:"";

if ( $sub_action == "" ) $sub_action="list";

// if correction is asked go to directly to add_action
if (isset($_POST['corr'] )) 
{
  $ag_comment=urldecode($_POST['ag_comment']);
  $sub_action="add_action";
}
// if this page is called from another menu (customer, supplier,...)
// a button back is added
// TODO add function for generating url, hidden tags...
//$retour='<A class="mtitle" HREF="commercial.php?p_action=suivi_courrier"><input type="button" value="Retour"></A>';
$retour="";
$h_url="";

if ( isset ($_REQUEST['url'])) 
{
     $retour=sprintf('<A class="mtitle" HREF="%s"><input type="button" value="Retour"></A>',urldecode($_REQUEST['url']));
     $h_url=sprintf('<input type="hidden" name="url" value="%s">',urldecode($_REQUEST['url']));
}
//----------------------------------------------------------------------
// Update the detail
// Add a new action related to this one or update 
//----------------------------------------------------------------------
if ( $sub_action=="update" )
{
  // Update the modification
  if ( isset($_POST['save']))
    {
      $act=new action($cn);
      $act=new action($cn);
      
      $act->ag_id=$_POST['ag_id'];
      $act->ag_comment=$_POST['ag_comment'];
      $act->ag_timestamp=$_POST['ag_timestamp'];
      $act->d_state=$_POST['d_state'];
      $act->dt_id=(isset($_POST['dt_id']))?$_POST['dt_id']:0;
      $act->qcode_exp=$_POST['qcode_exp'];
      $act->qcode_dest=$_POST['qcode_dest'];
      $act->ag_title=$_POST['ag_title'];
      $act->d_id=(isset($_POST['d_id']))?$_POST['d_id']:0;
      if ( $act->Update() == false ) {
	$sub_action="detail";
      } 
      else 
	{
	  ShowActionList($cn,$retour,$h_url);
	}
    }
  //----------------------------------------------------------------------
  // Add a related action 
  //----------------------------------------------------------------------
if ( isset ($_POST['add_action_here']) )
{
      $act=new action($cn);
      $act->ag_ref_ag_id=$_POST['ag_id'];
      
      //----------------------------------------
      // puis comme ajout normal (copier / coller )
      echo $retour;
      $act->ag_id=0;
      $act->qcode_dest=(isset($_POST['qcode_dest']))?$_REQUEST['qcode_dest']:"";
      $act->qcode_exp=(isset($_POST['qcode_exp']))?$_REQUEST['qcode_exp']:"";
      $act->f_id_dest=(isset($_POST['f_id_dest']))?$_POST['f_id_dest']:0;
      $act->f_id_exp=(isset($_POST['f_id_exp']))?$_POST['f_id_exp']:0;

      $act->ag_ref_ag_id=$_POST['ag_id'];
      $act->ag_timestamp=(isset($_POST['ag_timestamp']))?$_POST['ag_timestamp']:"";
      $act->qcode_dest=isset($_POST['qcode_dest'])?$_REQUEST['qcode_dest']:"";
      $act->qcode_exp=isset($_POST['qcode_exp'])?$_REQUEST['qcode_exp']:"";
      $act->d_id=0;
      $act->dt_id=isset($_POST['dt_id'])?$_REQUEST['dt_id']:"";
      $act->d_state=(isset($_POST['d_state']))?$_POST['d_state']:"";
      $act->ag_ref="";
      $act->ag_title=(isset($_POST['ag_title']))?$_POST['ag_title']:"";
      echo '<div class="u_redcontent">';
      echo JS_SEARCH_CARD;
      // Add hidden tag
      echo '<form name="RTEDemo" action="commercial.php?p_action=suivi_courrier" method="post" onsubmit="return submitForm();">';
      
      $act->ag_comment=(isset($_POST['ag_comment']))?Decode($_POST['ag_comment']):"";
      echo_debug("action.inc",__LINE__,"call display");
      echo $act->Display('NEW',false);
      
      echo '<input type="hidden" name="p_action" value="suivi_courrier">';
      echo '<input type="hidden" name="sa" value="save_action_st1">';
      
      echo $h_url;
      echo '<input type="submit" name="save_action_st1" value="Sauver"></p>'.
	'</form>'.
	'</div>';
      
    }
  
  
}
//--------------------------------------------------------------------------------
// Show the detail of an action
// permit the update
if ( $sub_action=='detail' )
{
  echo '<div class="u_redcontent">';
  echo '<A class="mtitle" HREF="commercial.php?p_action=suivi_courrier"><input type="button" value="Retour"></A>';
  $act=new action($cn);
  $act->ag_id=$_REQUEST['ag_id'];
  echo $act->get();
  $act->ag_comment=Decode($act->ag_comment);
  echo '<form name="RTEDemo" action="commercial.php"  enctype="multipart/form-data"  method="post"  onsubmit="return submitForm();" >';
  echo JS_SEARCH_CARD;
  echo $act->display('UPD',false);
  echo '<input type="hidden" name="p_action" value="suivi_courrier">';
  echo '<input type="hidden" name="sa" value="update">';
  $upload=new widget("file");
  $upload->name="file_upload";
  $upload->value="";
  echo "Enregistrer le fichier ".$upload->IOValue();
  echo $upload->Submit("save","Sauve");
  echo $upload->Submit("add_action_here","Ajoute une action à celle-ci");
  echo '</form>';

  echo $retour;

  echo '</div>';
}
//--------------------------------------------------------------------------------
// Show a list of the action
if ( $sub_action == "list" )
     ShowActionList($cn,$retour,$h_url);
       
//--------------------------------------------------------------------------------
// Add an action
if ( $sub_action == "add_action" ) 
{
  echo '<A class="mtitle" HREF="commercial.php?p_action=suivi_courrier"><input type="button" value="Retour"></A>';
  echo_debug('action',__LINE__,var_export($_POST,true));
  echo $retour;
  $act=new action($cn);
  $act->ag_id=0;
  $act->ag_ref_ag_id=(isset($_POST['ag_ref_ag_id']))?$_POST['ag_ref_ag_id']:"0";
  $act->ag_timestamp=(isset($_POST['ag_timestamp']))?$_POST['ag_timestamp']:"";
  $act->qcode_dest=(isset($_POST['qcode_dest']))?$_REQUEST['qcode_dest']:"";
  $act->qcode_exp=(isset($_POST['qcode_exp']))?$_REQUEST['qcode_exp']:"";
  $act->f_id_dest=(isset($_POST['f_id_dest']))?$_POST['f_id_dest']:0;
  $act->f_id_exp=(isset($_POST['f_id_exp']))?$_POST['f_id_exp']:0;
  $act->d_id=0;
  $act->dt_id=isset($_POST['dt_id'])?$_REQUEST['dt_id']:"";
  $act->d_state=(isset($_POST['d_state']))?$_POST['d_state']:"";
  $act->ag_ref="";
  $act->ag_title=(isset($_POST['ag_title']))?$_POST['ag_title']:"";
  echo '<div class="u_redcontent">';
  echo JS_SEARCH_CARD;
  // Add hidden tag
  echo '<form name="RTEDemo" action="commercial.php?p_action=suivi_courrier" method="post" onsubmit="return submitForm();">';

  $act->ag_comment=(isset($_POST['ag_comment']))?Decode($_POST['ag_comment']):"";
  echo_debug("action.inc",__LINE__,"call display");
  echo $act->Display('NEW',false);

  echo '<input type="hidden" name="p_action" value="suivi_courrier">';
  echo '<input type="hidden" name="sa" value="save_action_st1">';

  echo $h_url;
  echo '<input type="submit" name="save_action_st1" value="Sauver"></p>'.
    '</form>'.
    '</div>';

}
//--------------------------------------------------------------------------------
// Save Action
// Stage 1 : show the result and confirm
//--------------------------------------------------------------------------------
if  ( $sub_action == "save_action_st1" ) 
{
  $act=new action($cn);
  $act->ag_timestamp=$_POST['ag_timestamp'];
  $act->ag_comment=$_POST['ag_comment'];
  $act->ag_timestamp=$_POST['ag_timestamp'];
  $act->d_state=$_POST['d_state'];
  $act->dt_id=$_POST['dt_id'];
  $act->qcode_exp=$_POST['qcode_exp'];
  $act->qcode_dest=$_POST['qcode_dest'];
  $act->f_id_dest=$_POST['f_id_dest'];
  $act->f_id_exp=$_POST['f_id_exp'];

  $act->ag_title=$_POST['ag_title'];
  $act->d_id=0;
  $act->ag_id=$_POST['ag_id'];
  $act->ag_ref_ag_id=(isset($_POST['ag_ref_ag_id']))?$_POST['ag_ref_ag_id']:0;
      
  echo $act->Confirm();
}
//--------------------------------------------------------------------------------
// Save Action
// Stage 2 : Save the action and propose to save a file
//--------------------------------------------------------------------------------
if  ( $sub_action == "save_action_st2" ) 
{
  $act=new action($cn);

  $act->ag_comment=$_POST['ag_comment'];
  $act->ag_timestamp=$_POST['ag_timestamp'];
  $act->d_state=$_POST['d_state'];
  $act->dt_id=$_POST['dt_id'];
  $act->qcode_dest=$_POST['qcode_dest'];
  $act->qcode_exp=$_POST['qcode_exp'];
  $act->f_id_dest=$_POST['f_id_dest'];
  $act->f_id_exp=$_POST['f_id_exp'];

  $act->ag_title=$_POST['ag_title'];
  $act->d_id=0;
  $act->ag_ref_ag_id=(isset($_POST['ag_ref_ag_id']))?$_POST['ag_ref_ag_id']:0;
  $act->md_id=(isset($_POST['gen_doc']))?$_POST['gen_doc']:0;

  $act->gen=isset($_POST['p_gen'])?'on':'off';
  // insert into action_gestion
  echo $act->SaveStage2();
  echo '<A HREF="commercial.php?p_action=suivi_courrier"><INPUT TYPE="BUTTON" VALUE="Retour Liste"></A>';
}

//--------------------------------------------------------------------------------
// Save Document
// Stage 3 : Save the document
//--------------------------------------------------------------------------------
if  ( $sub_action == "save_action_st3" ) 
{
  echo_debug("action.inc.php",__LINE__,'Stage 3');
  $act=new action($cn);
  $act->ag_id=$_POST['ag_id'];
  $act->ag_ref_ag_idid=$_POST['ag_ref_ag_id'];
  $act->ag_comment=$_POST['ag_comment'];
  $act->ag_timestamp=$_POST['ag_timestamp'];
  $act->d_state=$_POST['d_state'];
  $act->dt_id=$_POST['dt_id'];
  $act->qcode_dest=$_POST['qcode_dest'];
  $act->qcode_exp=$_POST['qcode_exp'];

  $act->ag_title=$_POST['ag_title'];
  $d_id=(isset($_POST['d_id']))?$_POST['d_id']:0;
  $act->ag_ref_ag_id=(isset($_POST['ag_ref_ag_id']))?$_POST['ag_ref_ag_id']:0;
  echo $act->SaveStage3($d_id);
  ShowActionList($cn,$retour,$h_url);

}
//---------------------------------------------------------------------

echo "</div>";
