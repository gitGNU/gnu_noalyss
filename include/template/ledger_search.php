<fieldset><legend><?=_('Critères de Recherche')?></legend>
<ul class="select_table">
<li>
<span style='text-align:right;width:30em'>
<?=_('Dans le journal')?>
</span>
<span>
   <?php echo $f_ledger; ?>
</span>
</li>

<li>
<span style='text-align:right;width:30em'>
<?=_('Et Compris entre les date')?>
</span>
<span>
<?php echo $f_date_start->input();  ?> <?=_('et')?> <?php echo $f_date_end->input();  ?>
</span>
</li>

<li>
<span style='text-align:right;width:30em'>
<?=_('Et contenant dans le libellé, pièce justificative ou n° interne')?>
</span>

<span>
<?php echo $f_descript->input(); ?>
</span>
</li>
<li>
<span style='text-align:right;width:30em'>
<?=_('Et compris entre les montants')?>
</span>
<span >
<?php echo $f_amount_min->input();  ?> <?=_('et')?> <?php echo $f_amount_max->input(); ; ?>
</span>
</li>
<li>
<span style='text-align:right;width:30em'>
	<?=_('Et utilisant la fiche (quick code)')?>
</span>
<span>
   <?php echo $f_qcode->search();echo $f_txt_qcode->input();  ?>
</span>
</li>
<li>
<span style='text-align:right;width:30em'>
	<?php echo _('Et utilisant le poste comptable').$info?>
</span>

<span>
<?php echo $f_accounting->input();  ?>
</span>
</li>

<li>
<span style='text-align:right';width:30em>
	<?=_('Et uniquement non payées')?>
</span>

<span>
<?php echo $f_paid->input();  ?>
</span>
</li>

</ul>
</fieldset>
