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

// Copyright 2015 Author Dany De Bontridder danydb@aevalys.eu

if (!defined('ALLOWED'))
    die('Appel direct ne sont pas permis');
/**
 * @file
 * @brief the file contents the code which answer to ajax call from 
 * admin_repo.php
 * @see admin_repo.php ajax_misc.php admin.js
 */
if ($g_user->Admin()==0)
{
    die();
}
// From admin, grant  the access to a folder to an
// user
if ($op=='folder_add') // operation
{

    $cn=new Database();
    $user_id=HtmlInput::default_value_get("p_user", 0); // get variable
    $dossier_id=HtmlInput::default_value_get("p_dossier", 0); // get variable
    if ($user_id==0||$dossier_id==0||isNumber($user_id)==0||$dossier_id==0)
    {

        $content=_('Erreur paramètre');
        $status="NOK";
    }
    else
    {
        $user=new User($cn, $user_id);
        $user->set_folder_access($dossier_id, true);
        $dossier=new Dossier($dossier_id);
        $dossier->load();
        $content="<td>{$dossier->dos_name}</td><td>{$dossier->dos_description}</td>".
                "<td>".
                 HtmlInput::anchor(_('Enleve'),"",
                            " onclick=\"folder_remove({$user_id},{$dossier_id});\"").
                "</td>";
        $status='OK';
    }
    //----------------------------------------------------------------
    // Answer in XML
    header('Content-type: text/xml; charset=UTF-8');
    $dom=new DOMDocument('1.0', 'UTF-8');
    $xml_content=$dom->createElement('content', $content);
    $xml_status=$dom->createElement('status', $status);
    $root=$dom->createElement("root");
    $root->appendChild($xml_content);
    $root->appendChild($xml_status);
    $dom->appendChild($root);
    echo $dom->saveXML();
    exit();
}
// From admin, revoke the access to a folder from an
// user
if ($op=='folder_remove') // operation
{

    $cn=new Database();
    $user_id=HtmlInput::default_value_get("p_user", 0); // get variable
    $dossier_id=HtmlInput::default_value_get("p_dossier", 0); // get variable
    if ($user_id==0||$dossier_id==0||isNumber($user_id)==0||$dossier_id==0)
    {
        $content=_('Erreur paramètre');
        $status="NOK";
    }
    else
    {
        $user=new User($cn, $user_id);
        $user->set_folder_access($dossier_id, false);
        $content="";
        $status='OK';
    }
    //----------------------------------------------------------------
    // Answer in XML
    header('Content-type: text/xml; charset=UTF-8');
    $dom=new DOMDocument('1.0', 'UTF-8');
    $xml_content=$dom->createElement('content', $content);
    $xml_status=$dom->createElement('status', $status);
    $root=$dom->createElement("root");
    $root->appendChild($xml_content);
    $root->appendChild($xml_status);
    $dom->appendChild($root);
    echo $dom->saveXML();
    exit();
}
/**
 * Display the forbidden folders if the request comes from a form
 * with an input text (id:database_filter_input) then this text is 
 * used as a filter
 * 
 */
if ($op=='folder_display') // operation
{

    $cn=new Database();
    $user_id=HtmlInput::default_value_get("p_user", 0); // get variable
    $p_filter=HtmlInput::default_value_get('p_filter', '');

    if ($user_id==0||isNumber($user_id)==0)
    {
        $content=_('Erreur paramètre');
        $status="NOK";
    }
    else
    {
        ob_start();
        $user=new User($cn, $user_id);
        $a_dossier=Dossier::show_dossier('X', $user->id, $p_filter, MAX_FOLDER_TO_SHOW);
        echo HtmlInput::title_box(_("Liste dossier"), 'folder_list_div');
        ?>
        <form method="get" onsubmit="folder_display('<?php echo $user_id ?>');
                return false">
            <p style="text-align: center">
                <?php echo _('Recherche'); ?><input type="text" id="database_filter_input" class="input_text" autofocus="true" nohistory autocomplete="false" value="<?php echo $p_filter ?>">
                <input type="submit" class="smallbutton" value="<?php echo _('Valider') ?>">
            </p>
        </form>    
        <p>
            <?php
            $found_dossier=count($a_dossier);
            $max=( $nb_dossier>=MAX_FOLDER_TO_SHOW)?MAX_FOLDER_TO_SHOW:$nb_dossier;
            echo _('Dossiers trouvés').':'.$nb_dossier." "._('Dossiers affichés').$max;
            ?>
        </p>
        <?php
        require 'template/folder_display.php';
        $content=ob_get_clean();
        $status='OK';
    }
    //----------------------------------------------------------------
    // Answer in XML
    header('Content-type: text/xml; charset=UTF-8');
    $dom=new DOMDocument('1.0', 'UTF-8');
    $xml=escape_xml($content);
    $xml_content=$dom->createElement('content', $xml);
    $xml_status=$dom->createElement('status', $status);
    $root=$dom->createElement("root");
    $root->appendChild($xml_content);
    $root->appendChild($xml_status);
    $dom->appendChild($root);
    echo $dom->saveXML();
    exit();
}
?>        