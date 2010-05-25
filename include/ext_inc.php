<?php

/* if a code has been asked */
if (isset($_REQUEST['plugin_code']) ) {
  $cn=new Database(dossier::id());
  $ext=new Extension($cn);
  $ext->search('code',$_REQUEST['plugin_code']);
  if ( $ext->get_parameter('id') != 0 ) {
    /* security */
    if ( $ext->can_request($_SESSION['g_user']) == 0 ) {
      alert(j(_("Vous ne pouvez pas utiliser cette extension. Contactez votre responsable")));
      exit();
    }
    /* show name of extension */
    echo '<div style="position:absolute;left:3px;top:3px;margin:0;z-index:10" class="noprint">';
    echo '<h2 class="error" style="display:inline;padding:0px;margin:0px">'.$ext->get_parameter('name').'</h2>';


    echo '</div>';
    require_once('ext'.DIRECTORY_SEPARATOR.trim($ext->get_parameter('filepath')));
  } else {
    alert(j(_("Cette extension n'existe pas ")));
    exit();
  }

}
?>
