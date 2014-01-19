<?php
//This file is part of NOALYSS and is under GPL 
//see licence.txt

if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
require_once 'class_menu_ref.php';
$m=new Menu_Ref($cn,$me_code);
$msg="Modification ".$m->me_code.' '.h($m->me_menu);
echo '<form method="POST" onsubmit="return confirm(\'Vous confirmez ?\')">';

require_once 'template/menu_detail.php';

echo HtmlInput::submit('modify_menu','Sauver');
echo HtmlInput::button_close('divmenu');
echo '</form>';
?>
