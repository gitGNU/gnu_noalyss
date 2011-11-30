<?php

?>
<fieldset>
<legend><?=$f_legend ?>
</legend>
	<div id="jrn_name_div">
	<h2 id="jrn_name"> <?=$this->get_name()?></h2>
</div>
      <?=_('Date').':'?> <?=$f_date ?> <?=_('Echeance')?> : <?=$f_echeance?>
<?=$f_periode?><br>
      <?=_('Journal')?> <?=$f_jrn?><br><hr>
<?=$f_type?><?=$f_client_qcode?><?=$f_client_bt?> <?=$f_client?><br>
      <?=_('Libellé')?> <?=$f_desc?>
      <?=_('Num Pj')?> <?=$f_pj?><br>
<?=$str_add_button?>
</fieldset>

<fieldset>
<legend><?=$f_legend_detail?></legend>
<table id="sold_item" width="100%" border="0">
<tr>
<th style="width:auto"colspan="2">Code <?=HtmlInput::infobulle(0)?></th>
      <th><?=_('Dénomination')?></th>
      <th><?=_('prix/unité htva')?><?=HtmlInput::infobulle(6)?></th>
      <th><?=_('quantité')?></th>
      <th><?=_('Total HTVA')?></th>
<? if ($flag_tva =='Y') : ?>
 <th><?=_('tva')?></th>
 <th><?=_('tot.tva')?></th>
<th><?=_('tvac')?></th>
<? endif;?>



</tr>
<? foreach ($array as $item) {
echo '<tr>';
echo $item['quick_code'];
echo '<td>'.$item['bt'].'</td>';
?>
<td style="border-bottom: 1px dotted grey; width: 75%;"><?=$item['denom'] ?></td>
<?
echo td($item['pu']);
echo td($item['quantity' ]);
echo td($item['htva']);
if ($flag_tva=='Y')  {
	echo td($item['tva']);
	echo td($item['amount_tva'].$item['hidden']);

}
echo td($item['tvac']);
echo '</tr>';
}

?>
</table>

<div style="position:float;float:right;text-align:right;padding-right:5px;font-size:1.2em;font-weight:bold;color:blue">
      <?=HtmlInput::button('act',_('Actualiser'),'onClick="compute_all_ledger();"'); ?>
 </div>

    <div style="position:float;float:right;text-align:left;font-size:1.2em;font-weight:bold;color:blue" id="sum">
    <br><span id="htva">0.0</span>
<?php
    if ( $flag_tva=='Y' )  : ?>
     <br><span id="tva">0.0</span>
    <br><span id="tvac">0.0</span>
<?php    endif;     ?>

 </div>

<div style="position:float;float:right;text-align:right;padding-right:5px;font-size:1.2em;font-weight:bold;color:blue">
<?php
	if ( $flag_tva =='Y') :
	?>
  <br><?=_('Total HTVA')?>
  <br><?=_('Total TVA')?>
  <br><?=_('Total TVAC')?>
 <?php else:  ?>
     <br><?=_('Total')?>
<?php endif; ?>
</div>

</fieldset>


