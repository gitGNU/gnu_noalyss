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
 * \brief included file for managing the predefined operation
 */
require_once("class_iselect.php");
require_once("class_icheckbox.php");
require_once("class_ihidden.php");
require_once('class_database.php');
require_once('ac_common.php');
require_once('class_pre_operation.php');
$user=new User(new Database(dossier::id()));
$user->can_request(PARPREDE,1);
echo '<div class="content">';
echo '<form method="GET">';
$sel=new ISelect();
$sel->name="jrn";
$sel->value=$cn->make_array("select jrn_def_id,jrn_def_name from ".
                            " jrn_def order by jrn_def_name");
// Show a list of ledger
$sa=(isset($_REQUEST['sa']))?$_REQUEST['sa']:"";
$sel->selected=(isset($_REQUEST['jrn']))?$_REQUEST['jrn']:-1;
echo 'Choississez un journal '.$sel->input();

echo dossier::hidden();
$hid=new IHidden();
echo $hid->input("sa","jrn");
echo $hid->input("p_action","preod");
echo '<hr>';
echo HtmlInput::submit('Accepter','Accepter');
echo '</form>';

// if $_REQUEST[sa] == del delete the predefined operation
if ( $sa == 'del')
{
    $op=new Pre_operation($cn);
    $op->od_id=$_REQUEST['od_id'];
    $op->delete();
    $sa='jrn';
}

// if $_REQUEST[sa] == jrn show the  predefined operation for this
// ledger
if ( $sa == 'jrn' )
{
    $op=new Pre_operation($cn);
    $op->set_jrn($_GET['jrn']);
    if ( isset($_GET['direct']))
    {
        $op->od_direct='true';
    }
    else
    {
        $op->od_direct='false';
    }
    $array=$op->get_list_ledger();
    if ( empty($array) == true )
    {
        echo "Aucun enregistrement";
        exit();
    }

    echo '<table>';
    $count=0;
    foreach ($array as $row )
    {

        if ( $count %2 == 0 )
            echo '<tr class="even">';
        else
            echo '<tr>';
        echo '<td>'.h($row['od_name']).'</td>';
        echo '<td>';
        echo '<form method="POST">';
        echo dossier::hidden();
        echo $hid->input("sa","del");
        echo $hid->input("p_action","preod");
        echo $hid->input("del","");
        echo $hid->input("od_id",$row['od_id']);
        echo $hid->input("jrn",$_GET['jrn']);

        $b='<input type="submit" class="button" value="Effacer" '.
           ' onClick="return confirm(\'Voulez-vous vraiment effacer cette operation ?\');" >';
        echo $b;
        echo '</form>';

        echo '</td>';
        echo '</tr>';

    }
    echo '</table>';
}
echo '</div>';
?>
