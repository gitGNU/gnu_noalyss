<fieldset id="asearch" style="height:88%">
   <legend><? echo _('Résultats'); ?></legend>
<div style="height:88%;overflow:auto;">
<table >
<? for ($i=0;$i<sizeof($array);$i++) : ?>
<tr>
<td style="padding-right:55">
<a href="javascript:void(0)" class="one" onclick="<?=$array[$i]['javascript']?>">
<?=$array[$i]['quick_code']?>
</a>
</td>
<td>
   <?=$array[$i]['name']?>
</td>
<td>
   <?=$array[$i]['first_name']?>
</td>
<td>
<?=$array[$i]['description']?>
</td>

</tr>


<? endfor; ?>
</table>
<span style="font-style: italic;">
   <? echo _("Nombre d'enregistrements:$i"); ?>
</span>
<br>
</div>
</fieldset>
