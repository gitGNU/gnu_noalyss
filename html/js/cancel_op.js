function cancelOperation(p_value,p_sessid,p_jrn)
		{
			var win=window.open('annulation.php?p_jrn='+p_jrn+'&jrn_op='+p_value+'&PHPSESSID='+p_sessid,'Annule','toolbar=no,width=400,height=400,scrollbars=yes,resizable=yes');
		}
function RefreshMe() {
window.location.reload();
}