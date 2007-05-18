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
echo_debug('user_action_gl.php',__LINE__,"include user_action_gl.php");
/*! \file
 * \brief included file for the great ledger 
 */

include_once ("preference.php");
include_once ("user_common.php");
include_once ("class_widget.php");
include_once("class_user.php");
require_once("jrn.php");

$cn=DbConnect($_SESSION['g_dossier']);

?>
<div class="u_redcontent">
<form method="GET" action="user_jrn.php">
<?php  

$hid=new widget("hidden");

$hid->name="jrn_type";
$hid->value="NONE";
echo $hid->IOValue();

$hid->name="action";
$hid->value="voir_jrn";
echo $hid->IOValue();

$w=new widget("select");
// filter on the current year
$filter_year=" where p_exercice='".$User->getExercice()."'";

$periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by p_start,p_end",1);
$current=(isset($_GET['p_periode']))?$_GET['p_periode']:$User->GetPeriode();
$w->selected=$current;
echo 'Période  '.$w->IOValue("p_periode",$periode_start).$w->Submit('gl_submit','Valider');
?>
</form>
<?php  
 if ( $current == -1) {
   $cond=" and jr_tech_per in (select p_id from parm_periode where p_exercice='".$User->getExercice()."')";
 } else {
   $cond=" and jr_tech_per=".$current;
 }

$sql=SQL_LIST_ALL_INVOICE.$cond;
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
