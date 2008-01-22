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
 * open a windows for searching the concerned operation
 */
 
/*! \brief this function search into the ledger
 *  \param p_sessid PHPSESSID
 *  \param p_ctl ctl name
 *  \param p_montant amount to search (if 0 get it from the e_other_amount 
 */
function SearchJrn(p_sessid,p_dossier,p_ctl,p_montant,p_paid)
 {
 var url='jrn_search.php?p_ctl='+p_ctl+'&PHPSESSID='+p_sessid+'&gDossier='+p_dossier+'&'+p_paid;


 if ( p_montant == 0 ) {
   // compute amount name replace the number
   num=p_ctl.replace("e_concerned","");

   /* Get the amount */
   var ctl_montant_name="e_other"+num+"_amount";

   if ( document.forms[0]) { 

     for ( i=0; i < document.forms[0].length; i++)
       {    
	 var e=document.forms[0].elements[i];
	 if ( e.name == ctl_montant_name ) {
	   p_montant=e.value;
	   break;
	  }
       }
   }
   if ( p_montant == 0 && document.forms[1]) { 

     for ( i=0; i < document.forms[1].length; i++)
       {    
	 var e=document.forms[1].elements[i];
	 if ( e.name == ctl_montant_name ) {
	   p_montant=e.value;
	   break;
	  }
       }
   }

 }	


 if ( p_montant == 0 ) {
         var win=window.open(url,'Cherche','toolbar=no,width=600,height=600,scrollbars=yes');

 } else {
         var win=window.open(url+'&search&p_montant='+p_montant+'&p_montant_sel=%3D','Cherche','toolbar=no,width=600,height=600,scrollbars=yes');
 }
}

function updateJrn(p_ctl) {
  var form=document.forms[1];

  for (var e=0;e<form.elements.length;e++) {
    var elmt=form.elements[e];
    if ( elmt.type == "checkbox") {
      if (elmt.checked==true ) {
	var str_name=elmt.name;
	var nValue=str_name.replace("jr_concerned","");

	self.opener.SetIt(nValue,p_ctl);
      }
    }
  }
  window.close();
}
 function GetIt(p_ctl,p_value) {
   self.opener.SetIt(p_value,p_ctl);
        window.close(); 
 }

function go_next_concerned() {
 var form=document.forms[1];

  for (var e=0;e<form.elements.length;e++) {
    var elmt=form.elements[e];
    if ( elmt.type == "checkbox") {
      if (elmt.checked==true ) {
	return confirm("Si vous changez de page vous perdez les reconciliations, continuez ?");
      }
    }
  }
}
 /* SetValue( p_ctl,p_value )
 /* p_ctl is the name of the control
 /* p_value is the value to set in
 */
 function SetIt(p_value,p_ctl) {       

         var f=document.getElementsByName(p_ctl);
         for (var h=0; h < f.length; h++) {
	   var old_value=f[h].value;
	   // if f[h] is not empty add a comma
	   if (old_value == "") {
	     f[h].value=p_value;
	   } else {
	     f[h].value=old_value+','+p_value;
	   }
	 }
 
 }
 
 
