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
echo_debug(__FILE__,__LINE__,"include user_action_gl.php");

include_once ("preference.php");
include_once ("user_common.php");
include_once ("class_widget.php");
include_once("class_jrn.php");
include_once("class_user.php");

$dossier=sprintf("dossier%d",$g_dossier);
$cn=DbConnect($dossier);
?>
<div class="u_redcontent">
<form method="post" action="user_jrn.php?JRN_TYPE=NONE">
<?
$w=new widget("select");

$periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode order by p_id");
$User=new cl_user($cn);
$current=(isset($_POST['p_periode']))?$_POST['p_periode']:$User->GetPeriode();
$w->selected=$current;
echo 'Période'.$w->IOValue("p_periode",$periode_start).$w->Submit('gl_submit','Valider');
?>
</form>
<?
$Jrn = new jrn($cn,0);
$Jrn->GetRow($current,$current);
 if ( count($Jrn->row ) == 0 ) 
  	exit;

  echo "<TABLE>";
foreach ( $Jrn->row as $op ) { 
      echo "<TR>".
	"<TD>".$op['internal']."</TD>".
	"<TD>".$op['j_date']."</TD>".
	"<TD>".$op['poste']."</TD>".
	"<TD>".$op['description']."</TD>".
	"<TD>".$op['deb_montant']."</TD>".
	"<TD>".$op['cred_montant']."</TD>".
	"</TR>";
  }
  echo "</table>";
?>
</div>