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
    $array_jr=$cn->get_array('select jr_montant,jr_comment from jrn where jr_id=$1',array($aRap[$e]));
    $amount=$array_jr[0]['jr_montant'];
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
    echo tr (td('<a class="line" href="javascript:void(0)" onclick="'.$str.'" >'.$internal.'</A>').td(nbm($amount)).td($array_jr[0]['jr_comment']).td($remove));
  }
  echo '</table>';
}
?>
</legend>
<?
if ( $access=='W') {
  $search='<INPUT TYPE="BUTTON" class="button" VALUE="Cherche" OnClick="search_reconcile('.$gDossier.",'rapt".$div."','".$obj->det->jr_montant."')\">";
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
  echo HtmlInput::submit('save',_('Sauver'),'onClick="return verify_ca(\'popup\');"');
  $owner=new Own($cn);
  if ($owner->MY_ANALYTIC != 'nu' && $div=='popup'){
    echo '<input type="button" class="button" value="'._('verifie CA').'" onClick="verify_ca(\'popup\');">';
  }

  $per=new Periode($cn,$obj->det->jr_tech_per);
  if ( $per->is_closed() == 0 && $owner->MY_STRICT=='N'){
    $remove=new IButton('Effacer');
    $remove->label=_('Effacer');
    $remove->javascript="if ( confirm('Vous confirmez effacement ?')) {removeOperation('".$obj->det->jr_id."',".dossier::id().",'".$div."')}";
    echo $remove->input();
  }

  $reverse=new IButton('bext'.$div);
  $reverse->label=_('Extourner');
  $reverse->javascript="g('ext".$div."').style.display='block'";
  echo $reverse->input();

echo '</form>';

  echo '<div id="ext'.$div.'" style="display:none">';
  $date=new IDate('ext_date');
  $r="<form id=\"form_".$div."\" onsubmit=\"this.divname='$div';return reverseOperation(this);\">";
  $r.=HtmlInput::hidden('jr_id',$_REQUEST['jr_id']).HtmlInput::hidden('div',$div).dossier::hidden().HtmlInput::hidden('act','reverseop');
  $r.='<h2 class="info">Extourner </H2>';
  $r.="entrez une date :".$date->input();
  $r.=HtmlInput::submit('x','accepter','onclick="return confirm(\'Vous confirmez  ? \');"');
  $r.='</form>';
  echo $r;
  echo '</div>';



}
?>
