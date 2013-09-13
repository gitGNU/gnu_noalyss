<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
require_once ('class_anc_listing.php');
$list = new Anc_Listing($cn);
$list->get_request();

echo $list->display_form();
//---- result
if (isset($_GET['result']))
{
    echo '<div class="content">';

    //--------------------------------
    // export Buttons
    //---------------------------------
    echo $list->show_button();
    echo $list->display_html();
    echo '</div>';
}
echo '</div>';
?>
