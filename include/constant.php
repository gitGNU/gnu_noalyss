<?php  
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

/*! \file
 * \brief Contains all the variable + the javascript
 * and some parameter 
 */
require_once ('config.inc.php');
require_once('constant.security.php');

define ("DBVERSION",58);

define ("MAX_COMPTE",4);
define ('MAX_BUD_DETAIL',20);
define ('MAX_ARTICLE',8);
define ("DEBUG","false"); 	/* value are true or false */

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

//!\enum ACTION  defines document_type for action
define('ACTION','1,5,6,7,8');

//valeurs standardd
define ("YES",1);
define ("NO",0);
define ("OPEN",1);
define ("CLOSED",0);
define ("NOTCENTRALIZED",3);
define ("ALL",4);

// Pour les ShowMenuComptaLeft
define ("MENU_FACT",1);
define ("MENU_FICHE",2);
define ("MENU_PARAM",3);

// for the fiche_inc.GetSqlFiche function
define ("ALL_FICHE_DEF_REF", 1000);

// fixed value for attr_def data
define ("ATTR_DEF_ACCOUNT",5);
define ("ATTR_DEF_NAME",1);
define ("ATTR_DEF_BQ_NO",3);
define ("ATTR_DEF_BQ_NAME",4);
define ("ATTR_DEF_PRIX_ACHAT",7);
define ("ATTR_DEF_PRIX_VENTE",6);
define ("ATTR_DEF_TVA",2);
define ("ATTR_DEF_NUMTVA",13);
define ("ATTR_DEF_ADRESS",14);
define ("ATTR_DEF_CP",15);
define ("ATTR_DEF_PAYS",16);
define ("ATTR_DEF_STOCK",19);
define ("ATTR_DEF_TEL",17);
define ("ATTR_DEF_EMAIL",18);
define ("ATTR_DEF_CITY",24);
define ("ATTR_DEF_COMPANY",25);
define ("ATTR_DEF_FAX",26);
define ("ATTR_DEF_NUMBER_CUSTOMER",30);
define ("ATTR_DEF_DEP_PRIV",31);

define ("FICHE_TYPE_CLIENT",9);
define ("FICHE_TYPE_VENTE",1);
define ("FICHE_TYPE_FOURNISSEUR",8);
define ("FICHE_TYPE_FIN",4);
define ("FICHE_TYPE_CONTACT",16);
define ("FICHE_TYPE_EMPL",25);
define ("FICHE_TYPE_ADM_TAX",14);
define ("FICHE_TYPE_ACH_MAR",2);
define ("FICHE_TYPE_ACH_SER",3);



define ("ATTR_DEF_DEPENSE_NON_DEDUCTIBLE",20);
define ("ATTR_DEF_TVA_NON_DEDUCTIBLE",21);
define ("ATTR_DEF_TVA_NON_DEDUCTIBLE_RECUP",22);
define ("ATTR_DEF_QUICKCODE",23);
define ("JS_CONFIRM",
"<SCRIPT language=\"javascript\" src=\"js/confirm.js\">	</SCRIPT>");
define ("JS_SEARCH_POSTE",
"<SCRIPT language=\"javascript\" src=\"js/search_poste.js\">	</SCRIPT>");

define ("JS_BUD_SCRIPT",
"<SCRIPT language=\"javascript\" src=\"js/bud_script.js\">	</SCRIPT>");

define ("JS_VIEW_JRN_CANCEL",
"<script  language=\"javascript\" src=\"js/cancel_op.js\">	</script>");

define ("JS_VIEW_JRN_MODIFY",
"<script  language=\"javascript\" src=\"js/modify_op.js\">	</script>");


define ("JS_UPDATE_PCMN",
"<script  language=\"javascript\" src=\"js/update_pcmn.js\">	</script>");

define ("JS_SEARCH_CARD","
<script  language=\"javascript\" src=\"js/search_card.js\"></script>
");
// concerned operation
define ("JS_CONCERNED_OP",'
<script type="text/javascript" language="javascript"  src="js/jrn_concerned.js">
</script>');
define ('JS_CALENDAR','
<script type="text/javascript" language="javascript"  src="js/jrn_concerned.js">
</script>');

define ('JS_COMPUTE_DIRECT','
<script type="text/javascript" language="javascript"  src="js/compute_direct.js">
</script>');


define ('JS_CAOD_COMPUTE','
<script type="text/javascript" language="javascript"  src="js/caod_compute.js">
</script>');

define ('JS_INFOBULLE','
<DIV id="bulle" class="infobulle"></DIV>
<script type="text/javascript" language="javascript"  src="js/infobulle.js">
</script>');


// One line calculator
define ("JS_CALC_LINE",'
<div style="border:outset black 3px; position:float; float:right;background-color:white;font-family:sans-serif;font-size:9pt;">
<script type="text/javascript" language="javascript"  src="js/calc.js">
</script>
<h2 class="info"> Calculette</H2>
<form name="calc_line"  method="GET" onSubmit="cal();return false;" >
<input style="border:solid 1px;" type="text" size="30" id="inp" name="calculator">
<input type="button" value="Efface tout" onClick="Clean();return false;" >
</form>
<span id="result">  </span><br>
<span id="sub_total">  Taper une formule (ex 20*5.1) puis enter  </span><br>
<span id="listing"> </span>
<br>
</div>
'
);
define ("JS_COMPUTE_ODS",
"<SCRIPT language=\"javascript\" src=\"js/compute.js\">	</SCRIPT>");

 define ("JS_SHOW_TVA","<SCRIPT language=\"javascript\">
 function ShowTva(p_sessid,p_dossier,ctl)
      {
        var win=window.open('show_tva.php?ctl='+ctl+'&PHPSESSID='+p_sessid+'&gDossier='+p_dossier,'Montre','scrollbar,toolbar=no,width=300,height=300,resizable=yes');
     } 
  function GetIt(ctl,tva_id) {
    self.opener.SetValue(ctl,tva_id)
    window.close();	
 } 
</script>");

define ("JS_TVA","<script  language=\"javascript\">

function ChangeTVA(p_ctl,p_value) {
        if (document.getElementById(p_ctl) ) {
          var f=document.getElementsByName(p_value);
	  for ( var i=0; i < f.length ; i++) {
		  document.getElementById(p_ctl).innerHTML=f[i].value;
	}
	}
}

</script>");
define ("JS_AJAX_FICHE",'<script language="javascript" src="js/ajax_fiche.js"></script>');
define ("JS_TODO",'<script language="javascript" src="js/todo_list.js"></script>');
define ("JS_AJAX_OP",'<script language="javascript" src="js/ajax_op.js"></script>');
define ("JS_PROTOTYPE",'<script language="javascript" src="js/prototype.js"></script>');
define ("JS_MINTOOLKIT",'<script language="javascript" src="js/mintoolkit.js"></script>');
// Sql string
define ("SQL_LIST_ALL_INVOICE","");

define ("SQL_LIST_UNPAID_INVOICE"," where (jr_rapt is null or jr_rapt = '') and jr_valid = true  and jr_ech is null"
); 


define ("SQL_LIST_UNPAID_INVOICE_DATE_LIMIT" ," 
   where (jr_rapt is null or jr_rapt = '') 
       and to_date(to_char(jr_ech,'DD.MM.YYYY'),'DD.MM.YYYY') < to_date(to_char(now(),'DD.MM.YYYY'),'DD.MM.YYYY') and jr_ech is not null
       and jr_valid = true" );
?>
