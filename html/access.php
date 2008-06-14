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
/* $Revision: 1615 $ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/*!\file 
 * \brief first page
 */
require_once ('constant.php');
require_once ('ac_common.php');
require_once ('class_user.php');
require_once('class_acc_report.php');
require_once('class_periode.php');
require_once ('user_menu.php');
require_once ('class_dossier.php');



$cn=DbConnect(dossier::id());
$user=new User($cn);

html_page_start($_SESSION['g_theme']);
echo '<div class="u_tmenu">';
echo menu_tool('access');
echo '</div>';
echo '<div class="content">';




$report=$user->get_mini_report();
if ( $report != 0 ) {
  $rapport=new Acc_Report($cn);
  $rapport->id=$report;
  echo '<div style="float:right">';
  echo '<fieldset style="background-color:white"><legend>'.$rapport->get_name().'</legend>';
  $exercice=$user->get_exercice();
  if ( $exercice == 0 ) {
    echo "<script>alert('Aucune periode par defaut');</script>";
  } else {
    $periode=new Periode($cn);
    $limit=$periode->limit_year($exercice);
    
    $result=$rapport->get_row($limit['start'],$limit['end'],'periode');
    $ix=0;
    echo '<table border="0">';
    foreach ($result as $row) {
      $ix++;
      $bgcolor=($ix%2==0)?' style="background-color:lightgrey"':'';
      echo '<tr'.$bgcolor.'">';

      echo '<td> '.$row['desc'].'</td>'.
	'<td>'.sprintf("% 10.2f",$row['montant'])." &euro;</td>";
      echo '</tr>';
    }
    echo '</table>';
  }
  echo '</fieldset>';
  echo '</div>';
 } else {
  echo '<div style="float:right;width:20%">';
  echo '<fieldset style="background-color:white"><legend> Aucun rapport défini</legend>';
  echo '<a href="user_pref.php?'.dossier::get().'"> Cliquez ici pour mettre à jour vos préférences</a>';
  echo '</fieldset>';
  echo '</div>';
 }
echo '</div>';
