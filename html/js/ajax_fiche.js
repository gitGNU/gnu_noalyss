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
			resp=String(ajaxRequest.responseText);
			
			a_resp=resp.split("/");
			toSet=p_ctl+"_label";
			if ( resp.length == 0 ) 
			   {
			   var ctl_toSet=document.getElementById(toSet);
			   ctl_toSet.innerHTML="Fiche inconnue";
			   ctl_toSet.style.color='red';
			   }
			else {
				var ctl_toSet=document.getElementById(toSet);
				ctl_toSet.style.color='black';
				ctl_toSet.innerHTML=a_resp[0];
				nSell=p_ctl+"_sell";	
				nBuy=p_ctl+"_buy";	
				nTva_id=p_ctl+"_tva_id";

				if ( document.getElementById(nSell) != null ) { 
				   
				   document.getElementById(nSell).value=a_resp[1];
				   }
				if ( document.getElementById(nBuy) != null ) { 
				   
				   document.getElementById(nBuy).value=a_resp[2];
				   }

				if ( document.getElementById(nTva_id) != null ) { 
				   
				    document.getElementById(nTva_id).value=a_resp[3];
				    }
			}
		}
	}
	if (document.getElementById(p_ctl).value.length == 0 ) {
	   var toSet=p_ctl+"_label";
	   var ctl_toSet=document.getElementById(toSet);
	   ctl_toSet.innerHTML="";
	
	} else {
	  queryString="?FID="+document.getElementById(p_ctl).value;
	  queryString=queryString+"&d="+p_deb+"&j="+p_jrn;
	  
	  ajaxRequest.open("GET", "fid.php"+queryString, true);
	  ajaxRequest.send(null); 
	}
}

//-->