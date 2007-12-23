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

function bud_search_poste(p_sessid,p_dossier,p_ctl)
     {
	var comment="";
	if ( document.getElementById(p_ctl) )	{
	       comment=document.getElementById(p_ctl).value;
	} 

	var win=window.open('bud_search_poste.php?gDossier='+p_dossier+'&p_ctl='+p_ctl+'&PHPSESSID='+p_sessid+"&comment="+comment+"&search",'Cherche','toolbar=no,width=600,height=600,scrollbars=yes,resizable=yes');
    } 
function GetIt() {
  window.close();	
} 

function SetItChild(p_ctl,p_value,p_label) {
  self.opener.SetItParent(p_ctl,p_value,p_label);
	window.close();
}
function SetItParent(p_ctl,p_value,p_label) {
	$(p_ctl).value=p_value+' '+p_label;
	$(p_ctl+'_hidden').value=p_value;
}
function bud_form_enable(p_ctl) {
   $("form_"+p_ctl).enable();
   $('button_save'+p_ctl).show();
   $('button_change'+p_ctl).hide();
   $('button_delete'+p_ctl).show();
   $('button_escape'+p_ctl).show();
}
function bud_form_disable(p_ctl) {
   $("form_"+p_ctl).disable();
   $('button_save'+p_ctl).hide();
   $('button_change'+p_ctl).show();
   $('button_delete'+p_ctl).hide();
   $('button_escape'+p_ctl).hide();

}

function bud_form_save(p_ctl,p_sessid,p_dossier) {
  query=$("form_"+p_ctl).serialize();
  query+="&action=save";
  bud_form_disable(p_ctl);
  bud_action(query,p_ctl);
}
function bud_form_delete(p_ctl,p_sessid,p_dossier) {
  if  ( confirm ("Vous etes sur d'effacer") == false) { return;}
  query=$("form_"+p_ctl).serialize();
  query+="&action=delete";
  var elt=$("form_"+p_ctl).getElements();
  //  alert (elt);
  for (i=0;i < elt.length;i++) { 
    if ( elt[i].name.search(/amount_/)!= -1 ) { 
      elt[i].value="0";
    }
    if ( elt[i].name.search(/account_/) != -1 ) { elt[i].value=" ";}
    if ( elt[i].name.search(/bd_id/) != -1 ) { elt[i].value="0";}
  }
  bud_form_disable(p_ctl);
  bud_action(query,p_ctl);
}
function display_error(request) {
  alert("L'operation a echoue");
}

function display_success(request,json) {
  var answer=request.responseText.evalJSON(true);
  var form=$('form_'+answer.form_id);
  var elt=form.getElements();

  for (i = 0;i < elt.length;i++) {
    if  ( elt[i].name.search(/bd_id/) != -1 ) {

      elt[i].value=answer.bd_id;
    }
  }


}
function bud_action(p_query,p_ctl) {
  var action=new Ajax.Request (
			       "bud_ajax.php",
			       {
			       method:'post',
			       parameters:p_query,
			       onFailure:display_error,
			       onSuccess:display_success
			       }
			
			       );
}