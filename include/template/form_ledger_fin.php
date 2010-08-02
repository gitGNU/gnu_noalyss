<fieldset>
<legend><?=$f_legend ?> </legend>
   <?=_('Date').' '.$f_date ?>
<?=$f_period?><br>
<?=$f_jrn?><br>
<?=_('Banque')?><?=$f_bank ?>
</fieldset>

<fieldset>
<legend><?=$f_legend_detail?></legend>
   <fieldset><legend><?=_('Extrait de compte')?></legend>
   <?=_('Numéro extrait')?> <?=$f_extrait?>
   <?=_('Solde début') ?> <?=$wFirst->input();?>
<?=_('Solde Fin')?> <?=$wLast->input();?>
</fieldset>
<?=$str_add_button?>
   <fieldset><legend><?=_('Opérations')?></legend>
<table id="fin_item" width="100%" border="0">
<tr>
<th colspan="2">code<?HtmlInput::infobulle(0)?></TH>
   <th><?=_('Commentaire')?></TH>
   <th><?=_('Montant')?></TH>
   <th colspan="2"> <?=_('Op. Concernée(s)')?></th>
</tr>

<? foreach ($array as $item) {
echo '<tr>';
echo td($item['search'].$item['qcode']);
echo td($item['span']);
echo td($item['comment']);
echo td($item['amount']);
echo td($item['concerned']);
echo '</tr>';
}
?>
</table>
</fieldset>
</fieldset>


