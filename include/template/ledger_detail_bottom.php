<fieldset>
<legend>
<?
echo _('Rapprochement');
$oRap=new Acc_Reconciliation($cn);
$oRap->jr_id=$jr_id;
$aRap=$oRap->get();
if ($aRap  != null ) {
  echo '<table>';
  echo '<tr>';
  for ($e=0;$e<count($aRap);$e++)  {
    $opRap=new Acc_Operation($cn);
    $opRap->jr_id=$aRap[$e];
    $internal=$opRap->get_internal();
    $amount=$cn->get_value('select jr_montant from jrn where jr_id=$1',array($aRap[$e]));
    $str="modifyOperation(".$aRap[$e].",".$gDossier.")";

    echo td('<a href="#" onclick="'.$str.'" >'.$internal.'</A>').td($amount);
  }
  echo '</tr>';
  echo '</table>';
}
?>
</legend>
<?
$search='<INPUT TYPE="BUTTON" class="button" VALUE="Cherche" OnClick="SearchJrn('.$gDossier.",'rapt','".$obj->det->jr_montant."')\">";
$rapt=new IText('rapt');
echo $rapt->input().$search;
?>
</fieldset>
<? 
$a=new IButton('Fermer','Fermer');
$a->label="Fermer";
$a->javascript="removeDiv('".$div."')";
echo $a->input();
?>
<? echo HtmlInput::submit('Sauver','save'); ?>
</form>
<?
 require_once('template/ledger_detail_file.php'); 
?>