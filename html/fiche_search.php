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
include_once ("poste.php");
html_page_start($g_UserProperty['use_theme']);

if ( ! isset ( $g_dossier ) ) {
  echo "You must choose a Dossier ";
  phpinfo();
  exit -2;
}
include_once ("postgres.php");
include_once("jrn.php");
/* Admin. Dossier */
CheckUser();
include_once ("postgres.php");
/* Admin. Dossier */
CheckUser();

include_once ("check_priv.php");
// Get The priv on the selected folder
if ( $g_UserProperty['use_admin'] == 0 ) {
  $r=CheckAction($g_dossier,$g_user,FICHE_READ);
  if ($r == 0 ){
    /* Cannot Access */
    echo '<h2 class="error"> Vous n\' avez pas accès</h2>';
    return;
  }
}

function get_list_fiche($p_cn,$get,$p_jrn)
{
  $sql="select $get as fiche from jrn_def where jrn_def_id=$p_jrn";
  $Res=ExecSql($p_cn,$sql);

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
    exit();
  }
  $list_fiche=$list['fiche'];
  return $list_fiche;
}
?>
<script>
function SetData (name_ctl,value,value_2,value_3,value_4,value_5,value_6) {
  self.opener.SetData(name_ctl,value,value_2,value_3,value_4,value_5,value_6);
  window.close();
}
</script>
<?
$l_Db=sprintf("dossier%d",$g_dossier);
$cn=DbConnect($l_Db);
$r="";

foreach ($HTTP_GET_VARS as $key=>$element) {
  // The value are e_name e_type e_PHPSESSID
  ${"e_$key"}=$element;
  echo("e_$key =$element<br>");

}
$e_fic_search=(isset ($_POST['fic_search']))?$_POST['fic_search']:"";

$r.="<FORM METHOD=\"POST\" ACTION=\"".$_SERVER['REQUEST_URI']."\">";
$r.="Recherche : ".'<INPUT TYPE="TEXT" NAME="fic_search" VALUE="'.$e_fic_search.'">';
$r.='<INPUT TYPE="submit" name="search" value="Go">';
$r.="</FORM>";
$r.="<div>";
echo $r;
$r="";

// Show result of the search 
if ( isset ( $_POST['search']) )  {

  // Get the field from database
  if ( $e_type == 'deb' ) {
    $get='jrn_def_fiche_deb';
    $list_fiche=get_list_fiche($cn,$get,$g_jrn);
    $sql="select * from vw_fiche_attr where fd_id in ( $list_fiche )";
  }
  if ( $e_type == 'cred' ) {
    $get='jrn_def_fiche_cred';
    $list_fiche=get_list_fiche($cn,$get,$g_jrn);
    $sql="select * from vw_fiche_attr where fd_id in ( $list_fiche )";
  }


  // if e_type contains a list of value
  if ( $e_type != 'cred' and $e_type != 'deb')     {
    $list_fiche=$e_type;
    $sql="select * from vw_fiche_attr where frd_id in ( $list_fiche )";
  }
// e_fic_search contains the pattern

  if (strlen(trim($e_fic_search) ) == 0 ) {
    $Res=ExecSql($cn,$sql); 
  } else {
    $Res=ExecSql($cn,"$sql and upper(vw_name) like upper('%$e_fic_search%')"); 
  }

  // Test whether rows are returned
 if ( ($Max = pg_NumRows($Res) ) == 0 ) {
   echo_warning("Pas de fiche trouvée");
   return;
 } 
 // Show the cards
 for ( $i=0; $i < $Max; $i++)  {
  $row=pg_fetch_array($Res,$i);
  if ( $i %2  == 0 ) 
    $class="even";
  else
    $class="odd";

  $r.="<span class=\"$class\">";
  $r.=sprintf ('<input type="button" onClick="'."SetData('%s','%s','%s','%s','%s','%s','%s')".'" value="select">',
	      $e_name,
	      $row['f_id'] ,
	      $row['vw_name'], 
	      $row['vw_sell'], 
	      $row['vw_buy'], 
	      $row['tva_id'], 
	      $row['tva_label']  
	       );
  $r.=  $row['vw_name'].$row['vw_addr'].$row['vw_cp'];
  $r.="</span>";
 }
}
$r.="</div>";
echo $r;

?>

<?

html_page_stop();
?>
