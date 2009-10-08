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
 * \brief javascript script for the ledger in accountancy,
 * compute the sum, add a row at the table..
 *
 */
/**
 * @brief add a line in the form for the sold ledger
 */
function ledger_sold_add_row(){
   style='class="input_text"';
   var mytable=g("sold_item").tBodies[0];
   var line=mytable.rows.length;
   var row=mytable.insertRow(line);
   var phpsessid=g("phpsessid");
   var nb=g("nb_item");

  var newNode = mytable.rows[1].cloneNode(true);
  var tt=newNode.innerHTML;
  mytable.appendChild(newNode);


  new_tt=tt.replace(/march0/g,"march"+nb.value);
  new_tt=new_tt.replace(/quant0/g,"quant"+nb.value);
  new_tt=new_tt.replace(/sold\(0\)/g,"sold("+nb.value+")");

  newNode.innerHTML=new_tt;
    g("e_march"+nb.value+"_label").innerHTML='&nbsp;';
    g("e_march"+nb.value+"_price").value='0';
    g("e_march"+nb.value).value="";
    g("e_quant"+nb.value).value="1";

  nb.value++;

}
function compute_all_sold() {
    var loop=0;
    for (loop=0;loop<g("nb_item").value;loop++){
	compute_sold(loop);
    }

}
/**
 * @brief compute the sum of a sold, update the span tvac, htva and tva
 * all the needed data are taken from the document (hidden field : phpsessid and gdossier)
 * @param the number of the changed ctrl
 */
function compute_sold(p_ctl_nb) {
    var phpsessid=g("phpsessid").value;
    var dossier=g("gDossier").value;

    g("e_march"+p_ctl_nb).value=trim(g("e_march"+p_ctl_nb).value);
    var qcode=g("e_march"+p_ctl_nb).value;

    if ( qcode.length == 0 ) {
	clean_sold(p_ctl_nb);
	refresh_sold();
	return;
    }
    var tva_id=-1;
    if (g('e_march'+p_ctl_nb+'_tva_id')) tva_id=g('e_march'+p_ctl_nb+'_tva_id').value;
	
    g('e_march'+p_ctl_nb+'_price').value=trim(g('e_march'+p_ctl_nb+'_price').value);

    var price=g('e_march'+p_ctl_nb+'_price').value;
    g('e_quant'+p_ctl_nb).value=trim(g('e_quant'+p_ctl_nb).value);
    var quantity=g('e_quant'+p_ctl_nb).value;

  var querystring='?PHPSESSID='+phpsessid+'&gDossier='+dossier+'&c='+qcode+'&t='+tva_id+'&p='+price+'&q='+quantity+'&n='+p_ctl_nb;
    g('sum').hide();
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

  for (var i=0;i<g("nb_item").value;i++) {
    if ( g('tva_march'+i) ) tva+=g('tva_march'+i).value*1;
    if (g('htva_march'+i)) htva+=g('htva_march'+i).value*1;
    if (g('tvac_march'+i)) tvac+=g('tvac_march'+i).value*1;
    if (g('tva_march'+i+'_show')) g('tva_march'+i+'_show').value=g('tva_march'+i).value;

  }

    if ( g('tva')) g('tva').innerHTML=Math.round(tva*100)/100;
    if ( g('htva')) g('htva').innerHTML=Math.round(htva*100)/100;
    if (g('tvac')) g('tvac').innerHTML=Math.round(tvac*100)/100;
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
	g('sum').show();
	if(g('tva_march'+ctl))  g('tva_march'+ctl).value=rtva;
	if(g('htva_march'+ctl))  g('htva_march'+ctl).value=rhtva;
	if (g('tvac_march'+ctl)) g('tvac_march'+ctl).value=rtvac;
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
    var jrn=g("p_jrn").value;
    var phpsessid=g("phpsessid").value;
    var dossier=g("gDossier").value;
    var querystring='?PHPSESSID='+phpsessid+'&gDossier='+dossier+'&l='+jrn+'&t='+p_type+'&d='+p_direct;
    g("p_jrn_predef").value=jrn;
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
    obj=g("pre_def");
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
    var jrn=g("p_jrn").value;
    var phpsessid=g("phpsessid").value;
    var dossier=g("gDossier").value;
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
    obj=g("e_pj");
    obj.value='';
    if ( answer.count == 0 ) return;
    obj.value=answer.pj;
    g("e_pj_suggest").value=answer.pj;
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
    var mytable=g("fin_item").tBodies[0];
    var line=mytable.rows.length;
    var row=mytable.insertRow(line);
    var phpsessid=g("phpsessid");
    var nb=g("nb_item");
    
    var newNode = mytable.rows[1].cloneNode(true);
    var tt=newNode.innerHTML;
    mytable.appendChild(newNode);
    
    
    new_tt=tt.replace(/e_other0/g,"e_other"+nb.value);
    new_tt=new_tt.replace(/e_other0_comment/g,"e_other"+nb.value+'_comment');
    new_tt=new_tt.replace(/e_other0_amount/g,"e_other"+nb.value+'_amount');
    new_tt=new_tt.replace(/e_concerned0/g,"e_concerned"+nb.value);
    new_tt=new_tt.replace(/e_other0_label/g,"e_other"+nb.value+'_label');
    newNode.innerHTML=new_tt;
    g("e_other"+nb.value+'_label').innerHTML="";
    nb.value++;

}
/**
 * @brief add a line in the form for the purchase ledger
 */
function ledger_purchase_add_row(){
	style='class="input_text"';
	var mytable=g("sold_item").tBodies[0];
	var line=mytable.rows.length;
	var row=mytable.insertRow(line);
	var phpsessid=g("phpsessid");
	var nb=g("nb_item");
	var newNode = mytable.rows[1].cloneNode(true);
	var tt=newNode.innerHTML;
	mytable.appendChild(newNode);
	new_tt=tt.replace(/march0/g,"march"+nb.value);
	new_tt=new_tt.replace(/quant0/g,"quant"+nb.value);
	new_tt=new_tt.replace(/sold\(0\)/g,"sold("+nb.value+")");
	newNode.innerHTML=new_tt;
	g("e_march"+nb.value+"_label").innerHTML='&nbsp;';
	g("e_march"+nb.value+"_price").value='0';
	g("e_march"+nb.value).value="";
	g("e_quant"+nb.value).value="1";
	nb.value++;

}
/**
 * @brief compute the sum of a purchase, update the span tvac, htva and tva
 * all the needed data are taken from the document (hidden field : phpsessid and gdossier)
 * @param the number of the changed ctrl
 */
function compute_purchase(p_ctl_nb) {
    var phpsessid=g("phpsessid").value;
    var dossier=g("gDossier").value;
	var a=-1;
	if ( document.getElementById("e_march"+p_ctl_nb+'_tva_amount')) {
		a=trim(g("e_march"+p_ctl_nb+'_tva_amount').value);
		g("e_march"+p_ctl_nb+'_tva_amount').value=a;
	}
    g("e_march"+p_ctl_nb).value=trim(g("e_march"+p_ctl_nb).value);
    var qcode=g("e_march"+p_ctl_nb).value;

    if ( qcode.length == 0 ) { clean_purchase(p_ctl_nb);refresh_purchase();return;}
	var tva_id=-1;
	if ( g('e_march'+p_ctl_nb+'_tva_id') ) {
		tva_id=g('e_march'+p_ctl_nb+'_tva_id').value;
	}

    g('e_march'+p_ctl_nb+'_price').value=trim(g('e_march'+p_ctl_nb+'_price').value);
    var price=g('e_march'+p_ctl_nb+'_price').value;

    g('e_quant'+p_ctl_nb).value=trim(g('e_quant'+p_ctl_nb).value);
    var quantity=g('e_quant'+p_ctl_nb).value;
    var querystring='?PHPSESSID='+phpsessid+'&gDossier='+dossier+'&c='+qcode+'&t='+tva_id+'&p='+price+'&q='+quantity+'&n='+p_ctl_nb;
    g('sum').hide();
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
/**
*@brief refresh the purchase screen, recompute vat, total...
*/
function refresh_purchase() {
  var tva=0; var htva=0;var tvac=0;

  for (var i=0;i<g("nb_item").value;i++) {
    if( g('tva_march'+i))  tva+=g('tva_march'+i).value*1;
    htva+=g('htva_march'+i).value*1;
    tvac+=g('tvac_march'+i).value*1;
  }

    if ( g('tva') ) g('tva').innerHTML=Math.round(tva*100)/100;
    g('htva').innerHTML=Math.round(htva*100)/100;
    if (g('tvac'))    g('tvac').innerHTML=Math.round(tvac*100)/100;
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
	g('htva_march'+ctl).value=rhtva;
	g('tvac_march'+ctl).value=rtvac;
	g('sum').show();
	refresh_purchase();

	return;
  }
  rtva=answer.tva*1;
  
 
  
  g('sum').show();
  if ( g('e_march'+ctl+'_tva_amount').value=="" ||  g('e_march'+ctl+'_tva_amount').value==0 ){
			g('tva_march'+ctl).value=rtva;
			g('e_march'+ctl+'_tva_amount').value=rtva;
	}
	else {
		g('tva_march'+ctl).value=g('e_march'+ctl+'_tva_amount').value;
	}
	g('htva_march'+ctl).value=rhtva;
	g('tvac_march'+ctl).value=parseFloat(g('htva_march'+ctl).value)+parseFloat(g('tva_march'+ctl).value);
	
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
    for (loop=0;loop<g("nb_item").value;loop++){
	compute_purchase(loop);
    }
    var tva=0; var htva=0;var tvac=0;
    
    for (var i=0;i<g("nb_item").value;i++) {
	if ( g('tva_march') ) tva+=g('tva_march'+i).value*1;
	htva+=g('htva_march'+i).value*1;
	tvac+=g('tvac_march'+i).value*1;
    }
    
    if ( g('tva') ) g('tva').innerHTML=Math.round(tva*100)/100;
    g('htva').innerHTML=Math.round(htva*100)/100;
    if (g('tvac'))g('tvac').innerHTML=Math.round(tvac*100)/100;


}

function clean_tva(p_ctl) {
if ( g('e_march'+p_ctl+'_tva_amount') )g('e_march'+p_ctl+'_tva_amount').value=0;
}

function clean_sold( p_ctl_nb) 
{
    if ( g("e_march"+p_ctl_nb) ) { g("e_march"+p_ctl_nb).value=trim(g("e_march"+p_ctl_nb).value); }
    if (g('e_march'+p_ctl_nb+'_price')) { g('e_march'+p_ctl_nb+'_price').value='';}
    if ( g('e_quant'+p_ctl_nb)) { g('e_quant'+p_ctl_nb).value='1'; }
    if ( g('tva_march'+p_ctl_nb+'_show') ) { g('tva_march'+p_ctl_nb+'_show').value='0';}
    if (g('tva_march'+p_ctl_nb)) { g('tva_march'+p_ctl_nb).value=0;}
    if ( g('htva_march'+p_ctl_nb)) { g('htva_march'+p_ctl_nb).value=0;}
    if ( g('tvac_march'+p_ctl_nb)) {g('tvac_march'+p_ctl_nb).value=0;}

}
function clean_purchase(p_ctl_nb) {
    var qcode=g("e_march"+p_ctl_nb).value;
    g('e_march'+p_ctl_nb+'_price').value='';
    g('e_quant'+p_ctl_nb).value='1';
    if ( g('e_march'+p_ctl_nb+'_tva_amount') )g('e_march'+p_ctl_nb+'_tva_amount').value='0';
    if (  g('tva_march'+p_ctl_nb)) g('tva_march'+p_ctl_nb).value=0;
    if (g('htva_march'+p_ctl_nb)) g('htva_march'+p_ctl_nb).value=0;
    if( g('tvac_march'+p_ctl_nb)) g('tvac_march'+p_ctl_nb).value=0;
}
/**
 * @brief add a line in the form for the quick_writing
 */
function quick_writing_add_row(){
   style='class="input_text"';
   var mytable=g("quick_item").tBodies[0];
   var line=mytable.rows.length;
   var row=mytable.insertRow(line);
   var phpsessid=g("phpsessid");
   var nb=g("nb_item");

  var newNode = mytable.rows[1].cloneNode(true);
  var tt=newNode.innerHTML;
  mytable.appendChild(newNode);


  new_tt=tt.replace(/qc_0/g,"qc_"+nb.value);
  new_tt=new_tt.replace(/amount0/g,"amount"+nb.value);
  new_tt=new_tt.replace(/poste0/g,"poste"+nb.value);
  new_tt=new_tt.replace(/ck0/g,"ck"+nb.value);
  new_tt=new_tt.replace(/ld0/g,"ld"+nb.value);


  newNode.innerHTML=new_tt;
    g("qc_"+nb.value).value='';
    g("amount"+nb.value).value='';
    g("poste"+nb.value).value='';
    g("ld"+nb.value).value='';
 
  nb.value++;

}
/*! \file 
 * \brief
 * open a windows to confirm and cancel an operation
 */
function cancelOperation(p_value,p_sessid,p_dossier,p_jrn)
		{
			var win=window.open('annulation.php?p_jrn='+p_jrn+'&jrn_op='+p_value+'&PHPSESSID='+p_sessid+'&gDossier='+p_dossier,'Annule','toolbar=no,width=400,height=400,scrollbars=yes,resizable=yes');
		}
function RefreshMe() {
window.location.reload();
}
/*! \brief this function search into the ledger
 *  \param p_sessid PHPSESSID
 *  \param p_ctl ctl name
 *  \param p_montant amount to search (if 0 get it from the e_other_amount 
 */
function SearchJrn(p_sessid,p_dossier,p_ctl,p_montant,p_paid)
 {
 var url='jrn_search.php?p_ctl='+p_ctl+'&PHPSESSID='+p_sessid+'&gDossier='+p_dossier+'&'+p_paid;


 if ( p_montant == 0 ) {
   // compute amount name replace the number
   num=p_ctl.replace("e_concerned","");

   /* Get the amount */
   var ctl_montant_name="e_other"+num+"_amount";

   if ( document.forms[0]) { 

     for ( i=0; i < document.forms[0].length; i++)
       {    
	 var e=document.forms[0].elements[i];
	 if ( e.name == ctl_montant_name ) {
	   p_montant=e.value;
	   break;
	  }
       }
   }
   if ( p_montant == 0 && document.forms[1]) { 

     for ( i=0; i < document.forms[1].length; i++)
       {    
	 var e=document.forms[1].elements[i];
	 if ( e.name == ctl_montant_name ) {
	   p_montant=e.value;
	   break;
	  }
       }
   }

 }	


 if ( p_montant == 0 ) {
         var win=window.open(url,'Cherche','toolbar=no,width=600,height=600,scrollbars=yes');

 } else {
         var win=window.open(url+'&search&p_montant='+p_montant+'&p_montant_sel=%3D','Cherche','toolbar=no,width=600,height=600,scrollbars=yes');
 }
}

function updateJrn(p_ctl) {
  var form=document.forms[1];

  for (var e=0;e<form.elements.length;e++) {
    var elmt=form.elements[e];
    if ( elmt.type == "checkbox") {
      if (elmt.checked==true ) {
	var str_name=elmt.name;
	var nValue=str_name.replace("jr_concerned","");

	set_inparent(nValue,p_ctl);
      }
    }
  }
  window.close();
}

function go_next_concerned() {
 var form=document.forms[1];

  for (var e=0;e<form.elements.length;e++) {
    var elmt=form.elements[e];
    if ( elmt.type == "checkbox") {
      if (elmt.checked==true ) {
	return confirm("Si vous changez de page vous perdez les reconciliations, continuez ?");
      }
    }
  }
}

/*!\brief
 * \param p_value jrn.jr_id
 * \param p_sessid PHPSESSID
 * \param p_jrn ledger number
 * \param p_vue easy or expert view of the operation
 */

function modifyOperation(p_value,p_sessid,p_dossier,p_jrn,p_vue)
		{

			var win=window.open('modify_op.php?action=update&p_jrn='+p_jrn+'&line='+p_value+'&PHPSESSID='+p_sessid+'&p_view='+p_vue+'&gDossier='+p_dossier,'','toolbar=no,width=690,height=410,scrollbars=yes,resizable=yes');
			win.focus();
		}

/*!\brief
 * \param p_value jrn.jr_id
 * \param p_sessid PHPSESSID
 */

function viewOperation(p_value,p_sessid,p_dossier)
		{
			var win=window.open('modify_op.php?action=view_ca&line='+p_value+'&PHPSESSID='+p_sessid+'&gDossier='+p_dossier,'','toolbar=no,width=690,height=410,scrollbars=yes,resizable=yes');
			win.focus();
		}
function dropLink(p_value,p_value2,p_sessid,p_dossier) {
	var win=window.open('modify_op.php?action=delete&line='+p_value+'&line2='+p_value2+'&PHPSESSID='+p_sessid+'&gDossier='+p_dossier,'Liaison','toolbar=no,width=500,height=400,scrollbars=yes,resizable=yes');
		}
