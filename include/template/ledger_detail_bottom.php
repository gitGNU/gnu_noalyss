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
    echo tr (td('<a href="#" onclick="'.$str.'" >'.$internal.'</A>').td($amount).td($remove));
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
<? 
$a=new IButton('Fermer','Fermer');
$a->label="Fermer";
$a->javascript="removeDiv('".$div."')";
echo $a->input();
?>
<?if ( $access=='W') { 
  echo HtmlInput::submit('save','Sauver'); 
  echo '</form>';
}
?>
