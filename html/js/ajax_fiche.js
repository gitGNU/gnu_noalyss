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
 * \brief This file permit to use the AJAX function to fill up 
 *        info from fiche
 *
 */

/*!\brief this function fills the data from fid.php, 
 * \param p_ctl the ctrl to fill
 * \param p_deb if debit of credit
 * \param p_jrn the ledger
 */
function trim(s) {
    return s.replace(/^\s+/, '').replace(/\s+$/, '');
}
/*!\brief clean the row (the label, price and vat)
 * \param p_ctl the calling ctrl
 */
function clean_Fid(p_ctl)
{
   	nSell=p_ctl+"_price";	
	nBuy=p_ctl+"_price";	
	nTva_id=p_ctl+"_tva_id";
	if ( $(nSell) ) {	  $(nSell).value="";	}
	if ( $(nBuy) ) {	  $(nBuy).value="";}
	if ( $(nTva_id) ) {  $(nTva_id).value="-1"; }
	
}
function errorFid(request,json) {
  alert('erreur : ajax fiche');
}
/*!\brief this function fills the data from fid.php, 
 * \param p_ctl the ctrl to fill
 * \param p_deb if debit of credit
 * \param p_jrn the ledger
 * \param phpsessid
 *\param p_caller id of the caller 
 *\param  p_extra extra parameter, change depends of the caller
 */
function ajaxFid(p_ctl,p_deb,phpsessid,p_caller,p_extra) 
{
  var gDossier=$('gDossier').value;
    var ctl_value=trim($(p_ctl).value);
    $(p_ctl).value=ctl_value;
  var p_jrn=$('p_jrn').value;
  if ( trim(ctl_value)==0 ) {
    nLabel=p_ctl+"_label";
    if ($(nLabel) ){$(nLabel).value="";}
    $(nLabel).innerHTML="&nbsp;";
    clean_Fid(p_ctl);
    return;
  }
  queryString="?FID="+ctl_value;
  queryString=queryString+"&d="+p_deb+"&j="+p_jrn+'&gDossier='+gDossier;
  queryString=queryString+'&ctl='+p_ctl+'&PHPSESSID='+phpsessid;
  queryString=queryString+'&caller='+p_caller+'&extra='+p_extra;
  /*  alert(queryString); */
  var action=new Ajax.Request (
			       "fid.php",
			       {
				 method:'get',
				 parameters:queryString,
				 onFailure:errorFid,
				 onSuccess:successFid
			       }
			
			       );
  
}
/*!\brief callback function for ajax
 * \param request : object request
 * \param json : json answer */
function successFid(request,json) {
  var answer=request.responseText.evalJSON(true);

  var data=answer.name;
  var sell=answer.sell;
  var buy=answer.buy;
  var tva_id=answer.tva_id;
  var ctl=answer.ctl;
  var extra=answer.extra;
  var caller=answer.caller;
  if ( caller == 'searchcardControl') {
     if ( trim (data)== "") { $(ctl).style.color="red";}
	else { $(ctl).style.color="black"; $(extra).value=data;}
  }
  var toSet=ctl+'_label';
  if (trim(data) == "" ) {
    $(toSet).innerHTML="Fiche Inconnue";
    $(toSet).style.color="red";
    clean_Fid(ctl);
    $(ctl).style.color="red";
  } else {
    $(ctl).style.color="black";	
    var nSell=ctl+"_price";
    var nBuy=ctl+"_price";
    var nTva_id=ctl+"_tva_id";
    $(toSet).innerHTML=data;
    $(toSet).style.color="black";
    if ( $(nTva_id) ) {
      $(nTva_id).value=tva_id;
    }
    if ( $(nSell ) && trim(sell)!="" ) {
      $(nSell).value=sell;
    }
      if ( $(nBuy) && trim(buy)!="")  { $(nBuy).value=buy; }
    
  }
  
}
function ajax_error_saldo(request,json) {
  alert('erreur : ajax fiche');
}
/*!\brief this function get the saldo
 * \param p_ctl the ctrl where we take the quick_code 
 * \param phpsessid
 */
function ajax_saldo(phpsessid,p_ctl) 
{
  var gDossier=$('gDossier').value;
    var ctl_value=trim($(p_ctl).value);
    var jrn=$('p_jrn').value;
  queryString="?FID="+ctl_value;
  queryString=queryString+'&gDossier='+gDossier+'&j='+jrn;
  queryString=queryString+'&ctl='+ctl_value+'&PHPSESSID='+phpsessid;
  /*  alert(queryString); */
  var action=new Ajax.Request (
			       "get_saldo.php",
			       {
				 method:'get',
				 parameters:queryString,
				 onFailure:ajax_error_saldo,
				 onSuccess:ajax_success_saldo
			       }
			
			       );
  
}
/*!\brief callback function for ajax
 * \param request : object request
 * \param json : json answer */
function ajax_success_saldo(request,json) {
  var answer=request.responseText.evalJSON(true);
  $('first_sold').value=answer.saldo;
  
}
//-->
