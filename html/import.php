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
// Author Olivier Dzwoniarkiewicz
/*! \file
 * \brief for importing Bank operations
 */

include_once("ac_common.php");
include_once("user_menu.php");
include_once ("constant.php");
require_once('class_database.php');
require_once('function_javascript.php');
load_all_script();
require_once('class_dossier.php');
$gDossier=dossier::id();

$cn=new Database($gDossier);

include ('class_user.php');
$User=new User($cn);
$User->Check();
$User->check_dossier(dossier::id());

html_page_start($User->theme);


/* Admin. Dossier */

include_once("import_inc.php");

include_once ("user_menu.php");
echo '<div class="u_tmenu">';
echo ShowMenuCompta("user_advanced.php?".dossier::get());
echo '</div>';


echo ShowMenuAdvanced(7);
$User->can_request(GEBQ,1);

echo '</div>';
echo '<div class="content"> <h2> Ce module est obsolète et est remplacé par un plugin. Il sera définitivement enlevé dans la prochaine
 version. Si possible ne l\'utilisez plus</h2>';
echo '</div>';

echo '<div class="lmenu">';
ShowMenuImport();

// if action is set proceed to it
if ( isset ($_GET["action"]) )
{
    $action=$_GET["action"];
    // menu = import cvs
    if ($action == "import" )
    {
        if(isset($_FILES['fupload']))
        {
            // load the table with the cvs' content
            echo '<DIV class="u_redcontent">';
            ImportCSV($cn,$_FILES['fupload']['tmp_name'],$_POST['import_bq'],$_POST['format_csv'],$_POST['import_jrn']);
            echo "</DIV>";
        }
        else
        {
            echo '<DIV class="u_redcontent">';
            ShowFormTransfert($cn);
            echo "</DIV>";
        }
    }
    if ($action == "verif" )
    {
        echo '<DIV class="u_redcontent">';
        VerifImport($cn);
        echo "</DIV>";
    }

    if ($action == "transfer" )
    {

        echo '<DIV class="u_redcontent">';
        /* show the possible period */
        $default_period=$User->get_periode();
        $ip=new IPeriod('period');
        $ip->filter_year=true;
        $ip->user=$User;
        $ip->value=(isset($_GET['period']))?$_GET['period']:$default_period;
        $ip->cn=$cn;
        $ip->type=OPEN;
        $ip->javascript="onchange=submit(this)";
        echo '<form method="get" action="import.php" >';
        echo dossier::hidden();
        echo HtmlInput::hidden('action','transfer');
        echo _('Choississez la période à transfèrer');
        echo $ip->input();
        echo '</form>';
        //   TransferCSV($cn,
        ConfirmTransfert($cn,$ip->value);
        echo "</DIV>";
    }
}
/*-----------------------------------------------
 * transfert or remove the wrong record 
 *
 *-----------------------------------------------*/
if ( isset ($_POST['action']))
{
    $action=$_POST['action'];
    if ($action == "transfer" )
    {
        echo '<DIV class="u_redcontent">';
        TransferCSV($cn, $User->get_periode());
        echo "</DIV>";
    }

    if ($action == "remove" )
    {
        echo '<DIV class="u_redcontent">';
        RemoveCSV($cn);
        ConfirmTransfert($cn,$User->get_periode());
        echo "</DIV>";
    }

}
if ( isset($_REQUEST['action']) && $_REQUEST['action']=='purge')
{
    if ( isset($_POST['ok']))
    {
        echo '<div class="u_redcontent">';
        echo h2info('Toutes  les données CSV sont effacées');
        $cn->exec_sql('truncate import_tmp');
        echo '</DIV>';

    }
    else
    {
        echo '<div class="u_redcontent">';
        echo '<p> En purgeant la table, vous effacez toutes les données des fichiers CSV que vous avez importés,
        cela n\' affecte pas les opérations déjà transfèrées dans la comptabilité</p>';
        echo '<form method="POST">';
        echo HtmlInput::hidden('action','purge');
        echo HtmlInput::submit('ok','Purger');
        echo '</FORM>';
        echo '</DIV>';
    }
}
html_page_stop();
?>
