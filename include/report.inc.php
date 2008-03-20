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
/* $Revision: 1937 $ */
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/*! \file
 * \brief handle your own report: create or view report
 */
include_once ("ac_common.php");
include_once ("user_menu.php");
require_once('class_acc_report.php');
require_once('class_dossier.php');
include_once ("postgres.php");
include_once ("class_user.php");
include_once ("user_menu.php");



$gDossier=dossier::id();
$str_dossier=dossier::get();

/* Admin. Dossier */
$rep=DbConnect();

$User=new User($rep);
$User->Check();
$User->can_request($cn,FORM);


$cn=DbConnect($gDossier);

$nocookie='&PHPSESSID='.$_REQUEST['PHPSESSID'];
$rap=new Acc_Report($cn);

if ( isset ($_POST["del_form"]) ) {
  $rap->id=$_POST['fr_id'];
  $rap->delete();
  //   header('Location:'.$_SERVER["SCRIPT_URI"].'?'.$str_dossier.$nocookie);
}
if ( isset ($_POST["record"] )) {
  $rap->from_array($_POST);
  $rap->save();
  //  header('Location:'.$_SERVER["SCRIPT_URI"].'?'.$str_dossier.$nocookie);
}
if ( isset($_POST['update'])) {
    $rap->from_array($_POST);
    $rap->save($_POST);

  }


$lis=$rap->get_list();
$p_action='p_action=defreport';
echo '<div class="lmenu">';
echo '<TABLE>';
echo '<TR><TD class="mtitle"><A class="mtitle" HREF="?'.$p_action.'&action=add&'.$str_dossier.'">Ajout</A></TD></TR>';

foreach ( $lis as $row) {
  printf ('<TR><TD class="mtitle"><A class="mtitle" HREF="?'.$p_action.'&action=view&fr_id=%s&%s">%s</A></TD></TR>', $row->id,$str_dossier,$row->name);

}
echo "</TABLE>";
echo '</div>';

if ( isset ($_REQUEST["action"]) ) {
  echo JS_SEARCH_POSTE;
  $action=$_REQUEST ["action"];
  $rap->id=(isset($_REQUEST ['fr_id']))?$_REQUEST['fr_id']:0;

  if ($action == "add" && ! isset($_REQUEST['fr_id']))
    {

      echo '<DIV class="u_redcontent">';
      echo '<form method="post" >';
      echo dossier::hidden();
       $rap->id=0;
      echo $rap->form(15);
      
      echo widget::submit("record","Sauve");
      echo '</form>';
      echo '<span class="notice">Les lignes vides seront effac&eacute;es</span>';
      echo "</DIV>";
    }
  if ($action=="view"      ) 
      {
	echo '<DIV class="u_redcontent">';
	$rap->id=$_REQUEST ['fr_id'];
	echo '<form method="post">';
	$rap->load();
	echo $rap->form();
	echo widget::hidden("fr_id",$rap->id);
	echo widget::hidden("action","record");
	echo widget::submit("update","Mise a jour");
	echo widget::submit("del_form","Effacement");
	echo '</form>';
	echo '<span class="notice">Les lignes vides seront effac&eacute;es</span>';
	echo "</DIV>";
      }

}



html_page_stop();
?>
