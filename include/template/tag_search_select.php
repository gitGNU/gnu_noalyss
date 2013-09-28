<?php
echo HtmlInput::title_box('Tag', $p_prefix.'tag_div');
$max=$this->cn->count($ret);
if ( $max == 0 ) {
    echo h2("Aucune étiquette disponible",' class="notice"');
    return;
}
?>
Filtrer = <?php echo HtmlInput::filter_table($p_prefix.'tag_tb_id', '0,1', 1); ?>
<table id="<?php echo $p_prefix;?>tag_tb_id">
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
    for ($i=0;$i<$max;$i++):
        $row=Database::fetch_array($ret, $i);
?>
    <tr class="<?php echo (($i%2==0)?'even':'odd');?>">
        <td>
            <?php
            $js=sprintf("search_add_tag('%s','%s','%s')",$gDossier,$row['t_id'],$p_prefix);
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