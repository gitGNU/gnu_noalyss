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

include_once("postgres.php");
require_once("class_document.php");
/*! \file
 * \brief Common functions 
 */
/*! 
 **************************************************
 *\brief  Return the rate of the p_tva_id
 *        
 * 
 * \param $p_cn database connection
 * \param $p_tva_id tva.tva_id
 *
 * \return
 *       an array containing the rate and the label
 *       the tva rate or null if a problem occured
 */

function GetTvaRate($p_cn,$p_tva_id) {
  // $p_tva_id is an empty string, returns 0
  if (strlen(trim($p_tva_id))==0) return 0;

  // Get vat info from the database
  $Res=ExecSql($p_cn,"select tva_id,tva_rate,tva_label from tva_rate where tva_id=".$p_tva_id);
  if (pg_NumRows($Res) == 0 ) return null;

  $r=pg_fetch_array($Res,0);
  return $r;

}
/*!
 **************************************************
 *\brief  Compute the vat,
 *           the fiche.f_id are in a_fiche
 *           the quantity in a_quant
 *           
 *        
 *  
 * \param $p_cn database connection
 * \param $a_fiche fiche id array
 * \param $a_quantity array 
 * \param $a_price array 
 * \param $ap_vat Array of tva id
 * \param $a_amount_tva for the expense, if the tva amount is given
 * \param $all = false if we reduce VAT
 * \return: array
 *       a[tva_id] =  amount vat
 */
function ComputeTotalVat($p_cn,	$a_fiche,$a_quant,$a_price,$ap_vat,$a_vat_amount=null,$all=false ) {
echo_debug('user_common.php',__LINE__,"ComputeTotalVat $a_fiche $a_quant $a_price");
 foreach ( $a_fiche as $t=>$el) {
   echo_debug('user_common.php',__LINE__,"t $t e $el");
 }
 $r=null;
// foreach goods 
//--
 foreach ( $a_fiche as $idx=>$element) {
   echo_debug ('user_common.php',__LINE__,"idx $idx element $element");
  // if the card id is null or empty 
    if (  strlen(trim($element))==0) continue;

    // Get the tva_id
    if ( $ap_vat != null and
	 isNumber($ap_vat[$idx])== 1 and $ap_vat[$idx] != -1 ) 
      {
	$tva_id=$ap_vat[$idx];
	echo_debug('user_common',__LINE__,' tva_id is given');
	echo_debug('user_common',__LINE__,$ap_vat);
      }
    else
      {
	$tva_id=GetFicheAttribut($p_cn,$element,ATTR_DEF_TVA);
	echo_debug('user_common',__LINE__,'retrieve tva_id');
      }
    echo_debug('user_common',__LINE__,"tva id $tva_id");
    if ( $tva_id == null ) continue;
    // for each fiche find the tva_rate and tva_id
    $a_vat=GetTvaRate($p_cn,$tva_id);
   
	// Get the attribut price of the card(fiche)
    if ( $a_vat != null  and  $a_vat['tva_id'] != "" ) 
      {  
	$flag=true;
	$a=$a_vat['tva_id'];
	// Compute vat for this item
	$vat_amount=round($a_price[$idx]*$a_vat['tva_rate']*$a_quant[$idx],2);

	// if a vat amount is given
	if ( $a_vat_amount != null && 
	     $a_vat_amount[$idx] != 0 )
	  $vat_amount= $a_vat_amount[$idx] ;
	echo_debug('user_common',__LINE__,"vat amount = $vat_amount");
	// only the deductible vat
	if ( $all == false ) 
	  {
	    //variable containing the nd part 
	    // used when a card has both special rule for vat 
	    $nd1=0;
	    // if a part is not deductible then reduce vat_amount
	    $nd=GetFicheAttribut($p_cn,$a_fiche[$idx],ATTR_DEF_TVA_NON_DEDUCTIBLE);
	    if ( $nd != null && strlen(trim($nd)) != 0 && $nd != 0 )
	      {
		// if tva amount is given we do not compute it
		if ( $a_vat_amount != null && 
		     $a_vat_amount[$idx] != 0 )
		  $nd_amount=round($vat_amount*$nd,2);
		else
		  $nd_amount=round($a_price[$idx]*$a_vat['tva_rate']*$a_quant[$idx]*$nd,2);


		// problem with round
		$vat_amount=$vat_amount-$nd_amount;
		echo_debug('user_common.php',__LINE__,
			   "A - TVA Attr fiche [$nd] nd amount [ $nd_amount ]".
			   "vat amount [ $vat_amount]");
		$flag=false;
		// save nd into nd1
		$nd1=$nd;
	      }	
	    // if a part is not deductible then reduce vat_amount
	    $nd=GetFicheAttribut($p_cn,$a_fiche[$idx],ATTR_DEF_TVA_NON_DEDUCTIBLE_RECUP);
	    if ( $nd != null && strlen(trim($nd)) != 0 && $nd != 0 )
	      {
		// if tva amount is given we do not compute it
		if ( $a_vat_amount != null && 
		     $a_vat_amount[$idx] != 0 )
		  $nd_amount2=round($a_vat_amount[$idx]*$nd,2);
		else
		  $nd_amount2=round($a_price[$idx]*$a_vat['tva_rate']*$a_quant[$idx]*$nd,2);
		
		$vat_amount=$vat_amount-$nd_amount2;
		// when using both vat, their sum cannot exceed 1, if = 1 then vat = 0
		if ( ($nd+$nd1) == 1)
		  $vat_amount=0;
		echo_debug('user_common.php',__LINE__,
			   "B - TVA Attr fiche [$nd] nd amount [ $nd_amount2 ]".
			   "vat amount [ $vat_amount]");
		
		$flag=false;
	      }	
	  }
	
	$r[$a]=isset ( $r[$a] )?$r[$a]+$vat_amount:$vat_amount; 
      }
    
 }
 echo_debug('user_common.php',__LINE__," return ".var_export($r,true));
 return $r;
 
 
}

/*!   
 **************************************************
 *\brief  Compute the vat for only one elt,
 *           the fiche.f_id are in p_fiche
 *           the quantity in p_quant
 *           
 *        
 *
 * \param $p_cn database connection
 * \param $p_fiche fiche id int
 * \param $p_quantity int
 * \param $p_price float 
 * \param $p_tva_id
 *	-
 * \return the amount of vat
 */
function ComputeVat($p_cn,	$p_fiche,$p_quant,$p_price,$p_vat ) 
{
  echo_debug('user_common.php',__LINE__,"function ComputeVat($p_cn,$p_fiche,$p_quant,$p_price,$p_vat )");
  // Get the tva_id
  if ( $p_vat != null and  isNumber($p_vat)== 1 and $p_vat != -1)
    $tva_id=$p_vat;
  else
    $tva_id=GetFicheAttribut($p_cn,$p_fiche,ATTR_DEF_TVA);
 
  echo_debug('user_common',__LINE__,"ComputeVat tva id = $tva_id"); 
  if ( $tva_id == null  ) return -1;
  // find the tva_rate and tva_id
  $a_vat=GetTvaRate($p_cn,$tva_id);
  $vat_amount=null;
  // Get the attribut price of the card(fiche)
  if ( $a_vat != null  and  $a_vat['tva_id'] != "" ) 
    {
      $a=$a_vat['tva_id'];
      $vat_amount=$p_price*$a_vat['tva_rate']*$p_quant;
    } 
  echo_debug('user_common',__LINE__,'return '.round($vat_amount,2));
  return round($vat_amount,2);
  
  
}


/*!   
 **************************************************
 *\brief  Get the account of tva_rate.tva_poste
 *        return the credit or the debit account
 * \param $p_cn connection
 * \param $p_tva_id tva_rate.tva_id
 * \param $p_cred       type ( d or credit)
 *
 * \return
 *        return the credit or the debit account
 *        null if error
 */
function GetTvaPoste($p_cn,$p_tva_id,$p_cred) {
	$Res=ExecSql($p_cn,"select tva_poste from tva_rate where tva_id=$p_tva_id");
	if ( pg_NumRows($Res) == 0 ) return null;
	$a=pg_fetch_array($Res,0);
	list ($deb,$cred)=split(",",$a['tva_poste']);
	if ( $p_cred=='c' ) return $cred;
	if ($p_cred=='d') return $deb;
	echo_error ("Invalid $p_cred in GetTvaRate");
	return null;
}




/*!   InsertJrnx($p_cn,$p_type,$p_user,$p_jrn,$p_poste,$p_date,$p_amount,$p_grpt,$p_periode
 **************************************************
 *\brief  Insert into the table Jrn
 *        
 *  
 * \param $p_cn database connection
 * \param $p_type debit or credit
 * \param $p_user the current user
 * \param $p_jrn the current 'journal' (folder)
 * \param $p_poste the account
 * \param $p_date
 * \param $p_amount amount to insert
 * \param $p_periode the concerned periode
 *
 * \return  nothing
 *
 */

function InsertJrnx($p_cn,$p_type,$p_user,$p_jrn,$p_poste,$p_date,$p_amount,$p_grpt,$p_periode,$p_qcode="")
{
  echo_debug ('user_common.php',__LINE__,"InsertJrnx param 
	    type = $p_type p_user $p_user 
            p_date $p_date p_poste $p_poste 
            p_amount $p_amount p_grpt = $p_grpt p_periode = $p_periode");

  if ( $p_amount == 0) return true;

  $debit=($p_type=='c')?'false':'true';

  // if negative value the operation is inversed
  if ( $p_amount < 0 ) {
    $debit=($debit=='false')?'true':'false';
  }

  $sql=sprintf("select insert_jrnx
		 ('%s',abs(%.2f),%d,%d,%d,%s,'%s',%d,upper('%s'))",
	          $p_date,round($p_amount,2),$p_poste,$p_grpt,$p_jrn,$debit,$p_user,$p_periode,$p_qcode);

  echo_debug('user_common.php',__LINE__,"InsertJrnx $sql");
  $Res=ExecSql($p_cn,$sql);
  if ( $Res==false) return $Res;
  return GetSequence($p_cn,'s_jrn_op');

}
/*!   InsertJrn($p_cn,$p_date,$p_jrn,$p_comment,$p_amount,$p_grpt,$p_periode
 **************************************************
 *\brief  Insert into the table Jrn, the amount is computed from jrnx thanks the 
 *        group id ($p_grpt)
 *        
 * \param $p_cn database connection
 * \param $p_date date
 * \param $p_jrn the current 'journal' (folder)
 * \param $p_poste the account
 * \param $p_periode the concerned periode
 * \param $p_comment comment
 *
 * \return  nothing
 *  
 */

function InsertJrn($p_cn,$p_date,$p_echeance,$p_jrn,$p_comment,$p_grpt,$p_periode)
{
	echo_debug ('user_common.php',__LINE__,"InsertJrn param 
	    p_date $p_date p_poste $p_comment p_amount  p_grpt = $p_grpt p_periode = $p_periode p_echeance = $p_echeance
comment = $p_comment");
	$p_comment=FormatString($p_comment);

	if ( $p_echeance == "" or $p_echeance==null) {
		$p_echeance='null';
	} else {
		$p_echeance=sprintf("to_date('%s','DD.MM.YYYY')",$p_echeance);
	}
	// retrieve the value from jrnx
	// 
	$montant_deb=getDBValue($p_cn,"select sum(j_montant) from jrnx where j_debit='t' and j_grpt=$p_grpt");
	$montant_cred=getDBValue($p_cn,"select sum(j_montant) from jrnx where j_debit='f' and j_grpt=$p_grpt");
	echo_debug('InsertJrn',__LINE__,"debit = $montant_deb credit  = $montant_cred ");

	$amount=-1.0000;
	if ( $montant_deb == $montant_cred ) {
	  $amount=$montant_deb;
	} else {
	  echo "Erreur : balance incorrecte : d&eacute;bit = $montant_deb cr&eacute;dit = $montant_cred";
	  return false;
	}
	// if amount == -1then the triggers will throw an error
	// 
	$sql=sprintf("insert into jrn (jr_def_id,jr_montant,jr_comment,jr_date,jr_ech,jr_grpt_id,jr_tech_per)
	         values ( %d,abs(%.2f),'%s',to_date('%s','DD.MM.YYYY'),%s,%d,%d)",
		     $p_jrn, $amount,$p_comment,$p_date,$p_echeance,$p_grpt,$p_periode);


	echo_debug('user_common.php',__LINE__,"InsertJrn $sql");
	$Res=ExecSql($p_cn,$sql);				 
	if ( $Res == false)  return false;
	return GetSequence($p_cn,'s_jrn');
}
/*!   ListJrn($p_cn,$p_jrn,$p_wherel)
 ************************************************************************************
 *\brief  show all the lines of the asked jrn, uses also the $_GET['o'] for the sort
 *        
 * 
 * \param $p_cn database connection
 * \param $p_jrn jrn_id jrn.jrn_def_id
 * \param $p_where the sql query where clause
 * \param $p_array param. for a search
 * \param $p_value offset
 * \param $p_paid value : 0 nothing is shown, 1 check box; 2 check_box disable
 * \return array (entryCount,generatedHTML);
 * 
 */
function ListJrn($p_cn,$p_jrn,$p_where="",$p_array=null,$p_value=0,$p_paid=0)
{

  include_once("central_inc.php");
  $limit=($_SESSION['g_pagesize']!=-1)?" LIMIT ".$_SESSION['g_pagesize']:"";
  $offset=($_SESSION['g_pagesize']!=-1)?" OFFSET ".$p_value:"";
  $order="  order by jr_date_order asc,jr_internal asc";
  // Sort
  $url=CleanUrl();
  $image_asc='<IMAGE SRC="image/down.png" border="0" >';
  $image_desc='<IMAGE SRC="image/up.png" border="0">';
  $image_sel_desc='<IMAGE SRC="image/select1.png">';
  $image_sel_asc='<IMAGE SRC="image/select2.png">';
  
  $sort_date="<th>  <A class=\"mtitle\" HREF=\"?$url&o=da\">$image_asc</A>Date <A class=\"mtitle\" HREF=\"?$url&o=dd\">$image_desc</A></th>";
  $sort_description="<th>  <A class=\"mtitle\" HREF=\"?$url&o=ca\">$image_asc</A>Description <A class=\"mtitle\" HREF=\"?$url&o=cd\">$image_desc</A></th>";
  $sort_amount="<th>  <A class=\"mtitle\" HREF=\"?$url&o=ma\">$image_asc</A>Montant <A class=\"mtitle\" HREF=\"?$url&o=md\">$image_desc</A></th>";
$sort_echeance="<th>  <A class=\"mtitle\" HREF=\"?$url&o=ea\">$image_asc</A>Echéance <A class=\"mtitle\" HREF=\"?$url&o=ed\">$image_desc</A> </th>";
  // if an order is asked
  if ( isset ($_GET['o']) ) 
    {
      switch ($_GET['o'])
	{
	case 'da':
	  // date asc
	  $sort_date="<th>$image_sel_asc Date <A class=\"mtitle\" HREF=\"?$url&o=dd\">$image_desc</A></th>";
	  $order=" order by jr_date_order asc ";
	  break;
	case 'dd':
	  $sort_date="<th> <A class=\"mtitle\" HREF=\"?$url&o=da\">$image_asc</A> Date $image_sel_desc</th>";
	  // date desc
	  $order=" order by jr_date_order desc ";
	  break;
	case 'ma':
	  // montant asc
	  $sort_amount="<th> $image_sel_asc Montant <A class=\"mtitle\" HREF=\"?$url&o=md\">$image_desc</A></th>";
	  $order=" order by jr_montant asc ";
	  break;
	case 'md':
	  // montant desc
	  $sort_amount="<th>  <A class=\"mtitle\" HREF=\"?$url&o=ma\">$image_asc</A>Montant $image_sel_desc</th>";
	  $order=" order by jr_montant desc ";
	  break;
	case 'ca':
	  // jr_comment asc
	  $sort_description="<th> $image_sel_asc Description <A class=\"mtitle\" HREF=\"?$url&o=cd\">$image_desc</A></th>";
	  $order=" order by jr_comment asc ";
	  break;
	case 'cd':
	  // jr_comment desc
	  $sort_description="<th>  <A class=\"mtitle\" HREF=\"?$url&o=ca\">$image_asc</A>Description $image_sel_desc</th>";
	  $order=" order by jr_comment desc ";
	  break;
	case 'ea':
	  // jr_comment asc
	  $sort_echeance="<th> $image_sel_asc Echeance <A class=\"mtitle\" HREF=\"?$url&o=ed\">$image_desc</A></th>";
	  $order=" order by jr_ech asc ";
	  break;
	case 'ed':
	  // jr_comment desc
	  $sort_echeance="<th>  <A class=\"mtitle\" HREF=\"?$url&o=ea\">$image_asc</A> Echeance $image_sel_desc</th>";
	  $order=" order by jr_ech desc ";
	  break;

	}
    }
  // set a filter for the FIN 
  $a_parm_code=GetArray($p_cn,"select p_value from parm_code where p_code in ('BANQUE','VIREMENT_INTERNE','COMPTE_COURANT','CAISSE')");
  $sql_fin="(";
  $or="";
  foreach ($a_parm_code as $code) {
    $sql_fin.="$or j_poste like '".$code['p_value']."%'";
    $or=" or ";
  }
  $sql_fin.=")";

  if ( $p_array == null ) {
   $sql="select jr_id	,
			jr_montant,
			jr_comment,
			jr_ech,
			to_char(jr_date,'DD.MM.YYYY') as jr_date,
                        jr_date as jr_date_order,
			jr_grpt_id,
			jr_rapt,
			jr_internal,
			jrn_def_id,
			jrn_def_name,
			jrn_def_ech,
			jrn_def_type,
                        jr_valid,
                        jr_tech_per,
                        jr_pj_name,
                        p_closed
		       from 
			jrn 
                            join jrn_def on jrn_def_id=jr_def_id 
                            join parm_periode on p_id=jr_tech_per
                       $p_where 
	                 $order";
  }
  if ( $p_array != null ) {
    // Construction Query 
    foreach ( $p_array as $key=>$element) {
      ${"l_$key"}=$element;
    }
    $sql="select jr_id	,
		jr_montant,
		jr_comment,
		jr_ech,
		to_char(jr_date,'DD.MM.YYYY') as jr_date,
                jr_date as jr_date_order,
		jr_grpt_id,
		jr_rapt,
		jr_internal,
		jrn_def_id,
		jrn_def_name,
		jrn_def_ech,
		jrn_def_type,
                jr_valid,
                jr_tech_per,
                jr_pj_name,
                p_closed
		      from 
                jrn join jrn_def on jrn_def_id=jr_def_id 
                    join parm_periode on p_id=jr_tech_per
                ";
    $jrn_sql=($p_jrn =0)?"1=1":"jrn_def_id=$p_jrn ";
    $l_and=" where ";
    // amount
    // remove space
    $l_s_montant=trim($l_s_montant);
    // replace comma by dot
    $l_s_montant=str_replace(',','.',$l_s_montant);
    echo_debug('user_common',__LINE__,"l_s_montant $l_s_montant");

    if ( ereg("^[0-9]+$", $l_s_montant) || ereg ("^[0-9]+\.[0-9]+$", $l_s_montant) ) 
    {
      $sql.=$l_and."  jr_montant $l_mont_sel $l_s_montant";
      $l_and=" and ";
    }
    // date
    if ( isDate($l_date_start) != null ) 
    {
      $sql.=$l_and." jr_date >= to_date('".$l_date_start."','DD.MM.YYYY')";
      $l_and=" and ";
    }
    if ( isDate($l_date_end) != null ) {
      $sql.=$l_and." jr_date <= to_date('".$l_date_end."','DD.MM.YYYY')";
      $l_and=" and ";
    }
    // comment
    $l_s_comment=FormatString($l_s_comment);
    if ( $l_s_comment != null ) 
    {
      $sql.=$l_and." upper(jr_comment) like upper('%".$l_s_comment."%') ";
      $l_and=" and ";
    }
    // internal
    $l_s_internal=FormatString($l_s_internal);
    if ( $l_s_internal != null ) {
      $sql.=$l_and."  jr_internal like ('%$l_s_internal%')  ";
      $l_and=" and ";
    }
    // Poste
    $l_poste=FormatString($l_poste);
    if ( $l_poste != null ) {
      $sql.=$l_and."  jr_grpt_id in (select j_grpt 
             from jrnx where j_poste like '$l_poste' )  ";
      $l_and=" and ";
    }
    // Quick Code
    if ( $l_qcode != null ) 
      {
	$l_qcode=FormatString($l_qcode);
	$sql.=$l_and."  jr_grpt_id in ( select j_grpt from 
             jrnx where trim(j_qcode) = upper(trim('$l_qcode')))";
	$l_and=" and ";
      }
    // if not admin check filter 
    $User=new cl_user(DbConnect());
    $User->Check();
    if ( $User->admin == 0 ) 
    {
      $sql.=$l_and." jr_def_id in ( select uj_jrn_id ".
	" from user_sec_jrn where ".
	" uj_login='".$_SESSION['g_user']."'".
	" and uj_priv in ('R','W'))";
    }
    $sql.=$order;
  }// p_array != null
  // Count 
  $count=CountSql($p_cn,$sql);
  // Add the limit 
  $sql.=$limit.$offset;

  // Execute SQL stmt
  $Res=ExecSql($p_cn,$sql);

  //starting from here we can refactor, so that instead of returning the generated HTML, 
  //this function returns a tree structure.
  
  $r="";

  $r.=JS_VIEW_JRN_CANCEL;
  $r.=JS_VIEW_JRN_MODIFY;

  $Max=pg_NumRows($Res);

  //TODO: correct this message. 
  if ($Max==0) return array(0,"Aucun enregistrement trouvé");

  $r.='<table style="width:100%;border:solid blue 2px ;border-style:outset;">';
  $l_sessid=$_REQUEST['PHPSESSID'];
  $r.="<tr class=\"even\">";
  $r.="<th> Internal</th>";
  $r.=$sort_date;
  $r.=$sort_echeance;
  $r.=$sort_description;
  $r.=$sort_amount;
  // if $p_paid is not equal to 0 then we have a paid column
  if ( $p_paid != 0 ) 
    {
      $r.="<th> Pay&eacute;</th>";
    }
  $r.="<th>Op. Concernée</th>";
  $r.="<th>Document</th>";
  $r.="</tr>";

  for ($i=0; $i < $Max;$i++) {

    //STAN the rows here must be stored in an array
    
    $row=pg_fetch_array($Res,$i);
    
    if ( $i % 2 == 0 ) $tr='<TR class="odd">'; 
		else $tr='<TR class="even">';
    $r.=$tr;
    //internal code
	// button  modify
    $r.="<TD>";
  // If url contains
    $r.=sprintf('<A class="detail" HREF="javascript:modifyOperation(\'%s\',\'%s\',\'%s\')" >%s</A>',
		$row['jr_id'], $l_sessid, $p_jrn, $row['jr_internal']);
    $r.="</TD>";
    // date
    $r.="<TD>";
    $r.=$row['jr_date'];
    $r.="</TD>";
    // echeance
    $r.="<TD>";
    $r.=$row['jr_ech'];
    $r.="</TD>";
    
    // comment
    $r.="<TD>";
    $r.=$row['jr_comment'];
    $r.="</TD>";
    
    // Amount
    // If the ledger is financial :
    // the credit must be negative and written in red
    $positive=0;

    // Check ledger type : 
     if (  $row['jrn_def_type'] == 'FIN' ) 
     {
       $positive = CountSql($p_cn,"select * from jrn inner join jrnx on jr_grpt_id=j_grpt ".
 			   " where jr_id=".$row['jr_id']." and $sql_fin ".
 			   " and j_debit='f'");
     }
    $r.="<TD align=\"right\">";
    //STAN $positive always == 0
     $r.=( $positive != 0 )?"<font color=\"red\">  - ".sprintf("%8.2f",$row['jr_montant'])."</font>":sprintf("%8.2f",$row['jr_montant']);
    $r.="</TD>";


    // Show the paid column if p_paid is not null
    if ( $p_paid !=0 )
      {
	$w=new widget("checkbox");
	$w->name="rd_paid".$row['jr_id'];
	$w->selected=($row['jr_rapt']=='paid')?true:false;
	// if p_paid == 2 then readonly
	$w->readonly=( $p_paid == 2)?true:false;
	$h=new widget("hidden");
	$h->name="set_jr_id".$row['jr_id'];
	$r.='<TD>'.$w->IOValue().$h->IOValue().'</TD>';
      }
    
    // Rapprochement
    $a=GetConcerned($p_cn,$row['jr_id']);
    $r.="<TD>";
    if ( $a != null ) {
      // $r.="operation concernée ";
      
      foreach ($a as $key => $element) 
      {      
	      $r.= "<A class=\"detail\" HREF=\"javascript:modifyOperation('".$element."','".$l_sessid."')\" > ".GetInternal($p_cn,$element)."</A>";
      }//for
    }// if ( $a != null ) {
    $r.="</TD>";

    if ( $row['jr_valid'] == 'f'  ) {
      $r.="<TD> Opération annulée</TD>";
    }    else {
      // all operations can be removed either by setting to 0 the amount
      // or by writing the opposite operation if the period is closed
      $r.="<TD>";
      // cancel operation
      $r.=sprintf('<input TYPE="BUTTON" VALUE="%s" onClick="cancelOperation(\'%s\',\'%s\',\'%s\')">',
		  "Annuler",$row['jr_grpt_id'],$l_sessid,$p_jrn);
      $r.="</TD>";
    } // else
    //document
    $r.="<TD>".sprintf('<A class="detail" HREF="show_pj.php?jrn=%s&jr_grpt_id=%s">%s</A>',
		       $p_jrn,
		       $row['jr_grpt_id'],
		       $row['jr_pj_name'])."</TD>";
    
    // end row
    $r.="</tr>";
    
  }
  $r.="</table>";
  
return array ($count,$r);
}



/*!   InsertStockGoods($p_cn,$j_id,$a_good[$i],$a_quant[$i],'c');
 **************************************************
 *\brief  Insert data into stock_goods,
 *        
 * \param  $p_cn database connection
 * 
 * \param $p_j_id the j_id
 * \param $p_goods the goods
 * \param $p_quant  quantity
 * \param $p_type c for credit or d for debit
 *
 * \return none
 * \note Link to jrn gives the date
 */
function InsertStockGoods($p_cn,$p_j_id,$p_good,$p_quant,$p_type)
{
  echo_debug('user_common.php',__LINE__,"function InsertStockGoods($p_cn,$p_j_id,$p_good,$p_quant,$p_type)");
  // Retrieve the good account for stock
  $code_marchandise=GetFicheAttribut($p_cn,$p_good,ATTR_DEF_STOCK);
  $p_good=FormatString($p_good);
  $sql="select f_id from vw_poste_qcode where j_qcode=upper('$p_good')";
  $Res=ExecSql($p_cn,$sql);
  $r=pg_fetch_array($Res,0);
  $f_id=$r['f_id'];
  $Res=ExecSql($p_cn,"insert into stock_goods (
                            j_id,
                            f_id,
                            sg_code, 
                            sg_quantity,
                             sg_type ) values (
                            $p_j_id,
                            $f_id,
                            '$code_marchandise',
                            $p_quant, '$p_type') 
                     ");
 return $Res;
}
/*!   withStock($p_cn,$p_f_id)
 **************************************************
 *\brief  return true if we manage stock for it
 *          value is stored in attr_value
 *        
 * parm : 
 *	- $p_cn database connection
 *      - $p_f_id fiche.f_id
 * gen :
 *	- none
 * return:
 *       none
 */
function withStock($p_cn,$p_f_id)
{
  $a=getFicheAttribut($p_cn,$p_f_id, ATTR_DEF_STOCK);
  if ( $a == "1" ) return true;
  return false;

}
/*!    VerifyOperationDate ($p_cn,$p_user,$p_date) 
 **************************************************
 *\brief  Verify if 
 *    the date is a valid date
 *    the date is in the default period
 *    the period is not closed
 *        
 * parm : 
 *	- db connection
 *      - user
 *      - date
 * gen :
 *	- none
 * return:
 *     - null if error or date if ok
 */
function VerifyOperationDate($p_cn,$p_periode,$p_date) {

  // Verify the date
  if ( isDate($p_date) == null ) { 
	  echo_error("Invalid date $p_date");
	  echo_debug('user_common.php',__LINE__,"Invalid date $p_date");
	  echo "<SCRIPT> alert('INVALID DATE $p_date !!!!');</SCRIPT>";
	  return null;
		}
// userPref contient la periode par default
    list ($l_date_start,$l_date_end)=GetPeriode($p_cn,$p_periode);

    // Date dans la periode active
    echo_debug ("date start periode $l_date_start date fin periode $l_date_end date demandée $p_date");
    if ( cmpDate($p_date,$l_date_start)<0 || 
	 cmpDate($p_date,$l_date_end)>0 )
      {
		  $msg="Not in the active periode please change your preference";
			echo_error($msg); echo_error($msg);	
			echo "<SCRIPT>alert('$msg');</SCRIPT>";
			return null;
      }
    // Periode fermée 
    if ( PeriodeClosed ($p_cn,$p_periode)=='t' )
      {
		$msg="This periode is closed please change your preference";
		echo_error($msg); echo_error($msg);	
		echo "<SCRIPT>alert('$msg');</SCRIPT>";
		return null;
      }
    return $p_date;
}

/*!
 **************************************************
 *\brief   Insert into jrn_rapt the concerned operations
 *        
 * 
 * \param $p_cn database connection
 * \param $jr_id (jrn.jr_id) => jrn_rapt.jr_id
 * \param $jr_id2 (jrn.jr_id) => jrn_rapt.jra_concerned
 *
 * \return none
 *
 */
function InsertRapt($p_cn,$jr_id,$jr_id2) {
  if ( isNumber($jr_id)  == 0 ||  isNumber($jr_id2) == 0 )
    {
      echo_error(" InsertRapt : invalid jr_id $jr_id, jr_id2 $jr_id2");
      echo_debug('user_common.php',__LINE__," InsertRapt : invalid jr_id $jr_id, jr_id2 $jr_id2");
      return false;
    }
  // verify if exists
  if ( CountSql($p_cn,"select jra_id from jrn_rapt where jra_concerned=$jr_id and jr_id=$jr_id2
                   union
                 select jra_id from jrn_rapt where jra_concerned=$jr_id2 and jr_id=$jr_id ") ==0) 
    {
      // Ok we can insert 
      $Res=ExecSql($p_cn,"insert into jrn_rapt(jr_id,jra_concerned) values ($jr_id,$jr_id2)");
    }
  return true;
}
/*!   DeleteRapt($p_cn,$jr_id,$jr_id2)
 **************************************************
 *\brief   Insert into jrn_rapt the concerned operations
 *        
 * parm : 
 * \param $p_cn database connection
 * \param $jr_id (jrn.jr_id) => jrn_rapt.jr_id
 * \param $jr_id2 (jrn.jr_id) => jrn_rapt.jra_concerned
 * 
 * \return none
 */
function DeleteRapt($p_cn,$jr_id,$jr_id2) {
  echo_debug('user_common.php',__LINE__,"DeleteRapt($p_cn,$jr_id,$jr_id2) ");
  if ( isNumber($jr_id)  == 0 or 
       isNumber($jr_id2) == 0 )
    {
      echo_error(" InsertRapt : invalid jr_id jr_id = $jr_id jr_id2 = $jr_id2");
      return;
    }
  // verify if exists
  if ( CountSql($p_cn,"select jra_id from jrn_rapt where jra_concerned=$jr_id and jr_id=$jr_id2
                   union
                 select jra_id from jrn_rapt where jra_concerned=$jr_id2 and jr_id=$jr_id ") !=0) 
    {
      // Ok we can insert 
      $Res=ExecSql($p_cn,"delete from jrn_rapt where (jra_concerned=$jr_id2 and jr_id=$jr_id) or 
                               (jra_concerned=$jr_id and jr_id=$jr_id2) ");
    }
}

/*!   GetConcerned (p_cn ,jr_id)
 **************************************************
 *\brief   Return an array of the concerned operation
 *        
 *  
 *\param database connection
 *\param      jrn.jr_id
 * \return array if something is found or null
 */
function GetConcerned ($p_cn, $jr_id) {
$sql=" select jr_id as cn from jrn_rapt where jra_concerned=$jr_id
      union
       select jra_concerned as cn from jrn_rapt where jr_id=$jr_id";
 $Res=ExecSql($p_cn,$sql);

 // If nothing is found return null
 $n=pg_NumRows($Res);

 if ($n ==0 ) return null;

 // put everything in an array
 for ($i=0;$i<$n;$i++) {
   $l=pg_fetch_array($Res,$i);
   $r[$i]=$l['cn'];
 }
 return $r;
}
/*!   GetGrpt($p_cn,$p_jr_id)
 **************************************************
 *\brief   Return the jr_grpt_id from jrn where
 *            jr_id = $p_jr_id
 *        
 * parm : 
 *	- $p_jr_id jrn.jr_id
 *      - $p_cn database connection
 * gen :
 *	- none
 * return:
 *      - return the jrn.jr_grpt_id or null 
 */
function  GetGrpt($p_cn,$p_jr_id)
{
  $Res=ExecSql($p_cn,"select jr_grpt_id from jrn where jr_id=".$p_jr_id);
  if ( pg_NumRows($Res) == 0 ) {
    return null;
  }
  $r=pg_fetch_array($Res,0);
  return $r['jr_grpt_id'];
}
/*!   UpdateComment ($p_cn,$p_jr_id,$p_comment)
 **************************************************
 *\brief  Update comment in jrn 
 *         
 * parm : 
 *	- database conn.
 *              -  jrn.jr_id
 *              - comment
 * gen :
 *	- none
 * return:
 *              - none
 */
function UpdateComment ($p_cn,$p_jr_id,$p_comment) {
  $p_comment=FormatString($p_comment);
  $Res=ExecSql($p_cn,"update jrn set jr_comment='".$p_comment."'
                               where jr_id = $p_jr_id"); 

}

/*!   isValid ($p_cn, $p_grpt_id
 **************************************************
 *\brief   test if a jrn op is valid
 *        
 * parm : 
 *	- db connection 
 *      - p_grpt_id
 * gen :
 *	- none
 * return:
 *        1 is valid
 *        0 is not valid
 */
function isValid ($p_cn,$p_grpt_id) {
  $Res=ExecSql($p_cn,"select jr_valid from jrn where jr_grpt_id=$p_grpt_id");

  if ( ( $M = pg_NumRows($Res)) == 0 ) return 0;

  $a=pg_fetch_array($Res,0);

  if ( $a['jr_valid'] == 't') return 1;
  if ( $a['jr_valid'] == 'f') return 0;

  echo_error ("Invalid result = ".$a['result']);


}

/*!    jrn_navigation_bar
 **************************************************
 *\brief  
 *     Create a navigation_bar (pagesize)
 *        
 * \param $p_offset first record number  
 * \param $p_line total of returned row
 * \param $p_size current g_pagesize user's preference
 * \param $p_page number of the page where the user is 
 *
 * \return   string with the nav. bar
 */
function jrn_navigation_bar($p_offset,$p_line,$p_size=0,$p_page=1)
{
  echo_debug('user_common',__LINE__,"function jrn_navigation_bar($p_offset,$p_line,$p_size=0,$p_page=1)");
  // if the pagesize is unlimited return ""
  // in that case there is no nav. bar
  if ( $_SESSION['g_pagesize'] == -1  ) return "";
  if ( $p_size==0) {
    $p_size= $_SESSION['g_pagesize'];
  }
  // if there is no row return an empty string
  if ( $p_line == 0 ) return "";

  // Clean url, cut away variable coming frm here
  $url=cleanUrl();
  // action to clean
  $url=str_replace('&p_action=delete','',$url);

  // compute max of page
  $nb_page=($p_line-($p_line%$p_size))/$p_size;
  echo_debug('user_common',__LINE__,"nb_page = $nb_page");
  // if something remains
  if ( $p_line % $p_size != 0 ) $nb_page+=1;

  // if max page == 1 then return a empty string
  if ( $nb_page == 1) return "";

  $r="";
  // previous
  if ($p_page !=1) {
    $e=$p_page-1;
    $step=$p_size;
    $offset=($e-1)*$step;

    $r='<A class="mtitle" href="'.$_SERVER['PHP_SELF']."?".$url."&offset=$offset&step=$step&page=$e&size=$step".'">';
    //$r.="Pr&eacute;c&eacute;dent";
    $r.='<INPUT TYPE="IMAGE" width="12" SRC="image/go-previous.png">';
    $r.="</A>&nbsp;&nbsp;";
  }
  //----------------------------------------------------------------------
  // Create a partial bar 
  // if current page < 11 show 1 to 20 
  // otherwise            show $p_page -10 to $p_page + 10
  //----------------------------------------------------------------------
  $start_bar=($p_page < 11 )?1:$p_page-10;
  $end_bar  =($p_page < 11 )?20:$p_page+10;
  $end_bar  =($end_bar > $nb_page )?$nb_page:$end_bar;

  // Create the bar
  for ($e=$start_bar;$e<=$end_bar;$e++) {
    // do not included current page
    if ( $e != $p_page ) {
      $step=$p_size;
    $offset=($e-1)*$step;
    $go=sprintf($_SERVER['PHP_SELF']."?".$url."&offset=$offset&step=$step&page=$e&size=$step");
    $r.=sprintf('<A class="mtitle" HREF="%s" CLASS="one">%d</A>&nbsp;',$go,$e);
    } else {
      $r.="<b> [ $e ] </b>";
    } //else
  } //for
  // next
  
  if ($p_page !=$nb_page) {
    // If we are not at the last page show the button next
    $e=$p_page+1;
    $step=$p_size;
    $offset=($e-1)*$step;

    $r.='&nbsp;<A class="mtitle" href="'.$_SERVER['PHP_SELF']."?".$url."&offset=$offset&step=$step&page=$e&size=$step".'">';
    //$r.="Suivant";
    $r.='<INPUT TYPE="IMAGE" width="12" SRC="image/go-next.png">';
    $r.="</A>";
  }


  return $r;
}


/*!\brief Verify that a fiche has a valid ledger. It must be verify before
 *        entering data into jrnx. Called from the form_verify_input
 * \param $p_cn database connx
 * \param $qcode the quick_code
 * \return null if an error occurs + a alert message in javascript
 *         otherwise 1
 */
function CheckPoste($p_cn,$qcode)
{
    // check if the  ATTR_DEF_ACCOUNT is set
    $poste=GetFicheAttribut($p_cn,$qcode,ATTR_DEF_ACCOUNT);
    echo_debug('poste.php',__LINE__,"poste value = ".$poste."size = ".strlen(trim($poste)));
    if ( $poste == null ) 
      {	
	$msg="La fiche ".$qcode." n\'a pas de poste comptable";
	echo_error($msg); echo_debug('poste.php',__LINE__,$msg);	
	echo "<SCRIPT>alert('$msg');</SCRIPT>";
	return null;
	
      }
    if ( strlen(trim($poste))==0 )
      {
	$msg="La fiche ".$qcode." n\'a pas de poste comptable";
	echo_error($msg); echo_debug('poste.php',__LINE__,$msg);		
	echo "<SCRIPT>alert('$msg');</SCRIPT>";
	return null;
      }
    // Check that the account exists
    if ( CountSql($p_cn,
		  "select * from tmp_pcmn where pcm_val=$poste") == 0 )
      {
      $msg=" Le poste comptable $poste de la fiche ".$qcode." n\'existe pas, il faudra le créer manuellement dans le module comptabilité, menu : avancé->plan comptable";
	echo_error($msg); echo_debug('poste.php',__LINE__,$msg);		
	echo "<SCRIPT>alert('$msg');</SCRIPT>";
	return null; 

      }
    return  1; 
}
/*! 
 * \brief Clean the url, remove the $_GET offset,step, page and size
 * \param none
 *
 * \return the cleaned url
 */

function CleanUrl()
{
  // Compute the url
  $url="";
  $and="";
  $get=$_GET;
  if ( isset ($get) ) {
    foreach ($get as $name=>$value ) {
      // we clean the parameter offset, step, page and size
      if (  ! in_array($name,array('offset','step','page','size'))) {
	$url.=$and.$name."=".$value;
	$and="&";
      }// if
    }//foreach
  }// if
return $url;
}
?>
