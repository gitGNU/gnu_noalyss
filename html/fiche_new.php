<?php
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
require_once("class_fiche.php");
require_once("class_fiche_def.php");

/*! \file
 * \brief Create a new card in a popup window
 * \note parameter (GET) e_type (deb, cred, filter) if filter is
 * specified, you can give a p_jrn parameter, otherwise e_type
 * contains a list of value
 * 
 */

/* $Revision$ */
/* Admin. Dossier */
$gDossier=dossier::id();
$cn=DbConnect($gDossier);

include_once ("class_user.php");
$User=new User($cn);
$User->Check();
$User->check_dossier($gDossier);
html_min_page_start($User->theme,"onLoad='window.focus();'");
require_once('class_dossier.php');

// TODO add security here
// Get The priv on the selected folder
if ( $User->check_action(FICADD)== 0) {
    /* Cannot Access */
    echo '<h2 class="error"> Vous  ne pouvez pas ajouter de fiche</h2>';
    return;
}

include_once("fiche_inc.php");


foreach ($_GET as $key=>$element) {
  // The value are e_name e_type e_PHPSESSID
  ${"e_$key"}=$element;
  echo_debug('fiche_new.php',__LINE__,"e_$key =$element<br>");

}
echo JS_TVA;
function new_fiche($p_cn,$p_type) {
  $fiche=new fiche($p_cn);
  $r='<FORM action="fiche_new.php" method="post">';
  $r.=dossier::hidden();
  $r.='<INPUT TYPE="HIDDEN" name="fiche" value="'.$p_type.'">';
  $l_sessid=(isset ($_POST["PHPSESSID"]))?$_POST["PHPSESSID"]:$_GET["PHPSESSID"];

  $fiche_def=new fiche_def($p_cn,$p_type);
  $fiche_def->Get();

  $r.= '<H2 class="info"> '.$fiche_def->label.'<br>Nouveau </H2>';
  $r.= $fiche->blank($p_type);
    $r.='<INPUT TYPE="SUBMIT" name="add_fiche" value="Mis à jour">';

    $r.='</FORM>';
    return $r;
}

if ( isset($_POST['add_fiche'])) {
  $fiche=new fiche($cn);
  $fiche->Save($_POST['fiche']);

?>
<SCRIPT>

    window.close();
</SCRIPT>
<?php
    return;
}
// Prob : ajout de fiche mais si plusieur cat possible ???
 // Get the field from database
  // if e_type contains a list of value
  if ( $e_type != 'cred' and $e_type != 'deb' && $e_type!='filter')     {
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
      $sql="select $get as fiche from jrn_def where jrn_def_id=$1";
    }
    if ( $e_type == 'cred' ) {
      $get='jrn_def_fiche_cred';
      $sql="select $get as fiche from jrn_def where jrn_def_id=$1";
    }
    if ($e_type=='filter') {
    
    $get_cred='jrn_def_fiche_cred';

    $get_deb='jrn_def_fiche_deb';

    $sql="select $get_cred||','||$get_deb as fiche from jrn_def where jrn_def_id=$1";
    
  }

    

    $Res=ExecSqlParam($cn,$sql,array($_GET['p_jrn']));
    
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
	echo dossier::hidden();
    foreach ($a as $element) {
      $fiche_def=new fiche_def($cn,$element);
      $fiche_def->Get();
      $name=$fiche_def->label;
      printf('<INPUT TYPE="RADIO" NAME="cat" value="%s">%s<br>',
	     $element,$name);
    
  }
    echo '<INPUT TYPE="SUBMIT" value="Choisir">';
    echo "</FORM>";
  }
?>
