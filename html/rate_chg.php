<?
/*
 *   This file is part of WCOMPTA.
 *
 *   WCOMPTA is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   WCOMPTA is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with WCOMPTA; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
// Auteur Dany De Bontridder ddebontridder@yahoo.fr
include_once("ac_common.php");
html_page_start();
echo_debug("code : $code");
include_once("postgres.php");
?>
<script>
function SaveRate(p_code,p_rate) 
{
<?
  $dossier=sprintf("dossier%d",$g_dossier);
  $cn=DbConnect($dossier);
  $Res=ExecSql($cn,"update  parm_money set pm_rate=$f_rate where pm_code='$f_code'");
?>
    //  this.location.reload();

}
</script>
<?
CheckUser();
if ( CheckAdmin()==0 ) return;
$dossier=sprint("dossier%d",$g_dossier);
$cn=DbConnect($dossier);
$Res=ExecSql($cn,"select pm_code, pm_rate from parm_money where pm_code='$p_code'");
$l_line=pg_fetch_array($Res,0);

echo '<FORM METHOD="POST" onSubmit="SaveRate(\''.$l_line[pm_code].'\',$l_line[pm_rate])">';
echo '<INPUT TYPE="HIDDEN" VALUE="'.$l_line[pm_code].'" NAME="f_code">';
echo "$l_line[pm_code]";
echo '<INPUT TYPE="TEXT" VALUE="'.$l_line[pm_rate].'" NAME="f_rate">';
echo "</FORM>";
html_page_stop();
?>
