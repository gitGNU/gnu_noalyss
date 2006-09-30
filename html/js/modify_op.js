function modifyOperation(p_value,p_sessid,p_jrn)
		{
			var win=window.open('modify_op.php?action=update&p_jrn='+p_jrn+'&line='+p_value+'&PHPSESSID='+p_sessid,'Modifie','toolbar=no,width=500,height=400,scrollbars=yes,resizable=yes');
		}
function RefreshMe() {
window.location.reload();
}
	function dropLink(p_value,p_value2,p_sessid) {
	var win=window.open('modify_op.php?action=delete&line='+p_value+'&line2='+p_value2+'&PHPSESSID='+p_sessid,'Liaison','toolbar=no,width=500,height=400,scrollbars=yes,resizable=yes');
		}
