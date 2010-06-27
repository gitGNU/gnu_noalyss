<? require_once('template/ledger_detail_top.php'); ?>
<div class="content" style="padding:0">

    <? if ( $access=='W') : ?>
<form  onsubmit="return op_save(this);">
   <? endif; ?>

    <? echo HtmlInput::hidden('whatdiv',$div).HtmlInput::hidden('jr_id',$jr_id).dossier::hidden();?>
<table>
<tr>
<? echo td('Date').td($obj->det->jr_date);?>
</tr>

<tr>
<?
$bk=new Fiche($cn,$obj->det->array[0]['qf_bank']);
echo td($bk->get_quick_code());
/**
 *@file
 *@todo ajouter la possiblité d'avoir l'historique si on cliques sur le quick_code
 *@quant_fin les opérations avec visa ne sont pas rentrées dans quant_fin, donc il faut
 * faire une procédure pl/sql : chercher les enregistrements qui ne sont pas dans quant_fin
 * et celui qui est ni client ni fournisseur est forcément le compte banque, insérer. 
 *@todo Ajouter une clef unique sur quant_fin.jr_id, quant_purchase.j_id et quant_sold.j_id
 */
echo td(h($bk->getName()));
?>
</tr>
<tr>
<?
 
$bk=new Fiche($cn,$obj->det->array[0]['qf_other']);

echo td($bk->get_quick_code());
echo td(h($bk->getName()));
?>
</tr>

<tr>
<? 
  $itext=new IText('lib');
  $itext->value=$obj->det->jr_comment;
  $itext->size=40;
  echo td(_('Libellé')).td($itext->input());


?>
</tr>
<tr>
<? echo td('montant').td($obj->det->array[0]['qf_amount'],' class="inum"');?>
</tr>
<tr>
<? 
$itext=new IText('npj');
$itext->value=$obj->det->jr_pj_number;
echo td(_('Pièce')).td($itext->input());
?>

</tr>
</table>
<fieldset>
<legend>
<?=_('Détail')?>
<? 
  $detail=new Acc_Misc($cn,$obj->jr_id);
$detail->get();
?>
</legend>
<table class="result">
<?
  for ($e=0;$e<count($detail->det->array);$e++) {
    $row=''; $q=$detail->det->array;
    $row=td($q[$e]['j_poste']);
    $row.=td($q[$e]['j_qcode']);
    if ( $q[$e]['j_qcode'] !='') {
      // nom de la fiche 
      $ff=new Fiche($cn);
      $ff->get_by_qcode( $q[$e]['j_qcode']);
      $row.=td($ff->strAttribut(h(ATTR_DEF_NAME)));
    } else {
      // libellé du compte
      $name=$cn->get_value('select pcm_lib from tmp_pcmn where pcm_val=$1',array($q[$e]['j_poste']));
      $row.=td(h($name));
    }
    $montant=td($q[$e]['j_montant'],'class="num"');
    $row.=($q[$e]['j_debit']=='t')?$montant:td('');
    $row.=($q[$e]['j_debit']=='f')?$montant:td('');
    echo tr($row);

  }
?>
</table>
</fieldset>
<?
require_once('ledger_detail_bottom.php');
?>
</div>