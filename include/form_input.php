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
 *        is the label or for the js_search p_list is a string (cred
 *        or deb or the fiche_def_type
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
      return "<TD><span id=\"$p_name\"> $p_label </span> $hidden  </TD><TD>$p_value</TD>";
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
    $r=sprintf('<TD>%s</TD><TD> <INPUT TYPE="%s" NAME="%s" VALUE="%s" SIZE="10"></TD>',
	       $p_label,
	       $p_type,
	       $p_name,
	       $p_value);
    return $r;
  }
  // input type == TEXT_BIG
  if ( strtolower($p_type)=="text_big") {
    $r=sprintf('<TD>%s</TD><TD> <INPUT TYPE="%s" NAME="%s" VALUE="%s" SIZE="50"></TD>',
	       $p_label,
	       $p_type,
	       $p_name,
	       $p_value);
    return $r;
  }

  //span
  if ( strtolower($p_type)=="span") {
    $r=sprintf('<TD><span id="%s">%s</span></TD>',
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

  // input type == js_search => button search
  if ( strtolower($p_type)=="js_search") {
    $l_sessid=(isset ($_POST['PHPSESSID']))?$_POST['PHPSESSID']:$_GET['PHPSESSID'];
    $r=sprintf('<TD>
         <INPUT TYPE="button" onClick=NewCard(\'%s\',\'%s\',\'%s\') value="New">
         <INPUT TYPE="button" onClick=SearchCard(\'%s\',\'%s\',\'%s\') value="Search">
            %s</TD><TD> 

             <INPUT TYPE="Text" NAME="%s" VALUE="%s" SIZE="8">
                 </TD>',
	       $l_sessid,
	       $p_list,
	       $p_name,
	       $l_sessid,
	       $p_list,
	       $p_name,
	       $p_label,
	       $p_name,
	       $p_value 
	       );

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
    $r.=JS_SEARCH_CARD;
    
    $r.="<FORM NAME=\"form_detail\" ACTION=\"user_jrn.php?action=insert_vente\" METHOD=\"POST\">";

    
  }
  $r.='<TABLE>';
  $r.='<TR>'.InputType("Date ","Text","e_date",$op_date,$view_only).'</TR>';
  $r.='<TR>'.InputType("Echeance","Text","e_ech",$e_ech,$view_only).'</TR>';
  include_once("fiche_inc.php");
  // Display the customer
  // TODO ADD BUTTON NEW CUSTOMER
  //  $fiche=GetFicheJrn($p_cn,$p_jrn,'deb');
  $fiche='deb';
  echo_debug("Client Nombre d'enregistrement ".sizeof($fiche));
  // Save old value and set a new one
  $e_client=( isset ($e_client) )?$e_client:"";

  $e_client_label="";  

  // retrieve e_client_label
  if ( isNumber($e_client) == 1 ) {
    if ( isFicheOfJrn($p_cn,$p_jrn,$e_client,'deb') == 0 ) {
      $msg="Fiche inexistante !!! ";
      echo_error($msg); echo_error($msg);	
      echo "<SCRIPT>alert('$msg');</SCRIPT>";
      $e_client="";
    } else {
      $a_client=GetFicheAttribut($p_cn,$e_client);
      if ( $a_client != null)   
	  $e_client_label=$a_client['vw_name']."  adresse ".$a_client['vw_addr']."  ".$a_client['vw_cp'];
    }
  }

  $r.='<TR>'.InputType("Client ","js_search","e_client",$e_client,$view_only,$fiche).'</TD>';  
  
  $r.=       InputType(""       ,"span"   ,"e_client_label",$e_client_label,false).'</TD>';
  $r.="</TABLE>";


  // Record the current number of article
  $r.='<INPUT TYPE="HIDDEN" name="nb_item" value="'.$p_article.'">';
  $e_comment=(isset($e_comment))?$e_comment:"";


  // Start the div for item to sell
  $r.="<DIV>";
  $r.='<H2 class="info">Articles</H2>';
  $r.='<TABLE>';
  //  $fiche=GetFicheJrn($p_cn,$p_jrn,'cred');
  //  echo_debug("Cred Nombre d'enregistrement ".sizeof($fiche));
  for ($i=0;$i< $p_article;$i++) {
    // Code id
    $march=(isset(${"e_march$i"}))?${"e_march$i"}:"";
    $march_sell=(isset(${"e_march".$i."_sell"}))?${"e_march".$i."_sell"}:"";


    $march_tva_label="";
    $march_label="";

    // If $march has a value
    if ( isNumber($march) == 1 ) {
      if ( isFicheOfJrn($p_cn,$p_jrn,$march,'cred') == 0 ) {
	$msg="Fiche inexistante !!! ";
	echo_error($msg); echo_error($msg);	
	echo "<SCRIPT>alert('$msg');</SCRIPT>";
	$march="";
      } else {
	// retrieve the tva label and name
	$a_fiche=GetFicheAttribut($p_cn, $march);
	if ( $a_fiche != null ) {
	  $march_tva_label=$a_fiche['tva_label'];
	  $march_label=$a_fiche['vw_name'];
	}
      }
    }
    // Show input
    $r.='<TR>'.InputType("","js_search","e_march".$i,$march,$view_only,'cred');
    $r.=InputType("","span", "e_march".$i."_label", $march_label,$view_only);
    $r.=InputType("prix","text","e_march".$i."_sell",$march_sell,$view_only);

    $r.=InputType("tva","span","e_march".$i."_tva_label",$march_tva_label,$view_only);

    $quant=(isset(${"e_quant$i"}))?${"e_quant$i"}:"0";
    $r.=InputType("Quantité","TEXT","e_quant".$i,$quant,$view_only);
    $r.='</TR>';
  }



  $r.="</TABLE>";
  $r.='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">';
  $r.='<INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Enregistrer">';
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
// Verify is a client is set
 if ( isNumber($e_client)    == 0) {
   $msg="Client inexistant";
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

 // Check if the fiche is in the jrn
 if (IsFicheOfJrn($p_cn , $p_jrn, $e_client,'deb') == 0 ) 
   {
     $msg="Client invalid please recheck";
     echo_error($msg);
     echo "<SCRIPT>alert('$msg');</SCRIPT>";
     return null;
   }

 // check if all e_march are in fiche
  for ($i=0;$i<$p_number;$i++) {
    if ( trim(${"e_march$i"})  == "" ) {
      // no goods to sell 
      continue;
    }
  
    // Check wether the f_id is a number
    if ( isNumber(${"e_march$i"}) == 0 ) {
      $msg="Fiche inexistante !!! ";
      echo_error($msg); echo_error($msg);	
      echo "<SCRIPT>alert('$msg');</SCRIPT>";
      return null;
    }
    // Check 
    if ( isFicheOfJrn($p_cn,$p_jrn,${"e_march$i"},'cred') == 0 ) {
      $msg="Fiche inexistante !!! ";
      echo_error($msg); echo_error($msg);	
      echo "<SCRIPT>alert('$msg');</SCRIPT>";
      return null;
    }
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
    if ( trim(${"e_march$i"})  == "" ) {
      // no goods to sell 
      continue;
    }
  
    // Get the name
    $fiche_name=getFicheName($p_cn,${"e_march$i"});

    // Quantity
    $fiche_quant=${"e_quant$i"};

    // No  row if there is quantity
    if ( $fiche_quant == 0.0 ) continue;


    // If the price is not a number, retrieve the price from the database
    if ( isNumber(${"e_march$i"."_sell"}) == 0 ) {
	   $fiche_price=getFicheAttribut($p_cn,${"e_march$i"},ATTR_DEF_PRIX_VENTE);
	 } else {
      $fiche_price=${"e_march$i"."_sell"};
    }
    
    
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
	  //	  $r.='<INPUT TYPE="SUBMIT" name="record_invoice" value="Sauver">';
	  $r.='<INPUT TYPE="SUBMIT" name="record_and_print_invoice" value="Sauver & imprimer">';
	  $r.='<INPUT TYPE="SUBMIT" name="correct_new_invoice" value="Corriger">';
	  $r.='</FORM>';
	  } 
if ( $p_doc == 'pdf' ) {
  // prob with pdf and the pdf pluggin 
  // Cannot find a nice workaround for that
 	  $r.='<FORM target="new" METHOD="POST" ACTION="print_invoice.php">';
// 	  $r.='<FORM METHOD="POST">';
	  $r.=$data;
	  $r.=InputType("","HIDDEN","e_comment",$p_comment);
	  $sessid=( isset ($_POST['PHPSESSID']))?$_POST['PHPSESSID']:$_GET['PHPSESSID'];
	  //	  $r.='<INPUT TYPE="SUBMIT" name="record_invoice" onClick="var a=window.open(\'print_invoice.php?PHPSESSID='.$sessid.' \',\'Invoice\');" value="Imprimer">';
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
 * e_march0_sell=>e:1
 * e_march1 => e : 6
 * e_quant1 => e : 2
 * e_march1_sell=>e:1
 * e_march2 => e : 7
 * e_quant2 => e : 3
 * e_march2_sell=>e:1
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

    // check wether the price is set or no
    if ( isNumber(${"e_march$i"."_sell"}) == 0 ) {
      // If the price is not set we have to find it from the database
      $a_price[$i]=GetFicheAttribut($p_cn,$a_good[$i],ATTR_DEF_PRIX_VENTE);
    } else {
      // The price is valid
      $a_price[$i]=${"e_march$i"."_sell"};
    }
    $amount+=$a_price[$i]*$a_quant[$i];
  }

  $a_vat=ComputeVat($p_cn,	$a_good,$a_quant,$a_price);

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

		// don't record operation of 0
		if ( $a_price[$i]*$a_quant[$i] == 0 ) continue;

		// record into jrnx
		$j_id=InsertJrnx($p_cn,'c',$p_user,$p_jrn,$poste,$e_date,$a_price[$i]*$a_quant[$i],$seq,$periode);

		// always save quantity but in withStock we can find what card need a stock management
		InsertStockGoods($p_cn,$j_id,$a_good[$i],$a_quant[$i],'c');
	}
	// Insert Vat
	if (sizeof($a_vat) != 0 ) // no vat
	{
		foreach ($a_vat as $tva_id => $tva_amount ) {
		$poste=GetTvaPoste($p_cn,$tva_id,'c');
		if ($tva_amount == 0 ) continue;
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
//  $e_date=( ! isset($e_date) ) ? "":$e_date;
  $e_date=( ! isset($e_date) ) ? "01".substr($l_date_start,2,8):$e_date;

  // Verify if valid date
  if (  VerifyOperationDate($p_cn,$p_user,$e_date)   == null) {
    if ( $view_only == true) 
      return null;
    else 
      $e_date="01".substr($l_date_start,2,8);
  }
  
  $e_ech=(isset($e_ech))?$e_ech:"";
  $e_comment=(isset($e_comment))?$e_comment:"";
  // Save old value and set a new one
  //  echo_debug("form_input.php.FormAch p_op_date is $e_date");
  $r="";
  if ( $view_only == false) {
    $r.=JS_SEARCH_CARD;


    
  }
  $r.="<FORM NAME=\"form_detail\" ACTION=\"user_jrn.php?action=new\" METHOD=\"POST\">";
  $r.='<TABLE>';
  $r.='<TR>'.InputType("Date ","Text","e_date",$e_date,$view_only).'</TR>';
  $r.='<TR>'.InputType("Echeance","Text","e_ech",$e_ech,$view_only).'</TR>';
  $r.='<TR>'.InputType("Description","Text_big","e_comment",$e_comment,$view_only).'</TR>';
  include_once("fiche_inc.php");
  // Display the supplier
  // TODO ADD BUTTON NEW SUPPLIER
  //  $fiche=GetFicheJrn($p_cn,$p_jrn,'cred');
  
  // Save old value and set a new one
  $e_client=( isset ($e_client) )?$e_client:"";
  //  $r.='<TR>'.InputType("Fournisseur","SELECT","e_client",$customer,$view_only,$fiche).'</TR>';

  $e_client_label="";  

  // retrieve e_client_label
  if ( isNumber($e_client) == 1 ) {
      if ( isFicheOfJrn($p_cn,$p_jrn,$e_client,'cred') == 0 ) {
	$msg="Fiche inexistante !!! ";
	echo_error($msg); echo_error($msg);	
	echo "<SCRIPT>alert('$msg');</SCRIPT>";
	$e_client="";
      } else {
	$a_client=GetFicheAttribut($p_cn,$e_client);
	if ( $a_client != null)   
	  $e_client_label=$a_client['vw_name']."  adresse ".$a_client['vw_addr']."  ".$a_client['vw_cp'];
      }
  }
  
  $r.='<TR>'.InputType("Fournisseur","js_search","e_client",$e_client,$view_only,'cred');
  $r.=       InputType(""       ,"span"   ,"e_client_label",$e_client_label,false).'</TR>';
  $r.="</TABLE>";



  // Record the current number of article
  $r.='<INPUT TYPE="HIDDEN" name="nb_item" value="'.$p_article.'">';
  $e_comment=(isset($e_comment))?$e_comment:"";


  // Start the div for item to sell
  $r.="<DIV>";
  $r.='<H2 class="info">Articles</H2>';
  $r.='<TABLE>';


  for ($i=0;$i< $p_article;$i++) {

    $march=(isset(${"e_march$i"}))?${"e_march$i"}:"";
    $march_buy=(isset(${"e_march".$i."_buy"}))?${"e_march".$i."_buy"}:"0";


    $march_tva_label="";
    $march_label="";

    // If $march has a value
    if ( isNumber($march) == 1 ) {
      if ( isFicheOfJrn($p_cn,$p_jrn,$march,'deb') == 0 ) {
      $msg="Fiche inexistante !!! ";
      echo_error($msg); echo_error($msg);	
      echo "<SCRIPT>alert('$msg');</SCRIPT>";
      $march="";
      } else {
           // retrieve the tva label and name
      $a_fiche=GetFicheAttribut($p_cn, $march);
      if ( $a_fiche != null ) {
	$march_tva_label=$a_fiche['tva_label'];
	$march_label=$a_fiche['vw_name'];
      }
    }
    }
    $r.='<TR>'.InputType("","js_search","e_march".$i,$march,$view_only,'deb');
    $r.=InputType("","span", "e_march".$i."_label", $march_label,$view_only);
    $r.=InputType("prix","text","e_march".$i."_buy",$march_buy,$view_only);
    $r.=InputType("tva","span","e_march".$i."_tva_label",$march_tva_label,$view_only);

    $quant=(isset(${"e_quant$i"}))?${"e_quant$i"}:"1";
    $r.=InputType("Quantité","TEXT","e_quant".$i,$quant,$view_only);
    $r.='</TR>';
  }

  $r.="</TABLE>";
  $r.=$p_submit;
  $r.="</DIV>";
  $r.="</FORM>";
  //TODO if view only show total
  return $r;


}

/* function RecordAchat
 **************************************************
 * Purpose : Record an buy in the table jrn &
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
 * e_march0_sell=>e:1
 * e_march1 => e : 6
 * e_quant1 => e : 2
 * e_march1_sell=>e:1
 * e_march2 => e : 7
 * e_quant2 => e : 3
 * e_march2_sell=>e:1
V : view_invoice => e : Voir cette facture
V : record_invoice => e : Sauver 
 *  - $p_user userid
 *  - $p_jrn current folder (journal)
 * gen :
 *	- none
 * return:
 *	      true on success
 */
function RecordAchat($p_cn,$p_array,$p_user,$p_jrn)
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

    // check wether the price is set or no
    if ( isNumber(${"e_march$i"."_buy"}) == 0 ) {
      // If the price is not set we have to find it from the database
      $a_price[$i]=GetFicheAttribut($p_cn,$a_good[$i],ATTR_DEF_PRIX_ACHAT);
    } else {
      // The price is valid
      $a_price[$i]=${"e_march$i"."_buy"};
    }
    $amount+=$a_price[$i]*$a_quant[$i];
  }

  $a_vat=ComputeVat($p_cn,	$a_good,$a_quant,$a_price);

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

  InsertJrnx($p_cn,'c',$p_user,$p_jrn,$poste,$e_date,$amount+$sum_vat,$seq,$periode);
  
  // Credit = goods 
  for ( $i = 0; $i < $nb_item;$i++) {
    $poste=GetFicheAttribut($p_cn,$a_good[$i],ATTR_DEF_ACCOUNT);
    if ( $a_price[$i] * $a_quant[$i] == 0 ) continue;    
    $j_id=InsertJrnx($p_cn,'d',$p_user,$p_jrn,$poste,$e_date,$a_price[$i]*$a_quant[$i],$seq,$periode);
    //    if ( withStock($p_cn,$a_good[$i]) == true )  
    // always save quantity but in withStock we can find what card need a stock management
    InsertStockGoods($p_cn,$j_id,$a_good[$i],$a_quant[$i],'c');
  }
  // Insert Vat
  if (sizeof($a_vat) != 0 ) // no vat
    {
      foreach ($a_vat as $tva_id => $tva_amount ) {
	$poste=GetTvaPoste($p_cn,$tva_id,'d');
	InsertJrnx($p_cn,'c',$p_user,$p_jrn,$poste,$e_date,$tva_amount,$seq,$periode);
      }
    }
  echo_debug("echeance = $e_ech");
  InsertJrn($p_cn,$e_date,$e_ech,$p_jrn,"",$amount+$sum_vat,$seq,$periode);
  // Set Internal code and Comment
  $comment=SetInternalCode($p_cn,$seq,$p_jrn)."  client : ".GetFicheName($p_cn,$e_client);
  if ( $e_comment=="" ) {
    // Update comment if comment is blank
    $Res=ExecSql($p_cn,"update jrn set jr_comment='".$comment."' where jr_grpt_id=".$seq);
  }
  return $comment;
}
/* TODO function to retrieve data from a operation (jrn.jr_id)
 transform it accordingly  the expected array of FormAchat, FormVente
 or FormFin  
 */


/* function FormFin($p_cn,$p_jrn,$p_user,$p_array=null,$view_only=true,$p_item=1) 
 * Purpose : Display the form for financial 
 *           Used to show detail, encode a new fin op 
 *           or update one
 *        
 * parm : 
 *	- p_array which can be empty
 *      - the "journal"
 *      - $p_user = $g_user
 *      - $p_submit contains the submit string
 *      - view_only if we cannot change it (no right or centralized op)
 *      - $p_item number of article
 * gen :
 *	-
 * return: string with the form
 */
function FormFin($p_cn,$p_jrn,$p_user,$p_submit,$p_array=null,$view_only=true,$p_item=4)
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
  $e_date=( ! isset($e_date) ) ? "01".substr($l_date_start,2,8):$e_date;

  // Verify if valid date
  if (  VerifyOperationDate($p_cn,$p_user,$e_date)   == null) {
    if ( $view_only == true) 
      return null;
    else 
      $e_date="01".substr($l_date_start,2,8);
  }


  $e_ech=(isset($e_ech))?$e_ech:"";
  $e_comment=(isset($e_comment))?$e_comment:"";

  $r="";
  if ( $view_only == false) {
    $r.=JS_SEARCH_CARD;
  }

  $r.="<FORM NAME=\"form_detail\" ACTION=\"user_jrn.php?action=new\" METHOD=\"POST\">";
  $r.='<TABLE>';
  $r.='<TR>'.InputType("Date ","Text","e_date",$e_date,$view_only).'</TR>';

  $r.='<TR>'.InputType("Description","Text_big","e_comment",$e_comment,$view_only).'</TR>';
  include_once("fiche_inc.php");
  $r.='<INPUT TYPE="HIDDEN" name="nb_item" value="'.$p_item.'">';

  // bank_account operation
  // Save old value and set a new one
  $e_bank_account=( isset ($e_bank_account) )?$e_bank_account:"";
  $e_bank_account_label="";  

    // retrieve e_bank_account_label
  if ( isNumber($e_bank_account) == 1 ) {
    if ( isFicheOfJrn($p_cn,$p_jrn,$e_bank_account,'cred') == 0 ) {
      $msg="Fiche inexistante !!! ";
      echo_error($msg); echo_error($msg);	
      echo "<SCRIPT>alert('$msg');</SCRIPT>";
      $e_bank_account="";
    } else {
      $a_client=GetFicheAttribut($p_cn,$e_bank_account);
      if ( $a_client != null)   
	$e_bank_account_label=$a_client['vw_name']."  adresse ".$a_client['vw_addr']."  ".$a_client['vw_cp'];
      }
  }
  
  $r.='<TR>'.InputType("Banque","js_search","e_bank_account",$e_bank_account,$view_only,FICHE_TYPE_FIN).'</TR>';
  $r.="</TABLE>";
  
  $r.=       InputType(""       ,"span"   ,"e_bank_account_label",$e_bank_account_label,false).'</TD>';
  
  $e_comment=(isset($e_comment))?$e_comment:"";
  
  // TODO Mettre le solde ici
  // ComputeBanqueSaldo
  
  // Start the div for item to move money
  $r.="<DIV>";
  $r.='<H2 class="info">Actions</H2>';
  $r.='<TABLE>';
    
  // Parse each " tiers" 
    for ($i=0; $i < $p_item; $i++) {
      $tiers=(isset(${"e_other".$i}))?${"e_other".$i}:"";
      $tiers_label="";
      $tiers_amount=(isset(${"e_other$i"."_amount"}))?${"e_other$i"."_amount"}:0;
    // If $tiers has a value
    if ( isNumber($tiers) == 1 ) {
      if ( isFicheOfJrn($p_cn,$p_jrn,$tiers,'deb') == 0 ) {
	$msg="Fiche inexistante !!! ";
	echo_error($msg); echo_error($msg);	
	echo "<SCRIPT>alert('$msg');</SCRIPT>";
	$tiers="";
      } else {
	// retrieve the tva label and name
	$a_fiche=GetFicheAttribut($p_cn, $tiers);
	if ( $a_fiche != null ) {
	  $tiers_label=$a_fiche['vw_name'];
	}
      }
    }
    ${"e_other$i"."_amount"}=(isset (${"e_other$i"."_amount"}))?${"e_other$i"."_amount"}:0;
    // Compute the string to pass to InputType
    $f=FICHE_TYPE_CLIENT.",".FICHE_TYPE_FOURNISSEUR.",".FICHE_TYPE_ADM_TAX.",".FICHE_TYPE_FIN;
    $r.='<TR>'.InputType("","js_search","e_other".$i,$tiers,$view_only,$f);
    $r.=InputType("","span", "e_other$i"."_label", $tiers_label,$view_only);
    $r.=InputType("amount","TEXT","e_other$i"."_amount",$tiers_amount,$view_only);
    $r.='</TR>';
 }

$r.="</TABLE>";
// if view_only is true
//Put the new saldo here (old saldo - operation)

$r.=$p_submit;
$r.="</DIV>";
$r.="</FORM>";
//TODO view new saldo
return $r;


}

/* function RecordFin
 **************************************************
 * Purpose : Record an invoice in the table jrn &
 *           jrnx
 *        
 * parm : 
 *	- $p_cn Database connection
 *  - $p_array contains all the invoice data
 * e_date => e : 01.01.2003
 * e_bank_account => e : 3
 *  - $p_user userid
 *  - $p_jrn current folder (journal)
 *  - array e_other$i, e_other$i_amount, e_other$i_label
 * gen :
 *	- none
 * return:
 *	      true on success
 */
function RecordFin($p_cn,$p_array,$p_user,$p_jrn) {

  foreach ( $p_array as $v => $e)
  {
    ${"$v"}=$e;
  }
  // Get the default period
  $periode=GetUserPeriode($p_cn,$p_user);

  // Test if the data are correct
  // Verify the date
  if ( isDate($e_date) == null ) { 
	  echo_error("Invalid date $e_date");
	  echo_debug("Invalid date $e_date");
	  echo "<SCRIPT> alert('INVALID DATE $e_date !!!!');</SCRIPT>";
	  return null;
		}


  // Test the date

  // Compute the j_grpt
  $seq=GetNextId($p_cn,'j_grpt')+1;
  
  
  // Debit = banque
  $poste_bq=GetFicheAttribut($p_cn,$e_bank_account,ATTR_DEF_ACCOUNT);

  $amount=0.0;  
  // Credit = goods 
  for ( $i = 0; $i < $nb_item;$i++) {
    // if tiers is set and amount != 0 insert it into the database 
    // and quit the loop ?
    if ( ${"e_other$i"."_amount"} == 0 ) continue;
    $poste=GetFicheAttribut($p_cn,${"e_other$i"},ATTR_DEF_ACCOUNT);

    $amount+=${"e_other$i"."_amount"};
    // Record a line for the bank
    //    $type=( ${"e_other$i"."_amount"} < 0 )?'d':'c';

    InsertJrnx($p_cn,'d',$p_user,$p_jrn,$poste_bq,$e_date,${"e_other$i"."_amount"},$seq,$periode);    


    // Record a line for the other account
    //    $type=( ${"e_other$i"."_amount"} < 0 )?'c':'d';
    $j_id=InsertJrnx($p_cn,'c',$p_user,$p_jrn,$poste,$e_date,${"e_other$i"."_amount"},$seq,$periode);
    echo_debug("   $j_id=InsertJrnx($p_cn,'d',$p_user,$p_jrn,$poste,$e_date,".${"e_other$i"}."_amount".",$seq,$periode);");

    InsertJrn($p_cn,$e_date,'c',$p_jrn,$e_comment,${"e_other$i"."_amount"},$seq,$periode);
  



  // Set Internal code and Comment
  $comment=SetInternalCode($p_cn,$seq,$p_jrn)."  client : ".GetFicheName($p_cn,$e_bank_account);
  if ( $e_comment=="" ) {
    // Update comment if comment is blank
    $Res=ExecSql($p_cn,"update jrn set jr_comment='".$comment."' where jr_grpt_id=".$seq);
  }
  }
  return $comment;
}
