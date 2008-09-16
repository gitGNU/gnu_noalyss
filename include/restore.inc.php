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
 * \brief restaure a database 
 */

if ( isset ($_REQUEST['sa'] )) {

  $cmd=escapeshellcmd (PG_RESTORE);
  putenv("PGPASSWORD=".phpcompta_password);
  putenv("PGUSER=".phpcompta_user);
  putenv("PGPORT=".phpcompta_psql_port);
  $retour='<hr>'.widget::button_href("Retour","?action=restore&PHPSESSID=".$_REQUEST['PHPSESSID']);
  if ( ! isset($_REQUEST['t'])) {
    echo '<div class="content">';
    echo ("<span class=\"error\">Vous devez préciser s'il s'agit d'un modèle ou d'un dossier</span>");
    echo $retour;
    echo '</div>';
    exit();
  }
  if ( empty ($_FILES['file']['name']) || 
       strlen(trim($_FILES['file']['name']))==0
       ) {
    echo '<div class="content">';

    echo ("<span class=\"error\">Vous devez donner un fichier </span>");
    echo $retour;
    echo '</div>';
    exit();
  }
  //---------------------------------------------------------------------------
  // Restore a folder (dossier)
  if ( $_REQUEST['t']=='d') {
    ini_set('upload_max_filesize','5M');
    echo '<div class="content">';

    $cn=DbConnect();
    $id=NextSequence($cn,'dossier_id');

    if ( strlen(trim($_REQUEST['database'])) == 0 )
      $lname=$id." Restauration :".FormatString($_FILES['file']['name']);
    else 
      $lname=$id." ".$_REQUEST['database'];


    $sql="insert into ac_dossier (dos_id,dos_name) values (".$id.",'".$lname."') ";
    StartSql($cn);
    try{
      getDbValue($cn,$sql);


    } catch ( Exception $e) {
      echo '<span class="error">'."Echec de la restauration ".'</span>';
      print_r($e);
      Rollback($cn);
      exit();
    }
    Commit($cn);
    $name=domaine."dossier".$id;
    echo $name;
    ExecSql($cn,"create database ".$name." encoding='utf8'");
    $args="  -d $name ".$_FILES['file']['tmp_name'];

    $status=system(PG_RESTORE.$args);
    echo '<h2 class="info"> Restauration réussie du dossier '.$lname.'</h2>';

    $new_cn=DbConnect($id);

    apply_patch($new_cn,$name,0);
    echo '<span class="error">'.'Ne pas recharger la page, sinon votre base de données sera restaurée une fois de plus'.'</span>';
    echo $retour;

    echo '</div>';
  }
  //---------------------------------------------------------------------------
  // Restore a modele

  if ( $_REQUEST['t']=='m') {
    ini_set('upload_max_filesize','5M');
    echo '<div class="content">';

    $cn=DbConnect();
    $id=NextSequence($cn,'s_modid');

    if ( strlen(trim($_REQUEST['database'])) == 0 )
      $lname=$id." Restauration :".FormatString($_FILES['file']['name']);
    else 
      $lname=$id." ".$_REQUEST['database'];


    $sql="insert into modeledef (mod_id,mod_name) values (".$id.",'Restauration".$lname."') ";
    StartSql($cn);
    try{
      getDbValue($cn,$sql);

    } catch ( Exception $e) {
      echo '<span class="error">'."Echec de la restauration ".'</span>';
      print_r($e);
      Rollback($cn);
      exit();
    }
    Commit($cn);

    $name=domaine."mod".$id;
    ExecSql($cn,"create database ".$name." encoding='utf8'");
    $args="   -d $name ".$_FILES['file']['tmp_name'];
    $status=exec(PG_RESTORE.$args);

    echo '<h2 class="info"> Restauration réussie du modèle '.$lname.'</h2>';
    $new_cn=DbConnect($id,'mod');

    apply_patch($new_cn,$name,0);

    echo '<span class="error">'.'Ne pas recharger la page, sinon votre base de données sera restaurée une fois de plus'.'</span>';
    echo $retour;

    echo '</div>';
  }
 } else  {
  echo '<div class="content">';
  ini_set('upload_max_filesize','5M');
  echo '<form method="POST" enctype="multipart/form-data" >';
  echo widget::hidden('action','restore');
  echo widget::hidden('sa','r');
  echo '<table>';
  echo '<tr><td>'."Nom de la base de donnée".'</td>';
  $wNom=new widget("text");
  $wNom->name="database";
  $wNom->size=12;
  echo '<td>'.$wNom->IOValue().'</td></tr>';
  echo '<tr><td>'."Type de backup :".'</td>';
  $chk=new widget("radio");
  $chk->name="t";
  $chk->value="d";
  echo '<td>'.$chk->IOValue()."Dossier".'</td>';
  echo '</tr><tr><td></td>';
  $chk->name="t";
  $chk->value="m";
  echo '<td>'.$chk->IOValue()."Modele".'</td>';
  echo '<tr>';
  $file=new widget("file");
  $file->name="file";
  $file->value="mod";
  $file->label="Fichier";
  $file->table=1;
  echo $file->IOValue();
  echo '</tr>';
  echo '</table>';
  echo widget::submit("","Restauration");
  echo '</form>';
  echo '</div>';
 }
