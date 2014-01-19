<?php
//This file is part of NOALYSS and is under GPL 
//see licence.txt
?><div class="<?php echo $style_menu; ?>">
    <?php if ( count($amenu) > 4 && $idx == 0) :
	$style ='style= "width:100%"';
     elseif ($idx==0):
switch (count($amenu))
{
case 4:
case 3:
   $width=count($amenu)*20;
   $left=round((100-$width)/2);
$style="style=\"width:$width%;margin-left:$left%\"";
break;
default:
$style="";
}
	else:
		$style=" class=\"mtitle\"";

    	endif;?>
<table  <?php echo $style?> >


    <tr>
	<?php
	global $g_user;
	// Display the menu
	for($i=0;$i < count($amenu);$i++):
	    if ( (count($amenu)==1)|| (isset($module[$idx+1]) && $module[$idx+1]==$amenu[$i]['me_code'])):
		$class="selectedcell";
?>
	<td class="<?php echo $class?>">
	    <a class="mtitle" href="do.php?gDossier=<?php echo Dossier::id()?>&ac=<?php echo $_REQUEST['ac']?>" title="<?php echo h(_($amenu[$i]['me_description']))?>" >
	    <?php echo _($amenu[$i]['me_menu'])?>
	    </a>
	</td>
<?php 
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

?>	<td class="<?php echo $class?>">
	    <a class="mtitle" href="do.php?gDossier=<?php echo Dossier::id()?>&ac=<?php echo $url?>" <?php echo $js?> title="<?php echo h(_($amenu[$i]['me_description']))?>">
	    <?php echo _($amenu[$i]['me_menu'])?>
	    </a>
	</td>


<?php 
endif;

	?>
	<?php 
	    endfor;
    	?>
    </tr>


</table>
</div>
<?php
// if something is selected check if file to include or submen
//
 ?>
