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

/* !\file 
 */

/* \brief manage the group
 *
 */
require_once ('class_anc_group.php');
require_once ('class_widget.php');
$r=new Anc_Group($cn);


//----------------------------------------------------------------------
// REMOVE
if ( isset ($_POST['remove'])) {
  if (isset($_POST['ck'] )) {
      foreach ($_POST['ck'] as $m ) {
	$obj=new Anc_Group($cn);
	$obj->ga_id=$m;
	$obj->remove();
      }
    }
 }

//----------------------------------------------------------------------
// INSERT
if ( isset($_POST['add'])) {
  $obj=new Anc_Group($cn);
  $obj->get_from_array($_POST);
  $obj->insert();
 }
$array=$r->myList();

echo '<div class="u_content">';
echo '<form method="post">';
echo dossier::hidden();
echo '<table style="border: 2px outset blue; width: 100%;"  >';
echo '<tr> <th> Code </th><th>Plan </td><th>Description</th></tr>';
foreach ($array as $idx=>$m) {
  echo '<tr>';
  echo '<td>'.$m->ga_id.'</td>';
  echo '<td>'.$m->pa_name.'</td>';
  echo '<td>'.$m->ga_description.'</td>';
  echo '<td> Effacer <input type="Checkbox" name="ck[]" value="'.$m->ga_id.'">'.'</td>';
  echo '</tr>';
}
$w=new widget("text");
$val_pa_id=make_array($cn,"select pa_id,pa_name from plan_analytique");
$wPa_id=new widget ("select");
$wPa_id->value=$val_pa_id;

echo "<td>".$w->IOValue("ga_id")."</td>";
echo "<td>".$wPa_id->IOValue("pa_id")."</td>";
echo "<td>".$w->IOValue("ga_description").
widget::submit_button('add','Ajouter').
"</td>";;

echo '</table>';

echo "<hr>";
echo widget::submit_button('remove','Effacer');

echo '</div>';
