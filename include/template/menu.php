<div class="topmenu">
    <? if ( count($amenu) > 4 && $idx == 0) :
	$style = "width:100%";
    ?>
<table style="<?=$style?>" >
    <? elseif ($idx==0):
switch (count($amenu))
{
case 4:
case 3:
   $width=count($amenu)*20;
   $left=round((100-$width)/2);
$style="width:$width%;margin-left:$left%";
break;
default:
$style="";
}
	else:
		$style=" class=\"mtitle\"";

    	endif;?>
<table style="<?=$style?>" >


    <tr>
	<?php
	global $g_user;
	// Display the menu
	for($i=0;$i < count($amenu);$i++):
	    if ( (count($amenu)==1)|| (isset($module[$idx+1]) && $module[$idx+1]==$amenu[$i]['me_code'])):
		$class="selectedcell";
?>
	<td class="<?=$class?>">
	    <a class="mtitle" href="do.php?gDossier=<?=Dossier::id()?>&ac=<?=$_REQUEST['ac']?>" title="<?=h($amenu[$i]['me_description'])?>" >
	    <?=$amenu[$i]['me_menu']?>
	    </a>
	</td>
<?
	    else:
		$class="mtitle";
	    $url="";$pt="";
		$js="";
	    for ($e=0;$e <= $idx;$e++):
			$url.=$pt.$module[$e];
			$pt="/";
	    endfor;
		if ( $amenu[$i]['me_url']!='')
		{
			$url=$row['me_url'];
		}
		elseif ($amenu[$i]['me_javascript'] != '')
		{
			$url="javascript:void(0)";
			$js=sprintf(' onclick="%s"',$amenu[$i]['me_javascript']);
		}
		else
		{
			$url.=$pt.$amenu[$i]['me_code'];
		}

?>	<td class="<?=$class?>">
	    <a class="mtitle" href="do.php?gDossier=<?=Dossier::id()?>&ac=<?=$url?>" <?=$js?> title="<?=h($amenu[$i]['me_description'])?>">
	    <?=$amenu[$i]['me_menu']?>
	    </a>
	</td>


<?
endif;

	?>
	<?
	    endfor;
    	?>
    </tr>


</table>
</div>
<?php
// if something is selected check if file to include or submen
//
 ?>
