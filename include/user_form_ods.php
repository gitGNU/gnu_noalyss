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
require_once("constant.php");
require_once("class_widget.php");
require_once("preference.php");
require_once("fiche_inc.php");
require_once("user_common.php");
/*! \file
 * \brief Functions for the ledger of misc. operation
 */
/*! 
 * \brief  Display the miscellaneous operation
 *           Used to show detail, encode a new oper
 *           or update one
 *\param $p_cn database connextion  
 *\param $p_array which can be empty
 * \param $p_jrn the "journal"
 * \param $p_user = $g_user
 * \param $p_submit contains the submit string
 * \param $pview_only if we cannot change it (no right or centralized op)
 * \param $p_article number of article
 * \param $p_saved if true propose to upload a piece
 * gen :
 *	-
 * return: string with the form
 */
function FormODS($p_cn,$p_jrn,$p_periode,$p_submit,$p_array=null,$pview_only=true,$p_article=6,$p_saved=false)
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
  if (  $flag==1 and VerifyOperationDate($p_cn,$p_periode,$e_date)   == null) {
    if ( $pview_only == true) 
      return null;
    else 
      $e_date=substr($l_date_start,2,8);
  }    
  
  $e_comm=(isset($e_comm))?$e_comm:"";
  // Save old value and set a new one

  $r="";

  if ( $pview_only == false) {
    $r.=JS_SEARCH_POSTE;
    $r.=JS_COMPUTE_ODS;
  }

  $r.="<FORM NAME=\"form_detail\" enctype=\"multipart/form-data\" ACTION=\"user_jrn.php?action=new&p_jrn=$p_jrn\" METHOD=\"POST\">";
  $r.='<TABLE>';
  // Date
  $wDate=new widget('text');
  $wDate->SetReadOnly($pview_only);
  $wDate->table=1;
  $r.="<TR>".$wDate->IOValue("e_date",$e_date,'Date')."</TR>";

  // Description
  $Commentaire=new widget("text");
  $Commentaire->table=1;
  $Commentaire->SetReadOnly($pview_only);
  $Commentaire->size=80;
  $r.="<tr>";
  $r.=$Commentaire->IOValue("e_comm",$e_comm,"Description");
  $r.="</tr>";

  include_once("fiche_inc.php");

  // Record the current number of article
  $r.='<INPUT TYPE="HIDDEN" ID="nb_item" name="nb_item" value="'.$p_article.'">';
  $e_comment=(isset($e_comment))?$e_comment:"";


  // Start the div for item to encode
  $r.="<DIV>";
  $r.='<H2 class="info">Op&eacute;rations Diverses</H2>';
  $r.='<TABLE border="0">';
  $r.="<tr>";
  $r.="<th></th>";
  $r.="<th>Compte</th>";
  $r.="<th>Poste</th>";
  $r.="<th>Montant</th>";
  $r.="<th>Cr&eacute;dit ou d&eacute;dit</th>";
  $r.="</tr>";
  $sum_deb=0.0;
  $sum_cred=0.0;

  // for each good
  for ($i=0;$i< $p_article;$i++) {

    $account=(isset(${"e_account$i"}))?${"e_account$i"}:"";

    $lib='<span id="e_account'.$i.'_label"></span>';
    // If $account has a value
    if ( isNumber($account) == 1 ) {
      if ( CountSql($p_cn,"select * from tmp_pcmn where pcm_val=$account") == 0 ) {
	$msg="Poste comptable inexistant !!! ";
	echo_error($msg); echo_error($msg);
	echo "<SCRIPT>alert('$msg');</SCRIPT>";
	$account="";
	if ( $pview_only == true ) return null;
      } else {
	// retrieve the tva label and name
	$lib='<span id="e_account'.$i.'_label">'.GetPosteLibelle($p_cn, $account,1).'</span>';
      }
    }

    ${"e_account$i"."_amount"}=(isset(${"e_account$i"."_amount"}))?abs(round(${"e_account$i"."_amount"},2)):0;
    if ( isNumber(${"e_account$i"."_amount"}) == 0 ) {
      if ( $pview_only==true) {
	$msg="Montant invalide !!! ";
	echo_error($msg); echo_error($msg);
	echo "<SCRIPT>alert('$msg');</SCRIPT>";
	return null;
      }
	${"e_account$i"."_amount"}=0;
    }
    // code
    // Do we need a filter ?
    $l_line=GetJrnProp($_SESSION['g_dossier'],$p_jrn);
    if(  strlen(trim ($l_line['jrn_def_class_cred']) ) > 0 or
	 strlen(trim ($l_line['jrn_def_class_deb']) ) > 0 ) {
      $filter=1;
    }
    else
      $filter=null;
    $W = new widget('js_search_poste');
    $W->readonly=$pview_only;
    $W->label="";
    $W->extra=$p_jrn;
    $W->extra2=$filter;
    //    $r.='<TR>'.InputType("","js_search_poste","e_account".$i,$account,$pview_only,$filter);
    $r.="<TR>".$W->IOValue("e_account".$i, $account); 
    //libelle
    $r.="<td> $lib </td>";
    //amount
    $wAmount=new widget("text");
    $wAmount->table=1;
    $wAmount->SetReadOnly($pview_only);
    $wAmount->javascript=' onChange="checkTotal()"';

    $r.=$wAmount->IOValue("e_account".$i."_amount",${"e_account$i"."_amount"});


    // Type is debit or credit, retrieve the old values
    ${"e_account$i"."_type"}=(isset (${"e_account$i"."_type"}))?${"e_account$i"."_type"}:'d';
    $c_check=( ${"e_account$i"."_type"} == 'c')?"CHECKED":"";
    $d_check=( ${"e_account$i"."_type"} == 'd' )?"CHECKED":"";
    $r.='<td>';
    if ( $pview_only == false ) {
      $r.='  <input type="radio" id="'."e_account"."$i"."_type".'" name="'."e_account"."$i"."_type".'" value="d" '.$d_check.' onChange="checkTotal()">D&eacute;bit ou ';
      $r.='  <input type="radio" id="'."e_account"."$i"."_type".'" name="'."e_account"."$i"."_type".'" value="c" '.$c_check.'  onChange="checkTotal()"> Cr&eacute;dit ';
    }else {
      $r.=(${"e_account$i"."_type"} == 'c' )?"Cr&eacute;dit":"D&eacute;dit";
      $r.='<input type="hidden" name="e_account'.$i.'_type" value="'.${"e_account$i"."_type"}.'">';
    }
    $r.='</td>';
    $r.='</TR>';
    $sum_deb+=(${"e_account$i"."_type"}=='d')?${"e_account$i"."_amount"}:0;
    $sum_cred+=(${"e_account$i"."_type"}=='c')?${"e_account$i"."_amount"}:0;
  } // End for 

  $r.="</TABLE>";

 if ( $pview_only==true && $p_saved==false) {
// check for upload piece
   $file=new widget("file");
   $file->table=1;
   $r.="<hr>";
   $r.= "<table>"; 
   $r.="<TR>".$file->IOValue("pj","","Pi&egrave;ce justificative")."</TR>";
   $r.="</table>";
   $r.="<hr>";
 } else {
  $r.= '<div class="info">
    D&eacute;bit = <span id="totalDeb"></span>
    Cr&eacute;dit = <span id="totalCred"></span>
    Difference = <span id="totalDiff"></span>
</div>
    ';
 }
  // Set correctly the REQUEST param for jrn_type 
  $h=new widget('hidden');
  $h->name='jrn_type';
  $h->value='ODS';
  $r.=$h->IOValue();

  $r.=$p_submit;
  //  $r.="</DIV>";
  $r.="</FORM>";
  //TODO if view only show total
  $tmp= abs($sum_deb-$sum_cred);
  echo_debug('user_form_ods.php',__LINE__,"Diff = ".$tmp);
  if ( abs($sum_deb-$sum_cred) > 0.0001  and $pview_only==true) {
    $msg=sprintf("Montant non correspondant credit = %.5f debit = %.5f diff = %.5f",
		 $sum_cred,$sum_deb,$sum_cred-$sum_deb);
    echo "<script> alert('$msg'); </script>";
    return null;
  }

  // Verify that we have a non-null operation
  if ($pview_only==true and $sum_cred == 0)
  {
    $msg=sprintf("Montant null");
    echo "<script> alert('$msg'); </script>";
    return null;
  }
  
  return $r;


}

/*! 
 **************************************************
 * \brief  Record an buy in the table jrn &
 *           jrnx
 *
 * 
 * \param  $p_cn Database connection
 * \param $p_array contains all the  data
 * e_date => e : 01.01.2003
 * nb_item => e : 3
 * e_account0 => e : 6
 * e_account0_amount=>e:1
 *  - $p_user userid
 *  - $p_jrn current folder (journal)
 *
 * \return
 *	      true on success
 */
function RecordODS($p_cn,$p_array,$p_user,$p_jrn)
{
  foreach ( $p_array as $v => $e)
  {
    ${"$v"}=$e;
  }
  // Get the default period
  $periode=$p_user->GetPeriode();
  $amount=0.0;
  // Computing total customer

  $sum_deb=0.0;
  $sum_cred=0.0;

	// Compute the j_grpt
  $seq=NextSequence($p_cn,'s_grpt');
  try 
    {
      StartSql($p_cn);
      // store into the database
      for ( $i = 0; $i < $nb_item;$i++) {
		if ( isNumber(${"e_account$i"}) == 0 ) continue;
		$sum_deb+=(${"e_account$i"."_type"}=='d')?round(${"e_account$i"."_amount"},2):0;
		$sum_cred+=(${"e_account$i"."_type"}=='c')?round(${"e_account$i"."_amount"},2):0;
		
		if ( ${"e_account$i"."_amount"} == 0 ) continue;
		${"e_account$i"."_amount"}=round(${"e_account$i"."_amount"},2);
		$j_id=InsertJrnx($p_cn,${"e_account$i"."_type"},$p_user->id,$p_jrn,${"e_account$i"},$e_date,${"e_account$i"."_amount"},$seq,$periode);
	  }

	  InsertJrn($p_cn,$e_date,"",$p_jrn,$e_comm,$seq,$periode) ;
	  
      // Set Internal code and Comment
      $internal_code=SetInternalCode($p_cn,$seq,$p_jrn);
      if ( $e_comm=="" ) {
		// Update comment if comment is blank
	$Res=ExecSql($p_cn,"update jrn set jr_comment='".$internal_code."' where jr_grpt_id=".$seq);
      }
      if ( isset ($_FILES))
		save_upload_document($p_cn,$seq);
	
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
