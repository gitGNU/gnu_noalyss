<div style="float:right;height:10px;display:block;margin-top:0.48%;margin-right:0.48%">
<?php 
   if ($div != "popup") {
     $callback=$_SERVER['PHP_SELF'];
     $str=$_SERVER['QUERY_STRING']."&act=$action&ajax=$callback";
     echo '<A id="close_div" HREF="javascript:void(0)" onclick="var a=window.open(\'popup.php?'.$str.'\',\'\',\'location=no,toolbar=no,fullscreen=yes,scrollbars=yes,resizable=yes,status=no\'); a.focus();removeDiv(\''.$div.'\')">
!pop me out ! </A>';
     echo '<A id="close_div" HREF="javascript:void(0)" onclick="removeDiv(\''.$div.'\');">Fermer</A>';
   }
?>
</div>
<div>
   <?php echo h2($oLedger->get_name(),'class="title"'); ?>
</div>
<?php echo "Opération ID=".hb($obj->det->jr_internal); ?>
<div id="<?php echo $div.'info'?>" class="divinfo"></div>
<?php require_once('class_itextarea.php');
?>