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
/* $Revision: 2546 $ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/*!\file 
 * \brief javascript scripts for the gestion
 *
 */



/**
 *@brief remove an attached document of an action
 *@param phpsessid
 *@param dossier
 *@param dt_id id of the document (pk document:d_id)
*/
function remove_document(p_sessid,p_dossier,p_id) {
  queryString="?PHPSESSID="+p_sessid+"&gDossier="+p_dossier+"&a=rm&d_id="+p_id;
  var action=new Ajax.Request (
			       "show_document.php",
			       {
				 method:'get',
				 parameters:queryString,
				 onFailure:errorRemoveDoc,
				 onSuccess:successRemoveDoc
			       }
			
			       );

}
/**
 *@brief error if a document if removed
 */
 function errorRemoveDoc() { alert('Impossible d\'effacer ce document');}
 /**
  *@brief success when removing a document
  */
  function successRemoveDoc(request,json){
	  var answer=request.responseText.evalJSON(true);
	  var action="ac"+answer.d_id;
	  $(action).innerHTML="";
	  var doc="doc"+answer.d_id;
	  $(doc).style.color="red";
	  $(doc).href="javascript:alert('Document Effacé')";
	  $(doc).style.textDecoration="line-through";
	  	
  }
 /**
 * @brief check the format of the hour
 * @param p_ctl is the control where the hour is encoded
 */
 function check_hour(p_ctl) {
	 try { 
	 	var h=document.getElementById(p_ctl);
	 	var re = /^\d{1,2}:\d{2}$/;
	 	 if ( trim(h.value) !='' && ! h.value.match(re)) 
	 	 	alert("Format de l'heure est HH:MM ")
	 }catch (erreur){
	 	alert('fct : check_hour '+erreur);
	 }
	 
 }
