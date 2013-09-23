<?php
/**
 * @brief display a window with the content of a tag
 */
if ( !defined ('ALLOWED') )  die('Appel direct ne sont pas permis');
require_once 'class_tool_uos.php';
require_once 'class_tag.php';
ob_start();
$tag=new Tag($cn);
$tag->data->t_id=$_GET['tag'];
$tag->data->load();
echo HtmlInput::title_box("Détail du dossier / tag", "tag_div");

?>
<form id="tag_frm_detail" method="post">
    <?php
    echo dossier::hidden();
    echo HtmlInput::hidden('t_id', $_GET['tag']);
    echo HtmlInput::hidden('ac',$_GET['ac']);
    $data=$tag->data;
    require_once 'template/tag_detail.php';
    echo HtmlInput::submit("save_tag", "Valider");
    ?>
</form>
<?php
    $response=  ob_get_clean();
    ob_end_clean();
    $html=escape_xml($response);
    header('Content-type: text/xml; charset=UTF-8');
    echo <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<data>
<ctl></ctl>
<code>$html</code>
</data>
EOF;
    exit();
    ?>