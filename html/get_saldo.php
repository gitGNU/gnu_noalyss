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
 * \brief get the saldo of a account
 * the get variable are :
 *  - l the jrn id
 *  - ctl the ctl where to get the quick_code
 */
require_once('class_user.php');
require_once('class_dossier.php');
require_once('class_fiche.php');
extract($_GET);
/* check the parameters */
foreach ( array('j','ctl') as $a ) {
  if ( ! isset(${$a}) ) {
    echo "missing $a";
    return;
  }
}

$cn=new Database(dossier::id());
$user=new User($cn);
$user->check();
if ( $user->check_jrn($_GET['j'])=='X' ) return '{"saldo":"0"}';
/*  make a filter on the exercice */

$filter_year="  j_tech_per in (select p_id from parm_periode ".
  "where p_exercice='".$user->get_exercice()."')";

$acc=new Fiche($cn);
$acc->get_by_qcode($_GET['ctl'],false);

if ( $acc->belong_ledger($_GET['j']) == -1 )
  return '{"saldo":"0"}';
$res=$acc->get_solde_detail($filter_year);


if ( empty($res) ) return '{"saldo":"0"}';
$solde=$res['solde'];
if ( $res['debit'] < $res['credit'] ) $solde=$solde*(-1);

//header("Content-type: text/html; charset: utf8",true);

echo '{"saldo":"'.$solde.'"}';



