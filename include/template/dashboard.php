<div>
	<div style="float:left;width:50%">
<fieldset>
<legend><?=_('Calendrier')?>
</legend>
<?php echo $cal->display(); ?>
</fieldset>

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
echo h2info("Note");
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
</div>
<div style="float:left;width:50%">'
<fieldset> <legend><?=_('Pense-Bête')?></legend>

<?php
echo HtmlInput::button('add',_('Ajout'),'onClick="add_todo()"');
if ( ! empty ($array) )  {
  echo '<table id="table_todo" width="100%">';
  $nb=0;
  $today=date('d.m.Y');

  foreach ($array as $row) {
    if ( $nb % 2 == 0 ) $odd='class="odd" '; else $odd='class="even" ';
    if ( strcmp($today,$row['tl_date'])==0) { $odd.=' style="background-color:#FFEA00"';}
    $nb++;
    echo '<tr id="tr'.$row['tl_id'].'" '.$odd.'>'.
      '<td>'.
      $row['tl_date'].
      '</td>'.
      '<td>'.
      '<a href="javascript:void(0)" onclick="todo_list_show(\''.$row['tl_id'].'\')">'.
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
	<div style="clear:both"></div>
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
  echo '<fieldset style="background-color:white"><legend>'.$rapport->get_name().'</legend>';
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
      $bgcolor=($ix%2==0)?' style="background-color:lightgrey"':'';
      echo '<tr'.$bgcolor.'">';

      echo '<td> '.$row['desc'].'</td>'.
	'<td style="text-align:right">'.nbm($row['montant'])." &euro;</td>";
      echo '</tr>';
    }
    echo '</table>';
  }
  echo '</fieldset>';
  echo '</div>';
 } else {
  echo '<fieldset style="background-color:white"><legend>'._('Aucun rapport défini').'</legend>';
  echo '<a href="user_pref.php?'.dossier::get().'">'._('Cliquez ici pour mettre à jour vos préférences').'</a>';
  echo '</fieldset>';
 }

?>
</div>
