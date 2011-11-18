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
 *
 * \brief show a screen to search a ca account
 *
 */

// parameter are gDossier , c1 : the control id to update,
// c2 the control id which contains the pa_id
require_once("class_itext.php");
require_once("class_ihidden.php");
require_once("class_ibutton.php");
require_once ('class_database.php');
require_once ("ac_common.php");
require_once ('class_dossier.php');
require_once ('class_anc_account.php');
require_once ('class_anc_plan.php');
require_once('function_javascript.php');

echo HtmlInput::title_box("Recherche activité", $ctl);

//------------- FORM ----------------------------------
echo '<FORM METHOD="GET" onsubmit="search_anc_form(this);return false">';
echo '<span>'._('Recherche').':';

$texte=new IText();
$texte->value=HtmlInput::default_value('label',"", $_GET);
echo $texte->input('label');
echo '</span>';
echo dossier::hidden();
$hid=new IHidden();
echo $hid->input("c1",$_REQUEST['c1']);
echo $hid->input("c2",$_REQUEST['c2']);
echo $hid->input("go");
echo HtmlInput::submit("go",_("Recherche"));
echo '</form>';
//------------- FORM ----------------------------------
if ( isset($_REQUEST['go']))
{
    $cn=Dossier::connect();
    $plan=new Anc_Plan($cn,$_REQUEST['c2']);
    $plan->pa_id=$_REQUEST['c2'];
    if ( $plan->exist()==false)
        exit("Ce plan n'existe pas");

    $sql="select po_name , po_description from poste_analytique ".
         "where pa_id=".$_REQUEST['c2']." and ".
         " upper (po_name) like upper('%".Database::escape_string($_REQUEST['label'])."%') order by po_name";
    $res=$cn->exec_sql($sql);
    $array=Database::fetch_all($res);
    if (empty($array) == true)
    {
        echo "D&eacute;sol&eacute; aucun poste trouv&eacute;";
        return;
    }
    $button=new IButton();
    $button->name="Choix";
    $button->label="Choix";

    echo '<table>';
    foreach ($array as $line)
    {
        $button->javascript=sprintf("$('%s').value='%s'",
                                    $_REQUEST['c1'],
                                    $line['po_name']);
        echo '<tr>'.
        '<td>'.
        $button->input().
        '</td>'.
        '<td>'.
        $line['po_name'].
        '</td><td>'.
        $line['po_description'].
        '</tr>';
    }
    echo '</table>';
}
