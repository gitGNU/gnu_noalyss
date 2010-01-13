<?php

	function display_security_fieldset($p_legend,$p_array,$sec_User) {
  $array=array(array('value'=>0,'label'=>_("Pas d'accès")),
	       array('value'=>1,'label'=>_('Accès')),
		     );

	$gDossier=dossier::id();
	?>
<fieldset><legend><?php echo $p_legend;?></legend>
	<TABLE align="right">

		<?php
			foreach  ( $p_array as $l_line){
			?>
		<tr>
			<td align="right">
				<?php echo $l_line['ac_description'];?>
			</td>

			<?php
				$right=$sec_User->check_action($l_line['ac_id']);

			$a=new ISelect();
				$a->name=sprintf('action%d',$l_line['ac_id']);
				$a->value=$array;
				$a->selected=$right;
				if ( $right==1) {
				?>
			<td style="border:lightgreen 2px solid; ">
			<?php } else { ?>
			<td style="border:red 2px solid; " align="right">
				<?php }?>

			<?php  echo $a->input();  ?>
			</td>
		</tr>
		<?php
			}


			?>
	</table>
</fieldset>
<?php } ?>
<div style="float:left;width:33%">
<div style="float:top">
			<?php   $array=$cn_dossier->get_array(
				"select ac_id, ac_description from action  where ac_id >=$1 and ac_id <=$2 order by ac_id ",
				array(1100,1400));
                                display_security_fieldset(_('Paramètre'),$array,$sec_User); ?>

</div>
</div>
<div style="float:left;width:33%">
<div style="float:top">
			<?php   $array=$cn_dossier->get_array("select ac_id, ac_description from action  where ac_id >=$1 and ac_id <=$2 order by ac_id ",
				array(800,1000));
                                display_security_fieldset(_('Fiche'),$array,$sec_User); ?>
</div>
	<div style="float:top">
			<?php   $array=$cn_dossier->get_array(
				"select ac_id, ac_description from action  where ac_id >=$1 and ac_id <=$2 order by ac_id ",
				array(200,299));
                                display_security_fieldset(_('Comptabilité Analytique'),$array,$sec_User); ?>
</div>

<div style="float:top">

			<?php   $array=$cn_dossier->get_array(
				"select ac_id, ac_description from action  where ac_id >=$1 and ac_id <=$2 order by ac_id ",
				array(700,799));
                                display_security_fieldset(_('Impression'),$array,$sec_User); ?>
</div>
</div>
<div style="float:left;">
<div style="float:top">
			<?php   $array=$cn_dossier->get_array(
				"select ac_id, ac_description from action  where ac_id >=$1 and ac_id <$2 order by ac_id ",
				array(1500,1600));
                                display_security_fieldset(_('Stock'),$array,$sec_User); ?>
</div>
<div style="float:top">
		<?php   $array=$cn_dossier->get_array(
				"select ac_id, ac_description from action  where ac_id >=$1 and ac_id <=$2 order by ac_id ",
				array(1700,1799));
                                display_security_fieldset(_('Prévision'),$array,$sec_User); ?>
<div style="float:top">
			<?php   $array=$cn_dossier->get_array(
				"select ac_id, ac_description from action  where ac_id >=$1 and ac_id <=$2 order by ac_id ",
				array(1600,1699));
                                display_security_fieldset(_('Extension'),$array,$sec_User); ?>
</div>
<div style="float:top">
			<?php   $array=$cn_dossier->get_array(
				"select ac_id, ac_description from action  where ac_id >=$1 and ac_id <=$2 order by ac_id ",
				array(300,400));
                                display_security_fieldset(_('Gestion'),$array,$sec_User); ?>
</div>
</div>