<?php
/*
 *   This file is part of NOALYSS.
 *
 *   NOALYSS is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   NOALYSS is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with NOALYSS; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
// Copyright Author Dany De Bontridder danydb@aevalys.eu
/*! \file
 * \brief Return the balance in CSV format
 */
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
header('Pragma: public');
header('Content-type: application/csv');
header('Content-Disposition: attachment;filename="balance.csv"',FALSE);
include_once ("ac_common.php");
include_once("class_acc_balance.php");
require_once NOALYSS_INCLUDE.'/class_database.php';
require_once NOALYSS_INCLUDE.'/class_dossier.php';
$gDossier=dossier::id();

require_once NOALYSS_INCLUDE.'/class_acc_ledger.php';
$cn=new Database($gDossier);


require_once  NOALYSS_INCLUDE.'/class_user.php';

$bal=new Acc_Balance($cn);
$bal->jrn=null;
switch( $_GET['p_filter'])
{
case 0:
        $bal->jrn=null;
    break;
case 1:
    if (  isset($_GET['r_jrn']))
    {
        $selected=$_GET['r_jrn'];
        $array_ledger=$g_user->get_ledger('ALL',3);
        $array=get_array_column($array_ledger,'jrn_def_id');
        for ($e=0;$e<count($selected);$e++)
        {
            if (isset ($selected[$e]) && in_array ($selected[$e],$array) )
                $bal->jrn[]=$selected[$e];
        }
    }
    break;
case 2:
    if ( isset($_GET['r_cat']))   $bal->filter_cat($_GET['r_cat']);
    break;
}

$bal->from_poste=$_GET['from_poste'];
$bal->to_poste=$_GET['to_poste'];
if (isset($_GET['unsold'])) $bal->unsold=true;
$prev = (isset($_GET['previous_exc'])) ? 1: 0;

$row=$bal->get_row($_GET['from_periode'],
                   $_GET['to_periode'],
        $prev);
$prev =  ( isset ($row[0]['sum_cred_previous'])) ?1:0;
echo 'poste;libelle;';
if ($prev  == 1 ) echo 'deb n-1;cred n-1;solde deb n-1;solde cred n-1;';
echo 'deb;cred;solde deb;solde cred';
printf("\n");
foreach ($row as $r)
{
    echo $r['poste'].';'.
    $r['label'].';';
    if ( $prev == 1 )
    {
       echo  nb($r['sum_deb_previous']).';'.
        nb($r['sum_cred_previous']).';'.
        nb($r['solde_deb_previous']).';'.
        nb($r['solde_cred_previous']).';';
    }
    echo nb($r['sum_deb']).';'.
    nb($r['sum_cred']).';'.
    nb($r['solde_deb']).';'.
    nb($r['solde_cred']);
    printf("\n");
}


?>
