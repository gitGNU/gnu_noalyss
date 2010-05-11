<?php
/**
 * @brief this file is used for the ajax from the extension, it will the ajax.php file from the plugin directory
 * all the variable are in $_REQUEST
 * The code (of the plugin) is required
 * Required variable in $_REQUEST
 *  - gDossier
 *  - code
 */
require_once('class_database.php');
require_once('class_user.php');
require_once('class_extension.php');
if ( !isset ($_REQUEST['gDossier'])) exit();

$cn=new Database(dossier::id());
$user=new User($cn);
$user->check(true);

/* if a code has been asked */
if (isset($_REQUEST['code']) ) {

  $ext=new Extension($cn);
  $ext->search('code',$_REQUEST['code']);
  if ( $ext->get_parameter('id') != 0 ) {
    /* security */
    if ( !isset ($_SESSION['g_user']) || $ext->can_request($_SESSION['g_user']) == 0 ) {
      exit();
    }
    /* call the ajax script */
    require_once('ext'.DIRECTORY_SEPARATOR.dirname(trim($ext->get_parameter('filepath'))).DIRECTORY_SEPARATOR.'ajax.php');
  } else {
    alert(j(_("Cette extension n'existe pas ")));
    exit();
  }

}
?>