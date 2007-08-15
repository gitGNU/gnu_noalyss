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

	if ( document.getElementById(nSell) != null ) { 
			   document.getElementById(nSell).value="";
   }
	if ( document.getElementById(nBuy) != null ) { 
	   document.getElementById(nBuy).value="";
	}

	if ( document.getElementById(nTva_id) != null ) { 			   
	  document.getElementById(nTva_id).value="";
    }

}

/*!\brief this function fills the data from fid.php, 
 * \param p_ctl the ctrl to fill
 * \param p_deb if debit of credit
 * \param p_jrn the ledger
 */
function ajaxFid(p_ctl,p_deb,p_jrn){
	var ajaxRequest;  // The variable that makes Ajax possible!
;
	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
		  if ( ajaxRequest.status == 200 ) {

			xmldoc = ajaxRequest.responseXML.documentElement;
			data   = xmldoc.getElementsByTagName('name');

  			label  = data[0].firstChild.nodeValue;
  			sell   = xmldoc.getElementsByTagName('sell')[0].firstChild.nodeValue;
  			buy    = xmldoc.getElementsByTagName('buy')[0].firstChild.nodeValue;
  			tva_id = xmldoc.getElementsByTagName('tva_id')[0].firstChild.nodeValue;

			toSet=p_ctl+"_label";

			if ( trim(label) == "" ) 
			   {
			   var ctl_toSet=document.getElementById(toSet);
			   ctl_toSet.innerHTML="Fiche inconnue";
			   ctl_toSet.style.color='red';
			   clean_Fid(p_ctl);
			   }
			else {
				var ctl_toSet=document.getElementById(toSet);
				ctl_toSet.style.color='black';
				ctl_toSet.innerHTML=label;
				nSell=p_ctl+"_sell";	
				nBuy=p_ctl+"_buy";	
				nTva_id=p_ctl+"_tva_id";

				if ( document.getElementById(nSell) != null ) { 
				   
				  document.getElementById(nSell).value=sell;
				   }
				if ( document.getElementById(nBuy) != null ) { 
				   
				  document.getElementById(nBuy).value=buy;
				   }

				if ( document.getElementById(nTva_id) != null ) { 
				   
				  document.getElementById(nTva_id).value=tva_id;
				    }
			}
		  }
		}
	}
	if (document.getElementById(p_ctl).value.length == 0 ) {
	   var toSet=p_ctl+"_label";
	   var ctl_toSet=document.getElementById(toSet);
	   ctl_toSet.innerHTML="";
	   // clean other fields
	   clean_Fid(p_ctl);
	
	} else {
	  queryString="?FID="+document.getElementById(p_ctl).value;
	  queryString=queryString+"&d="+p_deb+"&j="+p_jrn;

	  ajaxRequest.open("GET", "fid.php"+queryString, true);
	  ajaxRequest.send(null); 
	}
}

//-->