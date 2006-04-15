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
/* $Revision$ */
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
require_once("constant.php");
require_once("class_widget.php");
require_once("preference.php");
require_once("fiche_inc.php");
require_once("user_common.php");
/* function FormAchInput
 * Purpose : Display the form for a sell
 *           Used to show detail, encode a new invoice 
 *           or update one
 *        
 * parm : 
 *	- $p_array which can be empty (normally = $_POST)
 *      - $p_jrn the ledger
 *      - $p_periode = periode
 *      - $pview_only if we cannot change it (no right or centralized op)
 *      - $p_article number of article
 * gen :
 *	-
 * return: string with the form
 */
function FormAchInput($p_cn,$p_jrn,$p_periode,$p_array=null,$p_submit="",$pview_only=true,$p_article=1)
{ 
echo_debug('user_form_ach.php',__LINE__,"Enter FormAchInput($p_cn,$p_jrn,$p_periode,$p_array,$p_submit,$pview_only,$p_article");
  if ( $p_array != null) {
    // array contains old value
    extract($p_array);
  }
  // The date
  list ($l_date_start,$l_date_end)=GetPeriode($p_cn,$p_periode);
  $op_date=( ! isset($e_date) ) ?substr($l_date_start,2,8):$e_date;
  $e_ech=(isset($e_ech))?$e_ech:"";
  $e_comm=(isset($e_comm))?$e_comm:"";

  // Save old value and set a new one
  echo_debug('user_form_ach.php',__LINE__,"form_input.php.FormSell_op_date is $op_date");
  $r="";
  if ( $pview_only == false) {
    $r.=JS_SEARCH_CARD;
    $r.=JS_SHOW_TVA;    
    $r.=JS_TVA;
    $r.="<FORM NAME=\"form_detail\"  enctype=\"multipart/form-data\" ACTION=\"user_jrn.php?action=new&p_jrn=$p_jrn\" METHOD=\"POST\">";
  }

  $sql="select jrn_def_id as value,jrn_def_name as label from jrn_def where jrn_def_type='VEN'";
  $list=GetArray($p_cn,$sql);
  $r.='<TABLE>';
  // Date widget
  //--
  $Date=new widget("text");
  $Date->SetReadOnly($pview_only);
  $Date->table=1;
  $r.="<tr>";
  $r.=$Date->IOValue("e_date",$op_date,"Date");
  $r.="</tr>";
  // Payment limit widget
  //--
  $Echeance=new widget("text");
  $Echeance->SetReadOnly($pview_only);
  $Echeance->table=1;
  $r.="<tr>";
  $r.=$Echeance->IOValue("e_ech",$e_ech,"Echeance");
  $r.="</tr>";
  // Comment
  //--
  $Commentaire=new widget("text");
  $Commentaire->table=1;
  $Commentaire->SetReadOnly($pview_only);
  $Commentaire->size=80;
  $r.="<tr>";
  $r.=$Commentaire->IOValue("e_comm",$e_comm,"Description");
  $r.="</tr>";
  include_once("fiche_inc.php");
  // Display the supplier
  //--
  $fiche='cred';
  echo_debug('user_form_ach.php',__LINE__,"Fournisseurs Nombre d'enregistrement ".sizeof($fiche));
  // Save old value and set a new one
  $e_client=( isset ($e_client) )?$e_client:"";

  $e_client_label="";  

  // retrieve e_client_label
  if ( isNumber($e_client) == 1 ) {
    if ( isFicheOfJrn($p_cn,$p_jrn,$e_client,'cred') == 0 ) {
      $msg="Fiche inexistante !!! ";
      echo_error($msg); echo_error($msg);	
      //      echo "<SCRIPT>alert('$msg');</SCRIPT>";
      $e_client="";
    } else {
      $a_client=GetFicheAttribut($p_cn,$e_client);
      if ( $a_client != null)   
	  $e_client_label=$a_client['vw_name']."  adresse ".$a_client['vw_addr']."  ".$a_client['vw_cp'];
    }
  }
  // widget search
  $W1=new widget("js_search");
  $W1->label="Fournisseur";
  $W1->name="e_client";
  $W1->value=$e_client;
  $W1->extra=$fiche;  // list of card
  $W1->extra2=$p_jrn;
  $r.="<TR>".$W1->IOValue();
  $client_label=new widget("span");
  $r.= $client_label->IOValue("e_client_label",$e_client_label)."</TD></TR>";
  $r.="</TABLE>";

  // Record the current number of article
  $Hid=new widget('hidden');
  $r.=$Hid->IOValue("nb_item",$p_article);
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
  $r.="<th>tva</th>";
  $r.="<th>quantité</th>";
  $r.='</TR>';
  // For each article
  // compute amount
  // verify if card exists
  // retrieve vat label
  //--
  for ($i=0;$i< $p_article;$i++) {
    // Code id
    $march=(isset(${"e_march$i"}))?${"e_march$i"}:"";
    $march_sell=(isset(${"e_march".$i."_sell"}))?${"e_march".$i."_sell"}:"";
    $march_tva_id=(isset(${"e_march$i"."_tva_id"}))?${"e_march$i"."_tva_id"}:"";

    $march_tva_label="";
    $march_label="";

    // If $march has a value
    if ( isFicheOfJrn($p_cn,$p_jrn,$march,'deb') == 0 ) {
	$msg="Fiche inexistante !!! ";
	echo_error($msg); echo_error($msg);	
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
  
    // Show input

    $W1=new widget("js_search");
    $W1->label="";
    $W1->name="e_march".$i;
    $W1->value=$march;
    $W1->extra='deb';  // credits
    $W1->extra2=$p_jrn;
    $W1->readonly=$pview_only;
    $r.="<TR>".$W1->IOValue()."</TD>";
    $Span=new widget ("span");
    $Span->SetReadOnly($pview_only);
    // card's name
    $r.="<TD>".$Span->IOValue("e_march".$i."_label",$march_label)."</TD>";
   // price
    $Price=new widget("text");
    $Price->SetReadOnly($pview_only);
    $Price->table=1;
    $r.=$Price->IOValue("e_march".$i."_sell",$march_sell);
    // vat label
    $select_tva=make_array($p_cn,"select tva_id,tva_label from tva_rate order by tva_id",1);
    $Tva=new widget("select");
    $Tva->table=1;
    $Tva->selected=$march_tva_id;
    $r.=$Tva->IOValue("e_march$i"."_tva_id",$select_tva);

    // quantity
    $quant=(isset(${"e_quant$i"}))?${"e_quant$i"}:"1";
    $Quantity=new widget("text");
    $Quantity->SetReadOnly($pview_only);
    $Quantity->table=1;
    $r.=$Quantity->IOValue("e_quant".$i,$quant);
    $r.='</TR>';
  }



  $r.="</TABLE>";
  $r.="<hr>";

  if ($pview_only == false ) {
    $r.=$p_submit;
    $r.="</DIV>";
    $r.="</FORM>";
  } else {
     $r.="</div>";

  }



  return $r;


}
/* function form_verify_input
 **************************************************
 * Purpose : verify if the data to insert are valid
 *        
 * parm : 
 *	- p_cn database connection
 *      - p_jrn concerned ledger
 *      - User periode
 *      - array with the post data
 *      - p_number number of items
 * gen :
 *	-
 * return:
 */
function form_verify_input($p_cn,$p_jrn,$p_periode,$p_array,$p_number)
{
  echo_debug('user_form_ach.php',__LINE__,"Enter form_verify_input $p_cn,$p_jrn,$p_periode,".var_export($p_array,true).",$p_number");
  foreach ($p_array as $name=>$content) {
    ${"$name"}=$content;
  }
  // Verify the date
  if ( isDate($e_date) == null ) 
    { 
      echo_error("Invalid date $e_date");
      echo_debug('user_form_ach.php',__LINE__,"Invalid date $e_date");
      echo "<SCRIPT> alert('INVALID DATE $e_date !!!!');</SCRIPT>";
      return null;
    }
// Verify the quantity
  for ($o = 0;$o < $p_number; $o++) 
    {
      if ( isNumber(${"e_quant$o"}) == 0 ) 
	{
	  echo_debug('user_form_ach.php',__LINE__,"invalid quantity ".${"e_quant$o"});
	  echo_error("invalid quantity ".${"e_quant$o"});
	  echo "<SCRIPT> alert('INVALID QUANTITY !!!');</SCRIPT>";
	  return null;
	}	
    // check if vat is correct
    if ( strlen(trim(${"e_march$o"."_tva_id"})) !=0 
	 and 
	 ${"e_march$o"."_tva_id"} != "-1")
      {
      // vat is given we check it now check if valid
      if (isNumber(${"e_march$o"."_tva_id"}) == 0
	       or CountSql($p_cn,"select tva_id from tva_rate where tva_id=".${"e_march$o"."_tva_id"}) ==0)
	{
	  $msg="Invalid TVA !!! e_march".$o."_tva_id = ".${"e_march".$o."_tva_id"};
	  echo_error($msg); echo_error($msg);	
	  echo "<SCRIPT>alert('$msg');</SCRIPT>";
	  return null;
	
	}
      }
    
    }

  // Verify the ech
 if (strlen($e_ech) != 0 and isNumber($e_ech)  == 0 and  isDate ($e_ech) == null ) 
   {
     $msg="Echeance invalide";
     echo_error($msg); echo_error($msg);	
     echo "<SCRIPT>alert('$msg');</SCRIPT>";
     return null;
   } 
 // Verify is a client is set
//  if ( isNumber($e_client)    == 0) 
//    {
//      $msg="Client inexistant";
//      echo_error($msg); echo_error($msg);	
//      echo "<SCRIPT>alert('$msg');</SCRIPT>";
//      return null;
//    }

 // if ech is a number of days then compute date limit
 if ( strlen($e_ech) != 0 and isNumber($e_ech) == 1) 
   {
     list($day,$month,$year)=explode(".",$e_date);
     echo_debug('user_form_ach.php',__LINE__," date $e_date = $day.$month.$year");
     $p_ech=date('d.m.Y',mktime(0,0,0,$month,$day+$e_ech,$year));
     echo_debug('user_form_ach.php',__LINE__,"p_ech = $e_ech $p_ech");
     $e_ech=$p_ech;
     $wHidden=new widget("hidden");
     $data.=$wHidden->IOValue("e_ech",$e_ech);
# $data.=InputType("","HIDDEN","e_ech",$e_ech);
   }

 // Check if the fiche is in the jrn
 if (IsFicheOfJrn($p_cn , $p_jrn, $e_client,'cred') == 0 ) 
   {
     $msg="Client invalid please recheck";
     echo_error($msg);
     echo "<SCRIPT>alert('$msg');</SCRIPT>";
     return null;
   }

 // check if all e_march are in fiche
  for ($i=0;$i<$p_number;$i++) 
    {
      if ( trim(${"e_march$i"})  == "" ) {
	// no goods to sell 
	continue;
    }
  
//     // Check wether the f_id is a number
//     if ( isNumber(${"e_march$i"}) == 0 ) {
//       $msg="Fiche inexistante !!! ";
//       echo_error($msg); echo_error($msg);	
//       echo "<SCRIPT>alert('$msg');</SCRIPT>";
//       return null;
//     }
    // Check 
    if ( isFicheOfJrn($p_cn,$p_jrn,${"e_march$i"},'deb') == 0 ) {
      $msg="Fiche inexistante !!! ";
      echo_error($msg); echo_error($msg);	
      echo "<SCRIPT>alert('$msg');</SCRIPT>";
      return null;
    }
    // check if the  ATTR_DEF_ACCOUNT is set
    $poste=GetFicheAttribut($p_cn,${"e_march$i"},ATTR_DEF_ACCOUNT);
    echo_debug('user_form_ach.php',__LINE__,"poste value = ".$poste."size = ".strlen(trim($poste)));
    if ( $poste == null ) 
      {	
	$msg="La fiche ".${"e_march$i"}." n\'a pas de poste comptable";
	echo_error($msg); echo_debug('user_form_ach.php',__LINE__,$msg);	
	echo "<SCRIPT>alert('$msg');</SCRIPT>";
	return null;
	
      }
    if ( strlen(trim($poste))==0 )
      {
	$msg="La fiche ".${"e_march$i"}." n\'a pas de poste comptable";
	echo_error($msg); echo_debug('user_form_ach.php',__LINE__,$msg);		
	echo "<SCRIPT>alert('$msg');</SCRIPT>";
	return null;
      }
    
    // Check if the percentage indicated in this field is valid
    $non_dedu=GetFicheAttribut($p_cn,${"e_march$i"},ATTR_DEF_DEPENSE_NON_DEDUCTIBLE);
    if ( $non_dedu != null && strlen(trim($non_dedu)) != 0 )
      {
	if ( isNumber($non_dedu) == 0 || $non_dedu > 1.00 ) 
	  {
	    $msg="La fiche ".${"e_march$i"}." a un pourcentage invalide,il doit être compris entre 0 et 1";
			echo_error($msg); echo_debug('user_form_ach.php',__LINE__,$msg);	
			echo "<SCRIPT>alert('$msg');</SCRIPT>";
			return null;
		
		}
	}
    // Check if the percentage indicated in this field is valid
    $non_dedu=GetFicheAttribut($p_cn,${"e_march$i"},ATTR_DEF_TVA_NON_DEDUCTIBLE);
    if ( $non_dedu != null && strlen(trim($non_dedu)) != 0 )
      {
	if ( isNumber($non_dedu) == 0 || $non_dedu > 1.00 ) 
	  {
	    $msg="La fiche ".${"e_march$i"}." a un pourcentage invalide, il doit être compris entre 0 et 1";
	    echo_error($msg); echo_debug('user_form_ach.php',__LINE__,$msg);	
	    echo "<SCRIPT>alert('$msg');</SCRIPT>";
	    return null;
	    
	  }
      }	// Check if the percentage indicated in this field is valid
    $non_dedu=GetFicheAttribut($p_cn,${"e_march$i"},ATTR_DEF_TVA_NON_DEDUCTIBLE_RECUP);
    if ( $non_dedu != null && strlen(trim($non_dedu)) != 0 )
	{
	  if ( isNumber($non_dedu) == 0 || $non_dedu > 1.00 ) 
	    {
	      $msg="La fiche ".${"e_march$i"}." a un pourcentage invalide, il doit être compris entre 0 et 1";
	      echo_error($msg); echo_debug('user_form_ach.php',__LINE__,$msg);	
	      echo "<SCRIPT>alert('$msg');</SCRIPT>";
	      return null;
	      
	    }
	}
    
    }
// Verify the userperiode

// p_periode contient la periode par default
  list ($l_date_start,$l_date_end)=GetPeriode($p_cn,$p_periode);
  
  // Date dans la periode active
  echo_debug ("date start periode $l_date_start date fin periode $l_date_end date demande $e_date");
  if ( cmpDate($e_date,$l_date_start)<0 || 
       cmpDate($e_date,$l_date_end)>0 )
    {
      $msg="Not in the active periode please change your preference";
      echo_error($msg); echo_error($msg);	
      echo "<SCRIPT>alert('$msg');</SCRIPT>";
      return null;
    }
    // Periode fermé
    if ( PeriodeClosed ($p_cn,$p_periode)=='t' )
      {
		$msg="This periode is closed please change your preference";
		echo_error($msg); echo_error($msg);	
		echo "<SCRIPT>alert('$msg');</SCRIPT>";
		return null;
      }
    return true;
}
/* function FormAchView ($p_cn,$p_jrn,$p_periode,$p_array,$p_number,$p_doc='html',$p_comment='') 
 **************************************************
 * Purpose : Show the invoice before inserting it 
 *           the database
 *        
 * parm : 
 *	- p_cn database connection
 *      - p_jrn journal
 *      - p_periode
 *      - array of value
 *      - nb of item
 *      - p_doc type pdf or html
 * gen :
 *	- none
 * return:
 *     - string
 * 
 */

function FormAchView ($p_cn,$p_jrn,$p_periode,$p_array,$p_submit,$p_number,$p_piece=true)
{
  $r="";
  $data="";
  // Keep all the data if hidden
  // and store the array in variables
  $hidden=new widget("hidden");
  foreach ($p_array as $name=>$content) {
    $data.=$hidden->IOValue($name,$content);
    ${"$name"}=$content;
  }
  
  // start table
  $r.='<TABLE>';
  // Show the Date
  $r.="<tr>";
  $r.="<TD>Date : $e_date</TD>";
  $r.="</tr>";
  // Show the customer Name
  $r.="<tr>";
  $r.="<TD>Client : ".getFicheName($p_cn,$e_client)."</TD>";
  $r.="</tr>";
  
  // show date limit
  $r.="<tr>";
  $r.="<TD> Echeance : $e_ech </TD>";
  $r.="</tr>";
  // Show desc
  $r.="<tr>";
  $r.="<TD> Description : $e_comm</TD>";
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
  for ($i=0;$i<$p_number;$i++) 
    {
      if ( trim(${"e_march$i"})  == "" ) 
	{
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
      if ( $a_vat == null ) 
	{
	  $vat_label="";
	  $vat_rate=0.0;
	} 
      else 
	{ 
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

  $r.='<FORM METHOD="POST" enctype="multipart/form-data" ACTION="user_jrn.php?action=new&p_jrn='.$p_jrn.'">';
  // check for upload piece
  $file=new widget("file");
  $file->table=1;
  $r.="<hr>";
  $r.= "<table>"; 
  if ( $p_piece) $r.="<TR>".$file->IOValue("pj","","Pièce justificative")."</TR>";
  $r.="</table>";
  $r.="<hr>";
  
  $r.=$data;
  $r.=$p_submit;
  
  $r.='</FORM>';
  
  return $r;
  
}

/* function RecordSell
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
 *  - $p_periode periode
 *  - $p_jrn current folder (journal)
 * gen :
 *	- none
 * return:
 *	      true on success
 */
function RecordSell($p_cn,$p_array,$p_user,$p_jrn)
{
  echo_debug('user_form_ach',__LINE__,"function RecordSell($p_cn,$p_array,$p_user,$p_jrn)");
  foreach ( $p_array as $v => $e)
  {
    ${"$v"}=$e;
  }

  // Get the default period
  $periode=$p_user->GetPeriode();
  $amount=0.0;
  $amount_jrn=0.0;
  $sum_tva_nd=0.0;
  // Computing total customer
  for ($i=0;$i<$nb_item;$i++) {
    // store quantity & goods in array
    $a_good[$i]=${"e_march$i"};
    $a_quant[$i]=${"e_quant$i"};
    $a_price[$i]=0;
    $a_vat1[$i]=${"e_march$i"."_tva_id"};
    // check wether the price is set or no
    if ( isNumber(${"e_march$i"."_sell"}) == 0 ) {
      if ( $a_good[$i] !="" ) {
	     // If the price is not set we have to find it from the database
	     $a_price[$i]=GetFicheAttribut($p_cn,$a_good[$i],ATTR_DEF_PRIX_VENTE);
	   } 
    } else {
      // The price is valid
      $a_price[$i]=${"e_march$i"."_sell"};
    }
    $cost=$a_price[$i]*$a_quant[$i];
    $amount+=$cost;
    // if cost  < 0 => not added to jrn.jr_amount
    $amount_jrn+=($cost<0)?0:$cost;
  }
  $comm=FormatString($e_comm);
echo_debug('user_form_achat.php',__LINE__,"Call ComputeTotalVat");
  $a_vat=ComputeTotalVat($p_cn,$a_good,$a_quant,$a_price,$a_vat1,false);
  $sum_vat=0.0;
  if ( $a_vat != null ){
    foreach ( $a_vat as $element => $t) {
      echo_debug('user_form_ach.php',__LINE__," a_vat element $element t $t");
      $sum_vat+=$t;
      echo_debug('user_form_ach.php',__LINE__,"sum_vat = $sum_vat");
    }
  }
// Compute vat without reduction
	$a_vat_full=ComputeTotalVat($p_cn,$a_good,$a_quant,$a_price,$a_vat1,true);
	$sum_vat_full=0.0;
	if ( $a_vat_full != null ){
		foreach ( $a_vat_full as $element => $t) {
		echo_debug('user_form_ach.php',__LINE__," a_vat element $element t $t");
		$sum_vat_full+=$t;
		echo_debug('user_form_ach.php',__LINE__,"sum_vat = $sum_vat");
		}
	}
  // First we add in jrnx
	
  // Compute the j_grpt
  $seq=NextSequence($p_cn,'s_grpt');


  // Debit = client
  $poste=GetFicheAttribut($p_cn,$e_client,ATTR_DEF_ACCOUNT);
  echo_debug('user_form_achat.php',__LINE__,"get e_client $e_client poste $poste");
  StartSql($p_cn);	
  echo_debug('user_form_achat.php',__LINE__,"insert client");
  $r=InsertJrnx($p_cn,'c',$p_user->id,$p_jrn,$poste,$e_date,round($amount,2)+round($sum_vat_full,2),$seq,$periode);
  if ( $r == false) { $Rollback($p_cn);exit("error 'user_form_ach.php' __LINE__");}
  // Credit = goods 
  for ( $i = 0; $i < $nb_item;$i++) {
    //    if ( isNumber($a_good[$i]) == 0 ) continue;
    $poste=GetFicheAttribut($p_cn,$a_good[$i],ATTR_DEF_ACCOUNT);
	  
    // don't record operation of 0
    if ( $a_price[$i]*$a_quant[$i] == 0 ) continue;

	$amount=$a_price[$i]*$a_quant[$i];
	// Put the non deductible part into a special account
	$non_dedu=GetFicheAttribut($p_cn,$a_good[$i],ATTR_DEF_DEPENSE_NON_DEDUCTIBLE);
	echo_debug('user_form_ach.php',__LINE__,"value non ded : $non_dedu");
	if ( $non_dedu != null && strlen(trim($non_dedu)) != 0 )
	{
		$nd_amount=$a_quant[$i]*$a_price[$i]*$non_dedu;
		$j_id=InsertJrnx($p_cn,'d',$p_user->id,$p_jrn,'6740',$e_date,round($nd_amount,2),$seq,$periode);
		if ( $j_id == false) { Rollback($p_cn);exit("error 'user_form_ach.php' __LINE__");}
		$amount=$amount-$nd_amount;
	}
  	// Put the non deductible part into a special account
	$non_dedu=GetFicheAttribut($p_cn,$a_good[$i],ATTR_DEF_TVA_NON_DEDUCTIBLE);
	echo_debug('user_form_ach.php',__LINE__,"TVA value non ded : $non_dedu");
	if ( $non_dedu != null && strlen(trim($non_dedu)) != 0 )
	{
		$ded_vat=ComputeVat($p_cn,	$a_good[$i],$a_quant[$i],$a_price[$i],
				$a_vat1[$i] )*$non_dedu;
		$sum_tva_nd+=$ded_vat;
		$j_id=InsertJrnx($p_cn,'d',$p_user->id,$p_jrn,'6740',$e_date,round($ded_vat,2),$seq,$periode);
		if ( $j_id == false) { Rollback($p_cn);exit("error 'user_form_ach.php' __LINE__");}
	}

  	// Put the non deductible part into a special account
	$non_dedu=GetFicheAttribut($p_cn,$a_good[$i],ATTR_DEF_TVA_NON_DEDUCTIBLE_RECUP);
	echo_debug('user_form_ach.php',__LINE__,"TVA value non ded : $non_dedu");
	if ( $non_dedu != null && strlen(trim($non_dedu)) != 0 )
	{
		$ded_vat=ComputeVat($p_cn,	$a_good[$i],$a_quant[$i],$a_price[$i],
				$a_vat1[$i] )*$non_dedu;
		$sum_tva_nd+=$ded_vat;
		$j_id=InsertJrnx($p_cn,'d',$p_user->id,$p_jrn,'6190',$e_date,round($ded_vat,2),$seq,$periode);
		if ( $j_id == false) { Rollback($p_cn);exit("error 'user_form_ach.php' __LINE__");}
	}




    // record into jrnx
    $j_id=InsertJrnx($p_cn,'d',$p_user->id,$p_jrn,$poste,$e_date,round($amount,2),$seq,$periode);
    if ( $j_id == false) { Rollback($p_cn);exit("error 'user_form_ach.php' __LINE__");}
    // always save quantity but in withStock we can find what card need a stock management
    if (  InsertStockGoods($p_cn,$j_id,$a_good[$i],$a_quant[$i],'c') == false ) {
      $Rollback($p_cn);exit("error 'user_form_ach.php' __LINE__");}
	echo_debug('user_form_ach.php',__LINE__,"value non ded : ".$a_good[$i]."is");		

  }

  
  // Insert Vat

  if ( $a_vat  !=  null  ) // no vat

    {
      foreach ($a_vat as $tva_id => $tva_amount ) 
	{
	  $poste=GetTvaPoste($p_cn,$tva_id,'d');
	  if ($tva_amount == 0 ) continue;
	  $r=InsertJrnx($p_cn,'d',$p_user->id,$p_jrn,$poste,$e_date,round($tva_amount,2),$seq,$periode);
	  if ( $r == false ) { Rollback($p_cn); exit(" Error 'user_form_ach.php' __LINE__");}
      
	}
    }
  echo_debug('user_form_ach.php',__LINE__,"echeance = $e_ech");
  $r=InsertJrn($p_cn,$e_date,$e_ech,$p_jrn,"Invoice",round($amount_jrn,2)+round($sum_vat,2)+round($sum_tva_nd,2),$seq,$periode);
  if ( $r == false ) { Rollback($p_cn); exit(" Error 'user_form_ach.php' __LINE__");}
  // Set Internal code and Comment
  $internal=SetInternalCode($p_cn,$seq,$p_jrn);
  $comment=(FormatString($e_comm) == null )?$internal."  client : ".GetFicheName($p_cn,$e_client):FormatString($e_comm);

  // Update and set the invoice's comment 
  $Res=ExecSql($p_cn,"update jrn set jr_comment='".$comment."' where jr_grpt_id=".$seq);
  if ( $Res == false ) { Rollback($p_cn); exit(" Error 'user_form_ach.php' __LINE__"); };

  if ( isset ($_FILES))
    save_upload_document($p_cn,$seq);


  Commit($p_cn);

  return $comment;
}

?>
