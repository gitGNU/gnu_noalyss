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
/* $Revision*/
include_once ("ac_common.php");
include_once ("poste.php");
include_once("preference.php");
include_once("central_inc.php");
include_once("check_priv.php");
include_once("user_common.php");

html_page_start($g_UserProperty['use_theme']);
if ( ! isset ( $g_dossier ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}
include_once ("postgres.php");
include_once("jrn.php");
/* Admin. Dossier */
CheckUser();

$dossier=sprintf("dossier%d",$g_dossier);
$cn=DbConnect($dossier);

if ( isset( $p_jrn )) {
  session_register("g_jrn");
  $g_jrn=$p_jrn;
}
if ( isset ( $_GET['action'] ) ) {
  $action=$_GET['action'];
}

if ( isset ( $_POST['action'] ) ) {
  $action=$_POST['action'];
}
if ( ! isset ( $action )) {
  echo_error("modify_op.php No action asked ");
  exit();
}

// Javascript
echo JS_VIEW_JRN_MODIFY;

if ( $action == 'update' ) {
  if ( ($priv=CheckJrn($g_dossier,$g_user,$g_jrn)) < 1 ) {
      NoAccess();
      exit -1;
    
    }
    // p_id is jrn.jr_id
    $p_id=$_GET["line"];
    echo_debug(" action = update p_id = $p_id");
    echo JS_VIEW_JRN_DETAIL;
    echo JS_CONCERNED_OP;
    $r ='<FORM METHOD="POST" ACTION="modify_op.php">';
    $r.=UpdateJrn($cn,$p_id);

    $r.='<INPUT TYPE="Hidden" name="action" value="update_record">';
    $r.='<input type="SUBMIT" name="update_record" value="Enregistre">';
    $r.='</FORM>';
    $r.='<br> <br> <input type="button" value="Fermer" onClick="window.close();">';
//    echo '<div class="redcontent">';
    echo $r;
//    echo '</div>';
  }    
if ( isset($_POST['update_record']) ) {
  if ( ($priv=CheckJrn($g_dossier,$g_user,$g_jrn)) !=2 ) {
      NoAccess();
      exit -1;
    
    }

  // NO UPDATE except rapt & comment
  UpdateComment($cn,$_POST['jr_id'],$_POST['comment']);
  InsertRapt($cn,$_POST['jr_id'],$_POST['rapt']);
  if ( isset ($_POST['is_paid'] ))
       $Res=ExecSql($cn,"update jrn set jr_rapt='paid' where jr_id=".$_POST['jr_id']);
  echo ' <script> 
 window.close();
 self.opener.RefreshMe();
 </script>';
} // if update_record
if (  $action  == 'delete' ) {
  echo_debug(" Call   DeleteRapt($cn,".$_GET['line'].",".$_GET['line2'].")");
  DeleteRapt($cn,$_GET['line'],$_GET['line2']);
 echo ' <script> 
  window.close();
 self.opener.RefreshMe();
 </script>';
}
html_page_stop();
?>
