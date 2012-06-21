<?php
require_once 'class_menu_ref.php';
$m=new Menu_Ref($cn,$me_code);
$msg="Modification ".$m->me_code.' '.h($m->me_menu);
echo '<form method="POST" onsubmit="return confirm(\'Vous confirmez ?\')">';
echo HtmlInput::hidden('tab','profile_menu_div');
require_once 'template/menu_detail.php';

echo HtmlInput::submit('modify_menu','Sauver');
echo HtmlInput::button_close('divmenu');
echo '</form>';
?>
