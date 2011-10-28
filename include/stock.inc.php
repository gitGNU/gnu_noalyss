<?php
/*
 *   This file is part of PHPCOMPTA.
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
 *   along with PHPCOMPTA; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
// Auteur Dany De Bontridder ddebontridder@yahoo.fr

/* $Revision$ */

/*!\file
 * \brief Manage the stock by year
 */
require_once('class_dossier.php');
include_once ("ac_common.php");
require_once('class_database.php');
include_once("stock_inc.php");
require_once('class_dossier.php');
require_once('class_periode.php');
$gDossier=dossier::id();

html_page_start($_SESSION['g_theme']);

require_once('class_database.php');

$cn=new Database($gDossier);
include_once ("class_user.php");
global $g_user;

$href=basename($_SERVER['PHP_SELF']);

// Get The priv on the selected folder
$g_user->can_request(STOLE,1);


$action= ( isset ($_GET['action']))? $_GET['action']:"";
include_once("stock_inc.php");

// Adjust the stock
if ( isset ($_POST['sub_change']))
{
    $g_user->can_request(STOWRITE,1);
    $change=$_POST['stock_change'];
    $sg_code=$_POST['sg_code'];
    $sg_date=$_POST['sg_date'];
    $year=$_POST['year'];
    $comment=$_POST['comment'];

    if ( isDate($sg_date) == null
            or isNumber($change) == 0
            or isNumber($year) == 0 )
    {
        $msg="Stock données non conformes";
        alert('$msg');
        echo_error($msg);
    }
    else
    {
        // Check if User Can change the stock
        if ( $g_user->check_action($gDossier,GESTOCK) == 0 )
        {
            NoAccess();
            exit (-1);
        }

        // if neg the stock decrease => credit
        $type=( $change < 0 )?'c':'d';
        if ( $change != 0)
        {
            $comment=FormatString($comment);
            $Res=$cn->exec_sql("insert into stock_goods
                               (  j_id,
                               f_id,
                               sg_code,
                               sg_quantity,
                               sg_type,
                               sg_date,
                               sg_exercice,
                               sg_comment,
                               sg_tech_user)
                               values (
                               null,
                               null,
                               '$sg_code',
                               abs($change),
                               '$type',
                               to_date('$sg_date','DD.MM.YYYY'),
                               '$year',
                               '$comment',
                               '".$_SESSION['g_user']."');
                               ");
        }
        // to update the view
        $action="detail";
    }
}

// View the summary

// if year is not set then use the year of the user's periode
if ( ! isset ($_GET['year']) )
{
    // get defaut periode
    $a=$g_user->get_periode();
    // get exercice of periode
    $periode=new Periode($cn,$a);
    $year=$periode->get_exercice();

}
else
{
    $year=$_GET['year'];
}

// View details
if ( $action == 'detail' )
{
    // Check if User Can see the stock
    $g_user->can_request(STOLE,1);
    $sg_code=(isset ($_GET['sg_code'] ))?$_GET['sg_code']:$_POST['sg_code'];
    $year=(isset($_GET['year']))?$_GET['year']:$_POST['year'];
    $a=ViewDetailStock($cn,$sg_code,$year);
    $write=$g_user->check_action(STOWRITE);

    $b="";


    echo '<div class="u_redcontent" style="margin-left:10%">' ;
    echo $a;
    echo '<div style="float:left;clear:both">';

    if ( $write != 0)
    {
        echo '<fieldset><legend>';
        echo 'Entrer la valeur qui doit augmenter ou diminuer le stock';
        echo '</legend>';
        echo '<form action="?ac='.$_GET['ac'].'" method="POST">';
        echo ChangeStock($sg_code,$year);
        echo HtmlInput::submit("sub_change" ,"Valider");
        echo dossier::hidden();
        echo HtmlInput::button_anchor('Retour','?ac='.$_REQUEST['ac'].'&'.dossier::get());
        echo '</form>';
        echo '</fieldset>';
    }
    else
        echo HtmlInput::button_anchor('Retour','?ac='.$_REQUEST['ac'].'&'.dossier::get());
    echo '</div>';




    echo '</div>';
    exit();
}

// Show the possible years
$sql="select distinct (p_exercice) as exercice from parm_periode ";
$Res=$cn->exec_sql($sql);
$r="";
for ( $i = 0; $i < Database::num_row($Res);$i++)
{
    $l=Database::fetch_array($Res,$i);
    $url=sprintf("?ac=".$_REQUEST['ac']."&year=%d&".dossier::get(),
                     $l['exercice']);
    $r.=HtmlInput::button_anchor($l['exercice'],$url);
}
// Check if User Can see the stock


// Show the current stock
echo '<div class="u_redcontent">';
echo $r;
$a=ViewStock($cn,$year);
if ( $a != null )
{
    echo $a;
}
echo '</div>';
html_page_stop();
?>
