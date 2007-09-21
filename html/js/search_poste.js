/*
 *   This file is part of PhpCompta.
 *
 *   PhpCompta is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   PhpCompta is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with PhpCompta; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
/* $Revision$ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/*! \file 
 * \brief
 * open a windows for searching an account (poste comptable)
 */

function SearchPoste(p_sessid,p_dossier,p_ctl,p_jrn)
     {
	var comment="";
	if ( document.getElementById(p_ctl) )	{
	       comment=document.getElementById(p_ctl).value;
	} 


       var win=window.open('poste_search.php?gDossier='+p_dossier+'&p_jrn='+p_jrn+'&p_ctl='+p_ctl+'&PHPSESSID='+p_sessid+"&p_comment="+comment+"&search",'Cherche','toolbar=no,width=600,height=600,scrollbars=yes,resizable=yes');
    } 
function SearchPosteFilter(p_sessid,p_dossier,p_ctl,p_filter,jrn)
     {
       var win=window.open('poste_search.php?gDossier='+p_dossier+'&p_jrn='+jrn+'&p_ctl='+p_ctl+'&PHPSESSID='+p_sessid+'&filter='+p_filter,'Cherche','toolbar=no,width=600,height=600,scrollbars=yes,resizable=yes');
    } 
	 function GetIt() {
	   window.close();	
	} 
function SetItChild(p_ctl,p_value,p_label) {

  self.opener.SetItParent(p_ctl,p_value,p_label);
	window.close();
}
function SetItParent(p_ctl,p_value,p_label) {

	var f=document.getElementById(p_ctl);
	f.value=p_value;
	var f1=document.getElementById(p_ctl+"_label");
	f1.innerHTML=p_label;
	
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
