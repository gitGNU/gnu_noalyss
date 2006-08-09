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
/*! \file
 * \brief Search a operation from a ledger into a popup window, used for the "rapprochement"
 */

require_once ("ac_common.php");
require_once ("postgres.php");
require_once("user_common.php");
/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();


html_page_start($User->theme,"onLoad='window.focus();'");
if ( ! isset ( $_SESSION['g_dossier'] ) ) {
  echo "You must choose a Dossier ";
  phpinfo();
  exit -2;
}
// Javascript
echo JS_CONCERNED_OP;
if ( isset( $p_jrn )) {
  $p_jrn=$p_jrn;
  $_SESSION[ "p_jrn"]=$p_jrn;

}
if (isset ($_GET['p_ctl'])) $p_ctl=$_GET['p_ctl'];
if (isset($_POST['p_ctl'])) $p_ctl=$_POST['p_ctl'];
$opt='<OPTION VALUE="="> =';
$opt.='<OPTION VALUE="<="> <=';
$opt.='<OPTION VALUE="<"> <';
$opt.='<OPTION VALUE=">"> >';
$opt.='<OPTION VALUE=">="> >=';
$opt_date=$opt;
$opt_montant=$opt;
$c_comment="";
$c_montant="";
$c_internal="";
$c_date="";
$condition="";
$part=" where ";
$cn=DbConnect($_SESSION['g_dossier']);
// if search then build the condition
if ( isset ($_GET["search"]) ) {
  $c1=0;
  foreach( $_GET as $key=>$element){
    echo_debug('jrn_search.php',__LINE__,"$key = $element");
    ${"$key"}=$element;
  }

  if ( strlen(trim($p_comment)) != 0 ) {
    $c_comment=" $part upper(jr_comment) like upper('%$p_comment%')";
    $part=" and ";
  }
  if ( strlen($p_montant) != 0 && (ereg ("^[0-9]*\.[0-9]*$",$p_montant) ||
				   ereg ("^[0-9]*$",$p_montant)) )
      { 
    $c_montant=sprintf(" $part jr_montant %s %s",$p_montant_sel,$p_montant);
    $opt_montant.='<OPTION VALUE="'.$p_montant_sel.'" SELECTED>'.$p_montant_sel;
    $part="  and ";
    }
  if ( strlen(trim($p_date)) != 0 ) {
      $c_date=sprintf(" $part j_date %s to_date('%s','DD.MM.YYYY')",$p_date_sel,$p_date);
      $part=" and ";
      $opt_date.='<OPTION VALUE="'.$p_date_sel.'" SELECTED>'.$p_date_sel;
  }
  if ( strlen(trim($p_internal)) != 0 ) {
    $c_internal=$part." jr_internal like  ('%".$p_internal."%')";
    $part=" and ";

  }

$condition=$c_comment.$c_montant.$c_date.$c_internal;
echo_debug('jrn_search.php',__LINE__,"condition = $condition");
}
$condition=$condition." ".$part;

// If the usr is admin he has all right
if ( $User->admin != 1 ) {
  $condition.="  uj_priv in ('W','R') and uj_login='".$User->id."'" ;
} else {
  $condition.=" uj_login='".$User->id."' ";
}
?>
<div style="font-size:11px;">
<?
echo '<FORM ACTION="jrn_search.php" METHOD="GET">';
echo '<TABLE>';
echo '<TR>';
if ( ! isset ($p_date)) $p_date="";
if ( ! isset ($p_montant)) $p_montant="";
if ( ! isset ($p_comment)) $p_comment="";
if ( ! isset ($p_internal)) $p_internal="";
echo '<input type="hidden" name="p_ctl" value="'.$p_ctl.'">';
echo '<TD> Date </TD>';
echo '<TD> <SELECT NAME="p_date_sel">'.$opt_date.' </TD>';
echo '<TD> <INPUT TYPE="text" name="p_date" VALUE="'.$p_date.'"></TD>';

echo '<TD> Montant </TD>';
echo '<TD> <SELECT NAME="p_montant_sel">'.$opt_montant.' </TD>';
echo '<TD> <INPUT TYPE="text" name="p_montant" VALUE="'.$p_montant.'"></TD>';
echo '</TR><TR>';
echo '<TD> Commentaire </TD>';
echo '<TD> contient </TD>';
echo '<TD> <INPUT TYPE="text" name="p_comment" VALUE="'.$p_comment.'"></TD>';
?>
</TR><TR>
<TD> Code interne </TD><TD>comme </TD>
<?
echo '<TD> <INPUT TYPE="text" name="p_internal" VALUE="'.$p_internal.'"></TD>';
echo "</TR>";

echo '</TABLE>';
echo '<INPUT TYPE="submit" name="search" value="cherche">';
echo '</FORM>';
echo '<div class="u_content">';
// if a search is asked otherwise don't show all the rows
if ( isset ($_GET["search"]) ) {
  $sql="select j_id,to_char(j_date,'DD.MM.YYYY') as j_date,
                 j_montant,j_poste,j_debit,j_tech_per,jr_id,jr_comment,j_grpt,pcm_lib,jr_internal from jrnx inner join 
                 jrn on jr_grpt_id=j_grpt inner join tmp_pcmn on j_poste=pcm_val ".
    " inner join user_sec_jrn on uj_jrn_id=j_jrn_def".
    $condition." order by jr_date,jr_id,j_debit desc";
  $Res=ExecSql($cn,$sql);

  $MaxLine=pg_NumRows($Res);
  $offset=(isset($_GET['offset']))?$_GET['offset']:0;
  $limit=$_SESSION['g_pagesize'];
  $sql_limit="";
  $sql_offset="";
  $bar="";
  if ( $limit != -1) {
    $page=(isset($_GET['page']))?$_GET['page']:0;
    $sql_limit=" LIMIT $limit ";
    $sql_offset=" OFFSET $offset ";
    $bar=jrn_navigation_bar($offset,$MaxLine,$limit,$page);

  }
  $sql.=$sql_limit.$sql_offset;
   if ( $MaxLine==0) { 
     html_page_stop();
     return;
   }
  $Res=ExecSql($cn,$sql);
  $MaxLine=pg_NumRows($Res);

  $col_vide="<TD></TD>";
  echo $bar;
  echo '<TABLE ALIGN="center" BORDER="0" CELLSPACING="O" width="100%">';
  $l_id="";
//   if ( $MaxLine > 250 ) {
//     echo "Trop de lignes redéfinir la recherche";
//     html_page_stop();
//     return;
//   }
  for ( $i=0; $i < $MaxLine; $i++) {
    $l_line=pg_fetch_array($Res,$i);
    if ( $l_id == $l_line['j_grpt'] ) {
      echo $col_vide.$col_vide;
    } else {
      echo '<TR style="background-color:lightblue"><TD>';
      echo '<INPUT TYPE="CHECKBOX" onClick="GetIt(\''.$p_ctl.'\',\''.$l_line['jr_id']."')\" >";
      echo "</TD>";

      echo "<TD>";
      echo $l_line['j_date'];
      echo "</TD>";
      
      echo "<TD>";
      echo $l_line['jr_internal'];
      echo "</TD>";
      $l_id=$l_line['j_grpt'];
      echo '<TD COLSPAN="4">'.$l_line['jr_comment'].'</TD></TR>';
    }
    if ( $l_line['j_debit'] == 't' ) {
      echo '<TR style="background-color:#E7FBFF;">';
    }
    else {
      echo '<TR style="background-color:#E7FFEB;">';
    }
    echo $col_vide;
    if ( $l_line['j_debit']=='f')
      echo $col_vide;

    echo '<TD>';
    echo $l_line['j_poste'];
    echo '</TD>';

    if ( $l_line['j_debit']=='t')
      echo $col_vide;

    echo '<TD>';
    echo $l_line['pcm_lib'];
    echo '</TD>';

    if ( $l_line['j_debit']=='f')
      echo $col_vide;

    echo '<TD>';
    echo $l_line['j_montant'];
    echo '</TD>';

    echo "</TR>";

  }
  
  echo '</TABLE>';
  echo $bar;
  echo '</div>';
}// if $_POST [search]
?>
</div>
<?
html_page_stop();
?>
