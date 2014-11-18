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
 * \brief concerns the management of the "Plan Comptable"
 */
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
require_once ('class_acc_account.php');
include_once ("ac_common.php");
require_once("constant.php");
require_once('class_dossier.php');
require_once('function_javascript.php');

$gDossier=dossier::id();

require_once('class_database.php');

/* Admin. Dossier */
$cn=new Database($gDossier);

include_once ("class_user.php");

include_once ("user_menu.php");

echo '<div id="acc_update" class="inner_box" style="display:none;position:absolute;text-align:left;z-index:1">';
echo HtmlInput::title_box("Poste comptable", "acc_update", "hide");
echo '<span id="acc_update_info" class="notice"></span>';
echo '<form method="post" id="acc_update_frm_id" onsubmit="account_update(\'acc_update_frm_id\');return false;">';
$val=new IText('p_valu');
$parent=new IText('p_parentu');
$lib=new IText('p_libu');
$lib->css_size="100%";
$type=new ISelect('p_typeu');
$type->value=Acc_Account::$type;
echo '<table>';
$r= td(_('Poste comptable')).td($val->input());
echo tr($r);
$r= td(_('Description')).td($lib->input());
echo tr($r);
$r= td(_('Parent')).td($parent->input());
echo tr($r);
$r= td(_('Type ')).td($type->input());
echo tr($r);
echo '</table>';
echo HtmlInput::hidden('p_oldu','');
echo HtmlInput::hidden('p_action','');
echo dossier::hidden();
$checkbox=new ICheckBox("delete_acc");
echo _('Cocher pour effacer')." ".$checkbox->input();
echo '<hr>';
echo HtmlInput::submit('update',_('Sauve'));
echo HtmlInput::button('hide',_('Annuler'),'onClick="$(\'acc_update\').hide();return true;"');
echo '</form>';
echo '</div>';



/* Store the p_start parameter */

$g_start=HtmlInput::default_value_get('p_start',-1);
echo '<div class="u_subtmenu">';

echo '</div>';


echo '<div class="content">';
menu_acc_plan($g_start);
echo '</div>';
if ($g_start == -1) return;
echo '<DIV CLASS="myfieldset" style="width:auto">';

$Ret=$cn->exec_sql("select pcm_val,pcm_lib,pcm_val_parent,pcm_type,array_to_string(array_agg(j_qcode) , ',') as acode
	from tmp_pcmn left join vw_poste_qcode on (j_poste=pcm_val) where substr(pcm_val::text,1,1)='".$g_start."'".
		"  group by pcm_val,pcm_lib,pcm_val_parent, pcm_type  order by pcm_val::text");
$MaxRow=Database::num_row($Ret);

?>
<span style="display:inline;margin: 15px 15px 15px 15px">
<input type="button" class="smallbutton" onclick="PcmnUpdate('','','','',0,0,'new')" value="<?php echo _('Ajout poste comptable'); ?>">
</span>
<?php echo _('Filtre')." ".HtmlInput::filter_table("account_tbl_id", "0,1,2,3,4", 1);?>
<FORM METHOD="POST">
             <?php
             echo HtmlInput::hidden('p_action','pcmn');
//echo HtmlInput::hidden('sa','detail');
echo dossier::hidden();
$limite=MAX_QCODE;
?>
<TABLE  id="account_tbl_id" class="result">
                             <TR>
                             <TH> Poste comptable </TH>
                             <TH> Libellé </TH>
                             <TH> Poste comptable Parent </TH>
                             <TH> Type </TH>
                             <TH> Fiche</TH>
                             </TR>

                                       <?php
                                       $str_dossier=dossier::get();
for ($i=0; $i <$MaxRow; $i++)
{
    $A=Database::fetch_array($Ret,$i);

    if ( $i%2 == 0 )
    {
        $td ='<TD class="odd">';
        $tr ='<TR class="odd">';
    }
    else
    {
        $td='<TD class="even">';
        $tr='<TR class="even">';
    }
    echo $tr;
    echo "$td";
    echo HtmlInput::history_account($A['pcm_val'], $A['pcm_val']);
    echo '</td>';
    echo "$td";
    printf ("<A style=\"text-decoration:underline\" HREF=\"javascript:void(0)\" onclick=\"PcmnUpdate('%s','%s','%s','%s',%d,0,'update')\">",
            str_replace("'","\'",$A['pcm_val']),
            str_replace("'","\'",$A['pcm_lib']),
            str_replace("'","\'",$A['pcm_val_parent']),
            str_replace("'","\'",$A['pcm_type']),
            dossier::id());
    echo h($A['pcm_lib']);

    echo $td;
    echo $A['pcm_val_parent'];
    echo '</TD>';
    echo "</td>$td";
    echo $A['pcm_type'];
    echo "</TD>";

	echo $td;
	if ( strlen($A['acode']) >0 ) {
		if (strpos($A['acode'], ",") >0 ) {
			$det_qcode=  explode(",", $A['acode']);
			echo '<ul style="display:inline;paddding:0;margin:0px;padding-left:0px;list-style-type:none;padding-start-value:0px">';
			$max=(count($det_qcode)>MAX_QCODE)?MAX_QCODE:count($det_qcode);
			for ($e=0;$e<$max;$e++) {
				echo '<li style="padding-start-value:0;display:inline">'.HtmlInput::card_detail($det_qcode[$e],'',' style="display:inline"').'</li>';
			}
			echo '</ol>';
			if ($max < count($det_qcode)) {
				echo "...";
			}
		} else {
			echo HtmlInput::card_detail($A['acode']);
		}
	}
	echo '</td>';

    echo "</TR>";
}
echo "</TABLE>";
echo "</FORM>";
?>
                             <input type="button" class="smallbutton" onclick="PcmnUpdate('','','','',0,-230,'new')" value="<?php echo _('Ajout poste comptable'); ?>">
<?php
echo "</DIV>";
html_page_stop();
?>
