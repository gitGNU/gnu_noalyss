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
 * \brief Check if still used
 */

if ( $action == 'update' ) {
    if ( CheckJrn($g_dossier,$g_user,$p_jrn) < 2 ) {
      NoAccess();
      exit -1;
    
    }
    // p_id is jrn.jr_id
    $p_id=$_GET["line"];
    echo_debug('user_update.php',__LINE__," action = update p_id = $p_id");

    echo JS_CONCERNED_OP;
    $r ='<FORM METHOD="POST" ACTION="user_jrn.php">';
    $r.=UpdateJrn($cn,$p_id);
    $r.='<INPUT TYPE="Hidden" name="action" value="update_record">';
    $r.='<input type="SUBMIT" name="update_record" value="Enregistre">';
    $r.='</FORM>';
    echo '<div class="redcontent">';
    echo $r;
    echo '</div>';
  }    
if ( isset($_POST['update_record']) ) {
  // NO UPDATE except rapt & comment
  UpdateComment($cn,$_POST['jr_id'],$_POST['comment']);
  InsertRapt($cn,$_POST['jr_id'],$_POST['rapt']);
  echo '<div class="redcontent">';
    ViewJrn($g_dossier,$g_user,$p_jrn,"user_jrn.php");
  echo '</div>';

} // if update_record
?>