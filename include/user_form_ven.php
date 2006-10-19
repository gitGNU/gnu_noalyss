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
/*! \file
 * \brief Functions for the ledger of sold
 */
require_once("constant.php");
require_once("class_widget.php");
require_once("preference.php");
require_once("fiche_inc.php");
require_once("user_common.php");
/*!   FormVenInput
 * \brief  Display the form for a sell
 *           Used to show detail, encode a new invoice 
 *           or update one
 *        
 *  
 * \param p_array which can be empty
 * \param the "journal"
 * \param $p_periode = periode
 * \param view_only if we cannot change it (no right or centralized op)
 * \param $p_article number of article
 * \return string with the form
 */
function FormVenInput($p_cn,$p_jrn,$p_periode,$p_array=null,$pview_only=true,$p_article=1)
{ 
  if ( $p_array != null ) {
    // array contains old value
    foreach ( $p_array as $a=>$v) {
      ${"$a"}=$v;
    }
  }
  // The date
  list ($l_date_start,$l_date_end)=GetPeriode($p_cn,$p_periode);
  $op_date=( ! isset($e_date) ) ?substr($l_date_start,2,8):$e_date;
  $e_ech=(isset($e_ech))?$e_ech:"";
  $e_comm=(isset($e_comm))?$e_comm:"";
  //  $e_jrn=(isset($e_jrn))?$e_jrn:"";
  // Save old value and set a new one

  echo_debug('user_form_ven.php',__LINE__,"form_input.php.FormVentep_op_date is $op_date");
  $r="";
  if ( $pview_only == false) {
    $r.=JS_SEARCH_CARD;
    $r.=JS_SHOW_TVA;    
    $r.=JS_TVA;
    // Compute href
    //    $href=basename($_SERVER['PHP_SELF']);
    $href=basename($_SERVER['PHP_SELF']);
    switch ($href)
      {
	// user_jrn.php
      case 'user_jrn.php':
	$href="user_jrn.php?action=insert_vente&p_jrn=$p_jrn";
	break;
      case 'commercial.php':
	$href="commercial.php?p_action=facture&p_jrn=$p_jrn";
	break;
      default:
	echo_error('user_form_ven.php',__LINE__,'Erreur invalid request uri');
	exit (-1);
      }

    $r.="<FORM NAME=\"form_detail\" ACTION=\"$href\" METHOD=\"POST\">";

    
  }

  $r.='<TABLE>';
  //  Date
  //--
  $Date=new widget("text");
  $Date->SetReadOnly($pview_only);
  $Date->table=1;
  $Date->tabindex=1;
  $r.="<tr>";
  $r.=$Date->IOValue("e_date",$op_date,"Date");
  $r.="</tr>";
  // Payment limit
  //--
  $Echeance=new widget("text");
  $Echeance->SetReadOnly($pview_only);
  $Echeance->table=1;
  $Echeance->tabindex=2;
  $r.="<tr>";
  $r.=$Echeance->IOValue("e_ech",$e_ech,"Echeance");
  $r.="</tr>";
  // Comment
  //--
  $Commentaire=new widget("text");
  $Commentaire->table=1;
  $Commentaire->SetReadOnly($pview_only);
  $Commentaire->size=80;
  $Commentaire->tabindex=3;
  $r.="<tr>";
  $r.=$Commentaire->IOValue("e_comm",$e_comm,"Description");
  $r.="</tr>";
  include_once("fiche_inc.php");
  // Display the customer
  //--
  $fiche='deb';
  echo_debug('user_form_ven.php',__LINE__,"Client Nombre d'enregistrement ".sizeof($fiche));
  // Save old value and set a new one
  //--
  $e_client=( isset ($e_client) )?$e_client:"";
  $e_client_label="";  

  // retrieve e_client_label
  //--
  $a_client=GetFicheAttribut($p_cn,$e_client);
  if ( $a_client != null)   
    $e_client_label=$a_client['vw_name']."  adresse ".$a_client['vw_addr']."  ".$a_client['vw_cp'];


  $W1=new widget("js_search");
  $W1->label="Client";
  $W1->name="e_client";
  $W1->tabindex=3;
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
  //--
  for ($i=0;$i< $p_article;$i++) {
    // Code id, price & vat code
    //--
    $march=(isset(${"e_march$i"}))?${"e_march$i"}:"";
    $march_sell=(isset(${"e_march".$i."_sell"}))?${"e_march".$i."_sell"}:"";
    $march_tva_id=(isset(${"e_march$i"."_tva_id"}))?${"e_march$i"."_tva_id"}:"";

    $march_tva_label="";
    $march_label="";

    // retrieve the tva label and name
    //--
    $a_fiche=GetFicheAttribut($p_cn, $march);
    if ( $a_fiche != null ) {
      if ( $march_tva_id == "" ) {
	$march_tva_id=$a_fiche['tva_id'];
	$march_tva_label=$a_fiche['tva_label'];
      }
      $march_label=$a_fiche['vw_name'];
    }
  

    // Show input
    //--
    $W1=new widget("js_search");
    $W1->label="";
    $W1->name="e_march".$i;
    $W1->value=$march;
    $W1->extra='cred';  // credits
    $W1->extra2=$p_jrn;
    $W1->readonly=$pview_only;
    $r.="<TR>".$W1->IOValue()."</TD>";
    $Span=new widget ("span");
    $Span->SetReadOnly($pview_only);
    // card's name, price
    //--
    $r.="<TD>".$Span->IOValue("e_march".$i."_label",$march_label)."</TD>";
   // price
    $Price=new widget("text");
    $Price->SetReadOnly($pview_only);
    $Price->table=1;
    $Price->size=9;
    $r.=$Price->IOValue("e_march".$i."_sell",$march_sell);
    // vat label
    //--
    $select_tva=make_array($p_cn,"select tva_id,tva_label from tva_rate order by tva_id",1);
    $Tva=new widget("select");
    $Tva->table=1;
    $Tva->selected=$march_tva_id;
    $r.=$Tva->IOValue("e_march$i"."_tva_id",$select_tva);

    // quantity
    //--
    $quant=(isset(${"e_quant$i"}))?${"e_quant$i"}:"1";
    $Quantity=new widget("text");
    $Quantity->SetReadOnly($pview_only);
    $Quantity->table=1;
    $Quantity->size=9;
    //$r.=InputType("","TEXT","e_quant".$i,$quant,$pview_only);
    $r.=$Quantity->IOValue("e_quant".$i,$quant);
    $r.='</TR>';
  }



  $r.="</TABLE>";
  $r.="<hr>";
  // Set correctly the REQUEST param for jrn_type 
  $h=new widget('hidden');
  $h->name='jrn_type';
  $h->value='VEN';
  $r.=$h->IOValue();

  if ($pview_only == false ) {
    $r.='<INPUT TYPE="SUBMIT" NAME="add_item" VALUE="Ajout article" TABINDEX="32767">';
    $r.='<INPUT TYPE="SUBMIT" NAME="view_invoice" VALUE="Enregistrer" TABINDEX="32767" ID="SubmitButton">';
    $r.="</DIV>";
    $r.="</FORM>";
    $r.=JS_CALC_LINE;
  } else {
     $r.="</div>";

  }



  return $r;


}
/*!   form_verify_input
 **************************************************
 \brief  verify if the data to insert are valid
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
  foreach ($p_array as $name=>$content) {
    ${"$name"}=$content;
  }
  // Verify the date
  if ( isDate($e_date) == null ) { 
	  echo_error("Invalid date $e_date");
	  echo_debug('user_form_ven.php',__LINE__,"Invalid date $e_date");
	  echo "<SCRIPT> alert('INVALID DATE $e_date !!!!');</SCRIPT>";
	  return null;
		}
// Verify the quantity
for ($o = 0;$o < $p_number; $o++) {
	if ( isNumber(${"e_quant$o"}) == 0 ) {
		echo_debug('user_form_ven.php',__LINE__,"invalid quantity ".${"e_quant$o"});
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
 if (strlen($e_ech) != 0 and isNumber($e_ech)  == 0 and  isDate ($e_ech) == null ) {
	$msg="Echeance invalide";
		echo_error($msg); echo_error($msg);	
		echo "<SCRIPT>alert('$msg');</SCRIPT>";
		return null;
 } 

 // if ech is a number of days then compute date limit
 if ( strlen($e_ech) != 0 and isNumber($e_ech) == 1) {
 list($day,$month,$year)=explode(".",$e_date);
  echo_debug('user_form_ven.php',__LINE__," date $e_date = $day.$month.$year");
  $p_ech=date('d.m.Y',mktime(0,0,0,$month,$day+$e_ech,$year));
  echo_debug('user_form_ven.php',__LINE__,"p_ech = $e_ech $p_ech");
  $e_ech=$p_ech;
  $wHidden=new widget("hidden");
  $data.=$wHidden->IOValue("e_ech",$e_ech);
 }

 // Check if the fiche is in the jrn
 if (IsFicheOfJrn($p_cn , $p_jrn, $e_client,'deb') == 0 ) 
   {
     $msg="Client invalid please recheck";
     echo_error($msg);
     echo "<SCRIPT>alert('$msg');</SCRIPT>";
     return null;
   }
 // Check if the card has a valid account
 if ( CheckPoste($p_cn,$e_client) == null )
   return null;

 // check if all e_march are in fiche
  for ($i=0;$i<$p_number;$i++) {
    if ( trim(${"e_march$i"})  == "" ) {
      // no goods to sell 
      continue;
    }
  

    // Check 
    if ( isFicheOfJrn($p_cn,$p_jrn,${"e_march$i"},'cred') == 0 ) {
      $msg="Fiche inexistante !!! ";
      echo_error(__FILE__.__LINE__.$msg);
      echo "<SCRIPT>alert('$msg');</SCRIPT>";
      return null;
    }
    // Check if the card has a valid account
    if ( CheckPoste($p_cn,${"e_march$i"}) == null )
      return null;
  }
// Verify the userperiode

// p_periode contient la periode par default
  list ($l_date_start,$l_date_end)=GetPeriode($p_cn,$p_periode);
  
  // Date dans la periode active
  echo_debug ('user_form_ven',__LINE__,"date start periode $l_date_start date fin periode $l_date_end date demande $e_date");
  if ( cmpDate($e_date,$l_date_start)<0 || 
       cmpDate($e_date,$l_date_end)>0 )
    {
      $msg="Not in the active periode please change your preference";
      echo "<SCRIPT>alert('$msg');</SCRIPT>";
      return null;
    }
    // Periode ferme
    if ( PeriodeClosed ($p_cn,$p_periode)=='t' )
      {
		$msg="This periode is closed please change your preference";
		echo_error($msg); echo_error($msg);	
		echo "<SCRIPT>alert('$msg');</SCRIPT>";
		return null;
      }
    return true;
}
/*!   FormVenteView ($p_cn,$p_jrn,$p_periode,$p_array,$p_number,$p_doc='html',$p_comment='') 
 **************************************************
 * \brief  Show the invoice before inserting it 
 *         the database.
 *        
 *  
 * \param p_cn database connection
 * \param p_jrn journal
 * \param p_periode
 * \param array of value
 * \param nb of item
 * \param p_doc type pdf or html
 * \return string
 * 
 */

function FormVenteView ($p_cn,$p_jrn,$p_periode,$p_array,$p_number,$p_doc='form',$p_comment='') 
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
      $vat_label="";
      $vat_rate=0.0;
    } else { 
      $vat_label=$a_vat['tva_label'];
      $vat_rate=$a_vat['tva_rate'];
    }		
    $fiche_price=round($fiche_price,2);
    // Total card without vat
    $fiche_sum=$fiche_price*$fiche_quant;
    $fiche_sum=round($fiche_sum,2);	

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
    // Compute href
    //    $href=basename($_SERVER['PHP_SELF']);
    $href=basename($_SERVER['PHP_SELF']);
    switch ($href)
      {
	// user_jrn.php
      case 'user_jrn.php':
	$href="user_jrn.php?action=record&p_jrn=$p_jrn";
	break;
      case 'commercial.php':
	$href="commercial.php?p_action=facture&sa=record&p_jrn=$p_jrn";
	break;
      default:
	echo_error('user_form_ven.php',__LINE__,'Erreur invalid request uri');
	exit (-1);
      }
      

    $r.='<FORM METHOD="POST" enctype="multipart/form-data" ACTION="'.$href.'">';

    // check for upload piece
    $file=new widget("file");
    $file->table=1;
    $r.="<hr>";
    $r.= "<table>"; 
    $r.="<TR>".$file->IOValue("pj","","Pièce justificative")."</TR>";
    $r.="</table>";
    $r.="<hr>";


    // if we were in the management module, appl. propose to generate an invoice
    //    if ( $_SERVER['PHP_SELF'] == '/commercial.php')
    echo_debug('user_form_ven',__LINE__," _SERVER['PHP_SELF'] ".$_SERVER['PHP_SELF']);
    if ( basename($_SERVER['PHP_SELF']) == 'commercial.php') 
      {
	// if a template exists propose to choose an invoice template
	if ( CountSql($p_cn,
		      "select md_id,md_name from document_modele where md_type=4") > 0 )
	  {
	    $r.='G&eacute;n&eacute;rer une facture <input type="checkbox" name="gen_invoice" CHECKED>';
	    // We propose to generate  the invoice and some template
	    $doc_gen=new widget("select");
	    $doc_gen->name="gen_doc";
	    $doc_gen->value=make_array($p_cn,
				       "select md_id,md_name from document_modele where md_type=4");
	    $r.=$doc_gen->IOValue();  

	    $r.="<hr>";
	  }
      }

    
    $r.=$data;
    if ( $sum_with_vat != 0 ) {
      $r.='<INPUT TYPE="SUBMIT" name="record_and_print_invoice" value="Sauver" >';
    }
    $r.='<INPUT TYPE="SUBMIT" name="correct_new_invoice" value="Corriger">';
    
    $r.='</FORM>';
  } 
  return $r;
  
}

/*!   RecordInvoice($p_cn,$p_array,$p_user,$p_jrn)
 **************************************************
 * \brief  Record an invoice in the table jrn &
 *           jrnx
 *        
 * \param  $p_cn Database connection
 * \param $p_array array contains all the invoice data
 *        - e_date => e : 01.01.2003
 *        - e_client => e : 3
 *        - nb_item => e : 3
 *        - e_march0 => e : 6
 *        - e_quant0 => e : 0
 *        - e_march0_sell=>e:1
 *        - e_march1 => e : 6
 *        - e_quant1 => e : 2
 *        - e_march1_sell=>e:1
 *        - e_march2 => e : 7
 *        - e_quant2 => e : 3
 *        - e_march2_sell=>e:1
 *        * V :        view_invoice => e : Voir cette facture
 *        * V : record_invoice => e : Sauver 
 * \param $p_periode periode
 * \param $p_jrn current folder (journal)
 *
 * \return     array ( internal_code,true on success)
 */
function RecordInvoice($p_cn,$p_array,$p_user,$p_jrn)
{
  echo_debug('RecordInvoice',0,"p_array = $p_array");

  foreach ( $p_array as $v => $e)
  {
    ${"$v"}=$e;
  }

  // Get the default period
  $periode=$p_user->GetPeriode();
  $amount=0.0;

  // Computing total customer
  //--
  for ($i=0;$i<$nb_item;$i++) {
    // store quantity & goods in array
    $a_good[$i]=${"e_march$i"};
    $a_quant[$i]=${"e_quant$i"};
    $a_price[$i]=0;
    $a_vat[$i]=${"e_march$i"."_tva_id"};
    // check whether the price is set or no
    if ( isNumber(${"e_march$i"."_sell"}) == 0 ) {
      if ( $a_good[$i] != "" ) {
	     // If the price is not set we have to find it from the database
	     $a_price[$i]=GetFicheAttribut($p_cn,$a_good[$i],ATTR_DEF_PRIX_VENTE);
	   } 
    } else {
      // The price is valid
      $a_price[$i]=${"e_march$i"."_sell"};
    }
    $a_price[$i]=round($a_price[$i],2);
    $cost=$a_price[$i]*$a_quant[$i];
    $amount+=$cost;

    echo_debug('RecordInvoice',__LINE__,"cost = $cost amount = $amount  ");
  }// for

  $comm=FormatString($e_comm);
  // Compute VAT
  //--
  $a_vat_new=ComputeTotalVat($p_cn,$a_good,$a_quant,$a_price,$a_vat);
  $sum_vat=0.0;
  if ( $a_vat_new != null ){
    foreach ( $a_vat_new as $element => $t) {
      echo_debug('user_form_ven.php',__LINE__," a_vat element $element t $t");
      $sum_vat+=$t;
      echo_debug('user_form_ven.php',__LINE__,"sum_vat = $sum_vat");
    }
  }
  // First we add in jrnx
	
  // Compute the j_grpt
  $seq=NextSequence($p_cn,'s_grpt');


  // Debit = client
  $poste=GetFicheAttribut($p_cn,$e_client,ATTR_DEF_ACCOUNT);
  StartSql($p_cn);	
  $r=InsertJrnx($p_cn,'d',$p_user->id,$p_jrn,$poste,$e_date,round($amount,2)+round($sum_vat,2),$seq,$periode,$e_client);
  if ( $r == false) { $Rollback($p_cn);exit("error 'user_form_ven.php' __LINE__");}

  // Credit = goods 
  for ( $i = 0; $i < $nb_item;$i++) {
    if ( $a_good[$i] == ""  ) continue;
    $poste=GetFicheAttribut($p_cn,$a_good[$i],ATTR_DEF_ACCOUNT);
	  
    // don't record operation of 0
    if ( $a_price[$i]*$a_quant[$i] == 0 ) continue;
	  
    // record into jrnx
    $j_id=InsertJrnx($p_cn,'c',$p_user->id,$p_jrn,$poste,$e_date,round($a_price[$i]*$a_quant[$i],2),$seq,$periode,$a_good[$i]);
    if ( $j_id == false) { $Rollback($p_cn);exit("error 'user_form_ven.php' __LINE__");}
    // always save quantity but in withStock we can find what card need a stock management
    if (  InsertStockGoods($p_cn,$j_id,$a_good[$i],$a_quant[$i],'c') == false ) {
      $Rollback($p_cn);exit("error 'user_form_ven.php' __LINE__");}
    } // end loop
  
  // Insert Vat

  if ( $a_vat_new  !=  null  ) // no vat

    {
      foreach ($a_vat_new as $tva_id => $tva_amount ) {
	$poste=GetTvaPoste($p_cn,$tva_id,'c');
	if ($tva_amount == 0 ) continue;
	$r=InsertJrnx($p_cn,'c',$p_user->id,$p_jrn,$poste,$e_date,round($tva_amount,2),$seq,$periode);
	if ( $r == false ) { Rollback($p_cn); exit(" Error 'user_form_ven.php' __LINE__");}
      
      }
    }
  echo_debug('user_form_ven.php',__LINE__,"echeance = $e_ech");
  $r=InsertJrn($p_cn,$e_date,$e_ech,$p_jrn,"Invoice",$seq,$periode);
  if ( $r == false ) { Rollback($p_cn); exit;}
  // Set Internal code and Comment
  $internal=SetInternalCode($p_cn,$seq,$p_jrn);
  $comment=(FormatString($e_comm) == null )?$internal."  client : ".GetFicheName($p_cn,$e_client):FormatString($e_comm);

  // Update and set the invoice's comment 
  $Res=ExecSql($p_cn,"update jrn set jr_comment='".$comment."' where jr_grpt_id=".$seq);
  if ( $Res == false ) { Rollback($p_cn); exit(" Error 'user_form_ven.php' __LINE__"); };

  if ( isset ($_FILES)) {
    if ( sizeof($_FILES) != 0 )
    save_upload_document($p_cn,$seq);
  }

// save the quantity, then we can make an invoice
  for ( $i=0;$i < $nb_item;$i++) 
	{
	// don't record operation of 0
    	if ( $a_price[$i]*$a_quant[$i] == 0 ) continue;
	
	// insert into the table quant_sold
	// Note that negative value are also saved but not the vat !
	if (  $a_vat[$i] == -1) {
		$computed_vat=0;
		$vat_code="null";
	} else {
		$computed_vat=ComputeVat($p_cn,$a_good[$i],$a_quant[$i],$a_price[$i],$a_vat[$i]);
		$vat_code=$a_vat[$i];
	}
	 $r=ExecSql($p_cn,"select insert_quant_sold ".
		    "('".$internal."','".$a_good[$i]."',".$a_quant[$i].",".$a_price[$i]*$a_quant[$i].
		    ",".$computed_vat.
		    ",".$vat_code.",'".$e_client."')");
	 if ( $r == false ) { Rollback($p_cn); exit(" Error 'user_form_ven.php' __LINE__"); };
	}
  Commit($p_cn);
  return array($internal,$comment);
}

?>
