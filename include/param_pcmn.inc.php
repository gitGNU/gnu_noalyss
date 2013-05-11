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
 * \brief concerns the management of the "Plan Comptable"
 */
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

echo '<div id="acc_update" style="border:1px solid blue;width:40%;display:none;background-color:lightgrey;padding:0;position:absolute;text-align:left;z-index:1">';
echo HtmlInput::title_box("Poste comptable", "acc_update", "hide");
echo '<form method="post">';
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
echo dossier::hidden();
echo HtmlInput::submit('update',_('Sauve'));
echo HtmlInput::button('hide',_('Annuler'),'onClick="$(\'acc_update\').hide();return true;"');
echo '</form>';
echo '</div>';



/* Store the p_start parameter */
if ( ! isset ( $_SESSION['g_start']) )
{
    $_SESSION['g_start']="";

}
if ( isset ($_GET['p_start']))
{
    $g_start=$_GET['p_start'];
    $_SESSION["g_start"]=$g_start;

}

echo '<div class="u_subtmenu">';

echo '</div>';


echo '<div class="lmenu">';
menu_acc_plan($_SESSION['g_start']);
echo '</div>';
echo '<DIV CLASS="redcontent">';
/* Analyse ce qui est demandé */
/* Effacement d'une ligne */
if (isset ($_GET['action']))
{
//-----------------------------------------------------
// Action == remove a line
    if ( $_GET['action']=="del" )
    {
        if ( isset ($_GET['l']) )
        {
            /* Ligne a enfant*/
            $R=$cn->exec_sql("select pcm_val from tmp_pcmn where pcm_val_parent=$1",array($_GET['l']));
            if ( Database::num_row($R) != 0 )
            {
                alert("Ne peut pas effacer le poste: d'autres postes en dépendent");
            }
            else
            {
                /* Vérifier que le poste n'est pas utilisé qq part dans les journaux */
                $Res=$cn->exec_sql("select * from jrnx where j_poste=$1",array($_GET['l']));
                if ( Database::num_row($Res) != 0 )
                {
                    alert("Ne peut pas effacer le poste: il est utilisé dans les journaux");
                }
                else
                {
                    $Del=$cn->exec_sql("delete from tmp_pcmn where pcm_val=$1",array($_GET['l']));
                } // if Database::num_row
            } // if Database::num_row
        } // isset ($l)
    } //$action == del
} // isset action
//----------------------------------------------------------------------
// Modification
//----------------------------------------------------------------------
if ( isset ($_POST['update']))
{
    $p_val=trim($_POST["p_valu"]);
    $p_lib=trim($_POST["p_libu"]);
    $p_parent=trim($_POST["p_parentu"]);
    $old_line=trim($_POST["p_oldu"]);
    $p_type=htmlentities($_POST['p_typeu']);
    $acc=new Acc_Account($cn);
    $acc->set_parameter('libelle',$p_lib);
    $acc->set_parameter('value',$p_val);
    $acc->set_parameter('parent',$p_parent);
    $acc->set_parameter('type',$p_type);
    // Check if the data are correct
    try
    {
        $acc->check() ;
    }
    catch (Exception $e)
    {
        $message="Valeurs invalides, pas de changement \n ".
                 $e->getMessage();
        echo '<script> alert(\''.$message.'\');
        </script>';
    }
    if ( strlen ($p_val) != 0 && strlen ($p_lib) != 0 && strlen($old_line)!=0 )
    {
        if (strlen ($p_val) == 1 )
        {
            $p_parent=0;
        }
        else
        {
            if ( strlen($p_parent)==0 )
            {
                $p_parent=substr($p_val,0,strlen($p_val)-1);
            }
        }
        /* Parent existe */
        $Ret=$cn->exec_sql("select pcm_val from tmp_pcmn where pcm_val=$1",array($p_parent));
        if ( ($p_parent != 0 && Database::num_row($Ret) == 0) || $p_parent==$old_line )
        {
            echo '<SCRIPT> alert(" Ne peut pas modifier; aucun poste parent"); </SCRIPT>';
        }
        else
        {
            try
            {
                $acc->update($old_line);
            }
            catch(Exception $e)
            {
                alert($e->getMessage());
            }
        }
    }
    else
    {
        echo '<script> alert(\'Update Valeurs invalides\'); </script>';
    }
}
//-----------------------------------------------------
/* Ajout d'une ligne */
if ( isset ( $_POST["Ajout"] ) )
{
    extract ($_POST);
    $p_val=trim($p_val);
    $p_parent=trim($p_parent);

    if ( isset ( $p_val) && isset ( $p_lib )  )
    {
        $p_val=trim($p_val);
        $p_parent=$_POST["p_parent"];
        if ( strlen ($p_val) != 0 && strlen ($p_lib) != 0 )
        {
            if (strlen ($p_val) == 1 )
            {
                $p_parent=0;
            }
            else
            {
                if ( strlen(trim($p_parent))==0 &&
                        (string) $p_parent != (string)(int) $p_parent)
                {
                    $p_parent=substr($p_val,0,strlen($p_val)-1);
                }
            }
            /* Parent existe */
            $Ret=$cn->exec_sql("select pcm_val from tmp_pcmn where pcm_val=$1",array($p_parent));
            if ( $p_parent != 0 && Database::num_row($Ret) == 0 )
            {
                alert(" Ne peut pas modifier; aucun poste parent");
            }
            else
            {
                // Check if the account already exists

                $Count=$cn->get_value("select count(*) from tmp_pcmn where pcm_val=$1",array($p_val));
                if ( $Count != 0 )
                {
                    // Alert message account already exists
                    alert(" Ce poste existe déjà ");

                }
                else
                {
                    $Ret=$cn->exec_sql("insert into tmp_pcmn (pcm_val,pcm_lib,pcm_val_parent,pcm_type) values ($1,$2,$3,$4)",array($p_val,$p_lib,$p_parent,$p_type));
                }
            }
        }
        else
        {
            echo '<H2 class="error"> Valeurs invalides </H3>';
        }
    }
}

$Ret=$cn->exec_sql("select pcm_val,pcm_lib,pcm_val_parent,pcm_type,array_to_string(array_agg(j_qcode) , ',') as acode
	from tmp_pcmn left join vw_poste_qcode on (j_poste=pcm_val) where substr(pcm_val::text,1,1)='".$_SESSION['g_start']."'".
		"  group by pcm_val,pcm_lib,pcm_val_parent, pcm_type  order by pcm_val::text");
$MaxRow=Database::num_row($Ret);

?>

<FORM METHOD="POST">
             <?php
             echo HtmlInput::hidden('p_action','pcmn');
//echo HtmlInput::hidden('sa','detail');
echo dossier::hidden();
?>
<TABLE class="result">
                             <TR>
                             <TH> Poste comptable </TH>
                             <TH> Libellé </TH>
                             <TH> Poste comptable Parent </TH>
                             <TH> Type </TH>
                             <TH> Fiche</TH>
                             </TR>
<?php
$line=new Acc_Account($cn);
echo $line->form(false);
?>
							 <td></td>
<TD>
<INPUT TYPE="SUBMIT" class="button" Value="Ajout" Name="Ajout">
                                       </TD>
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
    echo $A['pcm_val'];
    echo '</td>';
    echo "$td";
    printf ("<A HREF=\"javascript:void(0)\" onclick=\"PcmnUpdate('%s','%s','%s','%s',%d)\">",
            $A['pcm_val'],
			str_replace("'","\'",$A['pcm_lib']),
            $A['pcm_val_parent'],
            $A['pcm_type'],
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
			$det_qcode=  split(",", $A['acode']);
			echo '<ul style="paddding:0;margin:0;padding-left:0;list-style-type:none;padding-start-value:0">';
			for ($e=0;$e<count($det_qcode);$e++) {
				echo '<li style="padding-start-value:0">'.HtmlInput::card_detail($det_qcode[$e]).'</li>';
			}
			echo '</ol>';
		} else {
			echo HtmlInput::card_detail($A['acode']);
		}
	}
	echo '</td>';

    echo $td;
    printf ('<A href="?ac='.$_REQUEST['ac'].'&l=%s&action=del&%s">Efface</A>',$A['pcm_val'],$str_dossier);
    echo "</TD>";


    echo "</TR>";
}
echo "</TABLE>";
echo "</FORM>";
echo "</DIV>";
html_page_stop();
?>
