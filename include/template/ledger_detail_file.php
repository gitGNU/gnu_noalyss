<?
/** 
 *@brief  show an iframe, the iframe contains either
 * - a input type to save a file
 * - a file name (which can be opened or removed
 */
$str='?'.dossier::get()."&div=$div&act=file&jr_id=$jr_id";
?>
<div class="noprint">
<iframe frameborder=0 scrolling="no" style="border:0;width:248;height:21;overflow:hidden" src="<? echo 'ajax_ledger.php'.$str; ?>"> </iframe>
</div>