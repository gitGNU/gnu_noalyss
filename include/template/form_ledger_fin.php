<fieldset>
	<div id="jrn_name_div">
	<h2 id="jrn_name"> <?=$this->get_name()?></h2>
</div>
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
<th style="text-align: left;width: auto"colspan="2">code<?HtmlInput::infobulle(0)?></TH>
   <th style="text-align: left"><?=_('Commentaire')?></TH>
   <th style="text-align: left"><?=_('Montant')?></TH>
   <th style="text-align: left;width:auto"colspan="2"> <?=_('Op. Concernée(s)')?></th>
</tr>

<? foreach ($array as $item) {
echo '<tr>';
echo td($item['qcode'].$item['search']);
echo td($item['comment']);
echo td($item['amount']);
echo td($item['concerned']);
echo '</tr>';
}
?>
</table>
</fieldset>
</fieldset>


