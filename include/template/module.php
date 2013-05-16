<div class="u_tool">
    <div class="name">

<?php

if ( $cn->get_value("select count(*) from profile join profile_user using (p_id)
		where user_name=$1 and with_calc=true",array($_SESSION['g_user'])) ==1):
  echo '<div id="calc">';
	echo IButton::show_calc();
echo '</div>';
endif;

if ( $cn->get_value("select count(*) from profile join profile_user using (p_id)
		where user_name=$1 and with_direct_form=true",array($_SESSION['g_user'])) ==1):
?>
	<div id="direct">
	<form method="get">
		<?php echo HtmlInput::default_value('ac', '', $_REQUEST)?>
		<?php echo Dossier::hidden()?>
		<?php 

			$direct=new IText('ac');
			$direct->style='class="input_text"';
			$direct->value='';
			$direct->size=20;
			echo $direct->input();
			$gDossier=dossier::id();
			?>
		<div id="ac_choices" class="autocomplete" style="width:150"></div>
		<?php 
			echo HtmlInput::submit('go','aller');
			?>

	</form>
	<script charset="utf8" language="javascript">

		try {
			new Ajax.Autocompleter("ac","ac_choices","direct.php?gDossier=<?php echo $gDossier?>",
                            {paramName:"acs",minChars:1,indicator:null,
                            callback:null,
                             afterUpdateElement:null});} catch (e){alert(e.message);};
		</script>
	</div>
<?php 
endif;?>
	<div id="dossier">
	<H2 class="dossier"> Dossier : <?php echo h(dossier::name())?></h2>
	</div>
    </div>

    <div class="module">
	<table>
	    <tr>
		<?php
		foreach ($amodule as $row):
			$js="";
		    $style="";
		    if ( $row['me_code']=='new_line')
		    {
			echo "</tr><tr>";
			continue;
		    }
		    if ($row['me_code']==$selected)
		    {
			$style="background:red";
		    }
		    if ( $row['me_url']!='')
		    {
			$url=$row['me_url'];
		    }
		    elseif ($row['me_javascript'] != '')
			{
				$url="javascript:void(0)";
				$js=sprintf(' onclick="%s"',$row['me_javascript']);
			}
			else
		    {
				$url="do.php?gDossier=".Dossier::id()."&ac=".$row['me_code'];
		    }
		    ?>
		<td class="tool" style="<?php echo $style?>">
			<a class="mtitle" href="<?php echo $url?>" title="<?php echo _($row['me_description'])?>" <?php echo $js?> ><?php echo _($row['me_menu'])?></a></td>
		<?php 
		    endforeach;
		?>
	    </tr>
	</table>

    </div>
</div>
<div style="clear:both;"></div>