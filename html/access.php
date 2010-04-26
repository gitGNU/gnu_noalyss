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
require_once('class_calendar.php');
require_once('class_acc_ledger.php');

$cn=new Database(dossier::id());
$user=new User($cn);
$user->Check();
if ( $user->check_dossier(dossier::id()) == 'P') {
	redirect("extension.php?".dossier::get(),0);
}

html_page_start($_SESSION['g_theme']);
/*  Check Browser version if < IE6 then unsupported */
$browser=$_SERVER['HTTP_USER_AGENT'];

if ( strpos($browser,'MSIE 6')!=false ||
     strpos($browser,'MSIE 5')!=false ||
     strpos($browser,'MSIE 7')!=false 
) {


echo <<<EOF
    <!--[if lt IE 8]>
  <div style='border: 1px solid #F7941D; background: #FEEFDA; text-align: center; clear: both; height: 75px; position: relative;'>
    <div style='position: absolute; right: 3px; top: 3px; font-family: courier new; font-weight: bold;'><a href='#' onclick='javascript:this.parentNode.parentNode.style.display="none"; return false;'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-cornerx.jpg' style='border: none;' alt='Close this notice'/></a></div>
    <div style='width: 640px; margin: 0 auto; text-align: left; padding: 0; overflow: hidden; color: black;'>
      <div style='width: 75px; float: left;'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-warning.jpg' alt='Warning!'/></div>
      <div style='width: 275px; float: left; font-family: Arial, sans-serif;'>
        <div style='font-size: 14px; font-weight: bold; margin-top: 12px;'>Vous utilisez un navigateur dépassé !</div>
        <div style='font-size: 12px; margin-top: 6px; line-height: 12px;'>Pour une meilleure expérience web, prenez le temps de mettre votre navigateur à jour.</div>
      </div>
      <div style='width: 75px; float: left;'><a href='http://fr.www.mozilla.com/fr/' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-firefox.jpg' style='border: none;' alt='Get Firefox 3.5'/></a></div>
      <div style='width: 73px; float: left;'><a href='http://www.apple.com/fr/safari/download/' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-safari.jpg' style='border: none;' alt='Get Safari 4'/></a></div>
      <div style='float: left;'><a href='http://www.google.com/chrome?hl=fr' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-chrome.jpg' style='border: none;' alt='Get Google Chrome'/></a></div>
    </div>
  </div>
  <![endif]-->
EOF;
exit();
}

if ( DBVERSION < dossier::get_version($cn)) {
  echo '<h2 class="error" style="font-size:12px">'._("Attention: la version de base de donnée est supérieure à la version du programme").'</h2>';
 }
if ( DBVERSION > dossier::get_version($cn)) {
  echo '<h2 class="error" style="font-size:12px">'._("Votre base de données n'est pas à jour");
  $a=_("cliquez ici pour appliquer le patch");
  $base=dirname($_SERVER['REQUEST_URI']).'/admin/setup.php';
  echo '<a hreF="'.$base.'">'.$a.'</a></h2>';
}

echo '<div class="u_tmenu">';
echo menu_tool('access.php');
echo '</div>';
echo '<div class="content">';
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
  echo '<div style="float:right;width:27%">';
  echo '<fieldset style="background-color:white"><legend>'.$rapport->get_name().'</legend>';
  $exercice=$user->get_exercice();
  if ( $exercice == 0 ) {
    alert(_('Aucune periode par defaut'));
  } else {
    $periode=new Periode($cn);
    $limit=$periode->limit_year($exercice);

    $result=$rapport->get_row($limit['start'],$limit['end'],'periode');
    $ix=0;
    echo '<table border="0" width="100%">';
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
  echo '<fieldset style="background-color:white"><legend>'._('Aucun rapport défini').'</legend>';
  echo '<a href="user_pref.php?'.dossier::get().'">'._('Cliquez ici pour mettre à jour vos préférences').'</a>';
  echo '</fieldset>';
  echo '</div>';
 }
/*
 * Todo list
 */
echo JS_TODO;
echo dossier::hidden();
echo HtmlInput::phpsessid();
echo js_include('prototype.js');
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


echo '<div style="float:right;width:35em;">';
echo '<fieldset> <legend>'._('Pense-Bête').'</legend>';
echo '<div id="add_todo_list" style="display:none;text-align:left;line-height:3em">';
echo '<form method="post">';
$wDate=new IDate('p_date');
$wTitle=new IText('p_title');
$wDesc=new ITextArea('p_desc');
$wDesc->heigh=5;
echo _("Date")." ".$wDate->input().'<br>';
echo _("Titre")." ".$wTitle->input().'<br>';
echo _("Description")."<br>".$wDesc->input().'<br>';
echo HtmlInput::hidden('phpsessid',$_REQUEST['PHPSESSID']);
echo dossier::hidden();
echo HtmlInput::hidden('tl_id',0);
echo HtmlInput::submit('save_todo_list',_('Sauve'),'onClick="hide(\'add_todo_list\');show(\'add\');return true;"');
echo HtmlInput::button('hide',_('Annuler'),'onClick="$(\'add_todo_list\').hide();$(\'add\').show();return true;"');
echo '</form>';

echo '</div>';
echo HtmlInput::button('add',_('Ajout'),'onClick="add_todo()"');
if ( ! empty ($array) )  {
  echo '<table id="table_todo" width="100%">';
  $nb=0;
  $today=date('d.m.Y');

  foreach ($array as $row) {
    if ( $nb % 2 == 0 ) $odd='class="odd" '; else $odd='class="even" ';
    if ( strcmp($today,$row['tl_date'])==0) { $odd.=' style="background-color:#FFEA00"';}
    $nb++;
    echo '<tr id="tr'.$row['tl_id'].'" '.$odd.'>'.
      '<td>'.
      $row['tl_date'].
      '</td>'.
      '<td>'.
      '<a href="javascript:void" onclick="todo_list_show(\''.$row['tl_id'].'\')">'.
      htmlspecialchars($row['tl_title']).
      '</a>'.
       '</td>'.
      '<td>'.
      HtmlInput::button('del','X','onClick="todo_list_remove('.$row['tl_id'].')"').
      '</td>'.
      '</tr>';
  }
  echo '</table>';
}
echo '</fieldset>';
echo '</div>';

/* others report */
$cal=new Calendar();
$cal->get_preference();
$Ledger=new Acc_Ledger($cn,0);
$Operation=new Action($cn);
$last_ledger=$Ledger->get_last(10);
$last_operation=$Operation->get_last(10);
ob_start();
require_once('template/dashboard.php');
$ret=ob_get_contents();
ob_end_clean();
echo $ret;

echo '</div>';


