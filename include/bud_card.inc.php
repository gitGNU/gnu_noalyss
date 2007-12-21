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
 * \brief Manage the card for the budget module
 */
require_once ('class_bud_card.php');
require_once ('class_widget.php');


echo '<form method="get">';
$wHypo=new widget("select","","bh_id");
$wHypo->value=make_array($cn,"select bh_id,bh_name from bud_hypothese");
$wHypo->selected=(isset($_REQUEST['bh_id']))?$_REQUEST['bh_id']:"";
$wHypo->javascript='onChange="this.form.submit();"';
echo $wHypo->IOValue();
echo widget::submit_button('recherche','recherche');
echo dossier::hidden();
echo widget::hidden("p_action","fiche");
echo '</form>';
echo '<hr>';
if ( ! isset($_REQUEST['bh_id'])) {
	exit();
}
$msg="";

if ( isset($_POST['remove'])){ 
  $obj=new Bud_Card($cn);
  $obj->load_from_array($_POST);
  
  $msg=$obj->delete();
 }

if ( isset($_POST['add'])){ 
  $obj=new Bud_Card($cn);
  $obj->load_from_array($_POST);
  
  $msg=$obj->add();
 }

if ( isset($_POST['update'])){ 
  $obj=new Bud_Card($cn);
  $obj->load_from_array($_POST);

  $msg=  $obj->update();

 }
$list=Bud_Card::get_list($cn,$_REQUEST['bh_id']);

echo '<div class="lmenu">';
$str_dossier=dossier::get();
$bc_id=(isset($_REQUEST['bc_id']))?$_REQUEST['bc_id']:-1;
if (! empty ($list)) {
  $row=array();
  foreach ($list as $r ) {
    $row[]=array('?'.$str_dossier.'&p_action=fiche&sa=detail&bc_id='.$r->bc_id.'&bh_id='.$r->bh_id,
		 $r->bc_code,
		 $r->bc_description,
		 $r->bc_id
		 );
    
  }
  echo ShowItem($row,'V','mtitle','mtitle',$bc_id);
 }
echo widget::button_href('Ajout','?'.$str_dossier.'&sa=add&p_action=fiche&bh_id='.$_REQUEST['bh_id']);
echo '</div>';


$sa=( isset($_REQUEST['sa']))?$_REQUEST['sa']:'';
echo $msg;
//------------------------------------------------------------------------------
// Add
//------------------------------------------------------------------------------
if ( $sa == "add" ) {
  echo '<div class="u_redcontent">';
  $obj=new Bud_Card($cn);
  $obj->bh_id=$_REQUEST['bh_id'];
  echo '<form method="post">';
  echo $obj->form();
  echo widget::submit_button('add','Ajout');
  echo widget::hidden("p_action","fiche");
  echo dossier::hidden();
  echo '</form>';
  echo '</div>';
 }


//------------------------------------------------------------------------------
// Detail
//------------------------------------------------------------------------------
if ( $sa == "detail" ) {
  $obj=new Bud_Card($cn,$_GET['bc_id']);
  $obj->load();
  echo '<div class="u_redcontent">';
  echo '<form method="post">';
  echo dossier::hidden();

  echo $obj->form();
  echo widget::submit_button('remove','Effacer','onClick="return confirm(\'Vous confirmez cet effacement ?\')"');
  echo widget::submit_button('update','Mise &agrave jour');

  echo '</div>';
 }

