<?php
//This file is part of NOALYSS and is under GPL 
//see licence.txt

if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
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

/**
 * included from do.php + extension_choice.inc.php
 */

// find file and check security
global $cn,$g_user;

$ext=new Extension($cn);

if ($ext->search($_REQUEST['plugin_code']) == -1)
	{
		echo_warning("plugin non trouvé");
		return;
}
if ($ext->can_request($g_user->login)==-1)
{
	alert("Plugin non authorisé");
	return;
}
if ( ! file_exists('../include/ext'.DIRECTORY_SEPARATOR.trim($ext->me_file)))
	{
		alert(j(_("Ce fichier n'existe pas ")));
		return;
	}
echo '<div class="content">';
require_once('ext'.DIRECTORY_SEPARATOR.trim($ext->me_file));


?>
