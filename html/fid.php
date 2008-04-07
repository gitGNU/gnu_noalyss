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
 * \brief Fid for the ajax request for cards
 */


require_once  ("constant.php");
require_once  ("postgres.php");
require_once ("user_common.php");
require_once ("debug.php");
require_once('class_dossier.php');
$gDossier=dossier::id();

echo_debug('fid.php',__LINE__,"Recherche fid.php".$_GET["FID"]);
$cn=DbConnect($gDossier);
if ( isset($_SESSION['isValid']) && $_SESSION['isValid'] == 1)
{ 
  $d=$_GET['d'];
  $jrn=$_GET['j'];

  switch ($d) {
  case 'cred':
    $filter_jrn=getDbValue($cn,"select jrn_def_fiche_cred from jrn_def where jrn_def_id=$1",array($jrn));
    $filter_card="and fd_id in ($filter_jrn)";
    break;
  case 'deb':
    $filter_jrn=getDbValue($cn,"select jrn_def_fiche_deb from jrn_def where jrn_def_id=$1",array($jrn));
    $filter_card="and fd_id in ($filter_jrn)";
    break;
  case 'all':
    $filter_card="";
    break;
  case 'filter':
    $get_cred='jrn_def_fiche_cred';

    $get_deb='jrn_def_fiche_deb';

    $filter_jrn=getDbValue($cn,"select $get_cred||','||$get_deb as fiche from jrn_def where jrn_def_id=$1",array($jrn));

    $filter_card="and fd_id in ($filter_jrn)";
    break;
  default:
    $filter_card="and frd_id in ($d)";
  }


  $array=get_array($cn,"select vw_name,vw_addr,vw_cp,vw_buy,vw_sell,tva_id 
                    from vw_fiche_attr 
                    where quick_code=upper('".$_GET['FID']."') $filter_card"
		    );
  echo_debug("fid",__LINE__,$array);
  $name=$array[0]['vw_name']." ".$array[0]['vw_addr']." ".$array[0]['vw_cp'];
  $sell=$array[0]['vw_sell'] ;
  $buy=$array[0]['vw_buy'];
  $tva_id=$array[0]['tva_id'];

  // Check null
  $name=($name==null)?" ":str_replace('"','',$name);
  $sell=($sell==null)?" ":str_replace('"','',$sell);
  $buy=($buy==null)?" ":str_replace('"','',$buy);
  $tva_id=($tva_id==null)?" ":str_replace('"','',$tva_id);

  $a='{"answer":"ok","name":"'.$name.'","sell":"'.$sell.'","buy":"'.$buy.'","tva_id":"'.$tva_id.'","ctl":"'.$_GET['ctl'].'"}';

}
     else
     $a='{"answer":"nok"}';
echo_debug("fid.php",__LINE__,"Answer is \n $a");
header("Content-type: text/html; charset: utf8",true);
print $a;
?>
