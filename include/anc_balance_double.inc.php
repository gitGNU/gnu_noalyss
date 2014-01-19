<?php
//This file is part of NOALYSS and is under GPL 
//see licence.txt

if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
require_once ('class_anc_balance_double.php');
$bc = new Anc_Balance_Double($cn);
$bc->get_request();
echo '<form method="get">';
echo $bc->display_form();
echo '</form>';
if (isset($_GET['result']))
{
    echo $bc->show_button();
    echo $bc->display_html();
}
?>
