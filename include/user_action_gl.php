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
echo_debug('user_action_gl.php',__LINE__,"include user_action_gl.php");

include_once ("preference.php");
include_once ("user_common.php");
include_once ("class_widget.php");
include_once("class_user.php");
include("jrn.php");

$cn=DbConnect($_SESSION['g_dossier']);
if (CheckJrn($_SESSION['g_dossier'],$_SESSION['g_user'],0)  < 1 )
  {NoAccess();exit -1;}

?>
<div class="u_redcontent">
<form method="GET" action="user_jrn.php">
<?

$hid=new widget("hidden");

$hid->name="JRN_TYPE";
$hid->value="NONE";
echo $hid->IOValue();

$hid->name="action";
$hid->value="voir_jrn";
echo $hid->IOValue();

$w=new widget("select");

$periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode order by p_id");
$User=new cl_user($cn);
$current=(isset($_GET['p_periode']))?$_GET['p_periode']:$User->GetPeriode();
$w->selected=$current;
echo 'Période  '.$w->IOValue("p_periode",$periode_start).$w->Submit('gl_submit','Valider');
?>
</form>
<?

$sql=SQL_LIST_ALL_INVOICE." and jr_tech_per=".$current;
// Nav. bar 
$step=$_SESSION['g_pagesize'];
$page=(isset($_GET['offset']))?$_GET['page']:1;
$offset=(isset($_GET['offset']))?$_GET['offset']:0;

list ($max_line,$list)=ListJrn($cn,0,$sql,null,$offset);

$bar=jrn_navigation_bar($offset,$max_line,$step,$page);

echo $bar;
echo $list;
echo $bar;

?>
</div>
