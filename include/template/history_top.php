<div style="float:right;height:10px;margin-top:2;margin-right:2">
<?php
   if ($div != "popup")
   {
     $callback=$_SERVER['PHP_SELF'];
     $str=$_SERVER['QUERY_STRING']."&act=de&ajax=$callback";
     echo '<A id="close_div" HREF="javascript:void(0)" onclick="var a=window.open(\'popup.php?'.$str.'\',\'\',\'toolbar=0,width=600,height=400,scrollbars=yes,resizable=yes,status=0,location=0\'); a.focus();removeDiv(\''.$div.'\')">
!pop me out ! </A>';
echo '<A id="close_div" HREF="javascript:void(0)" onclick="removeDiv(\''.$div.'\');">Fermer</A>';
}
?>
</div>