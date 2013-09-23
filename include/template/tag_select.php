<?php
echo HtmlInput::title_box('Tag', 'tag_div');
$max=$this->cn->count($ret);
if ( $max == 0 ) {
    echo h2("Aucun tag disponible",' class="notice"');
    return;
}
?>
<table>
    <tr>
        <th>
            Tag
        </th>
        <th>
            Description
        </th>
    </tr>
<?php
$gDossier=Dossier::id();
if (isNumber($_REQUEST['ag_id']) == 0 ) die ('ERROR : parameters invalid');
    for ($i=0;$i<$max;$i++):
        $row=Database::fetch_array($ret, $i);
?>
    <tr class="<?php echo (($i%2==0)?'even':'odd');?>">
        <td>
            <?php
            $js=sprintf("action_tag_add('%s','%s','%s')",$gDossier,$_REQUEST['ag_id'],$row['t_id']);
            echo HtmlInput::anchor($row['t_tag'], "", "onclick=\"$js\"");
            ?>
        </td>
        <td>
            <?php
            echo $row['t_description'];
            ?>
        </td>
    </tr>
<?php
 endfor;
 ?>
</table>