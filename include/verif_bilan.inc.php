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

/*!\file
 * \brief Verify the saldo of ledger: independant file
 */
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');

require_once ('class_user.php');
require_once('class_acc_bilan.php');

global $g_captcha,$g_failed,$g_succeed;

$cn=new Database(dossier::id());
$exercice=$g_user->get_exercice();
echo '<div class="content">';

$sql_year=" and j_tech_per in (select p_id from parm_periode where p_exercice='".$g_user->get_exercice()."')";
echo '<fieldset><legend>'._('Vérification des journaux').'</legend>';
echo '<ol>';
$deb=$cn->get_value("select sum (j_montant) from jrnx where j_debit='t' $sql_year ");
$cred=$cn->get_value("select sum (j_montant) from jrnx where j_debit='f' $sql_year ");

if ( $cred == $deb )
{
    $result =$g_succeed;
}
else
{
    $result = $g_failed;
}

printf ('<li>'._("Solde Grand Livre : debit %f credit %f %s").'</li>',$deb,$cred,$result);

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
    $result =$g_succeed;
}
else
{
    $result = $g_failed;
}

    printf ('<li>'._("Journal %s Solde   : debit %f credit %f %s").'</li>',$name,$deb,$cred,$result);

}
echo '</ol>';

echo '</fieldset>';
echo '<fieldset><legend>'._('Vérification des comptes').'</legend>';
$bilan=new Acc_Bilan($cn);
$periode=new Periode($cn);
list ($start_periode,$end_periode)=$periode->get_limit($exercice);
$bilan->from=$start_periode->p_id;
$bilan->to=$end_periode->p_id;
$bilan->verify();
echo '</fieldset>';
echo '</div>';
?>
<hr>
<fieldset>
    <legend>
        <?php echo _("Vérification fiche").'</legend>';?>
    </legend>
    <h2>
        <?php echo _('Fiche avec plusieurs postes comptables');?>
    </h2>
    <?php
    $sql_year_target=" target.j_tech_per in (select p_id from parm_periode where p_exercice='".$g_user->get_exercice()."')";
    $sql_year_source=" source.j_tech_per in (select p_id from parm_periode where p_exercice='".$g_user->get_exercice()."')";

    $sql_qcode="select distinct source.f_id,source.j_qcode 
            from jrnx as source ,jrnx as target 
            where
            source.j_id < target.j_id 
            and source.j_poste<>target.j_poste 
            and source.j_qcode = target.j_qcode
            and $sql_year_source and $sql_year_target
           ";
    $sql_poste="select distinct j_poste,pcm_lib from jrnx join tmp_pcmn on (pcm_val=j_poste) where j_qcode =$1 $sql_year";
    $a_qcode=$cn->get_array($sql_qcode);
    $res=$cn->prepare('get_poste',$sql_poste);
    ?>
    <ol>
    <?php
    for ($i=0;$i<count($a_qcode);$i++):
        $poste=$cn->execute('get_poste',array($a_qcode[$i]['j_qcode']));
    ?>
        <li><?php 
                echo HtmlInput::card_detail($a_qcode[$i]["j_qcode"],$a_qcode[$i]["j_qcode"],' style="display:inline"') ;
                echo " ";
                echo HtmlInput::history_card($a_qcode[$i]["f_id"],_("Hist."),' display:inline'); 
                        
                ?>
        
        </li>
        <ul>
        <?php $all_dep=Database::fetch_all($poste); 
        for ($e=0;$e<count($all_dep);$e++):
        ?>
            <li>
                <?php echo HtmlInput::history_account($all_dep[$e]['j_poste'],$all_dep[$e]['j_poste'],' display:inline ')?>
                <?php echo h($all_dep[$e]['pcm_lib'])?>
            </li>
        <?php
        endfor;
        ?>
        </ul>
    <?php
    endfor;
    ?>
    </ol>
</fieldset>
