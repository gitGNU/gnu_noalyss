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

/* !\file 
 */

/* \brief javascript script, always added to every page
 *
 */

trim = function(p_value) {
	return p_value.replace(/^\s+|\s+$/g,"");
}//scripts library.

// Set the focus to the specified field,
// and select it if requested by SelectIt
function g(ID) {
  if (document.all) {
    return document.all[ID];
  } else if (document.getElementById) {
    return document.getElementById(ID);
  } else {
    return undefined;
  }
}
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
  if (!elem) { elem = GetID(Field); }

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
  SubmitButton = GetID('SubmitButton');
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
				parameters:query_string,
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
				parameters:query_string,
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
				parameters:query_string,
				}
				);
  var form=$("form_"+p_count);
  form.hide();
}
/**
 * @brief add a line in the form for the report 
 * @param p_dossier dossier id to connect
 * @param p_sessid session id
 */
function rapport_add_row(p_dossier,p_sessid){
   style='style="border: 1px solid blue;"';
   var table=$("rap1");
   var line=table.rows.length;

   var row=table.insertRow(line);
   // left cell
  var cellPos = row.insertCell(0);
  cellPos.innerHTML='<input type="text" '+style+' size="3" id="pos'+line+'" name="pos'+line+'" value="'+line+'">';

  // right cell
  var cellName = row.insertCell(1);
  cellName.innerHTML='<input type="text" '+style+' size="50" id="text'+line+'" name="text'+line+'">';

  // Formula
  var cellFormula = row.insertCell(2);
  cellFormula.innerHTML='<input type="text" '+style+' size="35" id="form'+line+'"  name="form'+line+'">';

  // Search Button
  var cellSearch = row.insertCell(3);
   cellSearch.innerHTML='<input type="button" value="Recherche Poste" onclick="SearchPoste(\''+p_sessid+'\','+p_dossier+',\'form'+line+'\',\'\',\'poste\',\'N\')" class="inp"/>';

}

/**
 * @brief create a file to export a report
 * @param p_sessid the Session id
 * @param p_dossier the dossier id
 */
function report_export(p_sessid,p_dossier,p_fr_id) {
  var queryString="?PHPSESSID="+p_sessid+"&gDossier="+p_dossier+"&f="+p_fr_id;
  var action=new Ajax.Request(
			      "ajax_report.php",
			      {
			      method:'get',
			      parameters:queryString,
			      onSuccess:report_export_success
			      }
			      );
  
}
/**
 * @brief callback function for exporting a report
 * @param request object request
 * @param json json answer
 */
function report_export_success(request,json) {
  var answer = request.responseText.evalJSON(true);
  var ok=answer.answer;
  var link=answer.link;
  $('export').hide();
  $('export_link').innerHTML='<a class="mtitle" href="'+link+'"> Cliquez ici pour télécharger le rapport</a>';
}

/**
 * @brief add a line in the form for the sold ledger
 */
function ledger_sold_add_row(){
   style='class="input_text"';
   var mytable=$("sold_item").tBodies[0];
   var line=mytable.rows.length;
   var row=mytable.insertRow(line);
   var phpsessid=$("phpsessid");
   var nb=$("nb_item");

  var newNode = mytable.rows[1].cloneNode(true);
  var tt=newNode.innerHTML;
  mytable.appendChild(newNode);


  new_tt=tt.replace(/march0/g,"march"+nb.value);
  new_tt=new_tt.replace(/quant0/g,"quant"+nb.value);
  new_tt=new_tt.replace(/sold\(0\)/g,"sold("+nb.value+")");

  newNode.innerHTML=new_tt;
    $("e_march"+nb.value+"_label").innerHTML='&nbsp;';
    $("e_march"+nb.value+"_price").value='0';
    $("e_march"+nb.value).value="";
    $("e_quant"+nb.value).value="1";

  nb.value++;

}
function compute_all_sold() {
    var loop=0;
    for (loop=0;loop<$("nb_item").value;loop++){
	compute_sold(loop);
    }

}
/**
 * @brief compute the sum of a sold, update the span tvac, htva and tva
 * all the needed data are taken from the document (hidden field : phpsessid and gdossier)
 * @param the number of the changed ctrl
 */
function compute_sold(p_ctl_nb) {
    var phpsessid=$("phpsessid").value;
    var dossier=$("gDossier").value;

    $("e_march"+p_ctl_nb).value=trim($("e_march"+p_ctl_nb).value);
    var qcode=$("e_march"+p_ctl_nb).value;

    if ( qcode.length == 0 ) {
	clean_sold(p_ctl_nb);
	refresh_sold();
	return;
    }
    var tva_id=-1;
    if ($('e_march'+p_ctl_nb+'_tva_id')) tva_id=$('e_march'+p_ctl_nb+'_tva_id').value;
	
    $('e_march'+p_ctl_nb+'_price').value=trim($('e_march'+p_ctl_nb+'_price').value);

    var price=$('e_march'+p_ctl_nb+'_price').value;
    $('e_quant'+p_ctl_nb).value=trim($('e_quant'+p_ctl_nb).value);
    var quantity=$('e_quant'+p_ctl_nb).value;

  var querystring='?PHPSESSID='+phpsessid+'&gDossier='+dossier+'&c='+qcode+'&t='+tva_id+'&p='+price+'&q='+quantity+'&n='+p_ctl_nb;
    $('sum').hide();
  var action=new Ajax.Request(
			      "compute.php",
			      { 
			      method:'get',
			      parameters:querystring,
			      onFailure:error_compute_sold,
			      onSuccess:success_compute_sold
			      }
			      );
  }
function refresh_sold () {
  var tva=0; var htva=0;var tvac=0;

  for (var i=0;i<$("nb_item").value;i++) {
    if ( $('tva_march'+i) ) tva+=$('tva_march'+i).value*1;
    if ($('htva_march'+i)) htva+=$('htva_march'+i).value*1;
    if ($('tvac_march'+i)) tvac+=$('tvac_march'+i).value*1;
    if ($('tva_march'+i+'_show')) $('tva_march'+i+'_show').value=$('tva_march'+i).value;

  }

    if ( $('tva')) $('tva').innerHTML=Math.round(tva*100)/100;
    if ( $('htva')) $('htva').innerHTML=Math.round(htva*100)/100;
    if ($('tvac')) $('tvac').innerHTML=Math.round(tvac*100)/100;
}
/**
 * @brief update the field htva, tva_id and tvac, callback function for  compute_sold
 */
function success_compute_sold(request,json) {
	var answer=request.responseText.evalJSON(true);
	var rtva=answer.tva*1;
	var rhtva=answer.htva*1;
	var rtvac=answer.tvac*1;
	var ctl=answer.ctl;
	$('sum').show();
	if($('tva_march'+ctl))  $('tva_march'+ctl).value=rtva;
	if($('htva_march'+ctl))  $('htva_march'+ctl).value=rhtva;
	if ($('tvac_march'+ctl)) $('tvac_march'+ctl).value=rtvac;
	refresh_sold();
}

/**
 * @brief callback error function for  compute_sold
 */
function error_compute_sold(request,json) {
  alert('Ajax does not work');
}
/**
* @brief update the list of available predefined operation when we change the ledger. 
*/
function update_predef(p_type,p_direct) {
    var jrn=$("p_jrn").value;
    var phpsessid=$("phpsessid").value;
    var dossier=$("gDossier").value;
    var querystring='?PHPSESSID='+phpsessid+'&gDossier='+dossier+'&l='+jrn+'&t='+p_type+'&d='+p_direct;
    $("p_jrn_predef").value=jrn;
  var action=new Ajax.Request(
			      "get_predef.php",
			      { 
			      method:'get',
			      parameters:querystring,
			      onFailure:error_get_predef,
			      onSuccess:success_get_predef
			      }
  );
}
/**
 * @brief update the field predef
 */
function success_get_predef(request,json) {
    var i=0;
  var answer=request.responseText.evalJSON(true);
    obj=$("pre_def");
    obj.innerHTML='';
    if ( answer.count == 0 ) return;

    for ( i = 0 ; i < answer.count;i++) {
	value=answer['value'+i];
	label=answer['label'+i];
	obj.options[obj.options.length]=new Option(label,value);
    }

}
/**
 * @brief update the field predef
 */
function error_get_predef(request,json) {
    alert ("Erreur mise à jour champs non possible");

}
/**
* @brief update the list of available predefined operation when we change the ledger. 
*/
function update_pj() {
    var jrn=$("p_jrn").value;
    var phpsessid=$("phpsessid").value;
    var dossier=$("gDossier").value;
    var querystring='?PHPSESSID='+phpsessid+'&gDossier='+dossier+'&l='+jrn;
  var action=new Ajax.Request(
			      "get_pj.php",
			      { 
			      method:'get',
			      parameters:querystring,
			      onFailure:error_get_pj,
			      onSuccess:success_get_pj
			      }
  );
}
/**
 * @brief update the field predef
 */
function success_get_pj(request,json) {

    var answer=request.responseText.evalJSON(true);
    obj=$("e_pj");
    obj.value='';
    if ( answer.count == 0 ) return;
    obj.value=answer.pj;
    $("e_pj_suggest").value=answer.pj;
}
/**
 * @brief update the field predef
 */
function error_get_pj(request,json) {
	alert("Ajax a echoue");
}

/**
 * @brief add a line in the form for the ledger fin
 */
function ledger_fin_add_row(){
    style='class="input_text"';
    var mytable=$("fin_item").tBodies[0];
    var line=mytable.rows.length;
    var row=mytable.insertRow(line);
    var phpsessid=$("phpsessid");
    var nb=$("nb_item");
    
    var newNode = mytable.rows[1].cloneNode(true);
    var tt=newNode.innerHTML;
    mytable.appendChild(newNode);
    
    
    new_tt=tt.replace(/e_other0/g,"e_other"+nb.value);
    new_tt=new_tt.replace(/e_other0_comment/g,"e_other"+nb.value+'_comment');
    new_tt=new_tt.replace(/e_other0_amount/g,"e_other"+nb.value+'_amount');
    new_tt=new_tt.replace(/e_concerned0/g,"e_concerned"+nb.value);
    new_tt=new_tt.replace(/e_other0_label/g,"e_other"+nb.value+'_label');
    newNode.innerHTML=new_tt;
    $("e_other"+nb.value+'_label').innerHTML="";
    nb.value++;

}
/**
 * @brief add a line in the form for the purchase ledger
 */
function ledger_purchase_add_row(){
	style='class="input_text"';
	var mytable=$("sold_item").tBodies[0];
	var line=mytable.rows.length;
	var row=mytable.insertRow(line);
	var phpsessid=$("phpsessid");
	var nb=$("nb_item");
	var newNode = mytable.rows[1].cloneNode(true);
	var tt=newNode.innerHTML;
	mytable.appendChild(newNode);
	new_tt=tt.replace(/march0/g,"march"+nb.value);
	new_tt=new_tt.replace(/quant0/g,"quant"+nb.value);
	new_tt=new_tt.replace(/sold\(0\)/g,"sold("+nb.value+")");
	newNode.innerHTML=new_tt;
	$("e_march"+nb.value+"_label").innerHTML='&nbsp;';
	$("e_march"+nb.value+"_price").value='0';
	$("e_march"+nb.value).value="";
	$("e_quant"+nb.value).value="1";
	nb.value++;

}
/**
 * @brief compute the sum of a purchase, update the span tvac, htva and tva
 * all the needed data are taken from the document (hidden field : phpsessid and gdossier)
 * @param the number of the changed ctrl
 */
function compute_purchase(p_ctl_nb) {
    var phpsessid=$("phpsessid").value;
    var dossier=$("gDossier").value;
	var a=-1;
	if ( document.getElementById("e_march"+p_ctl_nb+'_tva_amount')) {
		a=trim($("e_march"+p_ctl_nb+'_tva_amount').value);
		$("e_march"+p_ctl_nb+'_tva_amount').value=a;
	}
    $("e_march"+p_ctl_nb).value=trim($("e_march"+p_ctl_nb).value);
    var qcode=$("e_march"+p_ctl_nb).value;

    if ( qcode.length == 0 ) { clean_purchase(p_ctl_nb);refresh_purchase();return;}
	var tva_id=-1;
	if ( $('e_march'+p_ctl_nb+'_tva_id') ) {
		tva_id=$('e_march'+p_ctl_nb+'_tva_id').value;
	}

    $('e_march'+p_ctl_nb+'_price').value=trim($('e_march'+p_ctl_nb+'_price').value);
    var price=$('e_march'+p_ctl_nb+'_price').value;

    $('e_quant'+p_ctl_nb).value=trim($('e_quant'+p_ctl_nb).value);
    var quantity=$('e_quant'+p_ctl_nb).value;
    var querystring='?PHPSESSID='+phpsessid+'&gDossier='+dossier+'&c='+qcode+'&t='+tva_id+'&p='+price+'&q='+quantity+'&n='+p_ctl_nb;
    $('sum').hide();
    var action=new Ajax.Request(
			      "compute.php",
	{ 
	    method:'get',
	    parameters:querystring,
	    onFailure:error_compute_purchase,
	    onSuccess:success_compute_purchase
	}
    );
}
function refresh_purchase() {
  var tva=0; var htva=0;var tvac=0;

  for (var i=0;i<$("nb_item").value;i++) {
    if( $('tva_march'+i))  tva+=$('tva_march'+i).value*1;
    htva+=$('htva_march'+i).value*1;
    tvac+=$('tvac_march'+i).value*1;
  }

    if ( $('tva') ) $('tva').innerHTML=Math.round(tva*100)/100;
    $('htva').innerHTML=Math.round(htva*100)/100;
    if ($('tvac'))    $('tvac').innerHTML=Math.round(tvac*100)/100;
}
/**
 * @brief update the field htva, tva_id and tvac, callback function for  compute_sold
 * it the field TVA in the answer contains NA it means that VAT is appliable and then do not
 * update the VAT field except htva_martc
 */
function success_compute_purchase(request,json) {

  var answer=request.responseText.evalJSON(true);
  var ctl=answer.ctl;
  var rtva=answer.tva;
  var rhtva=answer.htva*1;
  var rtvac=answer.tvac*1;

  if ( rtva == 'NA' ) {
	var rhtva=answer.htva*1;
	$('htva_march'+ctl).value=rhtva;
	$('tvac_march'+ctl).value=rtvac;
	$('sum').show();
	refresh_purchase();

	return;
  }
  rtva=answer.tva*1;
  
 
  
  $('sum').show();
  if ( $('e_march'+ctl+'_tva_amount').value=="" ||  $('e_march'+ctl+'_tva_amount').value==0 ){
			$('tva_march'+ctl).value=rtva;
			$('e_march'+ctl+'_tva_amount').value=rtva;
	}
	else {
		$('tva_march'+ctl).value=$('e_march'+ctl+'_tva_amount').value;
	}
	$('htva_march'+ctl).value=rhtva;
	$('tvac_march'+ctl).value=parseFloat($('htva_march'+ctl).value)+parseFloat($('tva_march'+ctl).value);
	
    refresh_purchase();
}

/**
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


