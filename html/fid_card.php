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
/* $Revision$ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/*!\file
 * \brief this file is used by the autocomplete functionnality
 *\see ICard
 */


require_once('class_database.php');
require_once('class_dossier.php');
/*!\brief
 *  Received parameters are 
 *   - j for the ledger
 *   - e for extra (typecard)
 *   - type is the ledger type (ach, ven, fin, gl or nothing) 
 *   - FID contains the string the user is typing
 *\note the typecard can be
 *   - cred card for the debit only if j is set
 *   - deb card for the debit only if j is set
 *   - filter card for debit and credit only if j OR type is set
 *   - list of fd_id
 *     
 */

$jrn= ( ! isset($_REQUEST['j']))?-1:$_REQUEST['j'];
$filter_card="";
$cn=new Database(dossier::id());
$d=$_REQUEST['e'];
$filter_card='';

require_once('class_user.php');
$user=new User($cn);
$user->check();
$user->check_dossier(dossier::id());

switch ($d) {
   case 'all':
      $filter_card='';
      break;
    
    default:
      $filter_card="and fd_id in ($d)";
}

if ( $jrn != -1 ) {
    switch ($d) {
    case 'cred':
      $filter_jrn=$cn->make_list("select jrn_def_fiche_cred from jrn_def where jrn_def_id=$1",array($jrn));
      $filter_card=($filter_jrn != "")?" and fd_id in ($filter_jrn)":' and false ';
      break;
    case 'deb':
      $filter_jrn=$cn->make_list("select jrn_def_fiche_deb from jrn_def where jrn_def_id=$1",array($jrn));
      $filter_card=($filter_jrn != "")?" and fd_id in ($filter_jrn)":' and false ';
      break;
    case 'filter':
      $get_cred='jrn_def_fiche_cred';
      $get_deb='jrn_def_fiche_deb';
      $filter_jrn=$cn->make_list("select $get_cred||','||$get_deb as fiche from jrn_def where jrn_def_id=$1",array($jrn));
      $filter_card=($filter_jrn != "")?" and fd_id in ($filter_jrn)":' and false ';

      break;
 
    }
} else {
  if (isset($_REQUEST['type'])) {
    if ($_REQUEST['type']=='gl' || $_REQUEST['type']=='') $filter_card='';
    else {
      $get_cred='jrn_def_fiche_cred';
      $get_deb='jrn_def_fiche_deb';

      $filter_jrn=$cn->make_list("select $get_cred||','||$get_deb as fiche from jrn_def where jrn_def_type=$1",array($_REQUEST['type']));
      $filter_card=($filter_jrn != "")?" and fd_id in ($filter_jrn)":' and false ';

    }
  }
}


/* create a filter based on j */
/*$sql_str="select f_id, vw_name,quick_code,vw_description ".
  " from vw_fiche_attr where  ".
  " ( vw_name ilike '%'||$1||'%' or quick_code ilike $2||'%' or vw_description ilike '%'||$3||'%')    ".
  $filter_card;
*/

$sql_str="select distinct f_id from fiche join jnt_fic_att_value using (f_id) join attr_value using(jft_id) where ad_id in (9,1,23) and av_text ilike '%'||$1||'%' ".$filter_card;

$sql=$cn->get_array($sql_str		    ,array($_REQUEST['FID']));

if (sizeof($sql) != 0 ) {
  echo "<ul>";
  $sql_get=$cn->prepare('get_name',"select av_text from jnt_fic_att_value join attr_value using (jft_id) where f_id = $1 and ad_id=$2");

  for ($i =0;$i<12 && $i < count($sql) ;$i++) {
    $name='';$quick_code='';$desc='';

    $sql_name=$cn->execute('get_name',array($sql[$i]['f_id'],1));
    if ( Database::num_row($sql_name) == 1) $name=Database::fetch_result($sql_name,0,0);

    $sql_name=$cn->execute('get_name',array($sql[$i]['f_id'],9));
    if ( Database::num_row($sql_name) == 1) $desc=Database::fetch_result($sql_name,0,0);

    $sql_name=$cn->execute('get_name',array($sql[$i]['f_id'],23));
    if (Database::num_row($sql_name) == 1) $quick_code=Database::fetch_result($sql_name,0,0);


    /* Highlight the found pattern with bold format */
    $name=str_ireplace($_REQUEST['FID'],'<em>'.$_REQUEST['FID'].'</em>',h($name));
    $qcode=str_ireplace($_REQUEST['FID'],'<em>'.$_REQUEST['FID'].'</em>',h($quick_code));
    $desc=str_ireplace($_REQUEST['FID'],'<em>'.$_REQUEST['FID'].'</em>',h($desc));
    printf('<li id="%s">%s <span class="informal"> %s %s</span></li>',
	   $quick_code,
	   $quick_code,
	   $name,
	   $desc
	   );
  }
  echo '</ul>';
  if (count($sql) > 12) {
    printf ('<i>...Résultat limité à 12  ...</i>');
  }
} else {
  echo "<ul><li>Non trouvé</li></ul>";
}
?>
