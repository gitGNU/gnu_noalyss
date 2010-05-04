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
/* $Revision: 2729 $ */
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
  /*!\brief include from adm.inc.php and concerned only the customer card and
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
require_once('class_ipopup.php');
require_once('class_admin.php');
echo js_include('accounting_item.js');
echo js_include('prototype.js');
echo js_include('scriptaculous.js');
echo js_include('effects.js');
echo js_include('controls.js');
echo js_include('dragdrop.js');
echo JS_CARD;
echo JS_AJAX_FICHE;
echo HtmlInput::phpsessid();
echo ICard::ipopup('ipop_newcard');
$ip_cat=new IPopup('ipop_cat');
$ip_cat->title=_('Ajout d\'une catégorie');
$ip_cat->value='';
echo $ip_cat->input();
echo IPoste::ipopup('ipop_account');
$search_card=new IPopup('ipop_card');
$search_card->title=_('Recherche de fiche');
$search_card->value='';
echo $search_card->input();
echo ICard::ipopup('ipopcard');

$low_action=(isset($_REQUEST['sb']))?$_REQUEST['sb']:"list";
/*! \file
 * \brief Called from the module "Gestion" to manage the customer
 */
$User->can_request(GECUST);
$href=basename($_SERVER['PHP_SELF']);

// by default open liste
if ( $low_action  == "" ) 
      $low_action="list";


//-----------------------------------------------------
// Remove a card
//-----------------------------------------------------
if ( isset($_POST['delete'] ) )
{
  if ( $User->check_action(FICADD) == 0 ) {
    alert('Vous  ne pouvez pas enlever de fiche');
    return;
  }

  $f_id=$_REQUEST['f_id'];

  $fiche=new Customer($cn,$f_id);
  $fiche->remove();
  $low_action="list";

}

//-----------------------------------------------------
//    list of customer
//-----------------------------------------------------
if ( $low_action == "list" )
{

?>
<div class="content">
<span  style="position:float;float:left">
<form method="get" action="<?php echo $href; ?>">
<?php
	echo dossier::hidden();  
   $a=(isset($_GET['query']))?$_GET['query']:"";
   printf (_('Recherche').' <input type="text" name="query" value="%s">',
	   $a);
  $sel_card=new ISelect('cat');
  $sel_card->value=$cn->make_array('select fd_id, fd_label from fiche_def '.
	' where  frd_id='.FICHE_TYPE_ADM_TAX.
	' order by fd_label ',1);
  $sel_card->selected=(isset($_GET['cat']))?$_GET['cat']:-1;
  $sel_card->javascript=' onchange="submit(this);"';
  echo _('Catégorie :').$sel_card->input();

?>
<input type="submit" name="submit_query" value="<?=_('recherche')?>">
<input type="hidden" name="p_action" value="adm">
</form>
</span>
<?php  
   $adm=new Admin($cn);
 $search=(isset($_GET['query']))?$_GET['query']:"";
  $sql="";
  if ( isset($_GET['cat'])) {
    if ( $_GET['cat'] != -1) $sql=sprintf(" and fd_id = %d",$_GET['cat']);
  }

 echo '<div class="content">';
 echo $adm->Summary($search,'adm',$sql);


 echo '<br>';
 echo '<br>';
 echo '<br>';
 /* Add button */
 $f_add_button=new IButton('add_card');
 $f_add_button->label=_('Créer une nouvelle fiche');
 $f_add_button->set_attribute('ipopup','ipop_newcard');
 $f_add_button->set_attribute('win_refresh','yes');

 $f_add_button->set_attribute('type_cat',FICHE_TYPE_ADM_TAX);
 $f_add_button->javascript=" select_card_type(this);";
 echo $f_add_button->input();

 $f_cat_button=new IButton('add_cat');
 $f_cat_button->set_attribute('ipopup','ipop_cat');
 $f_cat_button->set_attribute('type_cat',FICHE_TYPE_ADM_TAX);
 $f_cat_button->label=_('Ajout d\'une catégorie');
 $f_cat_button->javascript='add_category(this)';
 echo $f_cat_button->input();
 echo '</div>';
 echo '</div>';


}
/*----------------------------------------------------------------------
 * Detail for a card, Suivi, Contact, Operation,... *
 * cc stands for customer card 
 *----------------------------------------------------------------------*/
if ( $low_action == 'detail') {
  /* Menu */
  require_once('adm_card.inc.php');
  exit();
}

if ( $low_action=="insert" )
{
  /* security : check if user can add card */
  if ( $User->check_action(FICADD) == 0 ) {
    alert('Vous  ne pouvez pas ajouter de fiche');
    return;
  }

  $customer=new Customer($cn);
  $customer->Save($_REQUEST['fd_id']);
  echo '<div class="content">';
  echo "<table>";
  echo $customer->Display(true);
  echo "</table>";
  $retour=new IAction();
  $retour->label="Retour";
  $retour->value="?p_action=adm&".dossier::get();
  echo $retour->input();
  echo '</div>';

}

html_page_stop();
?>
