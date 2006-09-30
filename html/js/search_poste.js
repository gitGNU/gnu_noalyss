function SearchPoste(p_sessid,p_ctl,p_jrn)
     {
       var win=window.open('poste_search.php?p_jrn='+p_jrn+'&p_ctl='+p_ctl+'&PHPSESSID='+p_sessid,'Cherche','toolbar=no,width=600,height=600,scrollbars=yes,resizable=yes');
    } 
function SearchPosteFilter(p_sessid,p_ctl,p_filter,jrn)
     {
       var win=window.open('poste_search.php?p_jrn='+jrn+'&p_ctl='+p_ctl+'&PHPSESSID='+p_sessid+'&filter='+p_filter,'Cherche','toolbar=no,width=600,height=600,scrollbars=yes,resizable=yes');
    } 
	 function GetIt() {
	   window.close();	
	} 
function SetItChild(p_ctl,p_value) {
	self.opener.SetItParent(p_ctl,p_value);
	window.close();
}
function SetItParent(p_ctl,p_value) {

	var f=document.getElementsByName(p_ctl);
	for (var h=0; h < f.length; h++) {
		f[h].value=p_value;
		}
	
}
/* SetValue( p_ctl,p_value )
/* p_ctl is the name of the control
/* p_value is the value to set in
*/
function SetValue(p_ctl,p_value) 
{

	var f=document.getElementsByName(p_ctl);
	for (var h=0; h < f.length; h++) {
		f[h].value=p_value;
		}
	

}