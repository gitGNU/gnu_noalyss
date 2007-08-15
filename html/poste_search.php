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
html_page_start($_SESSION['g_theme'],'onLoad="window.focus();"');
include_once ("postgres.php");
include_once("jrn.php");
/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();

echo JS_SEARCH_POSTE;

if ( ! isset ( $_SESSION['g_dossier'] ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}

$c_comment="";
$c_class="";

$condition="";
$cn=DbConnect($_SESSION['g_dossier']);
if ( isset($_GET['search']) ) {
  $c1=0;
  foreach( $_GET as $key=>$element){
    ${"$key"}=$element;
  }
  $condition="";
  if ( strlen(trim($p_comment)) != 0 ) {
    $condition=" where (upper(pcm_lib) like upper('%$p_comment%') or ".
      " pcm_val::text like '$p_comment%') ";
  }

}
$url="";

//--------------------------------------------------
// Filter defined in the ledger's parameter
// 
if ( isset($_GET['filter']) && $_GET['filter'] != 'all') {
  $url="?filter=1";
  // There is a filter, the value of the filter is the journal id, we
  // have to find what account are available
  $SqlCred="";

  // Load the property
  $l_line=GetJrnProp($_SESSION['g_dossier'],$_GET['p_jrn']);
  if ( strlen(trim ($l_line['jrn_def_class_cred']) ) > 0 ) {
    $valid_cred=split(" ",$l_line['jrn_def_class_cred']);

    // Creation query
    foreach ( $valid_cred as $item_cred) {
      if ( strlen (trim($item_cred))) {
	echo_debug('poste_search.php',__LINE__,"l_line[jrn_def_class_cred] $l_line[jrn_def_class_cred] item_cred $item_cred");
	if ( strstr($item_cred,"*") == true ) {
	  $item_cred=strtr($item_cred,"*","%");
	  $Sql=" pcm_val like '$item_cred' or";
	} else {
	  $Sql=" pcm_val = '$item_cred' or";
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
	  $Sql=" pcm_val like '$item_deb' or";
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
if ( isset($p_ctl) ) {
  if ($p_ctl != 'not')   echo '<INPUT TYPE="hidden" name="p_ctl" value="'.$p_ctl.'">';
}
echo '<TABLE>';
echo '<TR>';

/* echo '<TD>Poste Comptable Commence par  </TD>'; */
/* if ( ! isset ($p_class) ) $p_class=""; */
/* $opt=" <INPUT TYPE=\"text\" value=\"$p_class\" name=\"st_with\">"; */
/* echo '<TD> <INPUT TYPE="text" name="p_class" VALUE="'.$p_class.'"></TD>'; */

echo '<TD> Libellé ou poste comptable</TD>';
echo '<TD> contient </TD>';
if ( ! isset ($p_comment) ) $p_comment="";
echo '<TD> <INPUT TYPE="text" name="p_comment" VALUE="'.$p_comment.'"></TD></TR>';
echo '</TABLE>';
echo '<INPUT TYPE="submit" name="search" value="cherche">';
echo '</FORM>';

// if request search
if ( isset($_GET['search']) or isset($_GET['filter']) ) {
  $Res=ExecSql($cn,"select pcm_val,pcm_lib from tmp_pcmn $condition order by pcm_val::text");
  
  $MaxLine=pg_NumRows($Res);
  if ( $MaxLine==0) { 
    html_page_stop();
    return;
  }
  echo '<TABLE BORDER="0">';
  $l_id="";
  
  for ( $i=0; $i < $MaxLine; $i++) {
    $l_line=pg_fetch_array($Res,$i);
    echo "<TR>";
    // if p_ctl is set we need to be able to return the value
    if (isset($p_ctl) && $p_ctl != 'not' ){
      echo '<TD>';
      $slabel=FormatString($l_line['pcm_lib']);
      echo '<input type="checkbox" onClick="SetItChild(\''.$p_ctl.'\',\''.$l_line['pcm_val'].'\',\''.
	$slabel.'\')">';
      echo '</td>';
    }
    echo '<TD>';
    echo $l_line['pcm_val'];
    echo '</TD>';

    echo '<TD>';
    echo $l_line['pcm_lib'];
    echo '</TD>';
    echo "</TR>";

  }
  
  echo '</TABLE>';
}
echo '<INPUT TYPE="BUTTON" Value="Close" onClick=\'GetIt()\'>';
html_page_stop();
?>
