<?

/*
 *   This file is part of WCOMPTA.
 *
 *   WCOMPTA is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   WCOMPTA is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with WCOMPTA; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Auteur Dany De Bontridder ddebontridder@yahoo.fr
/* $Revision$ */

/* function
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-
 *
 */ 
  function ShowMenu($p_check = 1,$p_item="")
{
  /* $p_check == 1 si test sur Admin */
  include_once("postgres.php");
  echo '<div class="mtitle">';
  echo "<TABLE align=center><TR>";
  if ( $p_check == 1 ) {
    if ( CheckAdmin() != 0 ) {
      /* Administrator Menu */
      echo '<TD class="mtitle"><A class="mtitle" HREF=admin_repo.php>Administrator Menu</A></TD>';
    } 
  } 
  if (strlen ( $p_item ) != 0 ) {
    echo '<TD class="mtitle">'.$p_item.'</A></TD>';
  }
  
  echo ' <TD class="mtitle"> <A class="mtitle" HREF="user_pref.php">Préférence</A></TD>';

  echo "<TD class=\"mtitle\">"; 
  html_button_logout();
  echo "</TD>";
  echo "</TR></TABLE>";
  echo "</div>";
}
/* function
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-
 *
 */ 

function ShowMenuAdmin($p_dossier)
{
  include_once("postgres.php");
  $l_name=GetDossierName($p_dossier);
  echo "<P> <H2 class=\"info\"> $l_name </H2></P>";
  echo '<div class="tmenu">';
  echo '<TABLE ALIGN="CENTER"><TR>';
  echo '<TD class="mtitle"><A class="mtitle" HREF="pcmn_update.php?p_start=1">Mise à jour Plan Comptable</A></TD>';
  echo '<TD class="mtitle"><A class="mtitle" HREF="jrn_update.php">Gestion Journaux</A></TD>';
  echo '<TD class="mtitle"><A class="mtitle" HREF="dossier_prefs.php">Préférences</A></TD>';
  echo '<TD class="mtitle"><A class="mtitle" HREF="user_sec.php">Sécurité Utilisateur</A></TD>';
  echo '</TR></TABLE>';
  echo "</DIV>";

}
/* function
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-
 *
 */ 

function ShowMenuCompta($p_dossier)
{
  include_once("postgres.php");
  $l_name=GetDossierName($p_dossier);
  echo "<P> <H2 class=\"info\"> $l_name </H2></P>";
  echo '<div class="tmenu">';
  echo '<TABLE><TR>';
  // TODO echo '<TD class="mtitle"><A class="mtitle" HREF="facturation.php">Facturation</A></TD>';
  echo '<TD class="mtitle"><A class="mtitle" HREF="enc_jrn.php">Encodage Journaux</A></TD>';
  echo '<TD class="mtitle"<A class="mtitle" HREF="form.php">Formulaire </A> </TD>'; 
  echo '<TD class="mtitle"><A class="mtitle" HREF="impress.php">Impression</A></TD>';
  echo '<TD class="mtitle"><A class="mtitle" HREF="fiche.php">Fiche</A></TD>';
  echo '<TD class="mtitle"><A class="mtitle" HREF="pcmn_update.php?p_start=1">Plan Comptable</A></TD>';
  echo '<TD class="mtitle"><A class="mtitle" HREF="jrn_update.php">Gestion Journaux</A></TD>';
  echo '<TD class="mtitle"><A class="mtitle" HREF="central.php">Centralise</A></TD>';
  echo '<TD class="mtitle"><A class="mtitle" HREF="dossier_prefs.php">Paramètres</A></TD>';
  echo '<TD class="mtitle"><A class="mtitle" HREF="user_sec.php">Sécurité</A></TD>';

  echo "</TR>";
  echo "</TABLE>";
  echo '</div>';
}
/* function
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-
 *
 */ 
function ShowMenuComptaLeft($p_dossier,$p_item)
{
  switch ($p_item) {
  case MENU_FACT:
    echo '<div class="lmenu">';
    echo '<TABLE>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="not_implemented.php">Créer une facture</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="facturation.php?action=vue&fact=all">Voir les factures</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="facturation.php?action=vue&fact=impaye">Facture non payées</A></TD></TR>';
    echo "</TABLE>";
    echo '</div>';
    break;
  case MENU_FICHE:
    $l_dossier=sprintf("dossier%d",$p_dossier);
    $cn=DbConnect($l_dossier);
    echo '<div class="lmenu">';
    echo '<TABLE>';
    echo '<TR><TD colspan="3" class="mtitle">
         <A class="mtitle" HREF="fiche.php?action=add_modele&fiche=modele">Creation Modele</A></TD></TR>';
    $Res=ExecSql($cn,"select fd_id,fd_label from fichedef order by fd_label");
    $Max=pg_NumRows($Res);
    for ( $i=0; $i < $Max;$i++) {
      $l_line=pg_fetch_array($Res,$i);
      printf('<TR><TD class="cell">%s</TD><TD class="mtitle"><A class="mtitle" HREF="fiche.php?action=vue&fiche=%d">Voir</A>
              </TD>
              <TD class="mtitle"><A class="mtitle" HREF="fiche.php?action=modifier&fiche=%d">Modifier</A></TD>
              </TR>',
	     $l_line['fd_label'],
	     $l_line['fd_id'],
	     $l_line['fd_id']);
    }
    echo "</TABLE>";
    echo '</div>';
    break;
  case MENU_PARAM:
    $l_dossier=sprintf("dossier%d",$p_dossier);
    $cn=DbConnect($l_dossier);
    echo '<div class="lmenu">';
    echo '<TABLE>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="dossier_prefs.php?p_action=devise">Devises</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="dossier_prefs.php?p_action=periode">Périodes</A></TD></TR>';
    echo "</TABLE>";
    echo '</div>';
    break;

  }
}
/* function
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-
 *
 */ 
function ShowMenuComptaRight($p_dossier=0,$p_more="")
{
  include_once("ac_common.php");
  echo '<div class="rmenu">';
  echo '<TABLE>';
  if ( CheckAdmin() != 0 && $p_dossier != 0) {
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
/* function
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-
 *
 */ 
function ShowMenuAdminGlobalRight($p_dossier=0)
{
  include_once("ac_common.php");
  echo '<div class="rmenu">';
  echo '<TABLE>';
  echo '<TR><TD class="mtitle"> <A class="mtitle" HREF="login.php">Accueil</A></TD></TR>';
  echo '<TR><TD class="mtitle"> <A class="mtitle" HREF="user_pref.php">Preference</A></TD></TR>';
  if ( $p_dossier != 0) {
printf( '<TR><TD class="mtitle"> <A class="mtitle" HREF="compta.php?dos=%d">Retour Dossier</A></TD></TR>',
	$p_dossier);
  }

  echo '<TR><TD class="mtitle"> ';
  html_button_logout();
  echo ' </TD></TR>';
  echo "</TABLE>";
  echo '</div>';
}
/* function
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-
 *
 */ 

function ShowMenuPcmn($p_start=1)
{
    echo '<div class="lmenu">';
    echo '<TABLE>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="pcmn_update.php?p_start=1">1 Immobilisé </A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="pcmn_update.php?p_start=2">2 Actif a un an au plus</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="pcmn_update.php?p_start=3">3 Stock et commande</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="pcmn_update.php?p_start=4">4 Compte tiers</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="pcmn_update.php?p_start=5">5 Actif</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="pcmn_update.php?p_start=6">6 Charges</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="pcmn_update.php?p_start=7">7 Produits</A></TD></TR>';
    echo "</TABLE>";
    echo '</div>';

}
/* function
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-
 *
 */ 

function ShowMenuJrn($p_dossier)
{
    echo '<div class="lmenu">';
    echo '<TABLE>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="jrn_add.php">Création Journal </A></TD></TR>';
    include_once("postgres.php");
    $l_jrn=sprintf("dossier%d",$p_dossier);
    $Cn=DbConnect($l_jrn);
    $Ret=ExecSql($Cn,"select jrn_def_id,jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_type_id,jrn_desc 
                             from jrn_def join jrn_type on jrn_def_type=jrn_type_id");
    $Max=pg_NumRows($Ret);
    for ($i=0;$i<$Max;$i++) {
      $l_line=pg_fetch_array($Ret,$i);
      printf ('<TR><TD class="mtitle"><A class="mtitle" HREF="jrn_detail.php?p_jrn=%s">%s</A></TD></TR>',
	      $l_line['jrn_def_id'],$l_line['jrn_def_name']);

    }
    echo "</TABLE>";
    echo '</div>';

}
/* function
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-
 *
 */ 

function ShowMenuJrnUser($p_dossier,$p_user)
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
	printf ('<TD class="mtitle"><A class="mtitle" HREF="enc_jrn.php?p_jrn=%s&action=view">Voir</A></TD>',
		$l_line['jrn_def_id']);
      }
      // ecriture
      if ( $right >  1 ) {
	printf ('<TD class="mtitle"><A class="mtitle" HREF="enc_jrn.php?p_jrn=%s&action=record&max_deb=%s&max_cred=%s">Encoder</A></TD></TR>',
		$l_line['jrn_def_id'],
		$l_line['jrn_deb_max_line'],
		$l_line['jrn_cred_max_line']);
      }
    }
    echo "</TABLE>";
    echo '</div>';

}
/* function
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-
 *
 */ 

function ShowMenuRecherche($p_dossier,$p_jrn,$p_array=null)
{
  echo_debug("ShowMenuRecherche($p_dossier,$p_jrn,$p_array)");
  if ( $p_array != null ) {
    foreach ( $p_array as $key=> $element) {
      ${"p_$key"}=$element;
      echo_debug("p_$key =$element;");
    }
  }
  $opt='<OPTION VALUE="<="> <=';
  $opt.='<OPTION VALUE="<"> <';
  $opt.='<OPTION VALUE="="> =';
  $opt.='<OPTION VALUE=">"> >';
  $opt.='<OPTION VALUE=">="> >=';
  if ( ! isset ($p_date_start)) $p_date_start="";
  if ( ! isset ($p_date_end))   $p_date_end="";
  if ( ! isset ($p_mont_sel))$p_mont_sel="";
  if ( ! isset ($p_s_comment))$p_s_comment="";
  if ( ! isset ($p_s_montant)) $p_s_montant="";

  if ( $p_mont_sel != "" )  $opt.='<OPTION value="'.$p_mont_sel.'" SELECTED> '.$p_mont_sel;

  echo '<div class="searchmenu">';
  echo '<div style="border-style:outset;border-width:1pt;">';
  echo "<B>Recherche</B>";
  echo '<FORM ACTION="enc_jrn.php" METHOD="POST">';
  echo '<TABLE>';
  echo "<TR>";
  echo '<TD COLSPAN="3">  Date compris entre</TD> ';
  echo "</TR> <TR>";
  echo '<TD> <INPUT TYPE="TEXT" NAME="date_start" SIZE="10" VALUE="'.$p_date_start.'"></TD>';
  echo '<TD> <INPUT TYPE="TEXT" NAME="date_end" size="10" Value="'.$p_date_end.'"></TD>';
  echo '</TD><TD>';
  echo "</TR> <TR>";
  echo "<TD> Montant ";
  echo ' <SELECT NAME="mont_sel">'.$opt.' </SELECT></TD><TD>';
  echo ' <INPUT TYPE="TEXT" NAME="s_montant" SIZE="10" VALUE="'.$p_s_montant.'"></TD>';
  echo "</TR><TR>";
  echo '<TD colspan="3"> Le commentaire contient </TD>';
  echo "</TR><TR>";
  echo '<TD COLSPAN="3"> <INPUT TYPE="TEXT" NAME="s_comment" VALUE="'.$p_s_comment.'"></TD>';
  echo "</TR><TR>";
  echo '<TD COLSPAN="3"><INPUT TYPE="SUBMIT" VALUE="Recherche" NAME="viewsearch"></TD>';
  echo "</TR>";
  echo "</TABLE>";
  echo "</FORM>";
  echo '</div>';
  echo '</div>';

}
/* function
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-
 *
 */ 
function ShowMenuComptaForm($p_dossier) {
    include_once("postgres.php");
    $l_dossier=sprintf("dossier%d",$p_dossier);
    $cn=DbConnect($l_dossier);
    echo '<div class="lmenu">';
    echo '<TABLE>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="form.php?action=add">Ajout de formulaire </A></TD></TR>';
    $l_jrn=sprintf("dossier%d",$p_dossier);
    $Cn=DbConnect($l_jrn);
    $Ret=ExecSql($Cn,"select fr_id, fr_label 
                             from formdef order by fr_label");
    $Max=pg_NumRows($Ret);
    for ($i=0;$i<$Max;$i++) {
      $l_line=pg_fetch_array($Ret,$i);
      printf ('<TR><TD class="mtitle"><A class="mtitle" HREF="form.php?action=view&fr_id=%s">%s</A></TD></TR>',
	      $l_line['fr_id'],$l_line['fr_label']);

    }
    echo "</TABLE>";
    echo '</div>';
}
/* function
 * Purpose :
 * 
 * parm : 
 *	- 
 * gen :
 *	-
 * return:
 *	-
 *
 */ 

function ShowMenuJrnUserImp($p_cn,$p_user,$p_dossier)
{
    echo '<div class="lmenu">';
    include_once("preference.php");
    echo '<TABLE>';
    echo '<TR><TD style="background-color:#4F8DFF;color:white;font-style:bold;align:center" COLSPAN="3" > Journaux</TD></TR>'; 
    include_once("postgres.php");
    if ( CheckAdmin() ==0) {
      $Ret=ExecSql($p_cn,"select jrn_def_id,jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_type_id,jrn_desc,uj_priv,
                               jrn_deb_max_line,jrn_cred_max_line
                             from jrn_def join jrn_type on jrn_def_type=jrn_type_id
                             join user_sec_jrn on uj_jrn_id=jrn_def_id 
                             where
                             uj_login='$p_user'
                             and uj_priv !='X'
                             ");
    } else {
      $Ret=ExecSql($p_cn,"select jrn_def_id,jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_deb_max_line,jrn_cred_max_line,
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


      printf ('<TD class="mtitle"><A class="mtitle" HREF="impress.php?p_id=%s&action=viewhtml&type=jrn&filter=%d">Html</A></TD>',
	      $l_line['jrn_def_id'],YES);
      
      printf ('<TD class="mtitle"><A class="mtitle" HREF="impress.php?p_id=%s&action=viewpdf&type=jrn&filter=%d">Pdf</A></TD></TR>',
	      $l_line['jrn_def_id'],YES
	      );

    }
    $NoPriv=CountSql($p_cn,"select jrn_def_id,jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_type_id,jrn_desc,uj_priv,
                                jrn_deb_max_line,jrn_cred_max_line
                             from jrn_def join jrn_type on jrn_def_type=jrn_type_id
                             join  user_sec_jrn on uj_jrn_id=jrn_def_id 
                             where  
                             uj_login='$p_user'
                             and uj_priv ='X'
                   ");
    // Pour voir tout les journal ?
    if ( $NoPriv == 0 ) {
      printf ('<TR><TD class="cell">Grand Livre Non Centralisé</TD>');
      printf ('<TD class="mtitle"><A class="mtitle" HREF="impress.php?p_id=all&action=viewhtml&type=jrn&filter=%d&central=no">Html</A></TD>',NO);
      printf ('<TD class="mtitle"><A class="mtitle" HREF="impress.php?p_id=all&action=viewpdf&type=jrn&filter=%d&central=no">Pdf</A></TD></TR>',NO);

      printf ('<TR><TD class="cell">Grand Livre Centralisé</TD>');
      printf ('<TD class="mtitle"><A class="mtitle" HREF="impress.php?p_id=all&action=viewhtml&type=jrn&filter=%d&central=yes">Html</A></TD>',NO);
      printf ('<TD class="mtitle"><A class="mtitle" HREF="impress.php?p_id=all&action=viewpdf&type=jrn&filter=%d&central=yes">Pdf</A></TD></TR>',NO);

    }
    echo "</TABLE>";
    // Formulaire
    echo "<TABLE>";
    echo '<TR><TD style="background-color:#4F8DFF;color:white" COLSPAN="3" > Formulaire</TD></TR>'; 

    $Ret=ExecSql($p_cn,"select fr_id, fr_label from formdef order by fr_label");
    $Max=pg_NumRows($Ret);

    for ($i=0;$i<$Max;$i++) {
      $l_line=pg_fetch_array($Ret,$i);
      printf ('<TR><TD class="cell">%s</TD>',$l_line['fr_label']);


	printf ('<TD class="mtitle"><A class="mtitle" 
              HREF="impress.php?p_id=%s&action=viewhtml&type=form">Html</A></TD>',
		$l_line['fr_id']);

	printf ('<TD class="mtitle"><A class="mtitle" 
              HREF="impress.php?p_id=%s&action=viewpdf&type=form">Pdf</A></TD></TR>',
		$l_line['fr_id']
		);

    }
    echo "</TABLE>";
    // poste &
    echo "<TABLE>";
    echo '<TR><TD style="background-color:#4F8DFF;color:white" COLSPAN="3" > Poste </TD></TR>'; 


    printf ('<TR><TD class="cell">Poste</TD>');


    printf ('<TD class="mtitle"><A class="mtitle" 
              HREF="impress.php?action=viewhtml&type=poste">Html</A></TD>'
		);
    
    printf ('<TD class="mtitle"><A class="mtitle" 
              HREF="impress.php?action=viewpdf&type=poste">Pdf</A></TD></TR>'
	    );


    echo "</TABLE>";

    echo "</DIV>";
}

?>
