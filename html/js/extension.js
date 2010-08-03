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
/* $Revision: 2737 $ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/*!\file
 * \brief javascript script for extension
 *
 */
/**
*@brief show the popup for extension and call ajax
*@param the id of the extension to show
*/
function detail_extension(p_code){
	var dossier=$("gDossier").value;
	showIPopup("dtext");
    	var queryString='?gDossier='+dossier;
    	queryString+='&ex_id='+p_code;
        queryString+='&action=de'; 	// de for detail extension

    var action=new Ajax.Request ( 'ajax_extension.php',
				  {
				 method:'get',
				 parameters:queryString,
				 onFailure:errorExtension,
				 onSuccess:extDetail
			       }
			       );
}
/**
*@brief show the popup for adding a new  extension and call ajax
*/
function new_extension(){
	var dossier=$("gDossier").value;
	showIPopup("dtext");
    	var queryString='?gDossier='+dossier;
        queryString+='&action=ne'; 	// ne for new extension

    var action=new Ajax.Request ( 'ajax_extension.php',
				  {
				 method:'get',
				 parameters:queryString,
				 onFailure:errorExtension,
				 onSuccess:extDetail
			       }
			       );
}

/**
*@brief show the detail of a form
*/
function extDetail(req){
    try{
	var answer=req.responseXML;
	var a=answer.getElementsByTagName('ctl');
	if ( a.length == 0 ) { var rec=req.responseText;alert ('erreur :'+rec);}
	var html=answer.getElementsByTagName('code');
	var name_ctl=a[0].firstChild.nodeValue+'_content';
	var code_html=getNodeText(html[0]);
	code_html=unescape_xml(code_html);
	$(name_ctl).innerHTML=code_html;} 
    catch (e) {
	alert(e.message);}
    try{
	code_html.evalScripts();}
    catch(e){
	alert("Impossible executer script de la reponse\n"+e.message);}

}
/**
*@brief save the extension (add or update)
*/
function extension_save(p_obj_form){
    try{
	var dossier=$("gDossier").value;
    	var queryString='?gDossier='+dossier;
        queryString+='&action=se'; 	// se save extension
	// Data must be taken here
	try{	
	    var data=$(p_obj_form).serialize(false);
	} catch(f){ 
	    var sType=p_obj_form.tagName;
	    var sName=p_obj_form.id;
	    var nbElement=p_obj_form.elements.length;
	    alert("Message"+sName+" "+sType+" Nb Element"+nbElement+" "+f.message+"\n");
	    throw (f);
	}
	queryString+='&'+data;
	$("dtext_content").innerHTML=loading();
	var action=new Ajax.Request ( 'ajax_extension.php', { method:'POST', parameters:queryString,onFailure:errorExtension, 
							      onSuccess:successSave  }      );
	
      	hideIPopup('dtext');
    } catch(e) {
	alert("Probleme extension_save "+e._message+"\n"+e.description);
    }
}
function errorExtension(){
alert('Ajax extension failed');
}
function successSave(req){
    try{
	var answer=req.responseXML;
	var html=answer.getElementsByTagName('code');
	var code_html='';
	if ( html[0].firstChild != null ){
	    code_html=html[0].firstChild.nodeValue;
	    code_html=unescape_xml(code_html);
	}
    }
    catch (e) {
	alert(e.message);}
    try{
	if (code_html.length > 0) {code_html.evalScripts();}
    }
    catch(e){
	alert("Impossible executer script de la reponse\n"+e.message);}
    window.location.reload();
    
}
function extension_remove(ex_id) {
	var dossier=$("gDossier").value;
    	var queryString='?gDossier='+dossier;
	queryString+='&ex_id='+ex_id;
        queryString+='&action=re'; 	// re for remove extension
	var action=new Ajax.Request ( 'ajax_extension.php',
				      {
					  method:'POST',
					  parameters:queryString,
					  onFailure:errorExtension,
					  onSuccess:successSave
				      }
				      );
	
}
