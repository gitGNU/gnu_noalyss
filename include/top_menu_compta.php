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

/* function ShowMenu
 * Purpose : Show The login menu
 * 
 * parm : 
 *	- $p_admin 1 if admin
 *      - $p_item item to add 
 * gen :
 *	- none
 * return:
 *	- nothing
 *
 */ 
  function ShowMenu($p_admin,$p_check = 1,$p_item="")
{
  /* $p_check == 1 si test sur Admin */
  include_once("postgres.php");
  echo '<div class="mtitle">';
  echo "<TABLE align=center cellspadding=0><TR>";
  if ( $p_admin !=0 ) {
      /* Administrator Menu */
      echo '<TD class="mtitle"><A class="mtitle" HREF=admin_repo.php>Administrator Menu</A></TD>';
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

function ShowMenuCompta($p_dossier,$pa_user)
{
  if ( ! isset ( $pa_user ) ) {
	exit (1);
  }
  if ( $pa_user['use_usertype'] == 'compta' ) {
    include_once ("compta_menu.php");
    c_ShowMenuCompta($p_dossier);
  } else 
    if ( $pa_user['use_usertype']=='user') {
      include_once ("user_menu.php");
      u_ShowMenuCompta($p_dossier);
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
// TODO 
// Only for developper 
// A test must be added
    echo '<TR><TD colspan="3" class="mshort">
         <A class="mtitle" HREF="fiche.php?action=add_modele&fiche=modele">Creation</A></TD></TR>';
    $Res=ExecSql($cn,"select fd_id,fd_label from fiche_def order by fd_label");
    $Max=pg_NumRows($Res);
    for ( $i=0; $i < $Max;$i++) {
      $l_line=pg_fetch_array($Res,$i);
      printf('<TR><TD class="cell">%s</TD><TD class="mshort"><A class="mtitle" HREF="fiche.php?action=vue&fiche=%d">Voir</A>
              </TD>
              <TD class="mshort"><A class="mtitle" HREF="fiche.php?action=modifier&fiche=%d">Modifier</A></TD>
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
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="user_sec.php">Sécurité</A></TD></TR>';
    echo "</TABLE>";
    echo '</div>';
    break;

  }
}
/* function ShowMenuComptaRight
 * Purpose : Display menu on the Right
 *           (preference, logout and admin)
 * parm : 
 *	- $p_dossier the current dossier
 *      - $p_auser	array $g_UserProperty
 *      - $p_more code
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 
function ShowMenuComptaRight($p_dossier=0,$p_auser,$p_more="")
{
  if ( ! isset ( $p_auser['use_usertype']))  {
    exit (1);
  }
  if ( $p_auser['use_usertype']=='compta') {
    include_once("compta_menu.php");
    c_ShowMenuComptaRight($p_dossier,$p_auser['use_admin'],$p_more);
  }
  else 
    if ( $p_auser['use_usertype']=='user') {
      include_once("user_menu.php");
      u_ShowMenuComptaRight($p_dossier,$p_auser['use_admin'],$p_more);
       }
}
/* function ShowMenuAdminGlobalRight($p_dossier=0)
 * Purpose :
 *        Same as previous : show the right menu
 * parm : 
 *	- p_dossier
 * gen :
 *	- none
 * return:
 *	- none
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
/* function ShowMenuPcmn($p_start=1)
 * Purpose : Show the menu from the pcmn page
 * 
 * parm : 
 *	- $p_start class start
 * gen :
 *	- none
 * return:
 *	- none
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
/* function  ShowMenuJrn($p_dossier)
 * Purpose : Show the menu in the jrn page
 * 
 * parm : 
 *	- $p_dossier 
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 

function ShowMenuJrn($p_dossier)
{
    echo '<div class="lmenu">';
    echo '<TABLE>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="jrn_add.php">Création </A></TD></TR>';
    include_once("postgres.php");
    $l_jrn=sprintf("dossier%d",$p_dossier);
    $Cn=DbConnect($l_jrn);
    $Ret=ExecSql($Cn,"select jrn_def_id,jrn_def_name,
                             jrn_def_class_deb,jrn_def_class_cred,jrn_type_id,jrn_desc 
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
/* function ShowMenuJrnUser($p_dossier,$p_user)
 * Purpose : Display and Show the Menu from the jrn encode
 *           page following the use_usertype (compta,user,...)
 * 
 * parm : 
 *	- $p_dossier
 *      - $p_auser
 *      - $p_type (ACH, BQE, VEN)
 *      - $p_jrn  what journal
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 

function ShowMenuJrnUser($p_dossier,$p_auser,$p_type=-1,$p_jrn=-1)
{
   if ( ! isset ($p_auser['use_usertype'] ) ) exit (-1);
  if ($p_auser['use_usertype'] == 'compta' ) {
    include_once("compta_menu.php");
    c_ShowMenuJrnUser($p_dossier,$p_auser['use_login']);
  } 
  if ($p_auser['use_usertype'] == 'user' ) {
    include_once("user_menu.php");
    u_ShowMenuJrnUser($p_dossier,$p_auser['use_login'],$p_type,$p_jrn);
  } 

}
/* function ShowMenuRecherche
 * Purpose :
 * 
 * parm : 
 *	- $p_dossier,
 *      - $p_jrn jrn id
 *      - $p_array=null previous search
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 

function ShowMenuRecherche($p_dossier,$p_jrn,$p_array=null)
{
  echo_debug(__FILE__,__LINE__,"ShowMenuRecherche($p_dossier,$p_jrn,$p_array)");
  if ( $p_array != null ) {
    foreach ( $p_array as $key=> $element) {
      ${"p_$key"}=$element;
      echo_debug(__FILE__,__LINE__,"p_$key =$element;");
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
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="form.php?action=add">Ajout</A></TD></TR>';
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
/* function  ShowMenuJrnUserImp(
 *
 * 
 * Purpose : Show the menu of jrn to print
 * 
 * parm : 
 *	- $p_cn, connection
 *        $p_user, user
 *         $p_dossier dossier
 * gen :
 *	- none
 * return:
 *	- none
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

      printf ('<TR><TD class="cell"><a class="mtitle"
       href="user_impress.php?type=jrn&action=print&p_id=%d">%s</a></TD></TR>',$l_line['jrn_def_id'],$l_line['jrn_def_name']);

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
      printf ('<TR><TD class="cell"><a class="mtitle" href="user_impress.php?type=jrn&action=print&p_id=0">Grand Livre </TD></TR>');
      
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


	printf ('<TD class="mshort"><A class="mtitle" 
              HREF="impress.php?p_id=%s&action=viewhtml&type=form">Html</A></TD>',
		$l_line['fr_id']);

	printf ('<TD class="mshort"><A class="mtitle" 
              HREF="impress.php?p_id=%s&action=viewpdf&type=form">Pdf</A></TD></TR>',
		$l_line['fr_id']
		);

    }
    echo "</TABLE>";
    // poste &
    echo "<TABLE>";
    echo '<TR><TD style="background-color:#4F8DFF;color:white" COLSPAN="3" > Poste </TD></TR>'; 


    printf ('<TR><TD class="cell"><A class="mtitle" 
              HREF="impress.php?action=viewhtml&type=poste">Poste
	      </A>
	      </TD>
	      </TR>');


    echo "</TABLE>";

    echo "</DIV>";
}
/* function ShowMenuAdminGlobal */
/* purpose : show the menu for user/database management */
/* parameter : none */
/* return : none */
function ShowMenuAdminGlobal()
{
  include_once("ac_common.php");
  $item[0]=array("admin_repo.php?action=user_mgt","Utilisateurs");
  $item[1]=array("admin_repo.php?action=dossier_mgt","Dossiers");
  $item[2]=array("admin_repo.php?action=modele_mgt","Modèles");
  $item[3]=array("login.php","Accueil");
  $item[4]=array("logout.php","Logout");
  $menu=ShowItem($item,'H');
  echo '<DIV >';
  echo $menu;
  echo '</DIV>';
}
?>
