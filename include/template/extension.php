<?=$str_new?>
<table width="100%">
<? for ($i=0;$i < count($ext);$i++) : ?>
<? $idx=$ext[$i]['ex_id']; ?>
<tr id="<? echo 'tr_'.$idx;?>" >
<td>
<a href="javascript:detail_extension('<?=$idx?>')">
<?=$ext[$i]['ex_code']?>
</a>
</td>
<td>
<?=$ext[$i]['ex_name']?>
</td>
<td>
<?=$ext[$i]['ex_desc']?>
</td>
<td>
   <?if ($ext[$i]['ex_enable'] == 'Y' ) echo _('Activé'); else echo _('Désactivé');?>
</td>
<td>
<?php
   $bt=new IButton('remove');
$bt->label=_('Effacer extension');
$bt->javascript='if (confirm(\''.j(_("Confirmez effacement de l'extension ??")).'\')) { ';
$bt->javascript.='extension_remove(\''.$ext[$i]['ex_id'].'\')}';
echo $bt->input();
?>
</td>
</tr>


<? endfor; ?>
</table>