<div class="bxbutton">
<?php
   if ($div != "popup")
   {
     $callback=$_SERVER['PHP_SELF'];
     $str=$_SERVER['QUERY_STRING']."&act=de&ajax=$callback";
     echo '<A id="popmeout" HREF="javascript:void(0)" onclick="var a=window.open(\'popup.php?'.$str.'\',\'\',\'fullscreen=yes,location=no,toolbar=no,scrollbars=yes,resizable=yes,status=no,location=no\'); a.focus();removeDiv(\''.$div.'\')">
!pop me out ! </A>';
echo '<A id="close_div" HREF="javascript:void(0)" onclick="removeDiv(\''.$div.'\');">Fermer</A>';
}
?>
</div>
