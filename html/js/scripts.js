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

/*!\brief this function fills the data from fid.php, 
 * \param p_ctl the ctrl to fill
 * \param p_deb if debit of credit
 * \param p_jrn the ledger
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



function my_clear(p_ctrl) {
	if ( g(p_ctrl)){
	g(p_ctrl).value="";
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
		g('p_step').disabled=true;
	} else {
		g('from_periode').disabled=false;
		g('to_periode').disabled=false;
		g('from_date').disabled=true;
		g('to_date').disabled=true;
		g('p_step').disabled=false;
	}
}
 function set_inparent(p_ctl,p_value) {
   self.opener.set_value(p_value,p_ctl);
        window.close(); 
 }

 /* SetValue( p_ctl,p_value )
 /* p_ctl is the name of the control
 /* p_value is the value to set in
 */
 function set_value(p_value,p_ctl) {       
	if ( g(p_ctl)) {
		var g_ctrl=g(p_ctrl);
		if ( g_ctrl.value ) { g(p_ctl).value=p_value;}
		if ( g_ctrl.innerHTML ) { g(p_ctl).innerHTML=p_value;}
	}
}

function refresh_window() {
	window.location.reload();
} 
