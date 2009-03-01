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
 * \brief Search a account in a popup window
 */
include_once ("ac_common.php");
require_once('class_acc_ledger.php');

html_min_page_start($_SESSION['g_theme'],'onLoad="window.focus();"');
include_once ("postgres.php");
include_once("jrn.php");
/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new User($rep);
$User->Check();

echo JS_SEARCH_POSTE;

require_once('class_dossier.php');
$gDossier=dossier::id();

$c_comment="";
$c_class="";
extract ($_GET);

$condition="";
$cn=DbConnect($gDossier);
if ( isset($_GET['search']) ) {
  $c1=0;

  $condition="";
  if ( strlen(trim($p_comment)) != 0 ) {
    $condition=" where (upper(pcm_lib) like upper('%".pg_escape_string($p_comment)."%') or ".
      " pcm_val::text like '%".pg_escape_string($p_comment)."%') ";
  }

}
$url="";
$ctrl=(isset($_GET['ctrl']))?$_GET['ctrl']:"";
//--------------------------------------------------
// Filter defined in the ledger's parameter
// 
if ( isset($_GET['filter']) && $_GET['filter'] != 'all') {
  $url="?filter=1";
  // There is a filter, the value of the filter is the journal id, we
  // have to find what account are available
  $SqlCred="";

  // Load the property
   
  $jrn=new Acc_Ledger($cn,$_GET['p_jrn']);
  $l_line=$jrn->get_propertie();

  if ( strlen(trim ($l_line['jrn_def_class_deb']) ) > 0 ) {
    $valid_cred=split(" ",$l_line['jrn_def_class_deb']);

    // Creation query
    foreach ( $valid_cred as $item_cred) {
      if ( strlen (trim($item_cred))) {
	if ( strstr($item_cred,"*") == true ) {
	  $item_cred=strtr($item_cred,"*","%");
	  $Sql=" pcm_val::text like '$item_cred' or";
	} else {
	  $Sql=" pcm_val::text = '$item_cred' or";
	}
	$SqlCred=$SqlCred.$Sql;
      }
    }//foreach
   
  }
  if ( strlen(trim ($l_line['jrn_def_class_deb']) ) > 0 ) {
    $valid_deb=split(" ",$l_line['jrn_def_class_deb']);

    // Creation query
    foreach ( $valid_deb as $item_deb) {
      if ( strlen (trim($item_deb))) {
	echo_debug('poste_search.php',__LINE__,"l_line[jrn_def_class_deb] $l_line[jrn_def_class_deb] item_deb $item_deb");
	if ( strstr($item_deb,"*") == true ) {
	  $item_cred=strtr($item_deb,"*","%");
	  $Sql=" pcm_val::text like '$item_deb' or";
	} else {
	  $Sql=" pcm_val = '$item_deb' or";
	}
	$SqlCred=$SqlCred.$Sql;
      }
    }//foreach
       
  }
  if ( $condition=="") {
    $condition .= ($SqlCred=="")?"":" where  ".substr($SqlCred,0,strlen($SqlCred)-2);
  } else {
    $condition .= ($SqlCred=="")?"":" and (  ".substr($SqlCred,0,strlen($SqlCred)-2)." ) ";
  }
}// if (isset($_GET['filter']))


// Control to update in the calling doc.
if ( isset($_GET['p_ctl'])) {
  $p_ctl=$_GET['p_ctl'];
}


echo_debug('poste_search.php',__LINE__,"condition = $condition");

echo '<FORM ACTION="poste_search.php'.$url.'" METHOD="GET">';
echo dossier::hidden();

echo HtmlInput::hidden('ctrl',$_GET['ctrl']);
if ( isset($p_ctl) ) {
    echo '<INPUT TYPE="hidden" name="p_ctl" value="'.$p_ctl.'">';
}
if (isset ($ret)) echo HtmlInput::hidden('ret',$ret);

echo 'Libellé ou poste comptable ';
echo ' contient ';
if ( ! isset ($p_comment) ) $p_comment="";
echo ' <INPUT TYPE="text" name="p_comment" VALUE="'.$p_comment.'"></TD></TR>';
echo '<INPUT TYPE="submit" name="search" value="cherche">';
echo '</FORM>';
echo '<p class="notice">Nombre de lignes affichées est limité</p>';
// if request search
if ( isset($_GET['search']) || isset($_GET['filter']) ) {
  $Res=ExecSql($cn,"select pcm_val,html_quote(pcm_lib) as pcm_lib from tmp_pcmn $condition order by pcm_val::text ".
	       " limit 100");
  
  $MaxLine=pg_NumRows($Res);
  if ( $MaxLine==0) { 
    html_page_stop();
    return;
  }
  echo '<TABLE style="width:90%;border-collapse:collapse;">';
  $l_id="";
  $ahref="";
  $end_ahref="";  
  for ( $i=0; $i < $MaxLine; $i++) {
    $l_line=pg_fetch_array($Res,$i);
    $even=($i%2 == 0)?"odd":"even";
    echo "<TR class=\"$even\">";
    // if p_ctl is set we need to be able to return the value
    if (isset($ret) && $ret == 'label' ){
      $slabel=FormatString($l_line['pcm_lib']);
	$ahref='<A href="#" class="mtitle" onClick="SetItChild(\''.$p_ctl.'\',\''.$l_line['pcm_val'].'\',\''.
	  $slabel.'\',\''.$ctrl.'\')">';
	
      $end_ahref='</A>';

    } else if     (isset($ret) && $ret == 'poste' ){
      $ahref='<A href="#" class="mtitle" onClick="set_poste_child(\''.$p_ctl.'\',\''.$l_line['pcm_val'].'\')">';
      $end_ahref='</A>';

    } else if     (isset($ret) && $ret == 'jrn' ){
      $ahref='<A href="#" class="mtitle" onClick="set_jrn_child(\''.$p_ctl.'\',\''.$l_line['pcm_val'].'\')">';
      $end_ahref='</A>';

    } 

    echo "<TD class=\"$even\">";
    echo $ahref;
    echo $l_line['pcm_val'];
    echo $end_ahref;
    echo '</TD>';

    echo '<TD>';
    echo $ahref;
    echo $l_line['pcm_lib'];
    echo $end_ahref;
    echo '</TD>';
    echo "</TR>";

  }
  
  echo '</TABLE>';
}
echo '<INPUT TYPE="BUTTON" Value="Fermer" onClick=\'GetIt()\'>';
html_page_stop();
?>
