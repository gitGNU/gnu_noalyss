<? require_once('template/ledger_detail_top.php'); ?>
<div class="content" style="padding:0;">
<?
  require_once('class_own.php');
  $owner=new Own($cn);
?>

    <? if ( $access=='W') : ?>
<form  class="print" onsubmit="return op_save(this);">
   <? endif; ?>

    <? echo HtmlInput::hidden('whatdiv',$div).HtmlInput::hidden('jr_id',$jr_id).dossier::hidden();?>
    <table style="width:100%"><tr><td>
<table>
<tr>
<?php
$date=new IDate('p_date');
$date->value=format_date($obj->det->jr_date);
 echo td('Date').td($date->input());
 
 ?>

</tr>

<tr>
<?
$bk=new Fiche($cn,$obj->det->array[0]['qp_supplier']);
echo td(_('Client'));

$view_history= sprintf('<A class="detail" HREF="javascript:view_history_card(\'%s\',\'%s\')" >%s</A>',
				$bk->id, $gDossier, $bk->get_quick_code());
echo td(h($bk->getName())).td($view_history);;
?>
</tr>
<tr>
<? 
$itext=new IText('npj');
$itext->value=$obj->det->jr_pj_number;
echo td(_('Pièce')).td($itext->input());
?>

<tr>
<? 
  $itext=new IText('lib');
  $itext->value=$obj->det->jr_comment;
  $itext->size=40;
echo td(_('Libellé')).td($itext->input(),' colspan="2" ');


?>
</tr>
</table>
</td>
<td>
<table style="border:solid 1px yellow">
<tr>
<td>
	Note
</td></tr>
<tr>
<td>
<?
$inote=new ITextarea('jrn_note');
$inote->width=25;
$inote->heigh=5;
$inote->value=$obj->det->note;
echo $inote->input();
?>

</td>
</tr>
</table>
</td>
</tr>
</table>

<fieldset><legend><?=_('Détail')?></legend>
<table class="result">
<?
  bcscale(2);
  $total_htva=0;$total_tvac=0;
  echo th(_('Quick Code'));
echo th(_('Description'));
if ( $owner->MY_TVA_USE == 'Y') {
  echo th(_('Taux TVA'), 'style="text-align:right"');
} else {
  echo th('');
}
echo th(_('Quantité'), 'style="text-align:right"');
if ( $owner->MY_TVA_USE == 'Y') {
  echo th(_('HTVA'), 'style="text-align:right"');
  echo th(_('TVA'), 'style="text-align:right"');
  echo th(_('TVAC'), 'style="text-align:right"');
}else 
  echo th(_('Total'), 'style="text-align:right"');

    if ($owner->MY_ANALYTIC != 'nu' && $div=='popup'){
      $anc=new Anc_Plan($cn);
      $a_anc=$anc->get_list();
      $x=count($a_anc);
      /* set the width of the col */
      echo '<th colspan="'.$x.'">'._('Compt. Analytique').'</th>';

      /* add hidden variables pa[] to hold the value of pa_id */
      echo Anc_Plan::hidden($a_anc);
    }
echo '</tr>';  
  for ($e=0;$e<count($obj->det->array);$e++) {
    $row=''; 
    $q=$obj->det->array[$e];
    $fiche=new Fiche($cn,$q['qp_fiche']);
   $view_history= sprintf('<A class="detail" style="text-decoration:underline" HREF="javascript:view_history_card(\'%s\',\'%s\')" >%s</A>',
				$fiche->id, $gDossier, $fiche->strAttribut(ATTR_DEF_QUICKCODE));

   $row=td($view_history);
   $sym_tva='';
   if ( $owner->MY_TVA_USE=='Y') {
     /* retrieve TVA symbol */
     $tva=new Acc_Tva($cn,$q['qp_vat_code']);
     $tva->load();
     $sym_tva=h($tva->get_parameter('label'));
   }
    $row.=td($fiche->strAttribut(ATTR_DEF_NAME));
    $row.=td($sym_tva,'style="text-align:center"');
    $row.=td(nbm($q['qp_quantite']),'class="num"');
    $htva=$q['qp_price'];
    $row.=td(nbm($htva),'class="num"');
    $tvac=bcadd($htva,$q['qp_vat']);
    if ($owner->MY_TVA_USE=='Y')
      {
	$row.=td(nbm($q['qp_vat']),'class="num"');
	$row.=td(nbm($tvac),'class="num"');
      }
    $total_tvac+=$tvac;
    $total_htva+=$htva;
    /* Analytic accountancy */
    if ( $owner->MY_ANALYTIC != "nu" && $div == 'popup'){
      $poste=$fiche->strAttribut(ATTR_DEF_ACCOUNT);
      if ( preg_match('/^(6|7)/',$poste)) {
	$anc_op=new Anc_Operation($cn);
	$anc_op->j_id=$q['j_id'];
	echo HtmlInput::hidden('op[]',$anc_op->j_id);
	/* compute total price */
	bcscale(2);
	
	$row.=$anc_op->display_table(1,$htva,$div);
	
      }  else {
	$row.=td('');
      }
    }
    echo tr($row);

  }
$row= td(_('Total'),' style="font-style:italic;text-align:right;font-weight: bolder;" colspan="5"');
$row.=td(nbm($total_htva),'class="num" style="font-style:italic;font-weight: bolder;"');
if ($owner->MY_TVA_USE=='Y')
  $row.=td(nbm($total_tvac),'class="num" style="font-style:italic;font-weight: bolder;"');
echo tr($row);
?>
</table>


</fieldset>
<fieldset>
<legend>
<?=_('Ecritures comptables')?>
</legend>

<? 
  /* if it is not in a popup, the details are hidden */
  if ( $div != 'popup') {
    $ib=new IButton ("a".$div);
    $ib->label='Afficher';
    $ib->javascript="g('detail_".$div."').style.display='block';g('a".$div."').style.display='none';";
    echo $ib->input();
    echo '<div id="detail_'.$div.'" class="content" style="display:none">';
    $ib=new IButton ("h".$div);
    $ib->label='Cacher';
    $ib->javascript="g('detail_".$div."').style.display='none';g('a".$div."').style.display='block';";
    echo $ib->input();
  } else 
    echo '<div class="content">';
$detail=new Acc_Misc($cn,$obj->jr_id);
$detail->get();
?>
<table class="result">
<tr>
<?
 echo th(_('Poste Comptable'));
    echo th(_('Quick Code'));
    echo th(_('Libellé'));
echo th(_('Débit'),' style="text-align:right"');
echo th(_('Crédit'),' style="text-align:right"');
echo '</tr>';
  for ($e=0;$e<count($detail->det->array);$e++) {
    $row=''; $q=$detail->det->array;
   $view_history= sprintf('<A class="detail" style="text-decoration:underline" HREF="javascript:view_history_account(\'%s\',\'%s\')" >%s</A>',
			   $q[$e]['j_poste'], $gDossier, $q[$e]['j_poste']);

    $row.=td($view_history);
    if ( $q[$e]['j_qcode'] !=''){
      $fiche=new Fiche($cn);
      $fiche->get_by_qcode($q[$e]['j_qcode']);
      $view_history= sprintf('<A class="detail" style="text-decoration:underline" HREF="javascript:view_history_card(\'%s\',\'%s\')" >%s</A>',
			     $fiche->id,$gDossier, $q[$e]['j_qcode']);
    }
    else
      $view_history='';
    $row.=td($view_history);
    /* $row=td($q[$e]['j_poste']); */
    /* $row.=td($q[$e]['j_qcode']); */
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
    $montant=td(nbm($q[$e]['j_montant']),'class="num"');
    $row.=($q[$e]['j_debit']=='t')?$montant:td('');
    $row.=($q[$e]['j_debit']=='f')?$montant:td('');
    echo tr($row);

  }
?>
</table>
</div>
</fieldset>

<?
require_once('ledger_detail_bottom.php');
?>
</div>
