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
// Copyright (2014) Author Dany De Bontridder <dany@alchimerys.be>

if (!defined('ALLOWED'))
    die('Appel direct ne sont pas permis');

/**
 * @file
 * @brief Upgrade all the database : the central repository , the templates and
 * the folder
 * @param $rep db connection to central repository
 */
?>

<?php
$sb= HtmlInput::default_value_get("sb", "none");
if ($sb === "upg_all" && (!defined('MULTI')||(defined('MULTI')&&MULTI==1)))
{
    echo '<div class="content">';
    /* If multi folders */
    $Resdossier=$rep->exec_sql("select dos_id, dos_name from ac_dossier");
    $MaxDossier=$rep->size($Resdossier);
    
    //----------------------------------------------------------------------
    // Upgrade the account_repository
    //----------------------------------------------------------------------
    echo "<h2>"._("Mise à jour de la base de données principale")."</h2>";
    $cn=new Database();
    if (DEBUG==false)
        ob_start();
    $MaxVersion=DBVERSIONREPO-1;
    for ($i=4; $i<=$MaxVersion; $i++)
    {
        if ($cn->get_version()<=$i)
        {
            $cn->execute_script(NOALYSS_INCLUDE.'/sql/patch/ac-upgrade'.$i.'.sql');
        }
    }
    //----------------------------------------------------------------------
    // Upgrade the folders
    //----------------------------------------------------------------------
    echo "<h2>"._("Mise à jour dossiers")."</h2>";

    for ($e=0; $e<$MaxDossier; $e++)
    {
        $db_row=Database::fetch_array($Resdossier, $e);
        $name=$rep->format_name($db_row['dos_id'], 'dos');
        echo "<h3>Patching ".$name.':'.$db_row['dos_name'].'</h3>';


        if ($rep->exist_database($name)>0)
        {
            $db=new Database($db_row['dos_id'], 'dos');
            $db->apply_patch($db_row['dos_name']);
            Dossier::synchro_admin($db_row['dos_id']);
        }
        else
        {
            echo_warning(_("Dossier inexistant")." $name");
        }
    }

    //----------------------------------------------------------------------
    // Upgrade the template
    //----------------------------------------------------------------------
    $Resdossier=$rep->exec_sql("select mod_id, mod_name from modeledef");
    $MaxDossier=$rep->size();
    echo "<h2>"._("Mise à jour modèles")."</h2>";

    for ($e=0; $e<$MaxDossier; $e++)
    {
        $db_row=Database::fetch_array($Resdossier, $e);
        $name=$rep->format_name($db_row['mod_id'], 'mod');
        echo "<h3>Patching ".$name.":".$db_row['mod_name']."</h3>";

        if ($rep->exist_database($name)>0)
        {
            $db=new Database($db_row['mod_id'], 'mod');
            $db->apply_patch($db_row['mod_name']);
        }
        else
        {
            echo_warning(_("Modèle inexistant")." $name");
        }
    }

}
?>
