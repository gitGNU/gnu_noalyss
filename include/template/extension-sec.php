<div style="overflow:auto">
<fieldset>
<legend><?=_('Sécurité')?></legend>
<table class="result">
<tr >
<th><?=_('login')?></th>
<th><?=_('Prénom')?></th>
<th><?=_('Nom')?></th>
<th><?=_('Accès')?></th>
</tr>
<? for ($i = 0 ; $i < sizeof($array);$i++) : ?>
<? if ( $i % 2 == 0 ) $class="even"; else $class="odd"; ?>
<tr>
<td >
<?=$array[$i]['use_login']?>
</td>
<td>
<?=$array[$i]['use_first_name']?>
</td>
<td>
<?=$array[$i]['use_name']?>
</td>
<td>
<?=$array[$i]['access']?>
</td>
</tr>
<? endfor;?>

</table>
</fieldset>
</div>