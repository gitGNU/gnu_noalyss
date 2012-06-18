<fieldset>
	<div id="jrn_name_div">
	<h2 id="jrn_name"> <?=$this->get_name()?></h2>
</div>
<legend><?=$f_legend ?> </legend>
<?
	$wchdate=new ISelect('chdate');
	$wchdate->value=array(
			array('value'=>1,'label'=>"Avec date d'extrait"),
			array('value'=>2,'label'=>"Avec date opérations")
	);
	$wchdate->selected=(isset($chdate))?$chdate:1;
	$wchdate->javascript='onchange="show_fin_chdate(\'chdate\')"';
?>
<?=$wchdate->input();?>
<span id="chdate_ext">
   <?=_('Date').' '.$f_date ?>
</span>

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
<th id="thdate" style="display:none;text-align: left"><?=_('Date')?><?=HtmlInput::infobulle(16)?></TH>
<th style="text-align: left;width: auto">code<?HtmlInput::infobulle(0)?></TH>
   <th style="text-align: left"><?=_('Fiche')?></TH>
   <th style="text-align: left"><?=_('Commentaire')?></TH>
   <th style="text-align: left"><?=_('Montant')?></TH>
   <th style="text-align: left;width:auto"colspan="2"> <?=_('Op. Concernée(s)')?></th>
</tr>

<?
$i=0;
foreach ($array as $item) {

echo '<tr>';
// echo td($item['dateop']);
echo td($item['dateop'],' style="display:none" id="tdchdate'.$i.'"');
echo td($item['qcode'].$item['search']);
echo td($item['cname']);
echo td($item['comment']);
echo td($item['amount']);
echo td($item['concerned']);
echo '</tr>';
$i++;

}
?>
</table>
</fieldset>
</fieldset>
<script>
	show_fin_chdate('chdate');
</script>

