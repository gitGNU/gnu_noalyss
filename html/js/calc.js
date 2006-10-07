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
 * \brief This file show a little online calculator, in the caller
 *        the span id result, listing, the id form calc_line and the
 *        input id inp must exist see constant.php JS_CALC_LINE
 *
 */
var str="";
var val="";
var counter=0;

// add input 
function cal()
{
	p_variable=this.document.getElementById('inp').value;
	if (p_variable.search(/^\s*$/) !=-1) {
		return;
		}
if ( counter==0)
{
	this.document.getElementById('result').innerHTML="";
}
// if both parenthesis are not found we increase the counter 
// --> bug if ( p_variable.search(/\)+/) == -1 && p_variable.search(/\(+/) ==-1) {
	if ( counter%2 == 0)
	{
		// number only
		var regex=/^[0-9]*\.*[0-9]*$/;
	
		if (  p_variable.search(regex) ==-1)
		{
			alert ('Nombre uniquement');
			return;
		}	
	} else
	{
		var regex=/^\/?\+?-?\*?=?$/;
		// signs
		if (  p_variable.search(regex) ==-1)
		{
			alert ('Signe + - * / = uniquement');
			return;
		}
		
	}
	counter++;	
// --> PARENTHESIS}
	
if ( p_variable == "=" ) {
	Compute();
	str="";
	val="";
	counter=0;
	return;
	}
  str=str+p_variable;
  val=val+p_variable;
var str_sub="";
if ( counter%2 != 0 )
{
	sub=eval(val);
	str_sub="<hr><b><i> Total :"+val+" = "+sub+"<I></b>";
	this.document.getElementById("sub_total").innerHTML=str_sub;

}
  //alert ('value is '+p_variable+'Global :'+str);
  this.document.getElementById("listing").innerHTML=str;
  this.document.getElementById('inp').value="";
}
// Clean 
// 
function Clean() 
{
	str="";
	counter=0;
	val="";
	this.document.getElementById('listing').innerHTML="";
	this.document.getElementById('result').innerHTML="";
	this.document.getElementById('sub_total').innerHTML="";
	this.document.getElementById('inp').value="";
	this.document.getElementById('inp').focus();

}

function Compute()
{
	var tot=0;
	var ret="";
	tot=eval(val);
	ret+="<hr>";
	ret+="<b>Total   =  "+tot+'</b>';
	this.document.getElementById('inp').value="";
	this.document.getElementById('result').innerHTML=ret;
	this.document.getElementById('inp').focus();
}
