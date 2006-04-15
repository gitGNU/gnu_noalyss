<?
/*
 *   This file is part of PHPCOMPTA
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
 *   along with PHPCOMPTA; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
// Auteur Dany De Bontridder ddebontridder@yahoo.fr
include_once ("ac_common.php");
include_once ("postgres.php");
include_once ("check_priv.php");

/* $Revision$ */
/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();

html_page_start($User->theme,"onLoad='window.focus();'");

if ( ! isset ( $_SESSION['g_dossier'] ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}

$cn=DbConnect($_SESSION['g_dossier']);
// TODO add security here
// Get The priv on the selected folder
if ( $User->CheckAction($cn,FICHE_WRITE)== 0) {
    /* Cannot Access */
    echo '<h2 class="error"> Vous  ne pouvez pas ajouter de fiche</h2>';
    return;
}

include_once("fiche_inc.php");
foreach ($HTTP_GET_VARS as $key=>$element) {
  // The value are e_name e_type e_PHPSESSID
  ${"e_$key"}=$element;
  echo_debug('fiche_new.php',__LINE__,"e_$key =$element<br>");

}

function new_fiche($p_cn,$p_type) {
  $ch_col="</TD><TD>";
  $ch_li='</TR><TR>';
  $r='<FORM action="fiche_new.php" method="post">';
  $r.='<INPUT TYPE="HIDDEN" name="fiche" value="'.$p_type.'">';
  $l_sessid=(isset ($_POST["PHPSESSID"]))?$_POST["PHPSESSID"]:$_GET["PHPSESSID"];

  $r.=JS_SHOW_TVA;
  $r.=JS_SEARCH_POSTE;
  $r.="<TABLE>";
  echo '<H2 class="info">New </H2>';
  $p_f_id="";
  echo_debug('fiche_new.php',__LINE__,"Array is null");
  // Find all the attribute of the existing cards
  // --> Get_attr_def 
    $sql="select frd_id,ad_id,ad_text from  fiche_def join jnt_fic_attr using (fd_id)
           join attr_def using (ad_id) where fd_id=".$p_type." order by ad_id";

    $Res=ExecSql($p_cn,$sql);
    $Max=pg_NumRows($Res);
    // Put the card modele id (fiche_def.fd_id)
    $r.='<INPUT TYPE="HIDDEN" name="fd_id" value="'.$p_type.'">';
    for ($i=0;$i < $Max;$i++) {
      $l_line=pg_fetch_array($Res,$i);

      // The number of the attribute
      $Hid=sprintf('<INPUT TYPE="HIDDEN" name="ad_id%d" value="%s">',
		   $i,$l_line['ad_id']);

      $but_search_poste="";
      // Javascript for searching the account
      if ( $l_line ['ad_id'] == ATTR_DEF_ACCOUNT ) {
	$but_search_poste='<INPUT TYPE="BUTTON" VALUE="Cherche" OnClick="SearchPoste(\''.$l_sessid.'\',\'av_text'.$i.'\')">';
      } 
      // Javascript for showing the tva
      if ( $l_line ['ad_id'] == ATTR_DEF_TVA ) {
	$but_search_poste='<INPUT TYPE="BUTTON" VALUE="Montre" OnClick="ShowTva(\''.$l_sessid.'\',\'av_text'.$i.'\')">';
      }
      // content of the attribute
      $r.= sprintf('<TR><TD> %s </TD><TD><INPUT TYPE="TEXT" NAME="av_text%d">%s %s</TD></TR>',
	      $l_line['ad_text'], $i,$Hid,$but_search_poste);
   }  
    $r.="</TABLE>";
    $r.='<INPUT TYPE="SUBMIT" name="add_fiche" value="Mis à jour">';
    $r.= '<INPUT TYPE="HIDDEN" name="inc" value="'.$Max.'">';
    $r.='</FORM>';
    return $r;
}
if ( isset($_POST['add_fiche'])) {
  AddFiche($cn,$_POST["fiche"],$HTTP_POST_VARS);
?>
<SCRIPT>

  window.close();
</SCRIPT>
<?
    return;
}
// Prob : ajout de fiche mais si plusieur cat possible ???
 // Get the field from database
  // if e_type contains a list of value
  if ( $e_type != 'cred' and $e_type != 'deb')     {
    //    $list['fiche']=$e_type;
    $sql="select fd_id from fiche_def where frd_id in ($e_type)";
    $Res=ExecSql($cn,$sql);
    // fetch it
    $Max=pg_NumRows($Res);
    if ( $Max==0) {
      echo_warning("No rows");
    exit();
    }
    $n=pg_NumRows($Res);
    for ($i=0;$i <$n;$i++) {
      $f=pg_fetch_array($Res,$i);
      $e[$i]=$f['fd_id'];
    }
    $list['fiche']=join(',',$e);
  } else { // We have to find it from the database
    if ( $e_type == 'deb' ) {
      $get='jrn_def_fiche_deb';
      $sql="select $get as fiche from jrn_def where jrn_def_id=".$_GET['p_jrn'];
    }
    if ( $e_type == 'cred' ) {
      $get='jrn_def_fiche_cred';
    $sql="select $get as fiche from jrn_def where jrn_def_id=".$_GET['p_jrn'];
    }
    
    

    $Res=ExecSql($cn,$sql);
    
  // fetch it
    $Max=pg_NumRows($Res);
    if ( $Max==0) {
      echo_warning("No rows");
    exit();
    }
    // Normally Max must be == 1
    $list=pg_fetch_array($Res,0);
    if ( $list['fiche']=="") {
      echo_warning("Journal mal paramètré");
      return;
    }
  }


// Compter le nombre de cat. possible
$a=explode(",",$list['fiche']);

// sinon si cat = 1 ou si var. cat vaut qq chose
// Montrer le contenu de fiche
if ( sizeof($a) == 1 ) {
  if ( strlen(trim($a[0])) != 0)
 // Display a blank  card from the selected category
	$f=new_fiche($cn,$a[0]);
  echo $f;
}
 
if ( isset($_POST['cat'])) {
	$f=new_fiche($cn,$_POST['cat']);
	echo $f;
}

// si cat > 1 proposer choix cat
//      recharger avec var. cat
if ( sizeof($a)>1 and !isset ($_POST['cat']))
  {
    echo "Choix catégories fiche";
    echo '<FORM METHOD="POST" ACTION="'.$_SERVER['REQUEST_URI'].'">';
    foreach ($a as $element) {
      printf('<INPUT TYPE="RADIO" NAME="cat" value="%s">%s<br>',
	     $element,GetFicheDefName($cn,$element));
    
  }
    echo '<INPUT TYPE="SUBMIT" value="Choisir">';
    echo "<FORM>";
  }
?>
