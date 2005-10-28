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

/* function GetTvaRate
 **************************************************
 * Purpose : Return the rate of the p_tva_id
 *        
 * parm : 
 * -  p_cn database connection
 *	- p_tva_id tva.tva_id
 * gen :
 *	- none
 * return:
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
/* function ComputeVat($p_cn,$a_fiche,$a_quant,$a_price,$ap_vat)
 **************************************************
 * Purpose : Compute the vat,
 *           the fiche.f_id are in a_fiche
 *           the quantity in a_quant
 *           
 *        
 * parm : 
 *	- database connection
 *      - fiche id array
 *      1- quantity array 
 *      - price array 
 *      - $ap_vat Array of tva id
 * gen :
 *	-
 * return: array
 *       a[tva_id] =  amount vat
 */
function ComputeVat($p_cn,	$a_fiche,$a_quant,$a_price,$ap_vat ) {
echo_debug(__FILE__,__LINE__,"ComputeVat $a_fiche $a_quant $a_price");
 foreach ( $a_fiche as $t=>$el) {
   echo_debug(__FILE__,__LINE__,"t $t e $el");
 }
 $r=null;
// foreach goods 
//--
 foreach ( $a_fiche as $idx=>$element) {
   echo_debug ("idx $idx element $element");
  // if the card id is null or empty 
    if ( isNumber($element)==0 
	 or strlen(trim($element))==0) continue;

    // Get the tva_id
    if ( $ap_vat != null and
	 isNumber($ap_vat[$idx])== 1)
      $tva_id=$ap_vat[$idx];
    else
      $tva_id=GetFicheAttribut($p_cn,$a_fiche[$idx],ATTR_DEF_TVA);
    
    if ( $tva_id == 'Unknown' ) continue;
    // for each fiche find the tva_rate and tva_id
    $a_vat=GetTvaRate($p_cn,$tva_id);
    
    // Get the attribut price of the card(fiche)
    if ( $a_vat != null  and  $a_vat['tva_id'] != "" ) 
   {  $a=$a_vat['tva_id'];
      $vat_amount=$a_price[$idx]*$a_vat['tva_rate']*$a_quant[$idx];
      $r[$a]=isset ( $r[$a] )?$r[$a]+$vat_amount:$vat_amount;
    } 
    
 }
 echo_debug(__FILE__,__LINE__," return $r");
 return $r;
 
 
}



/* function GetTvaPoste($p_cn,$tva_id,$p_type);
 **************************************************
 * Purpose : Get the account of tva_rate.tva_poste
 *        return the credit or the debit account
 * parm : connection
 *	-     tva_rate.tva_id
 *        type ( d or credit)
 * gen :
 *	-
 * return:
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




/* function InsertJrnx($p_cn,$p_type,$p_user,$p_jrn,$p_poste,$p_date,$p_amount,$p_grpt,$p_periode
 **************************************************
 * Purpose : Insert into the table Jrn
 *        
 * parm : 
 *	- $p_cn database connection
 *  - $p_type debit or credit
 *  - $p_user the current user
 *  - $p_jrn the current 'journal' (folder)
 *  - $p_poste the account
 *  - $p_date
 *  - $p_amount amount to insert
 *  - $p_periode the concerned periode
 * gen :
 *	- none
 * return:
 *   - nothing
 */

function InsertJrnx($p_cn,$p_type,$p_user,$p_jrn,$p_poste,$p_date,$p_amount,$p_grpt,$p_periode)
{
  echo_debug ("InsertJrnx param 
	    type = $p_type p_user $p_user p_date $p_date p_poste $p_poste p_amount $p_amount p_grpt = $p_grpt p_periode = $p_periode");
  $debit=($p_type=='c')?'false':'true';

  // if negative value the operation is inversed
  if ( $p_amount < 0 ) {
    $debit=($debit=='false')?'true':'false';
  }

  $sql=sprintf("insert into jrnx (j_date,j_montant, j_poste,j_grpt, j_jrn_def,j_debit,j_tech_user,j_tech_per)
			values (to_date('%s','DD.MM.YYYY'),abs(%.2f),%d,%d,%d,%s,'%s',%d)",
	       $p_date,round($p_amount,2),$p_poste,$p_grpt,$p_jrn,$debit,$p_user,$p_periode);
  echo_debug(__FILE__,__LINE__,"InsertJrnx $sql");
  $Res=ExecSql($p_cn,$sql);
  if ( $Res==false) return $Res;
  return GetSequence($p_cn,'s_jrn_op');

}
/* function InsertJrn($p_cn,$p_date,$p_jrn,$p_comment,$p_amount,$p_grpt,$p_periode
 **************************************************
 * Purpose : Insert into the table Jrnx
 *        
 * parm : 
 *	- $p_cn database connection
 *  - $p_date date
 *  - $p_jrn the current 'journal' (folder)
 *  - $p_poste the account
 *  - $p_amount amount to insert
 *  - $p_periode the concerned periode
 *  - $p_comment comment
 * gen :
 *	- none
 * return:
 *   - nothing
 */

function InsertJrn($p_cn,$p_date,$p_echeance,$p_jrn,$p_comment,$p_amount,$p_grpt,$p_periode)
{
	echo_debug (__FILE__,__LINE__,"InsertJrn param 
	    p_date $p_date p_poste $p_comment p_amount $p_amount p_grpt = $p_grpt p_periode = $p_periode p_echeance = $p_echeance
comment = $p_comment");
	$p_comment=FormatString($p_comment);

	if ( $p_echeance == "" or $p_echeance==null) {
		$p_echeance='null';
	} else {
		$p_echeance=sprintf("to_date('%s','DD.MM.YYYY')",$p_echeance);
	}
	$sql=sprintf("insert into jrn (jr_def_id,jr_montant,jr_comment,jr_date,jr_ech,jr_grpt_id,jr_tech_per)
	         values ( %d,abs(%.2f),'%s',to_date('%s','DD.MM.YYYY'),%s,%d,%d)",
		     $p_jrn, round($p_amount,2),$p_comment,$p_date,$p_echeance,$p_grpt,$p_periode);
	echo_debug(__FILE__,__LINE__,"InsertJrn $sql");
	$Res=ExecSql($p_cn,$sql);				 
	if ( $Res == false)  return false;
	return GetSequence($p_cn,'s_jrn');
}
/* function ListJrn($p_cn,$p_jrn,$p_wherel)
 **************************************************
 * Purpose : show all the lines of the asked jrn
 *        
 * parm : 
 *	- $p_cn database connection
 *      - $p_jrn jrn_id jrn.jrn_def_id
 *      - $p_sql the sql query (where clause)
 * gen :
 *	- none
 * return:
 * 
 */
function ListJrn($p_cn,$p_jrn,$p_where="",$p_array=null,$p_value=0)
{

  include_once("central_inc.php");
  $limit=($_SESSION['g_pagesize']!=-1)?" LIMIT ".$_SESSION['g_pagesize']:"";
  $offset=($_SESSION['g_pagesize']!=-1)?" OFFSET ".$p_value:"";
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
			 order by jr_date_order asc";
  }
  if ( $p_array != null ) {
    // Construction Query 
    foreach ( $p_array as $key=>$element) {
      ${"l_$key"}=$element;
      echo_debug ("l_$key $element");
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
    if ( ereg("^[0-9]+$", $l_s_montant) || ereg ("^[0-9]+\.[0-9]+$", $l_s_montant) ) {
    $sql.=$l_and."  jr_montant $l_mont_sel $l_s_montant";
    $l_and=" and ";
    }
    // date
    if ( isDate($l_date_start) != null ) {
      $sql.=$l_and." jr_date >= to_date('".$l_date_start."','DD.MM.YYYY')";
      $l_and=" and ";
    }
    if ( isDate($l_date_end) != null ) {
      $sql.=$l_and." jr_date <= to_date('".$l_date_end."','DD.MM.YYYY')";
      $l_and=" and ";
    }
    // comment
    $l_s_comment=FormatString($l_s_comment);
    if ( $l_s_comment != null ) {
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
             from jrnx where j_poste = $l_poste)  ";
      $l_and=" and ";
    }
    // if not admin check filter 
    $User=new cl_user(DbConnect());
    $User->Check();
    if ( $User->admin == 0 ) {
      $sql.=$l_and." jr_def_id in ( select uj_jrn_id ".
	" from user_sec_jrn where ".
	" uj_login='".$_SESSION['g_user']."'".
	" and uj_priv in ('R','W'))";
    }
    $sql.=" order by jr_date_order asc";
  }// p_array != null
  // Count 
  $count=CountSql($p_cn,$sql);
  // Add the limit 
  $sql.=$limit.$offset;

  // Execute SQL stmt
  $Res=ExecSql($p_cn,$sql);
  $r="";
  $r.=JS_VIEW_JRN_DETAIL;
  $r.=JS_VIEW_JRN_CANCEL;
  $r.=JS_VIEW_JRN_MODIFY;

  $Max=pg_NumRows($Res);

  if ($Max==0) return array(0,"No row selected");

  $r.="<TABLE width=\"100%\">";
  $l_sessid=$_REQUEST['PHPSESSID'];
  $r.="<tr class=\"even\">";
 $r.="<th> Internal </th>";
  $r.="<th> Date </th>";
  $r.="<th> Echéance </th>";
  $r.="<th> Description</th>";
  $r.="<th> Montant </th>";
  $r.="<th>Op. Concernée</th>";
  $r.="<th>Document</th>";
  $r.="</tr>";

  for ($i=0; $i < $Max;$i++) {
    $row=pg_fetch_array($Res,$i);
    
    if ( $i % 2 == 0 ) $tr='<TR class="odd">'; 
		else $tr='<TR class="even">';
    $r.=$tr;
    //internal code
	// button  modify
    $r.="<TD>";
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
    // Get the jrn type
    $jrn_prop=GetJrnProp($p_cn,$row['jrn_def_id'],1);  
    $positive=0;
    if ( $positive=1  &&  $jrn_prop['jrn_def_type'] == 'FIN' ) {
      $positive = CountSql($p_cn,"select * from jrn inner join jrnx on jr_grpt_id=j_grpt ".
			   " where jr_id=".$row['jr_id']." and (j_poste like '55%' or j_poste like '57%' )".
			   " and j_debit='f'");
    }
    $r.="<TD align=\"right\">";
    $r.=( $positive != 0 )?"<font color=\"red\">  - ".sprintf("%8.2f",$row['jr_montant'])."</font>":sprintf("%8.2f",$row['jr_montant']);
    $r.="</TD>";
    
    // Rapt
    $a=GetConcerned($p_cn,$row['jr_id']);
    $r.="<TD>";
    if ( $a != null ) {
      // $r.="operation concernée ";
      
      foreach ($a as $key => $element) {
	$r.= "<A class=\"detail\" HREF=\"javascript:viewDetail('".GetGrpt($p_cn,$element)."','$l_sessid')\" > ".GetInternal($p_cn,$element)."</A>";
	
      }//for
    }// if ( $a != null ) {
    $r.="</TD>";
    //$l=user_jrn.php?action=update&line=91
    
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
    $r.="<TD>".sprintf('<A class="detail" HREF="show_document.php?jrn=%s&jr_grpt_id=%s">%s</A>',
		       $p_jrn,
		       $row['jr_grpt_id'],
		       $row['jr_pj_name'])."</TD>";
    
    // end row
    $r.="</tr>";
    
  }
  $r.="</table>";
  
return array ($count,$r);
}



/* function InsertStockGoods($p_cn,$j_id,$a_good[$i],$a_quant[$i],'c');
 **************************************************
 * Purpose : Insert data into stock_goods,
 *        
 * parm : 
 *	- $p_cn database connection
 *      - $p_j_id the j_id
 *      - $p_goods the goods
 *      - $p_quant  quantity
 *      - $p_type c for credit or d for debit
 * gen :
 *	- none
 * return:
 *       none
 */
function InsertStockGoods($p_cn,$p_j_id,$p_good,$p_quant,$p_type)
{
  // Retrieve the good account for stock
  $code_marchandise="select av_text from 
                  jnt_fic_att_value 
                  natural join attr_value
                    where
                  ad_id=".ATTR_DEF_STOCK." 
                   and f_id=$p_good";
 $Res=ExecSql($p_cn,$code_marchandise);
 if ( pg_NumRows($Res) == 0 ) {
   $l_code='null';
 }else {
   $r=pg_fetch_array($Res,0);
   $l_code=$r['av_text'];
 }
 $Res=ExecSql($p_cn,"insert into stock_goods (
                            j_id,
                            f_id,
                            sg_code, 
                            sg_quantity,
                             sg_type ) values (
                            $p_j_id,
                            $p_good,
                            '$l_code',
                            $p_quant, '$p_type') 
                     ");
 return $Res;
}
/* function withStock($p_cn,$p_f_id)
 **************************************************
 * Purpose : return true if we manage stock for it
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
/* function  VerifyOperationDate ($p_cn,$p_user,$p_date) 
 **************************************************
 * Purpose : Verify if 
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
	  echo_debug(__FILE__,__LINE__,"Invalid date $p_date");
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

/* function InsertRapt($p_cn,$jr_id,$jr_id2)
 **************************************************
 * Purpose :  Insert into jrn_rapt the concerned operations
 *        
 * parm : 
 *	- p_cn database connection
 *      - jr_id (jrn.jr_id) => jrn_rapt.jr_id
 *      - jr_id2 (jrn.jr_id) => jrn_rapt.jra_concerned
 * gen :
 *	- none
 * return:
 *      - none
 */
function InsertRapt($p_cn,$jr_id,$jr_id2) {
  if ( isNumber($jr_id)  == 0 or 
       isNumber($jr_id2) == 0 )
    {
      echo_error(" InsertRapt : invalid jr_id $jr_id, jr_id2 $jr_id2");
      echo_debug(__FILE__,__LINE__," InsertRapt : invalid jr_id $jr_id, jr_id2 $jr_id2");
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
/* function DeleteRapt($p_cn,$jr_id,$jr_id2)
 **************************************************
 * Purpose :  Insert into jrn_rapt the concerned operations
 *        
 * parm : 
 *	- p_cn database connection
 *      - jr_id (jrn.jr_id) => jrn_rapt.jr_id
 *      - jr_id2 (jrn.jr_id) => jrn_rapt.jra_concerned
 * gen :
 *	- none
 * return:
 *      - none
 */
function DeleteRapt($p_cn,$jr_id,$jr_id2) {
  echo_debug(__FILE__,__LINE__,"DeleteRapt($p_cn,$jr_id,$jr_id2) ");
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

/* function GetConcerned (p_cn ,jr_id)
 **************************************************
 * Purpose :  Return an array of the concerned operation
 *        
 * parm : 
 *	- database connection
 *      - jrn.jr_id
 * gen : 
 *	- none
 * return:
 *      - array if something is found
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
/* function GetGrpt($p_cn,$p_jr_id)
 **************************************************
 * Purpose :  Return the jr_grpt_id from jrn where
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
/* function UpdateComment ($p_cn,$p_jr_id,$p_comment)
 **************************************************
 * Purpose : Update comment in jrn 
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

/* function isValid ($p_cn, $p_grpt_id
 **************************************************
 * Purpose :  test if a jrn op is valid
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

/* function  jrn_navigation_bar
 **************************************************
 * Purpose : 
 *     Create a navigation_bar (pagesize)
 *        
 * parm : 
 *	- p_current first record number
 *      - p_line total of returned row
 *      - p_size current g_pagesize
 *      - p_page number of the page
 * gen :
 *	-none
 * return:
 *     string
 */
function jrn_navigation_bar($p_offset,$p_line,$p_size,$p_page=1)
{
  // if the pagesize is unlimited return ""
  // in that case there is no nav. bar
  if ( $_SESSION['g_pagesize'] == -1 ) return "";

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
  // compute max of page
  $nb_page=($p_line-($p_line%$p_size))/$p_size;

  // if something remains
  if ( $p_line % $p_size != 0 ) $nb_page+=1;

  // if max page == 1 then return a empty string
  if ( $nb_page == 1) return "";

  $r="";
  // previous
  if ($p_page !=1) {
    $e=$p_page-1;
    $step=$_SESSION['g_pagesize'];
    $offset=($e-1)*$step;

    $r='<A class="one" href="'.$_SERVER['PHP_SELF']."?".$url."&offset=$offset&step=$step&page=$e&size=$step".'">';
    $r.="Pr&eacute;c&eacute;dent";
    $r.="</A>&nbsp;&nbsp;";
  }

  // Create the bar
  for ($e=1;$e<=$nb_page;$e++) {
    // do not included current page
    if ( $e != $p_page ) {
    $step=$_SESSION['g_pagesize'];
    $offset=($e-1)*$step;
    $go=sprintf($_SERVER['PHP_SELF']."?".$url."&offset=$offset&step=$step&page=$e&size=$step");
    $r.=sprintf('<A HREF="%s" CLASS="one">%d</A>&nbsp;',$go,$e);
    } else {
      $r.="<b> $e </b>";
    } //else
    // add a break if e > 80
    if ( $e % 57 == 0 ) {
      $r.="<br>";
    }
  } //for
  // next
  if ($p_page !=$nb_page) {
    $e=$p_page+1;
    $step=$_SESSION['g_pagesize'];
    $offset=($e-1)*$step;

    $r.='&nbsp;<A class="one" href="'.$_SERVER['PHP_SELF']."?".$url."&offset=$offset&step=$step&page=$e&size=$step".'">';
    $r.="Suivant";
    $r.="</A>";
  }


  return $r;
  
}
