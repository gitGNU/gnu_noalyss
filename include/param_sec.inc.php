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
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/* $Revision$ */

/*! \file
 * \brief Set the security for an user
 */

include_once ("ac_common.php");
include_once("check_priv.php");
require_once('class_dossier.php');
$gDossier=dossier::id();
$str_dossier=dossier::get();

include_once ("postgres.php");
/* Admin. Dossier */
$cn=DbConnect($gDossier);
include_once ("class_user.php");
$User=new User($cn);
$User->Check();
$User->check_dossier($gDossier);

include_once ("user_menu.php");
$cn_dossier=DbConnect($gDossier);


if ( $User->check_action(PARSEC) == 0 ) {
  /* Cannot Access */
  NoAccess();
  exit -1;
 }

$cn=DbConnect();
/*  Show all the users, included local admin */
$user_sql=ExecSql($cn,"select  use_id,use_first_name,use_name,use_login,use_admin,priv_priv from ac_users natural join jnt_use_dos ".
	      " join priv_user on (jnt_id=priv_jnt) where use_login != 'phpcompta' and dos_id=".$gDossier);
$MaxUser=pg_NumRows($user_sql);

echo '<DIV class="content" >';

echo '<TABLE CELLSPACING="20" ALIGN="CENTER">';
for ($i = 0;$i < $MaxUser;$i++) {
  $l_line=pg_fetch_array($user_sql,$i);
  //  echo '<TR>';
  if ( $i % 3 == 0 && $i != 0)
    echo "</TR><TR>";
  $str=($l_line['priv_priv'] == 'L')?'Local Admin':' Utilisateur normal';
  if ( $l_line['use_admin'] == 1 ) 
    $str=' Super Admin';

  printf ('<TD><A href="?p_action=sec&action=view&user_id=%s&'.$str_dossier.'">%s %s ( %s )[%s]</A></TD>',
	  $l_line['use_id'],
	  $l_line['use_first_name'],
	  $l_line['use_name'],
	  $l_line['use_login'],
	  $str);

}
echo "</TR>";
echo '</TABLE>';
$action="";

if ( isset ($_GET["action"] )) {
  $action=$_GET["action"];

}
//----------------------------------------------------------------------
// Action = save
//----------------------------------------------------------------------
if ( isset($_POST['ok'])) {

  $sec_User=new User($cn_dossier,$_POST['user_id']);
  /* Save first the ledger */
  $cn_dossier=DbConnect(dossier::id());
  $a=get_array($cn_dossier,'select jrn_def_id from jrn_def');
  foreach ($a as $key) {
    $id=$key['jrn_def_id'];
    $priv=sprintf("jrn_act%d",$id);
    $count=getDbValue($cn_dossier,'select count(*) from user_sec_jrn where uj_login=$1 '.
		      ' and uj_jrn_id=$2',array($sec_User->login,$id));
    if ( $count == 0 )
      {
	ExecSqlParam($cn_dossier,'insert into user_sec_jrn (uj_login,uj_jrn_id,uj_priv)'.
		     ' values ($1,$2,$3)',
		     array($sec_User->login,$id,$_POST[$priv]));
	
      } else {
      ExecSqlParam($cn_dossier,'update user_sec_jrn set uj_priv=$1 where uj_login=$2 and uj_jrn_id=$3',
		   array($_POST[$priv],$sec_User->login,$id));
    }
  }
  /* now save all the actions */
  $a=get_array($cn_dossier,'select ac_id from action');

  foreach ($a as $key) {
    $id=$key['ac_id'];
    $priv=sprintf("action%d",$id);
    $count=getDbValue($cn_dossier,'select count(*) from user_sec_act where ua_login=$1 '.
		      ' and ua_act_id=$2',array($sec_User->login,$id));
    if ( $_POST[$priv] == 1 && $count == 0)
      {
	ExecSqlParam($cn_dossier,'insert into user_sec_act (ua_login,ua_act_id)'.
		     ' values ($1,$2)',
		     array($sec_User->login,$id));
	
      }
    if ($_POST[$priv] == 0 ){
      ExecSqlParam($cn_dossier,'delete from user_sec_act  where ua_login=$1 and ua_act_id=$2',
		   array($sec_User->login,$id));
    }
  } 
  
}


	 
       
//--------------------------------------------------------------------------------
// Action == View detail for users 
//--------------------------------------------------------------------------------

if ( $action == "view" ) {
  $l_Db=sprintf("dossier%d",$gDossier);

  $cn=DbConnect();
  $User=new User($cn,$_GET['user_id']);

  echo '<h2>'.h($User->first_name).' '.h($User->name).' '.hi($User->login);
  $access=$User->get_folder_access($gDossier);
 
  $admin=0;
  if ( $access == 'L') {
    $str='Local Admin';$admin=1;
  } else {
    $str=' Utilisateur normal';}

  if ( $User->admin==1 ) {
    $str=' Super Admin';$admin=1;
  }

  if ( $admin != 0 ) {
    echo '<h2 class="info"> Cet utilisateur est administrateur, il a tous les droits</h2>';
    exit();
  }
  //
  // Check if the user can access that folder
  if ( $access == 'X' ){
    echo "<H2 class=\"error\">L'utilisateur n'a pas accès à ce dossier</H2>";
    $action="";
    return;
  }
  //--------------------------------------------------------------------------------
  // Show access for journal
  //--------------------------------------------------------------------------------

  $Res=ExecSql($cn_dossier,"select jrn_def_id,jrn_def_name  from jrn_def ".
		    " order by jrn_def_name");
  $sec_User=new User($cn_dossier,$_GET['user_id']);

  echo '<form method="post">';
  $sHref=sprintf ('sec_pdf.php?p_action=sec&user_id=%s&'.$str_dossier ,
	  $_GET ['user_id']
	  );

  echo widget::button('Imprime','imprime',"onclick=\"window.open('".$sHref."');\"");
  echo widget::submit('ok','Sauve');
  echo widget::reset('Annule');
  echo dossier::hidden();
  echo widget::hidden('action','sec');
  echo widget::hidden('user_id',$_GET['user_id']);

  echo '<Fieldset><legend>Journaux </legend>';
  echo '<table align="CENTER" width="100%">';
  $MaxJrn=pg_NumRows($Res);
  $jrn_priv=new widget ('select');
  $array=array(
	       array ('value'=>'R','label'=>'Uniquement lecture'),
	       array ('value'=>'W','label'=>'Lecture et écriture'),
	       //	       array ('value'=>'O','label'=>'Uniquement opérations prédéfinies'),
	       array ('value'=>'X','label'=>'Aucun accès')
	       );

  for ( $i =0 ; $i < $MaxJrn; $i++ ) {
    /* set the widget */
    $l_line=pg_fetch_array($Res,$i);

    echo '<TR> ';
    if ( $i == 0 ) echo '<TD> <B> Journal </B> </TD>';else echo "<TD></TD>";
    echo "<TD> $l_line[jrn_def_name] </TD>";

    $jrn_priv->name='jrn_act'.$l_line['jrn_def_id'];
    $jrn_priv->value=$array;
    $jrn_priv->selected=$sec_User->get_ledger_access($l_line['jrn_def_id']);

    echo '<td>';
    echo $jrn_priv->IOValue();
    echo '</td>';
    echo '</tr>';
  }
  echo '</table>';
  echo '</fieldset>';

  //**********************************************************************
  // Show Priv. for actions
  //**********************************************************************
  echo '<fieldset> <legend>Actions </legend>';
  include('template/security_list_action.php');
  echo '</fieldset>';
  echo widget::button('Imprime','imprime',"onclick=\"window.open('".$sHref."');\"");
  echo widget::submit('ok','Sauve');
  echo widget::reset('Annule');
  echo '</form>';   
} // end of the form 
echo "</DIV>";
html_page_stop();
?>
