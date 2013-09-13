<?php
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
require_once 'class_menu_ref.php';
$msg="Création";
$m=new Menu_Ref($cn);
echo '<form method="POST" onsubmit="return confirm(\'Vous confirmez ?\')">';

require_once 'template/menu_detail.php';
echo HtmlInput::submit('create_menu','Sauver');
echo HtmlInput::button_close('divmenu');
echo '</form>';
?>
