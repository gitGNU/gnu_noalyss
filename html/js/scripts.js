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
	alert("Maximum 4 lignes désolé");
	return;
  }
  elt.value=new_value;

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