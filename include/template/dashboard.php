<div>
<fieldset>
<legend><?=_('Calendrier')?>
</legend>
<?php echo $cal->display(); ?>
</fieldset>
</div>
<div style="float:left;width:50%">
<fieldset>
<legend><?=_('Dernières opérations')?>
</legend>
<table width="100%">
<?php
for($i=0;$i<count($last_ledger);$i++):
?>
<tr>
<td>
<?=$last_ledger[$i]['jr_date_fmt']?>
</td>
<td>
   <?=h(substr($last_ledger[$i]['jr_comment'],0,20))?>
</td>
<td>
<?=$last_ledger[$i]['jr_montant']?>
</td>
<td>
<?=$last_ledger[$i]['jr_internal']?>
</td>

</tr>
<? endfor;?>
</table>

</fieldset>
</div>


<div style="float:left;width:50%">
<fieldset>
<legend><?=_('Dernières actions')?>
</legend>
<table width="100%">
<?php
for($i=0;$i<count($last_operation);$i++):
?>
<tr>
<td>
   <?=h($last_operation[$i]['ag_timestamp_fmt'])?>
</td>
<td>
   <?=h($last_operation[$i]['vw_name'])?>
</td>

<td>
<? echo '<A HREF="commercial.php?'.dossier::get().'&p_action=suivi_courrier&sa=detail&ag_id='.$last_operation[$i]['ag_id'].'">'; ?>
<?=h(substr($last_operation[$i]['ag_title'],0,40))?>
</a>
</td>
<td>
<?=$last_operation[$i]['dt_value']?>
</tr>

<? endfor;?>
</table>
</fieldset>
</div>

