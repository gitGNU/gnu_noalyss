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
require_once("class_widget.php");
require_once("constant.php");
require_once("preference.php");
require_once("fiche_inc.php");
require_once("user_common.php");
require_once ('class_pre_operation.php');
require_once ('class_acc_ledger.php');

/*! \file
 * \brief Functions for the financial ledger
 */
/*! 
 **************************************************
 * \brief  verify if the data to insert are valid
 *        
 *  
 * \param $p_cn database connection
 * \param $p_jrn concerned ledger
 * \param $p_periode User periode
 * \param $p_array array with the post data
 * \param $p_number number of items
 *
 * \return:
 */
function form_verify_input($p_cn,$p_jrn,$p_periode,$p_array,$p_number)
{
  foreach ($p_array as $name=>$content) {
    ${"$name"}=$content;
  }
  // Verify the date
  if ( isDate($e_date) == null ) { 
	  echo_error('user_form_fin.php',__LINE__,"Invalid date $e_date");
	  echo "<SCRIPT> alert('INVALID DATE $e_date !!!!');</SCRIPT>";
	  return null;
		}


 // Check if the fiche is in the jrn
 if (IsFicheOfJrn($p_cn , $p_jrn, $e_bank_account,'deb') == 0 ) 
   {
     $msg="Mauvais compte en banque";
     echo_error('user_form_fin.php',__LINE__,$msg);	
     echo "<SCRIPT>alert('$msg');</SCRIPT>";
     return null;
   }
 // Check if the card has a valid account
 if ( CheckPoste($p_cn,$e_bank_account) == null )
   return null;

 // check if all e_march are in fiche
  for ($i=0;$i<$p_number;$i++) {
    if ( trim(${"e_other$i"})  == "" ) {
      // nothing to do
      continue;
    }
	// Check amount
    if ( isNumber(${"e_other".$i."_amount"}) == 0) {
		$msg="Montant invalide !!! ";
		echo_error('user_form_fin.php',__LINE__,$msg);	
		echo "<SCRIPT>alert('$msg');</SCRIPT>";
		return null;
  	}
    // Check 
    if ( isFicheOfJrn($p_cn,$p_jrn,${"e_other$i"},'cred') == 0 ) {
      $msg="Fiche inexistante !!! ";
      echo_error('user_form_fin.php',__LINE__,$msg);
      echo "<SCRIPT>alert('$msg');</SCRIPT>";
      return null;
    }
    // Check if the card has a valid account
    if ( CheckPoste($p_cn,${"e_other".$i}) == null )
      return null;
  }
// Verify the userperiode

// p_periode contient la periode par default
  list ($l_date_start,$l_date_end)=GetPeriode($p_cn,$p_periode);
  
  // Date dans la periode active
  echo_debug ('user_form_fin',__LINE__,"date start periode $l_date_start date fin periode $l_date_end date demande $e_date");
  if ( cmpDate($e_date,$l_date_start)<0 || 
       cmpDate($e_date,$l_date_end)>0 )
    {
      $msg="Not in the active periode please change your preference";
      echo_debug('user_form_fin.php',__LINE__,$msg);	
      echo "<SCRIPT>alert('$msg');</SCRIPT>";
      return null;
    }
    // Periode ferm� 
    if ( PeriodeClosed ($p_cn,$p_periode)=='t' )
      {
		$msg="This periode is closed please change your preference";
		echo_debug('user_form_fin.php',__LINE__,$msg);	
		echo "<SCRIPT>alert('$msg');</SCRIPT>";
		return null;
      }
    return true;
}


/*! 
 * \brief  Display the form for financial 
 *           Used to show detail, encode a new fin op 
 *           or update one
 *        
 * \param $p_cn database connection 
 * \param $p_jrn ledger id (jr_id)
 * \param $p_submit contains the submit string
 * \param $p_array (default=null) containing the $_POST
 * \param $p_view_only (default=true) true if we cannot change it (no right or centralized op)
 * \param $p_item number of article (default=4)
 * \param $p_save (default false) if the operation is already recorded
 *
 *
 * \return string with the form, in readonly  or  writable mode
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
  //  $e_date=( ! isset($e_date) ) ? substr($l_date_start,2,8):$e_date;
  $e_date=( ! isset($e_date) ) ? $l_date_start:$e_date;
  // Comment
  $e_comment=(isset($e_comment))?$e_comment:"";

  $r="";
  if ( $pview_only == false) {
    $r.=JS_SEARCH_CARD;
    $r.=JS_CONCERNED_OP;
  }
  // Compute href
  //  $href=basename($_SERVER['PHP_SELF']);
  $href=basename($_SERVER['PHP_SELF']);
  switch ($href)
    {
      // user_jrn.php module "Comptable"
    case 'user_jrn.php':
      $href="user_jrn.php?action=new&p_jrn=$p_jrn";
      break;
      // commercial.php  module "Gestion"
    case 'commercial.php':
      $href="commercial.php?p_action=bank&p_jrn=$p_jrn";
      break;
    default:
      echo_error('user_form_fin.php',__LINE__,'Erreur invalid request uri');
      exit (-1);
    }

  $r.="<FORM NAME=\"form_detail\" enctype=\"multipart/form-data\" ACTION=\"$href\" METHOD=\"POST\">";
  $r.=dossier::hidden();
  $hid=new widget('hidden');
  $r.=$hid->IOValue('p_jrn',$p_jrn);

  $r.='<TABLE>';
  $Date=new widget("js_date");
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
  if ( $e_bank_account != ""  ) {
      $a_client=GetFicheAttribut($p_cn,$e_bank_account);
      if ( $a_client != null)   
		$e_bank_account_label=$a_client['vw_name']."  adresse ".$a_client['vw_addr']."  ".$a_client['vw_cp'];
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
    $Span=new widget ("span");
    $Span->SetReadOnly($pview_only);
    $r.="<TD>".$Span->IOValue("e_bank_account_label",$e_bank_account_label)."</TD>";

    $r.="</TABLE>";
  

  
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
      $tiers_amount=(isset(${"e_other$i"."_amount"}))?round(${"e_other$i"."_amount"},2):0;
  
      $tiers_comment=(isset (${"e_other$i"."_comment"}))?${"e_other$i"."_comment"}:"";
    // If $tiers has a value
    if ( $tiers != ""  ) 
	{
		// retrieve the tva label and name
		$a_fiche=GetFicheAttribut($p_cn, $tiers);
		if ( $a_fiche != null ) {
		$tiers_label=$a_fiche['vw_name'];
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
    $wConcerned->extra=0;
    $wConcerned->table=1;
	$wConcerned->extra2='paid';
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
   $chk=new widget('checkbox');
   $chk->selected=true;
   $r.="Sauvez l'op&eacute;ration ?";
   $r.=$chk->IOValue('opd_save');

   $name=new widget('text');
   $r.='nom de l\' op&eacute;ration ?'.$name->IOValue('predef'); 

 }
 // Set correctly the REQUEST param for jrn_type 
 $h=new widget('hidden');
 $h->name='jrn_type';
 $h->value='FIN';
 $r.=$h->IOValue();

$r.=$p_submit;
$r.="</DIV>";
$r.="</FORM>";

// if view_only is true
//Put the new saldo here (old saldo - operation)
 if ( $pview_only==true)  {
   $solde=round($solde,2);
   $new_solde=round($new_solde,2);

   // if not recorded the new amount must be recalculate
   if ( $p_save == false) {
     $r.=" <b> Ancien Solde = ".$solde." </b><br>";
     $op=$new_solde-$solde;
     $r.="<b> Montant op&eacute;ration =".$op."</b><br>";
     $r.=" <b> Nouveau Solde = ".$new_solde." </b><br>";
   }
   // if recorded the old amount is recalculated
   if ($p_save == true ) {
     $op=$solde-$new_solde;
     $r.=" <b> Ancien Solde = ".$new_solde." </b><br>";
     $r.="<b> Montant op&eacute;ration =".$op."</b><br>";
     $r.=" <b> Nouveau Solde = ".$solde." </b><br>";
   }
 }
 
return $r;


}

/*! 
 **************************************************
 * \brief  Record an invoice in the table jrn &
 *           jrnx
 *        
 *
 * \param $p_cn Database connection
 * \param  $p_array contains all the invoice data
 *           e_date => e : 01.01.2003
 *        e_bank_account => e : 3
 * \param $p_user userid
 * \param $p_jrn current folder (journal)
 * \param array e_other$i, e_other$i_amount, e_other$i_label

 * \return    true on success
 *	  
 */
function RecordFin($p_cn,$p_array,$p_user,$p_jrn) {
  $internal_code="";
  $oid=0;
  echo_debug('user_form_fin.php',__LINE__,"RecordFin");
  foreach ( $p_array as $v => $e)
  {
    ${"$v"}=$e;
  }
  // Get the default period
  $periode=$p_user->GetPeriode();
  
  // Debit = banque
  $poste_bq=GetFicheAttribut($p_cn,$e_bank_account,ATTR_DEF_ACCOUNT);
  try 
    {
      StartSql($p_cn);
      $amount=0.0;  
      // Credit = goods 
      for ( $i = 0; $i < $nb_item;$i++) {
	// if tiers is set and amount != 0 insert it into the database 
	// and quit the loop ?
	if ( ${"e_other$i"."_amount"} == 0 ) continue;
	$poste=GetFicheAttribut($p_cn,${"e_other$i"},ATTR_DEF_ACCOUNT);

	// round it
	${"e_other$i"."_amount"}=round( ${"e_other$i"."_amount"},2);

	$amount+=${"e_other$i"."_amount"};
	// Record a line for the bank
	// Compute the j_grpt
	$seq=NextSequence($p_cn,'s_grpt');
	// Set Internal code and Comment
	$oJrn=new Acc_Ledger($p_cn,$p_jrn);
	$internal=$oJrn->compute_internal_code($seq);


	InsertJrnx($p_cn,'d',$p_user->id,$p_jrn,$poste_bq,$e_date,round(${"e_other$i"."_amount"},2),
		   $seq,$periode,$e_bank_account) ;


	// Record a line for the other account
	$j_id=InsertJrnx($p_cn,'c',$p_user->id,$p_jrn,$poste,$e_date,
			 round(${"e_other$i"."_amount"},2),$seq,$periode,${"e_other$i"});
	
	echo_debug('user_form_fin.php',__LINE__,"   $j_id=InsertJrnx($p_cn,'d',$p_user->id,$p_jrn,$poste,$e_date,".${"e_other$i"}."_amount".",$seq,$periode);");

	$jr_id=InsertJrn($p_cn,$e_date,'',$p_jrn,FormatString(${"e_other$i"."_comment"}),
			 $seq,$periode);

	if ( trim(${"e_concerned".$i}) != "" ) {
	  if ( strpos(${"e_concerned".$i},',') !== 0 )
	    {
	      $aRapt=split(',',${"e_concerned".$i});
	      foreach ($aRapt as $rRapt) {
		if ( isNumber($rRapt) == 1 ) 
		  {
		    InsertRapt($p_cn,$jr_id,$rRapt);
		  }
	      }
	    } else 
	    if ( isNumber(${"e_concerned".$i}) == 1 ) 
	      {
		InsertRapt($p_cn,$jr_id,${"e_concerned$i"});
	      }
	}

    // Set Internal code and Comment
	$Res=ExecSql($p_cn,"update jrn set jr_internal='".$internal."' where ".
	       " jr_grpt_id = ".$seq);

	$comment=$internal_code."  compte : ".GetFicheName($p_cn,$e_bank_account);
	if ( FormatString(${"e_other$i"."_comment"}) == null ) {
	  // Update comment if comment is blank
	  $Res=ExecSql($p_cn,"update jrn set jr_comment='".$comment."' where jr_grpt_id=".$seq);
	}


	if ( $i == 0 )
	  {
	    // first record we upload the files and
	    // keep variable to update other row of jrn
	    if ( isset ($_FILES))
	      $oid=save_upload_document($p_cn,$seq);

	  } else {
	  if ( $oid  != 0 ) 
	    {
	      ExecSql($p_cn,"update jrn set jr_pj=".$oid.", jr_pj_name='".$_FILES['pj']['name']."', ".
		      "jr_pj_type='".$_FILES['pj']['type']."'  where jr_grpt_id=$seq");
	    }
	}
	
      } // for nbitem
	  // Save pre_operatoin
	  // Save the operation
	  if ( isset($_POST['opd_save']) && $_POST['opd_save']=='on' ){
		echo_debug(__FILE__.':'.__LINE__.'- ','save opd');
		$opd=new Pre_op_fin($p_cn);
		$opd->get_post();
		$opd->operation->name=$_POST['predef'];

		$opd->save();
		echo_debug(__FILE__.':'.__LINE__.'- ',"opd = ",$opd);
	  }

    } 
  catch (Exception $e)
    {
       echo '<span class="error">'.
	'Erreur dans l\'enregistrement '.
	__FILE__.':'.__LINE__.' '.
	$e->getMessage();
      Rollback($p_cn);
      exit();
 
    }
  Commit($p_cn);
  return $internal_code;
}
?>
