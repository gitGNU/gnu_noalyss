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
 *	- Label question
 *      - The type (text, select, text_big
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
  // Input type == select2
  if ( strtolower($p_type)=="select2" ) {
    $r="<TD> $p_label</TD><TD>";
    $r.=sprintf('<SELECT NAME="%s">',$p_name);
    foreach ($p_list as $item) {
      $selected="";
      if ( $p_value == $item['value'] ) {
		$selected="SELECTED";
      }
      $r.=sprintf('<OPTION VALUE="%s" %s>%s',
		  $item['value'],
		  $selected,
		  $item['label']);
    }
    $r.="</SELECT></TD>";
    return $r;
  }

  // input type == TEXT
  if ( strtolower($p_type)=="text") {
    if ( strlen(trim($p_label)) != 0 ) 
      $label="<TD>$p_label</TD>";
    else
      $label="";
    $r=sprintf('%s<TD> <INPUT TYPE="%s" NAME="%s" VALUE="%s" SIZE="10"></TD>',
	       $label,
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
    $l_sessid=$_REQUEST['PHPSESSID'];
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
  }
  // input type == js_concerned => button search for the concerned operations
  if ( strtolower($p_type)=="js_concerned") {
    $l_sessid=$_REQUEST['PHPSESSID'];
    $r=sprintf('<TD>
         <INPUT TYPE="button" onClick=SearchJrn(\'%s\',\'%s\') value="Search">
            %s</TD><TD> 

             <INPUT TYPE="Text" NAME="%s" VALUE="%s" SIZE="8">
                 </TD>',
	       $l_sessid,
	       $p_name,
	       $p_label,
	       $p_name,
	       $p_value 
	       );

  
   
  }

  // input type == js_search_poste => button search for the account
  if ( strtolower($p_type)=="js_search_poste") {
    $l_sessid=$_REQUEST['PHPSESSID'];

    if ( $p_list == null ) { // no filter
    $r=sprintf('<TD>
         <INPUT TYPE="button" onClick=SearchPoste(\'%s\',\'%s\') value="Search">
            %s</TD><TD> 

             <INPUT TYPE="Text" NAME="%s" VALUE="%s" SIZE="8">
                 </TD>',
	       $l_sessid,
	       $p_name,
	       $p_label,
	       $p_name,
	       $p_value 
	       );

    } else { // $p_list is not null, so we have a filter
      $r=sprintf('<TD>
         <INPUT TYPE="button" onClick=SearchPosteFilter(\'%s\',\'%s\',\'%s\') value="Search">
            %s</TD><TD> 

             <INPUT TYPE="Text" NAME="%s" VALUE="%s" SIZE="8">
                 </TD>',
		 $l_sessid,
		 $p_name,
		 $p_list,
		 $p_label,
		 $p_name,
		 $p_value 
		 );

    }
   
  }

  // input type == js_tva
  if ( strtolower($p_type)=="js_tva") {
    if ( strlen(trim($p_label)) != 0 ) 
      $label="<TD>$p_label</TD>";
    else
      $label="";
    $r=sprintf('%s<TD> <INPUT TYPE="%s" NAME="%s" VALUE="%s" SIZE="3" onChange="ChangeTVA(\'%s\',\'%s\');">',
	       $label,
	       $p_type,
	       $p_name,
	       $p_value,
	       $p_list,
	       $p_name);
    $l_sessid=$_REQUEST['PHPSESSID'];
    //    $r.="<script> document.getElementById('$p_list').innerHTML=\"  \";</script>"; 
    $r.=sprintf("<input type=\"button\" value=\"Tva\" onClick=\"ChangeTVA('%s','%s'); 
                  ShowTva('%s','%s')\"></TD>",
		$p_list,$p_name,$l_sessid,$p_name);
    return $r;
  }

  return $r;

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
  $op_date=( ! isset($e_date) ) ?substr($l_date_start,2,8):$e_date;
  $e_ech=(isset($e_ech))?$e_ech:"";
  //  $e_jrn=(isset($e_jrn))?$e_jrn:"";
  // Save old value and set a new one
  echo_debug(__FILE__,__LINE__,"form_input.php.FormVentep_op_date is $op_date");
  $r="";
  if ( $view_only == false) {
    $r.=JS_SEARCH_CARD;
    $r.=JS_SHOW_TVA;    
    $r.=JS_TVA;
    $r.="<FORM NAME=\"form_detail\" ACTION=\"user_jrn.php?action=insert_vente\" METHOD=\"POST\">";

    
  }
  //  $list=GetJrn($p_cn,$p_jrn);
  $sql="select jrn_def_id as value,jrn_def_name as label from jrn_def where jrn_def_type='VEN'";
  $list=GetArray($p_cn,$sql);
  $r.='<TABLE>';
  $r.='<TR>'.InputType("Date ","Text","e_date",$op_date,$view_only).'</TR>';

  $r.='<TR>'.InputType("Echeance","Text","e_ech",$e_ech,$view_only).'</TR>';
  $r.='<TR>'.InputType("Commentaire","Text_big","e_comm",$e_ech,$view_only).'</TR>';

  include_once("fiche_inc.php");
  // Display the customer
  $fiche='deb';
  echo_debug(__FILE__,__LINE__,"Client Nombre d'enregistrement ".sizeof($fiche));
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
  $r.='<TR>';
  $r.="<th></th>";
  $r.="<th>Code</th>";
  $r.="<th>Dénomination</th>";
  $r.="<th>prix</th>";
  $r.="<th colspan=\"2\">tva</th>";
  $r.="<th>quantité</th>";
  $r.='</TR>';
  //  $fiche=GetFicheJrn($p_cn,$p_jrn,'cred');
  //  echo_debug(__FILE__,__LINE__,"Cred Nombre d'enregistrement ".sizeof($fiche));
  for ($i=0;$i< $p_article;$i++) {
    // Code id
    $march=(isset(${"e_march$i"}))?${"e_march$i"}:"";
    $march_sell=(isset(${"e_march".$i."_sell"}))?${"e_march".$i."_sell"}:"";
    $march_tva_id=(isset(${"e_march$i"."_tva_id"}))?${"e_march$i"."_tva_id"}:"";

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
	  if ( $march_tva_id == "" ) {
	    $march_tva_id=$a_fiche['tva_id'];
	    $march_tva_label=$a_fiche['tva_label'];
	  }
	  $march_label=$a_fiche['vw_name'];
	}
      }
    }
    // Show input
    $r.='<TR>'.InputType("","js_search","e_march".$i,$march,$view_only,'cred');
    // card's name
    $r.=InputType("","span", "e_march".$i."_label", $march_label,$view_only);

    // price
    $r.=InputType("","text","e_march".$i."_sell",$march_sell,$view_only);
    // vat label
    $r.=InputType("","span","e_march".$i."_tva_label",$march_tva_label,$view_only);
    // Tva id 
    $r.=InputType("","js_tva","e_march$i"."_tva_id",$march_tva_id,$view_only,"e_march".$i."_tva_label");

    $quant=(isset(${"e_quant$i"}))?${"e_quant$i"}:"0";
    // quantity
    $r.=InputType("","TEXT","e_quant".$i,$quant,$view_only);
    $r.='</TR>';
  }



  $r.="</TABLE>";
  if ($view_only == false ) {
    $r.='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article">';
    $r.='<INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Enregistrer">';
    $r.="</DIV>";
    $r.="</FORM>";
    $r.=JS_CALC_LINE;
  } else {
//     // show summary
//     $total=0;
//     $r.='<h2 class="info">Total</h2>';
//     $r.="<table>";
//     $r.="<th> Description</th>";
//     $r.="<th> Base TVA </th>";
//     $r.="<th> Tva </th>";
//     $r.="<th> Total </th>";
//     for ( $i = 0; $i < $p_article;$i++) {
//       if ( $view_only == true and ! isset (${"e_march$i"}) ) continue;
//       $march=${"e_march$i"};
//       if ( isNumber($march) ==1 and
// 	   isFicheOfJrn($p_cn,$p_jrn,$march,'cred')){
// 	   $a_fiche=GetFicheAttribut($p_cn, $march);
// 	   // compute some data
// 	   //	   $tva=(isNumber($a_fiche['tva_rate']) == 0 )?0:$a_fiche['tva_rate'];
// 	   if ( isNumber(${"e_march$i"."_tva_id"})  ==1 ) {

// 		  $a_tva=GetTvaRate($p_cn,${"e_march$i"."_tva_id"});
// 		  $tva=$a_tva['tva_rate'];
// 		} else {
// 		  $tva=(isNumber($a_fiche['tva_rate'])==1)?$a_fiche['tva_rate']:0;
// 		}
// 	   $vat_row=${"e_march$i"."_sell"}*${"e_quant$i"}*$tva;
// 	   $total_row_no_vat=${"e_march$i"."_sell"}*${"e_quant$i"};
// 	   $total_row=${"e_march$i"."_sell"}*${"e_quant$i"}+$vat_row;
      
// 	   $r.="<TR>";
// 	   $r.="<TD>".$a_fiche['vw_name']."</td>";
// 	   //	   $r.="<TD>".$a_fiche['tva_label']."</td>";
// 	   $r.="<TD>  ".round($total_row_no_vat,2)."</TD>";
// 	   $r.="<TD>  ".$vat_row."</TD>";
// 	   $r.="<TD>  ".round($total_row,2)."</TD>";
// 	   $r.="</TR>";
// 	   $total+=$total_row;
//       }
//     }// for ($i=0
//     $r.="</table>";
//     $r.=sprintf(" Total = %8.2f",$total);
     $r.="</div>";

  }



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
 * 
 */

function FormVenteView ($p_cn,$p_jrn,$p_user,$p_array,$p_number,$p_doc='form',$p_comment='') 
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
	  echo_debug(__FILE__,__LINE__,"Invalid date $e_date");
	  echo "<SCRIPT> alert('INVALID DATE $e_date !!!!');</SCRIPT>";
	  return null;
		}
// Verify the quantity
for ($o = 0;$o < $p_number; $o++) {
	if ( isNumber(${"e_quant$o"}) == 0 ) {
		echo_debug(__FILE__,__LINE__,"invalid quantity ".${"e_quant$o"});
		echo_error("invalid quantity ".${"e_quant$o"});
		echo "<SCRIPT> alert('INVALID QUANTITY !!!');</SCRIPT>";
		return null;
	}	
    // check if vat is correct
    if ( strlen(trim(${"e_march$o"."_tva_id"})) !=0 ) {
      // vat is given we check it now check if valid
      if (isNumber(${"e_march$o"."_tva_id"}) == 0
	  or CountSql($p_cn,"select tva_id from tva_rate where tva_id=".${"e_march$o"."_tva_id"}) ==0){
	$msg="Invalid TVA !!! ";
	echo_error($msg); echo_error($msg);	
	echo "<SCRIPT>alert('$msg');</SCRIPT>";
	return null;
	
      }
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
  echo_debug(__FILE__,__LINE__," date $e_date = $day.$month.$year");
  $p_ech=date('d.m.Y',mktime(0,0,0,$month,$day+$e_ech,$year));
  echo_debug(__FILE__,__LINE__,"p_ech = $e_ech $p_ech");
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
  // Show desc
  $r.="<tr>";
  $r.=InputType("Description","text_big","",$e_comm,true);
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
    $vat=(isNumber(${"e_march$i"."_tva_id"})==0)?getFicheAttribut($p_cn,${"e_march$i"},ATTR_DEF_TVA):${"e_march$i"."_tva_id"};
	
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
    $r.='<TD  ALIGN="RIGHT">'.round($fiche_sum,2).'</TD>';
    $r.='<TD ALIGN="RIGHT">'.round($fiche_amount_vat,2).'</TD>';

    $r.='<TD>'.round($fiche_with_vat,2).'</TD>';

    $r.="</TR>";
  }
  
  // end table
  $r.='</TABLE> ';
  $r.='<DIV style="padding:30px;font-size:14px">';
  $r.="Total HTVA =".round( $sum_march,2)." <br>";
  $r.="Total = ".round($sum_with_vat,2);
  $r.="</DIV>";
  if ( $p_doc == 'form' ) {
	  $r.='<FORM METHOD="POST" ACTION="user_jrn.php?action=record">';
	  $r.=$data;
	  //	  $r.='<INPUT TYPE="SUBMIT" name="record_invoice" value="Sauver">';
	  // If the total == 0 prevent the insert
	  if ( $sum_with_vat != 0 ) {
	    $r.='<INPUT TYPE="SUBMIT" name="record_and_print_invoice" value="Sauver" >';
	  }
	  $r.='<INPUT TYPE="SUBMIT" name="correct_new_invoice" value="Corriger">';
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
    $a_price[$i]=0;
    $a_vat[$i]=${"e_march$i"."_tva_id"};
    // check wether the price is set or no
    if ( isNumber(${"e_march$i"."_sell"}) == 0 ) {
      if ( isNumber($a_good[$i]) == 1 ) {
	     // If the price is not set we have to find it from the database
	     $a_price[$i]=GetFicheAttribut($p_cn,$a_good[$i],ATTR_DEF_PRIX_VENTE);
	   } 
    } else {
      // The price is valid
      $a_price[$i]=${"e_march$i"."_sell"};
    }
    $amount+=$a_price[$i]*$a_quant[$i];
  }

  $a_vat=ComputeVat($p_cn,$a_good,$a_quant,$a_price,$a_vat);

  $sum_vat=0.0;
  if ( $a_vat != null ){
    foreach ( $a_vat as $element => $t) {
      echo_debug(__FILE__,__LINE__," a_vat element $element t $t");
      $sum_vat+=$t;
      echo_debug(__FILE__,__LINE__,"sum_vat = $sum_vat");
    }
  }
  // First we add in jrnx
	
  // Compute the j_grpt
  $seq=GetNextId($p_cn,'j_grpt')+1;


  // Debit = client
  $poste=GetFicheAttribut($p_cn,$e_client,ATTR_DEF_ACCOUNT);
  StartSql($p_cn);	
  $r=InsertJrnx($p_cn,'d',$p_user,$p_jrn,$poste,$e_date,$amount+$sum_vat,$seq,$periode);
  if ( $r == false) { $Rollback($p_cn);exit("error __FILE__ __LINE__");}
  // Credit = goods 
  for ( $i = 0; $i < $nb_item;$i++) {
    if ( isNumber($a_good[$i]) == 0 ) continue;
    $poste=GetFicheAttribut($p_cn,$a_good[$i],ATTR_DEF_ACCOUNT);
	  
    // don't record operation of 0
    if ( $a_price[$i]*$a_quant[$i] == 0 ) continue;
	  
    // record into jrnx
    $j_id=InsertJrnx($p_cn,'c',$p_user,$p_jrn,$poste,$e_date,$a_price[$i]*$a_quant[$i],$seq,$periode);
    if ( $j_id == false) { $Rollback($p_cn);exit("error __FILE__ __LINE__");}
    // always save quantity but in withStock we can find what card need a stock management
    if (  InsertStockGoods($p_cn,$j_id,$a_good[$i],$a_quant[$i],'c') == false ) {
      $Rollback($p_cn);exit("error __FILE__ __LINE__");}
  }
  
  // Insert Vat

  if ( $a_vat  !=  null  ) // no vat

    {
      foreach ($a_vat as $tva_id => $tva_amount ) {
	$poste=GetTvaPoste($p_cn,$tva_id,'c');
	if ($tva_amount == 0 ) continue;
	$r=InsertJrnx($p_cn,'c',$p_user,$p_jrn,$poste,$e_date,$tva_amount,$seq,$periode);
	if ( $r == false ) { Rollback($p_cn); exit(" Error __FILE__ __LINE__");}
      
      }
    }
  echo_debug(__FILE__,__LINE__,"echeance = $e_ech");
  $r=InsertJrn($p_cn,$e_date,$e_ech,$p_jrn,"Invoice",$amount+$sum_vat,$seq,$periode);
  if ( $r == false ) { Rollback($p_cn); exit(" Error __FILE__ __LINE__");}
  // Set Internal code and Comment
  $internal=SetInternalCode($p_cn,$seq,$p_jrn);
  $comment=(trim($e_comm) == "")?$internal."  client : ".GetFicheName($p_cn,$e_client):$e_comm;

  // Update and set the invoice's comment 
  $Res=ExecSql($p_cn,"update jrn set jr_comment='".$comment."' where jr_grpt_id=".$seq);
if ( $Res == false ) { Rollback($p_cn); exit(" Error __FILE__ __LINE__"); };
  Commit($p_cn);

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
   // default date
   $flag=(isset($e_date))?1:0;

  $e_date=( ! isset($e_date) ) ? substr($l_date_start,2,8):$e_date;
  // Verify if valid date
  if ( $flag==1 and VerifyOperationDate($p_cn,$p_user,$e_date)   == null) {
    if ( $view_only == true) 
      return null;
    else 
      $e_date=substr($l_date_start,2,8);
  }
  
  $e_ech=(isset($e_ech))?$e_ech:"";
  $e_comment=(isset($e_comment))?$e_comment:"";
  // Save old value and set a new one
  //  echo_debug(__FILE__,__LINE__,"form_input.php.FormAch p_op_date is $e_date");
  $r="";
  if ( $view_only == false) {
    $r.=JS_SEARCH_CARD;
    $r.=JS_TVA;
    $r.=JS_SHOW_TVA;
 
  }
  $r.="<FORM NAME=\"form_detail\" ACTION=\"user_jrn.php?action=new\" METHOD=\"POST\">";
  $r.='<TABLE>';
  $r.='<TR>'.InputType("Date ","Text","e_date",$e_date,$view_only).'</TR>';
  $r.='<TR>'.InputType("Echeance","Text","e_ech",$e_ech,$view_only).'</TR>';
  $r.='<TR>'.InputType("Description","Text_big","e_comment",$e_comment,$view_only).'</TR>';
  include_once("fiche_inc.php");
  // Display the supplier
  
  // Save old value and set a new one
  $e_client=( isset ($e_client) )?$e_client:"";

  $e_client_label="";  

  // retrieve e_client_label
  if ( isNumber($e_client) == 1 ) {
      if ( isFicheOfJrn($p_cn,$p_jrn,$e_client,'cred') == 0 ) {
	$msg="Fiche inexistante !!! ";
	echo_error($msg); echo_error($msg);	
	echo "<SCRIPT>alert('$msg');</SCRIPT>";
	$e_client="";
	if ( $view_only) return null;
      } else {
	$a_client=GetFicheAttribut($p_cn,$e_client);
	if ( $a_client != null)   
	  $e_client_label=$a_client['vw_name']."  adresse ".$a_client['vw_addr']."  ".$a_client['vw_cp'];
      }
  } else {
    if ( $view_only == true ) {
      $msg="Invalid Customer";
      echo_error($msg); echo_error($msg);	
      echo "<SCRIPT>alert('$msg');</SCRIPT>";
      if ( $view_only) return null;
    }
  }      
  $r.="</TABLE>";
  $r.="<TABLE>";
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
  $r.="<TR>";
  if ($view_only==false)  $r.="<th></th>";
  $r.="<th>code</th>";
  $r.="<th>Dénomination</th>";
  $r.="<th>Prix</th>";
  $r.="<th>Tva</th>";
  $r.="<th>Quantité</th>";

  $r.="</TR>";

  for ($i=0;$i< $p_article;$i++) {

    $march=(isset(${"e_march$i"}))?${"e_march$i"}:"";
    $march_buy=(isset(${"e_march".$i."_buy"}))?${"e_march".$i."_buy"}:"0";
    if ( $view_only== true && $march == "" ) continue;
    if ( isNumber($march_buy) == 0 and $march != "" ) {
      $msg="Montant invalide !!! ";
      echo_error($msg); echo_error($msg);	
      echo "<SCRIPT>alert('$msg');</SCRIPT>";
      $march_buy=0;
      if ( $view_only ) return null;
    }
    $march_tva_label="";
    $march_label="";
    $march_tva_id=(isset(${"e_march$i"."_tva_id"}))?${"e_march$i"."_tva_id"}:"";
    // If $march has a value
    if ( isNumber($march) == 1 ) {
      if ( isFicheOfJrn($p_cn,$p_jrn,$march,'deb') == 0 ) {
      $msg="Fiche inexistante !!! ";
      echo_error($msg); echo_error($msg);	
      echo "<SCRIPT>alert('$msg');</SCRIPT>";
      $march="";
      if ( $view_only ) return null;
      } else {
	if ( isNumber($march_tva_id)== 1) {
	  $a_tva=GetTvaRate($p_cn,$march_tva_id);
	  $march_tva_label=$a_tva['tva_label'];
	}
           // retrieve the tva label and name
	$a_fiche=GetFicheAttribut($p_cn, $march);
	$march_label=$a_fiche['vw_name'];
	if ( $a_fiche != null  and
	     $march_tva_id == "" ) {
	  $march_tva_id=$a_fiche['tva_id'];
	  $march_tva_label=$a_fiche['tva_label'];


	}
	
      
      }//else
    }
    else {
      if ( $view_only ) {
	$msg="Fiche inexistante !!! ";
	echo_error($msg); echo_error($msg);	
	echo "<SCRIPT>alert('$msg');</SCRIPT>";
	return null;
      }
    }
    $r.='<TR>'.InputType("","js_search","e_march".$i,$march,$view_only,'deb');
    $r.=InputType("","span", "e_march".$i."_label", $march_label,$view_only);
    // price
    $r.=InputType("","text","e_march".$i."_buy",$march_buy,$view_only);
    //vat
    $r.=InputType("","span","e_march".$i."_tva_label",$march_tva_label,$view_only);
    // Tva id 
    $r.=InputType("","js_tva","e_march$i"."_tva_id",$march_tva_id,$view_only,"e_march".$i."_tva_label");

    $quant=(isset(${"e_quant$i"}))?${"e_quant$i"}:"1";
    if ( isNumber($quant) == 0) {
      $msg="Montant invalide !!! ";
      echo_error($msg); echo_error($msg);	
      echo "<SCRIPT>alert('$msg');</SCRIPT>";
      $quant=0;
    }
    //quantity
    $r.=InputType("","TEXT","e_quant".$i,$quant,$view_only);

    $r.='</TR>';
  }

  $r.="</TABLE>";
  $r.=$p_submit;
  $r.="</DIV>";
  $r.="</FORM>";
  //if view only show total
  if ( $view_only==true) {
    $total=0;
    $r.="<TABLE>";
    $r.="<th>Nom</th>";
    $r.="<th>Tva</th>";
    $r.="<th>total</th>";
    for ( $i = 0; $i < $p_article;$i++) {
      if ( $view_only == true and ! isset (${"e_march$i"}) ) continue;
      $march=${"e_march$i"};
      if ( isNumber($march) ==1 and
	   isFicheOfJrn($p_cn,$p_jrn,$march,'deb')){
	   $a_fiche=GetFicheAttribut($p_cn, $march);
	   // compute some data
	   //	   $tva=(isNumber($a_fiche['tva_rate']) == 0 )?0:$a_fiche['tva_rate'];
	   if ( isNumber(${"e_march$i"."_tva_id"})  ==1 ) {

		  $a_tva=GetTvaRate($p_cn,${"e_march$i"."_tva_id"});
		  $tva=$a_tva['tva_rate'];
		} else {
		  $tva=(isNumber($a_fiche['tva_rate'])==1)?$a_fiche['tva_rate']:0;
		}
	   $vat_row=${"e_march$i"."_buy"}*${"e_quant$i"}*$tva;
	   $total_row=${"e_march$i"."_buy"}*${"e_quant$i"}+$vat_row;
      
	   $r.="<TR>";
	   $r.="<TD>".$a_fiche['vw_name']."</td>";
	   //	   $r.="<TD>".$a_fiche['tva_label']."</td>";
	   $r.="<TD>  ".$vat_row."</TD>";
	   $r.="<TD>  ".round($total_row,2)."</TD>";
	   $r.="</TR>";
	   $total+=$total_row;
      }
    }// for ($i=0
  
    $r.="<TR> <TD colspan=\"3\" align=\"center\"> Total =".round($total,2)."</TD></TR>";
    $r.="</TABLE>";
  }// if ( $view_only == true )
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
    echo_debug ("Record Achat $v ==> $e");
    ${"$v"}=$e;
  }

  // Get the default period
  $periode=GetUserPeriode($p_cn,$p_user);
  $amount=0.0;
  // Computing total customer
  for ($i=0;$i<$nb_item;$i++) {
     if ( ! isset(${"e_march$i"}) or ${"e_march$i"} == "" or ${"e_quant$i"} == 0) {

       continue;
     }
    // store quantity & goods in array
    if ( isNumber(${"e_march$i"}) == 0 ) continue;
    $a_good[$i]=${"e_march$i"};
    $a_quant[$i]=${"e_quant$i"};
    $a_vat[$i]=${"e_march$i"."_tva_id"};

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

  $a_vat=ComputeVat($p_cn,	$a_good,$a_quant,$a_price,$a_vat);

  $sum_vat=0.0;
  if ( $a_vat != null ) {
    foreach ( $a_vat as $element => $t) {
      echo_debug(__FILE__,__LINE__," a_vat element $element t $t");
      $sum_vat+=$t;
      echo_debug(__FILE__,__LINE__,"sum_vat = $sum_vat");
    }
  }
    // First we add in jrnx
	
  // Compute the j_grpt
  $seq=GetNextId($p_cn,'j_grpt')+1;
  
  
  // Debit = client
  $poste=GetFicheAttribut($p_cn,$e_client,ATTR_DEF_ACCOUNT);
  StartSql($p_cn);
  InsertJrnx($p_cn,'c',$p_user,$p_jrn,$poste,$e_date,$amount+$sum_vat,$seq,$periode);
  
  // Credit = goods 
  for ( $i = 0; $i < $nb_item;$i++) {
    if ( ! isset ( $a_good[$i]) ) continue;
    $poste=GetFicheAttribut($p_cn,$a_good[$i],ATTR_DEF_ACCOUNT);
    if ( $a_price[$i] * $a_quant[$i] == 0 ) continue;    
    $j_id=InsertJrnx($p_cn,'d',$p_user,$p_jrn,$poste,$e_date,$a_price[$i]*$a_quant[$i],$seq,$periode);
    if ( $j_id == false ) {$Rollback($p_cn);exit("error __FILE__ __LINE__");}
    //    if ( withStock($p_cn,$a_good[$i]) == true )  
    // always save quantity but in withStock we can find what card need a stock management
    if ( InsertStockGoods($p_cn,$j_id,$a_good[$i],$a_quant[$i],'d') == false ) {$Rollback($p_cn);exit("error __FILE__ __LINE__");}
  }
  // Insert Vat
  if ( $a_vat  != null ) // no vat
    {
      foreach ($a_vat as $tva_id => $tva_amount ) {
	$poste=GetTvaPoste($p_cn,$tva_id,'d');
	if ( InsertJrnx($p_cn,'d',$p_user,$p_jrn,$poste,$e_date,$tva_amount,$seq,$periode) == false ) { $Rollback($p_cn);exit("error __FILE__ __LINE__");}
      }
    }
  echo_debug(__FILE__,__LINE__,"echeance = $e_ech");
  echo_debug(__FILE__,__LINE__,"comment = $e_comment");
  if ( ($amount+$sum_vat) != 0 ){
    if ( InsertJrn($p_cn,$e_date,$e_ech,$p_jrn,$e_comment,$amount+$sum_vat,$seq,$periode) == false ) {
      $Rollback($p_cn);exit("error __FILE__ __LINE__");
    }
    // Set Internal code and Comment
    $comment=SetInternalCode($p_cn,$seq,$p_jrn)."  client : ".GetFicheName($p_cn,$e_client);
    if ( $e_comment=="" ) {
      // Update comment if comment is blank
      $Res=ExecSql($p_cn,"update jrn set jr_comment='".$comment."' where jr_grpt_id=".$seq);
    }
    Commit($p_cn);
    return $comment;
  }
}

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
function FormFin($p_cn,$p_jrn,$p_user,$p_submit,$p_array=null,$view_only=true,$p_item=4,$p_save=false)
{ 
  include_once("poste.php");
  if ( $p_array != null ) {
    // array contains old value
    foreach ( $p_array as $a=>$v) {
      ${"$a"}=$v;
    }
  }
  // The date
   $userPref=GetUserPeriode($p_cn,$p_user);
  list ($l_date_start,$l_date_end)=GetPeriode($p_cn,$userPref);
  $flag=(isset($e_date))?1:0;
  $e_date=( ! isset($e_date) ) ? substr($l_date_start,2,8):$e_date;

  // Verify if valid date
  if ($flag ==1 and   VerifyOperationDate($p_cn,$p_user,$e_date)   == null) {
    if ( $view_only == true) 
      return null;
    else 
      $e_date=substr($l_date_start,2,8);
  }


  $e_ech=(isset($e_ech))?$e_ech:"";
  $e_comment=(isset($e_comment))?$e_comment:"";

  $r="";
  if ( $view_only == false) {
    $r.=JS_SEARCH_CARD;
    $r.=JS_CONCERNED_OP;
  }

  $r.="<FORM NAME=\"form_detail\" ACTION=\"user_jrn.php?action=new\" METHOD=\"POST\">";
  $r.='<TABLE>';
  $r.='<TR>'.InputType("Date ","Text","e_date",$e_date,$view_only).'</TR>';


  include_once("fiche_inc.php");
  $r.='<INPUT TYPE="HIDDEN" name="nb_item" value="'.$p_item.'">';

  // bank_account operation
  // Save old value and set a new one
  $e_bank_account=( isset ($e_bank_account) )?$e_bank_account:"";
  $e_bank_account_label="";  

    // retrieve e_bank_account_label
  if ( isNumber($e_bank_account) == 1 ) {
    if ( isFicheOfJrn($p_cn,$p_jrn,$e_bank_account,'deb') == 0 ) {
      $msg="Fiche inexistante !!! ";
      echo_error($msg); echo_error($msg);	
      echo "<SCRIPT>alert('$msg');</SCRIPT>";
      $e_bank_account="";
      echo_debug(__FILE__,__LINE__,"FormFin returns NULL the bank account is not valid");
      return null;
    } else {
      $a_client=GetFicheAttribut($p_cn,$e_bank_account);
      if ( $a_client != null)   
	$e_bank_account_label=$a_client['vw_name']."  adresse ".$a_client['vw_addr']."  ".$a_client['vw_cp'];
      }
  }else {
    
    if ( $view_only ==true) {
      return null;
      echo_debug(__FILE__,__LINE__,"FormFin returns NULL the bank account is not valid");
    }
    
  }
  
  $r.='<TR>'.InputType("Banque","js_search","e_bank_account",$e_bank_account,$view_only,FICHE_TYPE_FIN).'</TR>';
  $r.="</TABLE>";
  
  $r.=       InputType(""       ,"span"   ,"e_bank_account_label",$e_bank_account_label,false).'</TD>';
  
  $e_comment=(isset($e_comment))?$e_comment:"";
  
  // ComputeBanqueSaldo
  // cred = nég !!!
  if ( $view_only == true ) {
    $solde=GetSolde($p_cn,GetFicheAttribut($p_cn,$e_bank_account,ATTR_DEF_ACCOUNT));
    $r.=" <b> Solde = ".$solde." </b>";
    $new_solde=$solde;
  }

  // Start the div for item to move money
  $r.="<DIV>";
  $r.='<H2 class="info">Actions</H2>';
  $r.='<TABLE>';
  $r.="<TR>";
  $r.="<th></TH>";
  $r.="<th>code</TH>";
  $r.="<th>Dénomination</TH>";
  $r.="<th>Description</TH>";
  $r.="<th>Montant</TH>";
  $r.='<th colspan="2"> Op. Concernée</th>';
  $r.="</TR>";
  // Parse each " tiers" 
    for ($i=0; $i < $p_item; $i++) {
      $tiers=(isset(${"e_other".$i}))?${"e_other".$i}:"";
      $tiers_label="";
      $tiers_amount=(isset(${"e_other$i"."_amount"}))?${"e_other$i"."_amount"}:0;
      if ( isNumber($tiers_amount) == 0) {
	if ( $view_only==true ){
	  $msg="Montant invalide !!! ";
	  echo_error($msg); echo_error($msg);	
	  echo "<SCRIPT>alert('$msg');</SCRIPT>";
	  return null;
	}
	$tiers_amount=0;
      }
      $tiers_comment=(isset (${"e_other$i"."_comment"}))?${"e_other$i"."_comment"}:"";
    // If $tiers has a value
    if ( isNumber($tiers) == 1 ) {
      if ( isFicheOfJrn($p_cn,$p_jrn,$tiers,'cred') == 0 ) {
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
    $r.='<TR>'.InputType("","js_search","e_other".$i,$tiers,$view_only,'cred');
    $r.=InputType("","span", "e_other$i"."_label", $tiers_label,$view_only);
    // Comment
    $r.=InputType("","Text","e_other$i"."_comment",$tiers_comment,$view_only);
    // amount
    $r.=InputType("","TEXT","e_other$i"."_amount",$tiers_amount,$view_only);
    ${"e_concerned".$i}=(isset(${"e_concerned".$i}))?${"e_concerned".$i}:"";
    $r.=InputType("","js_concerned","e_concerned".$i,${"e_concerned".$i},$view_only);
    $r.='</TR>';
   // if not recorded the new amount must be recalculate
   // if recorded the old amount is recalculated
    if ( $view_only == true)      
      $new_solde=($p_save==false)?$new_solde+$tiers_amount:$new_solde-$tiers_amount;
 }

$r.="</TABLE>";
$r.=$p_submit;
$r.="</DIV>";
$r.="</FORM>";

// if view_only is true
//Put the new saldo here (old saldo - operation)
 if ( $view_only==true)  {
   // if not recorded the new amount must be recalculate
   if ( $p_save == false) {
     $r.=" <b> Ancien Solde = ".$solde." </b><br>";
     $r.=" <b> Nouveau Solde = ".$new_solde." </b><br>";
   }
   // if recorded the old amount is recalculated
   if ($p_save == true ) {
     $r.=" <b> Ancien Solde = ".$new_solde." </b><br>";
     $r.=" <b> Nouveau Solde = ".$solde." </b><br>";
   }
 }
 
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
  echo_debug(__FILE__,__LINE__,"RecordFin");
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
	  echo_debug(__FILE__,__LINE__,"Invalid date $e_date");
	  echo "<SCRIPT> alert('INVALID DATE $e_date !!!!');</SCRIPT>";
	  return null;
		}
  
  // Debit = banque
  $poste_bq=GetFicheAttribut($p_cn,$e_bank_account,ATTR_DEF_ACCOUNT);
  StartSql($p_cn);
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

    // Compute the j_grpt
    $seq=GetNextId($p_cn,'j_grpt')+1;

    if ( InsertJrnx($p_cn,'d',$p_user,$p_jrn,$poste_bq,$e_date,${"e_other$i"."_amount"},$seq,$periode) == false ) {
      $Rollback($p_cn);exit("error __FILE__ __LINE__");
    }


    // Record a line for the other account
    //    $type=( ${"e_other$i"."_amount"} < 0 )?'c':'d';
    if ( ($j_id=InsertJrnx($p_cn,'c',$p_user,$p_jrn,$poste,$e_date,${"e_other$i"."_amount"},$seq,$periode)) == false )
      { $Rollback($p_cn);exit("error __FILE__ __LINE__");}

    echo_debug(__FILE__,__LINE__,"   $j_id=InsertJrnx($p_cn,'d',$p_user,$p_jrn,$poste,$e_date,".${"e_other$i"}."_amount".",$seq,$periode);");

    if ( ($jr_id=InsertJrn($p_cn,$e_date,'',$p_jrn,FormatString(${"e_other$i"."_comment"}),
			   ${"e_other$i"."_amount"},$seq,$periode))==false) {
      $Rollback($p_cn);exit("error __FILE__ __LINE__");}
  
    if ( isNumber(${"e_concerned".$i}) == 1 ) {

      InsertRapt($p_cn,$jr_id,${"e_concerned$i"});
    }


  // Set Internal code and Comment
  $comment=SetInternalCode($p_cn,$seq,$p_jrn)."  client : ".GetFicheName($p_cn,$e_bank_account);
  if ( FormatString(${"e_other$i"."_comment"}) == null ) {
    // Update comment if comment is blank
    $Res=ExecSql($p_cn,"update jrn set jr_comment='".$comment."' where jr_grpt_id=".$seq);
  }
  }
  Commit($p_cn);
}
/* function FormODS($p_cn,$p_jrn,$p_user,$p_array=null,$view_only=true,$p_article=1)
 * Purpose : Display the miscellaneous operation
 *           Used to show detail, encode a new oper
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
 */
function FormODS($p_cn,$p_jrn,$p_user,$p_submit,$p_array=null,$view_only=true,$p_article=6)
{ 
  include_once("poste.php");
  if ( $p_array != null ) {
    // array contains old value
    foreach ( $p_array as $a=>$v) {
      ${"$a"}=$v;
    }
  }
  // The date
   $userPref=GetUserPeriode($p_cn,$p_user);
   list ($l_date_start,$l_date_end)=GetPeriode($p_cn,$userPref);
   $flag=(isset($e_date))?1:0;
   $e_date=( ! isset($e_date) ) ? substr($l_date_start,2,8):$e_date;

  // Verify if valid date
  if (  $flag==1 and VerifyOperationDate($p_cn,$p_user,$e_date)   == null) {
    if ( $view_only == true) 
      return null;
    else 
      $e_date=substr($l_date_start,2,8);
  }

  $e_ech=(isset($e_ech))?$e_ech:"";
  $e_comment=(isset($e_comment))?$e_comment:"";
  // Save old value and set a new one

  $r="";
  if ( $view_only == false) {
    $r.=JS_SEARCH_POSTE;
  }
  $r.="<FORM NAME=\"form_detail\" ACTION=\"user_jrn.php?action=new\" METHOD=\"POST\">";
  $r.='<TABLE>';
  $r.='<TR>'.InputType("Date ","Text","e_date",$e_date,$view_only).'</TR>';
  $r.='<TR>'.InputType("Description","Text_big","e_comment",$e_comment,$view_only).'</TR>';
  include_once("fiche_inc.php");

  // Record the current number of article
  $r.='<INPUT TYPE="HIDDEN" name="nb_item" value="'.$p_article.'">';
  $e_comment=(isset($e_comment))?$e_comment:"";


  // Start the div for item to encode
  $r.="<DIV>";
  $r.='<H2 class="info">Opérations Diverses</H2>';
  $r.='<TABLE border="0">';
  $r.="<tr>";
  $r.="<th></th>";
  $r.="<th>Code</th>";
  $r.="<th>Poste</th>";
  $r.="<th>Montant</th>";
  $r.="<th>Crédit ou débit</th>";
  $r.="</tr>";
  $sum_deb=0.0;
  $sum_cred=0.0;


  for ($i=0;$i< $p_article;$i++) {

    $account=(isset(${"e_account$i"}))?${"e_account$i"}:"";

    $lib="";
    // If $account has a value
    if ( isNumber($account) == 1 ) {
      if ( CountSql($p_cn,"select * from tmp_pcmn where pcm_val=$account") == 0 ) {
	$msg="Poste comptable inexistant !!! ";
	echo_error($msg); echo_error($msg);
	echo "<SCRIPT>alert('$msg');</SCRIPT>";
	$account="";
	if ( $view_only == true ) return null;
      } else {
	// retrieve the tva label and name
	$lib=GetPosteLibelle($p_cn, $account,1);
      }
    }
    ${"e_account$i"."_amount"}=(isset(${"e_account$i"."_amount"}))?${"e_account$i"."_amount"}:0;
    if ( isNumber(${"e_account$i"."_amount"}) == 0 ) {
      if ( $view_only==true) {
	$msg="Montant invalide !!! ";
	echo_error($msg); echo_error($msg);
	echo "<SCRIPT>alert('$msg');</SCRIPT>";
	return null;
      }
	${"e_account$i"."_amount"}=0;
    }
    // code
    // Do we need a filter ?
    $l_line=GetJrnProperty($p_cn,$p_jrn);
    if(  strlen(trim ($l_line['jrn_def_class_cred']) ) > 0 or
	 strlen(trim ($l_line['jrn_def_class_deb']) ) > 0 ) {
      $filter=1;
    }
    else
      $filter=null;

    $r.='<TR>'.InputType("","js_search_poste","e_account".$i,$account,$view_only,$filter);
    //libelle
    $r.="<td> $lib </td>";
    //amount
    $r.=InputType("","text","e_account".$i."_amount",${"e_account$i"."_amount"},$view_only);


    // Type is debit or credit, retrieve the old values
    ${"e_account$i"."_type"}=(isset (${"e_account$i"."_type"}))?${"e_account$i"."_type"}:'d';
    $c_check=( ${"e_account$i"."_type"} == 'c')?"CHECKED":"";
    $d_check=( ${"e_account$i"."_type"} == 'd' )?"CHECKED":"";
    $r.='<td>';
    if ( $view_only == false ) {
      $r.='  <input type="radio" name="'."e_account"."$i"."_type".'" value="d" '.$d_check.'> Débit ou ';
      $r.='  <input type="radio" name="'."e_account"."$i"."_type".'" value="c" '.$c_check.'> Crédit ';
    }else {
      $r.=(${"e_account$i"."_type"} == 'c' )?"Crédit":"Débit";
      $r.='<input type="hidden" name="e_account'.$i.'_type" value="'.${"e_account$i"."_type"}.'">';
    }
    $r.='</td>';
    $r.='</TR>';
    $sum_deb+=(${"e_account$i"."_type"}=='d')?${"e_account$i"."_amount"}:0;
    $sum_cred+=(${"e_account$i"."_type"}=='c')?${"e_account$i"."_amount"}:0;
  }

  $r.="</TABLE>";
  $r.=$p_submit;
  //  $r.="</DIV>";
  $r.="</FORM>";
  //TODO if view only show total
  $tmp= abs($sum_deb-$sum_cred);
  echo_debug(__FILE__,__LINE__,"Diff = ".$tmp);
  if ( abs($sum_deb-$sum_cred) > 0.0001  and $view_only==true) {
    $msg=sprintf("Montant non correspondant credit = %.5f debit = %.5f diff = %.5f",
		 $sum_cred,$sum_deb,$sum_cred-$sum_deb);
    echo "<script> alert('$msg'); </script>";
    return null;
  }
  return $r;


}

/* function RecordODS
 **************************************************
 * Purpose : Record an buy in the table jrn &
 *           jrnx
 *
 * parm :
 *	- $p_cn Database connection
 *  - $p_array contains all the invoice data
 * e_date => e : 01.01.2003
 * nb_item => e : 3
 * e_account0 => e : 6
 * e_account0_amount=>e:1
 *  - $p_user userid
 *  - $p_jrn current folder (journal)
 * gen :
 *	- none
 * return:
 *	      true on success
 */
function RecordODS($p_cn,$p_array,$p_user,$p_jrn)
{
  foreach ( $p_array as $v => $e)
  {
    ${"$v"}=$e;
  }
  // Get the default period
  $periode=GetUserPeriode($p_cn,$p_user);
  $amount=0.0;
  // Computing total customer

  $sum_deb=0.0;
  $sum_cred=0.0;

	// Compute the j_grpt
  $seq=GetNextId($p_cn,'j_grpt')+1;

  StartSql($p_cn);
  // store into the database
  for ( $i = 0; $i < $nb_item;$i++) {
    if ( isNumber(${"e_account$i"}) == 0 ) continue;
    $sum_deb+=(${"e_account$i"."_type"}=='d')?${"e_account$i"."_amount"}:0;
    $sum_cred+=(${"e_account$i"."_type"}=='c')?${"e_account$i"."_amount"}:0;

    if ( ${"e_account$i"."_amount"} == 0 ) continue;
    if ( ($j_id=InsertJrnx($p_cn,${"e_account$i"."_type"},$p_user,$p_jrn,${"e_account$i"},$e_date,${"e_account$i"."_amount"},$seq,$periode)) == false ) {
      $Rollback($p_cn);exit("error __FILE__ __LINE__");}
  }

  if ( InsertJrn($p_cn,$e_date,"",$p_jrn,$e_comment,$sum_deb,$seq,$periode) == false ) {
    $Rollback($p_cn);exit("error __FILE__ __LINE__");}

  // Set Internal code and Comment
  $comment=SetInternalCode($p_cn,$seq,$p_jrn);
  if ( $e_comment=="" ) {
    // Update comment if comment is blank
    $Res=ExecSql($p_cn,"update jrn set jr_comment='".$comment."' where jr_grpt_id=".$seq);
  }
  Commit($p_cn);
  return $comment;
}

