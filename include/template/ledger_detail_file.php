<?
/** 
 *@brief  show an iframe, the iframe contains either
 * - a input type to save a file
 * - a file name (which can be opened or removed
 */
$str='?'.dossier::get()."&div=$div&act=file&jr_id=$jr_id";
?>
<iframe style="border:0;width:500;height:50;overflow:hidden" src="<? echo 'ajax_ledger.php'.$str; ?>"> </iframe>