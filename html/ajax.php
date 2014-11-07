<?php
/**
 *  This file is part of NOALYSS under GPL
 * 
 */
/**
 * @brief this file is used for the ajax from the extension, it will the ajax.php file from the plugin directory
 * all the variable are in $_REQUEST
 * The code (of the plugin) is required
 * Required variable in $_REQUEST
 *  - gDossier
 *  - plugin_code
 */
define ('ALLOWED',1);
require_once '../include/constant.php';
require_once('class_database.php');
require_once('class_user.php');
require_once('class_extension.php');
if ( !isset ($_REQUEST['gDossier'])) exit();

require_once 'class_own.php';
mb_internal_encoding("UTF-8");

global $g_user,$cn,$g_parameter;
$cn=new Database(dossier::id());
$g_parameter=new Own($cn);
$g_user=new User($cn);
$g_user->check(true);

/* if a code has been asked */
if (isset($_REQUEST['plugin_code']) )
{

    $ext=new Extension($cn);

    if ( $ext->search($_REQUEST['plugin_code']) != -1)
    {
        /* security */
        if ( !isset ($_SESSION['g_user']) || $ext->can_request($_SESSION['g_user']) == 0 )
        {
            exit();
        }
        /* call the ajax script */
        require_once('ext'.DIRECTORY_SEPARATOR.dirname(trim($ext->getp('me_file'))).DIRECTORY_SEPARATOR.'ajax.php');
    }
    else
    {
        alert(j(_("Cette extension n'existe pas ")));
        exit();
    }

}
?>