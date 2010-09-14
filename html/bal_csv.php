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
/*! \file
 * \brief Return the balance in CSV format
 */
header('Pragma: public');
header('Content-type: application/csv');
header('Content-Disposition: attachment;filename="balance.csv"',FALSE);
include_once ("ac_common.php");
include_once("class_acc_balance.php");
require_once('class_database.php');
require_once('class_dossier.php');
$gDossier=dossier::id();

require_once("class_acc_ledger.php");
$cn=new Database($gDossier);


require_once ('class_user.php');
$User=new User($cn);
$User->Check();
if ( $User->check_action(IMPBAL) == 0)
{
    NoAccess();
    exit;
}
echo 'poste;libelle;deb;cred;solde deb;solde cred';
printf("\n");
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
        $array_ledger=$User->get_ledger('ALL',3);
        for ($e=0;$e<count($array_ledger);$e++)
        {
            if (isset ($selected[$e]))
                $bal->jrn[]=$array_ledger[$e]['jrn_def_id'];
        }
    }
    break;
case 2:
    if ( isset($_GET['r_cat']))   $bal->filter_cat($_GET['r_cat']);
    break;
}

$bal->from_poste=$_GET['from_poste'];
$bal->to_poste=$_GET['to_poste'];

$row=$bal->get_row($_GET['from_periode'],
                   $_GET['to_periode']);
foreach ($row as $r)
{
    echo $r['poste'].';'.
    $r['label'].';'.
    nb($r['sum_deb']).';'.
    nb($r['sum_cred']).';'.
    nb($r['solde_deb']).';'.
    nb($r['solde_cred']);
    printf("\n");
}


?>
