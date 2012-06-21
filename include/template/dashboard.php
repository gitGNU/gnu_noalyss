<div style="float:left;width:50%">
<fieldset style="width:100%">
<legend><?=_('Calendrier')?>
</legend>
<?php echo $cal->display(); ?>
</fieldset>
</div>

<?php
/*
 * Todo list
 */
echo dossier::hidden();
if ( isset($_REQUEST['save_todo_list'])) {
  /* Save the new elt */
  $add_todo=new Todo_List($cn);
  $add_todo->set_parameter('id',$_REQUEST['tl_id']);
  $add_todo->set_parameter('title',$_REQUEST['p_title']);
  $add_todo->set_parameter('desc',$_REQUEST['p_desc']);
  $add_todo->set_parameter('date',$_REQUEST['p_date_todo']);
  $add_todo->save();
}
$todo=new Todo_List($cn);
$array=$todo->load_all();
?>

<div style="float:right;width:45%">
<fieldset> <legend><?=_('Pense-Bête')?></legend>

<?php
echo HtmlInput::button('add',_('Ajout'),'onClick="add_todo()"');
if ( ! empty ($array) )  {
  echo '<table id="table_todo" width="100%">';
  $nb=0;
  $today=date('d.m.Y');

  foreach ($array as $row) {
    if ( $nb % 2 == 0 ) $odd='class="odd" '; else $odd='class="even" ';
    if ( strcmp($today,$row['str_tl_date'])==0) { $odd.=' style="background-color:#FFEA00"';}
    $nb++;
    echo '<tr id="tr'.$row['tl_id'].'" '.$odd.'>'.
      '<td>'.
      $row['str_tl_date'].
      '</td>'.
      '<td>'.
      '<a class="line" href="javascript:void(0)" onclick="todo_list_show(\''.$row['tl_id'].'\')">'.
      htmlspecialchars($row['tl_title']).
      '</a>'.
       '</td>'.
      '<td>'.
      HtmlInput::button('del','X','onClick="todo_list_remove('.$row['tl_id'].')"').
      '</td>'.
      '</tr>';
  }
  echo '</table>';
}
?>
</fieldset>
</div>
<div style="float:right;width:45%">
<fieldset>
<legend><?=_('Action pour aujourd\'hui')?>
</legend>
<ol>
<?php
for($i=0;$i<count($last_operation);$i++):
?>
<li>
<span>
<?=smaller_date($last_operation[$i]['ag_timestamp_fmt'])?>
</span>
	<span  style="font-weight: bolder ">
		<?=h($last_operation[$i]['vw_name'])?>
	</span>
<span>
   <?=h(mb_substr($last_operation[$i]['ag_title'],0,40,'UTF-8'))?>
</span>
<span style="font-style: italic">
<?=$last_operation[$i]['dt_value']?>
</span>
</li>
<? endfor;?>
</ol>
</fieldset>
</div>

<div style="float:right;width:45%">
<fieldset>
<legend><?=_('Dernières opérations')?>
</legend>
<table style="width: 100%">
<?php
for($i=0;$i<count($last_ledger);$i++):
	$class=($i%2==0)?' class="even" ':' class="odd" ';
?>
<tr <?=$class?> >
	<td><?=  smaller_date($last_ledger[$i]['jr_date_fmt'])?>
	</td>
	<td>
		<?=$last_ledger[$i]['jrn_def_code']?>
	</td>
<td>
   <?=h(mb_substr($last_ledger[$i]['jr_comment'],0,40,'UTF-8'))?>
</td>
<td>
<?=HtmlInput::detail_op($last_ledger[$i]['jr_id'], $last_ledger[$i]['jr_internal'])?>
</td>
<td class="num">
<?=nbm($last_ledger[$i]['jr_montant'])?>
</td>

</tr>
<? endfor;?>
</ul></table>
</fieldset>
</div>

	<div style="float:left;width:50%;clear:left">
<?php
/*
 * Mini Report
 */
$report=$g_user->get_mini_report();

$rapport=new Acc_Report($cn);
$rapport->id=$report;
if ( $rapport->exist() == false ) {
  $g_user->set_mini_report(0);
  $report=0;
}

if ( $report != 0 ) {
  echo '<fieldset ><legend>'.$rapport->get_name().'</legend>';
  $exercice=$g_user->get_exercice();
  if ( $exercice == 0 ) {
    alert(_('Aucune periode par defaut'));
  } else {
    $periode=new Periode($cn);
    $limit=$periode->limit_year($exercice);

    $result=$rapport->get_row($limit['start'],$limit['end'],'periode');
    $ix=0;
    echo '<table border="0" width="100%">';
    foreach ($result as $row) {
      $ix++;
	  $class=($ix%2==0)?' class="even" ':' class="odd" ';
      echo '<tr'.$class.'">';

      echo '<td> '.$row['desc'].'</td>'.
	'<td style="text-align:right">'.nbm($row['montant'])." &euro;</td>";
      echo '</tr>';
    }
    echo '</table>';
  }
  echo '</fieldset>';
  echo '</div>';
 } else {
  echo '<fieldset style="width:50%;background-color:white"><legend>'._('Aucun rapport défini').'</legend>';
  echo '<a href="do.php?'.dossier::get().'&ac=PREFERENCE">'._('Cliquez ici pour mettre à jour vos préférences').'</a>';
  echo '</fieldset>';
 }

?>
</div>
</div>

<div id="add_todo_list" class="add_todo_list">
	<script charset="utf8" type="text/javascript" language="javascript">
		new Draggable($('add_todo_list'),{});
	</script>
<form method="post">
<?php
$wDate=new IDate('p_date_todo');
$wDate->id='p_date_todo';
$wTitle=new IText('p_title');
$wDesc=new ITextArea('p_desc');
$wDesc->heigh=5;
$wDesc->width=40;
echo HtmlInput::title_box("Note","add_todo_list","hide");
echo _("Date")." ".$wDate->input().'<br>';
echo _("Titre")." ".$wTitle->input().'<br>';
echo _("Description")."<br>".$wDesc->input().'<br>';
echo dossier::hidden();
echo HtmlInput::hidden('tl_id',0);
echo HtmlInput::submit('save_todo_list',_('Sauve'),'onClick="$(\'add_todo_list\').hide();return true;"');
echo HtmlInput::button('hide',_('Annuler'),'onClick="$(\'add_todo_list\').hide();return true;"');
?>
</form>
</div>
