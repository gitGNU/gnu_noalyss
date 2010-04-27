<table class="result">
<tr>
<th>
</th>
<th>
   <?=_('Lettrage')?>
</th>
<th>
   <?=_('Date')?>
</th>
<th>
   <?=_('Ref')?>
</th>
<th>
   <?=_('Description')?>
</th>
<th>
   <?=_('Montant')?>
</th>
<th>
   <?=_('Debit / Credit')?>
</th>
</tr>

<?php
   $amount_deb=($j_debit=='t')?$amount_init:0;
$amount_cred=($j_debit=='f')?$amount_init:0;
for ($i=0;$i<count($this->content);$i++):
?>
<tr>
<td>
<?php
echo HtmlInput::hidden('letter_j_id[]',$this->content[$i]['j_id']);

   if ($this->content[$i]['j_id']==$p_jid) continue;
$check=new ICheckbox('ck'.$i);
if ( $jnt_id == $this->content[$i]['letter'] ) $check->selected=true; else $check->selected=false;

if ( $this->content[$i]['letter'] == -1 ||  $check->selected == true )
	echo $check->input();
?>
</td>
<td>
<?php
$letter=($this->content[$i]['letter']==-1)?" ":$this->content[$i]['letter'];
?>
<?=$letter?>
</td>
<td> <?=$this->content[$i]['j_date_fmt']?> </td>
<?php
$r=sprintf('<A class="detail" style="text-decoration:underline"  HREF="javascript:modifyOperation(\'%s\',\'%s\',\'%s\',\'%s\',\'%s\')" >%s</A>',
	     $this->content[$i]['jr_id'], $_REQUEST['PHPSESSID'],dossier::id(), $this->content[$i]['jr_def_id'],'S',  $this->content[$i]['jr_internal']);
?>
<td> <?=$r?> </td>
<td> <?=$this->content[$i]['jr_comment']?> </td>
<td> <?=$this->content[$i]['j_montant']?> </td>
<td> <?=($this->content[$i]['j_debit']=='t')?'D':'C'?> </td>
</tr>

<?php
  $amount_deb+=( $jnt_id == $this->content[$i]['letter'] && $this->content[$i]['j_debit']=='t')?$this->content[$i]['j_montant']:0;
  $amount_cred+=( $jnt_id == $this->content[$i]['letter'] && $this->content[$i]['j_debit']=='f')?$this->content[$i]['j_montant']:0;

    endfor;
?>
</TABLE>
<span style="display:block;font-size:14px"><?=_('Total Debit')?>   <?=$amount_deb?></span>
<span style="display:block;font-size:14px"><?=_('Total Credit')?>   <?=$amount_cred?></span>

</table>