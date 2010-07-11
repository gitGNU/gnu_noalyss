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

/*!\file
 * \brief javascript script, always added to every page
 *
 */
/**
* callback function when we just need to update a hidden div with an info 
* message
*/
function infodiv(req,json) {
        try{
	var answer=req.responseXML;
	var a=answer.getElementsByTagName('ctl');
	var html=answer.getElementsByTagName('code');
	if ( a.length == 0 ) { var rec=req.responseText;alert ('erreur :'+rec);}
	var name_ctl=a[0].firstChild.nodeValue;
	var code_html=getNodeText(html[0]);
	
	code_html=unescape_xml(code_html);
	g(name_ctl+"info").innerHTML=code_html;
    } 
    catch (e) {
	alert("success_box"+e.message);}
    try{
	code_html.evalScripts();}
    catch(e){
	alert("answer_box Impossible executer script de la reponse\n"+e.message);}

}
 /**
 *@brief delete a row from a table (tb) the input button send the this
 as second parameter
 */
function deleteRow(tb,obj) { 
if (confirm('Confirmez effacement')) 
	{
	var td=obj.parentNode;
	var tr=td.parentNode;
	var lidx=tr.rowIndex;
	 g(tb).deleteRow(lidx);
	}
}
function deleteRowRec(tb,obj) { 
	var td=obj.parentNode;
	var tr=td.parentNode;
	var lidx=tr.rowIndex;
	 g(tb).deleteRow(lidx);
}
/*!\brief remove trailing and heading space
 * \param the string to modify
 * \return string without heading and trailing space
 */
function trim(s) {
    return s.replace(/^\s+/, '').replace(/\s+$/, '');
}

/**
 * @brief retrieve an element thanks its ID
 * @param ID is a string
 * @return the found object of undefined if not found
 */
function g(ID) {
  if (document.getElementById) {
    return document.getElementById(ID);
  } else   if (document.all) {
    return document.all[ID];
  }  else {
    return undefined;
  }
}
/**
 *@brief enable the type of periode
 */
function enable_type_periode() {
	if ( g('type_periode').value == 1 ) {
		g('from_periode').disabled=true;
		g('to_periode').disabled=true;
		g('from_date').disabled=false;
		g('to_date').disabled=false;
		g('p_step').disabled=true;
	} else {
		g('from_periode').disabled=false;
		g('to_periode').disabled=false;
		g('from_date').disabled=true;
		g('to_date').disabled=true;
		g('p_step').disabled=false;
	}
}

/**
 *@brief will reload the window but it is dangerous if we have submitted
 * a form
 */
function refresh_window() {
	window.location.reload();
}

/**
 *@fn encodeJSON(obj)
 *@brief we receive a json object as parameter and the function returns the string
 *       with the format variable=value&var2=val2...
 */
function encodeJSON(obj) {
	if (typeof obj != 'object') {alert('encodeParameter  obj n\'est pas  un objet');}
	try{
		var str='';var e=0;
		for (i in obj){
			if (e != 0 ) {str+='&';} else {e=1;}
			str+=i;
			str+='='+encodeURI(obj[i]);
		}
		return str;
	} catch(e){alert('encodeParameter '+e.message);}
}
function  hide(p_param){
  g(p_param).style.display='none';
}
function show(p_param){
  g(p_param).style.display='block';
}

/**
 *@brief set the focus on the selected field
 *@param Field id of  the control
 *@param selectIt : the value selected in case of Field is a object select, numeric
 */
function SetFocus(Field,SelectIt) {
  var elem = g(Field);
  if (elem) {
    elem.focus();
   }
  return true;
}
 /**
 * @brief set a DOM id with a value in the parent window (the caller),
  @param p_ctl is the name of the control
  @param p_value is the value to set in
 @param p_add if we don't replace the current value but we add something
 */
function set_inparent(p_ctl,p_value,p_add) {
    self.opener.set_value(p_ctl,p_value,p_add);
 }

 /**
 * @brief set a DOM id with a value, it will consider if it the attribute
 	value or innerHTML has be used
  @param p_ctl is the name of the control
  @param p_value is the value to set in
 @param p_add if we don't replace the current value but we add something
 */
function set_value(p_ctl,p_value,p_add) {
	if ( g(p_ctl)) {
		var g_ctrl=g(p_ctl);
		if ( p_add != undefined && p_add==1 ) {
		    if ( g_ctrl.value ) {p_value=g_ctrl.value+','+p_value;}
		}
		if ( g_ctrl.tagName=='INPUT' ) {g(p_ctl).value=p_value;}
		if ( g_ctrl.tagName=='SPAN' ) { g(p_ctl).innerHTML=p_value;}
		if ( g_ctrl.tagName=='SELECT' ) { g(p_ctl).value=p_value;}
	}
}
/**
*@brief format the number change comma to point
*@param HTML obj
*/
function format_number(obj) {
	var value=obj.value;
	value=value.replace(/,/,'.');
	value=parseFloat(value);
	if (  isNaN(value) ) {	value=0; }
	value=Math.round(value*100)/100;
	$(obj).value=value;
}
/**
 *@brief check if the object is hidden or show and perform the opposite,
 * show the hidden obj or hide the shown one
 *@param name of the object
*/
function toggleHideShow(p_obj,p_button) {
	var stat=g(p_obj).style.display;
	var str=g(p_button).value;
	if ( stat == 'none' ) {
	show(p_obj);str=str.replace(/Afficher/,'Cacher');g(p_button).value=str;}
	else {hide(p_obj);str=str.replace(/Cacher/,'Afficher');g(p_button).value=str;}
}
/**
 *@brief open popup with the search windows
 *@param p_dossier the dossier where to search
 *@param p_style style of the detail value are E for expert or S for simple
 */
function openRecherche(p_dossier,p_style) {
  if ( p_style == 'E' ) { p_style="expert";}
  var w=window.open("recherche.php?gDossier="+p_dossier+'&'+p_style,'','statusbar=no,scrollbars=yes,toolbar=no');
  w.focus();
}
/**
 *@brief show the popup. The top property is adapted if you've scrolled the window
 *@param name of the object PHP IPopup
 */
function showIPopup(p_name) {
    var sx=0;
    if ( window.scrollY) { sx=window.scrollY+40;}
	else { sx=document.body.scrollTop+40;}
    $(p_name+'_border').style.top=sx;
    if ( g(p_name+'_fond') ) {show(p_name+'_fond');}
    show(p_name+'_border');
    show(p_name+'_content');
}
/**
 *@brief hide the popup
 *@param name of the object PHP IPopup
 */
function hideIPopup(p_name) {
    if (g(p_name+'_fond')) {hide(p_name+'_fond');}
    if (g(p_name+'_border')) {hide(p_name+'_border');}
    hide(p_name+'_content');
   
}
/**
 *@brief replace the special characters (><'") by their HTML representation
 *@return a string without the offending char.
 */
function unescape_xml(code_html) {
    code_html=code_html.replace(/\&lt;/,'<');
    code_html=code_html.replace(/\&gt;/,'>');
    code_html=code_html.replace(/\&quot;/,'"');
    code_html=code_html.replace(/\&apos;/,"'");
    code_html=code_html.replace(/\&amp;/,'&');
    return code_html;
}
/**
 *@brief Firefox splits the XML into 4K chunk, so to retrieve everything we need
 * to get the different parts thanks textContent
 *@param xmlNode a node (result of var data = =answer.getElementsByTagName('code'))
 *@return all the content of the XML node
*/
function getNodeText(xmlNode)  
 {  
     if(!xmlNode) return '';  
     if(typeof(xmlNode.textContent) != "undefined") { return xmlNode.textContent;  }
     if ( xmlNode.firstChild && xmlNode.firstChild.nodeValue )     return xmlNode.firstChild.nodeValue;  
     return "";
 } 
/**
 *@brief change the periode in the calendar of the dashboard
 *@param object select
 */
function change_month(obj) {
    var queryString="gDossier="+obj.gDossier+"&op=cal"+"&per="+obj.value;
    var action = new Ajax.Request(
	"ajax_misc.php" , { method:'get', parameters:queryString,onFailure:ajax_misc_failure,onSuccess:change_month_success}
    );
   
}
function change_month_success(req) {
        try{
	var answer=req.responseXML;
	var html=answer.getElementsByTagName('code');
	if ( html.length == 0 ) { var rec=req.responseText;alert ('erreur :'+rec);}
	var nodeXml=html[0];
	var code_html=getNodeText(nodeXml);
	code_html=unescape_xml(code_html);
	$("user_cal").innerHTML=code_html;
	} 
    catch (e) {
	alert(e.message);}
    try{
	code_html.evalScripts();}
    catch(e){
	alert("Impossible executer script de la reponse\n"+e.message);}


}
function loading() { return '<image src="image/loading.gif" alt="chargement"></image>';}

function ajax_misc_failure() {
    alert('Ajax Misc failed');
}
/**
 *@brief remove a document_modele
 */
function cat_doc_remove(p_dt_id,p_dossier) {
    var queryString="gDossier="+p_dossier+"&op=rem_cat_doc"+"&dt_id="+p_dt_id;
    var action = new Ajax.Request(
	"ajax_misc.php" , { method:'get', parameters:queryString,onFailure:ajax_misc_failure,onSuccess:success_cat_doc_remove}
				  );
}
function success_cat_doc_remove(req) {
    try{
	var answer=req.responseXML;
	var html=answer.getElementsByTagName('dtid');
	if ( html.length == 0 ) { var rec=req.responseText;alert ('erreur :'+rec);}
	nodeXML=html[0];
	row_id=getNodeText(nodeXML);
	if ( row_id == 'nok') {alert('Error');return;}
	$('row'+row_id).style.textDecoration="line-through";
	$('X'+row_id).style.display='none';
	} 
    catch (e) {	alert(e.message);}
}
/**
 *@brief display the popup with vat and explanation
 *@param obj with 4 attributes gdossier, ctl,popup 
 */
function popup_select_tva(obj) {
    try {
	showIPopup(obj.popup);
	var queryString="gDossier="+obj.gDossier+"&op=dsp_tva"+"&ctl="+obj.ctl+'&popup='+obj.popup;
	if ( obj.jcode ) 
	    queryString+='&code='+obj.jcode;
	if (obj.compute)
	    queryString+='&compute='+obj.compute;
	var action = new Ajax.Request(
				      "ajax_misc.php" , 
				      { method:'get', 
					parameters:queryString,
					onFailure:ajax_misc_failure,
					onSuccess:success_popup_select_tva
				      }
				      );
    } catch (e) {alert("popup_select_tva "+e.message);}
}
/**
 *@brief display the popup with vat and explanations
 */
function success_popup_select_tva(req) {
    try {
	var answer=req.responseXML;
	var popup=answer.getElementsByTagName('popup');
	if ( popup.length == 0 ) { var rec=req.responseText;alert ('erreur :'+rec);}
	var html=answer.getElementsByTagName('code');

	var name_ctl=popup[0].firstChild.nodeValue+'_content';
	var nodeXml=html[0];
	var code_html=getNodeText(nodeXml);
	code_html=unescape_xml(code_html);
	$(name_ctl).innerHTML=code_html;
    } catch (e) {alert("success_popup_select_tva "+e.message);}

}
/**
 *@brief display the popup with vat and explanation
 *@param obj with 4 attributes gdossier, ctl,popup 
 */
function set_tva_label(obj) {
    try {
	var queryString="gDossier="+obj.gDossier+"&op=label_tva"+"&id="+obj.value;
	if ( obj.jcode ) 
	    queryString+='&code='+obj.jcode;
	var action = new Ajax.Request(
				      "ajax_misc.php" , 
				      { method:'get', 
					parameters:queryString,
					onFailure:ajax_misc_failure,
					onSuccess:success_set_tva_label
				      }
				      );
    } catch (e) {alert("set_tva_label "+e.message);}
}
/**
 *@brief display the popup with vat and explanations
 */
function success_set_tva_label(req) {
    try {
	var answer=req.responseXML;
	var code=answer.getElementsByTagName('code');
	var value=answer.getElementsByTagName('value');

	if ( code.length == 0 ) { var rec=req.responseText;alert ('erreur :'+rec);}

	var label_code=code[0].firstChild.nodeValue;
	var label_value=value[0].firstChild.nodeValue;
	set_value(label_code,label_value);
    } catch (e) {alert("success_set_tva_label "+e.message);}

}
/**
 *@brief set loading for waiting
 *@param name of ipopup
 *@see showIPopup
 */
function set_wait(name) {
    var content=name+"_content";
    $(content).innerHTML= '<image src="image/loading.gif" border="0" alt="Chargement...">';
}
/**
 *@brief add dynamically a object for AJAX
 *@param obj. 
 * the attributes are
 * - style to add style
 * - id to add an id
 * - class to add a class
 */
function add_div(obj) {
    try {
	var top=document;
	var elt=top.createElement('div');
	if (obj.id ) { elt.setAttribute('id',obj.id);}
	if (obj.style) {
	    if (elt.style.setAttribute) { /* IE7 bug */
		elt.style.setAttribute('cssText',obj.style);
	    } else { /* good Browser */
		elt.setAttribute('style',obj.style);
	    }
	}
	if (obj.cssclass ) {
	    elt.setAttribute('class',obj.cssclass);/* FF */
	    elt.setAttribute('className',obj.cssclass); /* IE */
	}
	if (obj.html) { elt.innerHTML=obj.html;}

	var bottom_div=document.body;
	bottom_div.appendChild(elt);
	if ( obj.drag ) {
	    new Draggable(obj.id,{starteffect:function(){
		new Effect.Highlight(obj.id,{scroll:window,queue:'end'});  } }
			 );
    	}
    } catch (e) { alert("add_div"+e.message);}
}
/**
 * remove a object created with add_div
 * @param elt id of the elt 
 */
function removeDiv(elt) {
    if (g(elt) ){ document.body.removeChild(g(elt)); }
}
/**
*@brief call add_div to add a DIV and after call the ajax
* the queryString, the callback for function for success and error management
* the method is always GET
*@param obj, the mandatory attributes are
*  - obj.qs querystring
*  - obj.js_success callback function in javascript for handling the xml answer
*  - obj.js_error callback function for error
*  - obj.callback the php file to call
*  - obj.fixed optional let you determine the position, otherwise works like IPopup
*@see add_div IBox
*/
function show_box(obj) {
    add_div(obj) ;
    if ( ! obj.fixed )  {
	var sx=0;
	if ( window.scrollY) { sx=window.scrollY+40;}
	else { sx=document.body.scrollTop+40;}
	g(obj.id).style.top=sx;
	show(obj.id);
    } else {
	show(obj.id);
    }

    var action=new Ajax.Request (
                               obj.callback,
                               {
                                 method:'GET',
                                 parameters:obj.qs,
                                 onFailure:eval(obj.js_error),
                                 onSuccess:eval(obj.js_success)
                               });
}
/**
 *@brief receive answer from ajax and just display it into the IBox
 * XML must contains at least 2 fields : code is the ID of the IBOX and 
 * html which is the contain
 */
function success_box(req,json)
{
    try{
	var answer=req.responseXML;
	var a=answer.getElementsByTagName('ctl');
	var html=answer.getElementsByTagName('code');
	if ( a.length == 0 ) { var rec=req.responseText;alert ('erreur :'+rec);}
	var name_ctl=a[0].firstChild.nodeValue;
	var code_html=getNodeText(html[0]);
	
	code_html=unescape_xml(code_html);
	g(name_ctl).innerHTML=code_html;
	g(name_ctl).style.height='auto';
	// if IE set to 60 %
	if(navigator.appName == "Microsoft Internet Explorer")
	    g(name_ctl).style.width='60%';
	else
	    g(name_ctl).style.width='auto';

    } 
    catch (e) {
	alert("success_box"+e.message);}
    try{
	code_html.evalScripts();}
    catch(e){
	alert("answer_box Impossible executer script de la reponse\n"+e.message);}
}

function error_box () {
    alert('IBOX : error_box ajax not implemented');
}
/**
* show the ledger choice
*/
function show_ledger_choice() {
    g('div_jrn').style.visibility='visible';
}
/**
* hide the ledger choice
*/
function hide_ledger_choice() {
    g('div_jrn').style.visibility='hidden';
}
/**
* show the cat of ledger choice
*/
function show_cat_choice() {
    g('div_cat').style.visibility='visible';
}
/**
* hide the cat of ledger choice
*/
function hide_cat_choice() {
    g('div_cat').style.visibility='hidden';
}