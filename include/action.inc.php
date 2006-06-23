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

//////////////////////////////////////////////////////////////////////
// Action
//////////////////////////////////////////////////////////////////////
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
<div class="u_content">
<span>
<form method="get" action="commercial.php">
<?
   $a=(isset($_GET['query']))?$_GET['query']:"";
   printf ('<span>Titre : <input type="text" name="query" value="%s"></span>',
	   $a);
   $qcode=(isset($_GET['qcode']))?$_GET['qcode']:"";
   printf ('<span>Tiers QuickCode: <input type="text" name="qcode" value="%s"></span>',
	   $qcode);

?>
<input type="submit" name="submit_query" value="recherche">
<input type="hidden" name="sa" value="list">
<input type="hidden" name="p_action" value="suivi_courrier">
</form>
</span>

<?
?>
<span>
<form method="get" action="commercial.php">
<input type="submit" name="submit_query" value="Ajout Action">
<input type="hidden" name="p_action" value="suivi_courrier">
<input type="hidden" name="sa" value="add_action">
<input type="hidden" name="tiers" value=<? echo '"'.$qcode.'"';?>>
   <? // if called from another menu, url is set
   echo $h_url;
   echo $retour; ?>
</form>


</span>
<?
    // show the  action in 
    $act=new action($cn);
   
   $query=(isset ($_REQUEST['query']))?"and ag_title ~* '".FormatString($_REQUEST['query'])."'":"";
   $str="";
   if ( isset($_REQUEST['qcode'] )) 
     {
       // verify that qcode is not empty
       if ( strlen(trim($_REQUEST['qcode'] )) != 0 )
	 { 
	   $fiche=new Fiche($cn);
	   $fiche->GetByQCode($_REQUEST['qcode']);
	   $str=" and f_id= ".$fiche->id;
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
var_dump($_REQUEST);echo '<hr>';
if ( $sub_action == "" ) $sub_action="list";

// if correction is asked go to directly to add_action
if (isset($_POST['corr'] )) 
     $sub_action="add_action";

// if this page is called from another menu (customer, supplier,...)
// a button back is added
// TODO add function for generating url, hidden tags...
$retour="";
$h_url="";

if ( isset ($_REQUEST['url'])) 
{
     $retour=sprintf('<A HREF="%s"><input type="button" value="Retour"></A>',urldecode($_REQUEST['url']));
     $h_url=sprintf('<input type="hidden" name="url" value="%s">',urldecode($_REQUEST['url']));
}

//////////////////////////////////////////////////////////////////////
// Show a list of the action
if ( $sub_action == "list" )
     ShowActionList($cn,$retour,$h_url);
       
//////////////////////////////////////////////////////////////////////
// Add an action
if ( $sub_action == "add_action" ) 
{
  echo $retour;

  echo '<div class="u_redcontent">';
  echo JS_SEARCH_CARD;
  // Add hidden tag
  echo '<form name="RTEDemo" action="commercial.php?p_action=suivi_courrier" method="post" onsubmit="return submitForm();">';
  echo $h_url;
  $date=new widget("text");
  $date->name="ag_date";
  $date->value=(isset($_POST['ag_date']))?$_POST['ag_date']:"";
  echo "<p>Date : ";
  echo $date->IOValue()."</p>";
  // Tiers
  $W1=new widget("js_search");
  $W1->label="Tiers";
  $W1->name="tiers";
  $W1->value=isset($_REQUEST['tiers'])?$_REQUEST['tiers']:"";
  $W1->extra="8,9,14"; // filter on frd_id
  $W1->extra2=0;      // jrn = 0
  echo "<div class=\"no\">".$W1->IOValue();
  $client_label=new widget("span");
  $tiers_label="";
  // Retrieve name
  if ( $W1->value != "" ) 
    {
      $tiers=new fiche($cn);
      $tiers->GetByQCode($W1->value);
      $tiers_label=$tiers->strAttribut(1);

    }
  echo $client_label->IOValue("tiers_label",$tiers_label)."</div>";

  $doc_type=new widget("select");
  $doc_type->name="dt_id";
  $doc_type->value=make_array($cn,"select dt_id,dt_value from document_type where dt_id in (".ACTION.")");
  echo 'Type d\' action';
  $doc_type->selected=(isset($_POST['dt_id']))?$_POST['dt_id']:"";
  echo $doc_type->IOValue();

  // state 
  $doc_state=new widget("select");
  $doc_state->name="d_state";
  $doc_state->value=make_array($cn,"select s_id,s_value from document_state");
  $doc_state->selected=(isset($_POST['d_state']))?$_POST['d_state']:"";
  echo "Etat : ".$doc_state->IOValue();
  echo '<input type="hidden" name="p_action" value="suivi_courrier">';
  echo '<input type="hidden" name="sa" value="save_action_st1">';
  $title=new widget("text");
  $title->name="ag_title";
  $title->value=(isset($_POST['ag_title']))?$_POST['ag_title']:"";
  echo "<p>Titre  : ";
  echo $title->IOValue()."</p>";

?>

<? 
////////////////////////////////////////////////////////////////////////////////
// add here for who the action is taken (contact, client or supplier)

?>



<script language="JavaScript" type="text/javascript">
<!--
function submitForm() {
	//make sure hidden and iframe values are in sync before submitting form
	//to sync only 1 rte, use updateRTE(rte)
	//to sync all rtes, use updateRTEs
	updateRTE('ag_desc');
	//updateRTE();
	//change the following line to true to submit form
	return true;
}

//Usage: initRTE(imagesPath, includesPath, cssFile)
initRTE("images/", "", "");
//-->
</script>
<noscript><p><b>Javascript must be enabled to use this form.</b></p></noscript>
    Note Interne : 
<script language="JavaScript" type="text/javascript">
<!--
//Usage: writeRichText(fieldname, html, width, height, buttons, readOnly)
    <? $a=(isset($_POST['ag_desc']))?FormatString(urldecode($_POST['ag_desc'])):""; ?>
writeRichText('ag_desc', <? printf ("'%s'",$a); ?>, 520, 200, true, false);
<? echo_debug('action.inc',__LINE__, " A = ".$a); ?>
//-->
</script>

<input type="submit" name="save_action_st1" value="Sauver"></p>
</form>
</div>
<?
}
////////////////////////////////////////////////////////////////////////////////
// Save Action
// Stage 1 : show the result and confirm
////////////////////////////////////////////////////////////////////////////////
if  ( $sub_action == "save_action_st1" ) 
{
  $act=new action($cn);
  $act->ag_desc=$_POST['ag_desc'];
  $act->ag_date=$_POST['ag_date'];
  $act->d_state=$_POST['d_state'];
  $act->dt_id=$_POST['dt_id'];
  $act->qcode=$_POST['tiers'];
  $act->ag_title=$_POST['ag_title'];
  echo $act->Confirm();
}
////////////////////////////////////////////////////////////////////////////////
// Save Action
// Stage 2 : Save the action and propose to save a file
////////////////////////////////////////////////////////////////////////////////
if  ( $sub_action == "save_action_st2" ) 
{
  $act=new action($cn);
  $act->ag_desc=$_POST['ag_desc'];
  $act->ag_date=$_POST['ag_date'];
  $act->d_state=$_POST['d_state'];
  $act->dt_id=$_POST['dt_id'];
  $act->qcode=$_POST['tiers'];
  $act->ag_title=$_POST['ag_title'];
  $act->md_id=(isset($_POST['gen_doc']))?$_POST['gen_doc']:0;

  $act->gen=isset($_POST['p_gen'])?'on':'off';

  echo $act->SaveStage2();
  echo '<A HREF="commercial.php?p_action=suivi_courrier"><INPUT TYPE="BUTTON" VALUE="Retour Liste"></A>';
}

////////////////////////////////////////////////////////////////////////////////
// Save Document
// Stage 3 : Save the document
////////////////////////////////////////////////////////////////////////////////
if  ( $sub_action == "save_action_st3" ) 
{
  echo_debug("action.inc.php",__LINE__,'Stage 3');
  $act=new action($cn);
  $act->ag_id=$_POST['ag_id'];
  $d_id=(isset($_POST['d_id']))?$_POST['d_id']:0;
  echo $act->SaveStage3($d_id);
  
  ShowActionList($cn,$retour,$h_url);

}

