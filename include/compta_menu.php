<?

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

/* function c_ShowMenuComptaRight
 * Purpose : Display menu on the Right
 *           (preference, logout and admin)
 * parm : 
 *	- $p_dossier the current dossier
 *      - $p_admin   $g_UserProperty['use_admin'] 1 if admin
 *      - $p_more code
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 
function c_ShowMenuComptaRight($p_dossier=0,$p_admin,$p_more="")
{
  include_once("ac_common.php");
  echo '<div class="rmenu">';
  echo '<TABLE>';
  if ( $p_admin != 0 && $p_dossier != 0) {
    echo '<TR><TD class="mtitle"> <A class="mtitle" HREF="admin_repo.php">Admin</A></TD></TR>';
  }
  echo '<TR><TD class="mtitle"> <A class="mtitle" HREF="login.php">Accueil</A></TD></TR>';
  echo '<TR><TD class="mtitle"> <A class="mtitle" HREF="user_pref.php">Preference</A></TD></TR>';
  if ( strlen ($p_more) != 0 ) {
    printf ('<TR><TD class="mtitle"> %s </TD></TR>',$p_more);
  }
  echo '<TR><TD class="mtitle"> ';
  html_button_logout();
  echo ' </TD></TR>';
  echo "</TABLE>";
  echo '</div>';
}
/* function ShowMenuCompta
 * Purpose : Montre le menu du haut
 * 
 * parm : 
 *	- dossier
 *	- $p_user	array ($g_UserProperty)
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 

function c_ShowMenuCompta($p_dossier)
{
  include_once("postgres.php");
  $l_name=GetDossierName($p_dossier);
  echo "<P> <H2 class=\"info2\"> $l_name </H2></P>";
  echo '<div class="tmenu">';
  echo '<TABLE><TR>';
  echo '<TD class="mtitle"><A class="mtitle" HREF="enc_jrn.php">Encodage </A></TD>';
  echo '<TD class="mtitle"<A class="mtitle" HREF="form.php">Formulaire </A> </TD>'; 
  echo '<TD class="mtitle"><A class="mtitle" HREF="impress.php">Impression</A></TD>';
  echo '<TD class="mtitle"><A class="mtitle" HREF="fiche.php">Fiche</A></TD>';
  echo '<TD class="mtitle"><A class="mtitle" HREF="pcmn_update.php?p_start=1">Plan Comptable</A></TD>';
  echo '<TD class="mtitle"><A class="mtitle" HREF="jrn_update.php">Journaux</A></TD>';
  echo '<TD class="mtitle"><A class="mtitle" HREF="central.php">Centralise</A></TD>';
  echo '<TD class="mtitle"><A class="mtitle" HREF="dossier_prefs.php">Paramètres</A></TD>';


  echo "</TR>";
  echo "</TABLE>";
  echo '</div>';
  }
/* function c_ShowMenuJrnUser($p_dossier,$p_user)
 * Purpose : Show the Menu from the jrn encode
 *           page
 * 
 * parm : 
 *	- $p_dossier
 *      - $p_user
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 

function c_ShowMenuJrnUser($p_dossier,$p_user)
{

    echo '<div class="searchmenu">';
    echo '<TABLE>';

    include_once("postgres.php");
    $l_jrn=sprintf("dossier%d",$p_dossier);
    $Cn=DbConnect($l_jrn);
    if ( CheckAdmin() ==0) {
      $Ret=ExecSql($Cn,"select jrn_def_id,jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_type_id,jrn_desc,uj_priv,
                               jrn_deb_max_line,jrn_cred_max_line
                             from jrn_def join jrn_type on jrn_def_type=jrn_type_id
                             join user_sec_jrn on uj_jrn_id=jrn_def_id 
                             where
                             uj_login='$p_user'
                             and uj_priv !='X'
                             ");
    } else {
      $Ret=ExecSql($Cn,"select jrn_def_id,jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_deb_max_line,jrn_cred_max_line,
                            jrn_type_id,jrn_desc,'W' as uj_priv
                             from jrn_def join jrn_type on jrn_def_type=jrn_type_id");

    } 
    $Max=pg_NumRows($Ret);
    include_once("check_priv.php");

    for ($i=0;$i<$Max;$i++) {
      $l_line=pg_fetch_array($Ret,$i);
      // Admin have always rights
      if ( CheckAdmin() == 0 ){
	$right=CheckJrn($p_dossier,$p_user,$l_line['jrn_def_id']);
      }else {
	$right=3;
      }

      printf ('<TR><TD class="cell">%s</TD>',$l_line['jrn_def_name']);
      if ( $right > 0 ) {
	// Lecture 
	printf ('<TD class="mltitle"><A class="mtitle" HREF="enc_jrn.php?p_jrn=%s&action=view">Voir</A></TD>',
		$l_line['jrn_def_id']);
      }
      // ecriture
      if ( $right >  1 ) {
	printf ('<TD class="mltitle"><A class="mtitle" HREF="enc_jrn.php?p_jrn=%s&action=record&max_deb=%s&max_cred=%s">Encoder</A></TD></TR>',
		$l_line['jrn_def_id'],
		$l_line['jrn_deb_max_line'],
		$l_line['jrn_cred_max_line']);
      }
    }
    echo "</TABLE>";
    echo '</div>';

}

?>
