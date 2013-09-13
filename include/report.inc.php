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
/* $Revision$ */
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/*! \file
 * \brief handle your own report: create or view report
 */
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
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


$gDossier=dossier::id();
$str_dossier=dossier::get();

/* Admin. Dossier */
$rep=new Database($gDossier);


$cn=new Database($gDossier);

$rap=new Acc_Report($cn);

if ( isset ($_POST["del_form"]) )
{
    $rap->id=$_POST['fr_id'];
    $rap->delete();
}
if ( isset ($_POST["record"] ))
{
    $rap->from_array($_POST);
    $rap->save();
}
if ( isset($_POST['update']))
{
    $rap->from_array($_POST);
    $rap->save($_POST);

}
if ( isset($_POST['upload']))
{
    $rap->upload();

}

$lis=$rap->get_list();
$ac="&ac=".$_REQUEST['ac'];
$p_action='p_action=defreport';
echo '<div class="lmenu">';
echo '<TABLE>';
echo '<TR><TD class="mtitle"><A class="mtitle" HREF="?'.$p_action.$ac.'&action=add&'.$str_dossier.'">Ajout</A></TD></TR>';

foreach ( $lis as $row)
{
    printf ('<TR><TD class="mtitle"><A class="mtitle" HREF="?'.$p_action.$ac.'&action=view&fr_id=%s&%s">%s</A></TD></TR>', $row->id,$str_dossier,$row->name);

}
echo "</TABLE>";
echo '</div>';
if ( isset($_POST['upload']))
{
    exit();
}
if ( isset ($_REQUEST["action"]) )
{

    $action=$_REQUEST ["action"];
    $rap->id=(isset($_REQUEST ['fr_id']))?$_REQUEST['fr_id']:0;

    if ($action == "add" && ! isset($_REQUEST['fr_id']))
    {

        echo '<DIV class="redcontent">';
        echo '<h1>'._('Définition').'</h1>';
        echo '<form method="post" >';
        echo dossier::hidden();
        $rap->id=0;
        echo $rap->form(15);

        echo HtmlInput::submit("record",_("Sauve"));
        echo '</form>';
        echo '<span class="notice">'._("Les lignes vides seront effacées").'</span>';
        echo "</DIV>";
        echo '<DIV class="redcontent">';

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
        echo '<DIV class="redcontent">';
        $rap->id=$_REQUEST ['fr_id'];
        echo '<form method="post" style="display:inline">';
        $rap->load();
        echo $rap->form();
        echo HtmlInput::hidden("fr_id",$rap->id);
        echo HtmlInput::hidden("action","record");
        echo HtmlInput::submit("update",_("Mise a jour"));
        echo HtmlInput::submit("del_form",_("Effacement"));

        echo '</form>';
		echo '<form method="get" action="export.php" style="display:inline">';
		echo dossier::hidden();
		echo HtmlInput::hidden("act","CSV:reportinit");
		echo HtmlInput::hidden('f',$rap->id);
		echo HtmlInput::submit('bt_csv',"Export CSV");
		echo HtmlInput::request_to_hidden(array('ac','action','p_action','fr_id'));
		echo '</form>';
        echo '<span class="notice">'._("Les lignes vides seront effacées").'</span>';
        echo "</DIV>";
    }

}



html_page_stop();
?>
