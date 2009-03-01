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
 * \brief Manage the hypothese for the budget module
 */
require_once ('class_bud_hypo.php');


if ( isset($_POST['remove'])){ 
  $obj=new Bud_Hypo($cn);
  $obj->get_from_array($_POST);
  $obj->delete();

 }

if ( isset($_POST['add'])){ 
  $obj=new Bud_Hypo($cn);
  $obj->get_from_array($_POST);
  $obj->add();

 }

if ( isset($_POST['update'])){ 
  $obj=new Bud_Hypo($cn);
  $obj->get_from_array($_POST);
  $obj->update();

 }

$list=Bud_Hypo::get_list($cn);

echo '<div class="lmenu">';
$str_dossier=dossier::get();
$bh_id=(isset($_REQUEST['bh_id']))?$_REQUEST['bh_id']:-1;
if (! empty ($list)) {
  $row=array();
  foreach ($list as $r ) {
    $row[]=array('?'.$str_dossier.'&p_action=hypo&sa=detail&bh_id='.$r->bh_id,
	       h($r->bh_name),
	       h($r->bh_description),
	       $r->bh_id);
    
  }
  echo ShowItem($row,'V','mtitle','mtitle',$bh_id);
 }
echo widget::button_href('Ajout','?'.$str_dossier.'&sa=add&p_action=hypo');
echo '</div>';


$sa=( isset($_REQUEST['sa']))?$_REQUEST['sa']:'';

//------------------------------------------------------------------------------
// Add
//------------------------------------------------------------------------------
if ( $sa == "add" ) {
  echo '<div class="u_redcontent">';
  $obj=new Bud_Hypo($cn);
  echo '<form method="post">';
  echo $obj->form();
  echo widget::submit('add','Ajout');
  echo '</form>';
  echo '</div>';
 }


//------------------------------------------------------------------------------
// Detail
//------------------------------------------------------------------------------
if ( $sa == "detail" ) {
  $obj=new Bud_Hypo($cn,$_GET['bh_id']);
  $obj->load();
  echo '<div class="u_redcontent">';
  echo '<form method="post">';
  echo $obj->form(1);
  echo widget::submit('remove','Effacer','onClick="return confirm(\'Vous confirmez cet effacement ?\')"');
  echo widget::submit('update','Mise &agrave jour');

  echo '</div>';
 }



