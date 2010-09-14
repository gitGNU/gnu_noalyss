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
var p_history="";
var p_variable="";
// add input
function cal()
{
    p_variable=this.document.getElementById('inp').value;
    if (p_variable.search(/^\s*$/) !=-1)
    {
        return;
    }
    try
    {
        Compute();
        sub=eval(p_variable);
        var result=parseFloat(sub);
        result=Math.round(result*100)/100;
    }
    catch(exception)
    {
        alert("Mauvaise formule\n"+p_variable);
        return false;
    }
    p_history=p_history+'<hr>'+p_variable;
    p_history+="="+result.toString();
    var str_sub="<hr><b><i> Total :"+p_variable+" = "+result.toString()+"<I></b>";
    this.document.getElementById("sub_total").innerHTML=str_sub;
    this.document.getElementById("listing").innerHTML=p_history;
    this.document.getElementById('inp').value="";
}
// Clean
//
function Clean()
{
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

    this.document.getElementById('inp').value="";
    this.document.getElementById('inp').focus();
}
