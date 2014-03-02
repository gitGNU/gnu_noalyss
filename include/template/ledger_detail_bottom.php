<?php
    $cmd=new IText('BON_COMMANDE',$obj->info->command);
    $other=new IText('OTHER',$obj->info->other);
?>
<div class="myfieldset">
    <h1 class="legend">"<?php echo _("Informations")?></h1>
    <table>
        <tr>
            <td><?php echo _(" Bon de commande")?>   :</td><td> <?php echo HtmlInput::infobulle(31)." ".$cmd->input();  ?></td>
        </tr>
        <tr>
            <td> <?php echo _("Autre information")?> : </td><td><?php echo HtmlInput::infobulle(30)." ".$other->input();?></td>
        </tr>
    </table>
</div>
<div class="myfieldset">
<h1 class="legend">
<?php echo _('Rapprochement');?>
</h1>
<?php 
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
	$comment=strip_tags($array_jr[0]['jr_comment']);
    echo tr (td('<a class="line" href="javascript:void(0)" onclick="'.$str.'" >'.$internal.'</A>').td(nbm($amount)).td($comment).td($remove));
  }
  echo '</table>';
}
?>
<?php 
if ( $access=='W') {
     $wConcerned=new IConcerned("rapt".$div);
     $wConcerned->amount_id=$obj->det->jr_montant;
    echo $wConcerned->input();

}
?>
</div>
<?php 
$array = Follow_Up::get_all_operation($jr_id);
if (count($array) > 0)
{
	?>
	<div class="myfieldset">
		<h1 class="legend">Actions liées</h1>
	<?php 
	/**
	 * show eventually action
	 */
	$array = Follow_Up::get_all_operation($jr_id);
	echo '<ul style="list-style-type:square;">';
	for ($i = 0; $i < count($array); $i++)
	{
		if ( $div == 'popup')
		{
			echo '<li>'.HtmlInput::detail_action($array[$i]['ag_id'], h($array[$i]['ag_ref']." ".$array[$i]['ag_title']),0).'</li>';
		}
		else
		{
			echo '<li>'.HtmlInput::detail_action($array[$i]['ag_id'], h($array[$i]['ag_ref']." ".$array[$i]['ag_title']),1).'</li>';
		}
	}
	echo '</ul>';

	echo '</div>';
}
?>

<?php 

require_once('template/ledger_detail_file.php');
?>
<hr>
<?php 

if ( $div != 'popup' ) {
  $a=new IButton('Fermer',_('Fermer'));
  $a->label=_("Fermer");
  $a->javascript="removeDiv('".$div."')";
  echo $a->input();
}

?>
<?php 

/**
 * if you can write
 */
  if ( $access=='W') {
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
