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
 * \brief Search a operation from a ledger into a popup window, used for the "rapprochement"
 */

require_once ("ac_common.php");
require_once('class_database.php');
require_once("user_common.php");
/* Admin. Dossier */
$rep=new Database();
include_once ("class_user.php");
require_once('class_html_input.php');
$User=new User($rep);
$User->Check();


html_min_page_start($User->theme,"onLoad='window.focus();'");

require_once('class_dossier.php');
$gDossier=dossier::id();

// Javascript
echo JS_LEDGER;
if ( isset( $p_jrn ))
{
    $p_jrn=$p_jrn;
    $_SESSION[ "p_jrn"]=$p_jrn;

}
if (isset ($_GET['p_ctl'])) $p_ctl=$_GET['p_ctl'];
if (isset($_POST['p_ctl'])) $p_ctl=$_POST['p_ctl'];
$opt='<OPTION VALUE="="> =';
$opt.='<OPTION VALUE="<="> <=';
$opt.='<OPTION VALUE="<"> <';
$opt.='<OPTION VALUE=">"> >';
$opt.='<OPTION VALUE=">="> >=';
$opt_date=$opt;
$opt_montant=$opt;
$c_comment="";
$c_montant="";
$c_internal="";
$c_date="";
$condition="";
$part=" where ";
$cn=new Database($gDossier);
// if search then build the condition
if ( isset ($_GET["search"]) )
{
    $c1=0;
    foreach( $_GET as $key=>$element)
    {
        ${"$key"}=$element;
    }
    $c_comment="";
    if ( isset ($p_comment) && strlen(trim($p_comment)) != 0 )
    {
        $c_comment=" $part upper(jr_comment) like upper('%$p_comment%')";
        $part=" and ";
    }
    $c_montant="";
    if ( isset ($p_montant) && strlen($p_montant) != 0 && isNumber($p_montant) )
    {
        $p_montant=abs($p_montant);
        $opt_montant.='<OPTION VALUE="'.$p_montant_sel.'" SELECTED>'.$p_montant_sel;
        $part="  and ";
        /* if the sign is equal then we look into the details */
        if ( $p_montant_sel != '=' )
        {
            $c_montant=sprintf(" $part jr_montant %s abs(%s)",$p_montant_sel,$p_montant);
        }
        else
        {
            $c_montant=$part.'  jr_grpt_id in (select j_grpt from jrnx where j_montant = '.$p_montant.')';
        }
    }
    if ( isset ($p_date) && strlen(trim($p_date)) != 0 )
    {
        $c_date=sprintf(" $part j_date %s to_date('%s','DD.MM.YYYY')",$p_date_sel,$p_date);
        $part=" and ";
        $opt_date.='<OPTION VALUE="'.$p_date_sel.'" SELECTED>'.$p_date_sel;
    }
    $c_internal="";
    if ( isset($p_internal) &&  strlen(trim($p_internal)) != 0 )
    {
        $c_internal=$part." jr_internal like  ('%".$p_internal."%')";
        $part=" and ";

    }
    $c_paid="";
    if (isset($paid))
    {
        $c_paid=$part."  (jr_rapt != 'paid' or jr_rapt is null) ";
        $part=" and ";
    }
    $condition=$c_comment.$c_montant.$c_date.$c_internal.$c_paid;
}
$condition=$condition." ".$part;

// If the usr is admin he has all right
if ( $User->admin != 1 && $User->is_local_admin()!=1)
{
    $condition.="  uj_priv in ('W','R') and uj_login='".$User->login."'" ;
}
else
{
    $condition.=" true ";
}
?>
<div style="font-size:11px;">
           <?php
           echo '<FORM ACTION="jrn_search.php" METHOD="GET">';
echo dossier::hidden();
if (isset($paid))
    echo '<div class="info"> Uniquement les non op&eacute;rations non pay&eacute;es<input type="hidden" name="paid" value="paid"></div>';
else
    echo '<div class="info"> Toutes les op&eacute;rations </div>';

echo '<TABLE>';
echo '<TR>';
if ( ! isset ($p_date)) $p_date="";
if ( ! isset ($p_montant)) $p_montant="";
if ( ! isset ($p_comment)) $p_comment="";
if ( ! isset ($p_internal)) $p_internal="";
echo '<input type="hidden" name="p_ctl" value="'.$p_ctl.'">';
echo '<TD> Date </TD>';
echo '<TD> <SELECT NAME="p_date_sel">'.$opt_date.' </TD>';
echo '<TD> <INPUT TYPE="text" name="p_date" VALUE="'.$p_date.'"></TD>';

echo '<TD> Montant </TD>';
echo '<TD> <SELECT NAME="p_montant_sel">'.$opt_montant.' </TD>';
echo '<TD> <INPUT TYPE="text" name="p_montant" VALUE="'.$p_montant.'"></TD>';
echo '</TR><TR>';
echo '<TD> Commentaire </TD>';
echo '<TD> contient </TD>';
echo '<TD> <INPUT TYPE="text" name="p_comment" VALUE="'.$p_comment.'"></TD>';
?>
</TR><TR>
<TD> Code interne </TD><TD>comme </TD>
<?php
echo '<TD> <INPUT TYPE="text" name="p_internal" VALUE="'.$p_internal.'"></TD>';
echo "</TR>";

echo '</TABLE>';
echo HtmlInput::submit('search','cherche');
echo '<input type="button" class="button" name="update_concerned" value="Mise à jour des réconciliation" onClick="updateJrn(\''.$p_ctl.'\')">';
echo '</FORM>';
echo '<div class="content">';
// if a search is asked otherwise don't show all the rows
if ( isset ($_GET["search"]) )
{
// If the usr is admin he has all right
    if ( $User->admin != 1 && $User->is_local_admin()!=1)
    {
        $jnt="  inner join user_sec_jrn on uj_jrn_id=j_jrn_def";
    }
    else
    {
        $jnt="  ";
    }
    $sql="select j_id,to_char(j_date,'DD.MM.YYYY') as j_date,
         j_montant,jr_montant,j_poste,j_debit,j_tech_per,jr_id,jr_comment,j_grpt,pcm_lib,jr_internal,jr_rapt,j_qcode from jrnx inner join
         jrn on jr_grpt_id=j_grpt inner join tmp_pcmn on j_poste=pcm_val ".
         $jnt.
         $condition." order by jr_date,jr_id,j_debit desc";
    $Res=$cn->exec_sql($sql);

    $MaxLine=Database::num_row($Res);
    $offset=(isset($_GET['offset']))?$_GET['offset']:0;
    $limit=$_SESSION['g_pagesize'];
    $sql_limit="";
    $sql_offset="";
    $bar="";
    if ( $limit != -1)
    {
        $page=(isset($_GET['page']))?$_GET['page']:0;
        $sql_limit=" LIMIT $limit ";
        $sql_offset=" OFFSET $offset ";
        $bar=jrn_navigation_bar($offset,$MaxLine,$limit,$page,'onClick="return go_next_concerned();"');

    }
    $sql.=$sql_limit.$sql_offset;
    if ( $MaxLine==0)
    {
        html_page_stop();
        return;
    }
    $Res=$cn->exec_sql($sql);
    $MaxLine=Database::num_row($Res);

    $col_vide="<TD></TD>";
    echo '<form id="form_jrn_concerned">';
    echo HtmlInput::hidden('nb_item',$MaxLine);
    echo $bar;
    echo '<TABLE ALIGN="center" BORDER="0" CELLSPACING="O" width="100%">';
    $l_id="";
//   if ( $MaxLine > 250 ) {
//     echo "Trop de lignes redéfinir la recherche";
//     html_page_stop();
//     return;
//   }
    for ( $i=0; $i < $MaxLine; $i++)
    {
        $l_line=Database::fetch_array($Res,$i);
        if ( $l_id == $l_line['j_grpt'] )
        {
            echo $col_vide.$col_vide;
        }
        else
        {
            echo '<TR style="background-color:lightblue"><TD>';
            echo '<INPUT TYPE="CHECKBOX" name="jr_concerned'.$l_line['jr_id'].'" ID="jr_concerned'.$l_line['jr_id'].'"> '.$l_line['jr_id'];
            echo "</TD>";

            echo "<TD>";
            echo $l_line['j_date'];
            echo "</TD>";

            echo "<TD>";
            echo $l_line['jr_internal'];
            echo "</TD>";
            $l_id=$l_line['j_grpt'];
            if ( $l_line['jr_rapt'] == 'paid')
                $lpay="  (Pay&eacute;) ";
            else
                $lpay="";

            echo '<TD COLSPAN="3">'.$l_line['jr_comment'].$lpay.'</TD>';
            echo '<td>'.$l_line['jr_montant'].'</td>';
            echo '</tr>';
        }
        if ( $l_line['j_debit'] == 't' )
        {
            echo '<TR style="background-color:#E7FBFF;">';
        }
        else
        {
            echo '<TR style="background-color:#E7FFEB;">';
        }
        echo $col_vide;
        if ( $l_line['j_debit']=='f')
            echo $col_vide;

        echo '<TD>';
        echo $l_line['j_poste'];
        echo '</TD>';

        if ( $l_line['j_debit']=='t')
            echo $col_vide;

        echo '<TD>';
        if ( $l_line ['j_qcode'] != "" )
        {
            $o=new Fiche($cn);
            $o->get_by_qcode($l_line['j_qcode'],false);
            echo "[ ".$l_line['j_qcode']." ]".
            $o->strAttribut(ATTR_DEF_NAME);
        }
        else
            echo $l_line['pcm_lib'];
        echo '</TD>';

        if ( $l_line['j_debit']=='f')
            echo $col_vide;

        echo '<TD>';
        echo $l_line['j_montant'];
        echo '</TD>';

        echo "</TR>";

    }

    echo '</TABLE>';
    echo $bar;
    echo '</form>';
    echo '</div>';
}// if $_POST [search]
?>
</div>
<?php
html_page_stop();
?>
