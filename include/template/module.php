<div class="u_tool">
    <div class="name">

	<H2 class="dossier"> Dossier : <?=h(dossier::name())?></h2>
	<?=IButton::show_calc()?>
	<div id="direct">
	<form method="get">
		<?=Dossier::hidden()?>
		<?
			$direct=new IText('ac');
			$direct->style='class="direct"';
			$direct->value=HtmlInput::default_value('ac', '', $_REQUEST);
			$direct->size=(strlen($direct->value)<10)?10:strlen($direct->value);
			echo $direct->input();
			echo HtmlInput::submit('go','aller');
			?>
	</form>
	</div>
    </div>
    <div class="acces_direct">
	<table>
	    <tr>
		<?php
		foreach ($amodule as $row):
			$js="";
		    $style="background:white";
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
		<td class="tool" style="<?=$style?>"><a class="mtitle" href="<?=$url?>" <?=$js?> ><?=$row['me_menu']?></td>
		<?
		    endforeach;
		?>
	    </tr>
	</table>

    </div>
</div>