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
/* $Revision$ */
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/*! \file
 * \brief Functions for the ledger of expenses
 */

require_once("constant.php");
require_once("class_widget.php");
require_once("preference.php");
require_once("fiche_inc.php");
require_once("user_common.php");
require_once("class_parm_code.php");
require_once ('class_plananalytic.php');
require_once ('class_own.php');
require_once ('class_operation.php');
require_once ('class_pre_op_ach.php');

/*! 
 * \brief  Display the form for a sell
 *           Used to show detail, encode a new invoice 
 *           or update one
 *        
 *  
 *\param $p_array which can be empty (normally = $_POST)
 *        containing :
 *        <ul>
 *        <li> e_client (quickcode),
 *        <li> e_marchX quickcode,
 *        <li> e_march_buyX,
 *        <li> e_march0_tva_id,
 *        <li> e_quant0,nb_item,
 *        <li> jrn_type,
 *        <li> e_date,
 *        <li> e_ech,
 *        <li> e_comm
 *        </ul>
 *\param $p_jrn the ledger
 *\param $p_periode = periode
 *\param $pview_only if we cannot change it (no right or centralized op)
 *\param $p_article number of article
 *
 * \return: string with the form
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
  //  $op_date=( ! isset($e_date) )
  //  ?substr($l_date_start,2,8):$e_date;
  $op_date=( ! isset($e_date) ) ?$l_date_start:$e_date;
  $e_ech=(isset($e_ech))?$e_ech:"";
  $e_comm=(isset($e_comm))?$e_comm:"";

  // Save old value and set a new one
  echo_debug('user_form_ach.php',__LINE__,"form_input.php.FormSell_op_date is $op_date");
  $r="";
  if ( $pview_only == false) {
    $r.=JS_SEARCH_CARD;
    $r.=JS_SHOW_TVA;    
    $r.=JS_TVA;
    // Compute href
    $href=basename($_SERVER['PHP_SELF']);
    switch ($href)
      {
	// user_jrn.php
      case 'user_jrn.php':
	$href="user_jrn.php?action=new&p_jrn=$p_jrn";
	break;
      case 'commercial.php':
	$href="commercial.php?p_action=depense&p_jrn=$p_jrn";
	break;
      default:
	echo_error('user_form_ach.php',__LINE__,'Erreur invalid request uri '.$href);
	exit (-1);
      }
    $r.="<FORM NAME=\"form_detail\"  enctype=\"multipart/form-data\" ACTION=\"$href\" METHOD=\"POST\">";
	$r.=dossier::hidden();
  }

  $r.='<TABLE>';
  // Date widget
  //--
  $Date=new widget("js_date");
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
  $a_client=GetFicheAttribut($p_cn,$e_client);
  if ( $a_client != null)   
    $e_client_label=$a_client['vw_name']."  adresse ".$a_client['vw_addr']."  ".$a_client['vw_cp'];

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
  // Set correctly the REQUEST param for jrn_type 
  $h=new widget('hidden');
  $h->name='jrn_type';
  $h->value='ACH';
  $r.=$h->IOValue();

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
  $r.="<th>Montant TVA</th>";
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
    $march_buy=(isset(${"e_march".$i."_buy"}))?${"e_march".$i."_buy"}:"";
    $march_tva_id=(isset(${"e_march$i"."_tva_id"}))?${"e_march$i"."_tva_id"}:"";
    $march_tva_amount=(isset(${"e_march$i"."_tva_amount"}))?${"e_march$i"."_tva_amount"}:"0";
    $march_tva_label="";
    $march_label="";

    // If $march has a value
    if ( strlen(trim($march)) != 0 &&  isFicheOfJrn($p_cn,$p_jrn,$march,'deb') == 0 ) {
	$msg="user_form_achat@".__LINE__."Fiche inexistante !!! ";
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
    $Price->size=9;
    $r.=$Price->IOValue("e_march".$i."_buy",$march_buy);
    // vat label
    $select_tva=make_array($p_cn,"select tva_id,tva_label from tva_rate order by tva_id",1);
    $Tva=new widget("select");
    $Tva->table=1;
    $Tva->selected=$march_tva_id;
    $r.=$Tva->IOValue("e_march$i"."_tva_id",$select_tva);
   // tva_amount
    $Tva_amount=new widget("text");
    $Tva_amount->SetReadOnly($pview_only);
    $Tva_amount->table=1;
    $Tva_amount->size=9;
    $r.=$Tva_amount->IOValue("e_march".$i."_tva_amount",$march_tva_amount);

    // quantity
    $quant=(isset(${"e_quant$i"}))?${"e_quant$i"}:"1";
    $Quantity=new widget("text");
    $Quantity->SetReadOnly($pview_only);
    $Quantity->table=1;
    $Quantity->size=9;
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
/*! 
 **************************************************
 * \brief  verify if the data to insert are valid
 *        
 * \param $p_cn database connection
 *\param $p_jrn concerned ledger
 *\param $p_periode User periode
 *\param $p_array array with the post data
 *\param $p_number number of items
 *
 * \return null if error
 */
function form_verify_input($p_cn,$p_jrn,$p_periode,$p_array,$p_number)
{
  echo_debug('user_form_ach.php',__LINE__,"Enter form_verify_input $p_cn,$p_jrn,$p_periode,".var_export($p_array,true).",$p_number");
  foreach ($p_array as $name=>$content) {
    ${"$name"}=$content;
  }
  // Verify the amount for each 
  //
  // Check for CA
  $own = new Own($p_cn);


  // Verify the date
  if ( isDate($e_date) == null ) 
    { 
      echo_error("Invalid date $e_date");
      echo_debug('user_form_ach.php',__LINE__,"Invalid date $e_date");
      echo "<SCRIPT> alert('INVALID DATE $e_date !!!!');</SCRIPT>";
      return null;
    }
  $tot=0;
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
    // if tva_amount is not a number than reset to 0
    if ( strlen(trim(${"e_march".$o."_tva_amount"})) !=0 &&
	 isNumber (${"e_march".$o."_tva_amount"}) == 0)
      {
	${"e_march".$o."_tva_amount"}=0;
      }
    // if amount is not empty and is not a number
    if ( strlen(trim(${"e_march".$o."_buy"})) !=0 && isNumber(${"e_march".$o."_buy"}) == 0 )
      {
	  echo_debug('user_form_ach.php',__LINE__,"Prix invalide ".${"e_march$o"});
	  echo_error("Prix n'est  pas un montant valide ".${"e_march$o"});
	  echo "<SCRIPT> alert('Prix ".${"e_march".$o."_buy"}." de la fiche ".${"e_march$o"}." n\'est pas un montant valide !!!');</SCRIPT>";
	  return null;
	
      }
	if ( $own->MY_ANALYTIC!='nu') // use of AA
	  {
		if ( isset (${"amount_t".$o})){
		  $hidden_amount=${"amount_t".$o};
		  $ca_amount=0;
		  // first we get the number of row for each item
		  for ($line=1;$line <=${"nb_t".$o};$line++) {
			$ca_amount+=${"val".$o."l".$line};
		  }
		  
		  // compare hidden value and computed
		  if ( round($ca_amount-$hidden_amount,2) != 0 ) {
			
		    $msg="Montant CA est différent total marchandise";
		    
		    echo "<SCRIPT>alert('$msg');</SCRIPT>";
			
		    return null;
			
		  }
		
		}
	  }

    $tot+=${"e_march".$o."_buy"}*${"e_quant$o"};
    }

  // if total amount == 0 we don't go further
  if ( $tot == 0 )
    return null;

  // Verify the ech
 if (strlen($e_ech) != 0 and isNumber($e_ech)  == 0 and  isDate ($e_ech) == null ) 
   {
     $msg="Echeance invalide";
     echo_error($msg); echo_error($msg);	
     echo "<SCRIPT>alert('$msg');</SCRIPT>";
     return null;
   } 

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

   }

 // Check if the fiche is in the jrn
 if (IsFicheOfJrn($p_cn , $p_jrn, $e_client,'cred') == 0 ) 
   {
     $msg="Client invalid please recheck";
     echo_error($msg);
     echo "<SCRIPT>alert('$msg');</SCRIPT>";
     return null;
   }
 // Check if the customer card has a valid account
 if ( CheckPoste($p_cn,$e_client) == null )
   return null;

 // check if all e_march are in fiche
  for ($i=0;$i<$p_number;$i++) 
    {
       if ( trim(${"e_march$i"})  == "" ) {
	// no goods to sell 
	continue;
    }
  
    // Check 
    if ( isFicheOfJrn($p_cn,$p_jrn,${"e_march$i"},'deb') == 0 ) {
      $msg="Fiche inexistante !!! ";
      echo_error(__FILE__.__LINE__.$msg); 
      echo "<SCRIPT>alert('$msg');</SCRIPT>";
      return null;
    }
    if ( CheckPoste($p_cn,${"e_march".$i}) == null )
      return null;

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
  echo_debug ('user_form_ach',__LINE__,"date start periode $l_date_start date fin periode $l_date_end date demande $e_date");
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
/*! 
 **************************************************
 * \brief  Show the invoice before inserting it 
 *           the database
 *        
 * \param $p_cn database connection
 * \param p_jrn journal
 * \param $p_periode
 * \param $p_array array of value
 * \param $p_number nb of item
 *\param $p_piece == true we can upload a doc.
 * 
 */

function FormAchView ($p_cn,$p_jrn,$p_periode,$p_array,$p_submit,$p_number,$p_piece=true)
{
  echo_debug(__FILE__.':'.__LINE__.'- FormAchView');
  $r="";
  $data="";
  $own=new own($p_cn);
  // Keep all the data if hidden
  // and store the array in variables
  $hidden=new widget("hidden");
  foreach ($p_array as $name=>$content) {
    // not the CA data
    if ( strpos( $name,"ta_")===false && 
	 strpos( $name,"nb_t")===false &&
	 strpos( $name,"val")===false )
      $data.=$hidden->IOValue($name,$content);

    ${"$name"}=$content;
  }
  // Compute href
  //  $href=basename($_SERVER['PHP_SELF']);
  $href=basename($_SERVER['PHP_SELF']);
  switch ($href)
    {
      // user_jrn.php
    case 'user_jrn.php':
      $href="user_jrn.php?action=new&p_jrn=$p_jrn";
      break;
    case 'commercial.php':
      $href="commercial.php?p_action=depense&p_jrn=$p_jrn";
      break;
    default:
      echo_error('user_form_ach.php',__LINE__,'Erreur invalid request uri');
      exit (-1);
    }

  $r.='<FORM METHOD="POST" enctype="multipart/form-data" ACTION="'.$href.'">'; 
  $r.=dossier::hidden();
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
      if ( isNumber(${"e_march$i"."_buy"}) == 0 ) 
		{
		  $fiche_price=getFicheAttribut($p_cn,${"e_march$i"},ATTR_DEF_PRIX_VENTE);
		} else 
		{
		  $fiche_price=${"e_march$i"."_buy"};
		}
      // round it
      $fiche_price=round($fiche_price,2);
	  
      // get TVA Amount
      $tva_amount=round(${"e_march".$i."_tva_amount"},2);

      // VAT 
      $vat=(isNumber(${"e_march$i"."_tva_id"})==0 || ${"e_march$i"."_tva_id"}==-1 )?getFicheAttribut($p_cn,${"e_march$i"},ATTR_DEF_TVA):${"e_march$i"."_tva_id"};
      
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
      if ( $tva_amount == 0) 
		{
		  $fiche_amount_vat=round($fiche_price*$fiche_quant*$vat_rate,2);
		  echo_debug(__FILE__.':'.__LINE__.'- Tva Amount is computed '.$fiche_amount_vat);
		  
		  // value card + vat
		  $fiche_with_vat=round($fiche_price*$fiche_quant,2)+$fiche_amount_vat;
		}
      else
		{
		  echo_debug(__FILE__.':'.__LINE__.'- Tva Amount is given '.$tva_amount);
		  $fiche_amount_vat=$tva_amount;
		  // value card + vat
		  $fiche_with_vat=round($fiche_price*$fiche_quant,2)+$tva_amount;
		}
      // Sum of invoice vat 
      $sum_with_vat+=$fiche_with_vat;
	  echo_debug(__FILE__.':'.__LINE__.'- Sum_with_vat='.$fiche_with_vat);
      // Show the data
      $r.='<TR>';
      $r.='<TD>'.$fiche_name.'</TD>';
      $r.='<TD ALIGN="CENTER">'.$fiche_quant.'</TD>';
      $r.='<TD ALIGN="right">'.$fiche_price.'</TD>';
      $r.="<TD  ALIGN=\"RIGHT\"> $vat_label </TD>";
      $r.='<TD  ALIGN="RIGHT">'.round($fiche_sum,2).'</TD>';
      $r.='<TD ALIGN="RIGHT">'.round($fiche_amount_vat,2).'</TD>';
      
      $r.='<TD>'.round($fiche_with_vat,2).'</TD>';
	  //----------------------------------------------------------------------
	  // CA
	  //----------------------------------------------------------------------
	  // to show a select list for the analytic
	  // if analytic is op (optionnel) there is a blank line

	  // encode the pa

	  if (  $own->MY_ANALYTIC!='nu') // use of AA
	    {
	      // show form
	      $op=new operation($p_cn);
	      $null=($own->MY_ANALYTIC=='op')?1:0;
	      $p_mode=($p_piece)?1:0;
	      $r.='<td>';
	      $r.=$op->display_form_plan($_POST,$null,$p_mode,$i,round($fiche_sum,2));
	      $r.='</td>';

	    }
	  
	  //----------------------------------------------------------------------
      $r.="</TR>";
    }
  
  // end table
  $r.='</TABLE> ';
  $r.='<DIV style="padding:30px;font-size:14px">';
  $r.="Total HTVA =".round( $sum_march,2)." <br>";
  $r.="Total = ".round($sum_with_vat,2);

 
  $r.="</DIV>";
 
  // check for upload piece
  // Set correctly the REQUEST param for jrn_type 
  $h=new widget('hidden');
  $h->name='jrn_type';
  $h->value=$_REQUEST['jrn_type'];
  $r.=$h->IOValue();

  $file=new widget("file");
  $file->table=1;
  $r.="<hr>";
  $r.= "<table>"; 
  if ( $p_piece) $r.="<TR>".$file->IOValue("pj","","Pièce justificative")."</TR>";
  // propose to save the pre_operation
  if ( $p_piece ) {
	$chk=new widget('checkbox');
	$chk->selected=true;
	$r.="Sauvez l'op&eacute;ration ?";
	$r.=$chk->IOValue('opd_save');

  }
  $r.="</table>";
  $r.="<hr>";
  
  $r.=$data;
  $r.=$p_submit;
  
  $r.='</FORM>';
  
  return $r;
  
}

/*! 
 **************************************************
 * \brief  Record an invoice in the table jrn &
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
 * e_march0_buy=>e:1
 * e_march1 => e : 6
 * e_quant1 => e : 2
 * e_march1_buy=>e:1
 * e_march2 => e : 7
 * e_quant2 => e : 3
 * e_march2_buy=>e:1
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
  echo_debug('user_form_ach',__LINE__,"function RecordSell($p_cn,$p_array,$p_user->id,$p_jrn)");
  foreach ( $p_array as $v => $e)
  {
    ${"$v"}=$e;
  }

  // Get the default period
  $periode=$p_user->GetPeriode();
  $amount=0.0;
  $amount_jrn=0.0;
  $sum_tva_nd=0.0;
  // own
  $own=new own($p_cn);
  $group=NextSequence($p_cn,"s_oa_group");

  // Computing total customer
  for ($i=0;$i<$nb_item;$i++) {
    // store quantity & goods in array
    $a_good[$i]=${"e_march$i"};
    $a_quant[$i]=${"e_quant$i"};
    $a_price[$i]=0;
    $a_vat_good[$i]=${"e_march$i"."_tva_id"};
    $a_vat_amount[$i]=round(${"e_march".$i."_tva_amount"},2);

    // check wether the price is set or no
    if ( isNumber(${"e_march$i"."_buy"}) == 0 ) {
      if ( $a_good[$i] !="" ) 
	{
	  // If the price is not set we have to find it from the database
	  $a_price[$i]=GetFicheAttribut($p_cn,$a_good[$i],ATTR_DEF_PRIX_VENTE);
	} 
    } 
    else 
      {
	// The price is valid
	$a_price[$i]=${"e_march$i"."_buy"};
      }

    $a_price[$i]=round($a_price[$i],2);
    
    $cost=$a_price[$i]*$a_quant[$i];
    $amount+=$cost;
    $amount_jrn+=$cost;
    echo_debug('user_form_ach.php',__LINE__,'Total customer:'.$amount_jrn);
  }
  //  $amount_jrn=round(
  $comm=FormatString($e_comm);

  // Compute vat with ded
  echo_debug('user_form_achat.php',__LINE__,"Call ComputeTotalVat");
  $a_vat=ComputeTotalVat($p_cn,$a_good,$a_quant,$a_price,$a_vat_good,$a_vat_amount,false);
  try {
    StartSql($p_cn);	
    
    // Compute the j_grpt
    $seq=NextSequence($p_cn,'s_grpt');
    // Set Internal code and Comment
    $internal=SetInternalCode($p_cn,$seq,$p_jrn);



    // Credit = goods 
    for ( $i = 0; $i < $nb_item;$i++) {

      // store not deductible and vat deductible via tax
      $aNd_amount[$i]=0.0;
      $aTva_ded_impot[$i]=0.0;
      $aTva_ded_impot_recup[$i]=0.0;

      $poste=GetFicheAttribut($p_cn,$a_good[$i],ATTR_DEF_ACCOUNT);
	  
      // don't record operation of 0
      if ( $a_price[$i]*$a_quant[$i] == 0 ) continue;

      $amount=$a_price[$i]*$a_quant[$i];
	  //We don't compute vat if it's given
	  $lvat=($a_vat_amount[$i]==0)?ComputeVat($p_cn,$a_good[$i],$a_quant[$i],$a_price[$i],  $a_vat_good[$i],$a_vat_amount[$i] ):$a_vat_amount[$i];

      // Put the non deductible part into a special account
      $non_dedu=GetFicheAttribut($p_cn,$a_good[$i],ATTR_DEF_DEPENSE_NON_DEDUCTIBLE);
      echo_debug('user_form_ach.php',__LINE__,"value non ded : $non_dedu");
      if ( $non_dedu != null && strlen(trim($non_dedu)) != 0)
		{
		  // if vat if given we use it to compute ND
		  $nd_amount=($a_vat_amount[$i]==0)?round($a_quant[$i]*$a_price[$i]*$non_dedu,2):round($a_vat_amount*$non_dedu,2);
		  
		  // save it
		  echo_debug('user_form_ach.php',__LINE__,"InsertJrnx($p_cn,'d',$p_user->id,$p_jrn,'6740',$e_date,round($nd_amount,2),$seq,$periode);");
		  $dna=new parm_code($p_cn,'DNA');
		  $j_id=InsertJrnx($p_cn,'d',$p_user->id,$p_jrn,$dna->p_value,$e_date,round($nd_amount,2),$seq,$periode);
		  $amount=$amount-$nd_amount;
		  
		  // save the ND in an array (for the easy view)
		  $aNd_amount[$i]=$nd_amount;
		}
      // Put the non deductible part into a special account
      $non_dedu=GetFicheAttribut($p_cn,$a_good[$i],ATTR_DEF_TVA_NON_DEDUCTIBLE);
      echo_debug('user_form_ach.php',__LINE__,"TVA value non ded : $non_dedu");
      if ( $non_dedu != null && strlen(trim($non_dedu)) != 0)
		{
		  $ded_vat=($lvat != null )?$lvat*$non_dedu:0;
		  $ded_vat=round($ded_vat,2);
		  $aTva_ded_impot[$i]=$ded_vat;
		  $sum_tva_nd+=$ded_vat;
		  
		  // compute the NDA TVA
		  $tva_dna=new parm_code($p_cn,'TVA_DNA');
		  echo_debug('user_form_ach.php',__LINE__,
					 "InsertJrnx($p_cn,'d',$p_user->id,$p_jrn,".$tva_dna->p_value.",$e_date,round($ded_vat,2),$seq,$periode);");
		  
		  $j_id=InsertJrnx($p_cn,'d',$p_user->id,$p_jrn,$tva_dna->p_value,$e_date,round($ded_vat,2),$seq,$periode);
		}
	  
      // Put the non deductible part into a special account
      $non_dedu=GetFicheAttribut($p_cn,$a_good[$i],ATTR_DEF_TVA_NON_DEDUCTIBLE_RECUP);
      echo_debug('user_form_ach.php',__LINE__,"TVA value non ded : $non_dedu");

      if ( $non_dedu != null && strlen(trim($non_dedu)) != 0 )
		{
		  		  
		  $ded_vat=($lvat != null )?$lvat*$non_dedu:0;
		  
		  $sum_tva_nd+=round($ded_vat,2);
		  $aTva_ded_impot_recup[$i]=round($ded_vat,2);
		  
		  // Save it 
		  $tva_ded_impot=new parm_code($p_cn,'TVA_DED_IMPOT');
		  echo_debug('user_form_ach.php',__LINE__,
					 "InsertJrnx($p_cn,'d',$p_user->id,$p_jrn,".$tva_ded_impot->p_value.",$e_date,round($ded_vat,2),$seq,$periode);");
		  
		  $j_id=InsertJrnx($p_cn,'d',$p_user->id,$p_jrn,$tva_ded_impot->p_value,$e_date,round($ded_vat,2),$seq,$periode);
		}
	  

	  
	  
      // record into jrnx
      echo_debug('user_form_ach.php',__LINE__,"InsertJrnx($p_cn,'d',$p_user->id,$p_jrn,$poste,$e_date,round($amount,2),$seq,$periode);");
      $j_id=InsertJrnx($p_cn,'d',$p_user->id,$p_jrn,$poste,$e_date,round($amount,2),$seq,$periode,$a_good[$i]);
	  
      /* \brief if the quantity is < 0 then the stock increase (return of
       *  material)
       */
      $nNeg=($a_quant[$i]<0)?-1:1;

      // always save quantity but in withStock we can find what card need a stock management
      InsertStockGoods($p_cn,$j_id,$a_good[$i],$nNeg*$a_quant[$i],'d');
      echo_debug('user_form_ach.php',__LINE__,"value non ded : ".$a_good[$i]."is");		
      if ( $own->MY_ANALYTIC != "nu" )
	{
	  // for each item, insert into operation_analytique */
	  $op=new operation($p_cn); 
	  $op->oa_group=$group;
	  $op->j_id=$j_id;
	  $op->oa_date=$e_date;
	  $op->oa_debit=($op->oa_amount < 0 )?'t':'f';
	  $op->oa_description=$comm;
	  $op->save_form_plan($_POST,$i);
	}
	  //---------------------------------------------------------
	  // insert into quant_purchase
	  //---------------------------------------------------------
	  
	  echo_debug(__FILE__.":".__LINE__,"a_vat = ",$a_vat);
	  //	  echo_debug(__FILE__.":".__LINE__."a_vat[$i] =",$a_vat[$i]);
	  //!\note
	  // $a_vat_good[$i] contains the tva_id
	  // $a_vat_amount[$i] contains the amount of vat
	  $vat_code=$a_vat_good[$i];
	  $computed_vat=$lvat-$aNd_amount[$i]-$aTva_ded_impot[$i]-$aTva_ded_impot_recup[$i];
	  $qp_vat=($vat_code==-1)?0:$computed_vat;

	  echo_debug('form_ach',__LINE__,"Insert into insert_quant_purchase");
	  $r=ExecSql($p_cn,"select insert_quant_purchase ".
				 "('".$internal."'".
				 ",".$j_id.
				 ",'".$a_good[$i]."'".
				 ",".$a_quant[$i].",".
				 round($amount,2).
				 ",".$qp_vat.
				   ",".$vat_code.
				 ",".$aNd_amount[$i].
				   ",".$aTva_ded_impot[$i].
				 ",".$aTva_ded_impot_recup[$i].
				 ",'".$e_client."')");
	  
	  
	  
    } // end loop
	
	// set up internal code for quant_purchase

    // Insert Vat
    $sum_tva=0.0;
    if ( $a_vat  !=  null  ) // no vat
      {
		echo_debug('user_form_ach',__LINE__,'a_vat = '.var_export($a_vat,true));
		foreach ($a_vat as $tva_id => $e_tva_amount ) 
		  {
			echo_debug('user_form_ach',__LINE__," tva_amount = $e_tva_amount tva_id=$tva_id");
			$poste=GetTvaPoste($p_cn,$tva_id,'d');
			if ($e_tva_amount == 0 ) continue;
			echo_debug('user_form_ach',__LINE__,"InsertJrnx($p_cn,'d',$p_user->id,$p_jrn,$poste,$e_date,round($e_tva_amount,2),$seq,$periode);");
			$r=InsertJrnx($p_cn,'d',$p_user->id,$p_jrn,$poste,$e_date,round($e_tva_amount,2),$seq,$periode);
			$sum_tva+=round($e_tva_amount,2);
		  }
      }
    echo_debug('user_form_ach.php',__LINE__,"echeance = $e_ech");
    echo_debug('user_form_ach.php',__LINE__,"sum_tva = $sum_tva");
    echo_debug('user_form_ach.php',__LINE__,"amount_jrn = $amount_jrn");
    echo_debug('user_form_ach.php',__LINE__,"sum_tva_nd = $sum_tva_nd");
	
    // Debit = client
    $poste=GetFicheAttribut($p_cn,$e_client,ATTR_DEF_ACCOUNT);
    echo_debug('user_form_achat.php',__LINE__,"get e_client $e_client poste $poste");
    echo_debug('user_form_achat.php',__LINE__,"insert client");
	
    $r=InsertJrnx($p_cn,'c',$p_user->id,$p_jrn,$poste,$e_date,round($amount_jrn+$sum_tva+$sum_tva_nd,2),$seq,
		  $periode,$e_client);
	
	
    $r=InsertJrn($p_cn,$e_date,$e_ech,$p_jrn,"--",$seq,$periode);
	
    // Set Internal code and Comment
	$Res=ExecSql($p_cn,"update jrn set jr_internal='".$internal."' where ".
	       " jr_grpt_id = ".$seq);

    $comment=(FormatString($e_comm) == null )?$internal." Fournisseur : ".GetFicheName($p_cn,$e_client):FormatString($e_comm);

    // Update and set the invoice's comment 
    $Res=ExecSql($p_cn,"update jrn set jr_comment='".$comment."' where jr_grpt_id=".$seq);

    if ( isset ($_FILES))
      save_upload_document($p_cn,$seq);
	  // Save the operation
	if ( isset($_POST['opd_save']) && $_POST['opd_save']=='on' ){
	  echo_debug(__FILE__.':'.__LINE__.'- ','save opd');
	  $opd=new Pre_op_ach($p_cn);
	  $opd->get_post();
	  $opd->save();
	  echo_debug(__FILE__.':'.__LINE__.'- ',"opd = ",$opd);
	}
  } catch (Exception $e) {
    echo '<span class="error">'.
      'Erreur dans l\'enregistrement '.
      __FILE__.':'.__LINE__.' '.
      $e->getMessage();
    Rollback($p_cn);
    exit();
  }
  Commit($p_cn);
  
  return array($internal,$comment);
}

?>
