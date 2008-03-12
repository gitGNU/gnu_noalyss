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
/*! \file
 * \brief handle your own report: create or view report
 */
include_once ("ac_common.php");
include_once ("user_menu.php");
require_once('class_rapport.php');
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

$cn=DbConnect($gDossier);


$rap=new Rapport($cn);
if ( isset ($_POST["del_form"]) ) {
  $rap->id=$_POST['fr_id'];
  $rap->delete();
  header('Location:form.php?'.$str_dossier);
}
if ( isset ($_POST["record"] )) {
  $rap->from_array($_POST);
  $rap->save();
  header('Location:form.php?'.$str_dossier);
}
if ( isset($_POST['update'])) {
    $rap->from_array($_POST);
    $rap->save($_POST);

  }


echo '<div class="u_tmenu">';
echo ShowMenuCompta("user_advanced.php?".dossier::get());
echo '</div>';
echo ShowMenuAdvanced(6);
// Get The priv on the selected folder
$User->can_request($cn,FORM);


include ("check_priv.php");


html_page_start($_SESSION['g_theme']);





$lis=$rap->get_list();

echo '<div class="lmenu">';
echo '<TABLE>';
echo '<TR><TD class="mtitle"><A class="mtitle" HREF="form.php?action=add&'.$str_dossier.'">Ajout</A></TD></TR>';

foreach ( $lis as $row) {
  printf ('<TR><TD class="mtitle"><A class="mtitle" HREF="form.php?action=view&fr_id=%s&%s">%s</A></TD></TR>', $row->id,$str_dossier,$row->name);

}
echo "</TABLE>";
echo '</div>';



if ( isset ($_REQUEST["action"]) ) {
  $action=$_REQUEST ["action"];
  $rap->id=$_REQUEST ['fr_id'];
  if ($action == "add" )
    {

      echo '<DIV class="u_redcontent">';
      echo '<form method="post" >';
      echo dossier::hidden();
      echo $rap->id=0;
      echo $rap->form(15);
      echo widget::submit("record","Sauve");
      echo '</form>';
      echo "</DIV>";
    }
  if ($action=="view" ) {
    echo '<DIV class="u_redcontent">';
    $rap->id=$_GET['fr_id'];
    echo '<form method="post" action="form.php">';
    $rap->load();
    echo $rap->form();
    echo widget::hidden("action","update");
    echo widget::submit("update","Mise a jour");
    echo widget::submit("del_form","Effacement");
    echo '</form>';

    echo "</DIV>";
  }

}

html_page_stop();
?>
