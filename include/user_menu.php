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
 *          at the login page
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
  $result.="<TABLE style=\"width:75%;border-width:0px;border-collapse:collapse;\">";
  for ($i=0;$i<sizeof($p_array);$i++) {
    $id=$p_array[$i]['dos_id'];
    $name= $p_array[$i]['dos_name'];
    $desc=$p_array[$i]['dos_description'];
    if ( $i%2 == 0) 
      $tr="odd";
    else $tr="even";
    

    $result.="<TR class=\"$tr\">";

    $result.="<TD class=\"$tr\">";
    $result.="<A class=\"dossier\" HREF=\"access.php?gDossier=$id\">";
    $result.=$id."  <B>".h($name)."</B>";
    $result.="</A>";
    $result.="</TD>";
    $desc=($desc=="")?"<i>Aucune description</i>":h($desc);
    $desc="<A  class=\"dossier\" HREF=\"access.php?gDossier=$id\">".$desc."</a>";
    $result.="<TD class=\"$tr\">".$desc;
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
  case "recherche.php":
    $default.="?".dossier::get();
    break;
  case "fiche.php":
    $default.="?".dossier::get();
    break;
  }
  if ( $p_high !== "" ) $default=$p_high;

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
	  case 'bank':
	    $default=3;
	    break;

	  }
	}
  echo_debug('user_menu.php',__LINE__,'defaut is '.$default);

  $str_dossier=dossier::get();
  $p_array=array(
		 array("compta.php?p_action=gl&".$str_dossier ,"Grand Livre","Grand livre : toutes les opérations",0),
		 array("compta.php?p_action=ven&".$str_dossier ,"Vente","Journal de vente / produit",1),
		 array("compta.php?p_action=ach&".$str_dossier,"Dépense","Journaux de dépense, d'achat",2 ),
		 array("compta.php?p_action=bank&".$str_dossier,"Financier","Journaux financiers: les banques, la caisse",3),
		 array('compta.php?p_action=quick_writing&'.$str_dossier,'Ecriture directe','Ecriture directe dans les journaux',4),
		 
		 array("compta.php?p_action=impress&".$str_dossier,"Impression","Impression",5),
				 array("compta.php?p_action=fiche&".$str_dossier,"Fiche","Ajouter, modifier ou effacer des fiches",6),
		 array("user_advanced.php?".$str_dossier,"Avancé","Opérations délicates",7),
		 );

  $result=ShowItem($p_array,'H',"mtitle","mtitle",$default,' width="100%"');
  $str_dossier=dossier::get();
  $r="";
  $r.=menu_tool("user_compta.php");
  $r.='<div style="float:left;background-color:#879ED4;width:100%;">';
  $r.=$result;
  $r.='</div>';
  //  $r.='</div>';
  
  return $r;




}

/*!  
 * \brief Returns the form for the search (module accountancy)
 *        
 * \param : 
 * \param $p_cn database connection
 * \param $p_jrn jrn id
 * \param $p_array=null previous search
 *
 *
 * \return HTML Code
 *	
 *
 */ 

function u_ShowMenuRecherche($p_cn,$p_jrn,$p_sessid,$p_array=null)
{
  echo_debug('user_menu.php',__LINE__,"u_ShowMenuRecherche($p_cn,$p_array)");
  if ( $p_array != null ) {
    foreach ( $p_array as $key=> $element) {
      ${"p_$key"}=$element;
      echo_debug('user_menu.php',__LINE__,"p_$key =$element;");
    }
  }

  // Find the journal property
  /*
  $opt='<OPTION VALUE="="> =';
  $opt.='<OPTION VALUE="<="> <=';
  $opt.='<OPTION VALUE="<"> <';
  $opt.='<OPTION VALUE=">"> >';
  $opt.='<OPTION VALUE=">="> >=';
  */
  if ( ! isset ($p_date_start)) $p_date_start="";
  if ( ! isset ($p_date_end))   $p_date_end="";
  if ( ! isset ($p_mont_sel))$p_mont_sel="";
  if ( ! isset ($p_s_comment))$p_s_comment="";
  if ( ! isset ($p_s_montant)) $p_s_montant="";
  if ( ! isset ($p_st_montant)) $p_st_montant="";
  if ( ! isset ($p_s_internal)) $p_s_internal="";
  if ( ! isset ($p_poste)) $p_poste="";
  if ( ! isset ($p_qcode)) $p_qcode="";


  //  if ( $p_mont_sel != "" )  $opt.='<OPTION value="'.$p_mont_sel.'" SELECTED> '.$p_mont_sel;
  $r="";
 
  //  $r.= '<div style="border-style:outset;border-width:1pt;">';

  $r.=JS_SEARCH_POSTE;
  $r.=JS_SEARCH_CARD;
  $r.= '<h2><IMG SRC="image/search.png" width="48" border="0" > Recherche</h2>';
  $r.= '<FORM ACTION="recherche.php" METHOD="GET">';
  $r.=dossier::hidden();
  $r.="<table><tr><TD>";  
  $r.= '<TABLE>';
  $r.= "<TR>";
  $r.= '<TD COLSPAN="3">  Date comprise entre</TD> ';
  $r.= "</TR> <TR>";
  $date_start=new IDate();
  $date_start->name="date_start";
  $date_start->value=$p_date_start;
  $date_start->table=0;

  $date_end=new IDate();
  $date_end->name="date_end";
  $date_end->value=$p_date_end;
  $date_end->table=0;

  //  $r.= '<TD> <INPUT TYPE="TEXT" NAME="date_start" SIZE="10" VALUE="'.$p_date_start.'"></TD>';
  //$r.= '<TD>et <INPUT TYPE="TEXT" NAME="date_end" size="10"
  //Value="'.$p_date_end.'"></TD>';
  $r.='<td>'.$date_start->input();
  $r.="  et ";
  $r.=$date_end->input().'</td>';
  $r.= '</TD><TD>';
  $r.= "</TR> <TR>";
  $r.= "<TD> Montant compris entre ";
  //  $r.= ' <SELECT NAME="mont_sel">'.$opt.' </SELECT></TD><TD>';
  $r.= ' <INPUT TYPE="TEXT" style="border:groove 1px blue;" NAME="s_montant" SIZE="10" VALUE="'.$p_s_montant.'">';
  $r.= ' et <INPUT TYPE="TEXT" style="border:groove 1px blue;" NAME="st_montant" SIZE="10" VALUE="'.$p_st_montant.'"></TD>';

  $r.= '</TR><TR valigne="top">';
  $r.='<TD > Internal code ';
  $r.='<input type="text" style="border:groove 1px blue;"name="s_internal" value="'.$p_s_internal.'"></td>'; 
  $r.='</TR><TR><TD colspan="2"><i>vous pouvez spécifier uniquement <br>une partie (VEN, num&eacute;ro d\'op&eacute;ration...)</i></td>';


  $r.="</TD></TR></TABLE></td><TD><table>";

  $r.= "</TR>";
  $r.= "<TR>";
  $W=new IPoste();
  $W->label="Numéro de poste<br> <i>Vous pouvez utilisez le %</i>";
  $W->name="poste";
  $W->value=$p_poste;
  $r.="<TR>".$W->input();
  $r.= "</TR>";
  $r.= "<TR>";
 
 $A=new ICard();
$A->noadd='no';
 $A->name='qcode';
 $A->jrn=0;
 $A->value=$p_qcode;
 $A->extra="";
 $A->table=1;
 $A->label="Quick Code";
 $A->extra='all';
 $A->extra2='Recherche';

 $sp=new ISpan();
 $sp->table=0;
 $r.=$A->input().'</TD>'.$sp->input("qcode_label");
 $r.= "</TR>";
 echo_debug('user_menu.php',__LINE__,"<TD>".$A->input().'</TD><TD>'.$sp->input("p_qcode_label")."</TD>");
 
  $r.= '<TD colspan="3"> Le commentaire contient( ou numéro de pièce) </TD>';
  $r.= "</TR><TR>";
  $r.= '<TD COLSPAN="3"> <INPUT TYPE="TEXT" style="border:groove 1px blue;" NAME="s_comment" VALUE="'.$p_s_comment.'"></TD>';
  $r.= "</TR><TR>";
  $r.= '<TD COLSPAN="3"><INPUT TYPE="SUBMIT" VALUE="Recherche" NAME="viewsearch"></TD>';
  $r.= "</TR>";
  $r.= "</TABLE>";
  $r.="</TR></TABLE>";
  if ( isset($p_expert)) 
	$r.='<input type="hidden" name="expert" value="on">';
  $r.= "</FORM>";


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

		array('user_advanced.php?'.$str_dossier.'&p_action=preod','Ecritures definies',"",9),
		array('user_advanced.php?p_action=periode&'.$str_dossier,'Periode',"Gestion des periodes",2),
		array('user_advanced.php?p_action=central&'.$str_dossier,'Centralise',"Centralisation",3),

		array('compta.php?p_action=stock&'.$str_dossier,'Stock',"Gestion des stocks",5),
		array('user_advanced.php?p_action=defreport&'.$str_dossier,'Rapport',"Rapport",6),
		array('import.php?'.$str_dossier,'Import Banque',"Banque",7),
		array('user_advanced.php?p_action=ouv&'.$str_dossier,'Ecriture ouverture',"",8),
		array('user_advanced.php?p_action=verif&'.$str_dossier,'V&eacute;rification',"",10)
	),
					  'H',"msubtitle","mtitle",$default);
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

      echo '<TR><TD colspan="1" class="mtitle">
          <A class="mtitle" HREF="?p_action=fiche&action=add_modele&fiche=modele&'.$str_dossier.'">Creation</A></TD>
          <TD><A class="mtitle" HREF="?p_action=fiche&'.$str_dossier.'">Recherche</A></TD>
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
  $item=array (array("admin_repo.php?action=user_mgt","Utilisateurs",'Gestion des utilisateurs',0),
	       array("admin_repo.php?action=dossier_mgt","Dossiers",'Gestion des dossiers',1),
	       array("admin_repo.php?action=modele_mgt","Modèles",'Gestion des modèles',2),
	       array("admin_repo.php?action=restore","Restaure","Restaure une base de données",3),
	       array("login.php","Accueil"),
	       array("logout.php","Logout")
	       );

  $menu=ShowItem($item,'H',"mtitle","mtitle",$def,' width="100%" ');
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
  // The phpsessid is set manually to avoid problem when the server is
  // misconfigured and the cookies are not sent (or accepted)
  $s=dossier::get().'&PHPSESSID='.$_REQUEST['PHPSESSID'];
  $sub_menu=ShowItem(array(
			  
			   array('parametre.php?p_action=company&'.$s,'Sociétés','Parametre societe',1),
			   array('parametre.php?p_action=divers&'.$s,'Divers','Devise, moyen de paiement',2),
			   array('parametre.php?p_action=tva&'.$s,'Tva','Taux & poste pour la TVA',3),
			   array('parametre.php?p_action=poste&'.$s,'Poste Comptable','Poste comptable constant',4),
			  array('parametre.php?p_action=pcmn&'.$s,'Plan Comptable','Modification du plan comptable',11),
			   array('parametre.php?p_action=fiche&'.$s,'Fiche','Modifie les classe de base',5),
			   array('parametre.php?p_action=sec&'.$s,'Sécurité','securite',8),
			   array('parametre.php?p_action=preod&'.$s,'Ecritures définies','Ecritures définies ',12),
			   array('parametre.php?p_action=document&'.$s,'Document','Facture, lettre de rappel, proposition...',7),
			   array('parametre.php?p_action=jrn&'.$s,'Journaux','Creation et modification de journaux',10)
			
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
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="?p_action=jrn&sa=add&'.$str_dossier.'">Création </A></TD></TR>';
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
    echo '<TR><TD class="mtitle"><A class="mtitle"  HREF="?p_action=pcmn&p_start=1'.$str_dossier.'">1 Immobilisé </A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle"  HREF="?p_action=pcmn&p_start=2'.$str_dossier.'">2 Actif a un an au plus</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle"  HREF="?p_action=pcmn&p_start=3'.$str_dossier.'">3 Stock et commande</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle"  HREF="?p_action=pcmn&p_start=4'.$str_dossier.'">4 Compte tiers</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="?p_action=pcmn&p_start=5'.$str_dossier.'">5 Actif</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle"  HREF="?p_action=pcmn&p_start=6'.$str_dossier.'">6 Charges</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="?p_action=pcmn&p_start=7'.$str_dossier.'">7 Produits</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="?p_action=pcmn&p_start=8'.$str_dossier.'">8 Hors Comptabilit&eacute;</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="?p_action=pcmn&p_start=9'.$str_dossier.'">9 Hors Comptabilit&eacute;</A></TD></TR>';
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
  echo '<TR><TD class="mtitle"><A class="mtitle" HREF="import.php?action=import&'.$str_dossier.'">Importer CSV</A></TD></TR>';
  echo '<TR><TD class="mtitle"><A class="mtitle" HREF="import.php?action=verif&'.$str_dossier.'">Verif CSV</A></TD></TR>';
  echo '<TR><TD class="mtitle"><A class="mtitle" HREF="import.php?action=transfer&'.$str_dossier.'">Transfert CSV</A></TD></TR>';
    
  echo "</TABLE>";
}
/*!\brief show the top menu to access the different modules in top
 * \param $p_from from which module this function is called
 * \return string 
*/
function menu_tool($p_from) {

  if ( ! isset ($_REQUEST['gDossier']))
    return "" ;
  $r="";
$r.='<script language="javascript"> 
	function openRecherche(p_sessid,p_dossier,p_style) {
  if ( p_style == \'E\' ) { p_style="expert";}
  var w=window.open("recherche.php?gDossier="+p_dossier+"&PHPSESSID="+p_sessid+\'&\'+p_style,\'\',\'statusbar=no,scrollbars=yes,toolbar=no\');
  w.focus();
}
</script>';


  $r.= '<div class="u_tool">';
  $r.= '<div class="name">';
  $r.= "<H2 class=\"info\"> Dossier : ".h(dossier::name())."</h2> ";
  $r.= '</div>';
  $r.= '<div class="acces_direct">';
  if ( $p_from == 'compta') $view='E';
	else $view='S';
  $agent=$_SERVER['HTTP_USER_AGENT'];

  $amodule=array(
		 array('value'=>'access.php','label'=>'Accueil'),
		 array('value'=>'user_pref.php','label'=>'Preference'),
		 array('value'=>'parametre.php','label'=>'Paramètre'),
		 array('value'=>'user_login.php','label'=>'Autre Dossier'),
		 array('value'=>'logout.php','label'=>'Deconnexion'),
		 array('value'=>'new_line'),
		 array('value'=>'user_compta.php','label'=>'Compta Générale'),
		 array('value'=>'commercial.php','label'=>'Gestion'),
		 array('value'=>'comptanalytic.php','label'=>'Compt. Analytique'),
		 array('value'=>'budget.php','label'=>'Budget')
	       );

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
      $r.= '<td>'.
	'<a class="cell" href="'.$url.'" >'.$col['label'].'</a>'.
	'</td>';
    }

  }
   $r.='<td class="mtitle2">';
  $r.= '<A class="cell" HREF="javascript:openRecherche(\''.$_REQUEST['PHPSESSID'].'\','.$gDossier.')">'.
    'Recherche</a></td>';
  $r.='</tr>';

  $r.= '</table>';
  $r.= '</div>';
  
  $r.= '</div>';
 return $r;
}
