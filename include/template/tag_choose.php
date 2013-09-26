<?php
echo HtmlInput::title_box('Etiquette', 'tag_div');
$max=$this->cn->count($ret);
if ( $max == 0 ) {
    echo h2("Aucune étiquette disponible",' class="notice"');
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
<?php
   $js=sprintf("onclick=\"show_tag('%s','%s','%s','j')\"",Dossier::id(),"none",'-1');
    echo HtmlInput::button("tag_add", "Ajout d'une étiquette", $js);
?>