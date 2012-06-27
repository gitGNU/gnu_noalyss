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
// SVNVERSION
global $version_phpcompta;
define ('SVNINFO',5015);

global $g_captcha,$g_failed,$g_succeed;
$g_captcha=false;
$g_failed="<span style=\"font-size:18px;color:red\">&#x2716;</span>";
$g_succeed="<span style=\"font-size:18px;color:green\">&#x2713;</span>";
/*set to none for production */
/* uncomment for production */
//$version_phpcompta=SVNINFO;
define ("DEBUG",true);

// If you don't want to be notified of the update
// define ("SITE_UPDATE",'');
define ("SITE_UPDATE",'http://www.phpcompta.eu/last_version.txt');
define ("SITE_UPDATE_PLUGIN",'http://www.phpcompta.eu/plugin_last_version.txt');


$version_phpcompta=4985;
//define ("DEBUG",true);

define ("DBVERSION",103);

define ("DBVERSIONREPO",14);
define ('NOTFOUND','--not found--');

define ("MAX_COMPTE",4);
define ('MAX_ARTICLE',12);
define ('MAX_CAT',15);
define ('MAX_FORECAST_ITEM',10);
define ('MAX_PREDEFINED_OPERATION',30);
define ('MAX_COMPTE_CARD',4);

if ( DEBUG ) error_reporting(2147483647);  else error_reporting(0);
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
define ("ATTR_DEF_DEPENSE_NON_DEDUCTIBLE",20);
define ("ATTR_DEF_TVA_NON_DEDUCTIBLE",21);
define ("ATTR_DEF_TVA_NON_DEDUCTIBLE_RECUP",22);
define ("ATTR_DEF_QUICKCODE",23);
define ("ATTR_DEF_FIRST_NAME",32);

define( 'ATTR_DEF_ACCOUNT_ND_TVA',50);
define('ATTR_DEF_ACCOUNT_ND_TVA_ND',51);
define ('ATTR_DEF_ACCOUNT_ND_PERSO',52);
define ('ATTR_DEF_ACCOUNT_ND',53);

define ("FICHE_TYPE_CLIENT",9);
define ("FICHE_TYPE_VENTE",1);
define ("FICHE_TYPE_FOURNISSEUR",8);
define ("FICHE_TYPE_FIN",4);
define ("FICHE_TYPE_CONTACT",16);
define ("FICHE_TYPE_EMPL",25);
define ("FICHE_TYPE_ADM_TAX",14);
define ("FICHE_TYPE_ACH_MAR",2);
define ("FICHE_TYPE_ACH_SER",3);


define ('JS_INFOBULLE','
        <DIV id="bulle" class="infobulle"></DIV>
        <script type="text/javascript" language="javascript"  src="js/infobulle.js">
        </script>');


// Sql string
define ("SQL_LIST_ALL_INVOICE","");

define ("SQL_LIST_UNPAID_INVOICE","  (jr_rapt is null or jr_rapt = '') and jr_valid = true  "
       );


define ("SQL_LIST_UNPAID_INVOICE_DATE_LIMIT" ,"
        where (jr_rapt is null or jr_rapt = '')
        and to_date(to_char(jr_ech,'DD.MM.YYYY'),'DD.MM.YYYY') < to_date(to_char(now(),'DD.MM.YYYY'),'DD.MM.YYYY')
        and jr_valid = true" );
?>
