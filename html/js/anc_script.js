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
/* $Revision: 2546 $ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/**
  * @brief javascript for the analytic accountancy
  */

/*!\brief add a row for the CA
 * \param p_table_id
 * \param p_amount amount to reach
 * \param p_count count of col.
 */
function add_row(p_table,p_seq,p_count) {
  var mytable=g(p_table).tBodies[0];

  if ( ! mytable ) {return;}
    var new_value=mytable.rows.length+1;
  if ( mytable.rows.length > 5 ) { 
	alert("Maximum 5 lignes ");
	return;
  }
  // For the detail view (modify_op) there is several form and then several time the
  // element
  var rowToCopy=mytable.rows[1];
  var row=mytable.insertRow(mytable.rows.length);

    for ( var i=0;i< rowToCopy.cells.length;i++) {
      var cell=row.insertCell(i);
	var txt=rowToCopy.cells[i].innerHTML;
	txt=txt.replace(/row_1/g,"row_"+new_value);
	cell.innerHTML=txt;
	}

  // create the amount cell

  row.cells[i-1].innerHTML='<input type="TEXT" name="val'+p_seq+"l"+new_value+'" id="val'+p_seq+"l"+new_value+'" size="6"  style="border:solid 1px blue;color:black;background:#EDEDED;text-align:right" value="0">';

}
/*! 
 * \brief Check the amount of the CA
 * \param p_style : error or ok, if ok show a ok box if the amount are equal
 *
 *
 * \return true if the amounts are equal
 */
function verify_ca(p_style) {
  var nb_item=g('nb_item').value;

  for ( var item=0;item<=nb_item-1;item++) {
      if ( g('nb_t'+item) ) {
	  var nb_row=1*g('nb_t'+item).value;
	  var amount=1*g('amount_t'+item).value;
	  var get=0;
	  for (var row=1;row <= nb_row;row++) {
	      
	      if ( g('ta_'+item+'o1row_'+row).value != -1) {
		  val=g('val'+item+'l'+row).value;
		  if ( isNaN(val)) {		continue;}
		  get=get+(val*1);
	      } else {
		  get=amount;
	      }
	  }
	  if ( Math.round(get,2) != Math.round(amount,2) ) {
	      diff=Math.round(get,2)-Math.round(amount,2);
	      alert ("montant differents \ntotal CA="+get+"\ntotal Operation "+amount+"\nDiff = "+diff);
	      return false;
	  }else {
	      if ( p_style=='ok') {
		  alert('les montants correspondent');
	      }
	  }
      }
  }
  return true;
}
/*! 
 * \brief open a window for searching a CA account, 
 * \param p_dossier dossier id
 * \param p_target ctrl to update
 * \param p_source ctrl containing the pa_id
 * 
 *
 * \return
 */
function search_ca (p_dossier,p_target,p_source)
{
  var pa_id=g(p_source).value;
  var url="?gDossier="+p_dossier+"&c1="+p_target+"&c2="+pa_id;
  var a=window.open("search_ca.php"+url,"CA recherche",'statusbar=no,scrollbars=yes,toolbar=no');
  a.focus();
}

function caod_checkTotal() {
  var ie4=false;
  if ( document.all ) { 
    ie4=true;
  }// Ajouter getElementById par document.all[str]
  var total_deb=0.0;
  var total_cred=0.0;
  var nb_item=10;

  for (var i=0;i <nb_item ;i++) {
    var doc_amount=g("pamount"+i);
	if ( ! doc_amount ) { return;}
    var side=g("pdeb"+i);
	if ( ! side ) { return;}
    var amount=parseFloat(doc_amount.value);
  
    if ( isNaN(amount) == true)  {
      amount=0.0;
    }
    if ( side.checked == false ) {
      total_cred+=amount;
    }
    if ( side.checked == true ) {
      total_deb+=amount;
    }
  }



  r_total_cred=Math.round(total_cred*100)/100;
  r_total_deb=Math.round(total_deb*100)/100;
  g('totalDeb').innerHTML=r_total_deb;
  g('totalCred').innerHTML=r_total_cred;

  if ( r_total_deb != r_total_cred ) {
    g("totalDiff").style.color="red";
    g("totalDiff").style.fontWeight="bold";
    g("totalDiff").innerHTML="Différence";
    diff=total_deb-total_cred;
    diff=Math.round(diff*100)/100;
    g("totalDiff").innerHTML=diff;
    
  } else {
    g("totalDiff").innerHTML="0.0";
  }
}

/**
 *@brief remove an operation
 *@param p_dossier is the folder
 *@param p_oa_group is the group of the analytic operation
 */
function op_remove(p_dossier,p_oa_group) {
  var a=confirm("Etes-vous sur de vouloir effacer cette operation ?\n");
  if ( a == false ) return;
  var obj={"oa":p_oa_group,"gDossier":p_dossier};
  queryString=encodeJSON(obj);
  g(p_oa_group).style.display='none';
  var e=new Ajax.Request("remove_op.php",
		  	{method:'get',parameters:queryString});

}

