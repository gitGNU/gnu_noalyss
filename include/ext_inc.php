<?php

/* if a code has been asked */
if (isset($_REQUEST['code']) ) {
  $cn=new Database(dossier::id());
  $ext=new Extension($cn);
  $ext->search('code',$_REQUEST['code']);
  if ( $ext->get_parameter('id') != 0 ) {
    /* security */
    if ( $ext->can_request($_SESSION['g_user']) == 0 ) {
      alert(j(_("Vous ne pouvez pas utiliser cette extension. Contactez votre responsable")));
      exit();
    }
    /* show name of extension */
    echo "<h2>".$ext->get_parameter('name').'</h2>';
    require_once('ext'.DIRECTORY_SEPARATOR.trim($ext->get_parameter('filepath')));
  } else {
    alert(j(_("Cette extension n'existe pas ")));
    exit();
  }

}
?>