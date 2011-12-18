<?
require_once('template/ledger_detail_top.php');
require_once('class_anc_operation.php');
require_once('class_anc_plan.php');

?>
<?
require_once('class_own.php');
require_once ('class_anc_plan.php');
?>
<div class="content" style="padding:0">

    <? if ( $access=='W') : ?>
<form class="print" onsubmit="return op_save(this);">
   <? endif; ?>

    <? echo HtmlInput::hidden('whatdiv',$div).HtmlInput::hidden('jr_id',$jr_id).dossier::hidden();?>
  <table style="width:100%"><tr><td>
					<table>
								<tr><td>
								<?php
								$date=new IDate('p_date');
								$date->value=format_date($obj->det->jr_date);
								 echo td('Date').td($date->input());

								 ?>
								</td>
								</tr>

								<tr><td>
								<?
								  $itext=new IText('lib');
								  $itext->value=strip_tags($obj->det->jr_comment);
								  $itext->size=40;
								  echo td(_('Libellé')).td($itext->input());


								?>
								</td></tr>
								<tr><td>
								<? echo td('montant').td(nbm($obj->det->jr_montant),' class="inum"');?>
								</td></tr>
								<tr><td>
								<?
								$itext=new IText('npj');
								$itext->value=strip_tags($obj->det->jr_pj_number);
								echo td(_('Pièce')).td($itext->input());
								?>

								</td></tr>
			</table>
			</td><td>
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
						$inote->value=strip_tags($obj->det->note);
						echo $inote->input();
						?>

						</td>
						</tr>
						</table>
</td>
</tr>
</table>

<fieldset>
<legend>
<?=_('Détail')?>
<?
  require_once('class_own.php');
  $owner=new Own($cn);
?>
</legend>
<table class="result">
<tr>
<?
    echo th(_('Poste Comptable'));
    echo th(_('Quick Code'));
    echo th(_('Libellé'));
echo th(_('Débit'), 'style="text-align:right"');
echo th(_('Crédit'), 'style="text-align:right"');
    if ($owner->MY_ANALYTIC != 'nu' && $div == 'popup'){
      $anc=new Anc_Plan($cn);
      $a_anc=$anc->get_list(' order by pa_id ');
      $x=count($a_anc);
      /* set the width of the col */
      echo '<th colspan="'.$x.'">'._('Compt. Analytique').'</th>';

      /* add hidden variables pa[] to hold the value of pa_id */
      echo Anc_Plan::hidden($a_anc);
    }
echo '</tr>';
  for ($e=0;$e<count($obj->det->array);$e++) {
    $row=''; $q=$obj->det->array;
    $view_history= sprintf('<A class="detail" style="text-decoration:underline" HREF="javascript:view_history_account(\'%s\',\'%s\')" >%s</A>',
			   $q[$e]['j_poste'], $gDossier, $q[$e]['j_poste']);

    $row.=td($view_history);

    if ( $q[$e]['j_qcode'] !='') {
      $fiche=new Fiche($cn);
      $fiche->get_by_qcode($q[$e]['j_qcode']);
      $view_history= sprintf('<A class="detail" style="text-decoration:underline" HREF="javascript:view_history_card(\'%s\',\'%s\')" >%s</A>',
			   $fiche->id, $gDossier, $q[$e]['j_qcode']);
    }
    else
      $view_history='';
    $row.=td($view_history);
	if ( $q[$e]['j_text']!='')
	{
	 $row.=td(h(strip_tags($q[$e]['j_text'])));
	}else
    if ( $q[$e]['j_qcode'] !='') {
      // nom de la fiche
      $ff=new Fiche($cn);
      $ff->get_by_qcode( $q[$e]['j_qcode']);
      $row.=td(h($ff->strAttribut(ATTR_DEF_NAME)));
    } else {
      // libellé du compte
      $name=$cn->get_value('select pcm_lib from tmp_pcmn where pcm_val=$1',array($q[$e]['j_poste']));
      $row.=td(h($name));
    }
    $montant=td(nbm($q[$e]['j_montant']),'class="num"');
    $row.=($q[$e]['j_debit']=='t')?$montant:td('');
    $row.=($q[$e]['j_debit']=='f')?$montant:td('');
    /* Analytic accountancy */
    if ( $owner->MY_ANALYTIC != "nu" && $div=='popup'){
      if ( preg_match('/^(6|7)/',$q[$e]['j_poste'])) {

echo HtmlInput::hidden("amount_t".$e,$montant);
	$anc_op=new Anc_Operation($cn);
	$anc_op->j_id=$q[$e]['j_id'];
	echo HtmlInput::hidden('op[]',$anc_op->j_id);
	$row.=$anc_op->display_table(1,$q[$e]['j_montant'],$div);

      }  else {
	$row.=td('');
      }
    }
    echo tr($row);

  }
?>
</table>
</fieldset>

<?
require_once('ledger_detail_bottom.php');
?>
</div>
