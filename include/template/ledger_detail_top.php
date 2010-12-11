<div style="float:right;height:10px;display:block;margin-top:2px;margin-right:2px">
<? 
   if ($div != "popup") {
     $callback=$_SERVER['PHP_SELF'];
     $str=$_SERVER['QUERY_STRING']."&act=$action&ajax=$callback";
     echo '<A style="margin-right:5px;background-color:blue;color:white;text-decoration:none" HREF="javascript:void(0)" onclick="var a=window.open(\'popup.php?'.$str.'\',\'\',\'toolbar=0,width=600,height=400,scrollbars=yes,resizable=yes,status=0,location=0\'); a.focus();removeDiv(\''.$div.'\')">
!pop me up ! </A>';
     echo '<A style="background-color:blue;color:white;text-decoration:none" HREF="javascript:void(0)" onclick="removeDiv(\''.$div.'\');">Fermer</A>'; 
   }
?>
</div>
<div>
   <? echo h2info($oLedger->get_name()); ?>
</div>
<? echo "Opération ID=".hb($obj->det->jr_internal); ?>
<div id="<?=$div.'info'?>" class="divinfo"></div>
<? require_once('class_itextarea.php');
?>