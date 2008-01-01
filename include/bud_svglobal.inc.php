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
require_once ('class_bud_synthese_group.php');

echo '<div class="u_content">';
$obj=new Bud_Synthese_Group($cn);
$bh_id=(isset($_REQUEST['bh_id']))?$_REQUEST['bh_id']:'';
$obj->from_array($_GET);

// if ( ! isset ($_GET['detail']) && ! isset ($_GET['display'])) {
  echo '<form METHOD="GET">';
  echo $obj->select_hypo();
  echo widget::submit_button('detail','Accepter');
  echo widget::hidden('do','vglobal');
 echo widget::hidden('p_action','synthese');
 echo '</form>';
// }


if ( isset( $_GET['bh_id'])) {
  echo '<form METHOD="GET">';

  $obj->bh_id=$_GET['bh_id'];
  echo $obj->form();
  echo widget::submit_button('display','Afficher');
  echo widget::hidden('do','vglobal');
  echo widget::hidden('p_action','synthese');
  echo widget::hidden('bh_id',$obj->bh_id);
  echo '</form>';


}

if ( isset($_GET['display'])) {
  $obj->from_array($_GET);
  $res=$obj->load();
  echo $obj->display_html($res);
  echo '<form method="GET" action="bud_csv.php">';
  echo $obj->hidden();
  echo widget::hidden('do','vglobal');
  echo widget::hidden('p_action','synthese');
  echo widget::hidden('bh_id',$obj->bh_id);
  echo dossier::hidden();
  echo widget::submit_button('display','Export CSV');
  echo '</form>';
 }

echo '</div>';
