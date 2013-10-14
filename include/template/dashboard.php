<!-- left div -->
<div style="float:right;width: 49%">

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
<div style="float:right;width: 100%">
<fieldset> 
    <legend> Situation </legend>
    <table class='result'>
		<tr>
			<th>

			</th>
			<th>
				Pour aujourd'hui
			</th>
			<th>
				En retard
			</th>
		</tr>
		<tr>
			<td>
				Action
			</td>
			<td>
				<?php if (count($last_operation)>0): ?>
				<A class="mtitle" style="color:red;text-decoration:underline;font-weight: bolder;"onclick="display_detail('action_now_div')">
					<span class="notice">
					<?php echo count($last_operation) ?>
					&nbsp;détail
					</span>
				</A>
			<?php else: ?>
				 0
			<?php endif; ?>
			</td>

			<td >
			<?php if (count($late_operation)>0): ?>
				<A class="mtitle"  style="color:red;text-decoration:underline;;font-weight: bolder" onclick="display_detail('action_late_div')">
				<span class="notice"><?php echo count($late_operation) ?>
					&nbsp;détail
                                </span>
				</A>
			<?php else: ?>
				 0
			<?php endif; ?>
			</td>

		</tr>
		<tr>
			<td>
				Paiement fournisseur
			</td>
			<td >
			<?php if (count($supplier_now)>0): ?>
				<A class="mtitle"  style="color:red;text-decoration:underline;font-weight: bolder" onclick="display_detail('supplier_now_div')">
				<span class="notice"><?php echo count($supplier_now) ?>&nbsp;détail</span>
					
				</A>
			<?php else: ?>
				 0
			<?php endif; ?>
			</td>
			<td >
			<?php if (count($supplier_late)>0): ?>
				<A class="mtitle"  style="color:red;text-decoration:underline;font-weight: bolder" onclick="display_detail('supplier_late_div')">
				<span class="notice"><?php echo count($supplier_late) ?>&nbsp;détail</span>
					
				</A>
			<?php else: ?>
				 0
			<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td>
				Paiement client
			</td>
			<td>
				<?php if (count($customer_now)>0): ?>
				<A class="mtitle"  style="color:red;text-decoration:underline;font-weight: bolder" onclick="display_detail('customer_now_div')">
				<span class="notice"><?php echo count($customer_now) ?>&nbsp;détail</span>
					
				</A>
			<?php else: ?>
				 0
			<?php endif; ?>
			</td>
			<td>
				<?php if (count($customer_late)>0): ?>
				<A class="mtitle"  style="color:red;text-decoration:underline;font-weight: bolder" onclick="display_detail('customer_late_div')">
				<span class="notice"><?php echo count($customer_late) ?>&nbsp;détail</span>
					
				</A>
			<?php else: ?>
				 0
			<?php endif; ?>
			</td>
		</tr>
	</table>
</fieldset>
<fieldset> <legend><?php echo _('Pense-Bête')?></legend>

<?php
echo HtmlInput::button('add',_('Ajout'),'onClick="add_todo()"');
if ( ! empty ($array) )  {
  echo '<table id="table_todo" class="sortable" width="100%">';
  echo '<tr><th class=" sorttable_sorted_reverse">Date <span id="sorttable_sortrevind">&nbsp;&blacktriangle;</span></th><th>Titre</th><th></th>';
  $nb=0;
  $today=date('d.m.Y');

  foreach ($array as $row) {
    if ( $nb % 2 == 0 ) $odd='class="odd" '; else $odd='class="even" ';
    if ( strcmp($today,$row['str_tl_date'])==0) { $odd.=' style="background-color:#FFEA00"';}
    $nb++;
    echo '<tr id="tr'.$row['tl_id'].'" '.$odd.'>'.
      '<td sorttable_customkey="'.$row['tl_date'].'>">'.
      $row['str_tl_date'].
      '</td>'.
      '<td>'.
      '<a class="line" href="javascript:void(0)" onclick="todo_list_show(\''.$row['tl_id'].'\')">'.
      htmlspecialchars($row['tl_title']).
      '</a>'.
       '</td>'.
      '<td>'.
      HtmlInput::button('del','X','onClick="todo_list_remove('.$row['tl_id'].')"','smallbutton').
      '</td>'.
      '</tr>';
  }
  echo '</table>';
}
?>
</fieldset>
</div>
<div style="float:right;width: 100%">
	
<div id="action_late_div"  class="inner_box" style="display:none;margin-left:25%;top:25%;width: 50%;height:50%;overflow: auto;">
	<?php
		echo HtmlInput::title_box("Action en retard", "action_late_div","hide")
	?>
	<ol>
	<?php if (count($late_operation)> 0) :

	for($i=0;$i<count($late_operation);$i++):
	?>
	<li>
		<?php echo HtmlInput::detail_action($late_operation[$i]['ag_id'],h($late_operation[$i]['ag_ref']))?>
	<span>
	<?php echo smaller_date($late_operation[$i]['ag_timestamp_fmt'])?>
	</span>
		<span  style="font-weight: bolder ">
			<?php echo h($late_operation[$i]['vw_name'])?>
		</span>
	<span>
	<?php echo h(mb_substr($late_operation[$i]['ag_title'],0,50,'UTF-8'))?>
	</span>
	<span style="font-style: italic">
	<?php echo $late_operation[$i]['dt_value']?>
	</span>
	</li>
	<?php endfor;?>
	</ol>
	<?php else : ?>
	<h2 class='notice'>Aucune action en retard</h2>
	<?php endif; ?>
	</div>

	<div id="action_now_div" class="inner_box" style="display:none;margin-left:25%;width: 50%;top:25%;height:50%;overflow: auto;">
	<?php
		echo HtmlInput::title_box("Action pour aujourd'hui", "action_now_div","hide")
	?>
	<ol>
	<?php
	if (count($last_operation)> 0) :
	for($i=0;$i<count($last_operation);$i++):
	?>
	<li>
		<?php echo HtmlInput::detail_action($last_operation[$i]['ag_id'],h($last_operation[$i]['ag_ref']))?>
	<span>
	<?php echo smaller_date($last_operation[$i]['ag_timestamp_fmt'])?>
	</span>
		<span  style="font-weight: bolder ">
			<?php echo h($last_operation[$i]['vw_name'])?>
		</span>
	<span>
	<?php echo h(mb_substr($last_operation[$i]['ag_title'],0,50,'UTF-8'))?>
	</span>
	<span style="font-style: italic">
	<?php echo $last_operation[$i]['dt_value']?>
	</span>
	</li>
	<?php endfor;?>
	</ol>
	<?php else : ?>
		<h2 class='notice'>Aucune action pour aujourd'hui</h2>
<?php endif; ?>
	</div>
	<?php display_dashboard_operation($supplier_now,"Fournisseurs à payer aujourd'hui",'supplier_now_div'); ?>
	<?php display_dashboard_operation($supplier_late,"Fournisseurs en retad",'supplier_late_div'); ?>
	<?php display_dashboard_operation($customer_now,"Encaissement clients aujourd'hui",'customer_now_div'); ?>
	<?php display_dashboard_operation($customer_late,"Clients en retard",'customer_late_div'); ?>
</div>



<div style="float:right;width: 100%">
<fieldset>
<legend><?php echo _('Dernières opérations')?>
</legend>
<table style="width: 100%">
<?php
for($i=0;$i<count($last_ledger);$i++):
	$class=($i%2==0)?' class="even" ':' class="odd" ';
?>
<tr <?php echo $class ?>>
	<td><?php echo   smaller_date($last_ledger[$i]['jr_date_fmt'])?>
	</td>
	<td>
		<?php echo $last_ledger[$i]['jrn_def_code']?>
	</td>
<td>
   <?php echo h(mb_substr($last_ledger[$i]['jr_comment'],0,40,'UTF-8'))?>
</td>
<td>
<?php echo HtmlInput::detail_op($last_ledger[$i]['jr_id'], $last_ledger[$i]['jr_internal'])?>
</td>
<td class="num">
<?php echo nbm($last_ledger[$i]['jr_montant'])?>
</td>

</tr>
<?php endfor;?>
</ul></table>
</fieldset>
</div>

</div>
<div style="float:right;width: 49%">
    
<div style="float:left;width: 100%">
<fieldset >
<legend><?php echo _('Calendrier')?>
</legend>
<?php echo HtmlInput::calendar_zoom($obj); ?>
<?php echo $cal->display(); ?>
</fieldset>
</div>
<!-- Mini rapport -->
<div style="float:left;width: 100%">
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

if ( $report != 0 ) : ?>
<fieldset style="height:50%;"><legend><?php echo $rapport->get_name()?></legend>
<?php    
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
      echo '<tr '.$class.'>';

      echo '<td> '.$row['desc'].'</td>'.
	'<td style="text-align:right">'.nbm($row['montant'])." &euro;</td>";
      echo '</tr>';
    }
    echo '</table>';
  }
  ?>
  </fieldset>
<?php
  else :
?>
  <fieldset style="height:50%;width:80%;background-color:white"><legend><?php _('Aucun rapport défini')?></legend>
  <a href="javascript:void(0)" onclick="set_preference('.dossier::id().')"><?php echo _('Cliquez ici pour mettre à jour vos préférences')?></a>

</fieldset>
<?php
endif;
?>
</div>
</div>


<div id="add_todo_list" >
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

<script type="text/javascript" language="javascript" charset="utf-8">
function display_detail(div) {
	$(div).style.display="block";
	//Effect.Grow(div,{});
}
try {
var array=Array('customer_now_div','customer_late_div','supplier_now_div','supplier_late_div','action_now_div','action_late_div');
var i=0;
for  (i=0;i < array.length;i++) {
	new Draggable(array[i],{});
	}
} catch (e) { alert(e.getMessage);}
</script>