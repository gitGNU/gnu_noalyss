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
    $r.=sprintf("<input type=\"button\" value=\"Tva\" 
    	onClick=\"
       	           ShowTva('%s','%s');\"></TD>",
		$l_sessid,$p_name);
    return $r;
  }

  return $r;

}
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


  $e_ech=(isset($e_ech))?$e_ech:"";
  $e_comment=(isset($e_comment))?$e_comment:"";

  $r="";
  if ( $pview_only == false) {
    $r.=JS_SEARCH_CARD;
    $r.=JS_CONCERNED_OP;
  }
  //	  $r.='<FORM METHOD="POST" enctype="multipart/form-data" ACTION="user_jrn.php?action=record">';
  $r.="<FORM NAME=\"form_detail\" enctype=\"multipart/form-data\" ACTION=\"user_jrn.php?action=new&p_jrn=$p_jrn\" METHOD=\"POST\">";
  $r.='<TABLE>';
  $r.='<TR>'.InputType("Date ","Text","e_date",$e_date,$pview_only).'</TR>';


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
  
  //  $r.='<TR>'.InputType("Banque","js_search","e_bank_account",$e_bank_account,$pview_only,FICHE_TYPE_FIN).'</TR>';
    $W1=new widget("js_search");
    $W1->readonly=$pview_only;
    $W1->label="Banque";
    $W1->name="e_bank_account";
    $W1->value=$e_bank_account;
    $W1->extra=FICHE_TYPE_FIN;  // credits
    $W1->extra2=$p_jrn;
    $r.="<TR>".$W1->IOValue()."</TD>";

  $r.="</TABLE>";
  
  $r.=       InputType(""       ,"span"   ,"e_bank_account_label",$e_bank_account_label,false).'</TD>';
  
  $e_comment=(isset($e_comment))?$e_comment:"";
  
  // ComputeBanqueSaldo
  // cred = nï¿½ !!!
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
  $r.="<th>D&eacute;omination</TH>";
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
    // Compute the string to pass to InputType
    // $f=FICHE_TYPE_CLIENT.",".FICHE_TYPE_FOURNISSEUR.",".FICHE_TYPE_ADM_TAX.",".FICHE_TYPE_FIN;
    //    $r.='<TR>'.InputType("","js_search","e_other".$i,$tiers,$pview_only,'cred');
    $W1=new widget("js_search");
    $W1->label="";
    $W1->name="e_other".$i;
    $W1->value=$tiers;
    $W1->extra='cred';  // credits
    $W1->extra2=$p_jrn;
    $W1->readonly=$pview_only;
    $r.="<TR>".$W1->IOValue()."</TD>";

    $r.=InputType("","span", "e_other$i"."_label", $tiers_label,$pview_only);
    // Comment
    $r.=InputType("","Text","e_other$i"."_comment",$tiers_comment,$pview_only);
    // amount
    $r.=InputType("","TEXT","e_other$i"."_amount",$tiers_amount,$pview_only);
    ${"e_concerned".$i}=(isset(${"e_concerned".$i}))?${"e_concerned".$i}:"";
    $r.=InputType("","js_concerned","e_concerned".$i,${"e_concerned".$i},$pview_only);
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
   $r.="<TR>".$file->IOValue("pj","","Pi&eagrave;ce justificative")."</TR>";
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
    //    $type=( ${"e_other$i"."_amount"} < 0 )?'d':'c';

    // Compute the j_grpt
    $seq=NextSequence($p_cn,'s_grpt');

    if ( InsertJrnx($p_cn,'d',$p_user->id,$p_jrn,$poste_bq,$e_date,round(${"e_other$i"."_amount"},2),$seq,$periode) == false ) {
      $Rollback($p_cn);exit("error __FILE__ __LINE__");
    }


    // Record a line for the other account
    //    $type=( ${"e_other$i"."_amount"} < 0 )?'c':'d';
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
/* function FormODS($p_cn,$p_jrn,$p_user,$p_array=null,$pview_only=true,$p_article=1)
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
  
  $e_ech=(isset($e_ech))?$e_ech:"";
  $e_comment=(isset($e_comment))?$e_comment:"";
  // Save old value and set a new one

  $r="";
  if ( $pview_only == false) {
    $r.=JS_SEARCH_POSTE;
  }
  $r.="<FORM NAME=\"form_detail\" enctype=\"multipart/form-data\" ACTION=\"user_jrn.php?action=new&p_jrn=$p_jrn\" METHOD=\"POST\">";
  $r.='<TABLE>';
  $r.='<TR>'.InputType("Date ","Text","e_date",$e_date,$pview_only).'</TR>';
  $r.='<TR>'.InputType("Description","Text_big","e_comment",$e_comment,$pview_only).'</TR>';
  include_once("fiche_inc.php");

  // Record the current number of article
  $r.='<INPUT TYPE="HIDDEN" name="nb_item" value="'.$p_article.'">';
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
	if ( $pview_only == true ) return null;
      } else {
	// retrieve the tva label and name
	$lib=GetPosteLibelle($p_cn, $account,1);
      }
    }
    ${"e_account$i"."_amount"}=(isset(${"e_account$i"."_amount"}))?${"e_account$i"."_amount"}:0;
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
    $l_line=GetJrnProperty($p_cn,$p_jrn);
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
    $r.=InputType("","text","e_account".$i."_amount",${"e_account$i"."_amount"},$pview_only);


    // Type is debit or credit, retrieve the old values
    ${"e_account$i"."_type"}=(isset (${"e_account$i"."_type"}))?${"e_account$i"."_type"}:'d';
    $c_check=( ${"e_account$i"."_type"} == 'c')?"CHECKED":"";
    $d_check=( ${"e_account$i"."_type"} == 'd' )?"CHECKED":"";
    $r.='<td>';
    if ( $pview_only == false ) {
      $r.='  <input type="radio" name="'."e_account"."$i"."_type".'" value="d" '.$d_check.'> D&eacute;bit ou ';
      $r.='  <input type="radio" name="'."e_account"."$i"."_type".'" value="c" '.$c_check.'> Cr&eacute;dit ';
    }else {
      $r.=(${"e_account$i"."_type"} == 'c' )?"Cr&eacute;dit":"D&eacute;dit";
      $r.='<input type="hidden" name="e_account'.$i.'_type" value="'.${"e_account$i"."_type"}.'">';
    }
    $r.='</td>';
    $r.='</TR>';
    $sum_deb+=(${"e_account$i"."_type"}=='d')?${"e_account$i"."_amount"}:0;
    $sum_cred+=(${"e_account$i"."_type"}=='c')?${"e_account$i"."_amount"}:0;
  }

  $r.="</TABLE>";

 if ( $pview_only==true && $p_saved==false) {
// check for upload piece
   $file=new widget("file");
   $file->table=1;
   $r.="<hr>";
   $r.= "<table>"; 
   $r.="<TR>".$file->IOValue("pj","","Pièce justificative")."</TR>";
   $r.="</table>";
   $r.="<hr>";
 }

  $r.=$p_submit;
  //  $r.="</DIV>";
  $r.="</FORM>";
  //TODO if view only show total
  $tmp= abs($sum_deb-$sum_cred);
  echo_debug(__FILE__,__LINE__,"Diff = ".$tmp);
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
  $periode=$p_user->GetPeriode();
  $amount=0.0;
  // Computing total customer

  $sum_deb=0.0;
  $sum_cred=0.0;

	// Compute the j_grpt
  $seq=NextSequence($p_cn,'s_grpt');

  StartSql($p_cn);
  // store into the database
  for ( $i = 0; $i < $nb_item;$i++) {
    if ( isNumber(${"e_account$i"}) == 0 ) continue;
    $sum_deb+=(${"e_account$i"."_type"}=='d')?round(${"e_account$i"."_amount"},2):0;
    $sum_cred+=(${"e_account$i"."_type"}=='c')?round(${"e_account$i"."_amount"},2):0;

    if ( ${"e_account$i"."_amount"} == 0 ) continue;
    if ( ($j_id=InsertJrnx($p_cn,${"e_account$i"."_type"},$p_user->id,$p_jrn,${"e_account$i"},$e_date,${"e_account$i"."_amount"},$seq,$periode)) == false ) {
      $Rollback($p_cn);exit("error __FILE__ __LINE__");}
  }

  if ( InsertJrn($p_cn,$e_date,"",$p_jrn,$e_comment,$sum_deb,$seq,$periode) == false ) {
    $Rollback($p_cn);exit("error __FILE__ __LINE__");}

  // Set Internal code and Comment
  $internal_code=SetInternalCode($p_cn,$seq,$p_jrn);
  if ( $e_comment=="" ) {
    // Update comment if comment is blank
    $Res=ExecSql($p_cn,"update jrn set jr_comment='".$internal_code."' where jr_grpt_id=".$seq);
  }
  if ( isset ($_FILES))
    save_upload_document($p_cn,$seq);

  Commit($p_cn);
  return $internal_code;
}
