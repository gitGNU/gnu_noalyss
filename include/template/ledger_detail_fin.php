
<div style="text-align:right">
<? 
echo '<A style="background-color:blue;color:white;text-decoration:none" HREF="#" onclick="removeDiv(\''.$div.'\');">Fermer</A>'; 
?>
</div>
<div class="content">
<h2> <? echo $oLedger->get_name(); ?></h2>
<?
$bk=new Fiche($cn,$obj->det->array[0]['qf_bank']);
echo $bk->getName();
echo $bk->get_quick_code();
?>
<table>
<tr>
<? echo td('Date').td($obj->det->jr_date);?>
</tr>
<tr>
<?
 
$bk=new Fiche($cn,$obj->det->array[0]['qf_other']);
echo td($bk->getName());
echo td($bk->get_quick_code());
?>
<tr>
<? echo td('Libellé').td($obj->det->jr_comment);?>
</tr>
<tr>
<? echo td('montant').td($obj->det->array[0]['qf_amount'],' class="inum"');?>
</tr>

</table>
<?
 require_once('template/ledger_detail_file.php'); 
?>

</div>