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
require_once("class_widget.php");
require_once("constant.php");
require_once("preference.php");
require_once("fiche_inc.php");
require_once("user_common.php");


/* function FormFin($p_cn,$p_jrn,$p_user,$p_array=null,$pview_only=true,$p_item=1) 
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
function FormFin($p_cn,$p_jrn,$p_periode,$p_submit,$p_array=null,$pview_only=true,$p_item=4,$p_save=false)
{ 
  include_once("poste.php");
  if ( $p_array != null ) {
    // array contains old value
    foreach ( $p_array as $a=>$v) {
      ${"$a"}=$v;
    }
  }
  // The date
  list ($l_date_start,$l_date_end)=GetPeriode($p_cn,$p_periode);
  $flag=(isset($e_date))?1:0;
  $e_date=( ! isset($e_date) ) ? substr($l_date_start,2,8):$e_date;

  // Verify if valid date
  if ($flag ==1 and   VerifyOperationDate($p_cn,$p_periode,$e_date)   == null) {
    if ( $pview_only == true) 
      return null;
    else 
      $e_date=substr($l_date_start,2,8);
  }


  $e_comment=(isset($e_comment))?$e_comment:"";

  $r="";
  if ( $pview_only == false) {
    $r.=JS_SEARCH_CARD;
    $r.=JS_CONCERNED_OP;
  }

  $r.="<FORM NAME=\"form_detail\" enctype=\"multipart/form-data\" ACTION=\"user_jrn.php?action=new&p_jrn=$p_jrn\" METHOD=\"POST\">";
  $r.='<TABLE>';
  $Date=new widget("text");
  $Date->SetReadOnly($pview_only);
  $Date->table=1;
  $r.="<tr>";
  $r.=$Date->IOValue("e_date",$e_date,"Date");
  $r.="</tr>";


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
    
    if ( $pview_only ==true) {
      return null;
      echo_debug(__FILE__,__LINE__,"FormFin returns NULL the bank account is not valid");
    }
    
  }
  
  //  search widget
    $W1=new widget("js_search");
    $W1->readonly=$pview_only;
    $W1->label="Banque";
    $W1->name="e_bank_account";
    $W1->value=$e_bank_account;
    $W1->extra=FICHE_TYPE_FIN;  // credits
    $W1->extra2=$p_jrn;
    $r.="<TR>".$W1->IOValue()."</TD>";

    $r.="</TABLE>";
  
    $Span=new widget ("span");
    $Span->SetReadOnly($pview_only);
    $r.="<TD>".$Span->IOValue("e_bank_account_label",$e_bank_account_label)."</TD>";

  
  // ComputeBanqueSaldo
  // 
  if ( $pview_only == true ) {
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
  $r.="<th>D&eacute;nomination</TH>";
  $r.="<th>Description</TH>";
  $r.="<th>Montant</TH>";
  $r.='<th colspan="2"> Op. Concern&eacute;</th>';
  $r.="</TR>";
  // Parse each " tiers" 
    for ($i=0; $i < $p_item; $i++) {
      $tiers=(isset(${"e_other".$i}))?${"e_other".$i}:"";
      $tiers_label="";
      $tiers_amount=(isset(${"e_other$i"."_amount"}))?${"e_other$i"."_amount"}:0;
      if ( isNumber($tiers_amount) == 0) {
	if ( $pview_only==true ){
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

    $W1=new widget("js_search");
    $W1->label="";
    $W1->name="e_other".$i;
    $W1->value=$tiers;
    $W1->extra='cred';  // credits
    $W1->extra2=$p_jrn;
    $W1->readonly=$pview_only;
    $r.="<TR>".$W1->IOValue()."</TD>";
    // label
    $other=new widget("span");
    $r.="<TD>";
    $r.=$other->IOValue("e_other$i"."_label", $tiers_label);
    // Comment
    $wComment=new widget("text");
    $wComment->table=1;
    $wComment->SetReadOnly($pview_only);
    $r.=$wComment->IOValue("e_other$i"."_comment",$tiers_comment);
    // amount
    $wAmount=new widget("text");
    $wAmount->table=1;
    $wAmount->size=7;
    $wAmount->SetReadOnly($pview_only);
    $r.=$wAmount->IOValue("e_other$i"."_amount",$tiers_amount);
    // concerned
    ${"e_concerned".$i}=(isset(${"e_concerned".$i}))?${"e_concerned".$i}:"";
    $wConcerned=new widget("js_concerned");
    $wConcerned->SetReadOnly($pview_only);
    $r.=$wConcerned->IOValue("e_concerned".$i,${"e_concerned".$i});
    $r.='</TR>';
   // if not recorded the new amount must be recalculate
   // if recorded the old amount is recalculated
    if ( $pview_only == true)      
      $new_solde=($p_save==false)?$new_solde+$tiers_amount:$new_solde-$tiers_amount;
 }

$r.="</TABLE>";

 if ( $pview_only==true && $p_save==false) {
// check for upload piece
   $file=new widget("file");
   $file->table=1;
   $r.="<hr>";
   $r.= "<table>"; 
   $r.="<TR>".$file->IOValue("pj","","Pi&egrave;ce justificative")."</TR>";
   $r.="</table>";
   $r.="<hr>";
 }
$r.=$p_submit;
$r.="</DIV>";
$r.="</FORM>";

// if view_only is true
//Put the new saldo here (old saldo - operation)
 if ( $pview_only==true)  {
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
  $periode=$p_user->GetPeriode();

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

    // Compute the j_grpt
    $seq=NextSequence($p_cn,'s_grpt');

    if ( InsertJrnx($p_cn,'d',$p_user->id,$p_jrn,$poste_bq,$e_date,round(${"e_other$i"."_amount"},2),$seq,$periode) == false ) {
      $Rollback($p_cn);exit("error __FILE__ __LINE__");
    }


    // Record a line for the other account
    if ( ($j_id=InsertJrnx($p_cn,'c',$p_user->id,$p_jrn,$poste,$e_date,round(${"e_other$i"."_amount"},2),$seq,$periode)) == false )
      { $Rollback($p_cn);exit("error __FILE__ __LINE__");}

    echo_debug(__FILE__,__LINE__,"   $j_id=InsertJrnx($p_cn,'d',$p_user,$p_jrn,$poste,$e_date,".${"e_other$i"}."_amount".",$seq,$periode);");

    if ( ($jr_id=InsertJrn($p_cn,$e_date,'',$p_jrn,FormatString(${"e_other$i"."_comment"}),
			   round(${"e_other$i"."_amount"},2),$seq,$periode))==false) {
      $Rollback($p_cn);exit("error __FILE__ __LINE__");}
  
    if ( isNumber(${"e_concerned".$i}) == 1 ) {

      InsertRapt($p_cn,$jr_id,${"e_concerned$i"});
    }


  // Set Internal code and Comment
    $internal_code=SetInternalCode($p_cn,$seq,$p_jrn);
    $comment=$internal_code."  client : ".GetFicheName($p_cn,$e_bank_account);
    if ( FormatString(${"e_other$i"."_comment"}) == null ) {
      // Update comment if comment is blank
      $Res=ExecSql($p_cn,"update jrn set jr_comment='".$comment."' where jr_grpt_id=".$seq);
    }
    
  } // for nbitem
  if ( isset ($_FILES))
    save_upload_document($p_cn,$seq);

  Commit($p_cn);
}
?>
