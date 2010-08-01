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
 *   Foundation, Inshowc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
/*!\file
 * \brief Nearly all the menu are here, some of them returns a HTML string, others echo
 * directly the result.
 */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/* $Revision$ */

require_once("class_idate.php");
require_once("class_icard.php");
require_once("class_ispan.php");


/*!
 * \brief   Show all the available folder  for the users
 *          at the login page. For the special case 'E'
 *          go directly to extension and bypasse the dashboard
 * \param $p_user user
 * \param $p_admin 1 if admin
 *
 * \return table in HTML
 *
 */
function u_ShowDossier($p_user,$p_admin,$p_filtre="")
{
  $p_array=GetAvailableFolder($p_user,$p_admin,$p_filtre);

  $result="";
  if ( $p_array == 0 ) return $result." * Aucun dossier *";
  $cn=new Database();
  $user=new User($cn);


  $result.="<TABLE style=\"width:auto;border-width:0px;border-collapse:collapse;\">";
  for ($i=0;$i<sizeof($p_array);$i++) {

    $id=$p_array[$i]['dos_id'];
    $name= $p_array[$i]['dos_name'];
    $desc=$p_array[$i]['dos_description'];
    if ( $i%2 == 0)
      $tr="odd";
    else $tr="even";
    if ( $user->check_dossier($id)!='P') {
    	$target="access.php?gDossier=$id";
    } else {
    	$target="extension.php?gDossier=$id";
    }

    $result.="<TR class=\"$tr\">";

    $result.="<TD class=\"$tr\">";
    $result.="<A class=\"dossier\" HREF=\"$target\">";
    $result.=$id."  <B>".h($name)."</B>";
    $result.="</A>";
    $result.="</TD>";
    $desc=($desc=="")?"<i>Aucune description</i>":h($desc);
    $desc="<A class=\"dossier\" HREF=\"$target\">$desc</A>";
    $result.="<TD class=\"$tr\" style=\"padding-left:50px\">".$desc;
    $result.="</TD>";
    $result.="</TR>";

  }
  $result.="</TABLE>";
  return $result;
}
/*!
 * \brief   Get all the available folders
 *          for the users, checked with the security
 *
 * \param  $p_user user login
 * \param  $p_admin 1 if admin
 * \return array containing
 *       - ac_dossier.dos_id
 *       - ac_dossier.dos_name
 *       - ac_dossier.dos_description
 *
 */
function GetAvailableFolder($p_user,$p_admin,$p_filter="")
{

  $filter="";
  if ($p_admin==0) {
    // show only available folders
    // if user is not an admin
    $sql="select distinct dos_id,dos_name,dos_description from ac_users
                  natural join jnt_use_dos
                  natural join  ac_dossier
                  join  priv_user on ( priv_jnt=jnt_id)
          where use_active=1
         and use_login='$p_user'
         and priv_priv != 'X' and dos_name ilike '%$p_filter%'
          order by dos_name";

  } else {
    $sql="select distinct dos_id,dos_name,dos_description from ac_dossier
      where  dos_name ilike '%$p_filter%' order by dos_name";
  }
  require_once('class_database.php');
  $cn=new Database();

  $Res=$cn->exec_sql($sql);
  $max=Database::num_row($Res);
  if ( $max == 0 ) return 0;

  for ($i=0;$i<$max;$i++) {
    $array[]=Database::fetch_array($Res,$i);
  }
  return $array;
}
/*!
 * \brief show the top menu for the user profile
 *        and highight the selected one
 * \param  p_high what to hightlight, by default it is autodetected
 *         but sometimes it must be given. Default value=""
 *
 * \return string
 *
 */
function ShowMenuCompta($p_high="")
{
  require_once('class_database.php');

  // find our current menu
  $default=basename($_SERVER['PHP_SELF']);

  switch ($default) {
  case "user_jrn.php":
    $default.="?show&".dossier::get();
    break;
  }

  if ( $p_high !== "" ) $default=$p_high;
  $default=-1;
  if  ( isset($_REQUEST['p_action']))
	{
	  switch ($_REQUEST['p_action'] ) {
	  case 'impress':
	    $default=5;
	    break;
	  case 'fiche':
	    $default=6;
	    break;
	  case 'quick_writing':
	    $default=4;
	    break;
	  case 'gl':
	    $default=0;
	    break;
	  case 'ven':
	    $default=1;
	    break;
	  case 'client':
	    $default=1;
	    break;
	  case 'ach':
	  case 'fournisseur':
	    $default=2;
	    break;
	  case 'fin':
	    $default=3;
	    break;
	  case 'let':
	    $default=10;
	    break;
	  }
	}

  $str_dossier=dossier::get();
  $p_array=array(
		 array("compta.php?p_action=gl&".$str_dossier ,_("Historique"),_("Historique : toutes les opérations"),0),
		 array("compta.php?p_action=ven&".$str_dossier ,_("Recette"),_("Journal de vente, produit et recette"),1),
		 array("compta.php?p_action=ach&".$str_dossier,_("Dépense"),_("Journaux de dépense, d'achat"),2 ),
		 array("compta.php?p_action=fin&".$str_dossier,_("Financier"),_("Journaux financiers: les banques, la caisse"),3),
		 array('compta.php?p_action=quick_writing&'.$str_dossier,_('Opération Diverses'),_('Journaux d\'OD: salaires, déclarations TVA,...'),4),

		 array("compta.php?p_action=impress&".$str_dossier,_("Impression"),_("Impression"),5),
		 array("compta.php?p_action=fiche&".$str_dossier,_("Fiche"),_("Ajouter, modifier ou effacer des fiches"),6),
		 array("compta.php?p_action=let&".$str_dossier,_("Lettrage"),_("Ajouter, modifier ou effacer des lettrage"),10),
		 array("user_advanced.php?".$str_dossier,_("Avancé"),_("Opérations délicates"),7),
		 );

  $result=ShowItem($p_array,'H',"mtitle","mtitle",$default,' width="100%"');
  $str_dossier=dossier::get();
  $r="";
  $r.=menu_tool("compta.php");
  $r.='<div class="topmenu">';
  $r.=$result;
  $r.='</div>';

  return $r;
}


/*!
 **************************************************
 * \brief   build the menu of user_advanced.php
 *
 * \param $default
 *
 * \return the menu
 */
function ShowMenuAdvanced($default="") {
  $str_dossier=dossier::get();
  // Show the left menu
  $left_menu=ShowItem(array(
		//('rapprt.php','Rapprochement'),

			    array('user_advanced.php?'.$str_dossier.'&p_action=preod',_('Ecritures definies'),"",9),
			    array('user_advanced.php?p_action=periode&'.$str_dossier,_('Periode'),_("Gestion des periodes"),2),
			    array('compta.php?p_action=stock&'.$str_dossier,'Stock',_("Gestion des stocks"),5),
			    array('user_advanced.php?p_action=defreport&'.$str_dossier,_('Rapport'),_("Rapport"),6),
			    array('import.php?'.$str_dossier,_('Import Banque'),_("Banque"),7),
			    array('user_advanced.php?p_action=ouv&'.$str_dossier,_('Ecriture ouverture'),"",8),
			    array('user_advanced.php?p_action=verif&'.$str_dossier,_('Vérification'),"",10)
	),
					  'H',"mtitle","mtitle",$default);
 $r='<div class="u_subtmenu">'.$left_menu."</div>";
 return $r;
}
/*!
 * \brief  Show the menu for the card management
 *
 * \param $p_dossier dossier 1
 *
 *
 *
 * \return nothing
 */
function ShowMenuFiche($p_dossier)
{
     $cn=new Database($p_dossier);
	 $str_dossier=dossier::get();
     echo '<div class="lmenu">';
     echo '<TABLE>';

      echo '<TR><TD colspan="1" class="mtitle"  style="width:auto" >
          <A class="mtitle" HREF="?p_action=fiche&action=add_modele&fiche=modele&'.$str_dossier.'">'._('Création').'</A></TD>
          <TD><A class="mtitle" HREF="?p_action=fiche&'.$str_dossier.'">'._('Recherche').'</A></TD>
           </TR>';
     $Res=$cn->exec_sql("select fd_id,fd_label from fiche_def order by fd_label");
     $Max=Database::num_row($Res);
     for ( $i=0; $i < $Max;$i++) {
       $l_line=Database::fetch_array($Res,$i);
       printf('<TR><TD class="cell">
             <A class="mtitle" HREF="?p_action=fiche&action=modifier&fiche=%d&%s">%s</A></TD>
               <TD class="mshort">
               <A class="mtitle" HREF="?p_action=fiche&action=vue&fiche=%d&%s">Liste</A>
               </TD>
               </TR>',
            $l_line['fd_id'],
			  $str_dossier,
            $l_line['fd_label'],
			  $l_line['fd_id'],
			  $str_dossier

            );
     }
     echo "</TABLE>";
     echo '</div>';
}
/*!   MenuAdmin */
/* \brief show the menu for user/database management
/*
/* \return HTML code with the menu
*/

function MenuAdmin()
{
  $def=-1;
  if (isset($_REQUEST['UID']))
	$def=0;
  if ( isset ($_REQUEST['action'])){
	switch ($_REQUEST['action']) {
	case 'user_mgt':
	  $def=0;
	  break;
	case 'dossier_mgt':
	  $def=1;
	  break;
	case 'modele_mgt':
	  $def=2;
	  break;
	case 'restore';
	  $def=3;
	  break;
	}
  }
  $item=array (array("admin_repo.php?action=user_mgt",_("Utilisateurs"),_('Gestion des utilisateurs'),0),
	       array("admin_repo.php?action=dossier_mgt",_("Dossiers"),_('Gestion des dossiers'),1),
	       array("admin_repo.php?action=modele_mgt",_("Modèles"),_('Gestion des modèles'),2),
	       array("admin_repo.php?action=restore",_("Restaure"),_("Restaure une base de données"),3),
	       array("login.php",_("Accueil"))
	       );

  $menu=ShowItem($item,'H',"mtitle","mtitle",$def,' style="width:80%;margin-left:10%" ');
  return $menu;
}
/*!
 * \brief  Show the parameter menu
 *
 * \param $p_action the last action choosed
 *
 *
 *
 * \return HTML Code
 *
 *
 */
function ShowMenuParam($p_action="")
{
  $s=dossier::get();
  $sub_menu=ShowItem(array(

			   array('parametre.php?p_action=company&'.$s,_('Sociétés'),_('Parametre societe'),1),
			   array('parametre.php?p_action=divers&'.$s,_('Divers'),_('Devise, moyen de paiement'),2),
			   array('parametre.php?p_action=pcmn&'.$s,_('Plan Comptable'),_('Modification du plan comptable'),11),
			   array('parametre.php?p_action=ext&'.$s,_('Extension'),_('Extension'),3),
			   array('parametre.php?p_action=sec&'.$s,_('Sécurité'),_('securite'),8),
			   array('parametre.php?p_action=preod&'.$s,_('Ecritures définies'),_('Ecritures définies'),12),
			   array('parametre.php?p_action=document&'.$s,_('Document'),_('Facture, lettre de rappel, proposition...'),7),
			   array('parametre.php?p_action=jrn&'.$s,_('Journaux'),_('Creation et modification de journaux'),10)

			  ),
		    'H',"mtitle","mtitle",$p_action,' width="100%"');
    return $sub_menu;

}
/*!
 * \brief  display the html the menu in the jrn page
 *
 * \return nothing
 *
 *
 */

function MenuJrn()
{
	$str_dossier=dossier::get();
    echo '<TABLE>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="?p_action=jrn&sa=add&'.$str_dossier.'">'._('Création').' </A></TD></TR>';
    require_once('class_database.php');
    $Cn=new Database(dossier::id());
    $Ret=$Cn->exec_sql("select jrn_def_id,jrn_def_name,
                             jrn_def_class_deb,jrn_def_class_cred,jrn_type_id,jrn_desc
                             from jrn_def join jrn_type on jrn_def_type=jrn_type_id order by jrn_def_name");
    $Max=Database::num_row($Ret);

    for ($i=0;$i<$Max;$i++) {
      $l_line=Database::fetch_array($Ret,$i);
      printf ('<TR><TD class="mtitle"><A class="mtitle" HREF="?p_action=jrn&sa=detail&p_jrn=%s&'.$str_dossier.'">%s</A></TD></TR>',
	      $l_line['jrn_def_id'],$l_line['jrn_def_name']);

    }
    echo "</TABLE>";
}
/*!
 * \brief  Show the menu from the pcmn page
 *
 * \param $p_start class start default=1
 *
 *
 *
 * \return nothing
 *
 *
 */

function ShowMenuPcmn($p_start=1)
{
  $str_dossier="&".dossier::get();
    echo '<TABLE>';
    echo '<TR><TD class="mtitle"><A class="mtitle"  HREF="?p_action=pcmn&p_start=0'.$str_dossier.'">0'._(' Hors Bilan').' </A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle"  HREF="?p_action=pcmn&p_start=1'.$str_dossier.'">1'._(' Immobilisé').' </A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle"  HREF="?p_action=pcmn&p_start=2'.$str_dossier.'">2 '._('Actif a un an au plus').'</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle"  HREF="?p_action=pcmn&p_start=3'.$str_dossier.'">3 '._('Stock et commande').'</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle"  HREF="?p_action=pcmn&p_start=4'.$str_dossier.'">4 '._('Compte tiers').'</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="?p_action=pcmn&p_start=5'.$str_dossier.'">5'._('Actif').'</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle"  HREF="?p_action=pcmn&p_start=6'.$str_dossier.'">6'._('Charges').'</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="?p_action=pcmn&p_start=7'.$str_dossier.'">7'._('Produits').'</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="?p_action=pcmn&p_start=8'.$str_dossier.'">8'._('Hors Comptabilité').'</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="?p_action=pcmn&p_start=9'.$str_dossier.'">9 '._('Hors Comptabilité').'</A></TD></TR>';
    echo "</TABLE>";
}


/*!
 * \brief Show the menu for importing, verify and transfert Bank CSV
 *
 * \return nothing
 */
function ShowMenuImport(){
  $str_dossier=dossier::get();

  echo '<TABLE>';
  echo '<TR><TD class="mtitle"><A class="mtitle" HREF="import.php?action=import&'.$str_dossier.'">'._('Importer CSV').'</A></TD></TR>';
  echo '<TR><TD class="mtitle"><A class="mtitle" HREF="import.php?action=verif&'.$str_dossier.'">'._('Verif CSV').'</A></TD></TR>';
  echo '<TR><TD class="mtitle"><A class="mtitle" HREF="import.php?action=transfer&'.$str_dossier.'">'._('Transfert CSV').'</A></TD></TR>';
  echo '<TR><TD class="mtitle"><A class="mtitle" HREF="import.php?action=purge&'.$str_dossier.'">'._('Purge CSV').'</A></TD></TR>';

  echo "</TABLE>";
}
/*!\brief show the top menu to access the different modules in top
 * \param $p_from from which module this function is called
 * \note must include javascript : acc_ledger.js
 * \return string
*/
function menu_tool($p_from) {

  if ( ! isset ($_REQUEST['gDossier']))
    return "" ;
  $r="";

  $r.= '<div class="u_tool">';
  $r.= '<div class="name">';
  $r.= "<H2 class=\"dossier\"> Dossier : ".h(dossier::name())."</h2> ";
  $r.= '</div>';
  $r.= '<div class="acces_direct">';
  if ( $p_from == 'compta') $view='E';
	else $view='S';
  $agent=$_SERVER['HTTP_USER_AGENT'];

  $amodule=array(
		 array('value'=>'access.php','label'=>_('Tableau de bord')),
		 array('value'=>'user_pref.php','label'=>_('Preference')),
		 array('value'=>'parametre.php','label'=>_('Paramètre')),
		 array('value'=>'user_login.php','label'=>_('Autre Dossier')),
		 array('value'=>'logout.php','label'=>_('Deconnexion')),
		 array('value'=>'new_line'),
		 array('value'=>'compta.php','label'=>_('Compta Générale')),
		 array('value'=>'commercial.php','label'=>_('Gestion')),
		 array('value'=>'comptanalytic.php','label'=>_('Compt. Analytique')),
		 array('value'=>'extension.php','label'=>_('Extension'))
		 ) ;

  $gDossier=dossier::id();
  $r.=    '<table>';
  foreach($amodule as $col ) {
    $url=$col['value'].'?'.dossier::get();
    if ( $p_from==$col['value']) {
      $r.= '<td style="background-color:red">'.
	'<a class="mtitle" href="'.$url.'" >'.$col['label'].'</a>'.
	'</td>';

    } else {
      if ( $col['value']=='new_line') {
	$r.='</tr><tr>';
	continue;
      }
      $r.= '<td class="tool">'.
	'<a class="cell" href="'.$url.'" >'.$col['label'].'</a>'.
	'</td>';
    }

  }
   $r.='<td class="tool">';
  $r.= '<A class="cell" HREF="javascript:openRecherche('.$gDossier.')">'.
    _('Recherche').'</a></td>';
  $r.='</tr>';

  $r.= '</table>';
  $r.= '</div>';

  $r.= '</div>';
 return $r;
}
