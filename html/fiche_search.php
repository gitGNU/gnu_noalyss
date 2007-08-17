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
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/* $Revision$ */
/*! \file
 * \brief Search a card in a popup window
 */

include_once ("ac_common.php");
include_once ("poste.php");
include_once ("postgres.php");
include_once("jrn.php");
/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();

//determine focus:
if ( isset ( $_GET['search']) )
{
  html_page_start($User->theme,"onLoad=\"window.focus();SetFocus('select0',0)\"");
} else
{
  html_page_start($User->theme,"onLoad=\"window.focus();SetFocus('fic_search',0)\"");
}



require_once('class_dossier.php');
$gDossier=dossier::id();

include_once ("check_priv.php");
$cn=DbConnect($gDossier);
// Get The priv on the selected folder
if ( $User->CheckAction($cn,FICHE_READ) == 0 ){
    /* Cannot Access */
    echo '<h2 class="error"> Vous n\' avez pas acc�s</h2>';
    return;
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
    echo_warning("Journal mal param�tr�");
    exit();
  }
  $list_fiche=$list['fiche'];
  return $list_fiche;
}
?>
<script language="javascript">
function SetData (name_ctl,value,value_2,value_3,value_4,value_5,value_6) {
  self.opener.SetData(name_ctl,value,value_2,value_3,value_4,value_5,value_6);
  window.close();
}
</script>
<?php
$cn=DbConnect($gDossier);
$r="";

foreach ($_GET as $key=>$element) {
  // The value are e_name e_type e_PHPSESSID
  ${"e_$key"}=$element;
  echo_debug('fiche_search.php',__LINE__,"e_$key =$element<br>");

}
$e_fic_search=(isset ($_REQUEST['fic_search']))?$_REQUEST['fic_search']:"";

$r.="<FORM METHOD=\"GET\" >";
$r.="Recherche : ".'<INPUT TYPE="TEXT" NAME="fic_search" VALUE="'.$e_fic_search.'">';
$r.='<INPUT TYPE="submit" name="search" value="Go">';

$r.="<div>";
echo $r;
$r="";
foreach ($_GET as $k=>$h)
{
  if ( $k != "fic_search" && $k != "first" )
    echo '<input type="HIDDEN" name="'.
      $k.'" value="'.$h.'">';
}
// Show result of the search 
if ( 
    (isset($_GET['first']) && strlen(trim($_GET['fic_search'])) != 0) 
    || ! isset($_GET['first'] )
    )
  {

  // Get the field from database
  if ( $e_type == 'deb' ) {
    $get='jrn_def_fiche_deb';
    $list_fiche=get_list_fiche($cn,$get,$_GET['p_jrn']);
    $sql="select * from vw_fiche_attr where fd_id in ( $list_fiche )";
  }
  elseif ( $e_type == 'cred' ) {
    $get='jrn_def_fiche_cred';
    $list_fiche=get_list_fiche($cn,$get,$_GET['p_jrn']);
    $sql="select * from vw_fiche_attr where fd_id in ( $list_fiche )";
  }
  /*!\brief if $e_type (from widget::extra) equal all, all the card are shown 
   * without restriction
   */
  elseif ( $e_type == 'all' ) {
    $sql="select * from vw_fiche_attr where true";
  }
  // if e_type contains a list of value for filtering on fiche_def_ref.frd_id
  else{
    $list_fiche=$e_type;
    $sql="select * from vw_fiche_attr where frd_id in ( $list_fiche )";
    //    $sql="select * from vw_fiche_attr ";
  }
// e_fic_search contains the pattern

  if (strlen(trim($e_fic_search) ) == 0 ) {
    $Res=ExecSql($cn,$sql); 
  } else {
    $e_fic_search=FormatString($e_fic_search);
    $Res=ExecSql($cn,"$sql and ( upper(vw_name) like upper('%$e_fic_search%') or ".
               "upper(quick_code) like upper('%$e_fic_search%'))" ); 
  }

  // Test whether rows are returned
 if ( ($Max = pg_NumRows($Res) ) == 0 ) {
   echo_warning("Pas de fiche trouv�e");
   return;
 } 
 // Show the cards
 for ( $i=0; $i < $Max; $i++)  {

  //set focus on first "Select" button.
   
  $row=pg_fetch_array($Res,$i);
  // if quick code is empty pass
  if (trim($row['quick_code']) == "")
    continue;


  if ( $i %2  == 0 ) 
    $class="even";
  else
    $class="odd";
  $text=FormatString($row['vw_name']);
  $r.="<span class=\"$class\">";
  $qcode=    $row['quick_code'] ;

  $r.=sprintf ('<input name="%s" type="button" onClick="'."SetData('%s','%s','%s','%s','%s','%s','%s')".'" value="%s">',
        "select" . $i,
	      $e_name,
	      $row['quick_code'] ,
	      $text, 
	      $row['vw_sell'], 
	      $row['vw_buy'], 
	      $row['tva_id'], 
	       $row['tva_label']  ,
	       $qcode
	       );
  $r.="&nbsp;".$row['vw_name'];
  if ( $row['vw_addr'] !="")
    $r.="<br><font size=-1>Adresse:&nbsp;".$row['vw_addr']."&nbsp;".$row['vw_cp']."</font>";
  $r.="</span>";
 }
}
$r.="</div>";
$r.="</FORM>";
echo $r;

?>

<?php

html_page_stop();
?>
