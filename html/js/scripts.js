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

/* !\file 
 */

/* \brief javascript script, always added to every page
 *
 */

//scripts library.

// Set the focus to the specified field,
// and select it if requested by SelectIt

function GetID(ID) {
  if (document.all) {
    return document.all[ID];
  } else if (document.getElementById) {
    return document.getElementById(ID);
  } else {
    return undefined;
  }
}
function GetFormField(Field) {
  var elem = document.forms ? document.forms[0] : false;
  return elem.elements ? elem.elements[Field] : false;
}

function AttachEvent(Obj, EventType, Function, Capture) {
  Capture = Capture || false;
  if (Obj == document && !document.all) { Obj = window; }
  if (Obj.addEventListener) {
    Obj.addEventListener(EventType, Function, Capture);
  } else if (Obj.attachEvent) {
    Obj.attachEvent("on" + EventType, Function);
  }
}

function SetFocus(Field,SelectIt) {
  var elem = GetFormField(Field);
  if (!elem) { elem = GetID(Field); }

  if (elem) {
    elem.focus();
    if (SelectIt > 0 && elem.select) {
      elem.select();
    }
    var Type = elem.type ? elem.type : false;
    if (SelectIt <= 0 && Type && (Type == "text" || Type == "textarea")) {
      // IE version
      var TxtPos = SelectIt < 0 ? elem.value.length : 0;
      if (elem.createTextRange) {
        var rng = elem.createTextRange()
        rng.collapse();
        rng.moveStart("character", TxtPos);
        rng.select();
      // Moz version
      } else if (elem.setSelectionRange) {
        elem.setSelectionRange(TxtPos,TxtPos);
        elem.focus();
      }
    }
  }
  return true;
}

function HandleSubmit(e) {
  SubmitButton = GetID('SubmitButton');
  var code = e.charCode || e.keyCode;
  if ( (code == 13) && e.ctrlKey ) 
  {     
    SubmitButton.click();    
    return true;
  }
}
/*
 * Open the search screen
 */
function openRecherche(p_sessid,p_dossier,p_style) {
  if ( p_style == 'E' ) { p_style="expert";}
  var w=window.open("recherche.php?gDossier="+p_dossier+"&PHPSESSID="+p_sessid+'&'+p_style,'','statusbar=no,scrollbars=yes,toolbar=no');
  w.focus();
}

/*!\brief add a row for the CA
 * \param p_table_id
 * \param p_amount amount to reach
 * \param p_count count of col.
 */
function add_row(p_table,p_seq,p_count) {
  var elt=document.getElementById("nb_"+p_table);


  if ( ! elt ) {return;}

  // number of elt = elt.value
  var old_value=elt.value;
  var new_value=1*elt.value+1;
  if ( new_value > 4 ) { 
	alert("Maximum 4 lignes ");
	return;
  }
  elt.value=new_value;
  // For the detail view (modify_op) there is several form and then several time the 
  // element
  var all_elt=document.getElementsByName("nb_"+p_table);
  for (var e=0;e<all_elt.length;e++) {
    all_elt[e].value=new_value;
  }
  var tbody=document.getElementById(p_table).getElementsByTagName("tbody")[0];
  var row=document.createElement("TR");
  for ( i=1;i<=p_count;i++) {
	var cell=document.createElement("TD");
	var col=document.getElementById(p_table+"td"+i+'c1');
	var txt=col.innerHTML;
	//	alert (txt);
	txt=txt.replace(/row_1/g,"row_"+new_value);
	//alert(txt);
	cell.innerHTML=txt;

	row.appendChild(cell); }

  // create the amount cell
  var cell_montant=document.createElement("TD");
  cell_montant.innerHTML='<input type="TEXT" name="val'+p_seq+"l"+new_value+'" id="val'+p_seq+"l"+new_value+'" size="6"  style="border:solid 1px blue;" >';
  row.appendChild(cell_montant);
  tbody.appendChild(row);

}
/*! 
 * \brief Check the amount of the CA
 * \param p_style : error or ok, if ok show a ok box if the amount are equal
 *
 *
 * \return true the amounts are equal
 */


function verify_ca(p_style) {
  var nb_item=document.getElementById('nb_item').value;
  for ( var item=0;item<=nb_item-1;item++) {
	var nb_row=1*document.getElementById('nb_t'+item).value;
	var amount=1*document.getElementById('amount_t'+item).value;
	var get=0;
	for (var row=1;row <= nb_row;row++) {

	  if ( document.getElementById('ta_'+item+'o1row_'+row).value != -1) {
	      val=document.getElementById('val'+item+'l'+row).value;
	      if ( isNaN(val)) {		continue;}
	      get=get+(val*1);
	  } else {
	    get=amount;
	  }
	}
	//alert ("table "+item+"\namount is"+amount+"\nVal = "+val+"\nGet ="+get);
	if ( Math.round(get,2) != Math.round(amount,2) ) {
	  diff=Math.round(get,2)-Math.round(amount,2);
	  alert ("montant differents \ntotal CA="+get+"\ntotal Operation "+amount+"\nDiff = "+diff);
	  /*!\todo remove debug 
	  // to debug purpose set to true	  return false;
	  */
	  return true
	    }else {
	  if ( p_style=='ok') {
	    alert('les montants correspondent');
	  }
	}
  }
  return true;
}
/*! 
 * \brief open a window for searching a CA account, 
 * \param p_sessid PHPSESSID
 * \param p_dossier dossier id
 * \param p_target ctrl to update
 * \param p_source ctrl containing the pa_id
 * 
 *
 * \return
 */
function search_ca (p_sessid,p_dossier,p_target,p_source)
{
  var pa_id=document.getElementById(p_source).value;

  var url="?PHPSESSID="+p_sessid+"&gDossier="+p_dossier+"&c1="+p_target+"&c2="+pa_id;
  var a=window.open("search_ca.php"+url,"CA recherche",'statusbar=no,scrollbars=yes,toolbar=no');
  a.focus();
}

/*! 
 * \brief set a ctrl is the caller windows
 * \param p_ctrl the control to change
 * \param p_value the value the control will contains
 * \param
 * 
 *
 * \return none
 */

function ca_set_child(p_ctl,p_value) {

  self.opener.ca_set_parent(p_ctl,p_value);
	window.close();
}
/*! 
 * \brief this script is in the parent windows and it is called by SetItChild
 * \param \see ca_set_child
 *
 * \return none
 */

function ca_set_parent(p_ctl,p_value) {

	var f=document.getElementById(p_ctl);
	f.value=p_value;
	
}
/*! \brief import : update a record (in ajax)
 *  \param $p_sessid : PHPSESSID
 * \param $p_dossier : the dossier id
 * \param $p_count : the item number
 */

function import_update(p_sessid,p_dossier,p_count) {
  //  alert ("Dossier = "+p_dossier+" counter ="+p_count);
  var query_string="PHPSESSID="+p_sessid+"&count="+p_count+"&gDossier="+p_dossier;
  var code=$("code"+p_count);
  var poste=$("poste"+p_count);
  var concerned=$("e_concerned"+p_count);
  var url="ajax_import.php";
  query_string+="&code="+code.value;
  query_string+="&poste="+poste.value;
  query_string+="&concerned="+concerned.value;
  query_string+="&action=update";


  /* call the script which handle the query */
  var update= new Ajax.Request (
				url, 
				{
				method:'get',
				parameters:query_string,
				}
				);
  var form=$("form_"+p_count);
  form.hide();
}
/*! \brief remove : remove a record (in ajax)
 *  \param $p_sessid : PHPSESSID
 * \param $p_dossier : the dossier id
 * \param $p_count : the item number
 */

function import_remove(p_sessid,p_dossier,p_count) {
  //  alert ("Dossier = "+p_dossier+" counter ="+p_count);
  var query_string="PHPSESSID="+p_sessid+"&count="+p_count+"&gDossier="+p_dossier;
  var code=$("code"+p_count);
  var url="ajax_import.php";
  query_string+="&code="+code.value;
  query_string+="&action=delete";
  var a = confirm("Etes-vous certain d'effacer cette operation ?");
  if ( a == false ) { return;}

  /* call the script which handle the query */
  var update= new Ajax.Request (
				url, 
				{
				method:'get',
				parameters:query_string,
				}
				);
  var form=$("form_"+p_count);
  form.hide();
}
/*! \brief remove : remove a record (in ajax)
 *  \param $p_sessid : PHPSESSID
 * \param $p_dossier : the dossier id
 * \param $p_count : the item number
 */

function import_not_confirmed(p_sessid,p_dossier,p_count) {
  //  alert ("Dossier = "+p_dossier+" counter ="+p_count);
  var query_string="PHPSESSID="+p_sessid+"&count="+p_count+"&gDossier="+p_dossier;
  var code=$("code"+p_count);
  var url="ajax_import.php";
  query_string+="&code="+code.value;
  query_string+="&action=not_confirmed";

  /* call the script which handle the query */
  var update= new Ajax.Request (
				url, 
				{
				method:'get',
				parameters:query_string,
				}
				);
  var form=$("form_"+p_count);
  form.hide();
}
