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
 * containing the javascript for opening a windows to search an account (poste comptable)
 */
/*!\brief open a window for searching a account
 *\param p_sessid PHPSESSID
 *\param p_dossier dossier id
 *\param p_ctl p_ctl to set
 *\param p_jrn the concerned ledger id
 *\param p_return update with the pcm_val or pcm_lib (value=label or poste)
 *\param p_search take the value of the ctl and make a search
 *\see poste_search.php
 */
function SearchPoste(p_sessid,p_dossier,p_ctl,p_jrn,p_return,p_search)
     {
	var comment="";
	if ( document.getElementById(p_ctl) && p_search=='Y')	{
	       comment=document.getElementById(p_ctl).value;
	} 


       var win=window.open('poste_search.php?gDossier='+p_dossier+'&p_jrn='+p_jrn+'&p_ctl='+p_ctl+'&PHPSESSID='+p_sessid+"&p_comment="+comment+"&search"+"&ret="+p_return,'Cherche','toolbar=no,width=600,height=600,scrollbars=yes,resizable=yes');
     } 
/*!\brief open a window for searching a account
 *\param p_sessid PHPSESSID
 *\param p_dossier dossier id
 *\param p_ctl p_ctl to set
 *\param p_jrn the concerned ledger id
 *\param p_return update with the pcm_val or pcm_lib (value=label or poste)
 *\param p_search take the value of the ctl and make a search
 *\see poste_search.php
 */

function SearchPosteFilter(p_sessid,p_dossier,p_ctl,p_filter,jrn)
     {
	var comment="";
	if ( document.getElementById(p_ctl) )	{
	       comment=document.getElementById(p_ctl).value;
	} 

	var win=window.open('poste_search.php?gDossier='+p_dossier+'&p_jrn='+jrn+'&p_ctl='+p_ctl+'&PHPSESSID='+p_sessid+'&filter='+p_filter+'&p_comment='+comment+"&search&ret=label",'Cherche','toolbar=no,width=600,height=600,scrollbars=yes,resizable=yes');
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
    if ( f1)	f1.innerHTML=p_label;
	
}

function set_poste_child(p_ctl,p_value) {

  self.opener.set_poste_parent(p_ctl,p_value);
	window.close();
}
function set_poste_parent(p_ctl,p_value) {

	var f=document.getElementById(p_ctl);
	f.value+='['+p_value+']';
	
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
