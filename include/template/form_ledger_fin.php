<?php

    echo JS_INFOBULLE;
    echo JS_SEARCH_CARD;
    echo JS_CONCERNED_OP;
    echo JS_AJAX_FICHE;

?>
<fieldset>
<legend><?=$f_legend ?> </legend>
Date <?=$f_date ?> 
<?=$f_period?><br>
<?=$f_jrn?><br>
<?=$ibank->input(); ?> <span id='e_bank_account_label'><?$f_bank_label?></span>
</fieldset>

<fieldset>
<legend><?=$f_legend_detail?></legend>
<fieldset><legend>Extrait de compte</legend>
Numéro extrait <?=$f_extrait?>
Solde début <?=$wFirst->input();?>
Solde Fin <?=$wLast->input();?>
</fieldset>

<fieldset><legend>Opérations</legend>
<table id="fin_item" width="100%" border="0">
<tr>    
<th colspan="2">code"<?HtmlInput::infobulle(0)?></TH>
<th>Commentaire</TH>
<th>Montant</TH>
<th colspan="2"> Op. Concern&eacute;e(s)</th>
</tr>

<? foreach ($array as $item) {
echo '<tr>';
echo td($item['qcode']);
echo td($item['span']);
echo td($item['comment']);
echo td($item['amount']);
echo td($item['concerned']);
echo '</tr>';
}
?>
</table>

</fieldset>


