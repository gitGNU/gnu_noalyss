<div style="text-align:right;height:13px">
<? 
   if ($div != "popup") {
     $callback=$_SERVER['PHP_SELF'];
     $str=$_SERVER['QUERY_STRING']."&act=$action&ajax=$callback";
     echo '<A style="margin-right:5px;background-color:blue;color:white;text-decoration:none" HREF="javascript:void(0)" onclick="var a=window.open(\'popup.php?'.$str.'\',\'\',\'toolbar=no,width=600,height=400,scrollbars=yes,resizable=yes\'); a.focus();removeDiv(\''.$div.'\')">
!pop me up ! </A>';
echo '<A style="background-color:blue;color:white;text-decoration:none" HREF="javascript:void(0)" onclick="removeDiv(\''.$div.'\');">Fermer</A>'; 
}
?>
</div>
   <? echo h2info($oLedger->get_name()); ?>
<? echo "Opération ID=".hb($obj->det->jr_internal); ?>