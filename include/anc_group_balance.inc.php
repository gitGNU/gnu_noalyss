<?php
//This file is part of NOALYSS and is under GPL 
//see licence.txt

if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
require_once('class_anc_group.php');
$gr = new Anc_Group($cn);
$gr->get_request();
echo '<form method="get">';
echo $gr->display_form();
echo '<p>' . HtmlInput::submit('Recherche', 'Recherche') . '</p>';
echo '</form>';
if (isset($_GET['result']))
{
    echo $gr->show_button();

    echo $gr->display_html();
}
?>
