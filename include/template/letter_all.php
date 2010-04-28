<?php
require_once ('class_acc_operation.php');
require_once ('class_acc_reconciliation.php');

$gDossier=dossier::id();
$l_sessid=$_REQUEST['PHPSESSID'];

if ( count($this->content) == 0 ) :
?>
  <h2 class="info2"><?=_('Désolé aucun résultat trouvé')?></h2>

<?      exit();
endif;?>
<table class="result">
<tr>
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
for ($i=0;$i<count($this->content);$i++):
?>
<tr>
<td> 
<?php
$letter=($this->content[$i]['letter']==-1)?"x":$this->content[$i]['letter'];
$js="this.gDossier=".dossier::id().
  ";this.phpsessid='".$_REQUEST['PHPSESSID']."'".
  ";this.j_id=".$this->content[$i]['j_id'].
  ";this.obj_type='".$this->object_type."'".
  ";dsp_letter(this)";

?> 
<A class="detail" href="javascript:<?=$js?>"><?=$letter?></A>
</td>
<td> <?=$this->content[$i]['j_date_fmt']?> </td>
<?php
$r=sprintf('<A class="detail" style="text-decoration:underline" HREF="javascript:modifyOperation(\'%s\',\'%s\',\'%s\',\'%s\',\'%s\')" >%s</A>',
	     $this->content[$i]['jr_id'], $l_sessid,$gDossier, $this->content[$i]['jr_def_id'],'S',  $this->content[$i]['jr_internal']);
?>
  <td> <?=$r?> </td>
  <td> <?=h($this->content[$i]['jr_comment'])?> </td>
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
	echo "<A class=\"detail\" HREF=\"javascript:modifyOperation('".$element."','".$l_sessid."',".$gDossier.")\" > ".$operation->get_internal()." [ $l_amount &euro; ]</A>";
      }//for
    }// if ( $a != null ) {

?>
</td>
</tr>

<?php
    endfor;
?>
</table>
