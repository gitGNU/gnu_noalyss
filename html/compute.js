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
function CheckTotal() {
var ie4=false;
if ( document.all ) { 
	ie4=true;
}// Ajouter getElementById par document.all[str]
var deb=0;
 for (var i=0;1;i++) {
	a=document.getElementById("mont_deb"+i);
	if ( a == null ) {
		document.forms[0].sum_deb.value=deb;
		break;
	} else {
	if ( isNaN(a.value) == true)  {
		a.value=0;
		alert ("Not a Number !!! ");
	}
	deb+=eval(a.value);
	}
 }
var cred=0;
 for (var i=0;1;i++) {
	a=document.getElementById("mont_cred"+i);
	if ( a == null ) {
		document.forms[0].sum_cred.value=cred;
		break;
	} else {
	if ( isNaN(a.value) == true)  {
		a.value=0;
		alert ("Not a Number !!! ");
	}
	cred+=eval(a.value);
	}
 }
if ( deb != cred ) {
	document.getElementById("diff").style.color="red";
	document.getElementById("diff").style.fontWeight="bold";
	document.getElementById("diff").innerHTML="Différence";

} else {
	document.getElementById("diff").innerHTML="";
}
}

