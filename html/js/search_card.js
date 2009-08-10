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
 * \brief javascript for searching a card
 */
/*!\brief open a windows for showing a card
* \param p_sessid must be given
* \param the qcode 
 */
function showfiche(p_sessid,p_qcode)
{
  p_dossier=document.getElementById("gDossier").value;
  var a=window.open('show_fiche.php?PHPSESSID='+p_sessid+'&gDossier='+p_dossier+'&q='+p_qcode,'','toolbar=no,width=350,height=450,scrollbar=yes,statusbar=no');
  a.focus();
}
/*!\brief Open a window for searching a card
*
* Remark The ledger (jrn_id) must be in the calling form as a hidden field named p_jrn
*\param  p_sessid is the PHPSESSID
*\param type must be deb or cred
*\param  name is the name of the control, it is used for computing the name of the VAT, Price field 
*\param no_add to avoid to be able to add a card (for undefined or no)
*\param p_ledger is the ledger id : 0 means no check
* \see SetData()
*/
function SearchCard(p_sessid,type,name,p_ledger,no_add)
{
  var search=document.getElementById(name).value;
  var gDossier=document.getElementById('gDossier').value;
  
  var jrn=0;
  if ( p_ledger == undefined ) {
	if ( document.getElementById("p_jrn") ) {
		jrn=document.getElementById("p_jrn").value;
	}	
  } else 
	jrn=p_ledger;
	
  var file='fiche_search.php';
var qadd='&add=yes';
if ( no_add != undefined && no_add=='no' ) { qadd="&add=no"}

var query='?first&search&fic_search='+search+'&p_jrn='+jrn+'&PHPSESSID='+p_sessid
+'&type='+type+'&name='+name+'&gDossier='+gDossier+qadd;
  query+="&caller=searchcard";
   var a=window.open(file+query,'item','toolbar=no,width=350,height=450,scrollbars=yes,statusbar=no');
   a.focus();
   return false;
}
/*!\brief Open a window for adding a card
*
* Remark The ledger (jrn_id) must be in the calling form as a hidden field named p_jrn or passed as a parameter
*\param  p_sessid is the PHPSESSID
*\param type must be deb or cred
*\param  name is the name of the control, it is used for computing the name of the VAT, Price field 
*\param p_jrn ledger id
*/
function NewCard(p_sessid,type,name,p_jrn)
{

  var search=document.getElementById(name).value;
  var gDossier=document.getElementById('gDossier').value;
  var jrn=0;
  if ( p_jrn == undefined )  {  jrn=$("p_jrn").value; } else { jrn=p_jrn;}
  var a=window.open('fiche_new.php?p_jrn='+jrn+'&PHPSESSID='+p_sessid+'&type='+type+'&name='+name+'&gDossier='+gDossier,'item','toolbar=no,width=350,height=450,scrollbars=yes,statusbar=no');
   return false;

}
/*!brief set the value of a ctrl 
 *\param p_ctl control to change
 *\param p_value value to set (only)
*/
function SetValue(p_ctl,p_value) 
{

	var f=document.getElementsByName(p_ctl);
	for (var h=0; h < f.length; h++) {
		f[h].value=p_value;
		}
	

}
/*!\brief Set the data from the child window to the parent one 
*
* Set the data found during the search (tva_id, price, tva_label) to the form, the name are based on i.
* The url contains the caller (searchcard).This function is called if the caller is searchcard
 * \param i ctl _name
 * \param p_id code id (quickcode
 * \param p_label = label
 * \param p_price vw_fiche_attr.vw_price
 * \param p_price vw_fiche_attr.vw_price
 * \param p_tva_id vw_fiche_attr.tva_id
 * \param p_tva_label vw_fiche_attr.tva_label
 */
  function SetData(i,p_id,p_label,p_price,p_price,p_tva_id, p_tva_label)
{
	SetValue(i,p_id);
	// for the form we use 1. and for span 2.    
	//1. document.form_detail.eval(a).value=p_price;
	//2. document.getElementById(a).innerHTML=p_price;

	// Compute name of label ctl
	var a=i+'_label';
	document.getElementById(a).innerHTML=p_label;
	document.getElementById(a).style.color='black';
	// Compute name of  sell  ctl 
 	var a=i+'_price';
	// if the object exist
 	var e=document.getElementsByName(a)  
	  if ( e.length != 0 ) {
	    SetValue(a,p_price);

	}

	// Compute name of  buy  ctl 
	var a=i+'_price';
	// if the object exist
 	var e=document.getElementsByName(a)  
        if ( e.length != 0 ) {
	  SetValue(a,p_price);
	}
	// Compute name of  tva_id  ctl 
	var a=i+'_tva_id';
	// if the object exist
 	var e=document.getElementsByName(a)  
        if ( e.length != 0 ) {
	  SetValue(a,p_tva_id);
	}

	// Compute name of  tva_label ctl 
	var a=i+'_tva_label';
	// if the object exist
        if (document.getElementById(a) ) {
	  document.getElementById(a).innerHTML=p_tva_label;
	}
}
/*!\brief Set the value of 2 input fields
*
* Set the quick code in the first ctrl and the label of the quickcode in the second one. This function is a variant of SetData for
* some specific need.  This function is called if the caller is searchcardCtrl
*
*\param p_ctrl the input with the name of the quick code
*\param  p_quickcode the found quick_code
*\param p_ctrlname the name of the input field with the label
*\param p_label the label of the quickcode
*/
function setCtrl(p_ctrl,p_quickcode,p_ctrlname,p_label){
   var ctrl=document.getElementById(p_ctrl);
   if ( ctrl ) { ctrl.value=p_quickcode; }
   var ctrl_name=document.getElementById(p_ctrlname);
   if ( ctrl_name ) { ctrl_name.value=p_label; }
}

/*!\brief Open a window for searching a card
*
* Remark The ledger (jrn_id) must be in the calling form as a hidden field named p_jrn, this function is derived from searchCard because
* the returns value are also handled differently (so a paramter caller is added to the url
*\param  p_sessid is the PHPSESSID
*\param  name is the name of the control, it is used for computing the name of the VAT, Price field 
* \see SetData()
*/
function searchCardCtrl(p_sessid,type,name,ctrl)
{
  var search=document.getElementById(name).value;
  var gDossier=document.getElementById('gDossier').value;
  var jrn=0;
  if ( document.getElementById("p_jrn") ) {
    jrn=document.getElementById("p_jrn").value;
  }
  var file='fiche_search.php';
  var query='?first&search&fic_search='+search+'&p_jrn='+jrn+'&PHPSESSID='+p_sessid+'&type='+type+'&name='+name+'&gDossier='+gDossier;
  query+="&extra="+ctrl;
  query+="&caller=searchcardCtrl";
   var a=window.open(file+query,'item','toolbar=no,width=350,height=450,scrollbars=yes,statusbar=no');
   a.focus();
   return false;
}
