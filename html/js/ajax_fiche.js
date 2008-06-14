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
   	nSell=p_ctl+"_sell";	
	nBuy=p_ctl+"_buy";	
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
 */
function ajaxFid(p_ctl,p_deb) 
{
  var gDossier=$('gDossier').value;
  var ctl_value=$(p_ctl).value;
  var p_jrn=$('p_jrn').value;
  if ( trim(ctl_value)==0 ) {
    nLabel=p_ctl+"_label";
    $(nLabel).value="";
    $(nLabel).innerHTML=".....................................................................................................";
    clean_Fid(p_ctl);
    return;
  }
  queryString="?FID="+ctl_value;
  queryString=queryString+"&d="+p_deb+"&j="+p_jrn+'&gDossier='+gDossier;
  queryString=queryString+'&ctl='+p_ctl;
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

/*   alert('data:'+data+' sell:'+sell+' Tva id:'+tva_id); */

  var toSet=ctl+'_label';
  if (trim(data) == "" ) {
    $(toSet).innerHTML="Fiche Inconnue";
    $(toSet).style.color="red";
    clean_Fid(ctl);
  } else {
    var nSell=ctl+"_sell";
    var nBuy=ctl+"_buy";
    var nTva_id=ctl+"_tva_id";
    $(toSet).innerHTML=data;
    $(toSet).style.color="black";
    if ( $(nTva_id) ) {
      $(nTva_id).value=tva_id;
    }
    if ( $(nSell ) ) {
      $(nSell).value=sell;
    }
    $(nBuy).value=buy;
    
  }
}
//-->
