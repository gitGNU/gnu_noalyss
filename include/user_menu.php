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

  $result="<TABLE>";
  for ($i=0;$i<sizeof($p_array);$i++) {
    $id=$p_array[$i]['dos_id'];
    $name= $p_array[$i]['dos_name'];
    $desc=$p_array[$i]['dos_description'];
    if ( $i%2 == 0) 
      $tr="odd";
    else $tr="even";

    $result.="<TR class=\"$tr\"><TD class=\"$tr\">";
    $result.="<B>$name</B>";
    $result.="</TD><TD class=\"$tr\">";
    $result.=$desc;
    $result.="</TD><TD class=\"mtitle\">";
    $result.="<A class=\"mtitle\" HREF=\"user_compta.php?dos=$id\">Encoder</A>";
    $result.="</TD></TR>";
  }
  return $result;
}
/* function u_ShowDossier
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
    $filter="and use_login='$p_user' ";

  }
  $sql="select distinct dos_id,dos_name,dos_description from ac_users 
                  natural join jnt_use_dos 
                  natural join  ac_dossier 
      where  use_active=1 ".$filter;
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
  
  echo ' <TD class="mtitle"> <A class="mtitle" HREF="user_pref.php">Préférence</A></TD>';

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
  echo "<P> <H2 class=\"info2\"> $l_name </H2></P>";

  $p_array=array(array("user_jrn.php?JRN_TYPE=VEN" ,"Vente"),
		 array("user_jrn.php?JRN_TYPE=ACH","Achat"),
		 array("user_jrn.php?JRN_TYPE=FIN","Banque"),
		 array("user_jrn.php?JRN_TYPE=OD","Op. Diverses"),
		 array("fiche.php?p_dossier=$p_dossier","Fiche"),
		 array("user_advanced.php","Avancé"),
		 array("dossier_prefs.php","Paramètre")
		   );

  $result=ShowItem($p_array,'H');
    echo "<DIV class=\"tmenu\">";

  echo $result;
  echo "</DIV>";




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
  echo_debug("U_SHOWMENUJRNUSER PTYPE=$p_type");
    echo '<div class="searchmenu">';
    echo '<TABLE><TR>';
    include_once("postgres.php");
    $l_jrn=sprintf("dossier%d",$p_dossier);
    $Cn=DbConnect($l_jrn);

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
      if ( CheckAdmin() == 0 ){
	$right=CheckJrn($p_dossier,$p_user,$l_line['jrn_def_id']);
      }else {
	$right=3;
      }


      if ( $right > 0 ) {
	// Minimum Lecture 
	echo_debug("p_jrn = $p_jrn ");
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
    echo "</TABLE>";
    echo '</div>';

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
    $ret.=sprintf('<TR><TD class="cell"><A class="mtitle" HREF="%s?%s">%s</A></td></tR>',
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
  echo_debug("u_ShowMenuRecherche($p_cn,$p_jrn,$p_array)");
  if ( $p_array != null ) {
    foreach ( $p_array as $key=> $element) {
      ${"p_$key"}=$element;
      echo_debug("p_$key =$element;");
    }
  }

  // Find the journal property
  $JrnProperty=GetJrnProperty($p_cn,$p_jrn);

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
  $r="";
 
  $r.= '<div style="border-style:outset;border-width:1pt;">';
  $r.= "<B>Recherche</B>";
  $r.= '<FORM ACTION="user_jrn.php?p_jrn='.$p_jrn.'&action=search&PHPSESSID='.$p_sessid.'&nofirst" METHOD="POST">';  
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

?>
