<fieldset id="asearch" style="height:88%">
<legend><? echo _('Résultats')?></legend>
<div style="height:88%;overflow:auto;">
<table>
<? for ($i=0;$i<sizeof($array);$i++) : ?>
<tr>
<td>
<a href="javascript:void(0)" onclick="<?=$array[$i]['javascript']?>">
</td>
<td>
<span  id="val<?=$i?>">
<?=$array[$i]['pcm_val']?>
</span>
</td>
<td>
<span id="lib<?=$i?>">
<?=$array[$i]['pcm_lib']?>
</span>
</td>
</tr>


<? endfor; ?>
</table>
<span style="background-color:#9FFFF1;border:1px solid black">
<? echo _("Nombre d'enregistrements").$i;?>
</span>

</div>
</fieldset>