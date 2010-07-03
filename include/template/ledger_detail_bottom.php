<fieldset>
<legend>
<?
echo _('Rapprochement');
$oRap=new Acc_Reconciliation($cn);
$oRap->jr_id=$jr_id;
$aRap=$oRap->get();
if ($aRap  != null ) {
  $tableid="tb".$div;
  echo '<table id="'.$tableid.'">';
  for ($e=0;$e<count($aRap);$e++)  {
    $opRap=new Acc_Operation($cn);
    $opRap->jr_id=$aRap[$e];
    $internal=$opRap->get_internal();
    $amount=$cn->get_value('select jr_montant from jrn where jr_id=$1',array($aRap[$e]));
    $str="modifyOperation(".$aRap[$e].",".$gDossier.")";
    $rmReconciliation=new IButton('rmr');
    $rmReconciliation->label='enlever';
    $rmReconciliation->javascript="if (confirm ('vous confirmez?') ) {";
    $rmReconciliation->javascript.=sprintf('dropLink(\'%s\',\'%s\',\'%s\',\'%s\');deleteRowRec(\'%s\',this);}',
					  $gDossier,
					  $div,
					  $jr_id,
					   $aRap[$e],
					   $tableid
					  );
    if ( $access=='W') 
      $remove=$rmReconciliation->input();
    else 
      $remove='';
    echo tr (td('<a href="javascript:void(0)" onclick="'.$str.'" >'.$internal.'</A>').td($amount).td($remove));
  }
  echo '</table>';
}
?>
</legend>
<?
if ( $access=='W') {
  $search='<INPUT TYPE="BUTTON" class="button" VALUE="Cherche" OnClick="SearchJrn('.$gDossier.",'rapt".$div."','".$obj->det->jr_montant."')\">";
  $rapt=new IText('rapt'.$div);
  echo $rapt->input().$search;
}
?>
</fieldset>
<?
 require_once('template/ledger_detail_file.php'); 
?>
<hr>
<? 

if ( $div != 'popup' ) {
  $a=new IButton('Fermer','Fermer');
  $a->label="Fermer";
  $a->javascript="removeDiv('".$div."')";
  echo $a->input();
}
?>
<?if ( $access=='W') { 
  echo HtmlInput::submit('save',_('Sauver')); 
  $owner=new Own($cn);
  $per=new Periode($cn,$obj->det->jr_tech_per);
  if ( $per->is_closed() == 0 && $owner->MY_STRICT=='N'){
    $remove=new IButton('Effacer');
    $remove->label=_('Effacer');
    $remove->javascript="if ( confirm('Vous confirmez effacement ?')) {removeOperation('".$obj->det->jr_id."',".dossier::id().",'".$div."')}";
    echo $remove->input();
  }
  $reverse=new IBox('Extourne'.$div,'Extourne');
  $reverse->html='';
  $reverse->cssclass="op_detail";
  $reverse->style="height:300;width:250;position:absolute;";
  $reverse->callback='ajax_ledger.php';
  $reverse->queryString='?'.dossier::get().'&div=Extourne'.$div.'&parent_div='.$div.'&act=ask_extdate'.'&jr_id='.$obj->det->jr_id;
  $reverse->set_attribute('drag',1);
  echo $reverse->input();

  echo '</form>';
}
?>
