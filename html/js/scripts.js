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
<<<<<<< .working
function GetFormField(Field) {
  var elem = document.forms ? document.forms[0] : false;
  return elem.elements ? elem.elements[Field] : false;
}

function AttachEvent(Obj, EventType, Function, Capture) {
  Capture = Capture || false;
  if (Obj == document && !document.all) { Obj = window; }
  if (Obj.addEventListener) {
    Obj.addEventListener(EventType, Function, Capture);
  } else if (Obj.attachEvent) {
    Obj.attachEvent("on" + EventType, Function);
  }
}

function SetFocus(Field,SelectIt) {
  var elem = GetFormField(Field);
  if (!elem) { elem = g(Field); }

  if (elem) {
    elem.focus();
    if (SelectIt > 0 && elem.select) {
      elem.select();
    }
    var Type = elem.type ? elem.type : false;
    if (SelectIt <= 0 && Type && (Type == "text" || Type == "textarea")) {
      // IE version
      var TxtPos = SelectIt < 0 ? elem.value.length : 0;
      if (elem.createTextRange) {
        var rng = elem.createTextRange()
        rng.collapse();
        rng.moveStart("character", TxtPos);
        rng.select();
      // Moz version
      } else if (elem.setSelectionRange) {
        elem.setSelectionRange(TxtPos,TxtPos);
        elem.focus();
      }
    }
  }
  return true;
}

function HandleSubmit(e) {
  SubmitButton = g('SubmitButton');
  var code = e.charCode || e.keyCode;
  if ( (code == 13) && e.ctrlKey ) 
  {     
    SubmitButton.click();    
    return true;
  }
}

/*!\brief add a row for the CA
 * \param p_table_id
 * \param p_amount amount to reach
 * \param p_count count of col.
 */
function add_row(p_table,p_seq,p_count) {
  var elt=document.getElementById("nb_"+p_table);


  if ( ! elt ) {return;}

  // number of elt = elt.value
  var old_value=elt.value;
  var new_value=1*elt.value+1;
  if ( new_value > 4 ) { 
	alert("Maximum 4 lignes ");
	return;
  }
  elt.value=new_value;
  // For the detail view (modify_op) there is several form and then several time the 
  // element
  var all_elt=document.getElementsByName("nb_"+p_table);
  for (var e=0;e<all_elt.length;e++) {
    all_elt[e].value=new_value;
  }
  var tbody=document.getElementById(p_table).getElementsByTagName("tbody")[0];
  var row=document.createElement("TR");
  for ( i=1;i<=p_count;i++) {
	var cell=document.createElement("TD");
	var col=document.getElementById(p_table+"td"+i+'c1');
	var txt=col.innerHTML;
	txt=txt.replace(/row_1/g,"row_"+new_value);
	cell.innerHTML=txt;

	row.appendChild(cell); }

  // create the amount cell
  var cell_montant=document.createElement("TD");
  cell_montant.innerHTML='<input type="TEXT" name="val'+p_seq+"l"+new_value+'" id="val'+p_seq+"l"+new_value+'" size="6"  style="border:solid 1px blue;" >';
  row.appendChild(cell_montant);
  tbody.appendChild(row);

}
/*! 
 * \brief Check the amount of the CA
 * \param p_style : error or ok, if ok show a ok box if the amount are equal
 *
 *
 * \return true the amounts are equal
 */


function verify_ca(p_style) {
  var nb_item=document.getElementById('nb_item').value;

  for ( var item=0;item<=nb_item-1;item++) {
      if ( document.getElementById('nb_t'+item) ) {
	  var nb_row=1*document.getElementById('nb_t'+item).value;
	  var amount=1*document.getElementById('amount_t'+item).value;
	  var get=0;
	  for (var row=1;row <= nb_row;row++) {
	      
	      if ( document.getElementById('ta_'+item+'o1row_'+row).value != -1) {
		  val=document.getElementById('val'+item+'l'+row).value;
		  if ( isNaN(val)) {		continue;}
		  get=get+(val*1);
	      } else {
		  get=amount;
	      }
	  }
	  if ( Math.round(get,2) != Math.round(amount,2) ) {
	      diff=Math.round(get,2)-Math.round(amount,2);
	      alert ("montant differents \ntotal CA="+get+"\ntotal Operation "+amount+"\nDiff = "+diff);
	      return false;
	  }else {
	      if ( p_style=='ok') {
		  alert('les montants correspondent');
	      }
	  }
      }
  }
  return true;
}
/*! 
 * \brief open a window for searching a CA account, 
 * \param p_sessid PHPSESSID
 * \param p_dossier dossier id
 * \param p_target ctrl to update
 * \param p_source ctrl containing the pa_id
 * 
 *
 * \return
 */
function search_ca (p_sessid,p_dossier,p_target,p_source)
{
  var pa_id=document.getElementById(p_source).value;

  var url="?PHPSESSID="+p_sessid+"&gDossier="+p_dossier+"&c1="+p_target+"&c2="+pa_id;
  var a=window.open("search_ca.php"+url,"CA recherche",'statusbar=no,scrollbars=yes,toolbar=no');
  a.focus();
}

/*! 
 * \brief set a ctrl is the caller windows
 * \param p_ctrl the control to change
 * \param p_value the value the control will contains
 * \param
 * 
 *
 * \return none
 */

function ca_set_child(p_ctl,p_value) {

  self.opener.ca_set_parent(p_ctl,p_value);
	window.close();
}
/*! 
 * \brief this script is in the parent windows and it is called by SetItChild
 * \param \see ca_set_child
 *
 * \return none
 */

function ca_set_parent(p_ctl,p_value) {

	var f=document.getElementById(p_ctl);
	f.value=p_value;
	
}
/*! \brief import : update a record (in ajax)
 *  \param $p_sessid : PHPSESSID
 * \param $p_dossier : the dossier id
 * \param $p_count : the item number
 */

function import_update(p_sessid,p_dossier,p_count) {
  var query_string="PHPSESSID="+p_sessid+"&count="+p_count+"&gDossier="+p_dossier;
  var code=$("code"+p_count);
  var poste=$("poste"+p_count);
  var concerned=$("e_concerned"+p_count);
  var url="ajax_import.php";
  query_string+="&code="+code.value;
  query_string+="&poste="+poste.value;
  query_string+="&concerned="+concerned.value;
  query_string+="&action=update";


  /* call the script which handle the query */
  var update= new Ajax.Request (
				url, 
				{
				method:'get',
				parameters:query_string
				}
				);
  var form=$("form_"+p_count);
  form.hide();
}
/*! \brief remove : remove a record (in ajax)
 *  \param $p_sessid : PHPSESSID
 * \param $p_dossier : the dossier id
 * \param $p_count : the item number
 */

function import_remove(p_sessid,p_dossier,p_count) {
  var query_string="PHPSESSID="+p_sessid+"&count="+p_count+"&gDossier="+p_dossier;
  var code=$("code"+p_count);
  var url="ajax_import.php";
  query_string+="&code="+code.value;
  query_string+="&action=delete";
  var a = confirm("Etes-vous certain d'effacer cette operation ?");
  if ( a == false ) { return;}

  /* call the script which handle the query */
  var update= new Ajax.Request (
				url, 
				{
				method:'get',
				parameters:query_string
				}
				);
  var form=$("form_"+p_count);
  form.hide();
}
/*! \brief remove : remove a record (in ajax)
 *  \param $p_sessid : PHPSESSID
 * \param $p_dossier : the dossier id
 * \param $p_count : the item number
 */

function import_not_confirmed(p_sessid,p_dossier,p_count) {
  var query_string="PHPSESSID="+p_sessid+"&count="+p_count+"&gDossier="+p_dossier;
  var code=$("code"+p_count);
  var url="ajax_import.php";
  query_string+="&code="+code.value;
  query_string+="&action=not_confirmed";

  /* call the script which handle the query */
  var update= new Ajax.Request (
				url, 
				{
				method:'get',
				parameters:query_string
				}
				);
  var form=$("form_"+p_count);
  form.hide();
}
=======
>>>>>>> .merge-right.r2625
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
<<<<<<< .working
function ledger_sold_add_row(){
   style='class="input_text"';
   var mytable=$("sold_item").tBodies[0];
   var line=mytable.rows.length;
   var phpsessid=$("phpsessid");
   var nb=$("nb_item");

  var newNode = mytable.rows[1].cloneNode(true);
  mytable.appendChild(newNode);

  var tt=mytable.rows[1].innerHTML;
   var new_tt=tt.replace(/march0/g,"march"+nb.value);
   new_tt=new_tt.replace(/e_march0_label/g,"e_march"+nb.value+'_label');
   new_tt=new_tt.replace(/quant0/g,"quant"+nb.value);
   new_tt=new_tt.replace(/sold\(0\)/g,"sold("+nb.value+")");

   newNode.innerHTML=new_tt;
    $("e_march"+nb.value+"_label").innerHTML='&nbsp;';
    $("e_march"+nb.value+"_price").value='0';
    $("e_march"+nb.value).value="";
    $("e_quant"+nb.value).value="1";
   
  nb.value++;
=======
function refresh_window() {
	window.location.reload();
>>>>>>> .merge-right.r2625
}

/**
 *@brief we receive a json object as parameter and the function returns the string
 *       with the format variable=value&var2=val2...
 */
var encodeJSON=function(obj) {
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
var hide=function(p_param){
  g(p_param).style.display='none';
}
var show=function(p_param){
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
 *@param p_sessid,the PHPSESSID
 *@param p_dossier the dossier where to search
 *@param p_style style of the detail value are E for expert or S for simple
 */
function openRecherche(p_sessid,p_dossier,p_style) {
  if ( p_style == 'E' ) { p_style="expert";}
  var w=window.open("recherche.php?gDossier="+p_dossier+"&PHPSESSID="+p_sessid+'&'+p_style,'','statusbar=no,scrollbars=yes,toolbar=no');
  w.focus();
}
<<<<<<< .working

<<<<<<< .working
/**
<<<<<<< .working
 * @brief callback error function for  compute_sold
 */
function error_compute_purchase(request,json) {
  alert('Ajax does not work');
}
function compute_all_purchase() {
    var loop=0;
    for (loop=0;loop<$("nb_item").value;loop++){
	compute_purchase(loop);
    }
    var tva=0; var htva=0;var tvac=0;
    
    for (var i=0;i<$("nb_item").value;i++) {
	if ( $('tva_march') ) tva+=$('tva_march'+i).value*1;
	htva+=$('htva_march'+i).value*1;
	tvac+=$('tvac_march'+i).value*1;
    }
    
    if ( $('tva') ) $('tva').innerHTML=Math.round(tva*100)/100;
    $('htva').innerHTML=Math.round(htva*100)/100;
    if ($('tvac'))$('tvac').innerHTML=Math.round(tvac*100)/100;


}

function clean_tva(p_ctl) {
if ( $('e_march'+p_ctl+'_tva_amount') )$('e_march'+p_ctl+'_tva_amount').value=0;
}

function clean_sold( p_ctl_nb) 
{
    if ( $("e_march"+p_ctl_nb) ) { $("e_march"+p_ctl_nb).value=trim($("e_march"+p_ctl_nb).value); }
    if ($('e_march'+p_ctl_nb+'_price')) { $('e_march'+p_ctl_nb+'_price').value='';}
    if ( $('e_quant'+p_ctl_nb)) { $('e_quant'+p_ctl_nb).value='1'; }
    if ( $('tva_march'+p_ctl_nb+'_show') ) { $('tva_march'+p_ctl_nb+'_show').value='0';}
    if ($('tva_march'+p_ctl_nb)) { $('tva_march'+p_ctl_nb).value=0;}
    if ( $('htva_march'+p_ctl_nb)) { $('htva_march'+p_ctl_nb).value=0;}
    if ( $('tvac_march'+p_ctl_nb)) {$('tvac_march'+p_ctl_nb).value=0;}

}
function clean_purchase(p_ctl_nb) {
    var qcode=$("e_march"+p_ctl_nb).value;
    $('e_march'+p_ctl_nb+'_price').value='';
    $('e_quant'+p_ctl_nb).value='1';
    if ( $('e_march'+p_ctl_nb+'_tva_amount') )$('e_march'+p_ctl_nb+'_tva_amount').value='0';
    if (  $('tva_march'+p_ctl_nb)) $('tva_march'+p_ctl_nb).value=0;
    if ($('htva_march'+p_ctl_nb)) $('htva_march'+p_ctl_nb).value=0;
    if( $('tvac_march'+p_ctl_nb)) $('tvac_march'+p_ctl_nb).value=0;
}
/**
 * @brief add a line in the form for the quick_writing
 */
function quick_writing_add_row(){
   style='class="input_text"';
   var mytable=$("quick_item").tBodies[0];
   var line=mytable.rows.length;
   var row=mytable.insertRow(line);
   var phpsessid=$("phpsessid");
   var nb=$("nb_item");

  var newNode = mytable.rows[1].cloneNode(true);
  var tt=newNode.innerHTML;
  mytable.appendChild(newNode);


  new_tt=tt.replace(/qc_0/g,"qc_"+nb.value);
  new_tt=new_tt.replace(/amount0/g,"amount"+nb.value);
  new_tt=new_tt.replace(/poste0/g,"poste"+nb.value);
  new_tt=new_tt.replace(/ck0/g,"ck"+nb.value);
  new_tt=new_tt.replace(/ld0/g,"ld"+nb.value);


  newNode.innerHTML=new_tt;
    $("qc_"+nb.value).value='';
    $("amount"+nb.value).value='';
    $("poste"+nb.value).value='';
    $("ld"+nb.value).value='';
 
  nb.value++;

}
function my_clear(p_ctrl) {
	if ( document.getElementById(p_ctrl)){
    document.getElementById(p_ctrl).value="";
	}
}
<<<<<<< .working
/**
 *@brief enable the type of periode
 */
function enable_type_periode() {
	if ( g('type_periode').value == 1 ) {
		g('from_periode').disabled=true;
		g('to_periode').disabled=true;
		g('from_date').disabled=false;
		g('to_date').disabled=false;
	} else {
		g('from_periode').disabled=false;
		g('to_periode').disabled=false;
		g('from_date').disabled=true;
		g('to_date').disabled=true;
	}
}
=======
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
>>>>>>> .merge-right.r2573

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
=======
>>>>>>> .merge-right.r2625
=======
/**
 *@brief show the popup
=======
 *@brief show the popup. The top property is adapted if you've scrolled the window
>>>>>>> .merge-right.r2857
 *@param name of the object PHP IPopup
 */
function showIPopup(p_name) {
    var sx=0;
    if ( window.scrollY) { sx=window.scrollY+40;}
	else { sx=document.body.scrollTop+40;}
    $(p_name+'_border').style.top=sx;
    show(p_name+'_fond');
    show(p_name+'_border');
    show(p_name+'_content');
}
<<<<<<< .working
>>>>>>> .merge-right.r2645
=======
/**
 *@brief hide the popup
 *@param name of the object PHP IPopup
 */
function hideIPopup(p_name) {
    hide(p_name+'_fond');
    hide(p_name+'_border');
    hide(p_name+'_content');
    $(p_name+'_content').innerHTML='<image src="image/loading.gif" border="0" alt="Chargement...">';
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
     if(typeof(xmlNode.textContent) != "undefined") return xmlNode.textContent;  
     return xmlNode.firstChild.nodeValue;  
 } 
/**
 *@brief change the periode in the calendar of the dashboard
 *@param object select
 */
function change_month(obj) {
    var queryString="PHPSESSID="+obj.phpsessid+"&gDossier="+obj.gDossier+"&op=cal"+"&per="+obj.value;
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

function ajax_misc_failure() {
    alert('Ajax Misc failed');
}>>>>>>> .merge-right.r2857
