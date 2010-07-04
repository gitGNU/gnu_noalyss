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
 * \brief Manage the attributs 
 */
require_once('class_fiche_attr.php');

echo js_include('card.js');

$fa=new Fiche_Attr($cn);
// echo '<div id="debug" style="position:absolute;top:0;right:0;border:1px solid red;"></div>';
/////////////////////////////////////////////////////////////////////////////
// If data are post we save them first
/////////////////////////////////////////////////////////////////////////////
if ( isset($_POST['save'])) {
  $ad_id=$_POST['ad_id'];
  $ad_text=$_POST['desc'];
  $ad_type=$_POST['type'];

  try {
    $cn->start();
    for ($e=0;$e<count($ad_id);$e++){
      $fa->set_parameter('id',$ad_id[$e]);
      $fa->set_parameter('desc',$ad_text[$e]);
      $fa->set_parameter('type',$ad_type[$e]);
      if ( trim($ad_text[$e])!='' && trim($ad_type[$e])!='')
	$fa->save();
    }
    $cn->commit();
  }
  catch (Exception $e)  
    { $e->getMessage();$cn->rollback();}
}
/* show list of existing */
$gDossier=dossier::id();
$array=$fa->seek();

$select_type=new ISelect('type[]');
$select_type->table=0;
$desc=new IText('desc[]');
$desc->size=50;
$select_type->value=array(
		     array('value'=>'text','label'=>'Texte'),
		     array('value'=>'numeric','label'=>'Nombre'),
		     array('value'=>'date','label'=>'Date')
		     );
$remove=new IButton('rmfa');
$remove->label='Effacer';
echo '<div class="content">';
echo '<form method="post">';

echo HtmlInput::hidden('sa','fat');
echo HtmlInput::hidden('p_action','divers');
echo '<table id="tb_rmfa">';

for ($e=0;$e<count($array);$e++) {
  $row=$array[$e];
  $r='';
  $r.=td(HtmlInput::hidden('ad_id[]',$row->get_parameter('id')));
  $select_type->selected=$row->get_parameter('type');
  $desc->value=$row->get_parameter('desc');

  if ( $row->get_parameter('id')>= 9000) {
    $select_type->readOnly=false;
    $desc->readOnly=false;
    $r.=td($desc->input());
    $r.=td($select_type->input());

    $remove->javascript=sprintf('if ( confirm(\'Vous  confirmez ?\')) { removeCardAttribut(%d,%d,\'tb_rmfa\',this);}',
				$row->get_parameter('id'),$gDossier);
    $msg='<span class="notice"> Attention : effacera les données qui y sont liées </span>';
    $r.=td($remove->input().$msg);
  } else {
    $select_type->readOnly=true;
    $desc->readOnly=true;
    $r.=td($desc->input().HtmlInput::hidden('type[]',''));
    $r.=td($select_type->input());
    $r.=td("");
  }




  echo tr($r);
  
}
$desc->readOnly=false;$select_type->readOnly=false;
$desc->value='';
$select_type->selected=-1;
$r=td(HtmlInput::hidden('ad_id[]','0'));
$r.=td($desc->input());
$r.=td($select_type->input());
echo tr($r);

echo '</table>';
echo HtmlInput::submit('save','Sauver');
echo '</form>';
echo '</div>';
