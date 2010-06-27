<div style="text-align:right;height:13px">
<? 
echo '<A style="background-color:blue;color:white;text-decoration:none" HREF="javascript:void(0)" onclick="removeDiv(\''.$div.'\');">Fermer</A>'; 
?>
</div>
   <? echo h2info($oLedger->get_name()); ?>
<? echo "Opération ID=".hb($obj->det->jr_internal); ?>