<?


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

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
// $Revision$
define ("phpcompta_password","dany");

// securite correspond a la table 
// action
// access aux journaux
define ("ENCJRN",1);
// création facture
define("FACT",2);
// lecture aux fiche
define ("FICHE_READ",3);
// Impression
define ("IMP",4);
//formulaire
define("FORM",5);
// Modif du Plan Comptable
define ("MPCMN",6);
// Gestion des journaux
define ("GJRN",7);
// Gestion des paramètres globaux
define ("PARM",8);
// Gestion de la sécurité
define ("SECU",9);
// Access à la centralisation
define ("CENTRALIZE",10);
define ("VEN",11);
define ("BQE",12);
define ("ODS",13);
define ("ACH",14);
define ("FICHE_WRITE",15);
define ("STOCK_WRITE",16);
define ("STOCK_READ",17);

// Erreur
define ("NOERROR",0);
define ("BADPARM",1);
define ("BADDATE",2);
define ("NOTPERIODE",3);
define ("PERIODCLOSED",4);
define ("INVALID_ECH",5);
define ("RAPPT_ALREADY_USED",6);
define ("RAPPT_NOT_EXIST",7);
define ("DIFF_AMOUNT",8);
define ("RAPPT_NOMATCH_AMOUNT",9);
define ("NO_PERIOD_SELECTED",10);
define ("NO_POST_SELECTED",11);
define ("LAST",1);
define ("FIRST",0);
define ("ERROR",12);

//valeurs standardd
define ("YES",1);
define ("NO",0);
define ("OPEN",1);
define ("CLOSED",0);
define ("NOTCENTRALIZED",3);

// Pour les ShowMenuComptaLeft
define ("MENU_FACT",1);
define ("MENU_FICHE",2);
define ("MENU_PARAM",3);

// for the fiche_inc.GetSqlFiche function
define ("ALL_FICHE_DEF_REF", 1000);

// fixed value for attr_def data
define ("ATTR_DEF_ACCOUNT",5);
define ("ATTR_DEF_NAME",1);
define ("ATTR_DEF_PRIX_ACHAT",7);
define ("ATTR_DEF_PRIX_VENTE",6);
define ("ATTR_DEF_TVA",2);
define ("ATTR_DEF_ADRESS",14);
define ("ATTR_DEF_CP",15);
define ("ATTR_DEF_PAYS",16);
define ("ATTR_DEF_STOCK",19);

define ("FICHE_TYPE_CLIENT",9);
define ("FICHE_TYPE_VENTE",1);
define ("FICHE_TYPE_FOURNISSEUR",8);
define ("FICHE_TYPE_FIN",4);
define ("FICHE_TYPE_ADM_TAX",14);

define ("JS_SEARCH_POSTE","<SCRIPT>function SearchPoste(p_sessid,p_ctl)
     {
       var win=window.open('poste_search.php?p_ctl='+p_ctl+'&PHPSESSID='+p_sessid,'Cherche','toolbar=no,width=600,height=600,scrollbars=yes,resizable=yes');
    } 
function SearchPosteFilter(p_sessid,p_ctl,p_filter)
     {
       var win=window.open('poste_search.php?p_ctl='+p_ctl+'&PHPSESSID='+p_sessid+'&filter='+p_filter,'Cherche','toolbar=no,width=600,height=600,scrollbars=yes,resizable=yes');
    } 
	 function GetIt() {
	   window.close();	
	} 
function SetItChild(p_ctl,p_value) {
	self.opener.SetItParent(p_ctl,p_value);
	window.close();
}
function SetItParent(p_ctl,p_value) {
	document.forms[0].eval(p_ctl).value=p_value;
}
	</SCRIPT>"
);
define ("JS_SHOW_TVA","<SCRIPT>function ShowTva(p_sessid,ctl)
     {
       var win=window.open('show_tva.php?ctl='+ctl+'&PHPSESSID='+p_sessid,'Montre','scrollbar,toolbar=no,width=300,height=300,resizable=yes');
    } 
	 function GetIt(ctl,tva_id) {
           self.opener.SetIt(ctl,tva_id)
	   window.close();	
	} 
function SetIt(ctl,tva_id)
{
document.forms[0].eval(ctl).value=tva_id;
}
	</SCRIPT>"
);

define ("JS_VIEW_JRN_DETAIL","<script>function viewDetail(p_value,p_sessid)
		{
			var win=window.open('jrn_op_detail.php?jrn_op='+p_value+'&PHPSESSID='+p_sessid,'Cherche','toolbar=no,width=400,height=400,scrollbars=yes,resizable=yes');
		}

	</script>");
define ("JS_VIEW_JRN_CANCEL","<script>function cancelOperation(p_value,p_sessid)
		{
			var win=window.open('annulation.php?jrn_op='+p_value+'&PHPSESSID='+p_sessid,'Cancel it','toolbar=no,width=400,height=400,scrollbars=yes,resizable=yes');
		}
function RefreshMe() {
window.location.reload();
}
	</script>");
define ("JS_VIEW_JRN_MODIFY","<script>function modifyOperation(p_value,p_sessid)
		{
			var win=window.open('modify_op.php?action=update&line='+p_value+'&PHPSESSID='+p_sessid,'Modify it','toolbar=no,width=500,height=400,scrollbars=yes,resizable=yes');
		}
function RefreshMe() {
window.location.reload();
}
	function dropLink(p_value,p_value2,p_sessid) {
	var win=window.open('modify_op.php?action=delete&line='+p_value+'&line2='+p_value2+'&PHPSESSID='+p_sessid,'delete link it','toolbar=no,width=500,height=400,scrollbars=yes,resizable=yes');
		}

	
	</script>");
define ("JS_SEARCH_CARD","
<script>
/* type must be cred or deb and name is
 * the control's name
*/
function SearchCard(p_sessid,type,name)
{
   var a=window.open('fiche_search.php?PHPSESSID='+p_sessid+'&type='+type+'&name='+name,'item','toolbar=no,width=350,height=450,scrollbars=yes');
}
function NewCard(p_sessid,type,name)
{
   var a=window.open('fiche_new.php?PHPSESSID='+p_sessid+'&type='+type+'&name='+name,'item','toolbar=no,width=350,height=450,scrollbars=yes');
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
	document.form_detail.eval(i).value=p_id;
	
	// for the form we use 1. and for span 2.    
	//1. document.form_detail.eval(a).value=p_buy;
	//2. document.getElementById(a).innerHTML=p_sell;

	// Compute name of label ctl
	var a=i+'_label';
	document.getElementById(a).innerHTML=p_label;
	
	// Compute name of  sell  ctl 
 	var a=i+'_sell';
	// if the object exist
 	var e=document.getElementsByName(a)  
	  if ( e.length != 0 ) {
	    document.form_detail.eval(a).value=p_sell;

	}

	// Compute name of  buy  ctl 
	var a=i+'_buy';
	// if the object exist
 	var e=document.getElementsByName(a)  
        if ( e.length != 0 ) {
	  document.form_detail.eval(a).value=p_buy;
	}
	// Compute name of  tva_id  ctl 
	var a=i+'_tva_id';
	// if the object exist
 	var e=document.getElementsByName(a)  
        if ( e.length != 0 ) {
	  document.form_detail.eval(a).value=p_tva_id;
	}

	// Compute name of  tva_label ctl 
	var a=i+'_tva_label';
	// if the object exist
        if (document.getElementById(a) ) {
	  document.getElementById(a).innerHTML=p_tva_label;
	}
}


</script>


");
// concerned operation
define ("JS_CONCERNED_OP","
<script>

function SearchJrn(p_sessid,p_ctl)
{
	var win=window.open('jrn_search.php?p_ctl='+p_ctl+'&PHPSESSID='+p_sessid,'Cherche','toolbar=no,width=600,height=600,scrollbars=yes');
}
function GetIt(p_ctl,p_value) {
  self.opener.SetIt(p_value,p_ctl);
	window.close();	
}
function SetIt(p_value,p_ctl) {
  document.forms[0].eval(p_ctl).value=p_value;
	
}


</script>
");
// One line calculator
define ("JS_CALC_LINE","

<form name=\"calc_line\" onSubmit=\"cal()\">
<input type=\"text\" size=\"20\" name=\"calculator\" onBlur=\"cal()\">
Answer:<span id=\"name\"></span>
<br>

<!-- <input type=\"button\" name=\"B1\" value=\"Calculate\" > -->

<input type=\"reset\" name=\"B2\" value=\"Reset\"><br>


</form>
<SCRIPT>

function cal()
{
  document.getElementById(\"name\").innerHTML=
  eval(document.calc_line.calculator.value)
}
</script>



"
);

define ("JS_TVA","<script>

function ChangeTVA(p_ctl,p_value) {
        if (document.getElementById(p_ctl) ) {
	  document.getElementById(p_ctl).innerHTML=document.forms[0].eval(p_value).value;
	}

}

</script>");
// Sql string
define ("SQL_LIST_ALL_INVOICE","");
define ("SQL_LIST_UNPAID_INVOICE"," where (jr_rapt is null or jr_rapt = '') and jr_valid = true "); 
define ("SQL_LIST_UNPAID_INVOICE_DATE_LIMIT" ," where (jr_rapt is null or jr_rapt = '') 
                       and to_date(to_char(jr_ech,'DD.MM.YYYY'),'DD.MM.YYYY') < to_date(to_char(now(),'DD.MM.YYYY'),'DD.MM.YYYY') and jr_valid = true" );
?>
