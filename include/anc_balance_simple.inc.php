<?php
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
require_once ('class_anc_balance_simple.php');
$bs = new Anc_Balance_Simple($cn);
$bs->get_request();
echo '<form method="get">';
echo $bs->display_form();
echo '</form>';
if (isset($_GET['result']))
{
    echo $bs->show_button();
    echo $bs->display_html();
}
?>
