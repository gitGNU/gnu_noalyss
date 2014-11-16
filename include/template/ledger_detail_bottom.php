<?php
/**
//This file is part of NOALYSS and is under GPL 
//see licence.txt
*/
/**
 * @brief show the common parts of operation details 
 * 
 * Variables : $div = popup or box (det[0-9]
 * 
 */
$a_tab['writing_div']=array('id'=>'writing_div'.$div,'label'=>_('Ecriture Comptable'),'display'=>'none');
$a_tab['info_operation_div']=array('id'=>'info_operation_div'.$div,'label'=>_('Information'),'display'=>'none');
$a_tab['linked_operation_div']=array('id'=>'linked_operation_div'.$div,'label'=>_('Opérations liées'),'display'=>'none');
$a_tab['document_operation_div']=array('id'=>'document_operation_div'.$div,'label'=>_('Document'),'display'=>'block');
$a_tab['linked_action_div']=array('id'=>'linked_action_div'.$div,'label'=>_('Actions liées'),'display'=>'none');

// show tabs
if ( $div != "popup") :
 $a_tab['document_operation_div']['display']='block';
?>
<ul  class="tabs">
    <?php foreach ($a_tab as $idx=>$a_value): ?>
    <?php 
        $class=($a_value['display']=='block') ?"tabs_selected":"tabs"
    ?>
    <li class="<?php echo $class?>">
        <?php $div_tab_id=$a_value['id'];?>
        <a href="javascript:void(0)" onclick="unselect_other_tab(this.parentNode.parentNode);var tab=Array('writing_div<?php echo $div?>','info_operation_div<?php echo $div?>','linked_operation_div<?php echo $div?>','document_operation_div<?php echo $div?>','linked_action_div<?php echo $div?>');this.parentNode.className='tabs_selected' ;show_tabs(tab,'<?php echo $div_tab_id; ?>');"><?php echo $a_value['label']?></a>
    </li>
    <?php    endforeach; ?>
</ul>
<?php
else :
    foreach ($a_tab as $idx=>$a_value):
    $a_tab[$idx]['display']='block';
    endforeach; 
endif;
?>


<?php
    $cmd=new IText('BON_COMMANDE',$obj->info->command);
    $other=new IText('OTHER',$obj->info->other);
?>
        <div id="writing_div<?php echo $div;?>" class="myfieldset" style="display:<?php echo $a_tab['writing_div']['display']?>">
          <?php 
          // display title only in popup
          if ($div == 'popup') :
          ?> 
                <h1 class="legend"><?php echo $a_tab['writing_div']['label']?></h1>
          <?php endif; ?>

<div class="content">
            <?php
            $detail = new Acc_Misc($cn, $obj->jr_id);
            $detail->get();
            ?>
            <table class="result">
                <tr>
                    <?php
                    echo th(_('Poste Comptable'));
                    echo th(_('Quick Code'));
                    echo th(_('Libellé'));
                    echo th(_('Débit'), ' style="text-align:right"');
                    echo th(_('Crédit'), ' style="text-align:right"');
                    echo '</tr>';
                    for ($e = 0; $e < count($detail->det->array); $e++)
                    {
                        $row = '';
                        $q = $detail->det->array;
                        $view_history = sprintf('<A class="detail" style="text-decoration:underline" HREF="javascript:view_history_account(\'%s\',\'%s\')" >%s</A>', $q[$e]['j_poste'], $gDossier, $q[$e]['j_poste']);

                        $row.=td($view_history);
                        if ($q[$e]['j_qcode'] != '')
                        {
                            $fiche = new Fiche($cn);
                            $fiche->get_by_qcode($q[$e]['j_qcode']);
                            $view_history = sprintf('<A class="detail" style="text-decoration:underline" HREF="javascript:view_history_card(\'%s\',\'%s\')" >%s</A>', $fiche->id, $gDossier, $q[$e]['j_qcode']);
                        } else
                            $view_history = '';
                        $row.=td($view_history);
                        
                        if ($q[$e]['j_text']=="")
                        {
                            if ($q[$e]['j_qcode'] != '')
                            {
                            // nom de la fiche
                                $ff = new Fiche($cn);
                                $ff->get_by_qcode($q[$e]['j_qcode']);
                                $row.=td($ff->strAttribut(h(ATTR_DEF_NAME)));
                            } else
                            {
                                // libellé du compte
                                $name = $cn->get_value('select pcm_lib from tmp_pcmn where pcm_val=$1', array($q[$e]['j_poste']));
                                $row.=td(h($name));
                            }
                        }
                        else 
                            $row.=td(h($q[$e]['j_text']));
                        
                        $montant = td(nbm($q[$e]['j_montant']), 'class="num"');
                        $row.=($q[$e]['j_debit'] == 't') ? $montant : td('');
                        $row.=($q[$e]['j_debit'] == 'f') ? $montant : td('');
                        $class=($e%2==0)?' class="even"':'class="odd"';

                        echo tr($row,$class);
                    }
                    ?>
            </table>
        </div>
</div>
<div id="info_operation_div<?php echo $div;?>" class="myfieldset" style="display:<?php echo $a_tab['info_operation_div']['display']?>">
    <?php 
          // display title only in popup
          if ($div == 'popup') :
          ?> 
                <h1 class="legend"><?php echo $a_tab['info_operation_div']['label']?></h1>
          <?php endif; ?>
    <table>
        <tr>
            <td><?php echo _(" Bon de commande")?>   :</td><td> <?php echo HtmlInput::infobulle(31)." ".$cmd->input();  ?></td>
        </tr>
        <tr>
            <td> <?php echo _("Autre information")?> : </td><td><?php echo HtmlInput::infobulle(30)." ".$other->input();?></td>
        </tr>
    </table>
</div>
<div id="linked_operation_div<?php echo $div;?>" style="display:<?php echo $a_tab['linked_operation_div']['display']?>" class="myfieldset">
 <?php 
          // display title only in popup
          if ($div == 'popup') :
          ?> 
                <h1 class="legend"><?php echo $a_tab['linked_operation_div']['label']?></h1>
          <?php endif; ?>
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
	?>
	<div id="linked_action_div<?php echo $div;?>" style="display:<?php echo $a_tab['linked_action_div']['display']?>" class="myfieldset">
		 <?php 
          // display title only in popup
          if ($div == 'popup') :
          ?> 
                <h1 class="legend"><?php echo $a_tab['linked_action_div']['label']?></h1>
          <?php endif; ?>
	<?php 
	/**
	 * show possible linked actions
	 */
	$array = Follow_Up::get_all_operation($jr_id);
	echo '<ul style="list-style-type:square;">';
	for ($i = 0; $i < count($array); $i++)
	{
            $remove='';
            if ( $access=='W') $remove=HtmlInput::button_action_remove_operation($array[$i]['ago_id']);
		if ( $div == 'popup')
		{
			echo '<li id="op'.$array[$i]['ago_id'].'">'.HtmlInput::detail_action($array[$i]['ag_id'], h($array[$i]['ag_ref']." ".$array[$i]['ag_title']),0).$remove.'</li>';
		}
		else
		{
			echo '<li id="op'.$array[$i]['ago_id'].'">'.HtmlInput::detail_action($array[$i]['ag_id'], h($array[$i]['ag_ref']." ".$array[$i]['ag_title']),1).$remove.'</li>';
		}
	}
	echo '</ul>';
        $related=new IRelated_Action('related');
        $related->id='related'.$div;
         if ( $access=='W') echo $related->input();
	echo '</div>';
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
} else {
    echo HtmlInput::hidden('p_jrn',$oLedger->id);
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
