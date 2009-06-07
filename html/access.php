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
require_once("class_idate.php");
require_once("class_itext.php");
require_once ('constant.php');
require_once ('ac_common.php');
require_once ('class_user.php');
require_once('class_acc_report.php');
require_once('class_periode.php');
require_once ('user_menu.php');
require_once ('class_dossier.php');
require_once('class_todo_list.php');
require_once("class_itextarea.php");

$cn=DbConnect(dossier::id());
$user=new User($cn);
$user->Check();
$user->check_dossier(dossier::id());

if ( DBVERSION!=dossier::get_version($cn)) {
  echo '<h2 class="error">Votre base de données n\'est pas  à jour, ';
  $a="cliquez ici pour appliquer le patch";
  $base=dirname($_SERVER['REQUEST_URI']).'/admin/setup.php';
  echo '<a hreF="'.$base.'">'.$a.'</a></h2>';
}

html_page_start($_SESSION['g_theme']);
echo '<div class="u_tmenu">';
echo menu_tool('access.php');
echo '</div>';
echo '<div class="content">';
/* 
 * Todo list
 */
echo JS_PROTOTYPE;
echo JS_TODO;
if ( isset($_REQUEST['save_todo_list'])) {
  /* Save the new elt */
  $add_todo=new Todo_List($cn);
  $add_todo->set_parameter('id',$_REQUEST['tl_id']);
  $add_todo->set_parameter('title',$_REQUEST['p_title']);
  $add_todo->set_parameter('desc',$_REQUEST['p_desc']);
  $add_todo->set_parameter('date',$_REQUEST['p_date']);
  $add_todo->save();
}
$todo=new Todo_List($cn);
$array=$todo->load_all();


echo '<div style="float:left;width:40%">';
echo '<fieldset> <legend>Liste des tâches</legend>';
echo '<div id="add_todo_list" style="display:none;text-align:left;line-height:3em">';
echo '<form method="post">';
$wDate=new IDate('p_date');
$wTitle=new IText('p_title');
$wDesc=new ITextArea('p_desc');
$wDesc->heigh=5;
echo "Date ".$wDate->input().'<br>';
echo "Titre ".$wTitle->input().'<br>';
echo "Description<br>".$wDesc->input().'<br>';
echo HtmlInput::hidden('phpsessid',$_REQUEST['PHPSESSID']);
echo dossier::hidden();
echo HtmlInput::hidden('tl_id',0);
echo HtmlInput::submit('save_todo_list','Sauve','onClick="$(\'add_todo_list\').hide();$(\'add\').show();return true;"');
echo HtmlInput::button('hide','Annuler','onClick="$(\'add_todo_list\').hide();$(\'add\').show();"');
echo '</form>';

echo '</div>';
echo HtmlInput::button('add','Ajout','onClick="add_todo()"');
if ( ! empty ($array) )  {
  echo '<table id="table_todo" width="100%">';
  $nb=0;
  foreach ($array as $row) {
if ( $nb % 2 == 0 ) $odd='class="odd" '; else $odd='class="even" ';
$nb++;
    echo '<tr id="tr'.$row['tl_id'].'" '.$odd.'>'.
      '<td>'.
      $row['tl_date'].
      '</td>'.
      '<td>'.
      htmlspecialchars($row['tl_title']).
      '</td>'.
      '<td>'.
      HtmlInput::button('mod','M','onClick="todo_list_show('.$row['tl_id'].')"').
      HtmlInput::button('del','E','onClick="todo_list_remove('.$row['tl_id'].')"').
      '</td>'.
      '</tr>';
  }
  echo '</table>';
}
echo '</fieldset>';
echo '</div>';
/* 
 * Mini Report
 */
$report=$user->get_mini_report();

$rapport=new Acc_Report($cn);
$rapport->id=$report;
if ( $rapport->exist() == false ) {
  $user->set_mini_report(0);
  $report=0;
}

if ( $report != 0 ) {

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
