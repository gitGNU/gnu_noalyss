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
/* $Revision$ */
include_once("preference.php");
include_once("fiche_inc.php");
include_once("user_common.php");
/* function InputType
 * Purpose : Create the tag <INPUT TYPE=... Name=...>
 *        
 * parm : 
 *	- Label
 *      - The type
 *      - Name of the variable
 *      - Default Value
 *      - View_only
 *      - $p_list for the select, give the possible values item[0] is the val and item[1] 
 *        is the label
 * gen :
 *	- None
 * return: string
 */
function InputType($p_label,$p_type,$p_name,$p_value,$p_viewonly=false,$p_list=null)
{
  // View only
  if ( $p_viewonly==true) {
    if ( strlen($p_label) != 0) {
      // name and value are set put the info in a hidden field
      $hidden=(strlen($p_name) != 0) ?"<INPUT TYPE=\"hidden\" NAME=\"$p_name\" value=\"$p_value\">":"";

      // return
      return "<TD>$p_label $hidden  </TD><TD>$p_value</TD>";
    } else {
      // name and value are set put the info in a hidden field
      $hidden=(strlen($p_name) != 0) ?"<INPUT TYPE=\"hidden\" NAME=\"$p_name\" value=\"$p_value\">":"";

      return "<TD>$p_value $hidden</TD>";
    }
  }
  // Input type == select
  if ( strtolower($p_type)=="select" ) {
    $r="<TD> $p_label</TD><TD>";
    $r.=sprintf('<SELECT NAME="%s">',$p_name);
    foreach ($p_list as $item) {
      $selected="";
      if ( $p_value == $item[0] ) {
		$selected="SELECTED";
      }
      $r.=sprintf('<OPTION VALUE="%s" %s>%s',
		  $item[0],
		  $selected,
		  $item[1]);
    }
    $r.="</SELECT></TD>";
    return $r;
  }

  // input type == TEXT
  if ( strtolower($p_type)=="text") {
    $r=sprintf('<TD>%s</TD><TD> <INPUT TYPE="%s" NAME="%s" VALUE="%s"></TD>',
	       $p_label,
	       $p_type,
	       $p_name,
	       $p_value);
    return $r;
  }

  // input type == HIDDEN
  if ( strtolower($p_type)=="hidden") {
    $r=sprintf('<TD> <INPUT TYPE="%s" NAME="%s" VALUE="%s"></TD>',
	       $p_type,
	       $p_name,
	       $p_value);
    return $r;
  }

}

/* function FormVente
 * Purpose : Display the form for a sell
 *           Used to show detail, encode a new invoice 
 *           or update one
 *        
 * parm : 
 *	- p_array which can be empty
 *      - the "journal"
 *      - $p_user = $g_user
 *      - view_only if we cannot change it (no right or centralized op)
 *      - $p_article number of article
 * gen :
 *	-
 * return: string with the form
 * TODO Add in parameters the infos about the company for making the invoice
 */
function FormVente($p_cn,$p_jrn,$p_user,$p_array=null,$view_only=true,$p_article=1)
{ 

  if ( $p_array != null ) {
    // array contains old value
    foreach ( $p_array as $a=>$v) {
      ${"$a"}=$v;
    }
  }
  // The date
  $userPref=GetUserPeriode($p_cn,$p_user);
  list ($l_date_start,$l_date_end)=GetPeriode($p_cn,$userPref);
  $op_date=( ! isset($e_date) ) ? "01".substr($l_date_start,2,8):$e_date;
  $e_ech=(isset($e_ech))?$e_ech:"";
  // Save old value and set a new one
  echo_debug("form_input.php.FormVentep_op_date is $op_date");
  $r="";
  if ( $view_only == false) {
    $r="<FORM ACTION=\"user_jrn.php?action=insert_vente\" METHOD=\"POST\">";
    
  }
  $r.='<TABLE>';
  $r.='<TR>'.InputType("Date ","Text","e_date",$op_date,$view_only).'</TR>';
  $r.='<TR>'.InputType("Echeance","Text","e_ech",$e_ech,$view_only).'</TR>';
  include_once("fiche_inc.php");
  // Display the customer
  // TODO ADD BUTTON NEW CUSTOMER
  $fiche=GetFicheJrn($p_cn,$p_jrn,'deb');
  echo_debug("Client Nombre d'enregistrement ".sizeof($fiche));
  // Save old value and set a new one
  $client=( isset ($e_client) )?$e_client:"";
  $r.='<TR>'.InputType("Client ","SELECT","e_client",$client,$view_only,$fiche).'</TR>';
  $r.="</TABLE>";
  // Record the current number of article
  $r.='<INPUT TYPE="HIDDEN" name="nb_item" value="'.$p_article.'">';
  $e_comment=(isset($e_comment))?$e_comment:"";


  // Start the div for item to sell
  $r.="<DIV>";
  $r.='<H2 class="info">Articles</H2>';
  $r.='<TABLE>';
  $fiche=GetFicheJrn($p_cn,$p_jrn,'cred');
echo_debug("Cred Nombre d'enregistrement ".sizeof($fiche));
  for ($i=0;$i< $p_article;$i++) {

    $march=(isset(${"e_march$i"}))?${"e_march$i"}:"";

    $r.='<TR>'.InputType("Article","SELECT","e_march".$i,$march,$view_only,$fiche);
    $quant=(isset(${"e_quant$i"}))?${"e_quant$i"}:"0";
    $r.=InputType("Quantité","TEXT","e_quant".$i,$quant,$view_only);
    $r.='</TR>';
  }


  // TODO Show the price



  $r.="</TABLE>";
  $r.='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">';
  $r.='<INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Voir cette facture">';
  $r.="</DIV>";
  $r.="</FORM>";

  return $r;


}

/* function FormVenteView ($p_cn,$p_jrn,$p_user,$p_array,$p_number,$p_doc='html',$p_comment='') 
 **************************************************
 * Purpose : Show the invoice before inserting it 
 *           the database
 *        
 * parm : 
 *	- p_cn database connection
 *      - p_jrn journal
 *      - p_user
 *      - array of value
 *      - nb of item
 *      - p_doc type pdf or html
 * gen :
 *	- none
 * return:
 *     - string
 * TODO Add test for the date
 */

function FormVenteView ($p_cn,$p_jrn,$p_user,$p_array,$p_number,$p_doc='html',$p_comment='') 
{
// TODO Verify the invoice data if verif failed return null and resubmit
  $r="";
  $data="";
  // Keep all the data if hidden
  // and store the array in variables
  foreach ($p_array as $name=>$content) {
    $data.=InputType("","HIDDEN",$name,$content);
    ${"$name"}=$content;
  }
  // Verify the date
  if ( isDate($e_date) == null ) { 
	  echo_error("Invalid date $e_date");
	  echo_debug("Invalid date $e_date");
	  echo "<SCRIPT> alert('INVALID DATE $e_date !!!!');</SCRIPT>";
	  return null;
		}
// Verify the quantity
for ($o = 0;$o < $p_number; $o++) {
	if ( isNumber(${"e_quant$o"}) == 0 ) {
		echo_debug("invalid quantity ".${"e_quant$o"});
		echo_error("invalid quantity ".${"e_quant$o"});
		echo "<SCRIPT> alert('INVALID QUANTITY !!!');</SCRIPT>";
		return null;
	}			
}
// Verify the ech
if (strlen($e_ech) != 0 and isNumber($e_ech)  == 0 and  isDate ($e_ech) == null ) {
	$msg="Echeance invalide";
		echo_error($msg); echo_error($msg);	
		echo "<SCRIPT>alert('$msg');</SCRIPT>";
		return null;
	} 
	
 // if ech is a number of days then compute date limit
 if ( strlen($e_ech) != 0 and isNumber($e_ech) == 1) {
 list($day,$month,$year)=explode(".",$e_date);
  echo_debug(" date $e_date = $day.$month.$year");
  $p_ech=date('d.m.Y',mktime(0,0,0,$month,$day+$e_ech,$year));
  echo_debug("p_ech = $e_ech $p_ech");
  $e_ech=$p_ech;
  $data.=InputType("","HIDDEN","e_ech",$e_ech);
   }
   
// Verify the userperiode

// userPref contient la periode par default
    $userPref=GetUserPeriode($p_cn,$p_user);
    list ($l_date_start,$l_date_end)=GetPeriode($p_cn,$userPref);

    // Date dans la periode active
    echo_debug ("date start periode $l_date_start date fin periode $l_date_end date demandée $e_date");
    if ( cmpDate($e_date,$l_date_start)<0 || 
	 cmpDate($e_date,$l_date_end)>0 )
      {
		  $msg="Not in the active periode please change your preference";
			echo_error($msg); echo_error($msg);	
			echo "<SCRIPT>alert('$msg');</SCRIPT>";
			return null;
      }
    // Periode fermée 
    if ( PeriodeClosed ($p_cn,$userPref)=='t' )
      {
		$msg="This periode is closed please change your preference";
		echo_error($msg); echo_error($msg);	
		echo "<SCRIPT>alert('$msg');</SCRIPT>";
		return null;
      }
  // start table
  $r.='<TABLE>';
  // Show the Date
  $r.="<tr>";
  $r.=InputType("Date","text","",$e_date,true);
  $r.="</tr>";
  // Show the customer Name
  $r.="<tr>";
  $r.=InputType("Client","text","",getFicheName($p_cn,$e_client),true);
  $r.="</tr>";
  
  // show date limit
  $r.="<tr>";
  $r.=InputType("Date limite","text","",$e_ech,true);
  $r.="</tr>";
  
  $sum_with_vat=0.0;
    $sum_march=0.0;
  // show all article, price vat and sum
    $r.="<TR>";
    $r.="<TH>Article</TH>";
    $r.="<TH>quantité</TH>";
    $r.="<TH>prix unit.</TH>";
    $r.="<TH>taux tva</TH>";
    $r.="<TH>Montant HTVA</TH>";
    $r.="<TH>Montant TVA</TH>";
    $r.="<TH>Total</TH>";
    $r.="</TR>";
  for ($i=0;$i<$p_number;$i++) {
    $fiche_name=getFicheName($p_cn,${"e_march$i"});
    $fiche_price=getFicheAttribut($p_cn,${"e_march$i"},ATTR_DEF_PRIX_VENTE);

    $fiche_quant=${"e_quant$i"};

    // No  row if there is quantity
    if ( $fiche_quant == 0.0 ) continue;
    // VAT 
    $vat=getFicheAttribut($p_cn,${"e_march$i"},ATTR_DEF_TVA);
	
	// vat label
	// vat rate
	$a_vat=GetTvaRate($p_cn,$vat);
	if ( $a_vat == null ) {
		$vat_label="unknown";
		$vat_rate=0.0;
		} else { 
		$vat_label=$a_vat['tva_label'];
		$vat_rate=$a_vat['tva_rate'];
	}		
	
    // Total card without vat
    $fiche_sum=$fiche_price*$fiche_quant;
    // Sum of invoice
    $sum_march+=$fiche_sum;
    // vat of the card
    $fiche_amount_vat=$fiche_price*$fiche_quant*$vat_rate;
    // value card + vat
    $fiche_with_vat=$fiche_price*$fiche_quant*(1+$vat_rate);
    // Sum of invoice vat 
    $sum_with_vat+=$fiche_with_vat;
    // Show the data
    $r.='<TR>';
    $r.='<TD>'.$fiche_name.'</TD>';
    $r.='<TD ALIGN="CENTER">'.$fiche_quant.'</TD>';
    $r.='<TD ALIGN="right">'.$fiche_price.'</TD>';
    $r.="<TD  ALIGN=\"RIGHT\"> $vat_label </TD>";
    $r.='<TD  ALIGN="RIGHT">'.$fiche_sum.'</TD>';
    $r.='<TD ALIGN="RIGHT">'.$fiche_amount_vat.'</TD>';

    $r.='<TD>'.$fiche_with_vat.'</TD>';

    $r.="</TR>";
  }
  
  // end table
  $r.='</TABLE> ';
  $r.='<DIV style="padding:30px;font-size:14pt">';
  $r.="Total HTVA = $sum_march <br>";
  $r.="Total = $sum_with_vat";
  $r.="</DIV>";
  if ( $p_doc == 'html' ) {
	  $r.='<FORM METHOD="POST" ACTION="user_jrn.php?action=record">';
	  $r.=$data;
	  $r.='<INPUT TYPE="SUBMIT" name="record_invoice" value="Sauver">';
	  $r.='<INPUT TYPE="SUBMIT" name="record_and_print_invoice" value="Sauver & imprimer">';
	  $r.='</FORM>';
	  } 
if ( $p_doc == 'pdf' ) {
	  $r.='<FORM METHOD="POST" ACTION="print_invoice.php">';
	  $r.=$data;
	  $r.=InputType("","HIDDEN","e_comment",$p_comment);
	  $r.='<INPUT TYPE="SUBMIT" name="record_invoice" value="Imprimer">';
	  $r.='</FORM>';

	  }
  return $r;

}

/* function RecordInvoice
 **************************************************
 * Purpose : Record an invoice in the table jrn &
 *           jrnx
 *        
 * parm : 
 *	- $p_cn Database connection
 *  - $p_array contains all the invoice data
 * e_date => e : 01.01.2003
 * e_client => e : 3
 * nb_item => e : 3
 * e_march0 => e : 6
 * e_quant0 => e : 0
 * e_march1 => e : 6
 * e_quant1 => e : 2
 * e_march2 => e : 7
 * e_quant2 => e : 3
V : view_invoice => e : Voir cette facture
V : record_invoice => e : Sauver 
 *  - $p_user userid
 *  - $p_jrn current folder (journal)
 * gen :
 *	- none
 * return:
 *	      true on success
 */
function RecordInvoice($p_cn,$p_array,$p_user,$p_jrn)
{
	foreach ( $p_array as $v => $e)
	{
		${"$v"}=$e;
	}
	// Get the default period
	$periode=GetUserPeriode($p_cn,$p_user);
	$amount=0.0;
	// Computing total customer
	for ($i=0;$i<$nb_item;$i++) {
	// store quantity & goods in array
		$a_good[$i]=${"e_march$i"};
		$a_quant[$i]=${"e_quant$i"};
		$amount+=GetFicheAttribut($p_cn,$a_good[$i],ATTR_DEF_PRIX_VENTE)*$a_quant[$i];
	}
	$a_vat=ComputeVat($p_cn,	$a_good,$a_quant,ATTR_DEF_PRIX_VENTE);
	$sum_vat=0.0;
	foreach ( $a_vat as $element => $t) {
	echo_debug(" a_vat element $element t $t");
	$sum_vat+=$t;
	echo_debug("sum_vat = $sum_vat");
	}
	// First we add in jrnx
	
	// Compute the j_grpt
    $seq=GetNextId($p_cn,'j_grpt')+1;

	
	// Debit = client
	$poste=GetFicheAttribut($p_cn,$e_client,ATTR_DEF_ACCOUNT);
	InsertJrnx($p_cn,'d',$p_user,$p_jrn,$poste,$e_date,$amount+$sum_vat,$seq,$periode);
	
	// Credit = goods 
	for ( $i = 0; $i < $nb_item;$i++) {
		$poste=GetFicheAttribut($p_cn,$a_good[$i],ATTR_DEF_ACCOUNT);
		
		InsertJrnx($p_cn,'c',$p_user,$p_jrn,$poste,$e_date,$amount,$seq,$periode);
		}
	// Insert Vat
	if (sizeof($a_vat) != 0 ) // no vat
	{
		foreach ($a_vat as $tva_id => $tva_amount ) {
		$poste=GetTvaPoste($p_cn,$tva_id,'c');
		InsertJrnx($p_cn,'c',$p_user,$p_jrn,$poste,$e_date,$tva_amount,$seq,$periode);
		}
	}
echo_debug("echeance = $e_ech");
	InsertJrn($p_cn,$e_date,$e_ech,$p_jrn,"Invoice",$amount+$sum_vat,$seq,$periode);
	// Set Internal code and Comment
	$comment=SetInternalCode($p_cn,$seq,$p_jrn)."  client : ".GetFicheName($p_cn,$e_client);

	// Update and set the invoice's comment 
	$Res=ExecSql($p_cn,"update jrn set jr_comment='".$comment."' where jr_grpt_id=".$seq);
	return $comment;
}
/* function FormAch($p_cn,$p_jrn,$p_user,$p_array=null,$view_only=true,$p_article=1)
 * Purpose : Display the form for buying
 *           Used to show detail, encode a new invoice 
 *           or update one
 *        
 * parm : 
 *	- p_array which can be empty
 *      - the "journal"
 *      - $p_user = $g_user
 *      - $p_submit contains the submit string
 *      - view_only if we cannot change it (no right or centralized op)
 *      - $p_article number of article
 * gen :
 *	-
 * return: string with the form
 * TODO Add in parameters the infos about the company for making the invoice
 */
function FormAch($p_cn,$p_jrn,$p_user,$p_submit,$p_array=null,$view_only=true,$p_article=1)
{ 

  if ( $p_array != null ) {
    // array contains old value
    foreach ( $p_array as $a=>$v) {
      ${"$a"}=$v;
    }
  }
  // The date
  $userPref=GetUserPeriode($p_cn,$p_user);
  list ($l_date_start,$l_date_end)=GetPeriode($p_cn,$userPref);
  $op_date=( ! isset($e_date) ) ? "01".substr($l_date_start,2,8):$e_date;
  
  $e_ech=(isset($e_ech))?$e_ech:"";
  // Save old value and set a new one
  echo_debug("form_input.php.FormAch p_op_date is $op_date");
  $r="";
  if ( $view_only == false) {
    $r="<FORM ACTION=\"user_jrn.php?action=new\" METHOD=\"POST\">";
    
  }
  $r.='<TABLE>';
  $r.='<TR>'.InputType("Date ","Text","e_date",$op_date,$view_only).'</TR>';
  $r.='<TR>'.InputType("Echeance","Text","e_ech",$e_ech,$view_only).'</TR>';
  include_once("fiche_inc.php");
  // Display the supplier
  // TODO ADD BUTTON NEW SUPPLIER
  $fiche=GetFicheJrn($p_cn,$p_jrn,'cred');
  
  // Save old value and set a new one
  $customer=( isset ($e_customer) )?$e_customer:"";
  $r.='<TR>'.InputType("Fournisseur","SELECT","e_client",$customer,$view_only,$fiche).'</TR>';
  $r.="</TABLE>";
  // Record the current number of article
  $r.='<INPUT TYPE="HIDDEN" name="nb_item" value="'.$p_article.'">';
  $e_comment=(isset($e_comment))?$e_comment:"";


  // Start the div for item to sell
  $r.="<DIV>";
  $r.='<H2 class="info">Articles</H2>';
  $r.='<TABLE>';
  $fiche=GetFicheJrn($p_cn,$p_jrn,'deb');
echo_debug("Cred Nombre d'enregistrement ".sizeof($fiche));
  for ($i=0;$i< $p_article;$i++) {

    $march=(isset(${"e_march$i"}))?${"e_march$i"}:"";

    $r.='<TR>'.InputType("Article","SELECT","e_march".$i,$march,$view_only,$fiche);
    $quant=(isset(${"e_quant$i"}))?${"e_quant$i"}:"0";
    $r.=InputType("Quantité","TEXT","e_quant".$i,$quant,$view_only);
	$price=(isset(${"e_price$i"}))?${"e_price$i"}:"0";
	$r.=InputType("Prix","TEXT","e_price".$i,$price,$view_only);
    $r.='</TR>';
  }

  $r.="</TABLE>";
  $r.=$p_submit;
  $r.="</DIV>";
  $r.="</FORM>";

  return $r;


}
