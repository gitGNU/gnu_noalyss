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

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/* $Revision$ */

/* function u_ShowDossier
 * Purpose :  Show all the available folder 
 *            for the users
 * parm : 
 *	- $p_user user login
 *      - $p_admin 1 if admin
 * gen :
 *	- none
 * return:
 *	- nothing
 *
 */ 
function u_ShowDossier($p_user,$p_admin)
{
  $p_array=GetAvailableFolder($p_user,$p_admin);   
  if ( $p_array == 0 ) return " * Aucun dossier *";
  $result="";
  $result.="<table>";
  $result.='<TR>';
    if ( $p_admin == 1 ) {
      $result.="<TD><A class=\"mtitle\" HREF=\"admin_repo.php\"> Administration : </A></TD>";
    }
    $result.='<TD><A HREF="logout.php" CLASS="mtitle">: Sortir</a></TD>';
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
    $result.="<A class=\"mtitle\" HREF=\"user_compta.php?dos=$id\">Comptabilit�</A>";
    $result.="</TD>";
    $result.="</TR>";
  }
  $result.="</TABLE>";
  return $result;
}
/* function GetAvailableFolder
 * Purpose :  Get all the available folders
 *            for the users
 * parm : 
 *	- $p_user user login
 *      - $p_admin 1 if admin
 * gen :
 *	- none
 * return:
 *	- array containing ac_dossier.dos_id
 *                         ac_dossier.dos_name
 *                         ac_dossier.dos_description
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
/* function u_ShowMenu
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
  function u_ShowMenu($p_admin,$p_check = 1,$p_item="")
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
  
  echo ' <TD class="mtitle"> <A class="mtitle" HREF="user_pref.php">Pr�f�rence</A></TD>';

  echo "<TD class=\"mtitle\">"; 
  html_button_logout();
  echo "</TD>";
  echo "</TR></TABLE>";
  echo "</div>";
}
/* function u_ShowMenuCompta 
 * purpose : show the top menu for 
 *           the user profile
 * parm : p_dossier
 *
 * return: none
 *
 * gen : none
 */
function u_ShowMenuCompta($p_dossier)
{
  include_once("postgres.php");
  $l_name=GetDossierName($p_dossier);


  $p_array=array(array("user_jrn.php?show","Journaux"),
		 array("recherche.php?p_dossier=$p_dossier","Recherche"),
		 array("fiche.php?p_dossier=$p_dossier","Fiche"),
		 array("user_impress.php","Impression"),
		 array("user_advanced.php","Avanc�"),
		 array("dossier_prefs.php","Param�tre"),
  		 array('user_pref.php','Preference'),
		 array('login.php','Accueil'),
		 array('logout.php','logout')
		 );

  $result=ShowItem($p_array,'H');
    //echo "<DIV class=\"tmenu\">";
  echo "<H2 class=\"info\"> $l_name </H2>";
  echo $result;
  //echo "</DIV>";




}
/* function ShowMenuComptaRight
 * Purpose : Display menu on the Right
 *           (preference, logout and admin)
 * parm : 
 *	- $p_dossier the current dossier
 *      - $p_admin   $g_UserProperty['use_admin'] 1 if admin
 *      - $p_more code
 * gen :
 *	- none
 * return:
 *	- string containing the html menu
 *
 */ 
function u_ShowMenuComptaRight($p_dossier=0,$p_admin,$p_more="")
{
  include_once("ac_common.php");
  $i=0;
  if ( $p_admin != 0 && $p_dossier != 0) {
    $p_array[$i++]=array("admin_repo.php","Admin");
  }
  $p_array[$i++]=array('login.php','Accueil');
  $p_array[$i++]=array('user_pref.php','Preference');
  $p_array[$i]=array('logout.php','logout');
  $menu=ShowItem($p_array);
  echo '<DIV class="rmenu">';
  echo $menu;
  echo '</DIV>';

}
/* function u_ShowMenuJrnUser($p_dossier,$p_user)
 * Purpose : Show the Menu from the jrn encode
 *           page
 * 
 * parm : 
 *	- $p_dossier
 *      - $p_user
 *      - $p_type type of journal (VEN,ACH,BQE,ODS)
 *      - $p_jrn journal
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 

function u_ShowMenuJrnUser($p_dossier,$p_user,$p_type,$p_jrn)
{
  include_once ("debug.php");
  include_once("constant.php");
  include_once("class_user.php");
  echo_debug(__FILE__,__LINE__,"U_SHOWMENUJRNUSER PTYPE=$p_type");
  //    echo '<div class="searchmenu">';
    echo '<TABLE><TR>';
    include_once("postgres.php");


    $l_jrn=sprintf("dossier%d",$p_dossier);
    $Cn=DbConnect($l_jrn);

	$User=new cl_user($Cn);
	if ( $User->Admin() ==0) {
      $Ret=ExecSql($Cn,"select jrn_def_id,jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_type_id,jrn_desc,uj_priv,
                               jrn_deb_max_line,jrn_cred_max_line
                             from jrn_def join jrn_type on jrn_def_type=jrn_type_id
                             join user_sec_jrn on uj_jrn_id=jrn_def_id 
                             where
                             uj_login='$p_user'
                             and uj_priv !='X'
                             and jrn_def_type='$p_type'
                             ");
    } else {
      $Ret=ExecSql($Cn,"select jrn_def_id,jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_deb_max_line,jrn_cred_max_line,
                            jrn_type_id,jrn_desc,'W' as uj_priv
                             from jrn_def join jrn_type on jrn_def_type=jrn_type_id where
                              jrn_def_type='$p_type'");

    } 
    $Max=pg_NumRows($Ret);
    include_once("check_priv.php");

    for ($i=0;$i<$Max;$i++) {
      $l_line=pg_fetch_array($Ret,$i);
      // Admin have always rights
      if ( $User->Admin() == 0 ){
	$right=CheckJrn($p_dossier,$p_user,$l_line['jrn_def_id']);
      }else {
	$right=3;
      }


      if ( $right > 0 ) {
	// Minimum Lecture 
	echo_debug(__FILE__,__LINE__,"p_jrn = $p_jrn ");
	if ( $l_line['jrn_def_id'] != $p_jrn ) {
	  echo '<TD class="cell">';
	  printf ('<A class="mtitle" HREF="user_jrn.php?p_jrn=%s">%s</A></TD>',
		  $l_line['jrn_def_id'],
		  $l_line['jrn_def_name']
		  );
	} else
	  {
	    echo '<TD class="selectedcell">'. $l_line['jrn_def_name'].'</TD>';
	  }
      }// if right
    }// for
    echo '</TR>';
    echo "</TABLE>";
    //echo '</div>';

}
/* function u_ShowMenuJrn
 * Purpose : Show the menu of the jrn depending of its type
 *        
 * parm : 
 *      - p_cn database connection
 *	- p_dossier
 *      - p_UserProperty
 *      - p_jrn_type
 *      - p_jrn
 * gen :
 *	- none
 * return:
 *     - string containing the menu
 */
function u_ShowMenuJrn($p_cn,$p_jrn_type) 
{

  $Res=ExecSql($p_cn,"select ja_name,ja_url,ja_action from jrn_action  where ja_jrn_type='$p_jrn_type'
                      order by ja_id");
  $num=pg_NumRows($Res);
  if ($num==0)    return "";
  // Retrieve in the database the menu
  $ret="<TABLE>";
  for ($i=0;$i<$num;$i++) {
    $action=pg_fetch_array($Res,$i);
    $ret.=sprintf('<TR><TD class="cell"><A class="mtitle" HREF="%s?%s">%s</A></td></TR>',
		  $action['ja_url'],$action['ja_action'],$action['ja_name']);
  }
  $ret.='</TABLE>';
  return $ret;

}
/* function u_ShowMenuRecherche ($p_cn,$p_jrn,$p_sessid,$p_array=null)
 * Purpose :
 * 
 * parm : 
 *	- $p_cn database connection
 *      - $p_jrn jrn id
 *      - $p_array=null previous search
 * gen :
 *	- none
 * return:
 *	- none
 *
 */ 

function u_ShowMenuRecherche($p_cn,$p_jrn,$p_sessid,$p_array=null)
{
  echo_debug(__FILE__,__LINE__,"u_ShowMenuRecherche($p_cn,$p_array)");
  if ( $p_array != null ) {
    foreach ( $p_array as $key=> $element) {
      ${"p_$key"}=$element;
      echo_debug(__FILE__,__LINE__,"p_$key =$element;");
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


  if ( $p_mont_sel != "" )  $opt.='<OPTION value="'.$p_mont_sel.'" SELECTED> '.$p_mont_sel;
  $r="";
 
  $r.= '<div style="border-style:outset;border-width:1pt;">';
  $r.= "<B>Recherche</B>";
  $r.= '<FORM ACTION="recherche.php?action=search&PHPSESSID='.$p_sessid.'&nofirst" METHOD="POST">';  
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
  $r.= "</TR><TR>";
  $r.= '<TD colspan="3"> Le commentaire contient </TD>';
  $r.= "</TR><TR>";
  $r.= '<TD COLSPAN="3"> <INPUT TYPE="TEXT" NAME="s_comment" VALUE="'.$p_s_comment.'"></TD>';
  $r.= "</TR><TR>";
  $r.= '<TD COLSPAN="3"><INPUT TYPE="SUBMIT" VALUE="Recherche" NAME="viewsearch"></TD>';
  $r.= "</TR>";
  $r.= "</TABLE>";
  $r.= "</FORM>";
  $r.= '</div>';

  return $r;

}
/* function function ShowMenuAdvanced() {
 **************************************************
 * Purpose :  build the menu of user_advanced.php
 *        
 * parm : 
 *	- none
 * gen :
 *	- none
 * return: the menu
 */
function ShowMenuAdvanced() {
// Show the left menu
$left_menu=ShowItem(array(
			  //('rapprt.php','Rapprochement'),
			  array('jrn_update.php','Journaux'),
			  array('central.php','Centralise'),
		          array('pcmn_update.php?p_start=1','Plan Comptable'),
			  array('stock.php','stock')
			  ),
		    'H',"cell","mtitle");
return $left_menu;
}
/* function ShowJrn
 **************************************************
 * Purpose : Return a string containing the menu
 *           main menu when you click on Journaux
 * parm : 
 *	- $p_menu the current menu (selected)
 * gen :
 *	- none
 * return: a string
 */
function ShowJrn($p_menu="")
{

 $p_array=array(
 		array("user_jrn.php?JRN_TYPE=NONE" ,"Grand Livre"),
 		array("user_jrn.php?JRN_TYPE=VEN" ,"Entr�e"),
                array("user_jrn.php?JRN_TYPE=ACH","D�pense"),
                array("user_jrn.php?JRN_TYPE=FIN","Financier"),
                array("user_jrn.php?JRN_TYPE=OD","Op. Diverses")
                 );
 $result=ShowItem($p_array,'H',"cell","mtitle",$p_menu);
 return $result;
}

/* function ShowMenuFiche
 **************************************************
 * Purpose : 
 *        
 * parm : 
 *	-
 * gen :
 *	-
 * return:
 */
function ShowMenuFiche($p_dossier)
{
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

?>
