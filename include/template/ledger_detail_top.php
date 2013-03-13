<div style="float:right;height:10px;display:block;margin-top:2px;margin-right:2px">
<?
   if ($div != "popup") {
     $callback=$_SERVER['PHP_SELF'];
     $str=$_SERVER['QUERY_STRING']."&act=$action&ajax=$callback";
     echo '<A id="close_div" HREF="javascript:void(0)" onclick="var a=window.open(\'popup.php?'.$str.'\',\'\',\'location=no,toolbar=no,fullscreen=yes,scrollbars=auto,resizable=yes,status=no\'); a.focus();removeDiv(\''.$div.'\')">
!pop me out ! </A>';
     echo '<A id="close_div" HREF="javascript:void(0)" onclick="removeDiv(\''.$div.'\');">Fermer</A>';
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