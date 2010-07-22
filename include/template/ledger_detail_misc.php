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
<form  onsubmit="return op_save(this);">
   <? endif; ?>

    <? echo HtmlInput::hidden('whatdiv',$div).HtmlInput::hidden('jr_id',$jr_id).dossier::hidden();?>
<table>
<tr>
<? echo td('Date').td(format_date($obj->det->jr_date));?>
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
<? echo td('montant').td($obj->det->jr_montant,' class="inum"');?>
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
      $a_anc=$anc->get_list();
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
    $montant=td($q[$e]['j_montant'],'class="num"');
    $row.=($q[$e]['j_debit']=='t')?$montant:td('');
    $row.=($q[$e]['j_debit']=='f')?$montant:td('');
    /* Analytic accountancy */
    if ( $owner->MY_ANALYTIC != "nu" && $div=='popup'){
      if ( preg_match('/^(6|7)/',$q[$e]['j_poste'])) {
	

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
