<?php
require_once 'class_menu_ref.php';
$msg="Création";
$m=new Menu_Ref($cn);
echo '<form method="POST" onsubmit="return confirm(\'Vous confirmez ?\')">';
echo HtmlInput::hidden('tab','profile_menu_div');
require_once 'template/menu_detail.php';
echo HtmlInput::submit('create_menu','Sauver');
echo HtmlInput::button_close('divmenu');
echo '</form>';
?>
