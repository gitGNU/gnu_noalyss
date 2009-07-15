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
 * \brief included file for the great ledger 
 */

include_once ("user_common.php");
include_once("class_user.php");
require_once("class_iselect.php");
require_once("jrn.php");

$cn=new Database($gDossier);

echo '<div class="u_content">
      <form method="GET">';  
echo dossier::hidden();
echo HtmlInput::hidden('p_action','gl');

$w=new ISelect();
// filter on the current year
$filter_year=" where p_exercice='".$User->get_exercice()."'";

$periode_start=$cn->make_array("select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by p_start,p_end",1);
$current=(isset($_GET['p_periode']))?$_GET['p_periode']:$User->get_periode();
$w->selected=$current;
echo 'Période  '.$w->input("p_periode",$periode_start).HtmlInput::submit('gl_submit','Valider');

echo '</form>';

if ( $current == -1) {
  $cond=" and jr_tech_per in (select p_id from parm_periode where p_exercice='".$User->get_exercice()."')";
 } else {
  $cond=" and jr_tech_per=".$current;
 }
/* security filter on the ledger */
$sql_ledger='';
if ( $User->Admin() == 0 && $User->is_local_admin()==0) { $sql_ledger=' and  '.$User->get_ledger_sql();}
$sql=SQL_LIST_ALL_INVOICE.$cond.$sql_ledger;
// Nav. bar 
$step=$_SESSION['g_pagesize'];
$page=(isset($_GET['offset']))?$_GET['page']:1;
$offset=(isset($_GET['offset']))?$_GET['offset']:0;

list ($max_line,$list)=ListJrn($cn,$sql,null,$offset);

$bar=jrn_navigation_bar($offset,$max_line,$step,$page);

echo $bar;
echo $list;
echo $bar;

?>
</div>
