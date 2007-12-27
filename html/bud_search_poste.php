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
print_r($_GET);


include_once ("postgres.php");
include_once("jrn.php");
/* Admin. Dossier */
$rep=DbConnect();


html_page_start($_SESSION['g_theme'],'onLoad="window.focus();"');

include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();



echo JS_BUD_SCRIPT;

require_once('class_dossier.php');
$gDossier=dossier::id();

$c_comment="";
$c_class="";

$condition="";
$cn=DbConnect($gDossier);
extract ($_GET);

if ( isset($_GET['search']) ) {

  $condition="";
  if ( isset($p_comment) && strlen(trim($p_comment)) != 0 ) {
    $condition=" where (upper(pcm_lib) like upper('%$p_comment%') or ".
      " pcm_val::text like '$p_comment%') ".
      " and (pcm_val::text like '7%' or pcm_val::text like '6%') ";
  } else {
    $condition="where pcm_val::text like '7%' or pcm_val::text like '6%' ";    
  }

}

// Control to update in the calling doc.
if ( isset($_GET['p_ctl'])) {
  $p_ctl=$_GET['p_ctl'];
}


echo '<FORM METHOD="GET">';
echo dossier::hidden();
if ( isset($p_ctl) ) {
  if ($p_ctl != 'not')   echo '<INPUT TYPE="hidden" name="p_ctl" value="'.$p_ctl.'">';
}
echo '<TABLE>';
echo '<TR>';


echo '<TD> Libellé ou poste comptable</TD>';
echo '<TD> contient </TD>';
if ( ! isset ($p_comment) ) $p_comment="";
echo '<TD> <INPUT TYPE="text" name="p_comment" VALUE="'.$p_comment.'"></TD></TR>';
echo '</TABLE>';
echo '<INPUT TYPE="submit" name="search" value="cherche">';
echo '</FORM>';

// if request search
if ( isset($_GET['search'])){
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
