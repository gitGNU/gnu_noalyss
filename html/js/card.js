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
* \todo must used ipopup
 */
function showfiche(p_sessid,p_qcode)
{
  p_dossier=g("gDossier").value;
  var a=window.open('show_fiche.php?PHPSESSID='+p_sessid+'&gDossier='+p_dossier+'&q='+p_qcode,'','toolbar=no,width=350,height=450,scrollbar=yes,statusbar=no');
  a.focus();
}
/*!\brief Open a window for searching a card
*
* Remark The ledger (jrn_id) must be in the calling form as a hidden field named p_jrn
*\param  p_sessid is the PHPSESSID
*\param  name is the name of the control, it is used for computing the name of the VAT, Price field 
*\param object_button is the object button with some attributes as 
*  - jrn (0 means all the ledger )
*  - add (no if the button to add a card is not available)
* \see SetData()
*/
function SearchCard(p_sessid,name,objectCtl)
{
  var search=g(name).value;
  var gDossier=g('gDossier').value;
  	
  var file='fiche_search.php';

var query='?first&search&fic_search='+search+'&p_jrn='+objectCtl.jrn+'&PHPSESSID='+p_sessid
+'&type='+objectCtl.type+'&name='+name+'&gDossier='+gDossier+objectCtl.add;
  query+="&caller=searchcard";
  $(name+'_fond').style.display='block';
  $(name+"_border").style.display='block';
  $(name+"_content").style.display='block';
     return false;
}
/*!\brief Open a window for adding a card
*
* Remark The ledger (jrn_id) must be in the calling form as a hidden field named p_jrn or passed as a parameter
*\param  p_sessid is the PHPSESSID
*\param type must be deb or cred
*\param  name is the name of the control, it is used for computing the name of the VAT, Price field 
*\param p_jrn ledger id
* \todo must used ipopup
*/
function NewCard(p_sessid,type,name,p_jrn)
{

  var search=g(name).value;
  var gDossier=g('gDossier').value;
  var jrn=0;
  if ( p_jrn == undefined )  {  jrn=$("p_jrn").value; } else { jrn=p_jrn;}
  var a=window.open('fiche_new.php?p_jrn='+jrn+'&PHPSESSID='+p_sessid+'&type='+type+'&name='+name+'&gDossier='+gDossier,'item','toolbar=no,width=350,height=450,scrollbars=yes,statusbar=no');
   return false;

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
	set_value(i,p_id);
	// for the form we use 1. and for span 2.    
	//1. document.form_detail.eval(a).value=p_price;
	//2. g(a).innerHTML=p_price;

	// Compute name of label ctl
	var a=i+'_label';
	
	if (g(a).tagName=='SPAN') {
	g(a).innerHTML=p_label;
	g(a).style.color='black';
	} else {
	 set_value(a,p_label)
	}
	// Compute name of  sell  ctl 
 	var a=i+'_price';
	// if the object exist
 	var e=document.getElementsByName(a)  
	  if ( e.length != 0 ) {
	    set_value(a,p_price);

	}

	// Compute name of  buy  ctl 
	var a=i+'_price';
	// if the object exist
 	var e=document.getElementsByName(a)  
        if ( e.length != 0 ) {
	  set_value(a,p_price);
	}
	// Compute name of  tva_id  ctl 
	var a=i+'_tva_id';
	// if the object exist
 	var e=document.getElementsByName(a)  
        if ( e.length != 0 ) {
	  set_value(a,p_tva_id);
	}

	// Compute name of  tva_label ctl 
	var a=i+'_tva_label';
	// if the object exist
        if (g(a) ) {
	  g(a).innerHTML=p_tva_label;
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
   var ctrl=g(p_ctrl);
   if ( ctrl ) { ctrl.value=p_quickcode; }
   var ctrl_name=g(p_ctrlname);
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
  var search=g(name).value;
  var gDossier=g('gDossier').value;
  var jrn=0;
  if ( g("p_jrn") ) {
    jrn=g("p_jrn").value;
  }
  var file='fiche_search.php';
  var query='?first&search&fic_search='+search+'&p_jrn='+jrn+'&PHPSESSID='+p_sessid+'&type='+type+'&name='+name+'&gDossier='+gDossier;
  query+="&extra="+ctrl;
  query+="&caller=searchcardCtrl";
   var a=window.open(file+query,'item','toolbar=no,width=350,height=450,scrollbars=yes,statusbar=no');
   a.focus();
   return false;
}
