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

/* \brief Misc Operation for analytic accountancy
 *
 */
require_once("class_poste_analytic.php");
require_once ("class_widget.php");
require_once ("class_operation.php");
require_once ("class_groupop.php");


$pa=new PlanAnalytic($cn);
$m=$pa->get_list();
echo '<div class="u_subt2menu">';
echo '<table>';
echo '<tr>';
if ( isset ($_REQUEST['pa_id'])){
  $pa_id=$_REQUEST['pa_id'];
 } else $pa_id=-1;
foreach ($m as $line)
{
  if ( $line['id'] == $pa_id ) {
	echo '<td class="selectedcell">'.
	  $line['name'].
	  '</td>';

  } else {
	echo '<td class="mtitle">'.
	  '<a class="mtitle" href="?p_action=ca_od&sa=pa&pa_id='.$line['id'].'&new"> '.
	  $line['name'].
	  '</a>'.'</td>';
  }
}
echo '</tr>';
echo '</table>';
echo '</div>';
if ( ! isset($_REQUEST['pa_id']) )
  exit();
//----------------------------------------------------------------------
// show the left menu
//----------------------------------------------------------------------
echo '
<div class="lmenu">
<table>
<tr>
    <td  class="mtitle" >
     <A class="mtitle" HREF="?p_action=ca_od&pa_id='.$_REQUEST['pa_id'].'&new"> Nouveau </A>
 </td>
</tr>
<tr>
    <td  class="mtitle" >
     <A class="mtitle" HREF="?p_action=ca_od&pa_id='.$_REQUEST['pa_id'].'&see"> Liste op&eacute;rations </A
 </td>
</tr>
</table>
</div>';
//----------------------------------------------------------------------
// the pa_id is set 
//
//----------------------------------------------------------------------
if ( isset($_GET['see'])) {
  // Show the list for the period
  // and exit
  //-----------------------------
  $a=new operation($cn);
  $a->pa_id=$_REQUEST['pa_id'];
  echo '<div class="u_redcontent">';
   echo $a->get_list();
   echo '</div>';
  exit();
 }
if ( isset($_POST['save'])) {
  // record the operation and exit
  // and exit
  //-----------------------------
  echo '<div class="u_redcontent">'.
	'Op&eacute;ration sauv&eacute;e';
  $a=new groupop($cn);
  $a->pa_id=$_POST['pa_id'];
  $a->from_POST();
  $a->save();
  echo $a->show();
  echo '</div>';
  exit();
 }

if ( isset($_GET['new'])) {
	//show the form for entering a new operation
	//------------------------------------------
  $a=new groupop($cn);
  $a->pa_id=$_GET['pa_id'];
  $wSubmit=new widget('hidden',"p_action","ca_od");
  $wSubmit->table=0;
  echo '<div class="u_redcontent">';
  echo '<form method="post">';
  echo $wSubmit->IOValue();
  echo $a->form();
  echo $wSubmit->Submit("save","Sauver");
  echo '</form>';

  echo '</div>';
  exit();
  }

?>
<div class="u_redcontent">
