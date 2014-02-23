<?php
//This file is part of NOALYSS and is under GPL 
//see licence.txt
?><?php 
/**
 *@brief  show an iframe, the iframe contains either
 * - a input type to save a file
 * - a file name (which can be opened or removed
 */
$str='?'.dossier::get()."&div=$div&act=file&jr_id=$jr_id";
if ( isset ($_REQUEST['ajax'])) $str.="&ajax=1";
?>
<div class="noprint">
<iframe frameborder=0 scrolling="no" style="margin:0px;padding: 0px;border:0px;width:100%;height:90px;overflow:hidden" src="<?php echo 'ajax_ledger.php'.$str; ?>"></iframe>
</div>