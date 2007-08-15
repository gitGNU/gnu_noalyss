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
function op_remove(p_sessid,p_oa_group) {
  var a=confirm("Etes-vous sur de vouloir effacer cette operation ?\n");
  if ( a == false ) return;
  var ajaxRequest;  // The variable that makes Ajax possible!

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
  queryString="?PHPSESSID"+p_sessid+"&oa="+p_oa_group;

  ajaxRequest.open("GET", "remove_op.php"+queryString, true);
  ajaxRequest.send(null); 
  document.getElementById(p_oa_group).style.display='none';
}

/*!\brief this function fills the data from fid.php, 
 * \param p_ctl the ctrl to fill
 * \param p_deb if debit of credit
 * \param p_jrn the ledger
 */
function trim(s) {
    return s.replace(/^\s+/, '').replace(/\s+$/, '');
}


//-->