<?php
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
require_once 'class_default_menu.php';

global $cn,$g_failed,$g_succeed;

$a_default=new Default_Menu();

if ( isset($_POST['save_menu_default']) ) {
    $a_default->set('code_follow',$_POST['code_follow']);
    $a_default->set('code_invoice',$_POST['code_invoice']);
    try
    {
        $a_default->save();
        echo h2("Sauvé",'class="notice"',$g_succeed);
    } catch (Exception $ex)
    {
        echo h2("Code menu invalide",'class="notice"',$g_failed);
    }
}

echo '<form method="POST">';
echo HtmlInput::hidden('ac',$_REQUEST['ac']);
echo Dossier::hidden();
$a_default->input_value();
echo HtmlInput::submit('save_menu_default', "Sauver");
echo '</form>';
?>