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
require_once ('class_bud_synthese_hypo.php');

echo '<div class="content">';
$obj=new Bud_Synthese_Hypo($cn);
$bh_id=(isset($_REQUEST['bh_id']))?$_REQUEST['bh_id']:'';




echo '<form METHOD="GET">';
$obj->from_array($_GET);
echo $obj->form();
echo widget::submit('display','Afficher');
echo widget::hidden('do','ga');
echo widget::hidden('p_action','synthese');
echo '</form>';

if ( isset($_GET['display'])) {
  $obj->from_array($_GET);
  $res=$obj->load();
  echo $obj->display_html($res);
  echo '<h2 class="info">Resume</h2>';
  echo $obj->summary_html($res);
  echo '<form method="GET" action="bud_csv.php">';
  echo $obj->hidden();
  echo widget::hidden('do','ga');
  echo widget::hidden('p_action','synthese');
  echo widget::hidden('bh_id',$obj->bh_id);
  echo dossier::hidden();
  echo widget::submit('display','Export CSV');
  echo '</form>';
 }

echo '</div>';
