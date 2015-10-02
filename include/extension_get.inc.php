<?php
//This file is part of NOALYSS and is under GPL 
//see licence.txt

if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
require_once NOALYSS_INCLUDE.'/lib/class_database.php';
require_once NOALYSS_INCLUDE.'/class/class_dossier.php';
require_once NOALYSS_INCLUDE.'/lib/ac_common.php';
require_once NOALYSS_INCLUDE.'/constant.php';
require_once NOALYSS_INCLUDE.'/lib/function_javascript.php';
require_once NOALYSS_INCLUDE.'/class/class_extension.php';
require_once  NOALYSS_INCLUDE.'/lib/class_html_input.php';
require_once NOALYSS_INCLUDE.'/lib/class_iselect.php';
require_once  NOALYSS_INCLUDE.'/constant.security.php';
require_once  NOALYSS_INCLUDE.'/class/class_user.php';

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
if ( ! file_exists(NOALYSS_PLUGIN.'/'.trim($ext->me_file)))
	{
		alert(j(_("Ce fichier n'existe pas ")));
		return;
	}
echo '<div class="content">';
require_once NOALYSS_PLUGIN.DIRECTORY_SEPARATOR.trim($ext->me_file);


?>
