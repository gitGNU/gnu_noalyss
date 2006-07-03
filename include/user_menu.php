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
 *   Foundation, Inshowc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
/*!\file
 * \brief Nearly all the menu are here, some of them returns a HTML string, others echo
 * directly the result.
 */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/* $Revision$ */

/*!
 * \brief   Show all the available folder  for the users
 *          at the login page
 * \param $p_user user 
 * \param $p_admin 1 if admin
 *	 
 * \return table in HTML
 * 
 */  
function u_ShowDossier($p_user,$p_admin)
{
  $p_array=GetAvailableFolder($p_user,$p_admin);   
  if ( $p_array == 0 ) return " * Aucun dossier *";
  $result="";
  $result.="<table border=\"0\">";
  $result.='<TR>';
    if ( $p_admin == 1 ) {
      $result.="<TD  class=\"mtitle\" ><A class=\"mtitle\" HREF=\"admin_repo.php\"> Administration  </A></TD>";
    }
    $result.='<TD  class="mtitle" ><A class="mtitle" HREF="logout.php" > Sortir</a></TD>';
    $result.="</TR>";
  $result.="</table>";
  $result.="<TABLE>";
  for ($i=0;$i<sizeof($p_array);$i++) {
    $id=$p_array[$i]['dos_id'];
    $name= $p_array[$i]['dos_name'];
    $desc=$p_array[$i]['dos_description'];
    if ( $i%2 == 0) 
      $tr="odd";
    else $tr="even";

    $result.="<TR class=\"$tr\"><TD class=\"$tr\">";
    $result.=$id."  <B>$name</B>";
    $result.="</TD><TD class=\"$tr\">";
    $result.=$desc;
    $result.="</TD><TD class=\"mtitle\">";
    $result.="<A class=\"mtitle\" HREF=\"user_compta.php?dos=$id\">Comptabilité</A>";
    $result.="</TD>";
    $result.="<TD class=\"mtitle\">";
    $result.="<A class=\"mtitle\" HREF=\"commercial.php?dos=$id\">Gestion</A>";
    $result.="</TD>";
    $result.="<TD class=\"mtitle\">";
    $result.="<A class=\"mtitle\" HREF=\"parametre.php?dos=$id\">Paramètres</A>";
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
function GetAvailableFolder($p_user,$p_admin)
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
         and priv_priv != 'NO' ";

  } else {
    $sql="select distinct dos_id,dos_name,dos_description from ac_users 
                  natural join jnt_use_dos 
                  natural join  ac_dossier 
      where  use_active=1 ";
  }
  include_once("postgres.php");
  $cn=DbConnect();
  $Res=ExecSql($cn,$sql);
  $max=pg_numRows($Res);
  if ( $max == 0 ) return 0;

  for ($i=0;$i<$max;$i++) {
    $array[]=pg_fetch_array($Res,$i);
  }
  return $array;
}
/*!   
 * \brief show the top menu for the user profile
 *        and highight the selected one
 * \param  p_dossier $_SESSION['g_dossier']
 * \param  p_high what to hightlight, by default it is autodetected
 *         but sometimes it must be given. Default value=""
 * \todo clean param p_dossier
 *
 * \return none
 *
 */
function ShowMenuCompta($p_dossier,$p_high="")
{
  include_once("postgres.php");

  // find our current menu
  $default=basename($_SERVER['SCRIPT_NAME']);
  switch ($default) {
  case "user_jrn.php":
    $default.="?show";
    break;
  case "recherche.php":
    $default.="?p_dossier=$p_dossier";
    break;
  case "fiche.php":
    $default.="?p_dossier=$p_dossier";
    break;
  }
  if ( $p_high !== "" ) $default=$p_high;

  echo_debug('user_menu.php',__LINE__,'defaut is '.$default);

  $p_array=array(array("user_jrn.php?show","Journaux","Les journaux permettent d'encoder toutes les opérations"),
		 array("recherche.php?p_dossier=$p_dossier","Recherche","Pour retrouver une opération"),
		 array("fiche.php?p_dossier=$p_dossier","Fiche","Ajouter, modifier ou effacer des fiches"),
		 array("user_impress.php","Impression","Impression"),
		 array("user_advanced.php","Avancé","Opérations délicates"),

  		 array('user_pref.php','Preference',"Préférence de l'utilisateur"),
		 array('parametre.php?dos='.$_SESSION['g_dossier'],"Paramètre"),
		 array('commercial.php?dos='.$_SESSION['g_dossier'],"Gestion"),
		 array('login.php','Accueil',"Accueil"),
		 array('logout.php','logout',"Sortie")
		 );

  $result=ShowItem($p_array,'H',"mtitle","mtitle",$default,' width="100%"');

  $r="<H2 class=\"info\">Comptabilit&eacute;  ".$_SESSION['g_name']." </H2>";
  $r.=$result;
  return $r;




}
/*! 
 * \brief Open the first legder automaticaly
 * \param $p_dossier ($_SESSION['g_dossier'])
 * \param $p_type type of the ledger (VEN,ACH,FIN,ODS)
 * 
 * 
 *
 * \return the first jrn_def_id
 */

function GetFirstJrnIdForJrnType($p_dossier,$p_type)
{
  include_once("postgres.php");
  
  //get db connection
  $Cn=DbConnect($p_dossier);  
  //execute query
  $Ret=ExecSql($Cn,"select min(jrn_def_id) from jrn_def ".
	       "where jrn_def_type='".$p_type."';");
  $l_line=pg_fetch_array($Ret,0);
  return $l_line[0];
  //return 0;
}
/*!   ShowMenuJrnUser($p_dossier,$p_type,$p_jrn)
 * \brief  Show the Menu from the jrn encode
 *           page
 * 
 * \param $p_dossier number
 * \param $p_type type of journal (VEN,ACH,BQE,ODS)
 * \param $p_jrn journal
 * \param $p_extra html code to add at the return (before the \</table\>)
 * \return string with table in HTML
 * \note we use the SCRIPT_NAME variable to build the href value but we need to add
 *       a parameter when the REQUEST_url is commercial.php
 *       
 *
 */ 
function ShowMenuJrnUser($p_dossier,$p_type,$p_jrn,$p_extra="")
{
  include_once ("debug.php");
  include_once("constant.php");
  include_once("class_user.php");
  echo_debug('user_menu.php',__LINE__,"U_SHOWMENUJRNUSER PTYPE=$p_type");

  echo '<TABLE><TR>';
  include_once("postgres.php");
  
  
  $Cn=DbConnect($p_dossier);
  
  $User=new cl_user($Cn);
  $User->Check();
  if ( $User->Admin() ==0) {
    $Ret=ExecSql($Cn,"select jrn_def_id,jrn_def_type,jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_type_id,jrn_desc,uj_priv,
                               jrn_deb_max_line,jrn_cred_max_line
                             from jrn_def join jrn_type on jrn_def_type=jrn_type_id
                             join user_sec_jrn on uj_jrn_id=jrn_def_id 
                             where
                             uj_login='".$User->id."'
                             and uj_priv !='X'
                             and jrn_def_type='$p_type' order by jrn_Def_id
                             ");
    } else {
      $Ret=ExecSql($Cn,"select jrn_def_id,jrn_def_type,jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_deb_max_line,jrn_cred_max_line,
                            jrn_type_id,jrn_desc,'W' as uj_priv
                             from jrn_def join jrn_type on jrn_def_type=jrn_type_id where
                              jrn_def_type='$p_type' order by jrn_Def_id");

    } 
    $Max=pg_NumRows($Ret);
    include_once("check_priv.php");

    for ($i=0;$i<$Max;$i++) {
      $l_line=pg_fetch_array($Ret,$i);
      // Admin have always rights
      if ( $User->Admin() == 0 ){
	$right=CheckJrn($p_dossier,$_SESSION['g_user'],$l_line['jrn_def_id']);
      }else {
	$right=3;
      }

      if ( $right > 0 ) {
	// Minimum Lecture 
	echo_debug('user_menu.php',__LINE__,"p_jrn = $p_jrn ");
	if ( $l_line['jrn_def_id'] != $p_jrn ) {
	  $href=$_SERVER["SCRIPT_NAME"];
	  $add="";
	  // if the SCRIPT_NAME == commercial.php, we need to add the parameter
	  // p_action=facture
	  if ( $href=="/commercial.php" ) 
	    {
	      $add='&p_action='.$_REQUEST['p_action'];
	    }
	  echo '<TD class="cell">';
	  printf ('<A class="mtitle" HREF="%s?jrn_type=%s&p_jrn=%s%s">%s</A></TD>',
		  $href,
		  $l_line['jrn_def_type'],
		  $l_line['jrn_def_id'],
		  $add,
		  $l_line['jrn_def_name']
		  );
	} else
	  {
	    echo '<TD class="selectedcell">'. $l_line['jrn_def_name'].'</TD>';
	  }
      }// if right
    }// for
    if ( $p_extra !="" ) echo $p_extra;
    echo '</TR>';
    echo "</TABLE>";
    //echo '</div>';

}
/*! 
 * \brief  Show the menu of the jrn depending of its type, check with the security
 *        
 * \param p_cn database connection
 * \param p_jrn_type type of the ledger
 * \param p_jrn jrn id
 *
 *
 * \return string containing the menu
 *    
 */
function ShowMenuJrn($p_cn,$p_jrn_type,$p_jrn) 
{

  $Res=ExecSql($p_cn,"select ja_name,ja_url,ja_action,ja_desc from jrn_action  where ja_jrn_type='$p_jrn_type'
                      order by ja_id");
  $num=pg_NumRows($Res);
  if ($num==0)    return "";
  // Retrieve in the database the menu
  $ret="<TABLE>";
  $access_key_list = array();
  for ($i=0;$i<$num;$i++) {
    $action=pg_fetch_array($Res,$i);
    $access_key=get_quick_key($action['ja_name'],$access_key_list);
    $lib=str_replace($access_key,'<u>'.$access_key.'</u>',$action['ja_name']);

    $ret.=sprintf('<TR><TD class="cell"><A class="mtitle" accesskey="%s" title="%s" '.
		  'HREF="%s?%s&p_jrn=%s&jrn_type=%s">%s</A></td></tR>',
		  $access_key, 
		  $action['ja_desc'], 
		  $action['ja_url'],
		  $action['ja_action'], 
		  $p_jrn, 
		  $_REQUEST['jrn_type'],
		  $lib);

  }
  $ret.='</TABLE>';
  return $ret;

}

/*!   get_quick_key
 * \brief  Show the menu of the jrn depending of its type
 *  return a not yet used access key. The returned key is added to $access_key_list       
 * 
 * \param $title
 * \param &$access_key_list
 *
 *
 * \return string containing the menu
 *     - 
 */
function get_quick_key($title,&$access_key_list)
{
	$quick = $title[0];
	if(array_key_exists($quick, $access_key_list))
	{
		echo_debug(" key exists: " . $quick);
		return get_quick_key(substr($title, 1), $access_key_list);
	} else
	{
		echo_debug(" new key: " . $quick);
	}
	$access_key_list[$quick] = $quick;
	
	return $quick;
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
  //  $JrnProperty=GetJrnProperty($p_cn,$p_jrn);

  $opt='<OPTION VALUE="="> =';
  $opt.='<OPTION VALUE="<="> <=';
  $opt.='<OPTION VALUE="<"> <';
  $opt.='<OPTION VALUE=">"> >';
  $opt.='<OPTION VALUE=">="> >=';
  if ( ! isset ($p_date_start)) $p_date_start="";
  if ( ! isset ($p_date_end))   $p_date_end="";
  if ( ! isset ($p_mont_sel))$p_mont_sel="";
  if ( ! isset ($p_s_comment))$p_s_comment="";
  if ( ! isset ($p_s_montant)) $p_s_montant="";
  if ( ! isset ($p_s_internal)) $p_s_internal="";
  if ( ! isset ($p_poste)) $p_poste="";
  if ( ! isset ($p_qcode)) $p_qcode="";


  if ( $p_mont_sel != "" )  $opt.='<OPTION value="'.$p_mont_sel.'" SELECTED> '.$p_mont_sel;
  $r="";
 
  //  $r.= '<div style="border-style:outset;border-width:1pt;">';

  $r.=JS_SEARCH_POSTE;
  $r.=JS_SEARCH_CARD;
  $r.= "<B>Recherche</B>";
  $r.= '<FORM ACTION="recherche.php" METHOD="GET">';
  $r.="<table><tr><TD>";  
  $r.= '<TABLE>';
  $r.= "<TR>";
  $r.= '<TD COLSPAN="3">  Date compris entre</TD> ';
  $r.= "</TR> <TR>";
  $r.= '<TD> <INPUT TYPE="TEXT" NAME="date_start" SIZE="10" VALUE="'.$p_date_start.'"></TD>';
  $r.= '<TD> <INPUT TYPE="TEXT" NAME="date_end" size="10" Value="'.$p_date_end.'"></TD>';
  $r.= '</TD><TD>';
  $r.= "</TR> <TR>";
  $r.= "<TD> Montant ";
  $r.= ' <SELECT NAME="mont_sel">'.$opt.' </SELECT></TD><TD>';
  $r.= ' <INPUT TYPE="TEXT" NAME="s_montant" SIZE="10" VALUE="'.$p_s_montant.'"></TD>';
  $r.= "</TR><TR>";
  $r.="<TD> Internal code</td>";
  $r.='<TD><input type="text" name="s_internal" value="'.$p_s_internal.'"></td>';

  $r.="</TD></TR></TABLE></td><TD><table>";

  $r.= "</TR>";
  $r.= "<TR>";
  $W=new widget("js_search_poste");
  $W->label="Numéro de poste";
  $W->name="poste";
  $W->value=$p_poste;
  $r.="<TR>".$W->IOValue();
  $r.= "</TR>";
  $r.= "<TR>";
 
 $A=new widget('js_search_only');
 $A->name='qcode';
 $A->value=$p_qcode;
 $A->extra="";
 $A->table=1;
 // $r.=$sp->IOValue("p_qcode_label")."</TD></TR>";

 //  $A=new widget("TEXT");
  $A->label="Quick Code";
  $A->extra='all';
  //$A->name="qcode";
  //  $A->value=$p_qcode;
  $sp= new widget("span");
  $sp->table=0;
  $r.=$A->IOValue().'</TD>'.$sp->IOValue("qcode_label");
  $r.= "</TR>";
  echo_debug('user_menu.php',__LINE__,"<TD>".$A->IOValue().'</TD><TD>'.$sp->IOValue("p_qcode_label")."</TD>");

  $r.= '<TD colspan="3"> Le commentaire contient </TD>';
  $r.= "</TR><TR>";
  $r.= '<TD COLSPAN="3"> <INPUT TYPE="TEXT" NAME="s_comment" VALUE="'.$p_s_comment.'"></TD>';
  $r.= "</TR><TR>";
  $r.= '<TD COLSPAN="3"><INPUT TYPE="SUBMIT" VALUE="Recherche" NAME="viewsearch"></TD>';
  $r.= "</TR>";
  $r.= "</TABLE>";
  $r.="</TR></TABLE>";
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
// Show the left menu
$left_menu=ShowItem(array(
			  //('rapprt.php','Rapprochement'),
			  array('jrn_update.php','Journaux'),
			  array('user_advanced.php?p_action=periode','Periode'),
			  array('central.php','Centralise'),
			  array('pcmn_update.php?p_start=1','Plan Comptable'),
			  array('stock.php','Stock'),
			  array('form.php','Rapport'),
			  array('import.php','Import Banque'),
			  array('ecrit_ouv.php','Ecriture ouverture')
			
			  ),
		    'H',"cell","mtitle",$default);
 $r='<div class="u_subtmenu">'.$left_menu."</div>";
return $r;
}
/*!  
 **************************************************
 * \brief  Return a string containing the menu
 *           main menu when you click on Journaux
 * \param $p_menu the current menu (selected)
 *
 * \return HTML Code
 */
function ShowJrn($p_menu="")
{

 $p_array=array(
 		array("user_jrn.php?jrn_type=NONE" ,"Grand Livre"),
 		array("user_jrn.php?jrn_type=VEN" ,"Entrée"),
                array("user_jrn.php?jrn_type=ACH","Dépense"),
                array("user_jrn.php?jrn_type=FIN","Financier"),
                array("user_jrn.php?jrn_type=OD","Op. Diverses")
                 );
 $result=ShowItem($p_array,'H',"cell","mtitle",$p_menu);
 return $result;
}

/*!   
 **************************************************
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
     $cn=DbConnect($p_dossier);
     echo '<div class="lmenu">';
     echo '<TABLE>';
     /*! \todo  Only for developper A test must be added
      */
      echo '<TR><TD colspan="3" class="mtitle">
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
}
/*!   MenuAdmin */
/* \brief show the menu for user/database management 
/*
/* \return HTML code with the menu
*/

function MenuAdmin()
{
  $item=array (array("admin_repo.php?action=user_mgt","Utilisateurs"),
	       array("admin_repo.php?action=dossier_mgt","Dossiers"),
	       array("admin_repo.php?action=modele_mgt","Modèles"),
	       array("login.php","Accueil"),
	       array("logout.php","Logout")
	       );

  $menu=ShowItem($item,'H');
  return $menu;
}
/*!  
 * \brief : Display Document's menu
 *   
 * \param none
 * \return string
 *
 *
 */ 
function ShowMenuDocument()
{
  $base="parametre.php?p_action=document&sa=";
  $sub_menu=ShowItem(
		     array(array($base."add_document","Ajout"))
		     ,'V',"cell","mtitle");
  return $sub_menu;

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
    $sub_menu=ShowItem(array(
			  
			  array('parametre.php?p_action=company','Sociétés'),
			  array('parametre.php?p_action=devise','Devises'),
			  array('parametre.php?p_action=tva','Tva'),
			  array('parametre.php?p_action=poste','Poste Comptable'),
			  array('parametre.php?p_action=fiche','Fiche'),
			  array('user_sec.php','Sécurité'),
			  array('parametre.php?p_action=document','Document'),
			  array('commercial.php?dos='.$_SESSION['g_dossier'],"Gestion"),
			  array('user_compta.php?dos='.$_SESSION['g_dossier'],"Comptabilité"),

			  array('login.php','Accueil',"Accueil"),
			  array('logout.php','logout',"Sortie")
			  ),
		    'H',"mtitle","mtitle",$p_action,' width="100%"');
    return $sub_menu;

}
/*! 
 * \brief  Show the menu in the jrn page
 * 
 * \param  $p_dossier  
 *	
 * 
 *
 * \return nothing
 *	
 *
 */ 

function MenuJrn($p_dossier)
{
    echo '<TABLE>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="jrn_add.php">Création </A></TD></TR>';
    include_once("postgres.php");
    $Cn=DbConnect($p_dossier);
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
    echo '<TABLE>';
    echo '<TR><TD class="mtitle"><A class="mtitle"  HREF="pcmn_update.php?p_start=1">1 Immobilisé </A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle"  HREF="pcmn_update.php?p_start=2">2 Actif a un an au plus</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle"  HREF="pcmn_update.php?p_start=3">3 Stock et commande</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle"  HREF="pcmn_update.php?p_start=4">4 Compte tiers</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="pcmn_update.php?p_start=5">5 Actif</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle"  HREF="pcmn_update.php?p_start=6">6 Charges</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="pcmn_update.php?p_start=7">7 Produits</A></TD></TR>';
    echo "</TABLE>";
}
/*!  
 * \brief Show the left menu for the report (add a report, view it)
 * 
 * \param $p_dossier dossier id
 *
 *
 *
 * \return nothing
 *
 *
 */ 
function ShowMenuComptaForm($p_dossier) {
     $cn=DbConnect($p_dossier);
    echo '<div class="lmenu">';
    echo '<TABLE>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="form.php?action=add">Ajout</A></TD></TR>';
    $Ret=ExecSql($cn,"select fr_id, fr_label 
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

/*! 
 * \brief Show the menu for importing, verify and transfert Bank CSV
 *
 * \return nothing
 */
function ShowMenuImport(){
    echo '<TABLE>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="import.php?action=import">Importer CSV</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="import.php?action=verif">Verif CSV</A></TD></TR>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="import.php?action=transfer">Transfert CSV</A></TD></TR>';
    
    echo "</TABLE>";
}

?>
