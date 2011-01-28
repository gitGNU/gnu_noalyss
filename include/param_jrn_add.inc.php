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
/* $Revision$ */
/*! \file
 * \brief To add a ledger
 */

include_once ("ac_common.php");
require_once("class_itext.php");
require_once("class_iselect.php");
require_once ('class_acc_ledger.php');

/* ipopup for search poste */
echo IPoste::ipopup('ipop_account');
/* search card */
$search_card=new IPopup('ipop_card');
$search_card->title=_('Recherche de fiche');
echo $search_card->input();

html_page_start($_SESSION['g_theme']);
require_once('class_dossier.php');
$gDossier=dossier::id();

require_once('class_database.php');
/* Admin. Dossier */

include_once ("class_user.php");
$cn=new Database($gDossier);
$User=new User($cn);
$User->Check();
$User->check_dossier($gDossier);
include_once ("user_menu.php");

$User->can_request(PARJRN);

if ( isset ($_POST["add"]) )
{
    if (  !isset($_POST["p_jrn_name"]) || ! isset($_POST["p_jrn_type"] ))
    {
        echo '<H2 CLASS="error">'._('Un paramètre manque').'</H2>';
    }
    else
    {
        /*  if name already use we stop */
        if ( $cn->count_sql("select * from jrn_def where upper(jrn_def_name)=upper($1)",array($_POST['p_jrn_name'])) >0 )
        {

            alert (_('Un journal de ce nom existe déjà'));
        }
        else
        {

            if (strlen(trim($_POST['p_jrn_deb_max_line'])) == 0 ||
                    (string) (int)$_POST['p_jrn_deb_max_line'] != (string)$_POST['p_jrn_deb_max_line'] )
                $l_deb_max_line=1;
            else
                $l_deb_max_line=$_POST['p_jrn_deb_max_line'];


            $p_jrn_name=$_POST["p_jrn_name"];
            $p_jrn_class_deb=FormatString($_POST["p_jrn_class_deb"]);
            if (strlen(trim($p_jrn_name))==0) return;
            // compute the jrn_def.jrn_def_code
            $p_code=sprintf("%s%02d",trim(substr($_POST['p_jrn_type'],0,1)),Acc_Ledger::next_number($cn,$_POST['p_jrn_type']));
            $p_jrn_fiche_deb="";
            $p_jrn_fiche_cred="";

            if ( isset    ($_POST["FICHEDEB"]))
            {
                $p_jrn_fiche_deb=join(",",$_POST["FICHEDEB"]);
            }
            if ( isset    ($_POST["FICHECRED"]))
            {
                $p_jrn_fiche_cred=join(",",$_POST["FICHECRED"]);
            }
            $l_cred_max_line=$l_deb_max_line;
            $cn->start();
            $bank=null;
            if (isset($_POST['bank']))
            {
                $a=new Fiche($cn);
                $a->get_by_qcode(trim(strtoupper($_POST['bank'])),false);
                $bank=$a->id;
                if ($bank==0) $bank=null;
            }
            $nop=0;
            if (isset($_POST['numb_operation']))
            {
                $nop=1;
            }

            if ($_POST['p_jrn_type']=='FIN' && $bank==null)
            {
                alert("Vous devez donner un compte en banque");
            }
            else
            {

                $Sql="insert into jrn_def(jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_deb_max_line,jrn_cred_max_line,
                     jrn_def_type,jrn_def_fiche_deb,jrn_def_fiche_cred,jrn_def_code,jrn_def_pj_pref,jrn_def_bank,jrn_def_num_op)
                     values ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12)";
                $sql_array=array(
                               $p_jrn_name,$p_jrn_class_deb,$p_jrn_class_deb,
                               $l_deb_max_line,$l_cred_max_line,$_POST['p_jrn_type'],
                               $p_jrn_fiche_deb,$p_jrn_fiche_cred,
                               $p_code,$_POST['jrn_def_pj_pref'],
                               $bank,$nop);
                $Res=$cn->exec_sql($Sql,$sql_array);
                $ledger_id=$cn->get_current_seq('s_jrn_def');
                $Res=$cn->create_sequence('s_jrn_pj'.$ledger_id);
                $cn->commit();
            }
        }
    }
    echo '<div class="lmenu">';
    MenuJrn();
    echo '</div>';
    exit();
}

echo '<div class="lmenu">';
MenuJrn();
echo '</div>';



echo '<DIV CLASS="u_redcontent">';
/* widget for searching an account */
$wSearch=new IPoste();
$wSearch->table=3;
$wSearch->set_attribute('ipopup','ipop_account');
$wSearch->set_attribute('account','p_jrn_class_deb');
$wSearch->set_attribute('no_overwrite','1');
$wSearch->set_attribute('noquery','1');

$wSearch->name="p_jrn_class_deb";
$wSearch->size=20;

$search=$wSearch->input();

/* construct all the hidden */
$hidden= HtmlInput::hidden('p_jrn',0);
$hidden.= HtmlInput::hidden('p_action','jrn');
$hidden.= HtmlInput::hidden('sa','add');
$hidden.= dossier::hidden();
$hidden.=HtmlInput::hidden('p_jrn_deb_max_line',10);
$hidden.=HtmlInput::hidden('p_ech_lib','echeance');

/* properties of the ledger */
$name="";
$code="";
$wType=new ISelect();
$wType->value=$cn->make_array('select jrn_type_id,jrn_desc from jrn_type');
$wType->name="p_jrn_type";
$type=$wType->input();
$rcred=$rdeb=array();
$wPjPref=new IText();
$wPjPref->name='jrn_def_pj_pref';
$pj_pref=$wPjPref->input();
$pj_seq='';
$last_seq=0;
$new=true;
/* bank card */
$qcode_bank='';
/* Numbering (only FIN) */
$num_op=new ICheckBox('numb_operation');


echo '<FORM METHOD="POST">';
echo dossier::hidden();
echo HtmlInput::hidden('p_action','jrn');
echo HtmlInput::hidden('p_jrn',-1);
echo HtmlInput::hidden('sa','add');
require_once('template/param_jrn.php');
echo HtmlInput::submit('add','Sauver');
echo '<INPUT TYPE="RESET" class="button" VALUE="Reset">';
echo '</FORM>';
echo "</DIV>";
html_page_stop();
?>
