<?php

    echo JS_INFOBULLE;
    echo JS_SEARCH_CARD;
    echo JS_SHOW_TVA;    
    echo JS_TVA;
    echo JS_AJAX_FICHE;

?>
<fieldset>
<legend><?=$f_legend ?>
</legend>
Date : <?=$f_date ?> Echeance : <?=$f_echeance?>
<?=$f_periode?><br>
Journal <?=$f_jrn?><br><hr>
Description <?=$f_desc?>
Num Pj <?=$f_pj?><br>
Client <?=$f_client_qcode?> <?=$f_client?>
</fieldset>

<fieldset>
<legend><?=$f_legend_detail?></legend>
<table id="sold_item" width="100%" border="0">
<tr>
<th colspan="2">Code <?=HtmlInput::infobulle(0)?></th>
<th>Dénomination</th>
<th>prix/unité htva<?=HtmlInput::infobulle(6)?></th>
<? if ($flag_tva =='Y') : ?>
<th>tva</th>
<th>montant htva</th>
<? endif;?>
<th>quantité</th>

</tr>
<? foreach ($array as $item) {
echo '<tr>';
echo $item['quick_code'];
?>
<td style="border-bottom: 1px dotted grey; width: 75%;"><?=$item['denom'] ?></td>
<?
echo td($item['pu']);
if ($flag_tva=='Y')  {
	echo td($item['tva']);
	echo td($item['amount_tva'].$item['hidden']);
}
echo td($item['quantity' ]);
echo '</tr>';
}

?>
</table>

<div style="position:float;float:right;text-align:right;padding-right:5px;font-size:1.2em;font-weight:bold;color:blue">
  <?=HtmlInput::button('act','Actualiser','onClick="compute_all_sold();"'); ?>
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
    <br>Total HTVA
    <br>Total TVA
    <br>Total TVAC
 <?php else:  ?>
	<br>Total
<?php endif; ?>
</div>

</fieldset>


