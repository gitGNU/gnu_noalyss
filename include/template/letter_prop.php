<?php
require_once ('class_acc_operation.php');
require_once ('class_acc_reconciliation.php');

$gDossier=dossier::id();
if ( count($this->content) == 0 ) :
?>
  <h2 class="info2"><?=_('Désolé aucun résultat trouvé')?></h2>

    <?php
  else :
$delta=0;
?>
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
<th style="text-align:right">
   <?=_('Débit')?>
</th>
<th style="text-align:right">
   <?=_('Crédit')?>
</th>
<th style="text-align:center">
  <?=_('Op. concernée')?>
</th>
</tr>

<?php
$this->content=array_merge($this->linked,$this->content);
$amount_deb=($j_debit=='t')?$amount_init:0;
$amount_cred=($j_debit=='f')?$amount_init:0;

$linked_limit=count($this->linked);

for ($i=0;$i<count($this->content);$i++):
  $class="";
if ( ($i % 2) == 0 ) $class="odd";
if ( $i < $linked_limit ) $class="even";
if ($linked_limit != 0 && $i==$linked_limit)
{
	?>
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
<th style="text-align:right">
   <?=_('Débit')?>
</th>
<th style="text-align:right">
   <?=_('Crédit')?>
</th>
<th style="text-align:center">
  <?=_('Op. concernée')?>
</th>
</tr>
<?

}
?>
  <tr <? echo "class=\"$class\""; ?> >
<td>
<?php

   if ($this->content[$i]['j_id']==$p_jid) continue;
if ( $jnt_id == $this->content[$i]['letter'] && $i >= $linked_limit) continue;

$check=new ICheckbox('ck[]',$this->content[$i]['j_id']);
if ( $jnt_id == $this->content[$i]['letter'] && $i < $linked_limit) $check->selected=true; else $check->selected=false;

if ( $this->content[$i]['letter'] < 0 ||  $check->selected == true )
	echo $check->input();
echo HtmlInput::hidden('letter_j_id[]',$this->content[$i]['j_id']);
?>
</td>
<td>
<?php
$letter=($this->content[$i]['letter']< 0)?" ":strtoupper(base_convert($this->content[$i]['letter'],10,36));
?>
<?=$letter?>
</td>
<td> <?=smaller_date($this->content[$i]['j_date_fmt'])?> </td>
<?php
$r=sprintf('<A class="detail" style="text-decoration:underline"  HREF="javascript:viewOperation(\'%s\',\'%s\')" >%s</A>',
	   $this->content[$i]['jr_id'], $gDossier,  $this->content[$i]['jr_internal']);
?>
<td> <?=$r?> </td>
<td> <?=$this->content[$i]['jr_comment']?> </td>
 <? if ($this->content[$i]['j_debit']=='t') : ?>
  <td style="text-align:right"> <?=nb($this->content[$i]['j_montant'])?> </td>
  <td></td>
  <? else : ?>
  <td></td>
  <td style="text-align:right"> <?=nb($this->content[$i]['j_montant'])?> </td>
  <? endif ?>
<td style="text-align:center">
<?php
    // Rapprochement
    $rec=new Acc_Reconciliation($this->db);
    $rec->set_jr_id($this->content[$i]['jr_id']);
    $a=$rec->get();
    if ( $a != null ) {
      foreach ($a as $key => $element)
      {
	$operation=new Acc_Operation($this->db);
	$operation->jr_id=$element;
	$l_amount=$this->db->get_value("select jr_montant from jrn ".
					 " where jr_id=$element");
	echo "<A class=\"detail\" HREF=\"javascript:viewOperation('".$element."',".$gDossier.")\" > ".$operation->get_internal()." [ $l_amount &euro; ]</A>";
      }//for
    }// if ( $a != null ) {

?>
</td>

</tr>
<?php
if ($i<$linked_limit)
{
  $amount_deb+=( $jnt_id == $this->content[$i]['letter'] && $this->content[$i]['j_debit']=='t')?$this->content[$i]['j_montant']:0;
  $amount_cred+=( $jnt_id == $this->content[$i]['letter'] && $this->content[$i]['j_debit']=='f')?$this->content[$i]['j_montant']:0;
}
    endfor;
$delta = bcsub($amount_deb, $amount_cred);
$side = 'Créditeur';
if ($delta < 0 ) {
$side = "Débiteur";
$delta = abs($delta);
}
?>
</TABLE>
  <h2 class="info"> Total lettré</h2>
<span style="display:block;font-size:14px"><?=_('Total Debit')?>   <?=$amount_deb?></span>
<span style="display:block;font-size:14px"><?=_('Total Credit')?>   <?=$amount_cred?></span>
<span style="display:block;font-size:14px"><?=_('Total ').$side?>   <?=$delta?></span>

<?php endif;?>
<?=HtmlInput::button('check_all','Sélectionner tout',' onclick="select_checkbox(\'letter_form\')"');?>
<?=HtmlInput::button('check_none','Tout Désélectionner ',' onclick="unselect_checkbox(\'letter_form\')"');?>