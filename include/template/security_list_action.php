<?php
	
	function display_security_fieldset($p_legend,$p_array,$sec_User) {			
	$array=array(array('value'=>0,'label'=>"Pas d'accès"),
		     array('value'=>1,'label'=>'Accès'),
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
				
				$a=new widget('select');
				$a->name=sprintf('action%d',$l_line['ac_id']);
				$a->value=$array;
				$a->selected=$right;
				if ( $right==1) { 
				?>
			<td style="border:lightgreen 2px solid; ">
			<?php } else { ?>
			<td style="border:red 2px solid; " align="right">
				<?php }?>
				
			<?php  echo $a->IOValue();  ?>
			</td>
		</tr>
		<?php
			}
			
			
			?>
	</table>
</fieldset>
<?php } ?>
<table>
	<tr valign="top">
		<td>
			<?php   $array=get_array($cn_dossier,
				"select ac_id, ac_description from action  where ac_id >=$1 and ac_id <=$2 order by ac_id ",
				array(100,199));
				display_security_fieldset('Budget',$array,$sec_User); ?>
		</td>
		<td>
			<?php   $array=get_array($cn_dossier,
				"select ac_id, ac_description from action  where ac_id >=$1 and ac_id <=$2 order by ac_id ",
				array(800,1000));
				display_security_fieldset('Fiche',$array,$sec_User); ?>
		</td>
	</tr><tr>
		<td>
			<?php   $array=get_array($cn_dossier,
				"select ac_id, ac_description from action  where ac_id >=$1 and ac_id <=$2 order by ac_id ",
				array(200,299));
				display_security_fieldset('Comptabilité Analytique',$array,$sec_User); ?>
		</td>
		
		<td>
			<?php   $array=get_array($cn_dossier,
				"select ac_id, ac_description from action  where ac_id >=$1 and ac_id <=$2 order by ac_id ",
				array(700,799));
				display_security_fieldset('Impression',$array,$sec_User); ?>
		</td>
	</tr>
	<tr valign="top">
		
		<td rowspan="3">
			<?php   $array=get_array($cn_dossier,
				"select ac_id, ac_description from action  where ac_id >=$1 and ac_id <=$2 order by ac_id ",
				array(1100,1400));
				display_security_fieldset('Paramètre',$array,$sec_User); ?>
		</td>
		
		
	</tr>
	<tr  valign="top">
		<td>
			<?php   $array=get_array($cn_dossier,
				"select ac_id, ac_description from action  where ac_id >=$1 and ac_id <=$2 order by ac_id ",
				array(300,400));
				display_security_fieldset('Gestion',$array,$sec_User); ?>
		</td>
	</tr><tr>
		<td>
			<?php   $array=get_array($cn_dossier,
				"select ac_id, ac_description from action  where ac_id >=$1 and ac_id <=$2 order by ac_id ",
				array(1500,1600));
				display_security_fieldset('Stock',$array,$sec_User); ?>
		</td>
		
		
		
		
	</tr>
</table>

