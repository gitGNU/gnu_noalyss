
<table>
<tr>
<td style="text-align:right;width:30em">
<?php echo _('Dans le journal')?>
</td>
<td>
   <?php echo $f_ledger; ?>
</td>
</tr>

<tr>
<td style="text-align:right;width:30em">
<?php echo _('Et Compris entre les date')?>
</td>
<td>
<?php echo $f_date_start->input();  ?> <?php echo _('et')?> <?php echo $f_date_end->input();  ?>
</td>
</tr>

<tr>
<td style="text-align:right;width:30em">
<?php echo _('Et contenant dans le libellé, pièce justificative ou n° interne')?>
</td>

<td>
<?php echo $f_descript->input(); ?>
</td>
</tr>
<tr>
<td style="text-align:right;width:30em">
<?php echo _('Et compris entre les montants')?>
</td>
<td >
<?php echo $f_amount_min->input();  ?> <?php echo _('et')?> <?php echo $f_amount_max->input(); ; ?>
</td>
</tr>
<tr>
<td style="text-align:right;width:30em">
	<?php echo _('Et utilisant la fiche (quick code)')?>
</td>
<td>
   <?php echo $f_qcode->input(); echo $f_qcode->search(); ?>
</td>
</tr>
<tr>
<td style="text-align:right;width:30em">
	<?php echo _('Et utilisant le poste comptable').$info?>
</td>

<td>
<?php echo $f_accounting->input();  ?>
</td>
</tr>

<tr>
<td style="text-align:right;width:30em">
	<?php echo _('Et uniquement non payées')?>
</td>

<td>
<?php echo $f_paid->input();  ?>
</td>
</tr>

</table>

