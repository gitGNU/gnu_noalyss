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
include_once ("ac_common.php");
html_page_start($g_UserProperty['use_theme']);
if ( ! isset ( $g_dossier ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}
include_once ("postgres.php");
/* Admin. Dossier */
CheckUser();

include_once ("top_menu_compta.php");
ShowMenuCompta($g_dossier,$g_UserProperty);
ShowMenuComptaRight($g_dossier,$g_UserProperty);
include_once("check_priv.php");

if ( $g_UserProperty['use_admin'] == 0 ) {
  include_once("check_priv.php");

  $r=CheckAction($g_dossier,$g_user,SECU);
  if ($r == 0 ){
    /* Cannot Access */
    NoAccess();
  exit -1;			

  }
}
echo '<SCRIPT LANGUAGE="javascript" SRC="win_search_poste.js"></SCRIPT>';
if ( isset( $_GET['p_jrn'] )) {
  session_register("g_jrn");
  $g_jrn=$p_jrn;
}


$l_Db=sprintf("dossier%d",$g_dossier);
$cn=DbConnect($l_Db);

If ( isset ($_POST["JRN_UPD"] )) {
  if (  !isset($_POST["p_jrn_name"])  ) {
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
    $p_jrn_name=$_POST['p_jrn_name'];
     if (strlen(trim($p_jrn_name))==0) return;
     $p_jrn_name=FormatString($p_jrn_name);
       $p_jrn_fiche_deb="";
       $p_jrn_fiche_cred="";

     if ( isset    ($_POST["FICHEDEB"])) {
       $p_jrn_fiche_deb=join(",",$_POST["FICHEDEB"]);
     }
      if ( isset    ($_POST["FICHECRED"])) {
       $p_jrn_fiche_cred=join(",",$_POST["FICHECRED"]);
      }
    $Sql=sprintf("update jrn_def set jrn_def_name='%s',jrn_def_class_deb='%s',jrn_def_class_cred='%s',
                 jrn_deb_max_line=%s,jrn_cred_max_line=%s,jrn_def_ech=%s,jrn_def_ech_lib=%s,jrn_def_fiche_deb='%s',
                  jrn_def_fiche_cred='%s'
                 where jrn_def_id=%s",
		 $p_jrn_name,$_POST['p_jrn_class_deb'],$_POST['p_jrn_class_cred'],
		 $l_deb_max_line,$l_cred_max_line,
		 $p_ech,$p_ech_lib,
		 $p_jrn_fiche_deb,$p_jrn_fiche_cred,
                 $g_jrn
		 );
    echo_debug($Sql);
    $Res=ExecSql($cn,$Sql);
  }
}
ShowMenuJrn($g_dossier);

$Res=ExecSql($cn,"select jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,".
	     "jrn_deb_max_line,jrn_cred_max_line,jrn_def_code".
                 ",jrn_def_type,jrn_def_ech, jrn_def_ech_lib,jrn_def_fiche_deb,jrn_def_fiche_cred".
                 " from jrn_def where".
                 " jrn_def_id=".$g_jrn);
$l_line=pg_fetch_array($Res,0);
if ( isset ($_GET["PHPSESSID"] ) ) {
  $sessid=$_GET["PHPSESSID"];
}
if ( isset ($_POST["PHPSESSID"] ) ) {
  $sessid=$_POST["PHPSESSID"];
}

$search='<INPUT TYPE="BUTTON" VALUE="Cherche" OnClick="SearchPoste(\''.$sessid."')\">";
echo '<DIV CLASS="ccontent">';
echo '<H2 class="info"> Fiches (profile comptable)</H2>';
echo '<FORM ACTION="jrn_detail.php" METHOD="POST">';
echo '<INPUT TYPE="HIDDEN" NAME="JRN_UPD">';
echo '<TABLE>';

echo '<TR>'; 
echo '<TD> Nom journal </TD>';
echo '<TD> <INPUT TYPE="text" NAME="p_jrn_name" VALUE="'.$l_line['jrn_def_name'].'"></TD>';
echo '</TR>';

echo '<TR>'; 
echo '<TD> Postes utilisables journal (débit) </TD>';
echo '<TD> <INPUT TYPE="text" NAME="p_jrn_class_deb" VALUE="'.$l_line['jrn_def_class_deb'].'">'.$search.'</TD>';
echo '</TR>';

echo '<TR>'; 
echo '<TD> Nombre de lignes par défaut (débit) </TD>';
echo '<TD> <INPUT TYPE="text" NAME="p_jrn_deb_max_line" VALUE="'.$l_line['jrn_deb_max_line'].'"></TD>';
echo '</TR>';

echo '<TR>'; 
echo '<TD> Postes utilisables journal (crédit) </TD>';
echo '<TD> <INPUT TYPE="text" NAME="p_jrn_class_cred" VALUE="'.$l_line['jrn_def_class_cred'].'">'.$search.'</TD>';
echo '</TR>';


echo '<TR>'; 
echo '<TD> Nombre de lignes par défaut (crédit) </TD>';
echo '<TD> <INPUT TYPE="text" NAME="p_jrn_cred_max_line" VALUE="'.$l_line['jrn_cred_max_line'].'"></TD>';
echo '</TR>';

echo '<TR>'; 
echo '<TD> Date d\'échéance </TD>';
if ( $l_line['jrn_def_ech'] == 't' ) {
  echo '<TD> <INPUT TYPE="radio" NAME="p_ech" VALUE="yes" checked> Oui';
  echo '<INPUT TYPE="radio" NAME="p_ech" VALUE="no">Non </TD>';
}
if ( $l_line['jrn_def_ech'] == 'f' ) {
  echo '<TD> <INPUT TYPE="radio" NAME="p_ech" VALUE="yes" >Oui';
  echo '<INPUT TYPE="radio" NAME="p_ech" VALUE="no" CHECKED>Non</TD>';
}
echo '</TR>';

echo '<TR>'; 
echo '<TD> Libellé echéance </TD>';
echo '<TD> <INPUT TYPE="text" NAME="p_ech_lib" VALUE="'.$l_line['jrn_def_ech_lib'].'"></TD>';
echo '</TR>';

echo '<TR>'; 
echo '<TD> Type de journal </TD>';
echo '<TD> ';
$Res=ExecSql($cn,"select jrn_type_id,jrn_desc from jrn_type where ".
	"trim(jrn_type_id)=trim('".$l_line['jrn_def_type']."')");
$Max=pg_NumRows($Res);

for ($i=0;$i<$Max;$i++) {
  $Line=pg_fetch_array($Res,$i);

  echo_debug("jrn type !".$Line['jrn_type_id']."!,!".$l_line['jrn_def_type']."!");
  printf (' %s',$Line['jrn_desc']);
}
echo '</TD>';
echo '</TR>';
echo "<TR><TD> Code </TD><TD>".$l_line['jrn_def_code']."</TD></TR>";


?>

<?

echo '</TABLE>';
// Get all the fiches
echo '<H2 class="info"> Fiches (profile user)</H2>';
$Res=ExecSql($cn,"select fd_id,fd_label from fichedef order by fd_label");
$num=pg_NumRows($Res);

$rdeb=split(',',$l_line['jrn_def_fiche_deb']);

$rcred=split(',',$l_line['jrn_def_fiche_cred']);
echo '<TABLE>';
echo '<TR>';
echo '<th> Fiches Crédit</TH>';
echo '<th> Fiches Dédit</TH>';
echo '</TR>';
// Show the fiche in deb section
for ($i=0;$i<$num;$i++) {
  $res=pg_fetch_array($Res,$i);
  $CHECKED=" unchecked";
  foreach ( $rdeb as $element) { 
    if ( $element == $res['fd_id'] ) {
    $CHECKED="CHECKED";
    break;
  }
  }
  echo '<TR>';
  printf ('<TD> <INPUT TYPE="CHECKBOX" VALUE="%s" NAME="FICHEDEB[]" %s>%s</TD>',
	  $res['fd_id'],$CHECKED,$res['fd_label']);
  $CHECKED=" unchecked";
  foreach ( $rcred as $element) { 
    if ( $element == $res['fd_id'] ) {
    $CHECKED="CHECKED";
    break;
  }
  }

  printf ('<TD> <INPUT TYPE="CHECKBOX" VALUE="%s" NAME="FICHECRED[]" %s>%s</TD>',
	  $res['fd_id'],$CHECKED,$res['fd_label']);
  echo '</TR>';
}


?>

<TABLE><TR><TD><INPUT TYPE="SUBMIT" VALUE="Sauve"></TD><TD><INPUT TYPE="RESET" VALUE="Reset"></TD></TR></TABLE>
<?
echo '</FORM>';
echo "</DIV>";
html_page_stop();
?>
