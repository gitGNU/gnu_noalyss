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
$rep=DbConnect();
include_once ("class_user.php");
$User=new User($rep);
$User->Check();

include_once ("user_menu.php");

$cn_dossier=DbConnect($gDossier);

if ( $User->check_action($cn_dossier,GJRN) == 0 ) {
  /* Cannot Access */
  NoAccess();
  exit -1;
 }

$cn=DbConnect();
$User=ExecSql($cn,"select  use_id,use_first_name,use_name,use_login from ac_users natural join jnt_use_dos where use_login != 'phpcompta' and dos_id=".$gDossier);
$MaxUser=pg_NumRows($User);


echo '<DIV class="content" >';

echo '<TABLE CELLSPACING="20" ALIGN="CENTER">';
for ($i = 0;$i < $MaxUser;$i++) {
  $l_line=pg_fetch_array($User,$i);
  //  echo '<TR>';
  if ( $i % 3 == 0 && $i != 0)
    echo "</TR><TR>";

  printf ('<TD><A href="?p_action=sec&action=view&user_id=%s&'.$str_dossier.'">%s %s ( %s )</A></TD>',
	  $l_line['use_id'],
	  $l_line['use_first_name'],
	  $l_line['use_name'],
	  $l_line['use_login'] );

}
echo "</TR>";
echo '</TABLE>';
$action="";

if ( isset ($_GET["action"] )) {
  $action=$_GET["action"];

}

// session_register set to off, so variable are undefined
foreach ($_GET as $name=>$value) 
  ${"$name"}=$value;

if ( $action == "change_jrn" ) {
  // Check if the user can access that folder
  if ( CheckDossier($_GET['login'],$gDossier) == 0 ) {
    echo "<H2 class=\"error\">Cet utilisateur ne peut pas acc&eacute;der ce dossier</H2>";
    $action="";
    return;
  }
  $login=$_GET['login'];
  $jrn=$_GET['jrn'];
  $access=$_GET['access'];
  $l_Db=sprintf("dossier%d",$gDossier);
  echo_debug('user_sec.php',__LINE__,"select * from user_sec_jrn where uj_login='$login' and uj_jrn_id=$jrn");
  $cn_dossier=DbConnect($gDossier);
  $l2_Res=ExecSql($cn_dossier,
		  "select * from user_sec_jrn where uj_login='$login' and uj_jrn_id=$jrn");
  $l2_count=pg_NumRows($l2_Res);
  if ( $l2_count == 1 ) {
    $Res=ExecSql($cn_dossier,"update  user_sec_jrn set uj_priv='$access' where uj_login='$login' and uj_jrn_id=$jrn");
  } else {
    $Res=ExecSql($cn_dossier,"insert into  user_sec_jrn(uj_login,uj_jrn_id,uj_priv) values( '$login' ,$jrn,'$access')");
  }

  $action="view";
}
if ( $action == "change_act" ) {
  // Check if the user can access that folder
  if ( CheckDossier($_GET['login'],$gDossier) == 0 ) {
    echo "<H2 class=\"error\">he cannot access this folder</H2>";
    $action="";
    return;
  }
  $l_Db=sprintf("dossier%d",$gDossier);
  $cn_dossier=DbConnect($gDossier);
  if ( $_GET['access']==0) {
    echo_debug('user_sec.php',__LINE__,"delete right");
    $Res=ExecSql($cn_dossier,
		 "delete from user_sec_act where ua_login='".$_GET['login']."' and ua_act_id=$act");
  } else {
    echo_debug('user_sec.php',__LINE__,"insert right");
	if ( CountSql($cn_dossier,"select * from user_sec_act where ua_login='$login' and ua_act_id=$act") < 1 )
	{
    	$Res=ExecSql($cn_dossier,
			 "insert into  user_sec_act(ua_login,ua_act_id) values( '$login' ,$act)");
	 }
  }
  $action="view";
}
// Action == View detail for users 
if ( $action == "view" ) {
  $l_Db=sprintf("dossier%d",$gDossier);
  $cn_dossier=DbConnect($gDossier);
  $cn=DbConnect();
  $User=ExecSql($cn,
		"select  use_id,use_first_name,use_name,use_login
                from ac_users where use_id=".$_GET['user_id']);
  $MaxUser=pg_NumRows($User);
  if ( $MaxUser == 0 ) return;
  $l2_line=pg_fetch_array($User,0);

  printf ('<H2 class="info"> Détail utilisateur %s %s (%s) </H2>',
	  $l2_line['use_first_name'],
	  $l2_line['use_name'],
	  $l2_line['use_login']);
  // Check if the user can access that folder
  if ( CheckDossier($l2_line['use_login'],$gDossier) == 0 ) {
    echo "<H2 class=\"error\">he cannot access this folder</H2>";
    $action="";
    return;
  }
  // Print button

  $sHref=sprintf ('sec_pdf.php?p_action=sec&user_id=%s&'.$str_dossier ,
	  $l2_line['use_id']
	  );
  echo widget::button_href('Imprime',$sHref);
  // Show access for journal
  $Res=ExecSql($cn_dossier,"select jrn_def_id,jrn_def_name  from jrn_def ");
  $admin=CheckIsAdmin($l2_line['use_login']);

  echo '<table align="CENTER" width="100%">';
  $MaxJrn=pg_NumRows($Res);
  for ( $i =0 ; $i < $MaxJrn; $i++ ) {
    $l_line=pg_fetch_array($Res,$i);
    echo '<TR> ';
    if ( $i == 0 ) echo '<TD> <B> Journal </B> </TD>';else echo "<TD></TD>";
    echo "<TD> $l_line[jrn_def_name] </TD>";

    $l_change="action=change_jrn&jrn=$l_line[jrn_def_id]&login=$l2_line[use_login]&user_id=$l2_line[use_id]";

    if ( $admin == 0) {
      $right=    CheckJrn($gDossier,$l2_line['use_login'],$l_line['jrn_def_id'] );
      echo_debug('user_sec.php',__LINE__,"Privilege is $right");
    } else $right = 3;
    if ( $right == 0 ) {
      echo "<TD BGCOLOR=RED>";
      echo "Pas d'accès";
      echo "</TD>";
      echo '<TD class="mtitle"> <A CLASS="mtitle" HREF="?p_action=sec&'.$l_change.'&access=R&'.$str_dossier.'"> Lecture</A></TD>';
      echo '<TD class="mtitle"> <A CLASS="mtitle" HREF="?p_action=sec&'.$l_change.'&access=W&'.$str_dossier.'"> Ecriture</A></TD>';

      }
    if ( $right == 1 ) {
      echo '<TD class="mtitle"> <A CLASS="mtitle" HREF="?p_action=sec&'.$l_change.'&access=X&'.$str_dossier.'"> Pas d\'accès</A></TD>';
      echo "<TD BGCOLOR=\"#3BCD27\">";
      echo "Lecture ";
      echo "</TD>";
      echo '<TD class="mtitle"> <A CLASS="mtitle" HREF="?p_action=sec&'.$l_change.'&access=W&'.$str_dossier.'"> Ecriture</A></TD>';
    }
    if ( $right == 2 ) {
      echo '<TD class="mtitle"> <A CLASS="mtitle" HREF="?p_action=sec&'.$l_change.'&access=X&'.$str_dossier.'"> Pas d\'accès</A></TD>';
      echo '<TD class="mtitle"> <A CLASS="mtitle" HREF="?p_action=sec&'.$l_change.'&access=R&'.$str_dossier.'"> Lecture</A></TD>';

      echo "<TD BGCOLOR=\"#3BCD27\">";
      echo "Ecriture ";
      echo "</TD>";

    }
    if ( $right == 3 ) { 
      echo '<TD class="mtitle">  Pas d\'accès</TD>';
      echo '<TD class="mtitle">  Lecture </TD>';

      echo "<TD BGCOLOR=\"#3BCD27\">";
      echo "Ecriture ";
      echo "</TD>";

    }



    echo '</TR>';
  }

  // Priv. for actions
  $Res=ExecSql($cn_dossier,
	       "select ac_id, ac_description from action   order by ac_id ");

  $MaxJrn=pg_NumRows($Res);

  for ( $i =0 ; $i < $MaxJrn; $i++ ) {
    $l_line=pg_fetch_array($Res,$i);
    echo '<TR> ';
    if ( $i == 0 ) echo '<TD> <B> Action <B></TD>';
	if  ($i != 0 &&  $l_line['ac_id'] != 50 && $l_line['ac_id']!=60)  echo "<TD></TD>";
    if ( $l_line['ac_id'] == 50 ) 
	  echo '<TD colspan="3"> <B> Compta. Analytique <B></TD><tr><td></td>';

    if ( $l_line['ac_id'] == 60 ) 
	  echo '<TD colspan="3"> <B> Module Budget <B></TD><tr><td></td>';
	
    echo "<TD>". $l_line['ac_description']." </TD>";
	
	$l_change="action=change_act&act=".$l_line['ac_id']."&login=".$l2_line['use_login']."&user_id=".$l2_line['use_id'];
	if ( $admin ==0 ) {
	  $right=check_action($gDossier,$l2_line['use_login'],$l_line['ac_id']);
	} else {
	  $right = 2;
	}
    if ( $right == 0 ) {
      echo "<TD BGCOLOR=RED>";
      echo "Pas d'accès";
      echo "</TD>";
      $l_change=$l_change."&access=1";
      echo '<TD class="mtitle"> <A CLASS="mtitle" HREF="?p_action=sec&'.$l_change.'&'.$str_dossier.'"> Accès </A></TD>';
    }   
    if ( $right == 1) {
      $l_change=$l_change."&access=0";
      echo '<TD class="mtitle"> <A CLASS="mtitle" HREF="?p_action=sec&'.$l_change.'&'.$str_dossier.'"> Pas d\'accès </A></TD>';
      echo "<TD BGCOLOR=\"#3BCD27\">";
      echo "Accès ";
      echo "</TD>";
      
    }
    if ( $right == 2) {

      echo '<TD class="mtitle">  Change </TD>';
      echo "<TD BGCOLOR=\"#3BCD27\">";
      echo "Accès ";
      echo "</TD>";
      
    }


    echo '</TR>';
  }

  echo '</TABLE>';
  echo widget::button_href('Imprime',$sHref);
    
}
echo "</DIV>";
html_page_stop();
?>
