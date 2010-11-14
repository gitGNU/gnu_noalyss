<div style="float:right;height:10px;margin-top:2;margin-right:2">
<?php
   if ($div != "popup")
   {
     $callback=$_SERVER['PHP_SELF'];
     $str=$_SERVER['QUERY_STRING']."&act=de&ajax=$callback";
     echo '<A style="margin-right:5px;background-color:blue;color:white;text-decoration:none" HREF="javascript:void(0)" onclick="var a=window.open(\'popup.php?'.$str.'\',\'\',\'toolbar=0,width=600,height=400,scrollbars=yes,resizable=yes,status=0,location=0\'); a.focus();removeDiv(\''.$div.'\')">
!pop me up ! </A>';
echo '<A style="background-color:blue;color:white;text-decoration:none" HREF="javascript:void(0)" onclick="removeDiv(\''.$div.'\');">Fermer</A>'; 
} 
?>
</div>