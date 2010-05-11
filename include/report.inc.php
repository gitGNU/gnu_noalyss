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

*/
/* $Revision: 1937 $ */
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/*! \file
 * \brief handle your own report: create or view report
 */
include_once ("ac_common.php");
include_once ("user_menu.php");
require_once("class_ifile.php");
require_once("class_ibutton.php");
require_once('class_acc_report.php');
require_once('class_dossier.php');
require_once('class_database.php');
include_once ("class_user.php");
include_once ("user_menu.php");
require_once('class_ipopup.php');

/* Load all the needed javascript */
echo js_include('prototype.js');
echo js_include('scriptaculous.js');
echo js_include('effects.js');
echo js_include('controls.js');
echo js_include('scripts.js');
echo js_include('acc_ledger.js');
echo js_include('card.js');
echo js_include('accounting_item.js');
echo JS_INFOBULLE;
echo js_include('ajax_fiche.js');
echo js_include('dragdrop.js');
echo js_include('acc_report.js');

echo ICard::ipopup('ipopcard');
echo ICard::ipopup('ipop_newcard');
echo IPoste::ipopup('ipop_account');
$search_card=new IPopup('ipop_card');
$search_card->title=_('Recherche de fiche');
$search_card->value='';
echo $search_card->input();



$gDossier=dossier::id();
$str_dossier=dossier::get();

/* Admin. Dossier */
$rep=new Database($gDossier);

$User=new User($rep);
$User->Check();
$User->can_request(PARRAP,1);


$cn=new Database($gDossier);

$rap=new Acc_Report($cn);

if ( isset ($_POST["del_form"]) ) {
  $rap->id=$_POST['fr_id'];
  $rap->delete();
}
if ( isset ($_POST["record"] )) {
  $rap->from_array($_POST);
  $rap->save();
}
if ( isset($_POST['update'])) {
    $rap->from_array($_POST);
    $rap->save($_POST);

  }
if ( isset($_POST['upload'])) {
  $rap->upload();

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
if ( isset($_POST['upload'])) {
    exit();
  }
if ( isset ($_REQUEST["action"]) ) {

  $action=$_REQUEST ["action"];
  $rap->id=(isset($_REQUEST ['fr_id']))?$_REQUEST['fr_id']:0;

  if ($action == "add" && ! isset($_REQUEST['fr_id']))
    {

      echo '<DIV class="u_redcontent">';
      echo '<h1>'._('Définition').'</h1>';
      echo '<form method="post" >';
      echo dossier::hidden();
       $rap->id=0;
      echo $rap->form(15);
      
      echo HtmlInput::submit("record",_("Sauve"));
      echo '</form>';
      echo '<span class="notice">'._("Les lignes vides seront effacées").'</span>';
      echo "</DIV>";
      echo '<DIV class="u_redcontent">';

      echo '<form method="post" enctype="multipart/form-data">';
      echo '<h1> Importation</h1>';
      echo dossier::hidden();
      $rap->id=0;
      $wUpload=new IFile();
      $wUpload->name='report';
      $wUpload->value='report_value';
      echo _('Importer ce rapport').' ';
      echo $wUpload->input();
      echo HtmlInput::submit("upload",_("Sauve"));
      echo '</form>';
      echo '<span class="notice">'._("Les lignes vides seront effacées").'</span>';
      echo "</DIV>";

    }
  if ($action=="view"      ) 
      {
	echo '<DIV class="u_redcontent">';
	$rap->id=$_REQUEST ['fr_id'];
	echo '<form method="post">';
	$rap->load();
	echo $rap->form();
	echo HtmlInput::hidden("fr_id",$rap->id);
	echo HtmlInput::hidden("action","record");
	echo HtmlInput::submit("update",_("Mise a jour"));
	echo HtmlInput::submit("del_form",_("Effacement"));
	$w=new IButton();
	$w->name="export";
	$w->javascript="report_export('".$gDossier."','".$rap->id."')";
	$w->label='Export';
	echo $w->input();
	echo '<span id="export_link"></span>';
	echo '</form>';
	echo '<span class="notice">'._("Les lignes vides seront effacées").'</span>';
	echo "</DIV>";
      }

}



html_page_stop();
?>
