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
   * - $sub_action sa from suivi courrier but sc from Suivi client, fournisseur...
   *
   */

$supl_hidden='';
if( isset($_REQUEST['sc']))
  $supl_hidden.=HtmlInput::hidden('sc',$_REQUEST['sc']);
if( isset($_REQUEST['f_id']))
  $supl_hidden.=HtmlInput::hidden('f_id',$_REQUEST['f_id']);
if( isset($_REQUEST['sb']))
  $supl_hidden.=HtmlInput::hidden('sb',$_REQUEST['sb']);
  $supl_hidden.=HtmlInput::hidden('ac',$_REQUEST['ac']);


/*--------------------------------------------------------------------------- */
/* We ask to generate the document */
/*--------------------------------------------------------------------------- */
if ( isset($_POST['generate']))
  {
    $act=new Follow_Up($cn);
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
    $act->generate_document($_POST['doc_mod'],$_POST);
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
        $act2=new Follow_Up($cn);
        $act2->fromArray($_POST );
		 $sub_action="detail";
		if ( $_POST["new_ref"] != "0")
		{
			$act2->add_depend($_POST['new_ref']);
		}
		if ( isset ($_POST['sup_dep']))
		{
			$act2->suppress_depend($_POST['sup_dep']);
		}
         $act2->Update();
      }
    //----------------------------------------------------------------------
    // Add a related action
    //----------------------------------------------------------------------
    if ( isset ($_POST['add_action_here']) )
      {
        $act=new Follow_Up($cn);


        //----------------------------------------
        // puis comme ajout normal (copier / coller )
        $act->fromArray($_POST);
        $act->ag_id=0;
        $act->d_id=0;
        $act->ag_ref_ag_id=$_POST['ag_id'];

        echo '<div class="content">';

        // Add hidden tag
        echo '<form  enctype="multipart/form-data" action="do.php" method="post"">';

        echo dossier::hidden();
        $act->ag_comment="";
        if (isset($_REQUEST['qcode_dest'])) $act->qcode_dest=$_REQUEST['qcode_dest'];
        echo $act->Display('NEW',false,$base,$retour);

        echo '<input type="hidden" name="ac" value="'.$_REQUEST['ac'].'">';
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
    $act=new Follow_Up($cn);
    $act->ag_id=$ag_id;
	$act->suppress=1;
    echo $act->get();
    $act->ag_comment=Decode($act->ag_comment);
    echo '<form  enctype="multipart/form-data"  class="print" action="do.php"  method="post"   >';
    echo $supl_hidden;
    echo HtmlInput::hidden('ac',$_REQUEST['ac']);
    echo dossier::hidden();
    echo $act->Display('UPD',false,$base,$retour);
    echo '<input type="hidden" name="sa" value="update">';
    echo HtmlInput::submit("save","Sauve");
    echo HtmlInput::submit("add_action_here",_("Ajoute une action à celle-ci"));
    echo HtmlInput::submit("delete",_("Efface cette action"),' onclick="return confirm(\''._("Vous confirmez l\'effacement").'\')" ');
	echo $retour;
    echo '</form>';
    echo '</div>';

  }
//-------------------------------------------------------------------------------
// Delete an action
if ( $sub_action == 'delete' )
  {
    // confirmed
    $cn->start();
    $act=new Follow_Up($cn);
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
    $act=new Follow_Up($cn);
    $act->fromArray($_POST );
    $act->ag_id=0;
    $act->d_id=0;
    echo '<div class="content">';
    // Add hidden tag
    echo '<form method="post" action="do.php" name="form_add" id="form_add" enctype="multipart/form-data" >';
    echo $supl_hidden;
    echo dossier::hidden();


    $act->ag_comment=(isset($_POST['ag_comment']))?Decode($_POST['ag_comment']):"";
    if (isset($_REQUEST['qcode'])) $act->qcode_dest=$_REQUEST['qcode'];
    echo $act->Display('NEW',false,$base,$retour);

    echo '<input type="hidden" name="ac" value="'.$_REQUEST["ac"].'">';
    echo '<input type="hidden" name="sa" value="save_action_st2">';
    echo '<input type="hidden" name="save_action_st2" value="save_action_st2">';
    echo '<input type="submit" class="button" name="save_action_st2" value="'._('Enregistrer').'">';
    echo '</form>';

    echo   '</div>';
  }
//--------------------------------------------------------------------------------
// Save Follow_Up
// Stage 2 : Save the action + Files and generate eventually a document
//--------------------------------------------------------------------------------
if  ( $sub_action == "save_action_st2" )
  {
    $act=new Follow_Up($cn);
    $act->fromArray($_POST);
    $act->d_id=0;
    $act->ag_ref_ag_id=(isset($_POST['ag_ref_ag_id']))?$_POST['ag_ref_ag_id']:0;
    $act->md_id=(isset($_POST['gen_doc']))?$_POST['gen_doc']:0;

    // insert into action_gestion
    echo $act->save();
    $url="?$base&sa=detail&ag_id=".$act->ag_id.'&'.dossier::get();
    echo '<p><a class="mtitle" href="'.$url.'">'.hb('Action Sauvée  : '.$act->ag_ref).'</a></p>';


    ShowActionList($cn,$base);
    $url="?$base&sa=detail&ag_id=".$act->ag_id.'&'.dossier::get();
    echo '<p><a class="mtitle" href="'.$url.'">'.hb('Action Sauvée  : '.$act->ag_ref).'</a></p>';
  }
//---------------------------------------------------------------------
function ShowActionList($cn,$p_base)
{
  // show the search menu
  ?>
  <div id="search_action" class="op_detail_frame" style="position:absolute;display:none;margin-left:120px;width:70%;clear:both;z-index:2;height:auto;border:1px #000080 solid">
	 <? echo HtmlInput::anchor_hide('Fermer','$(\'search_action\').style.display=\'none\';'); ?>
    <h2 class="info">
    <?=_('Recherche avancée')?>
    </h2>
    <form method="get" action="do.php" style="padding:10px">
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
    $supl_hidden.=HtmlInput::hidden('ac',$_REQUEST['ac']);

  $w=new ICard();
  $w->name='qcode';
  $w->value=$qcode;
  $w->extra="all";
  $w->typecard='all';
  $w->jrn=0;
  $w->table=0;
  $list=$cn->make_list("select fd_id from fiche_def where frd_id in (4,8,9,14,15,16,25)");
  $w->extra=$list;


  /* type of documents */
  $type_doc=new ISelect ('tdoc');
  $aTDoc=$cn->make_array('select dt_id,dt_value from document_type order by dt_value');
  $aTDoc[]=array('value'=>'-1','label'=>_('Tous les types'));
  $type_doc->value=$aTDoc;
  $type_doc->selected=(isset ($_GET['tdoc']))?$_GET['tdoc']:-1;

	// date
  $start=new IDate('date_start');
  $start->value=(isset($_GET['date_start']))?$_GET['date_start']:"";
  $end=new IDate('date_end');
  $end->value=(isset($_GET['date_end']))?$_GET['date_end']:"";

  $see_all=new ICheckBox('see_all');
  $see_all->selected=(isset($_GET['see_all']))?true:false;
  $my_action=new ICheckBox('all_action');
  $my_action->selected=(isset($_GET['all_action']))?true:false;
  // select profile
   $aAg_dest=$cn->make_array("select  p_id as value, ".
                                    "p_name as label ".
                                    " from profile order by 2");
$ag_dest=new ISelect();
$ag_dest->name="ag_dest";
$aAg_dest[]=array('value'=>0,'label'=>'Public');
$ag_dest->value=$aAg_dest;
$ag_dest->selected=(isset($_GET["ag_dest"]))?$_GET["ag_dest"]:0;
$str_ag_dest=$ag_dest->input();

  ?>
		<table style="width:100%">
			<tr>
			<td style="width:180px;text-align:right"><? echo _('Destinataire') ?></td>
			<td ><?=$w->input().$w->search()?></td>
			<tr>
			<tr>
				<td style="text-align:right" ><?=_("Profile")?></td>
				<td><?=$str_ag_dest?></td>
			</tr>
			<td style="text-align:right"><?	printf (_('contenant le mot'))?></td>
			<td ><input class="input_text" style="width:100%" type="text" name="query" value="<?=$a?>"></td>
			</tr>
			<tr>
					<td style="text-align:right"><?=_('Type de document')?></td>
					<td><? echo $type_doc->input();?></td>
			</tr>
			<tr>
				<td style="text-align:right">
					<? printf (_("Après le "))?>
				</td>
				<td >
					<?=$start->input()?>
				</td>
			</tr>
			<tr>
				<td style="text-align:right"><?=_('Avant le')?></td>
				<td>
					<?=$end->input()?>
				</td>
				</tr>
			</tr>
			<tr>
			<td style="text-align:right">
				<?=_('inclure les actions fermées ')?>
			</td>
			<td>
				<?=$see_all->input()?>
			</td>
			</tr>
			<tr>
				<td style="text-align:right"><?=_('les actions  affectées à d\'autres')?></td>
				<td><?=$my_action->input()?>
				</td>
			</tr>
  </table>
    <input type="submit" class="button" name="submit_query" value="<?=_('recherche')?>">
       <input type="hidden" name="sa" value="list">

       <?=$supl_hidden?>
<?  echo HtmlInput::button_anchor(_('Fermer'),'javascript:void(0)','fsearch_form','onclick="$(\'search_action\').style.display=\'none\';"'); ?>
       </form>
       </div>

       <div class="content" >
       <div>
       <form  method="get" action="do.php">
       <?php echo dossier::hidden();
  ?>
       <input type="submit" class="button" name="submit_query" value="<?=_("Ajout Action")?>">
	  <input type="hidden" name="ac" value="<?=$_REQUEST['ac']?>">
	  <input type="hidden" name="sa" value="add_action">
	  <?=$supl_hidden?>
	  <input id="bt_search" type="button" class="button" onclick="$('search_action').style.display='block'" value="<?=_('Recherche')?>">



	      </form>
	      </div>

	      <?php
	      // show the  action in
	      $act=new Follow_Up($cn);
	  /*! \brief
	   *  \note The field 'recherche' is   about a part of the title or a ref. number
	   */
	  $query="";

	  if ( isset($_REQUEST['query']) )
	    {
	      // if a query is request build the sql stmt
	      $query="and (ag_title ~* '".sql_string($_REQUEST['query'])."' ".
		"or ag_ref ='".trim(sql_string($_REQUEST['query'])).
		"' or ag_comment ~* '".trim(sql_string($_REQUEST['query']))."'".
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
	  if ( ! $see_all->selected )      $query .= ' and ag_state in (2,3) ';
	  if ( ! $my_action->selected )     {
		  $query .=" and (ag_owner='".$_SESSION['g_user']."' or ag_dest in (select p_id from profile_user where user_name='".$_SESSION['g_user']."') )";
	  }
	  if (isset ($_GET['date_start']) && isDate($_GET['date_start'])!= null ) {
		  $date_start=$_GET['date_start'];
		  $query.=" and ag_timestamp >= to_date('$date_start','DD.MM.YYYY')";
	  }
	  if (isset ($_GET['date_end']) && isDate($_GET['date_end'])!= null ) {
		  $date_end=$_GET['date_end'];
		  $query.=" and ag_timestamp <= to_date('$date_end','DD.MM.YYYY')";
	  }
	  if ( isset($_GET['ag_dest']))
	  {
		  if ( $_GET['ag_dest']==0)
			$query .= "and ag_dest is null ";
		  else
			$query.= " and ag_dest = ".sql_string($_GET['ag_dest']);
	  }
	  $r=$act->myList($p_base,"",$query.$str);
	  echo $r;
}
