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

  /*!\file
   * \brief this file is common to suivi client, suivi fournisseur, suivi
   * administration.
   * The needed variables are
   * - $cn for the database connection
   * - $p_action the $_REQUEST['p_action']
   * - $sub_action sa from suivi courrier but sc from Suivi client, fournisseur...
   *
   */
echo js_include('gestion.js');
require_once('class_itva_popup.php');
$pop_tva=new IPopup('popup_tva');
$pop_tva->title=_('Choix TVA');
$pop_tva->value='';
echo $pop_tva->input();

$supl_hidden='';
if( isset($_REQUEST['sc']))
  $supl_hidden.=HtmlInput::hidden('sc',$_REQUEST['sc']);
if( isset($_REQUEST['f_id']))
  $supl_hidden.=HtmlInput::hidden('f_id',$_REQUEST['f_id']);
if( isset($_REQUEST['sb']))
  $supl_hidden.=HtmlInput::hidden('sb',$_REQUEST['sb']);

/*--------------------------------------------------------------------------- */
/* We ask to generate the document */
/*--------------------------------------------------------------------------- */
if ( isset($_POST['generate']))
  {
    $act=new Action($cn);
    $act->fromArray($_POST);
    if ($act->ag_id == 0 )
      {
        $act->save();
        $ag_id=$act->ag_id;
      }
    else
      {
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

        if ( $act2->Update() == false )
	  {
            $sub_action="detail";
	  }
        else
	  {
            ShowActionList($cn,$base);
            $url="?$base&sa=detail&ag_id=".$act2->ag_id.'&'.dossier::get();
            echo '<p><a class="mtitle" href="'.$url.'">'.hb(_('Action sauvée').'  : '.$act2->ag_ref).'</a></p>';
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

        echo JS_LEDGER;
        // Add hidden tag
        echo '<form  enctype="multipart/form-data" action="commercial.php" method="post"">';

        echo dossier::hidden();
        $act->ag_comment="";
        if (isset($_REQUEST['qcode_dest'])) $act->qcode_dest=$_REQUEST['qcode_dest'];
        echo $act->Display('NEW',false,$base,$retour);

        echo '<input type="hidden" name="p_action" value="'.$p_action.'">';
        echo '<input type="hidden" name="sa" value="save_action_st2">';
        echo '<input type="submit" class="button" name="save_action_st2" value="'._('Enregistrer').'">';
        echo '<input type="submit" class="button" name="generate" value="'._('Génère le document').'"></p>';
        echo $supl_hidden;
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
    echo $supl_hidden;
    echo HtmlInput::hidden('p_action',$_REQUEST['p_action']);
    echo dossier::hidden();
    echo JS_LEDGER;
    echo $act->Display('UPD',false,$base,$retour);
    echo '<input type="hidden" name="p_action" value="'.$p_action.'">';
    echo '<input type="hidden" name="sa" value="update">';
    echo HtmlInput::submit("save","Sauve");
    echo HtmlInput::submit("add_action_here",_("Ajoute une action à celle-ci"));
    echo HtmlInput::submit("delete",_("Efface cette action"),' onclick="return confirm(\''._("Vous confirmez l\'effacement").'\')" ');
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
    ShowActionList($cn,$base);
    if ( isset( $act->ag_ref) )
      echo hb(_('Action ').$act->ag_ref._(' effacée'));
    exit();
  }

//--------------------------------------------------------------------------------
// Show a list of the action
if ( $sub_action == "list" )
  {
    ShowActionList($cn,$base);
  }
//--------------------------------------------------------------------------------
// Add an action
if ( $sub_action == "add_action" )
  {
    $act=new Action($cn);
    $act->fromArray($_POST );
    $act->ag_id=0;
    $act->d_id=0;
    echo '<div class="content">';
    echo JS_LEDGER;
    // Add hidden tag
    echo '<form method="post" action="commercial.php" name="form_add" id="form_add" enctype="multipart/form-data" >';
    echo $supl_hidden;
    echo dossier::hidden();


    $act->ag_comment=(isset($_POST['ag_comment']))?Decode($_POST['ag_comment']):"";
    if (isset($_REQUEST['qcode'])) $act->qcode_dest=$_REQUEST['qcode'];
    echo $act->Display('NEW',false,$base,$retour);

    echo '<input type="hidden" name="p_action" value="'.$p_action.'">';
    echo '<input type="hidden" name="sa" value="save_action_st2">';
    echo '<input type="hidden" name="save_action_st2" value="save_action_st2">';
    echo '<input type="submit" class="button" name="save_action_st2" value="'._('Enregistrer').'">';
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
    ShowActionList($cn,$base);
    $url="?$base&sa=detail&ag_id=".$act->ag_id.'&'.dossier::get();
    echo '<p><a class="mtitle" href="'.$url.'">'.hb('Action Sauvée  : '.$act->ag_ref).'</a></p>';
  }
//---------------------------------------------------------------------
function ShowActionList($cn,$p_base)
{
  // show the search menu
  ?>
  <div id="search_action" style="width:50%;text-align:right;clear:both;display:block;">
    <fieldset>
    <legend>
    <?=_('Recherche avancée')?>
    </legend>
    <form method="get" action="commercial.php">
    <?php
    echo dossier::hidden();
  $a=(isset($_GET['query']))?$_GET['query']:"";
  $qcode=(isset($_GET['qcode']))?$_GET['qcode']:"";

  $supl_hidden='';
  if( isset($_REQUEST['sc']))
    $supl_hidden.=HtmlInput::hidden('sc',$_REQUEST['sc']);
  if( isset($_REQUEST['f_id']))
    {
      $supl_hidden.=HtmlInput::hidden('f_id',$_REQUEST['f_id']);
      $f=new Fiche($cn,$_REQUEST['f_id']);
      $supl_hidden.=HtmlInput::hidden('qcode_dest',$f->get_quick_code());
    }
  if( isset($_REQUEST['sb']))
    $supl_hidden.=HtmlInput::hidden('sb',$_REQUEST['sb']);

  echo JS_LEDGER;
  $w=new ICard();
  $w->name='qcode';
  $w->value=$qcode;
  $w->extra="all";
  $w->typecard='all';
  $w->jrn=0;
  $w->table=0;
  $list=$cn->make_list("select fd_id from fiche_def where frd_id in (4,8,9,14,15,16,25)");
  $w->extra=$list;
  echo _('Fiche').' :'.$w->search().$w->input().'<br/>';
  printf (_('Titre ou référence').': <input class="input_text" type="text" name="query" value="%s">',
	  $a);
  echo '<br/>';

  /* type of documents */
  $type_doc=new ISelect ('tdoc');
  $aTDoc=$cn->make_array('select dt_id,dt_value from document_type');
  $aTDoc[]=array('value'=>'-1','label'=>_('Tous les types'));
  $type_doc->value=$aTDoc;
  $type_doc->selected=(isset ($_GET['tdoc']))?$_GET['tdoc']:-1;
  echo 'Type de document';
  echo $type_doc->input();
  echo '<br>';

  $see_all=new ICheckBox('see_all');
  $see_all->selected= (isset($_REQUEST['see_all']))?true:false;
  echo _('les actions fermées aussi:').$see_all->input().'<br/>';
  $my_action=new ICheckBox('all_action');
  $my_action->selected= (isset($_REQUEST['all_action']))?true:false;
  echo _('affecté à d\'autre:').$my_action->input().'<br/>';
  ?>
    <input type="submit" class="button" name="submit_query" value="<?=_('recherche')?>">
       <input type="hidden" name="sa" value="list">
       <input type="hidden" name="p_action" value="<?=$_REQUEST['p_action']?>">
       <?=$supl_hidden?>
       </form>

       </fieldset>
       </div>

       <div class="content" >
       <span style="float:left">
       <form  method="get" action="commercial.php">
       <?php echo dossier::hidden();
  ?>
       <input type="submit" class="button" name="submit_query" value="<?=_("Ajout Action")?>">
	  <input type="hidden" name="p_action" value="<?=$_REQUEST['p_action']?>">
	  <input type="hidden" name="sa" value="add_action">
	  <?=$supl_hidden?>
	  <input id="bt_search" type="button" class="button" onclick="toggleShow(this,'<?=_('Afficher Recherche')?>','<?=_('Cache Recherche')?>','search_action')" value="">

	  <script language="JavaScript">
	  <?php
	  /* if criteria then show the search otherwise hide it */
	  if ( (isset($_GET['qcode']) && trim($_GET['qcode']) != '') ||
	       (isset($_GET['query']) && trim($_GET['query']) != '') ||
	       isset ($_GET['all_action']) ||
	       isset ($_GET['see_all']) ||
	       (isset ($_GET['tdoc']) && trim($_GET['tdoc']) !='-1'))
	    {
	      /*  nothing */
	    }
	  else
	    {
	      ?>
	      toggleShow($('bt_search'),'<?=_('Afficher Recherche')?>','<?=_('Cache Recherche')?>','search_action')
		<?php
		}
  ?>
	  function toggleShow(p_but,p_msg_show,p_msg_hide,p_div)
	  {
	    try
	      {
		var detail=g(p_div);
		if (detail.style.display=='block' )
		  {
		    $(p_but).value=p_msg_show;
		    detail.style.display='none';
		  }
		else
		  {
		    $(p_but).value=p_msg_hide;
		    detail.style.display='block';
		  }

	      }
	    catch (error)
	    {
	      alert(error);
	    }

	  }

	  </script>


	      </form>
	      </span>

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
	  if (isset($_GET['tdoc']) && $_GET['tdoc'] != -1 )
	    {
	      $query .= ' and dt_id = '.Formatstring($_GET['tdoc']);
	    }
	  if ( ! isset($_REQUEST['see_all']))      $query .= ' and ag_state in (2,3) ';
	  if ( ! isset($_REQUEST['all_action']))      $query .= " and (ag_owner='".$_SESSION['g_user']."' or ag_dest='".$_SESSION['g_user']."')";
	  $r=$act->myList($p_base,"",$query.$str);
	  echo $r;
}
