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
html_page_start($g_UserProperty['use_theme']);
include_once ("postgres.php");
/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();

if ( ! isset ( $g_dossier ) ) {
  echo "You must choose a Dossier ";
  phpinfo();
  exit -2;
}
?>
<script>
function GetIt(ctl,tva_id) {
           self.opener.SetIt(ctl,tva_id)
	   window.close();	
	} 
</script>
<?

$condition="";
$cn=DbConnect($g_dossier);
$Res=ExecSql($cn,"select * from tva_rate order by tva_rate desc");
$Max=pg_NumRows($Res);
echo "<TABLE BORDER=\"1\">";
for ($i=0;$i<$Max;$i++) {
  $row=pg_fetch_array($Res,$i);
  $set=sprintf( '<INPUT TYPE="BUTTON" Value="select" onClick="GetIt(\'%s\',\'%s\');">',
	     $_GET['ctl'],$row['tva_id']);
  printf("<tr><TD BGCOLOR=\LIGHTGREEN\" >%s %d</TD><TD>%s</TD><TD>%s</TD></TR>",
	 $set,
	 $row['tva_id'],
	 $row['tva_label'],
	 $row['tva_comment']);
}
echo '</TABLE>';
?>
<input type='button' Value="fermer" onClick='window.close();'>
<?
html_page_stop();
?>
