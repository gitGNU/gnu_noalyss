<?php

require_once('class_anc_acc_list.php');
$tab = new Anc_Acc_List($cn);
$tab->get_request();
echo '<form method="get">';
echo $tab->display_form();
echo '<p>' . HtmlInput::submit('Recherche', 'Recherche') . '</p>';

echo '</form>';
if (isset($_GET['result']))
{
    echo $tab->show_button();
    $tab->display_html();
}
?>
