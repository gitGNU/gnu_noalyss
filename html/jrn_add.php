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
/* $Revision$ */
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/* $Revision$ */
include_once ("ac_common.php");
include_once("jrn.php");
html_page_start($_SESSION['use_theme']);
if ( ! isset ( $_SESSION['g_dossier'] ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}
include_once ("postgres.php");
/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();
include_once("check_priv.php");
include_once ("user_menu.php");
ShowMenuCompta($_SESSION['g_dossier']);


$cn=DbConnect($_SESSION['g_dossier']);
if ($User->CheckAction($cn,GJRN) == 0 ){
  /* Cannot Access */
  NoAccess();
  exit -1;			
  
 }

echo JS_SEARCH_POSTE;

If ( isset ($_POST["JRN_ADD"]) ) {
  if (  !isset($_POST["p_jrn_name"]) || ! isset($_POST["p_jrn_type"] )) {
    echo '<H2 CLASS="error"> Un paramètre manque</H2>';
  }
  else {
    if ( $_POST['p_ech'] == 'no' ) {
      $p_ech='false';
      $p_ech_lib='null';
    } else {
      $p_ech='true';
      $p_ech_lib="'".$_POST['p_ech_lib']."'";
    }
      if (strlen(trim($_POST['p_jrn_deb_max_line'])) == 0 || 
	  (string) (int)$_POST['p_jrn_deb_max_line'] != (string)$_POST['p_jrn_deb_max_line'] )
      $l_deb_max_line=1;
    else
      $l_deb_max_line=$_POST['p_jrn_deb_max_line'];

     if (  strlen(trim($_POST['p_jrn_cred_max_line'])) == 0 ||
	   (string) (int)$_POST['p_jrn_cred_max_line'] != (string)$_POST['p_jrn_cred_max_line'] )
      $l_cred_max_line=1;
    else
      $l_cred_max_line=$_POST['p_jrn_cred_max_line'];
     $p_jrn_name=$_POST["p_jrn_name"];
echo_debug(__FILE__,__LINE__,"nom journal $p_jrn_name");
     $p_jrn_class_deb=FormatString($_POST["p_jrn_class_deb"]);
     if (strlen(trim($p_jrn_name))==0) return;
     $p_jrn_name=FormatString($p_jrn_name);
     $p_jrn_class_cred=FormatString($_POST["p_jrn_class_cred"]);
     // compute the jrn_def.jrn_def_code
     $p_code=sprintf("%s-%02d",trim($_POST['p_jrn_type']),NextJrn($cn,$_POST['p_jrn_type']));
       $p_jrn_fiche_deb="";
       $p_jrn_fiche_cred="";

     if ( isset    ($_POST["FICHEDEB"])) {
       $p_jrn_fiche_deb=join(",",$_POST["FICHEDEB"]);
     }
      if ( isset    ($_POST["FICHECRED"])) {
       $p_jrn_fiche_cred=join(",",$_POST["FICHECRED"]);
      }

    $Sql=sprintf("insert into jrn_def(jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_deb_max_line,jrn_cred_max_line,
                  jrn_def_type,jrn_def_fiche_deb,jrn_def_fiche_cred,jrn_def_code) 
                  values ('%s','%s','%s',%s,%s,'%s','%s','%s','%s')",
		 $p_jrn_name,$p_jrn_class_deb,$p_jrn_class_cred,
                 $l_deb_max_line,$l_cred_max_line,$_POST['p_jrn_type'],
		 $p_jrn_fiche_deb,$p_jrn_fiche_cred,
		 $p_code
		 );
    echo_debug($Sql);
    $Res=ExecSql($cn,$Sql);
  }
}
echo ShowMenuAdvanced();
MenuJrn($_SESSION['g_dossier']);
if ( isset ($_GET["PHPSESSID"]) ) {
  $sessid=$_GET["PHPSESSID"];
}else {
  $sessid=$_POST["PHPSESSID"];
}
$search='<INPUT TYPE="BUTTON" VALUE="Cherche" OnClick="SearchPoste(\''.$sessid."','not')\">";

echo '<DIV CLASS="ccontent">';
echo '<FORM ACTION="jrn_add.php" METHOD="POST">';
echo '<INPUT TYPE="HIDDEN" NAME="JRN_ADD">';
echo '<TABLE>';

echo '<TR>'; 
echo '<TD> Nom journal </TD>';
echo '<TD> <INPUT TYPE="text" NAME="p_jrn_name"></TD>';
echo '</TR>';

echo '<TR>'; 
echo '<TD> Postes utilisables journal (débit) </TD>';
echo '<TD> <INPUT TYPE="text" NAME="p_jrn_class_deb">'.$search.'</TD>';
echo '</TR>';

echo '<TR>'; 
echo '<TD> Nombre de lignes par défaut (débit) </TD>';
echo '<TD> <INPUT TYPE="text" NAME="p_jrn_deb_max_line" ></TD>';
echo '</TR>';

echo '<TR>'; 
echo '<TD> Postes utilisables journal (crédit) </TD>';
echo '<TD> <INPUT TYPE="text" NAME="p_jrn_class_cred">'.$search.'</TD>';
echo '</TR>';


echo '<TR>'; 
echo '<TD> Nombre de lignes par défaut (crédit) </TD>';
echo '<TD> <INPUT TYPE="text" NAME="p_jrn_cred_max_line"></TD>';
echo '</TR>';

echo '<TR>'; 
echo '<TD> Date d\'échéance </TD>';
echo '<TD> <INPUT TYPE="radio" NAME="p_ech" VALUE="yes" >Oui';
echo '<INPUT TYPE="radio" NAME="p_ech" VALUE="no" CHECKED>Non</TD>';

echo '</TR>';

echo '<TR>'; 
echo '<TD> Libellé echéance </TD>';
echo '<TD> <INPUT TYPE="text" NAME="p_ech_lib" ></TD>';
echo '</TR>';


echo '<TR>'; 
echo '<TD> Type de journal </TD>';
echo '<TD> ';
$Res=ExecSql($cn,"select jrn_type_id,jrn_desc from jrn_type");
$Max=pg_NumRows($Res);
echo '<SELECT NAME="p_jrn_type">';
for ($i=0;$i<$Max;$i++) {
  $Line=pg_fetch_array($Res,$i);
  printf ('<OPTION VALUE="%s">%s',$Line['jrn_type_id'],$Line['jrn_desc']);
}
echo '</SELECT>';
echo '</TD>';
echo '</TR>';
?>
<?
// Get all the fiches
echo '<tr> <td colspan="2"><H2 class="info"> Fiches </H2></TD></TR>';
$Res=ExecSql($cn,"select fd_id,fd_label from fiche_def order by fd_label");
$num=pg_NumRows($Res);


echo '<TR>';
echo '<th> Fiches Crédit</TH>';
echo '<th> Fiches Dédit</TH>';
echo '</TR>';
// Show the fiche in deb section
for ($i=0;$i<$num;$i++) {
  $res=pg_fetch_array($Res,$i);
  echo '<TR>';
  printf ('<TD> <INPUT TYPE="CHECKBOX" VALUE="%s" NAME="FICHEDEB[]">%s</TD>',
	  $res['fd_id'],$res['fd_label']);
  printf ('<TD> <INPUT TYPE="CHECKBOX" VALUE="%s" NAME="FICHECRED[]">%s</TD>',
	  $res['fd_id'],$res['fd_label']);
  echo '</TR>';
}
?>
<TR><TD><INPUT TYPE="SUBMIT" VALUE="Sauve"></TD><TD><INPUT TYPE="RESET" VALUE="Reset"></TD></TR>
<?
echo '</TABLE>';
echo '</FORM>';
echo "</DIV>";
html_page_stop();
?>
