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

require_once ('class_widget.php');


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
    $result.=$id."  <B>$name</B>";
    $result.="</A>";
    $result.="</TD>";
    $desc=($desc=="")?"<i>Aucune description</i>":$desc;
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
         and priv_priv != 'NO' and dos_name ilike '%$p_filter%'";

  } else {
    $sql="select distinct dos_id,dos_name,dos_description from ac_dossier 
      where  dos_name ilike '%$p_filter%' ";
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
 * \param  p_high what to hightlight, by default it is autodetected
 *         but sometimes it must be given. Default value=""
 * \todo clean param p_dossier
 *
 * \return none
 *
 */
function ShowMenuCompta($p_high="")
{
  include_once("postgres.php");

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

  echo_debug('user_menu.php',__LINE__,'defaut is '.$default);
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
	  case 'ach':
	    $default=2;
	    break;
	  case 'fin':
	    $default=3;
	    break;
	  case 'ods':
	    $default=8;
	    break;

	  }
	}
  $str_dossier=dossier::get();
  $p_array=array(
		 array("compta.php?p_action=gl&".$str_dossier ,"Grand Livre","Grand livre : toutes les opérations",0),
		 array("compta.php?p_action=ven&".$str_dossier ,"Vente","Journal de vente / produit",1),
		 array("compta.php?p_action=ach&".$str_dossier,"Dépense","Journaux de dépense, d'achat",2 ),
		 array("compta.php?p_action=fin&".$str_dossier,"Financier","Journaux financiers","journaux financiers: les banques, la caisse",3),
		 array("compta.php?p_action=ods&".$str_dossier,"Op. Diverses","Journaux opération diverses : déclaration TVA, impot, prêt...",8),
		 array('compta.php?p_action=quick_writing&'.$str_dossier,'Ecriture directe','Ecriture directe dans les journaux',4),
		 
		 array("compta.php?p_action=impress&".$str_dossier,"Impression","Impression",5),
				 array("compta.php?p_action=fiche&".$str_dossier,"Fiche","Ajouter, modifier ou effacer des fiches",6),
		 array("user_advanced.php?".$str_dossier,"Avancé","Opérations délicates",7),
		 );

  $result=ShowItem($p_array,'H',"mtitle","mtitle",$default,' width="100%"');
  $str_dossier=dossier::get();
  $r="";
  $r.=menu_tool("compta");
  $r.='<div style="float:left;background-color:#879ED4;">';
  $r.=$result;
  $r.='</div>';
  //  $r.='</div>';
  
  return $r;




}
/*! 
 * \brief Open the first legder automaticaly
 * \param $p_dossier dossier::id
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
 * \note we use the PHP_SELF variable to build the href value but we need to add
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
  $str_dossier=dossier::get();
  
  $Cn=DbConnect($p_dossier);
  
  $User=new User($Cn);
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
    // If you can't access any ledger, so you don't have access
    if ( $Max == 0 )
      NoAccess();

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
		  $href=basename($_SERVER["PHP_SELF"]);
		  $add="";
		  // if the PHP_SELF == commercial.php, we need to add the parameter
		  // p_action=facture
	  if ( $href=="commercial.php" ) 
	    {
	      $add='&p_action='.$_REQUEST['p_action'];
	    }
	  echo '<TD class="mtitle">';
	  printf ('<A class="mtitle" HREF="%s?'.$str_dossier.'&jrn_type=%s&p_jrn=%s%s">%s</A></TD>',
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

  $Res=ExecSql($p_cn,"select ja_name,ja_url,ja_action,ja_desc from jrn_action  ".
	       " where ja_jrn_type='$p_jrn_type'
                      order by ja_id");
  $num=pg_NumRows($Res);
  if ($num==0)    return "";


  // Retrieve in the database the menu

  $access_key_list = array();
  $array_item=array();
  for ($i=0;$i<$num;$i++) {
    $action=pg_fetch_array($Res,$i);
    $access_key=get_quick_key($action['ja_name'],$access_key_list);
    $lib=str_replace($access_key,'<u>'.$access_key.'</u>',$action['ja_name']);
    $str_url=sprintf('?%s&p_jrn=%s&jrn_type=%s&'.dossier::get(),
			$action['ja_action'], 
			$p_jrn, 
			$_REQUEST['jrn_type']);
		       
    $str_lib=$action['ja_name'];
    $array_item[]=array($str_url,$str_lib);
  }
  $dir=($p_jrn_type=='FIN')?'H':'V';
  $ret=ShowItem($array_item,$dir);
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
  $date_start=new widget('js_date');
  $date_start->name="date_start";
  $date_start->value=$p_date_start;
  $date_start->table=0;

  $date_end=new widget('js_date');
  $date_end->name="date_end";
  $date_end->value=$p_date_end;
  $date_end->table=0;

  //  $r.= '<TD> <INPUT TYPE="TEXT" NAME="date_start" SIZE="10" VALUE="'.$p_date_start.'"></TD>';
  //$r.= '<TD>et <INPUT TYPE="TEXT" NAME="date_end" size="10"
  //Value="'.$p_date_end.'"></TD>';
  $r.='<td>'.$date_start->IOValue();
  $r.="  et ";
  $r.=$date_end->IOValue().'</td>';
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
  $W=new widget("js_search_poste");
  $W->label="Numéro de poste<br> <i>Vous pouvez utilisez le %</i>";
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
		array('ecrit_ouv.php?'.$str_dossier,'Ecriture ouverture',"",8),
		array('user_advanced.php?p_action=verif&'.$str_dossier,'V&eacute;rification',"",10)
	),
					  'H',"mtitle","mtitle",$default);
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
                array("user_jrn.php?jrn_type=ODS","Op. Diverses")
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
	 $str_dossier=dossier::get();
     echo '<div class="lmenu">';
     echo '<TABLE>';
     /*! \todo  Only for developper A test must be added
      */
      echo '<TR><TD colspan="1" class="mtitle">
          <A class="mtitle" HREF="?p_action=fiche&action=add_modele&fiche=modele&'.$str_dossier.'">Creation</A></TD>
          <TD><A class="mtitle" HREF="?p_action=fiche&'.$str_dossier.'">Recherche</A></TD>
           </TR>';
     $Res=ExecSql($cn,"select fd_id,fd_label from fiche_def order by fd_label");
     $Max=pg_NumRows($Res);
     for ( $i=0; $i < $Max;$i++) {
       $l_line=pg_fetch_array($Res,$i);
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
	       array("admin_repo.php?action=restore","Restore","Restaure une base de données",3),
	       array("login.php","Accueil"),
	       array("logout.php","Logout")
	       );

  $menu=ShowItem($item,'H',"mtitle","mtitle",$def);
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
  // The phpsessid is set manually to avoid problem when the server is
  // misconfigured and the cookies are not sent (or accepted)
  $s=dossier::get().'&PHPSESSID='.$_REQUEST['PHPSESSID'];
  $sub_menu=ShowItem(array(
			  
			   array('parametre.php?p_action=company&'.$s,'Sociétés','Parametre societe',1),
			   array('parametre.php?p_action=devise&'.$s,'Devises','Devise',2),
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

function MenuJrn()
{
	$str_dossier=dossier::get();
    echo '<TABLE>';
    echo '<TR><TD class="mtitle"><A class="mtitle" HREF="?p_action=jrn&sa=add&'.$str_dossier.'">Création </A></TD></TR>';
    include_once("postgres.php");
    $Cn=DbConnect(dossier::id());
    $Ret=ExecSql($Cn,"select jrn_def_id,jrn_def_name,
                             jrn_def_class_deb,jrn_def_class_cred,jrn_type_id,jrn_desc 
                             from jrn_def join jrn_type on jrn_def_type=jrn_type_id");
    $Max=pg_NumRows($Ret);

    for ($i=0;$i<$Max;$i++) {
      $l_line=pg_fetch_array($Ret,$i);
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
  $r.= "<H2 class=\"info\">Commercial ".dossier::name()."</h2> ";
  $r.= '</div>';
  $r.= '<div class="acces_direct">';
  if ( $p_from == 'compta') $view='E';
	else $view='S';
  $agent=$_SERVER['HTTP_USER_AGENT'];

  $amodule=array(
		 array('value'=>'pref','label'=>'Preference'),
		 array('value'=>'compta','label'=>'Comptabilite'),
		 array('value'=>'gestion','label'=>'Gestion'),
		 array('value'=>'analytic','label'=>'Compt. Analytique'),
		 array('value'=>'budget','label'=>'Budget'),
		 array('value'=>'param','label'=>'Parametre'),
		 array('value'=>'access','label'=>'Accueil'),
		 array('value'=>'home','label'=>'Autre Dossier'),
		 array('value'=>'logout','label'=>'Deconnection')
	       );
  if ( $_SESSION['g_topmenu'] == 'SELECT' ) {
    $lab="";
    foreach ($amodule as $l) 
      if ( $l['value'] == $p_from) {$lab=$l['label'];break;}
    
    $gDossier=dossier::id();
    $r.= '<form method="GET" action="control.php" >';
    $w=new widget('select');
    $w->name='m';
    $w->value=$amodule;
    $r.=    '<table><tr><td class="mtitle">';

    $search="openRecherche('".$_REQUEST['PHPSESSID']."','".$gDossier."','".$view."');";
    $r.='<input type="BUTTON" onClick="'.$search.'" value="Recherche">';
    $r.='</td>';
    $r.= '<td style="border:2px solid blue;border-style:groove;">'.$w->IOValue();
    $r.= dossier::hidden();
    $r.=widget::submit('','Acces Direct').'</td>';
    $r.= '</table>';
    $r.= '</form>';
    if ( $lab !="")
      echo '<span style="background-color:red;padding:3px;color:white">'.$lab.'</span>';

    $r.= '</div>';
  }
  if ( $_SESSION['g_topmenu'] == 'TEXT' ) {
    $gDossier=dossier::id();
    $r.=    '<table><tr><td class="mtitle2">';
    $r.= '<A class="cell" HREF="javascript:openRecherche(\''.$_REQUEST['PHPSESSID'].'\','.$gDossier.')">'.
      'Recherche</a></td>';
    foreach($amodule as $col ) {
      $url="control.php?".dossier::get()."&m=".$col['value'];
      if ( $p_from==$col['value']) {
      $r.= '<td style="background-color:red">'.
	'<a class="mtitle" href="'.$url.'" >'.$col['label'].'</a>'.
	'</td>';

      } else {
      $r.= '<td>'.
	'<a class="cell" href="'.$url.'" >'.$col['label'].'</a>'.
	'</td>';
      }

    }
    $r.='</tr>';
    $r.= '</table>';
    $r.= '</div>';
  }
  $r.= '</div>';
 return $r;
}
