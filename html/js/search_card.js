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
 * \brief
 * open a windows for searching a card
 */

function showfiche(p_sessid,qcode)
{
  var a=window.open('show_fiche.php?PHPSESSID='+p_sessid+'&q='+qcode,'','toolbar=no,width=350,height=450,scrollbar=yes,statusbar=no');
  
}

/* type must be cred or deb and name is
 * the control's name
*/
function SearchCard(p_sessid,type,name,jrn)
{
var search=document.getElementById(name).value;
   var a=window.open('fiche_search.php?fic_search='+search+'&p_jrn='+jrn+'&PHPSESSID='+p_sessid+'&type='+type+'&name='+name,'item','toolbar=no,width=350,height=450,scrollbars=yes,statusbar=no');
}
	function NewCard(p_sessid,type,name,jrn)
{
   var a=window.open('fiche_new.php?p_jrn='+jrn+'&PHPSESSID='+p_sessid+'&type='+type+'&name='+name,'item','toolbar=no,width=350,height=450,scrollbars=yes,statusbar=no');
}
/* SetValue( p_ctl,p_value )
/* p_ctl is the name of the control
/* p_value is the value to set in
*/
function SetValue(p_ctl,p_value) 
{

	var f=document.getElementsByName(p_ctl);
	for (var h=0; h < f.length; h++) {
		f[h].value=p_value;
		}
	

}
/* Parameters 
 * i = ctl _name
 * p_id = code id (fiche.f_id)
 *¨p_label = label
 * p_sell vw_fiche_attr.vw_sell
 * p_buy vw_fiche_attr.vw_buy
 * p_tva_id vw_fiche_attr.tva_id
 * p_tva_label vw_fiche_attr.tva_label
 */
  function SetData(i,p_id,p_label,p_sell,p_buy,p_tva_id, p_tva_label)
{
	SetValue(i,p_id);
	// for the form we use 1. and for span 2.    
	//1. document.form_detail.eval(a).value=p_buy;
	//2. document.getElementById(a).innerHTML=p_sell;

	// Compute name of label ctl
	var a=i+'_label';
	document.getElementById(a).innerHTML=p_label;
	document.getElementById(a).style.color='black';
	// Compute name of  sell  ctl 
 	var a=i+'_sell';
	// if the object exist
 	var e=document.getElementsByName(a)  
	  if ( e.length != 0 ) {
	    SetValue(a,p_sell);

	}

	// Compute name of  buy  ctl 
	var a=i+'_buy';
	// if the object exist
 	var e=document.getElementsByName(a)  
        if ( e.length != 0 ) {
	  SetValue(a,p_buy);
	}
	// Compute name of  tva_id  ctl 
	var a=i+'_tva_id';
	// if the object exist
 	var e=document.getElementsByName(a)  
        if ( e.length != 0 ) {
	  SetValue(a,p_tva_id);
	}

	// Compute name of  tva_label ctl 
	var a=i+'_tva_label';
	// if the object exist
        if (document.getElementById(a) ) {
	  document.getElementById(a).innerHTML=p_tva_label;
	}
}


