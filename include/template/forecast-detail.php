<h2><?php  echo $str_name;?></h2>
<fieldset>
<legend><?php  echo $str_action;?></legend>

<table id="fortable">
<tr>
<th><?php echo _('Catégorie');?></th>
<th><?php echo _('Poste Comptable');?></th>
<th><?php echo _('QuickCode');?></th>
<th><?php echo _('Libellé');?></th>
<th><?php echo _('Montant');?></th>
<th><?php echo _('Débit ou Crédit');?></th>
</tr>
<?php for ($i=0;$i<count($aCat);$i++):?>
<tr>
<td><?php echo $aCat[$i]['cat'];?></td>
<td><?php echo $aCat[$i]['account'];?></td>
<td><?php echo $aCat[$i]['qc'];?></td>
<td><?php echo $aCat[$i]['name'];?></td>
<td><?php echo $aCat[$i]['per'];?></td>

<td><?php echo $aCat[$i]['amount'];?></td>
<td><?php echo $aCat[$i]['deb'];?></td>
</tr>
<?php endfor;?>
</table>
<?=$f_add_row ?>
</fieldset>


