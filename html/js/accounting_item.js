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
 *\param p_ctrl the control (input) to update (default value = none )
 *\note the script will to see if it founds a hidden value in the document.form 
 * to know if the return must update or replace
 *\see poste_search.php
 */
function search_poste(p_sessid,p_dossier,p_ctl,p_jrn,p_return,p_search,p_ctrl)
     {
	var comment="";
	if ( g(p_ctl) && p_search=='Y')	{
	       comment=g(p_ctl).value;
	} 

       var
win=window.open('poste_search.php?gDossier='+p_dossier+'&p_jrn='+p_jrn+'&p_ctl='
+p_ctl+'&PHPSESSID='+p_sessid+"&p_comment="+comment+"&search"+"&ret="+p_return+
'&ctrl='+p_ctrl,
'Cherche','toolbar=no,width=600,height=600,scrollbars=yes,resizable=yes');
     } 
/*!\brief open a window for searching a account
 *\param p_sessid PHPSESSID
 *\param p_dossier dossier id
 *\param p_ctl p_ctl to set
 *\param p_jrn the concerned ledger id
 *\param p_return update with the pcm_val or pcm_lib (value=label or poste)
 *\param p_search take the value of the ctl and make a search
*\param p_ctrl the control to update (default value = none )
 *\see poste_search.php
 */
function SearchPosteFilter(p_sessid,p_dossier,p_ctl,p_filter,jrn,p_ctrl)
{
	var comment="";
	if ( g(p_ctl) )	{
	       comment=g(p_ctl).value;
	} 

	var win=window.open('poste_search.php?gDossier='+p_dossier+'&p_jrn='+jrn+'&p_ctl='+p_ctl+'&PHPSESSID='+p_sessid+'&filter='+p_filter+'&p_comment='+comment+"&search&ret=label"+'&ctrl='+p_ctrl,'Cherche','toolbar=no,width=600,height=600,scrollbars=yes,resizable=yes');
    } 

function set_poste_parent(p_ctl,p_value) {
	var f=g(p_ctl);
	f.value+='['+p_value+']';
}

function set_jrn_parent(p_ctl,p_value) {
	var f=g(p_ctl);
	if ( f ) {
		if ( trim(f.value)!="") f.value+=' ';
		f.value+=p_value;
	}
}


function PcmnUpdate(p_value,p_lib,p_parent,p_type,p_sessid,p_dossier)
	{
	var win=window.open('line_update.php?l='+p_value+'&n='+p_lib+'&p='+p_parent+'&m'+p_type+'&PHPSESSID='+p_sessid+'&gDossier='+p_dossier,'Modifie','toolbar=no,width=500,height=400,scrollbars=yes,resizable=yes');
	}


