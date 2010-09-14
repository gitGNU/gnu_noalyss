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
 * \brief Verify the saldo of ledger: independant file
 */

require_once ('class_user.php');
require_once('class_acc_bilan.php');

$cn=new Database(dossier::id());
$User=new User($cn);
$exercice=$User->get_exercice();
echo '<div class="content">';
$User->db=$cn;
$sql_year=" and j_tech_per in (select p_id from parm_periode where p_exercice='".$User->get_exercice()."')";
echo '<fieldset><legend>Vérification des journaux</legend>';
echo '<ol>';
$deb=$cn->get_value("select sum (j_montant) from jrnx where j_debit='t' $sql_year ");
$cred=$cn->get_value("select sum (j_montant) from jrnx where j_debit='f' $sql_year ");

if ( $cred == $deb )
{
    $result ='<span style="color:green;font-size:120%;font-weight:bold;"> OK </span>';
}
else
{
    $result ='<span style="color:red;font-size:120%;font-weight:bold;"> NON OK </span>';
}

printf ('<li> Solde Grand Livre : debit %f credit %f %s</li>',$deb,$cred,$result);

$sql="select jrn_def_id,jrn_def_name from jrn_def";
$res=$cn->exec_sql($sql);
$jrn=Database::fetch_all($res);
foreach ($jrn as $l)
{
    $id=$l['jrn_def_id'];
    $name=$l['jrn_def_name'];
    $deb=$cn->get_value("select sum (j_montant) from jrnx where j_debit='t' and j_jrn_def=$id $sql_year ");
    $cred=$cn->get_value("select sum (j_montant) from jrnx where j_debit='f' and j_jrn_def=$id  $sql_year ");

    if ( $cred == $deb )
    {
        $result ='<span style="color:green;font-size:120%;font-weight:bold;"> OK </span>';
    }
    else
    {
        $result ='<span style="color:red;font-size:120%;font-weight:bold;"> NON OK </span>';
    }

    printf ('<li> Journal %s Solde   : debit %f credit %f %s</li>',$name,$deb,$cred,$result);

}
echo '</ol>';
echo '<ol>';
$sql_year=" and j_tech_per in (select p_id from parm_periode where p_exercice='".$exercice."')";

$deb=$cn->get_value("select sum (j_montant) from jrnx where j_debit='t' $sql_year ");
$cred=$cn->get_value("select sum (j_montant) from jrnx where j_debit='f' $sql_year ");

if ( $cred == $deb )
{
    $result ='<span style="color:green;font-size:120%;font-weight:bold;"> OK </span>';
}
else
{
    $result ='<span style="color:red;font-size:120%;font-weight:bold;"> NON OK </span>';
}

printf ('<li> Total solde Grand Livre : debit %f credit %f %s</li>',$deb,$cred,$result);
$sql="select jrn_def_id,jrn_def_name from jrn_def";
$res=$cn->exec_sql($sql);
$jrn=Database::fetch_all($res);
foreach ($jrn as $l)
{
    $id=$l['jrn_def_id'];
    $name=$l['jrn_def_name'];
    $deb=$cn->get_value("select sum (j_montant) from jrnx where j_debit='t' and j_jrn_def=$id $sql_year ");
    $cred=$cn->get_value("select sum (j_montant) from jrnx where j_debit='f' and j_jrn_def=$id  $sql_year ");

    if ( $cred == $deb )
    {
        $result ='<span style="color:green;font-size:120%;font-weight:bold;"> OK </span>';
    }
    else
    {
        $result ='<span style="color:red;font-size:120%;font-weight:bold;"> NON OK </span>';
    }

    printf ('<li> Journal %s total : debit %f credit %f %s</li>',$name,$deb,$cred,$result);

}
echo '</fieldset>';
echo '<fieldset><legend>Vérification des comptes</legend>';
$bilan=new Acc_Bilan($cn);
$periode=new Periode($cn);
list ($start_periode,$end_periode)=$periode->get_limit($exercice);
$bilan->from=$start_periode->p_id;
$bilan->to=$end_periode->p_id;
$bilan->verify();
echo '</fieldset>';
echo '</div>';

?>
