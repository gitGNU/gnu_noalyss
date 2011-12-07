<?php
require_once 'class_html_input.php';
require_once 'class_itext.php';
echo HtmlInput::title_box($msg,"divmenu");
$str_code=new IText('me_code',$m->me_code);
if ( $m->me_code != -1) $str_code->setReadOnly (true);

$str_menu=new IText('me_menu',$m->me_menu);
$str_desc=new IText('me_description',$m->me_description);
$str_file=new IText('me_file',$m->me_file);
$str_url=new IText('me_url',$m->me_url);
$str_parameter=new IText('me_parameter',$m->me_parameter);
$str_js=new IText('me_javascript',$m->me_javascript);

?>
<table>
    <tr>
        <td>
            Code du menu
        </td>
        <td>
            <?=$str_code->input()?>
        </td>
    </tr>
        <tr>
        <td>
            Libellé du menu
        </td>
        <td>
            <?=$str_menu->input()?>
        </td>
    </tr>
        <tr>
        <td>
            Description
        </td>
        <td>
            <?=$str_desc->input()?>
        </td>
    </tr>
         <tr>
        <td>
            Fichier à inclure (depuis le répertoire include)
        </td>
        <td>
            <?=$str_file->input()?>
        </td>
    </tr>
    <tr>
        <td>
            URL
        </td>
        <td>
            <?=$str_url->input()?>
        </td>
    </tr>
     <tr>
        <td>
            Paramèttre
        </td>
        <td>
            <?=$str_parameter->input()?>
        </td>
    </tr>
     <tr>
        <td>
            Javascript
        </td>
        <td>
            <?=$str_js->input()?>
        </td>
    </tr>
</table>
