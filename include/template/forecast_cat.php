<fieldset>
<legend>
<?php echo $str_action;?></legend>
<?php echo $str_name;?>
<h2> <?php echo _('Catégories');?></h2>
<table>
<tr>
<th><?php echo _('Ordre');?></th>
<th><?php echo _('Nom');?></th>
</tr>
<?php for ($i=0;$i<count($aCat);$i++):?>
<tr>
<td>
<?php echo $aCat[$i]['order'];?>
</td>
<td>
<?php echo $aCat[$i]['name'];?>
</td>
</tr>
<?php endfor;?>
</table>


</fieldset>