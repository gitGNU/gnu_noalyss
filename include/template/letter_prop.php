<?php
require_once ('class_acc_operation.php');
require_once ('class_acc_reconciliation.php');

$gDossier=dossier::id();

if ( count($this->content) == 0 ) :
?>
  <h2 class="info2"><?=_('Désolé aucun résultat trouvé')?></h2>

<?     
  else :
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
<th>
   <?=_('Montant')?>
</th>
<th>
   <?=_('Debit / Credit')?>
</th>
<th>
  <?=_('Op. concerné')?>
</th>
</tr>

<?php
   $amount_deb=($j_debit=='t')?$amount_init:0;
$amount_cred=($j_debit=='f')?$amount_init:0;
for ($i=0;$i<count($this->content);$i++):
  $class="";
if ( ($i % 2) == 0 ) $class="odd";
?>
  <tr <? echo "class=\"$class\""; ?> >
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
$r=sprintf('<A class="detail" style="text-decoration:underline"  HREF="javascript:viewOperation(\'%s\',\'%s\')" >%s</A>',
	   $this->content[$i]['jr_id'], $gDossier,  $this->content[$i]['jr_internal']);
?>
<td> <?=$r?> </td>
<td> <?=$this->content[$i]['jr_comment']?> </td>
<td> <?=$this->content[$i]['j_montant']?> </td>
<td> <?=($this->content[$i]['j_debit']=='t')?'D':'C'?> </td>
<td>
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
  $amount_deb+=( $jnt_id == $this->content[$i]['letter'] && $this->content[$i]['j_debit']=='t')?$this->content[$i]['j_montant']:0;
  $amount_cred+=( $jnt_id == $this->content[$i]['letter'] && $this->content[$i]['j_debit']=='f')?$this->content[$i]['j_montant']:0;

    endfor;
?>
</TABLE>
<span style="display:block;font-size:14px"><?=_('Total Debit')?>   <?=$amount_deb?></span>
<span style="display:block;font-size:14px"><?=_('Total Credit')?>   <?=$amount_cred?></span>

</table>
<?php endif;?>
