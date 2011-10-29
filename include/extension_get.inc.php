<?php
require_once('class_database.php');
require_once('class_dossier.php');
require_once("ac_common.php");
require_once("constant.php");
require_once('function_javascript.php');
require_once('class_extension.php');
require_once ('class_html_input.php');
require_once('class_iselect.php');
require_once ('constant.security.php');
require_once ('class_user.php');

/* if a code has been asked */
if (isset($_REQUEST['plugin_code']) )
{
    $cn=new Database(dossier::id());
    $ext=new Extension($cn);
    $ext->search('code',$_REQUEST['plugin_code']);
    if ( $ext->get_parameter('id') != 0 )
    {
        /* security */
        if ( $ext->can_request($_SESSION['g_user']) == 0 )
        {
            alert(j(_("Vous ne pouvez pas utiliser cette extension. Contactez votre responsable")));
            exit();
        }

        if ( ! file_exists('../include/ext'.DIRECTORY_SEPARATOR.trim($ext->get_parameter('filepath'))))
        {
            alert(j(_("Ce fichier n'existe pas ")));
            exit();
        }
        require_once('ext'.DIRECTORY_SEPARATOR.trim($ext->get_parameter('filepath')));
    }
    else
    {
        alert(j(_("Cette extension n'existe pas ")));
        exit();
    }

}
?>
