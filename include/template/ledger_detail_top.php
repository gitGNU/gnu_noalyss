<div style="text-align:right;height:13px">
<? 
echo '<A style="margin-right:5px;background-color:blue;color:white;text-decoration:none" HREF="javascript:void(0)" onclick="var a=getElementById(\''.$div.'\');a.style.width=\'95%\';a.style.height=\'95%\';a.style.top=\'2%\';a.style.left=\'2%\';a.style.overflow=\'auto\';">Agrandir</A>'; 
echo '<A style="background-color:blue;color:white;text-decoration:none" HREF="javascript:void(0)" onclick="removeDiv(\''.$div.'\');">Fermer</A>'; 
?>
</div>
   <? echo h2info($oLedger->get_name()); ?>
<? echo "Opération ID=".hb($obj->det->jr_internal); ?>