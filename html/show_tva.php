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
CheckUser();

if ( ! isset ( $g_dossier ) ) {
  echo "You must choose a Dossier ";
  phpinfo();
  exit -2;
}


$l_Db=sprintf("dossier%d",$g_dossier);
$condition="";
$cn=DbConnect($l_Db);
$Res=ExecSql($cn,"select * from tva_rate order by tva_rate desc");
$Max=pg_NumRows($Res);
echo "<TABLE BORDER=\"1\">";
for ($i=0;$i<$Max;$i++) {
  $row=pg_fetch_array($Res,$i);
  printf("<tr><TD BGCOLOR=\LIGHTGREEN\" >%d</TD><TD>%s</TD><TD>%s</TD></TR>",
	 $row['tva_id'],
	 $row['tva_label'],
	 $row['tva_comment']);
}
echo '</TABLE>';
echo '<INPUT TYPE="BUTTON" Value="Close" onClick=\'window.close()\'>';
html_page_stop();
?>
